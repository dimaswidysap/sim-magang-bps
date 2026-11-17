<?php

namespace App\Http\Controllers;

use App\Models\MahasiswaProfile;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Menghitung jumlah statistik user menggunakan query agregasi COUNT(*)
     * tanpa memuat seluruh koleksi model ke memori.
     */
    private function getStatistikUsers(): array
    {
        $mahasiswaCounts = MahasiswaProfile::query()
            ->whereHas('user', fn ($q) => $q->where('role', 'mahasiswa'))
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $asnCounts = User::query()
            ->where('role', 'asn')
            ->selectRaw('is_active, COUNT(*) as total')
            ->groupBy('is_active')
            ->pluck('total', 'is_active');

        return [
            'aktif' => (int) ($mahasiswaCounts['aktif'] ?? 0),
            'nonAktif' => (int) ($mahasiswaCounts['nonaktif'] ?? 0),
            'selesai' => (int) ($mahasiswaCounts['selesai'] ?? 0),
            'batal' => (int) ($mahasiswaCounts['dibatalkan'] ?? 0),
            'pending' => (int) ($mahasiswaCounts['pending'] ?? 0),
            'asnAktif' => (int) ($asnCounts[1] ?? 0),
            'asnNonAktif' => (int) ($asnCounts[0] ?? 0),
        ];
    }

    public function adminIndex(Request $request)
    {
        MahasiswaProfile::hapusAkunTanpaTanggalKadaluarsa();

        $statistik = $this->getStatistikUsers();

        $daftarMahasiswaProfilWarning = User::query()
            ->where('role', 'mahasiswa')
            ->with('mahasiswaProfile')
            ->where(function ($query) {
                $query->whereDoesntHave('mahasiswaProfile')->orWhereHas('mahasiswaProfile', function ($subQuery) {
                    $subQuery->whereNull('nim')
                        ->orWhereNull('instansi_asal')
                        ->orWhereNull('jurusan');
                });
            })
            ->search($request->query('search'))
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

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
        return redirect()->route('admin-index');
    }

    public function asnAktif(Request $request)
    {
        $asnAktif = User::asn()
            ->asnAktif()
            ->with('asnProfile')
            ->search($request->query('search'))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('pages.admin.statistik-asn.aktif', compact('asnAktif'));
    }

    public function asnNonAktif(Request $request)
    {
        $asnNonAktif = User::asn()
            ->asnNonAktif()
            ->with('asnProfile')
            ->search($request->query('search'))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('pages.admin.statistik-asn.non-aktif', compact('asnNonAktif'));
    }

    public function magangAktif(Request $request)
    {
        $magangAktif = User::mahasiswa()
            ->profileAktif()
            ->with('mahasiswaProfile')
            ->search($request->query('search'))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('pages.admin.statistik-mahasiswa.aktif', compact('magangAktif'));
    }

    public function magangNonaktif(Request $request)
    {
        $magangNonaktif = User::mahasiswa()
            ->profileNonAktif()
            ->with('mahasiswaProfile')
            ->search($request->query('search'))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('pages.admin.statistik-mahasiswa.nonaktif', compact('magangNonaktif'));
    }

    public function magangPending(Request $request)
    {
        $magangPending = User::mahasiswa()
            ->profilePending()
            ->with('mahasiswaProfile')
            ->search($request->query('search'))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('pages.admin.statistik-mahasiswa.pending', compact('magangPending'));
    }

    public function magangSelesai(Request $request)
    {
        $magangSelesai = User::mahasiswa()
            ->profileSelesai()
            ->with('mahasiswaProfile')
            ->search($request->query('search'))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('pages.admin.statistik-mahasiswa.selesai', compact('magangSelesai'));
    }

    public function magangBatal(Request $request)
    {
        $magangBatal = User::mahasiswa()
            ->profileBatal()
            ->with('mahasiswaProfile')
            ->search($request->query('search'))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('pages.admin.statistik-mahasiswa.batal', compact('magangBatal'));
    }

    public function adminAsn(Request $request)
    {
        $dataAsn = User::query()
            ->where('role', 'asn')
            ->with('asnProfile')
            ->search($request->query('search'))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('pages.admin.asn.index', compact('dataAsn'));
    }

    public function adminMahasiswa(Request $request)
    {
        MahasiswaProfile::hapusAkunTanpaTanggalKadaluarsa();

        $dataMahasiswa = User::query()
            ->where('role', 'mahasiswa')
            ->with('mahasiswaProfile')
            ->search($request->query('search'))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('pages.admin.mahasiswa.index', compact('dataMahasiswa'));
    }

    public function adminSkill(Request $request)
    {
        $search = trim((string) $request->query('search'));

        $dataSkill = Skill::query()
            ->when($search !== '', fn ($q) => $q->where('nama_skill', 'like', "%{$search}%"))
            ->orderBy('nama_skill')
            ->paginate(15)
            ->withQueryString();

        return view('pages.admin.skill.index', compact('dataSkill'));
    }
}
