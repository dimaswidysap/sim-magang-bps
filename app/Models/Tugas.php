<?php

namespace App\Models;

use App\Enums\StatusTugas;
use Carbon\Carbon;
use DomainException;
use Illuminate\Database\Eloquent\Builder;
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

    protected $fillable = ['asn_id', 'mahasiswa_profile_id', 'judul', 'deskripsi', 'deadline', 'status', 'sumber', 'diambil_at', 'selesai_at'];

    protected function casts(): array
    {
        return [
            'deadline' => 'datetime',
            'diambil_at' => 'datetime',
            'selesai_at' => 'datetime',
            'status' => StatusTugas::class,
        ];
    }

    /* -------------------------------------------------------------------------- */
    /*                                 RELATIONS                                  */
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
    /*                          STATE MACHINE & GUARDS                            */
    /* -------------------------------------------------------------------------- */

    /**
     * Memastikan transisi status sah sesuai aturan state machine.
     */
    public function assertCanTransitionTo(StatusTugas $next, ?string $customMessage = null): void
    {
        if (!$this->status->canTransitionTo($next)) {
            throw new DomainException($customMessage ?? "Perubahan status dari '{$this->status->label()}' ke '{$next->label()}' tidak diizinkan.");
        }
    }

    /**
     * Mengecek apakah tugas sudah melewati batas waktu deadline.
     */
    public function isExpired(): bool
    {
        return $this->deadline !== null && now()->greaterThan($this->deadline);
    }

    /**
     * Mengecek apakah tugas masih tersedia dan belum lewat deadline.
     */
    public function canBeClaimed(): bool
    {
        return $this->status === StatusTugas::Tersedia && !$this->isExpired();
    }

    /* -------------------------------------------------------------------------- */
    /*                               HELPER METHODS                               */
    /* -------------------------------------------------------------------------- */

    public function timLengkap()
    {
        $anggotaProfiles =$this->anggotaDiterima()->with('mahasiswaProfile.user')->get()->pluck('mahasiswaProfile');

        if ($this->mahasiswaProfile) {
            return $anggotaProfiles->prepend($this->mahasiswaProfile);
        }

        return $anggotaProfiles;
    }

    public static function getJumlahTugasSelesai($mahasiswaProfileId): int
    {
        return self::query()
            ->where('status', StatusTugas::Selesai)
            ->where(function ($query) use ($mahasiswaProfileId) {
                $query->where('mahasiswa_profile_id',$mahasiswaProfileId)->orWhereExists(function ($subquery) use ($mahasiswaProfileId) {
                    $subquery->select(DB::raw(1))->from('tugas_anggota')->whereColumn('tugas_anggota.tugas_id', 'tugas.id')->where('tugas_anggota.mahasiswa_profile_id',$mahasiswaProfileId)->where('tugas_anggota.status', 'diterima');
                });
            })
            ->count();
    }

    public static function getJumlahTugasBelumSelesai($mahasiswaProfileId): int
    {
        return self::query()
            ->whereIn('status', [StatusTugas::Diambil, StatusTugas::MenungguReview, StatusTugas::Revisi])
            ->where(function ($query) use ($mahasiswaProfileId) {
                $query->where('mahasiswa_profile_id',$mahasiswaProfileId)->orWhereExists(function ($subquery) use ($mahasiswaProfileId) {
                    $subquery->select(DB::raw(1))->from('tugas_anggota')->whereColumn('tugas_anggota.tugas_id', 'tugas.id')->where('tugas_anggota.mahasiswa_profile_id',$mahasiswaProfileId)->where('tugas_anggota.status', 'diterima');
                });
            })
            ->count();
    }

    /* -------------------------------------------------------------------------- */
    /*                                   SCOPES                                   */
    /* -------------------------------------------------------------------------- */

    public function scopeAsnGetTugasSelesai(Builder $query): Builder
    {
        return $query->where('status', StatusTugas::Selesai)->where('asn_id', auth()->id());
    }

    public function scopeAsnGetTugasBelumSelesai(Builder $query): Builder
    {
        return $query->whereIn('status', [StatusTugas::Diambil, StatusTugas::MenungguReview, StatusTugas::Revisi])->where('asn_id', auth()->id());
    }

    public function scopeSelesaiByAsn(Builder $query, int$asnId): Builder
    {
        return $query->where('status', StatusTugas::Selesai)->where('asn_id',$asnId);
    }

    /**
     * Filter tugas selesai berdasarkan tanggal spesifik (Y-m-d) dan/atau bulan (Y-m).
     */
    public function scopeFilterSelesaiByPeriode(Builder $query, ?string $bulan = null, ?string $hari = null): Builder
    {
        if (!empty($hari)) {
            return $query->whereDate('selesai_at',$hari);
        }

        if (!empty($bulan)) {
            $parsedBulan = Carbon::createFromFormat('Y-m',$bulan);

            return $query
                ->whereYear('selesai_at', $parsedBulan->year)
                ->whereMonth('selesai_at', $parsedBulan->month);
        }

        return $query;
    }

    public function scopeTersedia(Builder $query): Builder
    {
        return $query->where('status', StatusTugas::Tersedia)->where('deadline', '>', now());
    }

    public function scopeBelumSelesai(Builder $query): Builder
    {
        return $query->where('status', '!=', StatusTugas::Selesai);
    }

    public function scopeMilikAsn(Builder $query, int$asnId): Builder
    {
        return $query->where('asn_id',$asnId);
    }

    public function scopeMilikMahasiswa(Builder $query, int$mahasiswaProfileId): Builder
    {
        return $query->where(function ($q) use ($mahasiswaProfileId) {
            $q->where('mahasiswa_profile_id',$mahasiswaProfileId)->orWhereHas('anggota', function ($aq) use ($mahasiswaProfileId) {
                $aq->where('mahasiswa_profile_id',$mahasiswaProfileId)->where('status', 'diterima');
            });
        });
    }
}
