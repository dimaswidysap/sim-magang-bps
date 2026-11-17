<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Logbook;
use App\Models\MagangLogbook;
use App\Models\MahasiswaProfile;
use App\Models\Tugas;
use App\Models\User;
use BackedEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LaporanController extends Controller
{
    /**
     * Menampilkan daftar peserta magang yang telah menyelesaikan magang (status = 'selesai').
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $tahun = $request->query('tahun');
        $tahunValid = is_string($tahun) && preg_match('/^\d{4}$/', $tahun) ? (int) $tahun : null;

        // Ambil daftar tahun unik dari periode magang peserta yang telah selesai
        $daftarTahun = MahasiswaProfile::query()
            ->where('status', 'selesai')
            ->get(['tanggal_mulai', 'tanggal_selesai'])
            ->flatMap(fn (MahasiswaProfile $profil) => [
                $profil->tanggal_mulai?->year,
                $profil->tanggal_selesai?->year,
            ])
            ->filter()
            ->unique()
            ->sortDesc()
            ->values();

        $daftarLaporan = User::query()
            ->where('role', 'mahasiswa')
            ->profileSelesai()
            ->with([
                'mahasiswaProfile.pembimbing.user',
                'mahasiswaProfile.skills',
            ])
            ->when($tahunValid, function ($query) use ($tahunValid) {
                $query->whereHas('mahasiswaProfile', function ($subQuery) use ($tahunValid) {
                    $subQuery->whereYear('tanggal_selesai', $tahunValid)
                        ->orWhereYear('tanggal_mulai', $tahunValid);
                });
            })
            ->search($search)
            ->orderByDesc(function ($query) {
                $query->select('tanggal_selesai')
                    ->from('mahasiswa_profiles')
                    ->whereColumn('mahasiswa_profiles.user_id', 'users.id')
                    ->limit(1);
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        // Hitung ringkasan hari masuk & tugas selesai untuk setiap peserta pada halaman aktif
        $daftarLaporan->getCollection()->transform(function (User $user) {
            $profil = $user->mahasiswaProfile;
            $user->total_hari_masuk = $profil ? $profil->getJumlahHariMasuk() : 0;
            $user->total_tugas_selesai = $profil ? Tugas::getJumlahTugasSelesai($profil->id) : 0;

            return $user;
        });

        return view('pages.admin.laporan.index', compact('daftarLaporan', 'daftarTahun', 'tahunValid'));
    }

    /**
     * Menampilkan halaman pratinjau laporan peserta magang sebelum didownload dalam format PDF.
     */
    public function show($id)
    {
        $data = $this->buildLaporanData($id);

        return view('pages.admin.laporan.show', $data);
    }

    /**
     * Mengunduh laporan peserta magang dalam format PDF.
     * Mendukung package barryvdh/laravel-dompdf secara otomatis apabila terpasang,
     * serta fallback ke mode cetak PDF A4 browser apabila belum terpasang.
     */
    public function downloadPdf($id)
    {
        $data = $this->buildLaporanData($id);
        $namaFile = 'Laporan-Magang-' . Str::slug($data['mahasiswa']->name) . '.pdf';

        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pages.admin.laporan.pdf', array_merge($data, [
                'isPdfEngine' => true,
            ]))->setPaper('a4', 'portrait');

            return $pdf->download($namaFile);
        }

        return view('pages.admin.laporan.pdf', array_merge($data, [
            'isPdfEngine' => false,
        ]));
    }

    /**
     * Menyusun seluruh data profil, pembimbing, tugas, dan logbook peserta selesai.
     */
    private function buildLaporanData($id): array
    {
        $mahasiswa = User::query()
            ->where('id', $id)
            ->where('role', 'mahasiswa')
            ->profileSelesai()
            ->with([
                'mahasiswaProfile.pembimbing.user',
                'mahasiswaProfile.skills',
            ])
            ->firstOrFail();

        $profil = $mahasiswa->mahasiswaProfile;

        $daftarTugas = Tugas::milikMahasiswa($profil->id)
            ->with([
                'asn',
                'skills',
                'latestSubmission',
                'anggotaDiterima.mahasiswaProfile.user',
            ])
            ->orderByDesc('selesai_at')
            ->orderByDesc('created_at')
            ->get();

        $logbookMandiri = MagangLogbook::where('mahasiswa_profile_id', $profil->id)
            ->tervalidasi()
            ->orderBy('tanggal_kegiatan', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $logbookTugas = Logbook::where('mahasiswa_profile_id', $profil->id)
            ->with(['tugas.asn'])
            ->orderBy('created_at', 'asc')
            ->get();

        $totalHariMasuk = $profil->getJumlahHariMasuk();
        $totalTugasSelesai = $daftarTugas->filter(function (Tugas $tugas) {
            $status = $tugas->status instanceof BackedEnum ? $tugas->status->value : (string) $tugas->status;

            return $status === 'selesai';
        })->count();

        $durasiHariKalender = ($profil->tanggal_mulai && $profil->tanggal_selesai)
            ? $profil->tanggal_mulai->diffInDays($profil->tanggal_selesai) + 1
            : 0;

        return [
            'mahasiswa' => $mahasiswa,
            'profil' => $profil,
            'pembimbing' => $profil->pembimbing,
            'daftarTugas' => $daftarTugas,
            'logbookMandiri' => $logbookMandiri,
            'logbookTugas' => $logbookTugas,
            'totalHariMasuk' => $totalHariMasuk,
            'totalTugasSelesai' => $totalTugasSelesai,
            'totalTugasDiikuti' => $daftarTugas->count(),
            'totalLogbookMandiri' => $logbookMandiri->count(),
            'durasiHariKalender' => $durasiHariKalender,
            'tanggalCetak' => now(),
        ];
    }
}