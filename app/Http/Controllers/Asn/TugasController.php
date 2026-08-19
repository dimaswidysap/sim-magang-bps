<?php

namespace App\Http\Controllers\Asn;

use Illuminate\Support\Facades\Auth;
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
                'file' => 'nullable|file|max:10240|mimes:doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,svg,webp,bmp,pdf,zip',
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
                'mimes' => 'Format file tidak didukung. Gunakan format: doc, docx, xls, xlsx, ppt, pptx, jpg, jpeg, png, gif, svg, webp, bmp, pdf, atau zip.',
                'min' => ':attribute minimal :min item.',
                'boolean' => 'Format :attribute tidak valid.',
                'required_if' => 'Pilih minimal 1 mahasiswa untuk ditugaskan langsung.',
            ],
            [
                'judul' => 'Judul Tugas',
                'deskripsi' => 'Deskripsi Tugas',
                'deadline' => 'Deadline',
                'skills' => 'Skill',
                'file' => 'File',
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
                // Kalau penugasan langsung: status langsung 'diambil', BUKAN
                // 'tersedia' - karena sudah pasti ada yang mengerjakan, tidak
                // perlu masuk daftar "tugas tersedia" untuk direbutkan lagi.
                'status' => $isPenugasanLangsung ? 'diambil' : 'tersedia',
                'diambil_at' => $isPenugasanLangsung ? now() : null,
                'periode_magang_id' => null,
                // Mahasiswa PERTAMA yang dipilih otomatis jadi "ketua" (kolom
                // ini) - sisanya masuk sebagai anggota. Ini murni teknis
                // (mengikuti struktur tabel yang sudah ada), bukan berarti
                // dia "lebih penting" dari anggota lain.
                'mahasiswa_profile_id' => $isPenugasanLangsung ? $validated['mahasiswa_ids'][0] : null,
            ]);

            if (!empty($validated['skills'])) {
                $tugas->skills()->attach($validated['skills']);
            }

            if ($isPenugasanLangsung) {
                // Mahasiswa ke-2 dan seterusnya (index 0 sudah jadi ketua di atas)
                $anggotaTambahan = array_slice($validated['mahasiswa_ids'], 1);

                foreach ($anggotaTambahan as $mahasiswaProfileId) {
                    $tugas->anggota()->create([
                        'mahasiswa_profile_id' => $mahasiswaProfileId,
                        'status' => 'diterima', // langsung diterima, tidak perlu konfirmasi
                        'diundang_oleh' => null, // bukan diundang mahasiswa lain
                        'sumber' => 'ditugaskan_asn',
                    ]);
                }

                // Logbook otomatis untuk SEMUA mahasiswa yang ditugaskan (ketua + anggota)
                foreach ($validated['mahasiswa_ids'] as $mahasiswaProfileId) {
                    Logbook::create([
                        'tugas_id' => $tugas->id,
                        'mahasiswa_profile_id' => $mahasiswaProfileId,
                    ]);
                }
            }

            return $tugas;
        });

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('tugas-attachments', 'public');

            $tugas->attachments()->create([
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'mime_type' => $file->getClientMimeType(),
            ]);
        }

        $pesan = $isPenugasanLangsung ? 'Tugas berhasil dibuat dan langsung ditugaskan ke mahasiswa terpilih.' : 'Tugas berhasil dibuat dan tersedia untuk diambil mahasiswa.';

        return redirect()->route('task-not-done')->with('success', $pesan);
    }

    public function editTugasForm($id)
    {
        $skills = Skill::all();
        $tugas = Tugas::with('skills')->findOrFail($id);
        return view('pages.asn.task-not-done.update', compact('tugas', 'skills'));
    }

    public function updateTugas(Request $request, $id)
    {
        // FIX: tambahkan where('asn_id', Auth::id()) - tanpa ini, ASN mana pun
        // bisa edit tugas ASN lain lewat URL, bukan cuma tugas miliknya sendiri.
        $tugas = Tugas::where('id', $id)->where('asn_id', Auth::id())->firstOrFail();

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'deadline' => 'required|date|after:now',
            'skills' => 'nullable|array',
            'skills.*' => 'exists:skills,id',
            'file' => 'nullable|file|max:10240|mimes:doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,svg,webp,bmp,pdf,zip',
        ]);

        $tugas->update([
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'],
            'deadline' => $validated['deadline'],
        ]);

        if (!empty($validated['skills'])) {
            $tugas->skills()->sync($validated['skills']);
        } else {
            $tugas->skills()->detach();
        }

        // Tambah file BARU tanpa menghapus yang lama - pakai create() biasa
        // (menambah baris baru di tugas_attachments), BUKAN sync/replace apa pun.
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('tugas-attachments', 'public');

            $tugas->attachments()->create([
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'mime_type' => $file->getClientMimeType(),
            ]);
        }

        return redirect()->route('task-not-done')->with('success', 'Tugas berhasil diperbarui.');
    }
}
