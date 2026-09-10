<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsnProfile extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','alamat', 'tanggal_lahir', 'nip', 'jabatan', 'unit_kerja'];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
