<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MahasiswaProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'asn_profile_id',
        'calon_asn_profile_id',
        'kode_kelompok',
        'is_ketua_kelompok',
        'foto_profil_path',
        'nim',
        'instansi_asal',
        'alamat',
        'jenjang',
        'jurusan',
        'tanggal_lahir',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'status_pengajuan',
        'alasan_penolakan',
        'pesan_penolakan_asn',
        'diajukan_at',
        'dikirim_ke_asn_at',
        'direspon_asn_at',
        'diverifikasi_oleh',
        'diverifikasi_at',
        'surat_pengantar_path',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'is_ketua_kelompok' => 'boolean',
            'tanggal_lahir' => 'date',
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'diajukan_at' => 'datetime',
            'dikirim_ke_asn_at' => 'datetime',
            'direspon_asn_at' => 'datetime',
            'diverifikasi_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIKA KELOMPOK & PENGAJUAN PEMBIMBING
    |--------------------------------------------------------------------------
    */

    public function isKelompok(): bool
    {
        return ! empty($this->kode_kelompok);
    }

    /**
     * Mengambil seluruh anggota dalam kelompok yang sama (diurutkan dari peserta pertama masuk).
     */
    public function anggotaKelompok()
    {
        if (! $this->isKelompok()) {
            return collect([$this]);
        }

        return static::query()
            ->where('kode_kelompok', $this->kode_kelompok)
            ->with('user')
            ->orderByDesc('is_ketua_kelompok')
            ->orderBy('id')
            ->get();
    }

    /**
     * Mengecek apakah profil ini berhak menekan tombol "Ajukan Pembimbing":
     * - Untuk individu: akun tersebut sendiri.
     * - Untuk kelompok: hanya data peserta pertama yang didaftarkan (is_ketua_kelompok = true).
     */
    public function adalahPengajuUtama(): bool
    {
        if (! $this->isKelompok()) {
            return true;
        }

        return (bool) $this->is_ketua_kelompok;
    }

    /**
     * Mengecek apakah tombol "Ajukan Pembimbing" dapat ditampilkan dan diklik.
     */
    public function bisaMengajukanPembimbing(): bool
    {
        if (! $this->user || ! $this->user->isProfileComplete()) {
            return false;
        }

        if (! $this->adalahPengajuUtama()) {
            return false;
        }

        // Jika sudah punya pembimbing dan status pengajuan diterima, tidak perlu mengajukan lagi
        if ($this->status_pengajuan === 'diterima' && ! empty($this->asn_profile_id)) {
            return false;
        }

        // Saat sedang diproses Admin maupun menunggu/ditolak internal ASN, tombol ajukan disembunyikan
        if (in_array($this->status_pengajuan, ['menunggu_verifikasi', 'menunggu_asn', 'ditolak_asn'], true)) {
            return false;
        }

        // Untuk data lama yang sudah langsung punya pembimbing tanpa alur pengajuan
        if (is_null($this->status_pengajuan) && ! empty($this->asn_profile_id)) {
            return false;
        }

        return in_array($this->status_pengajuan, ['belum_diajukan', 'ditolak', null], true);
    }

    /*
    |--------------------------------------------------------------------------
    | OTOMATISASI STATUS MAGANG BERDASARKAN TANGGAL
    |--------------------------------------------------------------------------
    */

    public static function tentukanStatusDariTanggal(
        mixed $tanggalMulai,
        mixed $tanggalSelesai,
        string $statusSaatIni = 'pending',
        bool $sudahDisetujui = true
    ): string {
        if ($statusSaatIni === 'dibatalkan') {
            return 'dibatalkan';
        }

        if (! $sudahDisetujui) {
            return 'pending';
        }

        $hariIni = Carbon::today();
        $mulai = $tanggalMulai ? Carbon::parse($tanggalMulai)->startOfDay() : null;
        $selesai = $tanggalSelesai ? Carbon::parse($tanggalSelesai)->startOfDay() : null;

        if ($selesai && $hariIni->gt($selesai)) {
            return 'selesai';
        }

        if ($mulai) {
            return $hariIni->gte($mulai) ? 'aktif' : 'pending';
        }

        return $statusSaatIni;
    }

    /**
     * Sinkronisasi massal status seluruh peserta magang berdasarkan tanggal hari ini.
     *
     * @return array{diaktifkan: int, diselesaikan: int}
     */
    public static function syncStatusOtomatis(): array
    {
        $hariIni = Carbon::today()->toDateString();

        // 1. Ubah status 'pending' -> 'aktif' hanya jika pembimbing sudah disetujui ASN
        $diaktifkan = static::query()
            ->where('status', 'pending')
            ->whereNotNull('asn_profile_id')
            ->where(function ($q) {
                $q->whereNull('status_pengajuan')
                    ->orWhere('status_pengajuan', 'diterima');
            })
            ->whereNotNull('tanggal_mulai')
            ->whereDate('tanggal_mulai', '<=', $hariIni)
            ->where(function ($query) use ($hariIni) {
                $query->whereNull('tanggal_selesai')
                    ->orWhereDate('tanggal_selesai', '>=', $hariIni);
            })
            ->update(['status' => 'aktif']);

        // 2. Ubah status 'pending' atau 'aktif' -> 'selesai' jika sudah melewati tanggal_selesai
        $diselesaikan = static::query()
            ->whereIn('status', ['pending', 'aktif'])
            ->whereNotNull('tanggal_selesai')
            ->whereDate('tanggal_selesai', '<', $hariIni)
            ->update(['status' => 'selesai']);

        return [
            'diaktifkan' => $diaktifkan,
            'diselesaikan' => $diselesaikan,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | PEMBERSIHAN OTOMATIS AKUN TANPA TANGGAL MAGANG (> 1 BULAN)
    |--------------------------------------------------------------------------
    */

    public static function hapusAkunTanpaTanggalKadaluarsa(): int
    {
        $batasWaktu = Carbon::now()->subMonth();

        $expiredUsers = User::query()
            ->where('role', 'mahasiswa')
            ->where('created_at', '<=', $batasWaktu)
            ->where(function ($query) {
                $query->whereDoesntHave('mahasiswaProfile')
                    ->orWhereHas('mahasiswaProfile', function ($subQuery) {
                        $subQuery->whereNull('tanggal_mulai')
                            ->whereNull('tanggal_selesai');
                    });
            })
            ->with('mahasiswaProfile')
            ->get();

        $jumlahDihapus = 0;

        foreach ($expiredUsers as $user) {
            DB::transaction(function () use ($user, &$jumlahDihapus) {
                $profile = $user->mahasiswaProfile;

                if ($profile) {
                    if ($profile->foto_profil_path && Storage::disk('public')->exists($profile->foto_profil_path)) {
                        Storage::disk('public')->delete($profile->foto_profil_path);
                    }

                    if ($profile->surat_pengantar_path && Storage::disk('public')->exists($profile->surat_pengantar_path)) {
                        Storage::disk('public')->delete($profile->surat_pengantar_path);
                    }

                    $profile->delete();
                }

                $user->delete();
                $jumlahDihapus++;
            });
        }

        return $jumlahDihapus;
    }

    public function belumMengisiTanggalMagang(): bool
    {
        return is_null($this->tanggal_mulai) && is_null($this->tanggal_selesai);
    }

    public function batasWaktuPengisianTanggal(): ?Carbon
    {
        if (! $this->belumMengisiTanggalMagang()) {
            return null;
        }

        $baseDate = $this->user?->created_at ?? $this->created_at;

        return $baseDate ? $baseDate->copy()->addMonth() : null;
    }

    public function sisaHariPengisianTanggal(): ?int
    {
        $batas = $this->batasWaktuPengisianTanggal();

        if (! $batas) {
            return null;
        }

        return max(0, (int) Carbon::now()->startOfDay()->diffInDays($batas->copy()->startOfDay(), false));
    }

    /*
    |--------------------------------------------------------------------------
    | REKAP KEHADIRAN / HARI MASUK BERDASARKAN LOGBOOK TERVALIDASI
    |--------------------------------------------------------------------------
    */

    public function getTanggalAktifLogbook(bool $includeLogbookTugas = true)
    {
        $tanggalDariMandiri = MagangLogbook::where('mahasiswa_profile_id', $this->id)
            ->tervalidasi()
            ->whereNotNull('tanggal_kegiatan')
            ->selectRaw('DISTINCT DATE(tanggal_kegiatan) as tanggal')
            ->pluck('tanggal');

        if (! $includeLogbookTugas) {
            return $tanggalDariMandiri->unique()->values();
        }

        $tanggalDariTugas = Logbook::where('mahasiswa_profile_id', $this->id)
            ->whereNotNull('created_at')
            ->selectRaw('DISTINCT DATE(created_at) as tanggal')
            ->pluck('tanggal');

        return $tanggalDariTugas->merge($tanggalDariMandiri)->unique()->values();
    }

    public function getJumlahHariMasuk(bool $includeLogbookTugas = true): int
    {
        return $this->getTanggalAktifLogbook($includeLogbookTugas)->count();
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getFotoProfilUrlAttribute(): string
    {
        if ($this->foto_profil_path && Storage::disk('public')->exists($this->foto_profil_path)) {
            return Storage::url($this->foto_profil_path);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->user->name ?? 'User') . '&color=7F9CF5&background=EBF4FF';
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS & SCOPES
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pembimbing()
    {
        return $this->belongsTo(AsnProfile::class, 'asn_profile_id');
    }

    public function calonPembimbing()
    {
        return $this->belongsTo(AsnProfile::class, 'calon_asn_profile_id');
    }

    public function verifikator()
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }

    public function magangLogbooks()
    {
        return $this->hasMany(MagangLogbook::class, 'mahasiswa_profile_id');
    }

    public function logbookMandiriBelumValidasi()
    {
        return $this->hasMany(MagangLogbook::class, 'mahasiswa_profile_id')
            ->where('is_validated', false);
    }

    public function scopeMahasiswaAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'mahasiswa_profile_skill');
    }

    public function tugasDiambil()
    {
        return $this->hasMany(Tugas::class);
    }

    public function undanganTugas()
    {
        return $this->hasMany(TugasAnggota::class);
    }

    public function undanganMenunggu()
    {
        return $this->hasMany(TugasAnggota::class)->where('status', 'diundang');
    }

    public function scopeAktif($query)
    {
        return $query
            ->where('status', 'aktif')
            ->whereHas('user', function ($q) {
                $q->where('is_active', true);
            });
    }

    public function scopeDenganStatistikTugas($query)
    {
        return $query->selectRaw("mahasiswa_profiles.*,
            (
                SELECT COUNT(*) FROM tugas
                WHERE tugas.mahasiswa_profile_id = mahasiswa_profiles.id
                AND tugas.status != 'selesai'
            )
            +
            (
                SELECT COUNT(*) FROM tugas_anggota
                INNER JOIN tugas ON tugas.id = tugas_anggota.tugas_id
                WHERE tugas_anggota.mahasiswa_profile_id = mahasiswa_profiles.id
                AND tugas_anggota.status = 'diterima'
                AND tugas.status != 'selesai'
            ) AS jumlah_tugas_aktif,
            (
                SELECT COUNT(*) FROM tugas
                WHERE tugas.mahasiswa_profile_id = mahasiswa_profiles.id
                AND tugas.status = 'selesai'
            )
            +
            (
                SELECT COUNT(*) FROM tugas_anggota
                INNER JOIN tugas ON tugas.id = tugas_anggota.tugas_id
                WHERE tugas_anggota.mahasiswa_profile_id = mahasiswa_profiles.id
                AND tugas_anggota.status = 'diterima'
                AND tugas.status = 'selesai'
            ) AS jumlah_tugas_selesai
        ");
    }
}
