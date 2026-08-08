<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TugasAttachment extends Model
{
    use HasFactory;

    protected $table = 'tugas_attachments';

    protected $fillable = [
        'tugas_id', 'file_path', 'file_name', 'file_size', 'mime_type',
    ];

    public function tugas()
    {
        return $this->belongsTo(Tugas::class);
    }
}
