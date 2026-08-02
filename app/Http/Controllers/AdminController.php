<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\MahasiswaProfile;
use App\Models\User;
use App\Models\Skill;
use App\Models\PeriodeMagang;

class AdminController extends Controller
{
    //

    public function adminIndex()
    {
        // Mengambil data user yang sedang login
        return View('pages.admin.index');
    }

    // mengambil semua data ASN
    public function adminAsn()
    {
        $dataAsn = User::query()->where('role', 'asn')->with('AsnProfile')->get();
        return view('pages.admin.asn.index',compact('dataAsn'));
    }

    // mengambil semua data mahasiswa
    public function adminMahasiswa()
    {
        // Tambahkan query() setelah model User
        $dataMahasiswa = User::query()->where('role', 'mahasiswa')->with('mahasiswaProfile')->get();

        return view('pages.admin.mahasiswa.index', compact('dataMahasiswa'));
    }

    public function adminSkill(){

    $dataSkill= Skill::all();

    return view('pages.admin.skill.index',compact('dataSkill'));
    }

    public function adminPeriodeMagang(){

        $periodeMagang=PeriodeMagang::all();

        return view('pages.admin.periode-magang.index',compact('periodeMagang'));
    }
}
