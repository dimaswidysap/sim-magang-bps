<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\MahasiswaProfile;
use App\Models\User;

class AdminController extends Controller
{
    //

    public function adminIndex()
    {
        // Mengambil data user yang sedang login
        return View('admin.index');
    }

    public function adminMahasiswa()
{
    // Tambahkan query() setelah model User
    $dataMahasiswa = User::query()->where('role', 'mahasiswa')->with('mahasiswaProfile')->get();

    return view('admin.mahasiswa.index', compact('dataMahasiswa'));
}
}
