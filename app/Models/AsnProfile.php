<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsnProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'alamat',
        'tanggal_lahir',
        'nip',
        'jabatan',
        'unit_kerja',
        'avatar',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mahasiswaBimbingan()
    {
        return $this->hasMany(MahasiswaProfile::class, 'asn_profile_id');
    }

    /**
     * Peserta magang yang sedang dibimbing (status aktif atau terjadwal pending).
     */
    public function mahasiswaBimbinganAktif()
    {
        return $this->hasMany(MahasiswaProfile::class, 'asn_profile_id')
            ->whereIn('status', ['aktif', 'pending']);
    }

    public function pengajuanLiburs()
    {
        return $this->hasMany(PengajuanLibur::class, 'asn_profile_id');
    }
}
