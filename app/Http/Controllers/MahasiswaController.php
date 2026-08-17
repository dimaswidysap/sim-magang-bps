<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\PeriodeMagang;
use App\Models\MahasiswaProfile;
use App\Models\Skill;
use App\Models\Tugas;
use App\Models\Logbook;

class MahasiswaController extends Controller
{
    //
    public function mahasiswaIndex()
    {
        $user = Auth::user();
        $profil = $user->mahasiswaProfile;

        // Ambil id dengan aman - kalau profil belum ada, $mahasiswaProfileId
        // jadi null, dan method-method di bawah harus bisa terima itu tanpa crash.
        $mahasiswaProfileId = $profil?->id;

        $jumlahSelesai = Tugas::getJumlahTugasSelesai($mahasiswaProfileId);
        $jumlahBelumSelesai = Tugas::getJumlahTugasBelumSelesai($mahasiswaProfileId);

        // ===== Bagian kalender logbook =====
        $bulanList = collect();
        $tanggalAktif = collect();

        if ($profil && $profil->tanggal_mulai && $profil->tanggal_selesai) {
            $kursor = $profil->tanggal_mulai->copy()->startOfMonth();
            $akhir = $profil->tanggal_selesai->copy()->startOfMonth();

            while ($kursor->lte($akhir)) {
                $bulanList->push([
                    'label' => $kursor->translatedFormat('F Y'),
                    'tahun' => $kursor->year,
                    'bulan' => $kursor->month,
                    'jumlah_hari' => $kursor->daysInMonth,
                ]);
                $kursor->addMonth();
            }

            $tanggalAktif = Logbook::query()->where('mahasiswa_profile_id', $profil->id)->get()->map(fn($item) => $item->created_at->toDateString())->unique();
        }

        return view('pages.mahasiswa.index', compact('jumlahSelesai', 'jumlahBelumSelesai', 'bulanList', 'tanggalAktif', 'profil'));
    }

    public function logbookDetailTanggal($tanggal)
    {
        $profil = Auth::user()->mahasiswaProfile;

        if (!$profil) {
            abort(404);
        }

        $logbook = Logbook::query()
            ->where('mahasiswa_profile_id', $profil->id)
            ->whereDate('created_at', $tanggal)
            ->with(['tugas.asn', 'tugas.skills'])
            ->get();

        // Kalau tidak ada kegiatan di tanggal ini, seharusnya user tidak pernah
        // sampai ke sini (tombolnya disabled di kalender) - tapi tetap dijaga
        // kalau ada yang coba akses URL manual dengan tanggal sembarangan.
        if ($logbook->isEmpty()) {
            abort(404, 'Tidak ada kegiatan pada tanggal ini.');
        }

        return view('pages.mahasiswa.logbook-detail', [
            'logbook' => $logbook,
            'tanggal' => $tanggal,
        ]);
    }

    public function tugas(Request $request)
    {
        // Tambahkan 'tersedia' sebagai parameter kedua (nilai default)
        $status = $request->query('status', 'tersedia');

        $dataTugas = Tugas::with(['asn', 'attachments'])
            // Karena $status sekarang memiliki default, filter ini akan otomatis
            // menjalankan where('status', 'tersedia') jika tidak ada parameter di URL
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->get(); // Jika data semakin banyak, pertimbangkan mengganti get() dengan paginate(10)

        // Kirim dataTugas ke view
        return view('pages.mahasiswa.tugas.index', compact('dataTugas'));
    }
    public function tugasSaya(Request $request)
    {
        $profil = Auth::user()->mahasiswaProfile;

        // Tambahkan 'diambil' sebagai parameter kedua (nilai default)
        $statusTugas = $request->query('status', 'diambil');

        // Mahasiswa yang belum melengkapi profil belum bisa "punya" tugas apa pun.
        if (!$profil) {
            return view('pages.mahasiswa.tugas-saya.index', ['dataTugas' => collect()])->with('error', 'Lengkapi profil Anda dulu untuk bisa mengambil tugas.');
        }

        // LIFO: tugas yang terakhir masuk akan tampil terlebih dahulu.
        $dataTugas = Tugas::milikMahasiswa($profil->id)
            // Fungsi when() akan otomatis menjalankan filter where('status', 'diambil')
            // jika tidak ada parameter status di URL
            ->when($statusTugas, function ($query, $statusTugas) {
                return $query->where('status', $statusTugas);
            })
            ->with(['asn', 'skills', 'anggota.mahasiswaProfile.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.mahasiswa.tugas-saya.index', compact('dataTugas'));
    }

    public function detailTugasSaya($id)
    {
        $detailTugas = Tugas::with('anggota')->findOrFail($id);

        return view('pages.mahasiswa.tugas-saya.view', compact('detailTugas'));
    }

    public function showFormProfil()
    {
        $profil = User::with('mahasiswaProfile.skills')->findOrFail(Auth::id());

        $periodeList = PeriodeMagang::orderBy('tanggal_mulai', 'desc')->get();
        $skillList = Skill::query()->orderBy('nama_skill', 'asc')->get();

        $selectedSkillIds = $profil->mahasiswaProfile ? $profil->mahasiswaProfile->skills->pluck('id')->toArray() : [];

        return view('pages.mahasiswa.profil', compact('profil', 'periodeList', 'skillList', 'selectedSkillIds'));
    }

    public function updateProfil(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'instansi_asal' => 'required|string|max:255',
            'nim' => [
                'required',
                'string',
                Rule::unique('mahasiswa_profiles', 'nim')
                    ->where(fn($query) => $query->where('instansi_asal', $request->instansi_asal))
                    ->ignore(optional($user->mahasiswaProfile)->id),
            ],
            'jenjang' => 'nullable|in:SMA/SMK,D3,D4,S1,S2',
            'jurusan' => 'nullable|string|max:255',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'periode_magang_id' => 'nullable|exists:periode_magang,id',
            'skills' => 'nullable|array',
            'skills.*' => 'exists:skills,id',
        ]);

        $user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
        ]);

        $profile = MahasiswaProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'periode_magang_id' => $validated['periode_magang_id'] ?? null,
                'nim' => $validated['nim'],
                'instansi_asal' => $validated['instansi_asal'],
                'jenjang' => $validated['jenjang'] ?? null,
                'jurusan' => $validated['jurusan'] ?? null,
                'tanggal_mulai' => $validated['tanggal_mulai'] ?? null,
                'tanggal_selesai' => $validated['tanggal_selesai'] ?? null,
            ],
        );

        $profile->skills()->sync($validated['skills'] ?? []);

        return redirect()->route('mahasiswa-profil')->with('success', 'Profil berhasil diperbarui.');
    }
}
