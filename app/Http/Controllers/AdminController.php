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

    // mengambil semua data ASN
    public function adminAsn()
    {
        $dataAsn = User::query()->where('role', 'asn')->with('AsnProfile')->get();
        return view('admin.asn.index',compact('dataAsn'));
    }

    // mengambil semua data mahasiswa
    public function adminMahasiswa()
    {
        // Tambahkan query() setelah model User
        $dataMahasiswa = User::query()->where('role', 'mahasiswa')->with('mahasiswaProfile')->get();

        return view('admin.mahasiswa.index', compact('dataMahasiswa'));
    }

    public function adminSkill(){

    return view('admin.skill.index');
    }

    public function adminPeriodeMagang(){
        return view('admin.periode-magang.index');
    }
}
