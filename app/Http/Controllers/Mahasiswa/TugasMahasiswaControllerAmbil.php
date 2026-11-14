<?php

namespace App\Http\Controllers\Mahasiswa;
use DomainException;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Tugas;
use App\Models\TugasAnggota;
use App\Models\Logbook;
use App\Models\User;
use App\Models\MahasiswaProfile;
use App\Services\PenugasanService;

class TugasMahasiswaControllerAmbil extends Controller
{

    // service
    public function __construct(
        protected PenugasanService $penugasanService
    ) {}
    //
    public function daftarTugasTersedia()
    {
        $tugasTersedia = Tugas::tersedia()
            ->with(['asn', 'skills'])
            ->orderBy('deadline')
            ->get();

        return view('pages.mahasiswa.tugas.index', compact('tugasTersedia'));
    }

    public function ambilTugas($tugasId)
    {
        $mahasiswaProfileId = auth()->user()->mahasiswaProfile?->id;

        if (!$mahasiswaProfileId) {
            return back()->with('error', 'Lengkapi profil Anda dulu sebelum mengambil tugas.');
        }

        try {
            DB::transaction(function () use ($tugasId, $mahasiswaProfileId) {
                // 1. Lock profil mahasiswa & cek aturan aktif + kuota 1 tugas di dalam transaksi
                $this->penugasanService->validasiBolehAmbilTugasMandiri($mahasiswaProfileId);

                // 2. Lock baris tugas agar tidak diambil 2 mahasiswa secara bersamaan
                $tugas = Tugas::where('id', $tugasId)->lockForUpdate()->firstOrFail();

                if ($tugas->status !== 'tersedia') {
                    throw new DomainException('Tugas ini sudah diambil orang lain.');
                }

                $tugas->update([
                    'mahasiswa_profile_id' => $mahasiswaProfileId,
                    'status' => 'diambil',
                    'sumber' => 'mandiri',
                    'diambil_at' => now(),
                ]);

                Logbook::firstOrCreate([
                    'tugas_id' => $tugas->id,
                    'mahasiswa_profile_id' => $mahasiswaProfileId,
                ]);
            });
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('detail-tugas', $tugasId)->with('success', 'Tugas berhasil diambil. Selamat mengerjakan.');
    }
}
