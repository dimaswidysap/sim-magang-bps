<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TugasAnggota;
use App\Models\Tugas;

class TugasAnggotaControllerRespond extends Controller
{
    //
    public function daftarUndangan()
    {
        $mahasiswaProfileSaya = auth()->user()->mahasiswaProfile;

        $undangan = $mahasiswaProfileSaya
            ->undanganMenunggu()
            ->with(['tugas.asn', 'pengundang.user'])
            ->latest()
            ->get();

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

        $undangan->update(['status' => 'diterima']);

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
