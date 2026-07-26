<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AdminMahasiswa extends Controller
{
    //
    public function showForm(){
        return view('admin.mahasiswa.create');
    }

  public function detailMahasiswa($id)
{
    // Mengambil data user berdasarkan ID. Gunakan findOrFail agar otomatis
    // menampilkan error 404 jika ID tidak ditemukan di database.
    $detailUser = User::query()->where('id', $id)
        ->where('role', 'mahasiswa')
        ->with('mahasiswaProfile.skills') // sekalian ambil skill kalau perlu
        ->firstOrFail();
    // Jangan lupa mengirimkan variabel $detailUser ke dalam view menggunakan compact()
    return view('admin.mahasiswa.view', compact('detailUser'));
}
}
