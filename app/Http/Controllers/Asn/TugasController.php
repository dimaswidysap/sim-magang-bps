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
    ]);

    $tugas = Tugas::create([
        'asn_id' => Auth::id(),
        'judul' => $validated['judul'],
        'deskripsi' => $validated['deskripsi'],
        'deadline' => $validated['deadline'],
        'status' => 'tersedia',
        'periode_magang_id' => null,
        'mahasiswa_profile_id' => null,
    ]);

    if (! empty($validated['skills'])) {
        $tugas->skills()->attach($validated['skills']);
    }

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

    return redirect()->route('task-not-done')->with('success', 'Tugas berhasil dibuat dan tersedia untuk diambil mahasiswa.');
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
