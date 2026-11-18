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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AsnController extends Controller
{
    public function asnIndex()
    {
        $asnProfileId = Auth::user()->asnProfile?->id;

        $totalSelesai = Tugas::asnGetTugasSelesai()->count();
        $totalBelumSelesai = Tugas::asnGetTugasBelumSelesai()->count();

        $daftarMahasiswa = $asnProfileId
            ? MahasiswaProfile::with('user')
                ->where('asn_profile_id', $asnProfileId)
                ->aktif()
                ->denganStatistikTugas()
                ->withCount('logbookMandiriBelumValidasi')
                ->get()
            : collect();

        return view('pages.asn.index', compact('totalSelesai', 'totalBelumSelesai', 'daftarMahasiswa'));
    }

    /**
     * Menampilkan detail profil peserta magang (kandidat pengajuan maupun bimbingan aktif ASN).
     */
    public function detailMahasiswa($id)
    {
        $asnProfileId = Auth::user()->asnProfile?->id;

        if (! $asnProfileId) {
            abort(403, 'Profil ASN Anda belum terdaftar.');
        }

        $detailUser = User::query()
            ->where('id', $id)
            ->where('role', 'mahasiswa')
            ->with([
                'mahasiswaProfile.skills',
                'mahasiswaProfile.pembimbing.user',
                'mahasiswaProfile.calonPembimbing.user',
            ])
            ->firstOrFail();

        $profil = $detailUser->mahasiswaProfile;

        $berhakAkses = $profil && (
            (int) $profil->calon_asn_profile_id === (int) $asnProfileId ||
            (int) $profil->asn_profile_id === (int) $asnProfileId
        );

        if (! $berhakAkses) {
            abort(403, 'Anda tidak memiliki akses untuk melihat detail profil peserta ini.');
        }

        $anggotaKelompok = $profil->anggotaKelompok();

        return view('pages.asn.mahasiswa-detail', compact('detailUser', 'anggotaKelompok'));
    }

    /**
     * Menampilkan daftar permintaan bimbingan peserta magang yang diteruskan Admin ke ASN ini.
     */
    public function daftarPermintaanBimbingan(Request $request)
    {
        $asnProfileId = Auth::user()->asnProfile?->id;

        if (! $asnProfileId) {
            abort(403, 'Profil ASN Anda belum terdaftar.');
        }

        $filterStatus = $request->query('status', 'menunggu_asn');
        if (! in_array($filterStatus, ['menunggu_asn', 'diterima'], true)) {
            $filterStatus = 'menunggu_asn';
        }

        $daftarPermintaan = MahasiswaProfile::query()
            ->whereHas('user', fn ($q) => $q->where('role', 'mahasiswa'))
            ->where(function ($q) {
                $q->whereNull('kode_kelompok')->orWhere('is_ketua_kelompok', true);
            })
            ->when($filterStatus === 'menunggu_asn', function ($query) use ($asnProfileId) {
                $query->where('calon_asn_profile_id', $asnProfileId)
                    ->where('status_pengajuan', 'menunggu_asn');
            })
            ->when($filterStatus === 'diterima', function ($query) use ($asnProfileId) {
                $query->where('asn_profile_id', $asnProfileId)
                    ->where('status_pengajuan', 'diterima');
            })
            ->with(['user', 'skills'])
            ->orderByDesc('dikirim_ke_asn_at')
            ->orderByDesc('id')
            ->get();

        $kodeKelompokList = $daftarPermintaan->pluck('kode_kelompok')->filter()->unique()->values();
        $anggotaPerKelompok = MahasiswaProfile::query()
            ->whereIn('kode_kelompok', $kodeKelompokList)
            ->with(['user', 'skills'])
            ->orderByDesc('is_ketua_kelompok')
            ->orderBy('id')
            ->get()
            ->groupBy('kode_kelompok');

        $daftarPermintaan->each(function (MahasiswaProfile $profil) use ($anggotaPerKelompok) {
            $profil->setRelation(
                'daftar_anggota',
                $profil->isKelompok()
                    ? ($anggotaPerKelompok[$profil->kode_kelompok] ?? collect([$profil]))
                    : collect([$profil])
            );
        });

        $jumlahMasuk = MahasiswaProfile::query()
            ->where('calon_asn_profile_id', $asnProfileId)
            ->where('status_pengajuan', 'menunggu_asn')
            ->where(fn ($q) => $q->whereNull('kode_kelompok')->orWhere('is_ketua_kelompok', true))
            ->count();

        $jumlahDiterima = MahasiswaProfile::query()
            ->where('asn_profile_id', $asnProfileId)
            ->where('status_pengajuan', 'diterima')
            ->where(fn ($q) => $q->whereNull('kode_kelompok')->orWhere('is_ketua_kelompok', true))
            ->count();

        return view('pages.asn.permintaan-bimbingan', compact(
            'daftarPermintaan',
            'filterStatus',
            'jumlahMasuk',
            'jumlahDiterima'
        ));
    }

    /**
     * ASN menerima permintaan membimbing peserta (individu maupun seluruh anggota kelompok).
     */
    public function terimaPermintaanBimbingan($id)
    {
        $asnProfileId = Auth::user()->asnProfile?->id;
        $profilUtama = MahasiswaProfile::with('user')->findOrFail($id);

        if (! $asnProfileId || (int) $profilUtama->calon_asn_profile_id !== (int) $asnProfileId || $profilUtama->status_pengajuan !== 'menunggu_asn') {
            return back()->with('error', 'Permintaan bimbingan ini tidak valid atau sudah direspons sebelumnya.');
        }

        $targetProfiles = $profilUtama->isKelompok()
            ? MahasiswaProfile::where('kode_kelompok', $profilUtama->kode_kelompok)->get()
            : collect([$profilUtama]);

        DB::transaction(function () use ($targetProfiles, $profilUtama, $asnProfileId) {
            foreach ($targetProfiles as $item) {
                $mulai = $item->tanggal_mulai ?? $profilUtama->tanggal_mulai;
                $selesai = $item->tanggal_selesai ?? $profilUtama->tanggal_selesai;

                $statusBaru = MahasiswaProfile::tentukanStatusDariTanggal($mulai, $selesai, 'pending', true);

                $item->update([
                    'asn_profile_id' => $asnProfileId,
                    'calon_asn_profile_id' => null,
                    'tanggal_mulai' => $mulai,
                    'tanggal_selesai' => $selesai,
                    'status' => $statusBaru,
                    'status_pengajuan' => 'diterima',
                    'pesan_penolakan_asn' => null,
                    'alasan_penolakan' => null,
                    'direspon_asn_at' => now(),
                ]);
            }
        });

        Cache::forget('magang_status_sync_lock');

        $namaPemohon = $profilUtama->user?->name ?? 'Peserta';
        $infoKelompok = $profilUtama->isKelompok() ? " beserta seluruh anggota kelompok ({$targetProfiles->count()} peserta)" : '';

        return redirect()
            ->route('asn-permintaan-bimbingan')
            ->with('success', "Anda telah menerima {$namaPemohon}{$infoKelompok} sebagai peserta bimbingan magang Anda.");
    }

    /**
     * ASN menolak permintaan bimbingan dan mengirimkan pesan penolakan ke Admin.
     */
    public function tolakPermintaanBimbingan(Request $request, $id)
    {
        $validated = $request->validate(
            [
                'pesan_penolakan_asn' => 'required|string|min:5|max:1000',
            ],
            [
                'pesan_penolakan_asn.required' => 'Pesan penolakan wajib diisi agar Admin mengetahui alasannya.',
                'pesan_penolakan_asn.min' => 'Pesan penolakan minimal 5 karakter.',
                'pesan_penolakan_asn.max' => 'Pesan penolakan maksimal 1000 karakter.',
            ]
        );

        $asnProfileId = Auth::user()->asnProfile?->id;
        $profilUtama = MahasiswaProfile::with('user')->findOrFail($id);

        if (! $asnProfileId || (int) $profilUtama->calon_asn_profile_id !== (int) $asnProfileId || $profilUtama->status_pengajuan !== 'menunggu_asn') {
            return back()->with('error', 'Permintaan bimbingan ini tidak valid atau sudah direspons sebelumnya.');
        }

        $targetProfiles = $profilUtama->isKelompok()
            ? MahasiswaProfile::where('kode_kelompok', $profilUtama->kode_kelompok)->get()
            : collect([$profilUtama]);

        DB::transaction(function () use ($targetProfiles, $validated) {
            foreach ($targetProfiles as $item) {
                $item->update([
                    'status_pengajuan' => 'ditolak_asn',
                    'pesan_penolakan_asn' => $validated['pesan_penolakan_asn'],
                    'direspon_asn_at' => now(),
                ]);
            }
        });

        $namaPemohon = $profilUtama->user?->name ?? 'Peserta';

        return redirect()
            ->route('asn-permintaan-bimbingan')
            ->with('success', "Permintaan bimbingan untuk {$namaPemohon} telah ditolak dan pesan Anda telah dikirimkan ke Admin.");
    }

    public function logbookMahasiswa($mahasiswaProfileId)
    {
        $asnProfileId = Auth::user()->asnProfile?->id;

        $mahasiswa = MahasiswaProfile::with('user')
            ->where('asn_profile_id', $asnProfileId ?? 0)
            ->findOrFail($mahasiswaProfileId);

        $bulanList = collect();
        $tanggalAktif = $mahasiswa->getTanggalAktifLogbook();
        $jumlahHariMasuk = $tanggalAktif->count();

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
        }

        $logbookMenungguValidasi = MagangLogbook::where('mahasiswa_profile_id', $mahasiswa->id)
            ->belumValidasi()
            ->orderBy('tanggal_kegiatan', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view('pages.asn.logbook-magang', compact(
            'mahasiswa',
            'bulanList',
            'tanggalAktif',
            'jumlahHariMasuk',
            'logbookMenungguValidasi'
        ));
    }

    public function logbookMahasiswaTanggal($mahasiswaProfileId, $tanggal)
    {
        $asnProfileId = Auth::user()->asnProfile?->id;

        $mahasiswa = MahasiswaProfile::with('user')
            ->where('asn_profile_id', $asnProfileId ?? 0)
            ->findOrFail($mahasiswaProfileId);

        $logbookTugas = Logbook::where('mahasiswa_profile_id', $mahasiswa->id)
            ->whereDate('created_at', $tanggal)
            ->with(['tugas.asn', 'tugas.skills'])
            ->get();

        $logbookMandiri = MagangLogbook::where('mahasiswa_profile_id', $mahasiswa->id)
            ->tervalidasi()
            ->whereDate('tanggal_kegiatan', $tanggal)
            ->get();

        if ($logbookTugas->isEmpty() && $logbookMandiri->isEmpty()) {
            abort(404, 'Tidak ada kegiatan tervalidasi pada tanggal ini.');
        }

        $jumlahHariMasuk = $mahasiswa->getJumlahHariMasuk();

        return view('pages.asn.logbook-detail', compact(
            'mahasiswa',
            'logbookTugas',
            'logbookMandiri',
            'tanggal',
            'jumlahHariMasuk'
        ));
    }

    public function validasiLogbookMandiri($id)
    {
        $asnProfileId = Auth::user()->asnProfile?->id;
        $logbook = MagangLogbook::with('mahasiswaProfile')->findOrFail($id);

        if (! $asnProfileId || (int) $logbook->mahasiswaProfile?->asn_profile_id !== (int) $asnProfileId) {
            abort(403, 'Anda tidak memiliki akses untuk memvalidasi logbook peserta ini.');
        }

        if (! $logbook->is_validated) {
            $logbook->update([
                'is_validated' => true,
                'validated_at' => now(),
                'validated_by' => Auth::id(),
            ]);
        }

        return back()->with('success', "Logbook \"{$logbook->judul_kegiatan}\" berhasil divalidasi dan telah masuk ke logbook peserta.");
    }

    public function validasiSemuaLogbookMandiri($mahasiswaProfileId)
    {
        $asnProfileId = Auth::user()->asnProfile?->id;

        $mahasiswa = MahasiswaProfile::where('asn_profile_id', $asnProfileId ?? 0)
            ->findOrFail($mahasiswaProfileId);

        $jumlah = MagangLogbook::where('mahasiswa_profile_id', $mahasiswa->id)
            ->belumValidasi()
            ->update([
                'is_validated' => true,
                'validated_at' => now(),
                'validated_by' => Auth::id(),
            ]);

        if ($jumlah === 0) {
            return back()->with('error', 'Tidak ada logbook yang perlu divalidasi.');
        }

        return back()->with('success', "{$jumlah} logbook mandiri berhasil divalidasi.");
    }

    public function detailLampiran($id)
    {
        return $this->AsnDetailLampiran($id);
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

        if (! $logbook) {
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
        $asnProfileId = Auth::user()->asnProfile?->id;
        $skillList = Skill::orderBy('nama_skill', 'asc')->get();

        $daftarMahasiswa = $asnProfileId
            ? MahasiswaProfile::query()
                ->where('asn_profile_id', $asnProfileId)
                ->where('status', 'aktif')
                ->whereHas('user', fn ($q) => $q->where('is_active', true))
                ->with('user')
                ->get()
            : collect();

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

        if ($hari && ! $bulan) {
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
        if (! is_string($value) || ! preg_match('/^\d{4}-\d{2}$/', $value)) {
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
        if (! is_string($value) || ! preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
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
