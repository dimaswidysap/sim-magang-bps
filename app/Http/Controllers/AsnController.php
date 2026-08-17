<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Skill;
use App\Models\User;
use App\Models\AsnProfile;
use App\Models\Tugas;
use App\Models\MahasiswaProfile;
use App\Models\Logbook;

class AsnController extends Controller
{
    //
    public function asnIndex()
    {
        $totalSelesai = Tugas::asnGetTugasSelesai()->count();
        $totalBelumSelesai = Tugas::asnGetTugasBelumSelesai()->count();

        $daftarMahasiswa = MahasiswaProfile::with('user')->aktif()->denganStatistikTugas()->get();

        return view('pages.asn.index', compact('totalSelesai', 'totalBelumSelesai', 'daftarMahasiswa'));
    }

    public function logbookMahasiswa($mahasiswaProfileId)
    {
        $mahasiswa = MahasiswaProfile::with('user')->findOrFail($mahasiswaProfileId);

        $bulanList = collect();
        $tanggalAktif = collect();

        if ($mahasiswa->tanggal_mulai && $mahasiswa->tanggal_selesai) {
            $kursor = $mahasiswa->tanggal_mulai->copy()->startOfMonth();
            $akhir = $mahasiswa->tanggal_selesai->copy()->startOfMonth();

            while ($kursor->lte($akhir)) {
                $bulanList->push([
                    'label' => $kursor->translatedFormat('F Y'),
                    'tahun' => $kursor->year,
                    'bulan' => $kursor->month,
                    'jumlah_hari' => $kursor->daysInMonth,
                ]);
                $kursor->addMonth();
            }

            $tanggalAktif = Logbook::query()->where('mahasiswa_profile_id', $mahasiswa->id)->get()->map(fn($item) => $item->created_at->toDateString())->unique();
        }

        return view('pages.asn.logbook-magang', compact('mahasiswa', 'bulanList', 'tanggalAktif'));
    }

    /**
     * Detail kegiatan mahasiswa tertentu pada tanggal tertentu.
     * Menampilkan tugas dari ASN manapun, bukan cuma ASN yang login -
     * makanya nama ASN pemberi tugas ditampilkan jelas di Blade.
     */
    public function logbookMahasiswaTanggal($mahasiswaProfileId, $tanggal)
    {
        $mahasiswa = MahasiswaProfile::with('user')->findOrFail($mahasiswaProfileId);

        $logbook = Logbook::query()
            ->where('mahasiswa_profile_id', $mahasiswa->id)
            ->whereDate('created_at', $tanggal)
            ->with(['tugas.asn', 'tugas.skills'])
            ->get();

        if ($logbook->isEmpty()) {
            abort(404, 'Tidak ada kegiatan pada tanggal ini.');
        }

        return view('pages.asn.logbook-detail', compact('mahasiswa', 'logbook', 'tanggal'));
    }

    public function createTugasForm()
    {
        $skillList = Skill::orderBy('nama_skill', 'asc')->get();
        $daftarMahasiswa = MahasiswaProfile::query()->where('status', 'aktif')->whereHas('user', fn($q) => $q->where('is_active', true))->with('user')->get();

        return view('pages.asn.create-task.create', compact('skillList', 'daftarMahasiswa'));
    }
    public function taskNotDone()
    {
        $tugasBelumSelesai = Tugas::milikAsn(Auth::id())
            ->belumSelesai()
            ->with(['mahasiswaProfile.user', 'skills'])
            ->orderBy('deadline')
            ->get();

        return view('pages.asn.task-not-done.index', compact('tugasBelumSelesai'));
    }
    public function taskDone()
    {
        // Mengambil ID dari user yang sedang login.
        // Pastikan login menggunakan guard yang sesuai agar Auth::id() mengembalikan asn_id
        $asnId = Auth::id();

        // Jika asn_id bukan ID utama dari user yang login (misal merelasikan tabel),
        // gunakan: $asnId = Auth::user()->asn_id;

        // Mengambil data menggunakan Local Scope dari model
        $tugasSelesai = Tugas::selesaiByAsn($asnId)->get();

        // Me-return data ke view (silakan sesuaikan nama view Anda)

        return view('pages.asn.task-done.index', compact('tugasSelesai'));
    }

    public function tugasSelesaiDetail($id)
    {
        $tugasDetail = Tugas::where('id', $id)
            ->where('asn_id', Auth::id()) // FIX: pastikan cuma tugas milik ASN yang login
            ->with([
                'mahasiswaProfile.user',
                'asn',
                'anggota.mahasiswaProfile.user',
                // Ambil submission yang SUDAH disetujui saja, terbaru duluan.
                // Kalau ada riwayat revisi berkali-kali, yang ditampilkan
                // cuma yang final disetujui, bukan seluruh riwayat.
                'submissions' => function ($q) {
                    $q->where('status', 'disetujui')->latest();
                },
            ])
            ->firstOrFail();

        return view('pages.asn.task-done.view', compact('tugasDetail'));
    }

    public function showFormProfil()
    {
        $profil = User::with('asnProfile')->findOrFail(Auth::id());

        return view('pages.asn.profil', compact('profil'));
    }

    public function updateProfil(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'nip' => 'required|string|unique:asn_profiles,nip,' . optional($user->asnProfile)->id,
            'jabatan' => 'nullable|string|max:255',
            'unit_kerja' => 'nullable|string|max:255',
        ]);

        $user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
        ]);

        AsnProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nip' => $validated['nip'],
                'jabatan' => $validated['jabatan'] ?? null,
                'unit_kerja' => $validated['unit_kerja'] ?? null,
            ],
        );

        return redirect()->route('asn-profil')->with('success', 'Profil berhasil diperbarui.');
    }

    public function destroyTugas($id)
    {
        // 1. Ambil data spesifiknya (atau gagal jika tidak ketemu/bukan miliknya)
        $tugas = Tugas::query()->where('id', $id)->where('asn_id', Auth::id())->firstOrFail();

        // 2. Hapus HANYA data ini
        $tugas->delete('tugas');

        // 3. Return sukses
        return redirect()->route('task-not-done')->with('success', 'Tugas berhasil dihapus beserta seluruh data terkait.');
    }
}
