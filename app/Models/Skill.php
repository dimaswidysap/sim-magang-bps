<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = ['nama_skill'];

    public function mahasiswaProfiles()
    {
        return $this->belongsToMany(MahasiswaProfile::class, 'mahasiswa_profile_skill');
    }

    public function tugas()
    {
        return $this->belongsToMany(Tugas::class, 'tugas_skill');
    }
}
