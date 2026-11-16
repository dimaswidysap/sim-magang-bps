<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    use HasFactory;

    protected $table = 'berita';

    protected $fillable = [
        'user_id', 'judul', 'konten',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attachments()
    {
        return $this->hasMany(BeritaAttachment::class);
    }

    public function canBeModifiedBy(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        return $user->role === 'admin' || (int) $this->user_id === (int) $user->id;
    }
}
