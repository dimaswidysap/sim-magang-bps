<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TugasAnggota extends Model
{
    use HasFactory;

    protected $table = 'tugas_anggota';

    protected $fillable = [
        'tugas_id', 'mahasiswa_profile_id', 'status', 'diundang_oleh', 'sumber',
    ];

    public function tugas()
    {
        return $this->belongsTo(Tugas::class);
    }

    public function mahasiswaProfile()
    {
        return $this->belongsTo(MahasiswaProfile::class, 'mahasiswa_profile_id');
    }

    public function pengundang()
    {
        return $this->belongsTo(MahasiswaProfile::class, 'diundang_oleh');
    }

    public function scopeDiterima($query)
    {
        return $query->where('status', 'diterima');
    }

    public function scopeMenungguKonfirmasi($query)
    {
        return $query->where('status', 'diundang');
    }
}
