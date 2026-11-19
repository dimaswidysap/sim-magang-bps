<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PenerimaanMagangMail;
use App\Models\AsnProfile;
use App\Models\MahasiswaProfile;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminMahasiswa extends Controller
{
    private const DEFAULT_PASSWORD = '1sampai8';

    public function showForm()
    {
        return view('pages.admin.mahasiswa.create');
    }

    public function detailMahasiswa($id)
    {
        $detailUser = User::query()
            ->where('id', $id)
            ->where('role', 'mahasiswa')
            ->with(['mahasiswaProfile.skills', 'mahasiswaProfile.pembimbing.user', 'mahasiswaProfile.calonPembimbing.user'])
            ->firstOrFail();

        $anggotaKelompok = $detailUser->mahasiswaProfile ? $detailUser->mahasiswaProfile->anggotaKelompok() : collect();

        return view('pages.admin.mahasiswa.view', compact('detailUser', 'anggotaKelompok'));
    }

    /**
     * Menyimpan pendaftaran peserta magang baru (Mode Individu atau Kelompok)
     * dengan password default '1sampai8' tanpa pemilihan pembimbing maupun periode di awal.
     */
    public function storeMahasiswa(Request $request)
    {
        $tipePendaftaran = $request->input('tipe_pendaftaran', 'individu');

        if ($tipePendaftaran === 'kelompok') {
            $validated = $request->validate(
                [
                    'tipe_pendaftaran' => 'required|in:individu,kelompok',
                    'anggota' => 'required|array|min:2',
                    'anggota.*.name' => 'required|string|max:255',
                    'anggota.*.email' => 'required|email|distinct|unique:users,email',
                ],
                [
                    'anggota.required' => 'Data anggota kelompok wajib diisi.',
                    'anggota.min' => 'Pendaftaran kelompok minimal terdiri dari 2 peserta.',
                    'anggota.*.name.required' => 'Nama lengkap setiap anggota wajib diisi.',
                    'anggota.*.name.max' => 'Nama lengkap maksimal 255 karakter.',
                    'anggota.*.email.required' => 'Email setiap anggota wajib diisi.',
                    'anggota.*.email.email' => 'Format email anggota tidak valid.',
                    'anggota.*.email.distinct' => 'Email antar anggota dalam satu kelompok tidak boleh sama.',
                    'anggota.*.email.unique' => 'Salah satu email anggota sudah terdaftar dalam sistem.',
                ],
            );

            $kodeKelompok = 'KLP-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5));

            DB::transaction(function () use ($validated, $kodeKelompok) {
                foreach (array_values($validated['anggota']) as $index => $item) {
                    $user = User::create([
                        'name' => $item['name'],
                        'email' => $item['email'],
                        'password' => Hash::make(self::DEFAULT_PASSWORD),
                        'password_terenkripsi' => Crypt::encryptString(self::DEFAULT_PASSWORD),
                        'role' => 'mahasiswa',
                        'is_active' => true,
                        'email_sent_count' => 0,
                    ]);

                    MahasiswaProfile::create([
                        'user_id' => $user->id,
                        'asn_profile_id' => null,
                        'calon_asn_profile_id' => null,
                        'kode_kelompok' => $kodeKelompok,
                        'is_ketua_kelompok' => $index === 0,
                        'tanggal_mulai' => null,
                        'tanggal_selesai' => null,
                        'status' => 'pending',
                        'status_pengajuan' => 'belum_diajukan',
                    ]);
                }
            });

            Cache::forget('magang_status_sync_lock');

            $jumlahAnggota = count($validated['anggota']);
            $namaKetua = array_values($validated['anggota'])[0]['name'];

            return redirect()
                ->route('admin-mahasiswa')
                ->with('success', "Berhasil mendaftarkan kelompok magang ({$jumlahAnggota} peserta) dengan password default '" . self::DEFAULT_PASSWORD . "'. Peserta pertama ({$namaKetua}) ditunjuk sebagai perwakilan untuk mengajukan pembimbing setelah profil dilengkapi.");
        }

        // Mode Pendaftaran Individu
        $validated = $request->validate(
            [
                'tipe_pendaftaran' => 'nullable|in:individu,kelompok',
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
            ],
            [
                'name.required' => 'Silakan isi nama lengkap peserta.',
                'name.max' => 'Nama terlalu panjang, maksimal 255 huruf.',
                'email.required' => 'Silakan isi alamat email.',
                'email.email' => 'Mohon masukkan alamat email yang valid (contoh: user@domain.com).',
                'email.unique' => 'Email ini sudah digunakan. Silakan gunakan email yang berbeda.',
            ],
        );

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make(self::DEFAULT_PASSWORD),
                'password_terenkripsi' => Crypt::encryptString(self::DEFAULT_PASSWORD),
                'role' => 'mahasiswa',
                'is_active' => true,
                'email_sent_count' => 0,
            ]);

            MahasiswaProfile::create([
                'user_id' => $user->id,
                'asn_profile_id' => null,
                'calon_asn_profile_id' => null,
                'kode_kelompok' => null,
                'is_ketua_kelompok' => false,
                'tanggal_mulai' => null,
                'tanggal_selesai' => null,
                'status' => 'pending',
                'status_pengajuan' => 'belum_diajukan',
            ]);
        });

        Cache::forget('magang_status_sync_lock');

        return redirect()
            ->route('admin-mahasiswa')
            ->with('success', "Akun peserta magang individu berhasil dibuat dengan password default '" . self::DEFAULT_PASSWORD . "'. Peserta silakan login untuk melengkapi profil dan mengajukan pembimbing.");
    }

    /**
     * Mengirim email pemberitahuan penerimaan magang di BPS beserta informasi login (Nama Lengkap & Password)
     * ke peserta magang. Dapat dikirim lebih dari 1 kali.
     */
    public function kirimEmail($id)
    {
        $user = User::query()
            ->where('id', $id)
            ->where('role', 'mahasiswa')
            ->firstOrFail();

        $plainPassword = self::DEFAULT_PASSWORD;
        if (! empty($user->password_terenkripsi)) {
            try {
                $plainPassword = Crypt::decryptString($user->password_terenkripsi);
            } catch (\Throwable $e) {
                $plainPassword = self::DEFAULT_PASSWORD;
            }
        }

        try {
            Mail::to($user->email)->send(new PenerimaanMagangMail($user->name, $plainPassword));
        } catch (\Throwable $e) {
            return back()->with('error', "Gagal mengirim email ke {$user->name}: " . $e->getMessage());
        }

        $user->increment('email_sent_count');

        return back()->with('success', "Email pemberitahuan penerimaan & informasi login berhasil dikirim ke {$user->name} (Total terkirim: {$user->email_sent_count} kali).");
    }

    /**
     * Mereset password akun peserta magang kembali ke password default ('1sampai8').
     */
    public function resetPassword($id)
    {
        $user = User::query()
            ->where('id', $id)
            ->where('role', 'mahasiswa')
            ->firstOrFail();

        $user->update([
            'password' => Hash::make(self::DEFAULT_PASSWORD),
            'password_terenkripsi' => Crypt::encryptString(self::DEFAULT_PASSWORD),
        ]);

        return back()->with('success', "Password peserta {$user->name} berhasil direset menjadi default: " . self::DEFAULT_PASSWORD);
    }

    /**
     * Menampilkan halaman daftar permintaan pembimbing dari peserta magang
     * beserta status konfirmasi dari kandidat ASN.
     */
    public function daftarPermintaan(Request $request)
    {
        $filterStatus = $request->query('status', 'perlu_tindakan');
        $allowedStatuses = ['perlu_tindakan', 'menunggu_asn', 'diterima', 'ditolak', 'semua'];
        if (! in_array($filterStatus, $allowedStatuses, true)) {
            $filterStatus = 'perlu_tindakan';
        }

        $daftarPengajuan = MahasiswaProfile::query()
            ->whereHas('user', fn ($q) => $q->where('role', 'mahasiswa'))
            ->where(function ($q) {
                $q->whereNull('kode_kelompok')->orWhere('is_ketua_kelompok', true);
            })
            ->when($filterStatus === 'perlu_tindakan', function ($query) {
                $query->whereIn('status_pengajuan', ['menunggu_verifikasi', 'ditolak_asn']);
            })
            ->when(in_array($filterStatus, ['menunggu_asn', 'diterima', 'ditolak'], true), function ($query) use ($filterStatus) {
                $query->where('status_pengajuan', $filterStatus);
            })
            ->when($filterStatus === 'semua', function ($query) {
                $query->whereIn('status_pengajuan', ['menunggu_verifikasi', 'menunggu_asn', 'ditolak_asn', 'diterima', 'ditolak']);
            })
            ->with(['user', 'skills', 'pembimbing.user', 'calonPembimbing.user', 'verifikator'])
            ->orderByRaw("CASE status_pengajuan WHEN 'ditolak_asn' THEN 1 WHEN 'menunggu_verifikasi' THEN 2 WHEN 'menunggu_asn' THEN 3 WHEN 'ditolak' THEN 4 WHEN 'diterima' THEN 5 ELSE 6 END")
            ->orderByDesc('diajukan_at')
            ->orderByDesc('updated_at')
            ->get();

        $kodeKelompokList = $daftarPengajuan->pluck('kode_kelompok')->filter()->unique()->values();
        $anggotaPerKelompok = MahasiswaProfile::query()
            ->whereIn('kode_kelompok', $kodeKelompokList)
            ->with(['user', 'skills'])
            ->orderByDesc('is_ketua_kelompok')
            ->orderBy('id')
            ->get()
            ->groupBy('kode_kelompok');

        $daftarPengajuan->each(function (MahasiswaProfile $profil) use ($anggotaPerKelompok) {
            $profil->setRelation('daftar_anggota', $profil->isKelompok() ? ($anggotaPerKelompok[$profil->kode_kelompok] ?? collect([$profil])) : collect([$profil]));
        });

        $jumlahPerluTindakan = MahasiswaProfile::query()
            ->whereIn('status_pengajuan', ['menunggu_verifikasi', 'ditolak_asn'])
            ->where(fn ($q) => $q->whereNull('kode_kelompok')->orWhere('is_ketua_kelompok', true))
            ->count();

        $jumlahMenungguAsn = MahasiswaProfile::query()
            ->where('status_pengajuan', 'menunggu_asn')
            ->where(fn ($q) => $q->whereNull('kode_kelompok')->orWhere('is_ketua_kelompok', true))
            ->count();

        $jumlahDiterima = MahasiswaProfile::query()
            ->where('status_pengajuan', 'diterima')
            ->where(fn ($q) => $q->whereNull('kode_kelompok')->orWhere('is_ketua_kelompok', true))
            ->count();

        $jumlahDitolak = MahasiswaProfile::query()
            ->where('status_pengajuan', 'ditolak')
            ->where(fn ($q) => $q->whereNull('kode_kelompok')->orWhere('is_ketua_kelompok', true))
            ->count();

        $pembimbingList = $this->getDaftarPembimbing();

        return view('pages.admin.mahasiswa.permintaan-bimbingan', compact(
            'daftarPengajuan',
            'filterStatus',
            'jumlahPerluTindakan',
            'jumlahMenungguAsn',
            'jumlahDiterima',
            'jumlahDitolak',
            'pembimbingList'
        ));
    }

    /**
     * Mengirimkan permintaan kesediaan membimbing ke kandidat ASN yang dipilih Admin.
     * Status berubah menjadi 'menunggu_asn' sampai ASN merespons terima/tolak.
     */
    public function kirimPermintaanKeAsn(Request $request, $id)
    {
        $validated = $request->validate(
            [
                'asn_profile_id' => 'required|exists:asn_profiles,id',
            ],
            [
                'asn_profile_id.required' => 'Silakan pilih kandidat pembimbing ASN terlebih dahulu.',
                'asn_profile_id.exists' => 'Pembimbing ASN yang dipilih tidak valid.',
            ],
        );

        $profilUtama = MahasiswaProfile::with('user')->findOrFail($id);

        if (! in_array($profilUtama->status_pengajuan, ['menunggu_verifikasi', 'ditolak_asn'], true)) {
            return back()->with('error', 'Pengajuan ini tidak dapat diteruskan ke ASN pada status saat ini.');
        }

        if ($profilUtama->status_pengajuan === 'ditolak_asn' && (int) $profilUtama->calon_asn_profile_id === (int) $validated['asn_profile_id']) {
            return back()->with('error', 'ASN tersebut baru saja menolak pengajuan ini. Silakan pilih pembimbing ASN lainnya.');
        }

        $calonAsn = AsnProfile::with('user')->findOrFail($validated['asn_profile_id']);
        $targetProfiles = $profilUtama->isKelompok()
            ? MahasiswaProfile::where('kode_kelompok', $profilUtama->kode_kelompok)->get()
            : collect([$profilUtama]);

        DB::transaction(function () use ($targetProfiles, $calonAsn) {
            foreach ($targetProfiles as $item) {
                $item->update([
                    'calon_asn_profile_id' => $calonAsn->id,
                    'status_pengajuan' => 'menunggu_asn',
                    'pesan_penolakan_asn' => null,
                    'alasan_penolakan' => null,
                    'dikirim_ke_asn_at' => now(),
                    'direspon_asn_at' => null,
                    'diverifikasi_oleh' => Auth::id(),
                    'diverifikasi_at' => now(),
                ]);
            }
        });

        $namaPemohon = $profilUtama->user?->name ?? 'Peserta';
        $namaAsn = $calonAsn->user?->name ?? 'ASN';

        return redirect()
            ->route('admin-permintaan-magang')
            ->with('success', "Permintaan bimbingan untuk {$namaPemohon} berhasil dikirim ke {$namaAsn}. Menunggu konfirmasi dari ASN.");
    }

    /**
     * Menolak pengajuan magang secara langsung oleh Admin beserta alasannya.
     */
    public function tolakPermintaan(Request $request, $id)
    {
        $validated = $request->validate(
            [
                'alasan_penolakan' => 'required|string|min:5|max:1000',
            ],
            [
                'alasan_penolakan.required' => 'Alasan penolakan wajib diisi agar peserta mengetahui hal yang perlu diperbaiki.',
                'alasan_penolakan.min' => 'Alasan penolakan minimal 5 karakter.',
                'alasan_penolakan.max' => 'Alasan penolakan maksimal 1000 karakter.',
            ],
        );

        $profilUtama = MahasiswaProfile::with('user')->findOrFail($id);

        if (! in_array($profilUtama->status_pengajuan, ['menunggu_verifikasi', 'ditolak_asn'], true)) {
            return back()->with('error', 'Pengajuan ini tidak dalam status yang dapat ditolak Admin.');
        }

        $targetProfiles = $profilUtama->isKelompok()
            ? MahasiswaProfile::where('kode_kelompok', $profilUtama->kode_kelompok)->get()
            : collect([$profilUtama]);

        DB::transaction(function () use ($targetProfiles, $validated) {
            foreach ($targetProfiles as $item) {
                $item->update([
                    'calon_asn_profile_id' => null,
                    'status_pengajuan' => 'ditolak',
                    'alasan_penolakan' => $validated['alasan_penolakan'],
                    'diverifikasi_oleh' => Auth::id(),
                    'diverifikasi_at' => now(),
                ]);
            }
        });

        $namaPemohon = $profilUtama->user?->name ?? 'Peserta';

        return redirect()
            ->route('admin-permintaan-magang')
            ->with('success', "Pengajuan magang atas nama {$namaPemohon} telah ditolak beserta catatan alasannya.");
    }

    public function formMahasiswaEdit($id)
    {
        $dataUser = User::query()
            ->where('id', $id)
            ->where('role', 'mahasiswa')
            ->with(['mahasiswaProfile.skills', 'mahasiswaProfile.pembimbing.user'])
            ->firstOrFail();

        $skillList = Skill::query()->orderBy('nama_skill', 'asc')->get();
        $pembimbingList = $this->getDaftarPembimbing();

        $selectedSkillIds = $dataUser->mahasiswaProfile ? $dataUser->mahasiswaProfile->skills->pluck('id')->toArray() : [];

        return view('pages.admin.mahasiswa.update', compact('dataUser', 'skillList', 'selectedSkillIds', 'pembimbingList'));
    }

    public function updateMahasiswa(Request $request, $id)
    {
        $user = User::query()->where('id', $id)->where('role', 'mahasiswa')->firstOrFail();

        $validated = $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:8',
                'is_active' => 'required',
                'asn_profile_id' => 'nullable|exists:asn_profiles,id',
                'instansi_asal' => 'nullable|string|max:255',
                'nim' => [
                    'nullable',
                    'string',
                    Rule::unique('mahasiswa_profiles', 'nim')
                        ->where(function ($query) use ($request) {
                            return $query->where('instansi_asal', $request->instansi_asal);
                        })
                        ->ignore(optional($user->mahasiswaProfile)->id),
                ],
                'jenjang' => 'nullable|in:SMA/SMK,D3,D4,S1,S2',
                'jurusan' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:13',
                'tanggal_lahir' => 'nullable|date',
                'tanggal_mulai' => 'nullable|date',
                'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
                'status' => 'required|in:pending,aktif,selesai,dibatalkan',
                'skills' => 'nullable|array',
                'skills.*' => 'exists:skills,id',
                'alamat' => 'nullable|string',
            ],
            [
                'name.required' => 'Nama lengkap wajib diisi.',
                'name.string' => 'Nama lengkap harus berupa teks.',
                'name.max' => 'Nama lengkap maksimal 255 karakter.',
                'email.required' => 'Alamat email wajib diisi.',
                'email.email' => 'Format email tidak valid. Contoh: nama@domain.com',
                'email.unique' => 'Alamat email sudah digunakan oleh pengguna lain. Silakan gunakan email lain.',
                'password.string' => 'Kata sandi harus berupa teks.',
                'password.min' => 'Kata sandi minimal 8 karakter.',
                'is_active.required' => 'Status aktif wajib dipilih.',
                'asn_profile_id.exists' => 'Pembimbing magang yang dipilih tidak terdaftar dalam sistem.',
                'instansi_asal.string' => 'Instansi asal harus berupa teks.',
                'instansi_asal.max' => 'Instansi asal maksimal 255 karakter.',
                'nim.string' => 'NIM harus berupa teks.',
                'nim.unique' => 'NIM dengan instansi asal yang sama sudah terdaftar. Silakan gunakan NIM lain.',
                'jenjang.in' => 'Jenjang harus salah satu dari: SMA/SMK, D3, D4, S1, S2.',
                'jurusan.string' => 'Jurusan harus berupa teks.',
                'jurusan.max' => 'Jurusan maksimal 255 karakter.',
                'phone.string' => 'Nomor telepon harus berupa angka.',
                'phone.max' => 'Nomor telepon maksimal 13 karakter.',
                'tanggal_mulai.date' => 'Format tanggal mulai tidak valid.',
                'tanggal_lahir.date' => 'Format tanggal lahir tidak valid.',
                'tanggal_selesai.date' => 'Format tanggal selesai tidak valid.',
                'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama dengan atau setelah tanggal mulai.',
                'status.required' => 'Status wajib dipilih.',
                'status.in' => 'Status harus salah satu dari: pending, aktif, selesai, atau dibatalkan.',
                'skills.array' => 'Format skills tidak valid.',
                'skills.*.exists' => 'Skill yang dipilih tidak terdaftar dalam sistem.',
            ],
        );

        $sudahAdaPembimbing = ! empty($validated['asn_profile_id']);
        $statusFinal = MahasiswaProfile::tentukanStatusDariTanggal($validated['tanggal_mulai'] ?? null, $validated['tanggal_selesai'] ?? null, $validated['status'], $sudahAdaPembimbing);

        DB::transaction(function () use ($validated, $user, $statusFinal, $sudahAdaPembimbing) {
            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'is_active' => $validated['is_active'] ?? false,
            ];

            if (! empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
                $updateData['password_terenkripsi'] = Crypt::encryptString($validated['password']);
            }

            $user->update($updateData);

            $profile = MahasiswaProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'asn_profile_id' => $validated['asn_profile_id'] ?? null,
                    'calon_asn_profile_id' => $sudahAdaPembimbing ? null : $user->mahasiswaProfile?->calon_asn_profile_id,
                    'nim' => $validated['nim'] ?? null,
                    'instansi_asal' => $validated['instansi_asal'] ?? null,
                    'jenjang' => $validated['jenjang'] ?? null,
                    'jurusan' => $validated['jurusan'] ?? null,
                    'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
                    'tanggal_mulai' => $validated['tanggal_mulai'] ?? null,
                    'tanggal_selesai' => $validated['tanggal_selesai'] ?? null,
                    'status' => $statusFinal,
                    'status_pengajuan' => $sudahAdaPembimbing ? 'diterima' : ($user->mahasiswaProfile?->status_pengajuan ?? 'belum_diajukan'),
                    'alamat' => $validated['alamat'] ?? null,
                ],
            );

            $profile->skills()->sync($validated['skills'] ?? []);
        });

        Cache::forget('magang_status_sync_lock');

        return redirect()->route('admin-mahasiswa')->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    public function destroyMahasiswa(Request $request, $id)
    {
        $user = User::query()->where('id', $id)->where('role', 'mahasiswa')->firstOrFail();

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('admin-mahasiswa')->with('success', 'Data mahasiswa berhasil dihapus.');
    }

    private function getDaftarPembimbing(): Collection
    {
        return AsnProfile::query()
            ->whereHas('user', function ($query) {
                $query->where('role', 'asn')->where('is_active', true);
            })
            ->with('user')
            ->withCount(['mahasiswaBimbinganAktif as jumlah_bimbingan'])
            ->get()
            ->sortBy(fn (AsnProfile $asn) => [$asn->jumlah_bimbingan, strtolower($asn->user->name ?? '')])
            ->values();
    }
}
