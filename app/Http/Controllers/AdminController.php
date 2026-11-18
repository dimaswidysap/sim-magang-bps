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
        $kategori = (string) $request->query('kategori', 'semua');
        $allowedKategori = ['semua', 'aktif', 'pending', 'selesai', 'dibatalkan'];
        if (! in_array($kategori, $allowedKategori, true)) {
            $kategori = 'semua';
        }

        $tahun = trim((string) $request->query('tahun', ''));
        if (! preg_match('/^\d{4}$/', $tahun)) {
            $tahun = '';
        }

        $tanggal = trim((string) $request->query('tanggal', ''));
        if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) {
            $tanggal = '';
        }

        $search = trim((string) $request->query('search', ''));

        // Base query: seluruh akun dengan role mahasiswa tanpa pengecualian status
        $baseQuery = User::query()
            ->where('role', 'mahasiswa')
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhereHas('mahasiswaProfile', function ($p) use ($search) {
                            $p->where('nim', 'like', "%{$search}%")
                                ->orWhere('instansi_asal', 'like', "%{$search}%")
                                ->orWhere('jurusan', 'like', "%{$search}%")
                                ->orWhere('jenjang', 'like', "%{$search}%");
                        });
                });
            })
            ->when($tahun !== '', function ($q) use ($tahun) {
                $q->where(function ($sub) use ($tahun) {
                    $sub->whereHas('mahasiswaProfile', function ($p) use ($tahun) {
                        $p->whereYear('tanggal_mulai', $tahun)
                            ->orWhereYear('tanggal_selesai', $tahun);
                    })->orWhere(function ($fallback) use ($tahun) {
                        $fallback->where(function ($noDate) {
                            $noDate->whereDoesntHave('mahasiswaProfile')
                                ->orWhereHas('mahasiswaProfile', fn ($p) => $p->whereNull('tanggal_mulai')->whereNull('tanggal_selesai'));
                        })->whereYear('created_at', $tahun);
                    });
                });
            })
            ->when($tanggal !== '', function ($q) use ($tanggal) {
                $q->where(function ($sub) use ($tanggal) {
                    $sub->whereHas('mahasiswaProfile', function ($p) use ($tanggal) {
                        $p->where(function ($range) use ($tanggal) {
                            $range->whereNotNull('tanggal_mulai')
                                ->whereDate('tanggal_mulai', '<=', $tanggal)
                                ->where(function ($end) use ($tanggal) {
                                    $end->whereNull('tanggal_selesai')
                                        ->orWhereDate('tanggal_selesai', '>=', $tanggal);
                                });
                        })->orWhere(function ($onlyEnd) use ($tanggal) {
                            $onlyEnd->whereNull('tanggal_mulai')
                                ->whereNotNull('tanggal_selesai')
                                ->whereDate('tanggal_selesai', '>=', $tanggal);
                        });
                    })->orWhere(function ($fallback) use ($tanggal) {
                        $fallback->where(function ($noDate) {
                            $noDate->whereDoesntHave('mahasiswaProfile')
                                ->orWhereHas('mahasiswaProfile', fn ($p) => $p->whereNull('tanggal_mulai')->whereNull('tanggal_selesai'));
                        })->whereDate('created_at', $tanggal);
                    });
                });
            });

        // Hitung jumlah per kategori tab
        $jumlahSemua = (clone $baseQuery)->count();

        $jumlahAktif = (clone $baseQuery)
            ->whereHas('mahasiswaProfile', fn ($p) => $p->where('status', 'aktif'))
            ->count();

        $jumlahPending = (clone $baseQuery)
            ->where(function ($sub) {
                $sub->whereDoesntHave('mahasiswaProfile')
                    ->orWhereHas('mahasiswaProfile', fn ($p) => $p->where('status', 'pending')->orWhereNull('status'));
            })
            ->count();

        $jumlahSelesai = (clone $baseQuery)
            ->whereHas('mahasiswaProfile', fn ($p) => $p->where('status', 'selesai'))
            ->count();

        $jumlahDibatalkan = (clone $baseQuery)
            ->whereHas('mahasiswaProfile', fn ($p) => $p->where('status', 'dibatalkan'))
            ->count();

        // Terapkan filter tab kategori
        $dataMahasiswa = (clone $baseQuery)
            ->when($kategori === 'aktif', function ($q) {
                $q->whereHas('mahasiswaProfile', fn ($p) => $p->where('status', 'aktif'));
            })
            ->when($kategori === 'pending', function ($q) {
                $q->where(function ($sub) {
                    $sub->whereDoesntHave('mahasiswaProfile')
                        ->orWhereHas('mahasiswaProfile', fn ($p) => $p->where('status', 'pending')->orWhereNull('status'));
                });
            })
            ->when($kategori === 'selesai', function ($q) {
                $q->whereHas('mahasiswaProfile', fn ($p) => $p->where('status', 'selesai'));
            })
            ->when($kategori === 'dibatalkan', function ($q) {
                $q->whereHas('mahasiswaProfile', fn ($p) => $p->where('status', 'dibatalkan'));
            })
            ->with(['mahasiswaProfile.pembimbing.user'])
            ->orderByDesc('created_at')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        // Daftar tahun dinamis dari data magang & pendaftaran akun
        $tahunMulai = MahasiswaProfile::whereNotNull('tanggal_mulai')
            ->selectRaw('DISTINCT YEAR(tanggal_mulai) as tahun')
            ->pluck('tahun');
        $tahunSelesai = MahasiswaProfile::whereNotNull('tanggal_selesai')
            ->selectRaw('DISTINCT YEAR(tanggal_selesai) as tahun')
            ->pluck('tahun');
        $tahunDaftar = User::where('role', 'mahasiswa')
            ->selectRaw('DISTINCT YEAR(created_at) as tahun')
            ->pluck('tahun');

        $daftarTahun = $tahunMulai
            ->merge($tahunSelesai)
            ->merge($tahunDaftar)
            ->push((int) date('Y'))
            ->filter()
            ->unique()
            ->sortDesc()
            ->values();

        return view('pages.admin.mahasiswa.index', compact(
            'dataMahasiswa',
            'kategori',
            'tahun',
            'tanggal',
            'search',
            'daftarTahun',
            'jumlahSemua',
            'jumlahAktif',
            'jumlahPending',
            'jumlahSelesai',
            'jumlahDibatalkan'
        ));
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
