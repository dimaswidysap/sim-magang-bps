<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tugas extends Model
{
    use HasFactory;

    protected $table = 'tugas';

    protected $fillable = ['asn_id', 'periode_magang_id', 'mahasiswa_profile_id', 'judul', 'deskripsi', 'deadline', 'status', 'diambil_at', 'selesai_at'];

    protected function casts(): array
    {
        return [
            'deadline' => 'datetime',
            'diambil_at' => 'datetime',
            'selesai_at' => 'datetime',
        ];
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
        return self::query()->where('status', 'diambil')
            ->where(function ($query) use ($mahasiswaProfileId) {
                $query->where('mahasiswa_profile_id', $mahasiswaProfileId)->orWhereExists(function ($subquery) use ($mahasiswaProfileId) {
                    $subquery->select(DB::raw(1))
                        ->from('tugas_anggota')
                        ->whereColumn('tugas_anggota.tugas_id', 'tugas.id')
                        ->where('tugas_anggota.mahasiswa_profile_id', $mahasiswaProfileId)
                        ->where('tugas_anggota.status', 'diterima');
                });
            })
            ->count();
    }

    public function scopeAsnGetTugasSelesai($query)
    {
        return $query->where('status', 'selesai')
                     ->where('asn_id', auth()->id());
    }

    public function scopeAsnGetTugasBelumSelesai($query)
    {
        return $query->whereIn('status', ['diambil', 'menunggu_review'])
                     ->where('asn_id', auth()->id());
    }

    public function scopeSelesaiByAsn($query, $asnId)
    {
        return $query->where('status', 'selesai')->where('asn_id', $asnId);
    }

    public function asn()
    {
        return $this->belongsTo(User::class, 'asn_id');
    }

    public function periodeMagang()
    {
        return $this->belongsTo(PeriodeMagang::class);
    }

    public function mahasiswaProfile()
    {
        return $this->belongsTo(MahasiswaProfile::class);
    }

    // Relasi semua anggota (termasuk yang menolak/diundang)
    public function anggota()
    {
        return $this->hasMany(TugasAnggota::class);
    }

    // Relasi KHUSUS anggota yang sudah menerima tugas
    public function anggotaDiterima()
    {
        return $this->hasMany(TugasAnggota::class)->where('status', 'diterima');
    }

    public function timLengkap()
    {
        $anggotaProfiles = $this->anggotaDiterima()->with('mahasiswaProfile.user')->get()->pluck('mahasiswaProfile');

        if ($this->mahasiswaProfile) {
            return $anggotaProfiles->prepend($this->mahasiswaProfile);
        }

        return $anggotaProfiles;
    }

    public function submissions()
    {
        return $this->hasMany(TugasSubmission::class);
    }

    public function attachments()
    {
        return $this->hasMany(TugasAttachment::class);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'tugas_skill');
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
