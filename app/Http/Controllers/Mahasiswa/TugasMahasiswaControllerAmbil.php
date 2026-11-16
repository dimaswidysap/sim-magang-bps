<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Enums\StatusTugas;
use App\Http\Controllers\Controller;
use App\Models\Logbook;
use App\Models\Tugas;
use App\Services\PenugasanService;
use DomainException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class TugasMahasiswaControllerAmbil extends Controller
{
    public function __construct(protected PenugasanService $penugasanService) {}

    public function daftarTugasTersedia()
    {
        $profil = auth()->user()->mahasiswaProfile?->loadMissing('pembimbing');

        $tugasTersedia = Tugas::tersedia()
            ->untukPembimbingMahasiswa($profil)
            ->with(['asn', 'skills'])
            ->orderBy('deadline')
            ->get();

        return view('pages.mahasiswa.tugas.index', compact('tugasTersedia'));
    }

    public function ambilTugas($tugasId)
    {
        $profil = auth()->user()->mahasiswaProfile?->loadMissing('pembimbing');
        $mahasiswaProfileId = $profil?->id;

        if (!$mahasiswaProfileId) {
            return back()->with('error', 'Lengkapi profil Anda dulu sebelum mengambil tugas.');
        }

        $pembimbingUserId = $profil->pembimbing?->user_id;

        if (!$pembimbingUserId) {
            return back()->with('error', 'Anda belum memiliki pembimbing magang sehingga belum dapat mengambil tugas.');
        }

        try {
            DB::transaction(function () use ($tugasId, $mahasiswaProfileId, $pembimbingUserId) {
                // 1. Lock profil mahasiswa & cek aturan aktif + kuota 1 tugas di dalam transaksi
                $this->penugasanService->validasiBolehAmbilTugasMandiri($mahasiswaProfileId);

                // 2. Lock baris tugas agar tidak diambil 2 mahasiswa secara bersamaan
                $tugas = Tugas::where('id', $tugasId)->lockForUpdate()->firstOrFail();

                // 3. Pastikan tugas diterbitkan oleh pembimbing ASN mahasiswa yang bersangkutan
                if ((int) $tugas->asn_id !== (int) $pembimbingUserId) {
                    throw new DomainException('Tugas ini bukan diterbitkan oleh pembimbing magang Anda.');
                }

                // 4. Validasi deadline tugas
                if ($tugas->isExpired()) {
                    throw new DomainException('Tugas ini sudah melewati batas waktu (deadline) dan tidak dapat diambil.');
                }

                // 5. Validasi transisi status via state machine
                if (!$tugas->status->canTransitionTo(StatusTugas::Diambil)) {
                    throw new DomainException('Tugas ini sudah diambil orang lain atau tidak lagi tersedia.');
                }

                $tugas->update([
                    'mahasiswa_profile_id' => $mahasiswaProfileId,
                    'status' => StatusTugas::Diambil,
                    'sumber' => 'mandiri',
                    'diambil_at' => now(),
                ]);

                Logbook::firstOrCreate([
                    'tugas_id' => $tugas->id,
                    'mahasiswa_profile_id' => $mahasiswaProfileId,
                ]);
            });
        } catch (ModelNotFoundException) {
            return back()->with('error', 'Tugas tidak ditemukan atau sudah dihapus.');
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        } catch (Throwable $e) {
            Log::error('Gagal mengambil tugas', [
                'tugas_id' => $tugasId,
                'mahasiswa_profile_id' => $mahasiswaProfileId,
                'exception' => $e,
            ]);

            return back()->with('error', 'Terjadi kesalahan sistem saat mengambil tugas. Silakan coba beberapa saat lagi.');
        }

        return redirect()->route('detail-tugas', $tugasId)->with('success', 'Tugas berhasil diambil. Selamat mengerjakan.');
    }
}
