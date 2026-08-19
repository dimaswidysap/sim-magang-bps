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

    // Tambahkan fungsi private ini di dalam Controller Anda
    private function getStatistikMahasiswa()
    {
        return [
            'aktif' => User::mahasiswa()->profileAktif()->with('mahasiswaProfile')->get(),
            'nonAktif' => User::mahasiswa()->profileNonAktif()->with('mahasiswaProfile')->get(),
            'selesai' => User::mahasiswa()->profileSelesai()->with('mahasiswaProfile')->get(),
            'batal' => User::mahasiswa()->profileBatal()->with('mahasiswaProfile')->get(),
            'pending' => User::mahasiswa()->profilePending()->with('mahasiswaProfile')->get(),
            'asnAktif'=> User::asn()->asnAktif()->get(),
            'asnNonAktif'=> User::asn()->asnNonAktif()->get()
        ];
    }

    public function adminIndex()
    {

        $statistik = $this->getStatistikMahasiswa();




        $daftarMahasiswaProfilWarning = User::query()
            ->where('role', 'mahasiswa')
            ->where(function ($query) {
                $query->whereDoesntHave('mahasiswaProfile')->orWhereHas('mahasiswaProfile', function ($subQuery) {
                    $subQuery->whereNull('nim')->orWhereNull('instansi_asal')->orWhereNull('jurusan');
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.admin.index', [
            'daftarMahasiswaProfilWarning' => $daftarMahasiswaProfilWarning,
            'jumlahMahasiswaAktif' => $statistik['aktif'],
            'jumlahMahasiswaNonAktif' => $statistik['nonAktif'],
            'jumlahMahasiswaSelesai' => $statistik['selesai'],
            'jumlahMahasiswaBatal' => $statistik['batal'],
            'jumlahMahasiswaPending' => $statistik['pending'],
            'jumlahAsnAktif' => $statistik['asnAktif'],
            'jumlahAsnNonAktif' => $statistik['asnNonAktif'],
        ]);
    }

    public function statistikUser()
    {
        // Panggil fungsi yang sama
        $statistik = $this->getStatistikMahasiswa();

        return view('pages.admin.statistik-mahasiswa.index', [
            'jumlahMahasiswaAktif' => $statistik['aktif'],
            'jumlahMahasiswaNonAktif' => $statistik['nonAktif'],
            'jumlahMahasiswaSelesai' => $statistik['selesai'],
            'jumlahMahasiswaBatal' => $statistik['batal'],
            'jumlahMahasiswaPending' => $statistik['pending'],
        ]);
    }


    public function statistikAsn(){
    $statistik = $this->getStatistikMahasiswa();

        return view('pages.admin.statistik-asn.index',[
            'aktif' => $statistik['asnAktif'],
            'nonAktif' => $statistik['asnNonAktif'],
        ]);
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
