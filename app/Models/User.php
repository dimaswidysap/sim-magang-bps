<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'phone', 'avatar', 'is_active'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function scopeMahasiswa($query)
    {
        return $query->where('role', 'mahasiswa');
    }

    // Scope baru untuk filter dari tabel relasi
    public function scopeProfileAktif($query)
    {
        return $query->whereHas('mahasiswaProfile', function ($q) {
            $q->where('status', 'aktif');
        });
    }
    public function scopeProfileNonAktif($query)
    {
        return $query->whereHas('mahasiswaProfile', function ($q) {
            $q->where('status', 'pending');
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
