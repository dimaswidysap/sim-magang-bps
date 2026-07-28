<?php

namespace App\Http\Controllers\Asn;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tugas;

class TugasController extends Controller
{
    //
    public function storeTugas(Request $request)
{
    $validated = $request->validate([
        'judul' => 'required|string|max:255',
        'deskripsi' => 'required|string',
        'deadline' => 'required|date|after:now',
    ]);

    Tugas::create([
        'asn_id' => auth()->id(),
        'judul' => $validated['judul'],
        'deskripsi' => $validated['deskripsi'],
        'deadline' => $validated['deadline'],
        'status' => 'tersedia',
        'periode_magang_id' => null, // kolom masih ada di tabel, sengaja tidak dipakai
        'mahasiswa_profile_id' => null,
    ]);

    return redirect()->route('asn-index')
        ->with('success', 'Tugas berhasil dibuat dan tersedia untuk diambil mahasiswa.');
}
}
