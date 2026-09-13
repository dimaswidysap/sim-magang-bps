<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class Tugas extends Model
{
    use HasFactory;

    protected $table = 'tugas';

    protected $fillable = [
        'asn_id',
        'mahasiswa_profile_id',
        'judul',
        'deskripsi',
        'deadline',
        'status',
        'diambil_at',
        'selesai_at',
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'datetime',
            'diambil_at' => 'datetime',
            'selesai_at' => 'datetime',
        ];
    }

    /* -------------------------------------------------------------------------- */
    /*                                RELATIONS                                  */
    /* -------------------------------------------------------------------------- */

    public function asn(): BelongsTo
    {
        return $this->belongsTo(User::class, 'asn_id');
    }

    public function mahasiswaProfile(): BelongsTo
    {
        return $this->belongsTo(MahasiswaProfile::class);
    }

    // Relasi semua anggota (termasuk yang menolak/diundang)
    public function anggota(): HasMany
    {
        return $this->hasMany(TugasAnggota::class);
    }

    // Relasi KHUSUS anggota yang sudah menerima tugas
    public function anggotaDiterima(): HasMany
    {
        return $this->hasMany(TugasAnggota::class)->where('status', 'diterima');
    }

    // Relasi ke seluruh riwayat pengumpulan tugas
    public function submissions(): HasMany
    {
        return $this->hasMany(TugasSubmission::class);
    }

    // Relasi KHUSUS untuk mengambil 1 pengumpulan terbaru saja
    public function latestSubmission(): HasOne
    {
        return $this->hasOne(TugasSubmission::class)->latestOfMany();
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(TugasAttachment::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'tugas_skill');
    }

    /* -------------------------------------------------------------------------- */
    /*                              HELPER METHODS                               */
    /* -------------------------------------------------------------------------- */

    public function timLengkap()
    {
        $anggotaProfiles = $this->anggotaDiterima()
            ->with('mahasiswaProfile.user')
            ->get()
            ->pluck('mahasiswaProfile');

        if ($this->mahasiswaProfile) {
            return $anggotaProfiles->prepend($this->mahasiswaProfile);
        }

        return $anggotaProfiles;
    }

    public static function getJumlahTugasSelesai($mahasiswaProfileId)
    {
        return self::query()
            ->where('status', 'selesai')
            ->where(function ($query) use ($mahasiswaProfileId) {
                $query
                    ->where('mahasiswa_profile_id', $mahasiswaProfileId)
                    ->orWhereExists(function ($subquery) use ($mahasiswaProfileId) {
                        $subquery
                            ->select(DB::raw(1))
                            ->from('tugas_anggota')
                            ->whereColumn('tugas_anggota.tugas_id', 'tugas.id')
                            ->where('tugas_anggota.mahasiswa_profile_id', $mahasiswaProfileId)
                            ->where('tugas_anggota.status', 'diterima');
                    });
            })
            ->count();
    }

    public static function getJumlahTugasBelumSelesai($mahasiswaProfileId)
    {
        return self::query()
            ->where('status', 'diambil')
            ->where(function ($query) use ($mahasiswaProfileId) {
                $query
                    ->where('mahasiswa_profile_id', $mahasiswaProfileId)
                    ->orWhereExists(function ($subquery) use ($mahasiswaProfileId) {
                        $subquery
                            ->select(DB::raw(1))
                            ->from('tugas_anggota')
                            ->whereColumn('tugas_anggota.tugas_id', 'tugas.id')
                            ->where('tugas_anggota.mahasiswa_profile_id', $mahasiswaProfileId)
                            ->where('tugas_anggota.status', 'diterima');
                    });
            })
            ->count();
    }

    /* -------------------------------------------------------------------------- */
    /*                                   SCOPES                                  */
    /* -------------------------------------------------------------------------- */

    public function scopeAsnGetTugasSelesai($query)
    {
        return $query->where('status', 'selesai')->where('asn_id', auth()->id());
    }

    public function scopeAsnGetTugasBelumSelesai($query)
    {
        return $query->whereIn('status', ['diambil', 'menunggu_review'])->where('asn_id', auth()->id());
    }

    public function scopeSelesaiByAsn($query, $asnId)
    {
        return $query->where('status', 'selesai')->where('asn_id', $asnId);
    }

    public function scopeTersedia($query)
    {
        return $query->where('status', 'tersedia');
    }

    public function scopeBelumSelesai($query)
    {
        return $query->where('status', '!=', 'selesai');
    }

    public function scopeMilikAsn($query, $asnId)
    {
        return $query->where('asn_id', $asnId);
    }

    public function scopeMilikMahasiswa($query, $mahasiswaProfileId)
    {
        return $query->where(function ($q) use ($mahasiswaProfileId) {
            $q->where('mahasiswa_profile_id', $mahasiswaProfileId)
              ->orWhereHas('anggota', function ($aq) use ($mahasiswaProfileId) {
                  $aq->where('mahasiswa_profile_id', $mahasiswaProfileId)->where('status', 'diterima');
              });
        });
    }
}
