<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Enums\StatusTugas;
use App\Http\Controllers\Controller;
use App\Models\MahasiswaProfile;
use App\Models\Tugas;
use App\Models\TugasAnggota;
use App\Services\PenugasanService;
use DomainException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TugasanggotacontrollerInvite extends Controller
{
    public function __construct(protected PenugasanService $penugasanService) {}

    public function formUndangAnggota($tugasId)
    {
        $tugas = Tugas::with(['anggota.mahasiswaProfile.user', 'mahasiswaProfile.user'])->findOrFail($tugasId);

        $mahasiswaProfileSaya = auth()->user()->mahasiswaProfile;

        if (!$this->bolehUndang($tugas, $mahasiswaProfileSaya)) {
            abort(403, 'Anda bukan bagian dari tugas ini, tidak bisa mengundang orang lain.');
        }

        if (!$tugas->status->canModifyAssignment()) {
            return redirect()->route('tugas-saya')->with('error', 'Tugas sudah selesai, tidak dapat mengundang anggota baru.');
        }

        if ($tugas->isExpired()) {
            return redirect()->route('tugas-saya')->with('error', 'Tugas sudah melewati batas waktu (deadline), tidak dapat mengundang anggota baru.');
        }

        // Kumpulkan ID ketua dan anggota yang sudah diundang atau diterima
        $idSudahTerlibat = collect([$tugas->mahasiswa_profile_id])
            ->merge(
                $tugas->anggota()
                    ->whereIn('status', [TugasAnggota::STATUS_DIUNDANG, TugasAnggota::STATUS_DITERIMA])
                    ->pluck('mahasiswa_profile_id')
            )
            ->filter()
            ->toArray();

        // Kumpulkan ID mahasiswa yang sedang terikat tugas mandiri/undangan aktif (bukan penugasan ASN)
        $idKetuaSibuk = Tugas::whereNotNull('mahasiswa_profile_id')
            ->where('status', '!=', StatusTugas::Selesai)
            ->where(function ($q) {
                $q->whereNull('sumber')->orWhere('sumber', '!=', TugasAnggota::SUMBER_DITUGASKAN_ASN);
            })
            ->pluck('mahasiswa_profile_id');

        $idAnggotaSibuk = TugasAnggota::where('status', TugasAnggota::STATUS_DITERIMA)
            ->where(function ($q) {
                $q->whereNull('sumber')->orWhere('sumber', '!=', TugasAnggota::SUMBER_DITUGASKAN_ASN);
            })
            ->whereHas('tugas', fn ($q) => $q->where('status', '!=', StatusTugas::Selesai))
            ->pluck('mahasiswa_profile_id');

        $idMahasiswaSibuk = $idKetuaSibuk->merge($idAnggotaSibuk)->unique()->toArray();

        // Ambil daftar mahasiswa aktif yang belum terlibat di tugas ini
        $daftarMahasiswa = MahasiswaProfile::whereNotIn('id', $idSudahTerlibat)
            ->where('status', 'aktif')
            ->whereHas('user', fn ($q) => $q->where('is_active', true))
            ->with('user')
            ->get()
            ->each(function ($mhs) use ($idMahasiswaSibuk) {
                $mhs->sedang_tugas_aktif = in_array($mhs->id, $idMahasiswaSibuk, true);
            });

        return view('pages.mahasiswa.tugas-saya.invite', compact('tugas', 'daftarMahasiswa'));
    }

    public function undangAnggota(Request $request, $tugasId)
    {
        $validated = $request->validate(
            [
                'mahasiswa_profile_ids' => 'required|array|min:1',
                'mahasiswa_profile_ids.*' => 'integer|distinct|exists:mahasiswa_profiles,id',
            ],
            [
                'mahasiswa_profile_ids.required' => 'Pilih minimal satu mahasiswa untuk diundang.',
                'mahasiswa_profile_ids.array' => 'Format pilihan mahasiswa tidak valid.',
                'mahasiswa_profile_ids.min' => 'Pilih minimal satu mahasiswa untuk diundang.',
                'mahasiswa_profile_ids.*.distinct' => 'Terdapat pilihan mahasiswa yang duplikat.',
                'mahasiswa_profile_ids.*.exists' => 'Salah satu mahasiswa yang dipilih tidak terdaftar.',
            ],
        );

        $pengundangProfileId = auth()->user()->mahasiswaProfile?->id;

        if (!$pengundangProfileId) {
            return back()->with('error', 'Profil Anda belum lengkap.');
        }

        $targetMahasiswaIds = array_values(array_unique(array_map('intval', $validated['mahasiswa_profile_ids'])));

        try {
            DB::transaction(function () use ($tugasId, $pengundangProfileId, $targetMahasiswaIds) {
                // 1. Lock & validasi pengundang (aktif) + seluruh target mahasiswa (aktif & tidak punya tugas mandiri aktif)
                $allIdsToLock = array_merge([$pengundangProfileId], $targetMahasiswaIds);
                $profiles = $this->penugasanService->lockDanValidasiMahasiswaAktif($allIdsToLock);
                $this->penugasanService->validasiBolehDiundangTugas($targetMahasiswaIds);

                // 2. Lock baris tugas
                $tugas = Tugas::where('id', $tugasId)
                    ->lockForUpdate()
                    ->firstOrFail();

                // 3. Validasi hak akses & status tugas
                if (!$this->bolehUndang($tugas, $profiles->get($pengundangProfileId))) {
                    throw new DomainException('Anda tidak memiliki akses untuk mengundang anggota pada tugas ini.');
                }

                if (!$tugas->status->canModifyAssignment()) {
                    throw new DomainException('Tugas sudah selesai, tidak dapat menambah anggota.');
                }

                if ($tugas->isExpired()) {
                    throw new DomainException('Tugas sudah melewati batas waktu (deadline), tidak dapat menambah anggota.');
                }

                // 4. Simpan undangan untuk setiap mahasiswa terpilih
                foreach ($targetMahasiswaIds as $targetId) {
                    $targetProfile = $profiles->get($targetId);
                    $namaTarget = $targetProfile?->nama ?? ($targetProfile?->user?->name ?? "ID #{$targetId}");

                    if ($targetId === (int) $tugas->mahasiswa_profile_id) {
                        throw new DomainException("Mahasiswa ({$namaTarget}) sudah menjadi ketua pada tugas ini.");
                    }

                    $anggotaExisting = $tugas->anggota()
                        ->where('mahasiswa_profile_id', $targetId)
                        ->first();

                    if ($anggotaExisting) {
                        if (in_array($anggotaExisting->status, [TugasAnggota::STATUS_DIUNDANG, TugasAnggota::STATUS_DITERIMA], true)) {
                            throw new DomainException("Mahasiswa ({$namaTarget}) sudah diundang atau sudah menjadi anggota tugas ini.");
                        }

                        // Jika sebelumnya 'ditolak', perbarui kembali menjadi 'diundang' agar tidak bentrok unique key
                        $anggotaExisting->update([
                            'status' => TugasAnggota::STATUS_DIUNDANG,
                            'diundang_oleh' => $pengundangProfileId,
                            'sumber' => TugasAnggota::SUMBER_UNDANGAN_TEMAN,
                        ]);
                    } else {
                        $tugas->anggota()->create([
                            'mahasiswa_profile_id' => $targetId,
                            'status' => TugasAnggota::STATUS_DIUNDANG,
                            'diundang_oleh' => $pengundangProfileId,
                            'sumber' => TugasAnggota::SUMBER_UNDANGAN_TEMAN,
                        ]);
                    }
                }
            });
        } catch (ModelNotFoundException) {
            return back()->with('error', 'Tugas tidak ditemukan atau sudah dihapus.');
        } catch (DomainException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat mengirim undangan. Silakan coba lagi.');
        }

        $jumlah = count($targetMahasiswaIds);
        return back()->with('success', "Undangan berhasil dikirim ke {$jumlah} mahasiswa.");
    }

    private function bolehUndang(Tugas $tugas, ?MahasiswaProfile $mahasiswaProfile): bool
    {
        if (!$mahasiswaProfile) {
            return false;
        }

        if ((int) $tugas->mahasiswa_profile_id === (int) $mahasiswaProfile->id) {
            return true; // ketua
        }

        return $tugas->anggota()
            ->where('mahasiswa_profile_id', $mahasiswaProfile->id)
            ->where('status', TugasAnggota::STATUS_DITERIMA)
            ->exists();
    }
}
