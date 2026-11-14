<?php

namespace App\Enums;

enum StatusTugas: string
{
    case Tersedia = 'tersedia';
    case Diambil = 'diambil';
    case MenungguReview = 'menunggu_review';
    case Revisi = 'revisi';
    case Selesai = 'selesai';

    public function label(): string
    {
        return match ($this) {
            self::Tersedia => 'Tersedia',
            self::Diambil => 'Sedang Dikerjakan',
            self::MenungguReview => 'Menunggu Review',
            self::Revisi => 'Perlu Revisi',
            self::Selesai => 'Selesai',
        };
    }

    /**
     * Guard aturan transisi status (state machine).
     */
    public function canTransitionTo(self $next): bool
    {
        if ($this === $next) {
            return true;
        }

        return match ($this) {
            self::Tersedia => in_array($next, [self::Diambil], true),
            // Hanya boleh kembali ke Tersedia jika ASN membatalkan penugasan sebelum masuk tahap review
            self::Diambil => in_array($next, [self::Tersedia, self::MenungguReview, self::Selesai], true),
            self::MenungguReview => in_array($next, [self::Revisi, self::Selesai], true),
            self::Revisi => in_array($next, [self::MenungguReview, self::Selesai], true),
            self::Selesai => false,
        };
    }

    public function isFinal(): bool
    {
        return $this === self::Selesai;
    }

    public function canModifyAssignment(): bool
    {
        return !$this->isFinal();
    }
}
