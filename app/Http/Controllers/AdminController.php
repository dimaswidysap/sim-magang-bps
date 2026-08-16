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
        $jumlahMahasiswaAktif = User::mahasiswa()->profileAktif()->with('mahasiswaProfile')->count();
        $jumlahMahasiswaNonAktif = User::mahasiswa()->profileNonAktif()->with('mahasiswaProfile')->count();
        $jumlahMahasiswaSelesai = User::mahasiswa()->profileSelesai()->with('mahasiswaProfile')->count();
        $jumlahMahasiswaBatal = User::mahasiswa()->profileBatal()->with('mahasiswaProfile')->count();

        $jumlahAsnAktif=User::asn()->asnAktif()->count();
        $jumlahAsnNonAktif=User::asn()->asnNonAktif()->count();

        $daftarMahasiswaProfilWarning = User::query()->where('role', 'mahasiswa')
        ->whereDoesntHave('mahasiswaProfile')
        ->orderBy('created_at', 'desc')
        ->get();

        return View('pages.admin.index', compact('daftarMahasiswaProfilWarning','jumlahMahasiswaAktif', 'jumlahMahasiswaNonAktif','jumlahMahasiswaSelesai','jumlahMahasiswaBatal','jumlahAsnAktif','jumlahAsnNonAktif'));
    }

    // mengambil semua data ASN
    public function adminAsn()
    {
        $dataAsn = User::query()->where('role', 'asn')->with('AsnProfile')->get();
        return view('pages.admin.asn.index', compact('dataAsn'));
    }

    // mengambil semua data mahasiswa
    public function adminMahasiswa()
    {
        // Tambahkan query() setelah model User
        $dataMahasiswa = User::query()->where('role', 'mahasiswa')->with('mahasiswaProfile')->get();

        return view('pages.admin.mahasiswa.index', compact('dataMahasiswa'));
    }

    public function adminSkill()
    {
        $dataSkill = Skill::all();

        return view('pages.admin.skill.index', compact('dataSkill'));
    }

    public function adminPeriodeMagang()
    {
        $periodeMagang = PeriodeMagang::all();

        return view('pages.admin.periode-magang.index', compact('periodeMagang'));
    }
}
