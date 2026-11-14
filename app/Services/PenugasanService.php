<?php

namespace App\Services;

use App\Enums\StatusTugas;
use App\Models\MahasiswaProfile;
use App\Models\Tugas;
use App\Models\TugasAnggota;
use DomainException;
use Illuminate\Support\Collection;

class PenugasanService
{
    /**
     * Kunci baris MahasiswaProfile dan pastikan semuanya ada serta berstatus 'aktif'.
     * Digunakan oleh SEMUA pintu (Mandiri, Undangan, maupun Penugasan Langsung ASN).
     *
     * @param  int|array<int>  $mahasiswaProfileIds
     * @return Collection<int, MahasiswaProfile>
     */
    public function lockDanValidasiMahasiswaAktif(int|array $mahasiswaProfileIds): Collection
    {
        $ids = is_array($mahasiswaProfileIds) ? $mahasiswaProfileIds : [$mahasiswaProfileIds];
        $ids = array_values(array_unique(array_filter($ids)));

        if (empty($ids)) {
            throw new DomainException('Profil mahasiswa tidak ditemukan. Lengkapi profil terlebih dahulu.');
        }

        // Urutkan ID untuk mencegah deadlock saat mengunci banyak baris sekaligus
        sort($ids);

        $profiles = MahasiswaProfile::with('user')->whereIn('id', $ids)->lockForUpdate()->get()->keyBy('id');

        foreach ($ids as $id) {
            $profile = $profiles->get($id);

            if (!$profile) {
                throw new DomainException('Profil mahasiswa tidak ditemukan.');
            }

            if ($profile->status !== 'aktif') {
                $nama = $profile->nama ?? ($profile->user->name ?? "ID #{$id}");
                throw new DomainException("Status magang mahasiswa ({$nama}) tidak aktif. Silakan hubungi pembimbing magang.");
            }
        }

        return $profiles;
    }

    /**
     * Validasi untuk jalur mandiri & undangan:
     * 1. Mahasiswa harus aktif (dengan lock).
     * 2. Mahasiswa tidak boleh punya tugas aktif lain (KECUALI tugas yang diberikan langsung oleh ASN).
     */
    public function validasiBolehAmbilTugasMandiri(int $mahasiswaProfileId, ?string $pesanCustom = null): MahasiswaProfile
    {
        $profiles = $this->lockDanValidasiMahasiswaAktif($mahasiswaProfileId);
        $profile = $profiles->get($mahasiswaProfileId);

        // Cek apakah sedang menjadi ketua pada tugas aktif yang BUKAN penugasan langsung ASN
        $punyaTugasKetuaAktif = Tugas::where('mahasiswa_profile_id', $mahasiswaProfileId)
            ->where('status', '!=', StatusTugas::Selesai)
            ->where(function ($q) {
                $q->whereNull('sumber')->orWhere('sumber', '!=', 'ditugaskan_asn');
            })
            ->exists();

        // Cek apakah sedang menjadi anggota diterima pada tugas aktif yang BUKAN penugasan langsung ASN
        $punyaTugasAnggotaAktif = TugasAnggota::where('mahasiswa_profile_id', $mahasiswaProfileId)
            ->where('status', 'diterima')
            ->where(function ($q) {
                $q->whereNull('sumber')->orWhere('sumber', '!=', 'ditugaskan_asn');
            })
            ->whereHas('tugas', fn($q) => $q->where('status', '!=', StatusTugas::Selesai))
            ->exists();

        if ($punyaTugasKetuaAktif || $punyaTugasAnggotaAktif) {
            throw new DomainException($pesanCustom ?? 'Anda masih punya tugas aktif lain. Selesaikan dulu sebelum mengambil atau bergabung ke tugas baru.');
        }

        return $profile;
    }

    /**
     * Memastikan tugas masih valid untuk diambil/diklaim (belum melewati deadline & statusnya Tersedia).
     * Panggil setelah baris Tugas dikunci dengan lockForUpdate().
     */
    public function validasiTugasBisaDiambil(Tugas $tugas): void
    {
        if ($tugas->isExpired()) {
            throw new DomainException('Tugas ini sudah melewati batas waktu (deadline) dan tidak dapat diambil.');
        }

        $tugas->assertCanTransitionTo(StatusTugas::Diambil, 'Tugas ini sudah diambil orang lain atau tidak lagi tersedia.');
    }
}
