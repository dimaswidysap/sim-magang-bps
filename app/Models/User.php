<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'password_terenkripsi',
        'role',
        'phone',
        'avatar',
        'is_active',
        'email_sent_count',
    ];

    protected $hidden = [
        'password',
        'password_terenkripsi',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'email_sent_count' => 'integer',
        ];
    }

    /**
     * Mencari user berdasarkan email atau name secara case-sensitive (huruf besar/kecil harus persis sama)
     * serta memverifikasi kecocokan password.
     */
    public static function findForCaseSensitiveLogin(string $identifier, string $password): ?self
    {
        $candidates = static::query()
            ->where('email', $identifier)
            ->orWhere('name', $identifier)
            ->get()
            ->filter(fn (self $user): bool => $user->email === $identifier || $user->name === $identifier);

        foreach ($candidates as $candidate) {
            if (Hash::check($password, $candidate->password)) {
                return $candidate;
            }
        }

        return null;
    }

    /**
     * Relasi ke tabel mahasiswa_profiles
     */
    public function mahasiswaProfileCheckWarning()
    {
        return $this->hasOne(MahasiswaProfile::class, 'user_id');
    }

    /**
     * Fungsi untuk mengecek apakah data mahasiswa sudah lengkap
     */
    public function isProfileComplete(): bool
    {
        if ($this->role !== 'mahasiswa') {
            return true;
        }

        $profile = $this->mahasiswaProfile;

        if (!$profile) {
            return false;
        }

        if (empty($profile->nim) || empty($profile->instansi_asal) || empty($profile->jenjang) || empty($profile->jurusan) || empty($profile->tanggal_mulai) || empty($profile->tanggal_selesai)) {
            return false;
        }

        return true;
    }

    /**
     * Pencarian server-side untuk data pengguna (nama, email, no HP, NIM, instansi, jurusan)
     */
    public function scopeSearch(Builder $query, ?string $keyword): Builder
    {
        $keyword = trim((string) $keyword);

        if ($keyword === '') {
            return $query;
        }

        return $query->where(function (Builder $q) use ($keyword) {
            $q->where('name', 'like', "%{$keyword}%")
                ->orWhere('email', 'like', "%{$keyword}%")
                ->orWhere('phone', 'like', "%{$keyword}%")
                ->orWhereHas('mahasiswaProfile', function (Builder $mq) use ($keyword) {
                    $mq->where('nim', 'like', "%{$keyword}%")
                        ->orWhere('instansi_asal', 'like', "%{$keyword}%")
                        ->orWhere('jurusan', 'like', "%{$keyword}%");
                });
        });
    }

    public function scopeMahasiswa($query)
    {
        return $query->where('role', 'mahasiswa');
    }

    public function scopeProfileAktif($query)
    {
        return $query->whereHas('mahasiswaProfile', function ($q) {
            $q->where('status', 'aktif');
        });
    }

    public function scopeProfileNonAktif($query)
    {
        return $query->whereHas('mahasiswaProfile', function ($q) {
            $q->where('status', 'nonaktif');
        });
    }

    public function scopeProfileSelesai($query)
    {
        return $query->whereHas('mahasiswaProfile', function ($q) {
            $q->where('status', 'selesai');
        });
    }

    public function scopeProfileBatal($query)
    {
        return $query->whereHas('mahasiswaProfile', function ($q) {
            $q->where('status', 'dibatalkan');
        });
    }

    public function scopeProfilePending($query)
    {
        return $query->whereHas('mahasiswaProfile', function ($q) {
            $q->where('status', 'pending');
        });
    }

    public function scopeAsn($query)
    {
        return $query->where('role', 'asn');
    }

    public function scopeAsnAktif($query)
    {
        return $query->where('is_active', 1);
    }

    public function scopeAsnNonAktif($query)
    {
        return $query->where('is_active', 0);
    }

    public function mahasiswaProfile()
    {
        return $this->hasOne(MahasiswaProfile::class);
    }

    public function asnProfile()
    {
        return $this->hasOne(AsnProfile::class);
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }

    public function tugasDibuat()
    {
        return $this->hasMany(Tugas::class, 'asn_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isMahasiswa(): bool
    {
        return $this->role === 'mahasiswa';
    }

    public function isAsn(): bool
    {
        return $this->role === 'asn';
    }
}
