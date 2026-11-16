<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MahasiswaProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'asn_profile_id',
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
        'surat_pengantar_path',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | OTOMATISASI STATUS MAGANG BERDASARKAN TANGGAL
    |--------------------------------------------------------------------------
    */

    /**
     * Menentukan status magang berdasarkan tanggal mulai & tanggal selesai.
     * - dibatalkan: tetap dibatalkan
     * - hari ini > tanggal_selesai: otomatis 'selesai'
     * - tanggal_mulai <= hari ini <= tanggal_selesai: otomatis 'aktif'
     * - hari ini < tanggal_mulai: tetap 'pending'
     */
    public static function tentukanStatusDariTanggal(
        mixed $tanggalMulai,
        mixed $tanggalSelesai,
        string $statusSaatIni = 'pending'
    ): string {
        if ($statusSaatIni === 'dibatalkan') {
            return 'dibatalkan';
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

        // 1. Ubah status 'pending' -> 'aktif' jika sudah masuk tanggal_mulai dan belum melewati tanggal_selesai
        $diaktifkan = static::query()
            ->where('status', 'pending')
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
