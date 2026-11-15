<?php

namespace App\Http\Controllers;

use App\Models\AsnProfile;
use App\Models\Logbook;
use App\Models\MagangLogbook;
use App\Models\MahasiswaProfile;
use App\Models\Skill;
use App\Models\Tugas;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AsnController extends Controller
{
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

            $tanggalDariTugas = Logbook::where('mahasiswa_profile_id', $mahasiswa->id)->get()->map(fn ($item) => $item->created_at->toDateString());

            $tanggalDariMandiri = MagangLogbook::where('mahasiswa_profile_id', $mahasiswa->id)->get()->map(fn ($item) => $item->tanggal_kegiatan->toDateString());

            $tanggalAktif = collect($tanggalDariTugas)->merge($tanggalDariMandiri)->unique();
        }

        return view('pages.asn.logbook-magang', compact('mahasiswa', 'bulanList', 'tanggalAktif'));
    }

    public function logbookMahasiswaTanggal($mahasiswaProfileId, $tanggal)
    {
        $mahasiswa = MahasiswaProfile::with('user')->findOrFail($mahasiswaProfileId);

        $logbookTugas = Logbook::where('mahasiswa_profile_id', $mahasiswa->id)
            ->whereDate('created_at', $tanggal)
            ->with(['tugas.asn', 'tugas.skills'])
            ->get();

        $logbookMandiri = MagangLogbook::where('mahasiswa_profile_id', $mahasiswa->id)->whereDate('tanggal_kegiatan', $tanggal)->get();

        if ($logbookTugas->isEmpty() && $logbookMandiri->isEmpty()) {
            abort(404, 'Tidak ada kegiatan pada tanggal ini.');
        }

        return view('pages.asn.logbook-detail', compact('mahasiswa', 'logbookTugas', 'logbookMandiri', 'tanggal'));
    }

    public function AsnDetailLampiran($id)
    {
        $user = auth()->user();
        $query = MagangLogbook::where('id', $id);

        if ($user->role !== 'asn') {
            $profilId = $user->mahasiswaProfile?->id ?? 0;
            $query->where('mahasiswa_profile_id', $profilId);
        }

        $logbook = $query->first();

        if (!$logbook) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Data logbook tidak ditemukan atau Anda tidak memiliki akses.',
                ],
                404,
            );
        }

        $files = $logbook->file_lampiran;

        if (is_string($files)) {
            $files = json_decode($files, true) ?? [];
        }

        $imageUrls = [];
        if (is_array($files)) {
            foreach ($files as $file) {
                if ($file) {
                    $imageUrls[] = Storage::url($file);
                }
            }
        }

        return response()->json([
            'success' => true,
            'judul' => $logbook->judul_kegiatan,
            'data' => $imageUrls,
        ]);
    }

    public function createTugasForm()
    {
        $skillList = Skill::orderBy('nama_skill', 'asc')->get();
        $daftarMahasiswa = MahasiswaProfile::query()->where('status', 'aktif')->whereHas('user', fn ($q) => $q->where('is_active', true))->with('user')->get();

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

    public function taskDone(Request $request)
    {
        $asnId = Auth::id();

        $bulan = $this->parseValidMonth($request->query('bulan'));
        $hari = $this->parseValidDate($request->query('hari'));

        // Sinkronisasi filter hari dan bulan:
        // - Jika hari diisi tanpa bulan, otomatis isi bulan sesuai tanggal hari tersebut
        // - Jika bulan diganti dan tidak cocok dengan tanggal hari lama, reset filter hari agar menampilkan seluruh bulan baru
        if ($hari && !$bulan) {
            $bulan = substr($hari, 0, 7);
        } elseif ($bulan && $hari && substr($hari, 0, 7) !== $bulan) {
            $hari = null;
        }

        $tugasSelesai = Tugas::selesaiByAsn($asnId)
            ->filterSelesaiByPeriode($bulan, $hari)
            ->orderByDesc('selesai_at')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('pages.asn.task-done.index', compact('tugasSelesai', 'bulan', 'hari'));
    }

    public function tugasSelesaiDetail($id)
    {
        $tugasDetail = Tugas::where('id', $id)
            ->where('asn_id', Auth::id())
            ->with([
                'mahasiswaProfile.user',
                'asn',
                'anggota.mahasiswaProfile.user',
                'submissions' => function ($q) {
                    $q->latest();
                },
            ])
            ->firstOrFail();

        return view('pages.asn.task-done.view', compact('tugasDetail'));
    }

    public function profilAsnDetail()
    {
        $profil = User::with('asnProfile')->findOrFail(Auth::id());

        return view('pages.asn.profil-view', compact('profil'));
    }

    public function showFormProfil()
    {
        $profil = User::with('asnProfile')->findOrFail(Auth::id());

        return view('pages.asn.profil', compact('profil'));
    }

    public function updateProfil(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate(
            [
                'name' => 'required|string|max:255',
                'phone' => 'nullable|string|max:20',
                'nip' => 'required|string|unique:asn_profiles,nip,' . optional($user->asnProfile)->id,
                'jabatan' => 'nullable|string|max:255',
                'unit_kerja' => 'nullable|string|max:255',
                'tanggal_lahir' => 'nullable|date',
                'alamat' => 'nullable|string',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            ],
            [
                'name.required' => 'Nama lengkap wajib diisi.',
                'name.string' => 'Nama lengkap harus berupa teks.',
                'name.max' => 'Nama lengkap maksimal 255 karakter.',
                'phone.string' => 'Nomor telepon harus berupa teks.',
                'phone.max' => 'Nomor telepon maksimal 20 karakter.',
                'nip.required' => 'NIP wajib diisi.',
                'nip.string' => 'NIP harus berupa teks.',
                'nip.unique' => 'NIP sudah digunakan oleh ASN lain. Silakan gunakan NIP lain.',
                'jabatan.string' => 'Jabatan harus berupa teks.',
                'jabatan.max' => 'Jabatan maksimal 255 karakter.',
                'unit_kerja.string' => 'Unit kerja harus berupa teks.',
                'unit_kerja.max' => 'Unit kerja maksimal 255 karakter.',
                'avatar.image' => 'File foto profil harus berupa gambar.',
                'avatar.mimes' => 'Format foto profil yang diperbolehkan adalah jpeg, png, jpg, atau webp.',
                'avatar.max' => 'Ukuran foto profil tidak boleh lebih dari 2MB.',
            ],
        );

        $user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
        ]);

        $asnProfile = $user->asnProfile;
        $avatarPath = $asnProfile ? $asnProfile->avatar : null;

        if ($request->hasFile('avatar')) {
            if ($avatarPath && Storage::disk('public')->exists($avatarPath)) {
                Storage::disk('public')->delete($avatarPath);
            }

            $avatarPath = $request->file('avatar')->store('avatars/asn', 'public');
        }

        AsnProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nip' => $validated['nip'],
                'jabatan' => $validated['jabatan'] ?? null,
                'unit_kerja' => $validated['unit_kerja'] ?? null,
                'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
                'avatar' => $avatarPath,
            ],
        );

        return redirect()->route('asn-detail-profil')->with('success', 'Profil berhasil diperbarui.');
    }

    private function parseValidMonth(mixed $value): ?string
    {
        if (!is_string($value) || !preg_match('/^\d{4}-\d{2}$/', $value)) {
            return null;
        }

        try {
            $date = Carbon::createFromFormat('Y-m', $value);
            return $date && $date->format('Y-m') === $value ? $value : null;
        } catch (\Throwable) {
            return null;
        }
    }

    private function parseValidDate(mixed $value): ?string
    {
        if (!is_string($value) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return null;
        }

        try {
            $date = Carbon::createFromFormat('Y-m-d', $value);
            return $date && $date->format('Y-m-d') === $value ? $value : null;
        } catch (\Throwable) {
            return null;
        }
    }
}
