<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Tugas;
use App\Models\TugasAnggota;
use App\Models\Logbook;


class TugasMahasiswaControllerAmbil extends Controller
{
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
        $mahasiswaProfileSaya = auth()->user()->mahasiswaProfile;

        if (!$mahasiswaProfileSaya) {
            return back()->with('error', 'Lengkapi profil Anda dulu sebelum mengambil tugas.');
        }

        // Cek 1 tugas aktif - sama persis dengan pengecekan di terimaUndangan(),
        // supaya aturannya konsisten dari 2 pintu masuk berbeda (ambil sendiri
        // vs terima undangan).
        $sudahJadiKetuaTugasAktif = Tugas::where('mahasiswa_profile_id', $mahasiswaProfileSaya->id)->where('status', '!=', 'selesai')->exists();

        $sudahJadiAnggotaTugasAktif = TugasAnggota::where('mahasiswa_profile_id', $mahasiswaProfileSaya->id)->where('status', 'diterima')->whereHas('tugas', fn($q) => $q->where('status', '!=', 'selesai'))->exists();

        if ($sudahJadiKetuaTugasAktif || $sudahJadiAnggotaTugasAktif) {
            return back()->with('error', 'Anda masih punya tugas aktif lain. Selesaikan dulu sebelum mengambil tugas baru.');
        }

        try {
            DB::transaction(function () use ($tugasId, $mahasiswaProfileSaya) {
                // lockForUpdate() - kunci baris ini, mahasiswa lain yang coba
                // ambil tugas yang sama di saat bersamaan harus antre.
                $tugas = Tugas::where('id', $tugasId)->lockForUpdate()->firstOrFail();

                if ($tugas->status !== 'tersedia') {
                    throw new \Exception('Tugas ini sudah diambil orang lain.');
                }

                $tugas->update([
                    'mahasiswa_profile_id' => $mahasiswaProfileSaya->id,
                    'status' => 'diambil',
                    'diambil_at' => now(),
                ]);
                Logbook::create([
                    'tugas_id' => $tugas->id,
                    'mahasiswa_profile_id' => $mahasiswaProfileSaya->id,
                ]);
            });
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('detail-tugas', $tugasId)->with('success', 'Tugas berhasil diambil. Selamat mengerjakan.');
    }
}
