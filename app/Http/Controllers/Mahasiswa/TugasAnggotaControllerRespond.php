<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\TugasAnggota;
use App\Models\Tugas;
use App\Models\Logbook;

class TugasAnggotaControllerRespond extends Controller
{
    //
    public function daftarUndangan()
    {
        // Ambil data profil mahasiswa
        $mahasiswaProfileSaya = auth()->user()->mahasiswaProfile;

        // Cek apakah profil mahasiswa sudah ada di database
        if ($mahasiswaProfileSaya) {
            // Jika ada, ambil data undangannya
            $undangan = $mahasiswaProfileSaya
                ->undanganMenunggu()
                ->with(['tugas.asn', 'pengundang.user'])
                ->latest()
                ->get();
        } else {
            // Jika belum ada/null, jadikan $undangan sebagai koleksi (array) kosong
            // Sehingga view tidak akan error saat mencoba melakukan looping (foreach)
            $undangan = collect();
        }

        return view('pages.mahasiswa.invitation', compact('undangan'));
    }

    public function terimaUndangan($id)
    {
        $mahasiswaProfileSaya = auth()->user()->mahasiswaProfile;

        $undangan = TugasAnggota::where('id', $id)
            ->where('mahasiswa_profile_id', $mahasiswaProfileSaya->id) // cuma pemilik undangan yang bisa respon
            ->where('status', 'diundang') // cegah terima 2x / terima undangan yang sudah ditolak sebelumnya
            ->firstOrFail();

        if ($undangan->tugas->status === 'selesai') {
            return back()->with('error', 'Tugas ini sudah selesai, undangan tidak berlaku lagi.');
        }

        // Cek: apakah mahasiswa ini sudah punya tugas aktif lain?
        // (baik sebagai ketua, maupun sebagai anggota tugas lain yang diterima)
        $sudahJadiKetuaTugasAktif = Tugas::where('mahasiswa_profile_id', $mahasiswaProfileSaya->id)->where('status', '!=', 'selesai')->exists();

        $sudahJadiAnggotaTugasAktif = TugasAnggota::where('mahasiswa_profile_id', $mahasiswaProfileSaya->id)->where('status', 'diterima')->whereHas('tugas', fn($q) => $q->where('status', '!=', 'selesai'))->exists();

        if ($sudahJadiKetuaTugasAktif || $sudahJadiAnggotaTugasAktif) {
            return back()->with('error', 'Anda masih punya tugas aktif lain. Selesaikan dulu sebelum menerima undangan baru.');
        }

        DB::transaction(function () use ($undangan, $mahasiswaProfileSaya) {
            $undangan->update(['status' => 'diterima']);

            Logbook::create([
                'tugas_id' => $undangan->tugas_id,
                'mahasiswa_profile_id' => $mahasiswaProfileSaya->id,
            ]);
        });

        return redirect()->route('mahasiswa-undangan')->with('success', 'Undangan diterima. Tugas masuk ke logbook Anda.');
    }

    public function tolakUndangan($id)
    {
        $mahasiswaProfileSaya = auth()->user()->mahasiswaProfile;

        $undangan = TugasAnggota::where('id', $id)->where('mahasiswa_profile_id', $mahasiswaProfileSaya->id)->where('status', 'diundang')->firstOrFail();

        $undangan->update(['status' => 'ditolak']);

        return redirect()->route('mahasiswa-undangan')->with('success', 'Undangan ditolak.');
    }
}
