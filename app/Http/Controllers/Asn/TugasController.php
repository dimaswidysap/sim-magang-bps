<?php

namespace App\Http\Controllers\Asn;
use DomainException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tugas;
use App\Models\Skill;
use App\Models\Logbook;
use App\Models\TugasAnggota;
use App\Models\MahasiswaProfile;
use App\Services\PenugasanService;

class TugasController extends Controller
{
    // service
    public function __construct(protected PenugasanService $penugasanService) {}
    //
    public function checkTugasAktif(Request $request)
    {
        $mahasiswaIds = $request->input('mahasiswa_ids', []);

        if (empty($mahasiswaIds)) {
            return response()->json(['has_active_task' => false, 'nama_mahasiswa' => []]);
        }

        // Cek sebagai ketua (mahasiswa_profile_id langsung di tabel tugas)
        $idKetuaAktif = Tugas::query()->where('status', '!=', 'selesai')->whereIn('mahasiswa_profile_id', $mahasiswaIds)->pluck('mahasiswa_profile_id');

        // Cek sebagai anggota tim yang diterima
        $idAnggotaAktif = TugasAnggota::query()->where('status', 'diterima')->whereIn('mahasiswa_profile_id', $mahasiswaIds)->whereHas('tugas', fn($q) => $q->where('status', '!=', 'selesai'))->pluck('mahasiswa_profile_id');

        $idMahasiswaAktif = $idKetuaAktif->merge($idAnggotaAktif)->unique();

        $namaMahasiswa = MahasiswaProfile::whereIn('id', $idMahasiswaAktif)->with('user')->get()->pluck('user.name')->values(); // reset index array supaya rapi jadi JSON array, bukan object

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

        try {
            $tugas = DB::transaction(function () use ($validated, $isPenugasanLangsung, $mahasiswaIds) {
                // Jika penugasan langsung ASN: pastikan semua mahasiswa berstatus 'aktif' (dengan lock),
                // tanpa dibatasi kuota 1 tugas karena ASN boleh memberi lebih dari 1 tugas.
                if ($isPenugasanLangsung) {
                    $this->penugasanService->lockDanValidasiMahasiswaAktif($mahasiswaIds);
                }

                $tugas = Tugas::create([
                    'asn_id' => Auth::id(),
                    'judul' => $validated['judul'],
                    'deskripsi' => $validated['deskripsi'],
                    'deadline' => $validated['deadline'],
                    'status' => $isPenugasanLangsung ? 'diambil' : 'tersedia',
                    'sumber' => $isPenugasanLangsung ? 'ditugaskan_asn' : 'mandiri',
                    'diambil_at' => $isPenugasanLangsung ? now() : null,
                    'periode_magang_id' => null,
                    'mahasiswa_profile_id' => $isPenugasanLangsung ? $mahasiswaIds[0] : null,
                ]);

                if (!empty($validated['skills'])) {
                    $tugas->skills()->attach($validated['skills']);
                }

                if ($isPenugasanLangsung) {
                    $anggotaTambahan = array_slice($mahasiswaIds, 1);

                    foreach ($anggotaTambahan as $mahasiswaProfileId) {
                        $tugas->anggota()->create([
                            'mahasiswa_profile_id' => $mahasiswaProfileId,
                            'status' => 'diterima',
                            'diundang_oleh' => null,
                            'sumber' => 'ditugaskan_asn',
                        ]);
                    }

                    foreach ($mahasiswaIds as $mahasiswaProfileId) {
                        Logbook::firstOrCreate([
                            'tugas_id' => $tugas->id,
                            'mahasiswa_profile_id' => $mahasiswaProfileId,
                        ]);
                    }
                }

                return $tugas;
            });
        } catch (DomainException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        // Proses Penyimpanan Multiple Files Lampiran (setelah transaksi DB sukses)
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
        // 1. Ambil data tugas milik ASN yang sedang login beserta relasi mahasiswa & file lampiran
        $tugas = Tugas::with(['skills', 'attachments', 'mahasiswaProfile.user', 'anggota.mahasiswaProfile.user'])
            ->where('asn_id', Auth::id())
            ->findOrFail($id);

        // 2. Ambil master data skill
        $skills = Skill::all();

        // 3. Ambil daftar mahasiswa aktif untuk opsi ubah penugasan
        $mahasiswaList = MahasiswaProfile::with('user')->where('status', 'aktif')->get();

        return view('pages.asn.task-not-done.update', compact('tugas', 'skills', 'mahasiswaList'));
    }

    public function updateTugas(Request $request, $id)
{
    $validated = $request->validate(
        [
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'deadline' => 'required|date|after:now',
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
    $mahasiswaIds = $isSyncMahasiswa
        ? array_values(array_unique(array_filter($validated['mahasiswa_ids'] ?? [])))
        : [];

    try {
        DB::transaction(function () use ($id, $validated, $isSyncMahasiswa, $mahasiswaIds, &$filesToDeleteFromStorage) {
            // 1. Lock MahasiswaProfile terlebih dahulu jika ada mahasiswa yang ditugaskan
            if ($isSyncMahasiswa && !empty($mahasiswaIds)) {
                $this->penugasanService->lockDanValidasiMahasiswaAktif($mahasiswaIds);
            }

            // 2. Lock baris Tugas & pastikan milik ASN yang login
            $tugas = Tugas::where('id', $id)
                ->where('asn_id', Auth::id())
                ->lockForUpdate()
                ->firstOrFail();

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
                $attachmentsToDelete = $tugas->attachments()
                    ->whereIn('id', $validated['delete_attachments'])
                    ->get();

                foreach ($attachmentsToDelete as $attachment) {
                    if ($attachment->file_path) {
                        $filesToDeleteFromStorage[] = $attachment->file_path;
                    }
                    $attachment->delete();
                }
            }

            // D. Update Penugasan Mahasiswa
            if ($isSyncMahasiswa) {
                if (!empty($mahasiswaIds)) {
                    // Mahasiswa index 0 sebagai ketua/penanggung jawab utama
                    $tugas->update([
                        'mahasiswa_profile_id' => $mahasiswaIds[0],
                        'status' => $tugas->status === 'selesai' ? 'selesai' : 'diambil',
                        'sumber' => 'ditugaskan_asn',
                        'diambil_at' => $tugas->diambil_at ?? now(),
                    ]);

                    // Anggota tambahan (index ke-1 ke atas)
                    $anggotaIds = array_slice($mahasiswaIds, 1);

                    // Hapus anggota & logbook mahasiswa yang tidak lagi terpilih
                    $tugas->anggota()->whereNotIn('mahasiswa_profile_id', $anggotaIds)->delete();
                    Logbook::where('tugas_id', $tugas->id)
                        ->whereNotIn('mahasiswa_profile_id', $mahasiswaIds)
                        ->delete();

                    // Tambahkan anggota baru
                    foreach ($anggotaIds as $mhsId) {
                        $tugas->anggota()->firstOrCreate(
                            ['mahasiswa_profile_id' => $mhsId],
                            ['status' => 'diterima', 'sumber' => 'ditugaskan_asn']
                        );
                    }

                    // Pastikan semua mahasiswa terpilih memiliki entri Logbook
                    foreach ($mahasiswaIds as $mhsId) {
                        Logbook::firstOrCreate([
                            'tugas_id' => $tugas->id,
                            'mahasiswa_profile_id' => $mhsId,
                        ]);
                    }
                } else {
                    if ($tugas->status === 'selesai') {
                        throw new DomainException('Tugas yang sudah selesai tidak dapat dikosongkan penugasannya.');
                    }

                    // Reset tugas menjadi bebas diambil kembali
                    $tugas->update([
                        'mahasiswa_profile_id' => null,
                        'status' => 'tersedia',
                        'sumber' => 'mandiri',
                        'diambil_at' => null,
                    ]);

                    // Hapus seluruh anggota & logbook mahasiswa sebelumnya pada tugas ini
                    $tugas->anggota()->delete();
                    Logbook::where('tugas_id', $tugas->id)->delete();
                }
            }
        });
    } catch (DomainException $e) {
        return back()->withInput()->with('error', $e->getMessage());
    }

    // Hapus file fisik setelah transaksi DB berhasil commit
    foreach ($filesToDeleteFromStorage as $filePath) {
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
    }

    // Tambah File Lampiran Baru
    if ($request->hasFile('file')) {
        $tugas = Tugas::findOrFail($id);
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
        // 1. Ambil data tugas beserta relasi attachments
        $tugas = Tugas::with('attachments')->where('id', $id)->where('asn_id', Auth::id())->firstOrFail();

        // 2. Hapus berkas fisik dari disk penyimpanan (public)
        foreach ($tugas->attachments as $attachment) {
            if ($attachment->file_path && Storage::disk('public')->exists($attachment->file_path)) {
                Storage::disk('public')->delete($attachment->file_path);
            }
        }

        // 3. Hapus record lampiran di database & data tugas
        $tugas->attachments()->delete();
        $tugas->delete();

        // 4. Return sukses
        return redirect()->route('task-not-done')->with('success', 'Tugas berhasil dihapus beserta seluruh file terkait.');
    }
}
