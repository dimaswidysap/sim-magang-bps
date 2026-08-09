<?php

namespace App\Http\Controllers\Asn;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tugas;
use App\Models\Skill;

class TugasController extends Controller
{
    //
  public function storeTugas(Request $request)
{
    $validated = $request->validate([
        'judul' => 'required|string|max:255',
        'deskripsi' => 'required|string',
        'deadline' => 'required|date|after:now',
        'skills' => 'nullable|array',
        'skills.*' => 'exists:skills,id',
        'file' => 'nullable|file|max:10240',
        'penugasan_langsung' => 'nullable|boolean',
        // required_if: mahasiswa_ids WAJIB diisi kalau checkbox dicentang
        'mahasiswa_ids' => 'required_if:penugasan_langsung,1|nullable|array|min:1',
        'mahasiswa_ids.*' => 'exists:mahasiswa_profiles,id',
    ], [
        'mahasiswa_ids.required_if' => 'Pilih minimal 1 mahasiswa untuk ditugaskan langsung.',
    ]);

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

        if (! empty($validated['skills'])) {
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

    $pesan = $isPenugasanLangsung
        ? 'Tugas berhasil dibuat dan langsung ditugaskan ke mahasiswa terpilih.'
        : 'Tugas berhasil dibuat dan tersedia untuk diambil mahasiswa.';

    return redirect()->route('task-not-done')->with('success', $pesan);
}

    public function editTugasForm($id)

    {
         $skills=Skill::all();
        $tugas = Tugas::with('skills')->findOrFail($id);
        return view('pages.asn.task-not-done.update', compact('tugas','skills'));
    }

    public function updateTugas(Request $request, $id)
    {
        // 1. Cari tugas berdasarkan ID
        $tugas = Tugas::findOrFail($id);

        // 2. Validasi Input
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            // Gunakan date saja jika deadline boleh diset ke hari yang sama,
            // atau biarkan after:now jika tidak boleh mundur dari waktu saat mengedit
            'deadline' => 'required|date|after:now',
            'skills' => 'nullable|array',
            'skills.*' => 'exists:skills,id',
        ]);

        // 3. Update data pada tabel tugas
        $tugas->update([
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'],
            'deadline' => $validated['deadline'],
        ]);

        // 4. Update relasi skills menggunakan sync()
        // sync() akan menghapus skill yang tidak dicentang dan menambah yang baru dicentang
        if (!empty($validated['skills'])) {
            $tugas->skills()->sync($validated['skills']);
        } else {
            // Jika tidak ada skill yang dicentang, hapus semua relasi skill
            $tugas->skills()->detach();
        }

        // 5. Redirect dengan pesan sukses
        return redirect()->route('task-not-done')->with('success', 'Tugas berhasil diperbarui.');
    }


}
