<?php

namespace App\Http\Controllers\Asn;

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

class TugasController extends Controller
{
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
                'file.*' => 'file|max:10240|mimes:doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,svg,webp,bmp,pdf,zip',
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

        $tugas = DB::transaction(function () use ($validated, $isPenugasanLangsung) {
            $tugas = Tugas::create([
                'asn_id' => Auth::id(),
                'judul' => $validated['judul'],
                'deskripsi' => $validated['deskripsi'],
                'deadline' => $validated['deadline'],
                'status' => $isPenugasanLangsung ? 'diambil' : 'tersedia',
                'diambil_at' => $isPenugasanLangsung ? now() : null,
                'periode_magang_id' => null,
                'mahasiswa_profile_id' => $isPenugasanLangsung ? $validated['mahasiswa_ids'][0] : null,
            ]);

            if (!empty($validated['skills'])) {
                $tugas->skills()->attach($validated['skills']);
            }

            if ($isPenugasanLangsung) {
                $anggotaTambahan = array_slice($validated['mahasiswa_ids'], 1);

                foreach ($anggotaTambahan as $mahasiswaProfileId) {
                    $tugas->anggota()->create([
                        'mahasiswa_profile_id' => $mahasiswaProfileId,
                        'status' => 'diterima',
                        'diundang_oleh' => null,
                        'sumber' => 'ditugaskan_asn',
                    ]);
                }

                foreach ($validated['mahasiswa_ids'] as $mahasiswaProfileId) {
                    Logbook::create([
                        'tugas_id' => $tugas->id,
                        'mahasiswa_profile_id' => $mahasiswaProfileId,
                    ]);
                }
            }

            return $tugas;
        });

        // Proses Penyimpanan Multiple Files Lampiran
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
        // 1. Validasi kepemilikan tugas
        $tugas = Tugas::where('id', $id)->where('asn_id', Auth::id())->firstOrFail();

        // 2. Validasi Input
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

        DB::transaction(function () use ($request, $tugas, $validated) {
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

            // C. Hapus File Lampiran Spesifik yang Dipilih/Dicentang oleh User
            if (!empty($validated['delete_attachments'])) {
                $attachmentsToDelete = $tugas->attachments()->whereIn('id', $validated['delete_attachments'])->get();

                foreach ($attachmentsToDelete as $attachment) {
                    // Hapus file fisik dari storage disk public
                    if ($attachment->file_path && Storage::disk('public')->exists($attachment->file_path)) {
                        Storage::disk('public')->delete($attachment->file_path);
                    }
                    // Hapus record database
                    $attachment->delete();
                }
            }

            // D. Tambah File Lampiran Baru (Multiple File Upload)
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

            // E. Update Penugasan Mahasiswa (Jika Disediakan dalam Form)
            if ($request->has('mahasiswa_ids')) {
                $mahasiswaIds = array_filter($validated['mahasiswa_ids'] ?? []);

                if (!empty($mahasiswaIds)) {
                    // Mahasiswa pertama sebagai ketua/penanggung jawab utama
                    $tugas->update([
                        'mahasiswa_profile_id' => $mahasiswaIds[0],
                        'status' => 'diambil',
                        'diambil_at' => $tugas->diambil_at ?? now(),
                    ]);

                    // Anggota tambahan (index ke-1 ke atas)
                    $anggotaIds = array_slice($mahasiswaIds, 1);

                    // Hapus anggota lama yang tidak terpilih lagi
                    $tugas->anggota()->whereNotIn('mahasiswa_profile_id', $anggotaIds)->delete();

                    // Tambahkan anggota baru
                    foreach ($anggotaIds as $mhsId) {
                        $tugas->anggota()->firstOrCreate(['mahasiswa_profile_id' => $mhsId], ['status' => 'diterima', 'sumber' => 'ditugaskan_asn']);
                    }

                    // Buat pencatatan Logbook untuk semua mahasiswa penanggung jawab & anggota
                    foreach ($mahasiswaIds as $mhsId) {
                        Logbook::firstOrCreate([
                            'tugas_id' => $tugas->id,
                            'mahasiswa_profile_id' => $mhsId,
                        ]);
                    }
                } else {
                    // Jika dikosongkan, reset tugas menjadi 'tersedia' kembali
                    $tugas->update([
                        'mahasiswa_profile_id' => null,
                        'status' => 'tersedia',
                        'diambil_at' => null,
                    ]);
                    $tugas->anggota()->delete();
                }
            }
        });

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
