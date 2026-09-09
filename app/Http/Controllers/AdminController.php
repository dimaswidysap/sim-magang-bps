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

    // function statistik user
    private function getStatistikUsers()
    {
        return [
            'aktif' => User::mahasiswa()->profileAktif()->with('mahasiswaProfile')->get(),
            'nonAktif' => User::mahasiswa()->profileNonAktif()->with('mahasiswaProfile')->get(),
            'selesai' => User::mahasiswa()->profileSelesai()->with('mahasiswaProfile')->get(),
            'batal' => User::mahasiswa()->profileBatal()->with('mahasiswaProfile')->get(),
            'pending' => User::mahasiswa()->profilePending()->with('mahasiswaProfile')->get(),
            'asnAktif' => User::asn()->asnAktif()->with('asnProfile')->get(),
            'asnNonAktif' => User::asn()->asnNonAktif()->with('asnProfile')->get(),
        ];
    }

    public function adminIndex()
    {
        $statistik = $this->getStatistikUsers();
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
        $statistik = $this->getStatistikUsers();

        return view('pages.admin.statistik-mahasiswa.index', [
            'jumlahMahasiswaAktif' => $statistik['aktif'],
            'jumlahMahasiswaNonAktif' => $statistik['nonAktif'],
            'jumlahMahasiswaSelesai' => $statistik['selesai'],
            'jumlahMahasiswaBatal' => $statistik['batal'],
            'jumlahMahasiswaPending' => $statistik['pending'],
        ]);
    }

    public function statistikAsn()
    {
        $statistik = $this->getStatistikUsers();

        return view('pages.admin.statistik-asn.index', [
            'aktif' => $statistik['asnAktif'],
            'nonAktif' => $statistik['asnNonAktif'],
        ]);
    }

    public function asnAktif()
    {
        $statistik = $this->getStatistikUsers();

        return view('pages.admin.statistik-asn.aktif', ['asnAktif' => $statistik['asnAktif']]);
    }
    //
    //
    //
    //
    //
    //

    public function magangAktif()
    {
        $statistik = $this->getStatistikUsers();

        return view('pages.admin.statistik-mahasiswa.aktif', ['magangAktif' => $statistik['aktif']]);
    }
    public function magangNonaktif()
    {
        $statistik = $this->getStatistikUsers();

        return view('pages.admin.statistik-mahasiswa.nonaktif', ['magangNonaktif' => $statistik['nonAktif']]);
    }
    public function magangPending()
    {
        $statistik = $this->getStatistikUsers();

        return view('pages.admin.statistik-mahasiswa.pending', ['magangPending' => $statistik['pending']]);
    }
    public function magangSelesai()
    {
        $statistik = $this->getStatistikUsers();

        return view('pages.admin.statistik-mahasiswa.selesai', ['magangSelesai' => $statistik['selesai']]);
    }
    public function magangBatal()
    {
        $statistik = $this->getStatistikUsers();

        return view('pages.admin.statistik-mahasiswa.batal', ['magangBatal' => $statistik['batal']]);
    }
    //
    //
    //
    //
    //
    //

    public function asnNonAktif()
    {
        $statistik = $this->getStatistikUsers();

        return view('pages.admin.statistik-asn.non-aktif', ['asnNonAktif' => $statistik['asnNonAktif']]);
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
