<?php

namespace App\Http\Controllers\Asn;

use App\Enums\StatusTugas;
use App\Http\Controllers\Controller;
use App\Models\Logbook;
use App\Models\MahasiswaProfile;
use App\Models\Skill;
use App\Models\Tugas;
use App\Models\TugasAnggota;
use App\Services\PenugasanService;
use DomainException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class TugasController extends Controller
{
    public function __construct(protected PenugasanService $penugasanService) {}

    public function checkTugasAktif(Request $request)
    {
        $mahasiswaIds = $request->input('mahasiswa_ids', []);

        if (empty($mahasiswaIds)) {
            return response()->json(['has_active_task' => false, 'nama_mahasiswa' => []]);
        }

        $idKetuaAktif = Tugas::query()->where('status', '!=', StatusTugas::Selesai)->whereIn('mahasiswa_profile_id', $mahasiswaIds)->pluck('mahasiswa_profile_id');

        $idAnggotaAktif = TugasAnggota::query()->where('status', 'diterima')->whereIn('mahasiswa_profile_id', $mahasiswaIds)->whereHas('tugas', fn($q) => $q->where('status', '!=', StatusTugas::Selesai))->pluck('mahasiswa_profile_id');

        $idMahasiswaAktif = $idKetuaAktif->merge($idAnggotaAktif)->unique();

        $namaMahasiswa = MahasiswaProfile::whereIn('id', $idMahasiswaAktif)->with('user')->get()->pluck('user.name')->values();

        return response()->json([
            'has_active_task' => $idMahasiswaAktif->isNotEmpty(),
            'nama_mahasiswa' => $namaMahasiswa,
        ]);
    }

    public function storeTugas(Request $request)
    {
        $validated = $request->validate(
            [
                'judul' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'deadline' => 'required|date|after:now',
                'skills' => 'nullable|array',
                'skills.*' => 'exists:skills,id',
                'file' => 'nullable|array',
                'file.*' => 'file|max:10240|mimes:doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,webp,bmp,pdf,zip',
                'penugasan_langsung' => 'nullable|boolean',
                'mahasiswa_ids' => 'required_if:penugasan_langsung,1|nullable|array|min:1',
                'mahasiswa_ids.*' => 'exists:mahasiswa_profiles,id',
            ],
            [
                'required' => ':attribute wajib diisi.',
                'string' => ':attribute harus berupa teks.',
                'max' => ':attribute maksimal :max karakter.',
                'date' => 'Format :attribute tidak valid.',
                'after' => ':attribute harus lebih besar dari waktu sekarang.',
                'array' => 'Format :attribute tidak valid.',
                'exists' => ':attribute yang dipilih tidak terdaftar.',
                'file' => 'File yang diunggah tidak valid.',
                'file.*.file' => 'Salah satu file yang diunggah tidak valid.',
                'file.*.max' => 'Ukuran setiap file maksimal 10MB.',
                'file.*.mimes' => 'Format file tidak didukung. Gunakan format: doc, docx, xls, xlsx, ppt, pptx, jpg, jpeg, png, gif, svg, webp, bmp, pdf, atau zip.',
                'min' => ':attribute minimal :min item.',
                'boolean' => 'Format :attribute tidak valid.',
                'required_if' => 'Pilih minimal 1 mahasiswa untuk ditugaskan langsung.',
            ],
            [
                'judul' => 'Judul Tugas',
                'deskripsi' => 'Deskripsi Tugas',
                'deadline' => 'Deadline',
                'skills' => 'Skill',
                'file' => 'File Referensi',
                'file.*' => 'File Referensi',
                'penugasan_langsung' => 'Penugasan Langsung',
                'mahasiswa_ids' => 'Mahasiswa',
            ],
        );

        $isPenugasanLangsung = $request->boolean('penugasan_langsung');

        $mahasiswaIds = $isPenugasanLangsung ? array_values(array_unique($validated['mahasiswa_ids'] ?? [])) : [];

        // Proses penyimpanan tugas
        $tugas = DB::transaction(function () use ($validated, $isPenugasanLangsung, $mahasiswaIds) {
            if ($isPenugasanLangsung) {
                $this->penugasanService->lockDanValidasiMahasiswaAktif($mahasiswaIds);
            }

            $tugas = Tugas::create([
                'asn_id' => Auth::id(),
                'judul' => $validated['judul'],
                'deskripsi' => $validated['deskripsi'],
                'deadline' => $validated['deadline'],
                'status' => $isPenugasanLangsung ? StatusTugas::Diambil : StatusTugas::Tersedia,
                'sumber' => $isPenugasanLangsung ? 'ditugaskan_asn' : 'mandiri',
                'diambil_at' => $isPenugasanLangsung ? now() : null,
                'periode_magang_id' => null,
                'mahasiswa_profile_id' => $isPenugasanLangsung ? $mahasiswaIds[0] : null,
            ]);

            // Menyimpan skill tugas
            if (!empty($validated['skills'])) {
                $tugas->skills()->attach($validated['skills']);
            }

            if ($isPenugasanLangsung) {
                $anggotaTambahan = array_slice($mahasiswaIds, 1);

                // Menyimpan anggota tambahan
                foreach ($anggotaTambahan as $mahasiswaProfileId) {
                    $tugas->anggota()->create([
                        'mahasiswa_profile_id' => $mahasiswaProfileId,
                        'status' => 'diterima',
                        'diundang_oleh' => null,
                        'sumber' => 'ditugaskan_asn',
                    ]);
                }

                // Membuat logbook untuk setiap mahasiswa
                foreach ($mahasiswaIds as $mahasiswaProfileId) {
                    Logbook::firstOrCreate([
                        'tugas_id' => $tugas->id,
                        'mahasiswa_profile_id' => $mahasiswaProfileId,
                    ]);
                }
            }

            return $tugas;
        });

        // Menyimpan file lampiran
        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $file) {
                $path = $file->store('tugas-attachments', 'public');

                $tugas->attachments()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getClientMimeType(),
                ]);
            }
        }

        $pesan = $isPenugasanLangsung ? 'Tugas berhasil dibuat dan langsung ditugaskan ke mahasiswa terpilih.' : 'Tugas berhasil dibuat dan tersedia untuk diambil mahasiswa.';

        return redirect()->route('task-not-done')->with('success', $pesan);
    }

    public function editTugasForm($id)
    {
        try {
            $tugas = Tugas::with(['skills', 'attachments', 'mahasiswaProfile.user', 'anggota.mahasiswaProfile.user'])
                ->where('asn_id', Auth::id())
                ->findOrFail($id);
        } catch (ModelNotFoundException) {
            return redirect()->route('task-not-done')->with('error', 'Tugas tidak ditemukan atau Anda tidak memiliki akses.');
        }

        $skills = Skill::all();
        $mahasiswaList = MahasiswaProfile::with('user')->where('status', 'aktif')->get();

        return view('pages.asn.task-not-done.update', compact('tugas', 'skills', 'mahasiswaList'));
    }

    public function updateTugas(Request $request, $id)
    {
        try {
            $tugasAwal = Tugas::where('id', $id)->where('asn_id', Auth::id())->firstOrFail();
        } catch (ModelNotFoundException) {
            return redirect()->route('task-not-done')->with('error', 'Tugas tidak ditemukan atau Anda tidak memiliki akses.');
        }

        // Terapkan after:now HANYA jika nilai deadline benar-benar diubah
        $deadlineRules = ['required', 'date'];
        if ($request->filled('deadline') && strtotime((string) $request->input('deadline')) !== false) {
            $deadlineInput = Carbon::parse($request->input('deadline'));
            if (!$tugasAwal->deadline || !$deadlineInput->equalTo($tugasAwal->deadline)) {
                $deadlineRules[] = 'after:now';
            }
        }

        $validated = $request->validate(
            [
                'judul' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'deadline' => $deadlineRules,
                'skills' => 'nullable|array',
                'skills.*' => 'exists:skills,id',
                'file' => 'nullable|array',
                'file.*' => 'file|max:10240|mimes:doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,svg,webp,bmp,pdf,zip',
                'delete_attachments' => 'nullable|array',
                'delete_attachments.*' => 'exists:tugas_attachments,id',
                'sync_mahasiswa' => 'nullable|boolean',
                'mahasiswa_ids' => 'nullable|array',
                'mahasiswa_ids.*' => 'exists:mahasiswa_profiles,id',
            ],
            [
                'required' => ':attribute wajib diisi.',
                'after' => ':attribute harus lebih besar dari waktu sekarang.',
                'file.*.max' => 'Ukuran file maksimal 10MB.',
                'file.*.mimes' => 'Format file tidak didukung.',
                'delete_attachments.*.exists' => 'File lampiran yang akan dihapus tidak valid.',
            ],
            [
                'judul' => 'Judul Tugas',
                'deskripsi' => 'Deskripsi Tugas',
                'deadline' => 'Deadline',
                'file.*' => 'File Lampiran',
                'delete_attachments' => 'File Lampiran yang Dihapus',
                'mahasiswa_ids' => 'Mahasiswa',
            ],
        );

        $filesToDeleteFromStorage = [];
        $isSyncMahasiswa = $request->boolean('sync_mahasiswa') || $request->has('mahasiswa_ids');
        $mahasiswaIds = $isSyncMahasiswa ? array_values(array_unique(array_filter($validated['mahasiswa_ids'] ?? []))) : [];

        try {
            $tugas = DB::transaction(function () use ($id, $validated, $isSyncMahasiswa, $mahasiswaIds, &$filesToDeleteFromStorage) {
                if ($isSyncMahasiswa && !empty($mahasiswaIds)) {
                    $this->penugasanService->lockDanValidasiMahasiswaAktif($mahasiswaIds);
                }

                $tugas = Tugas::where('id', $id)->where('asn_id', Auth::id())->lockForUpdate()->firstOrFail();

                // A. Update Data Utama Tugas
                $tugas->update([
                    'judul' => $validated['judul'],
                    'deskripsi' => $validated['deskripsi'],
                    'deadline' => $validated['deadline'],
                ]);

                // B. Update Skill (Sync)
                if (!empty($validated['skills'])) {
                    $tugas->skills()->sync($validated['skills']);
                } else {
                    $tugas->skills()->detach();
                }

                // C. Hapus Record File Lampiran yang Dicentang
                if (!empty($validated['delete_attachments'])) {
                    $attachmentsToDelete = $tugas->attachments()->whereIn('id', $validated['delete_attachments'])->get();

                    foreach ($attachmentsToDelete as $attachment) {
                        if ($attachment->file_path) {
                            $filesToDeleteFromStorage[] = $attachment->file_path;
                        }
                        $attachment->delete();
                    }
                }

                // D. Update Penugasan Mahasiswa dengan State Machine Guard
                if ($isSyncMahasiswa) {
                    if (!empty($mahasiswaIds)) {
                        if (!$tugas->status->canModifyAssignment()) {
                            throw new DomainException('Penugasan mahasiswa tidak dapat diubah karena tugas sudah selesai.');
                        }

                        // Hanya transisikan Tersedia -> Diambil; pertahankan status lanjutan (Diambil, MenungguReview, Revisi)
                        $statusBaru = $tugas->status === StatusTugas::Tersedia ? StatusTugas::Diambil : $tugas->status;

                        $tugas->assertCanTransitionTo($statusBaru);

                        $tugas->update([
                            'mahasiswa_profile_id' => $mahasiswaIds[0],
                            'status' => $statusBaru,
                            'sumber' => 'ditugaskan_asn',
                            'diambil_at' => $tugas->diambil_at ?? now(),
                        ]);

                        $anggotaIds = array_slice($mahasiswaIds, 1);

                        $tugas->anggota()->whereNotIn('mahasiswa_profile_id', $anggotaIds)->delete();
                        Logbook::where('tugas_id', $tugas->id)->whereNotIn('mahasiswa_profile_id', $mahasiswaIds)->delete();

                        foreach ($anggotaIds as $mhsId) {
                            $tugas->anggota()->firstOrCreate(['mahasiswa_profile_id' => $mhsId], ['status' => 'diterima', 'sumber' => 'ditugaskan_asn']);
                        }

                        foreach ($mahasiswaIds as $mhsId) {
                            Logbook::firstOrCreate([
                                'tugas_id' => $tugas->id,
                                'mahasiswa_profile_id' => $mhsId,
                            ]);
                        }
                    } else {
                        // Guard state machine: hanya status Tersedia atau Diambil yang boleh di-reset ke Tersedia
                        $tugas->assertCanTransitionTo(StatusTugas::Tersedia, 'Penugasan tidak dapat dikosongkan karena tugas sudah berada pada tahap ' . $tugas->status->label() . '.');

                        $tugas->update([
                            'mahasiswa_profile_id' => null,
                            'status' => StatusTugas::Tersedia,
                            'sumber' => 'mandiri',
                            'diambil_at' => null,
                        ]);

                        $tugas->anggota()->delete();
                        Logbook::where('tugas_id', $tugas->id)->delete();
                    }
                }

                return $tugas;
            });
        } catch (ModelNotFoundException) {
            return redirect()->route('task-not-done')->with('error', 'Tugas tidak ditemukan atau Anda tidak memiliki akses.');
        } catch (DomainException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui tugas', [
                'tugas_id' => $id,
                'asn_id' => Auth::id(),
                'exception' => $e,
            ]);

            return back()->withInput()->with('error', 'Terjadi kesalahan sistem saat memperbarui tugas. Silakan coba lagi.');
        }

        // Hapus file fisik setelah transaksi DB berhasil commit
        foreach ($filesToDeleteFromStorage as $filePath) {
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
        }

        // Tambah File Lampiran Baru
        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $file) {
                $path = $file->store('tugas-attachments', 'public');

                $tugas->attachments()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getClientMimeType(),
                ]);
            }
        }

        return redirect()->route('task-not-done')->with('success', 'Tugas berhasil diperbarui.');
    }

    public function destroyTugas($id)
    {
        try {
            $tugas = Tugas::with('attachments')->where('id', $id)->where('asn_id', Auth::id())->firstOrFail();

            $filePaths = $tugas->attachments->pluck('file_path')->filter()->all();

            DB::transaction(function () use ($tugas) {
                $tugas->attachments()->delete();
                $tugas->delete();
            });

            foreach ($filePaths as $filePath) {
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }
        } catch (ModelNotFoundException) {
            return redirect()->route('task-not-done')->with('error', 'Tugas tidak ditemukan atau Anda tidak memiliki akses.');
        } catch (Throwable $e) {
            Log::error('Gagal menghapus tugas', [
                'tugas_id' => $id,
                'asn_id' => Auth::id(),
                'exception' => $e,
            ]);

            return back()->with('error', 'Terjadi kesalahan sistem saat menghapus tugas. Silakan coba lagi.');
        }

        return redirect()->route('task-not-done')->with('success', 'Tugas berhasil dihapus beserta seluruh file terkait.');
    }
}
