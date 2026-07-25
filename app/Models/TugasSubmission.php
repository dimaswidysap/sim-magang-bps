<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TugasSubmission extends Model
{
    use HasFactory;

    protected $table = 'tugas_submissions';

    protected $fillable = [
        'tugas_id', 'file_path', 'file_name', 'file_size', 'mime_type',
        'catatan_mahasiswa', 'status', 'catatan_asn', 'direview_oleh', 'direview_at',
    ];

    protected function casts(): array
    {
        return [
            'direview_at' => 'datetime',
        ];
    }

    public function tugas()
    {
        return $this->belongsTo(Tugas::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'direview_oleh');
    }
}
