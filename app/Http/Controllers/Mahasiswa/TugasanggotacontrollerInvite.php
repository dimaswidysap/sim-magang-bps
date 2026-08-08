<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MahasiswaProfile;
use App\Models\Tugas;
use App\Models\TugasAnggota;

class TugasanggotacontrollerInvite extends Controller
{
    //
    public function formUndangAnggota($tugasId)
    {
        $tugas = Tugas::with(['anggota.mahasiswaProfile.user', 'mahasiswaProfile.user'])->findOrFail($tugasId);

        $mahasiswaProfileSaya = auth()->user()->mahasiswaProfile;

        // dd([
        //     'tugas_id' => $tugas->id,
        //     'tugas_mahasiswa_profile_id' => $tugas->mahasiswa_profile_id,
        //     'saya_mahasiswa_profile_id' => $mahasiswaProfileSaya?->id,
        //     'saya_null' => is_null($mahasiswaProfileSaya),
        // ]);

        if (!$this->bolehUndang($tugas, $mahasiswaProfileSaya)) {
            abort(403, 'Anda bukan bagian dari tugas ini, tidak bisa mengundang orang lain.');
        }

        // id mahasiswa yang SUDAH terlibat (ketua + semua yang pernah diundang,
        // apapun statusnya) - supaya tidak muncul lagi di daftar undangan
        $idSudahTerlibat = $tugas->anggota->pluck('mahasiswa_profile_id')->push($tugas->mahasiswa_profile_id)->filter()->toArray();

        $daftarMahasiswa = MahasiswaProfile::whereNotIn('id', $idSudahTerlibat)->whereHas('user', fn($q) => $q->where('is_active', true))->with('user')->get();

        return view('pages.mahasiswa.tugas-saya.invite', compact('tugas', 'daftarMahasiswa'));
    }

    public function undangAnggota(Request $request, $tugasId)
    {
        $tugas = Tugas::findOrFail($tugasId);
        $mahasiswaProfileSaya = auth()->user()->mahasiswaProfile;

        if (!$this->bolehUndang($tugas, $mahasiswaProfileSaya)) {
            abort(403, 'Anda bukan bagian dari tugas ini, tidak bisa mengundang orang lain.');
        }

        if ($tugas->status === 'selesai') {
            return back()->with('error', 'Tugas ini sudah selesai, tidak bisa menambah anggota lagi.');
        }

        $validated = $request->validate([
            'mahasiswa_profile_id' => 'required|exists:mahasiswa_profiles,id',
        ]);

        // Cegah undang diri sendiri
        if ((int) $validated['mahasiswa_profile_id'] === $mahasiswaProfileSaya->id) {
            return back()->with('error', 'Tidak bisa mengundang diri sendiri.');
        }

        // Cegah undang orang yang sudah jadi ketua atau sudah pernah diundang
        // (unique constraint di database juga menjaga ini, tapi dicek dulu di
        // sini supaya pesan errornya rapi, bukan error SQL mentah)
        $sudahTerlibat = $tugas->mahasiswa_profile_id == $validated['mahasiswa_profile_id'] || $tugas->anggota()->where('mahasiswa_profile_id', $validated['mahasiswa_profile_id'])->exists();

        if ($sudahTerlibat) {
            return back()->with('error', 'Mahasiswa ini sudah terlibat di tugas ini.');
        }

        TugasAnggota::create([
            'tugas_id' => $tugas->id,
            'mahasiswa_profile_id' => $validated['mahasiswa_profile_id'],
            'status' => 'diundang',
            'diundang_oleh' => $mahasiswaProfileSaya->id,
        ]);

        // Catatan: belum ada tabel notifikasi - undangan ini baru "terlihat"
        // kalau mahasiswa yang diundang buka halaman daftar undangannya sendiri.
        // Fitur notifikasi menyusul setelah alur terima/tolak selesai.

        return redirect()->route('tugas-saya')->with('success', 'Undangan berhasil dikirim.');
    }

    /**
     * Cek apakah mahasiswa ini boleh mengundang orang lain ke tugas ini -
     * yaitu kalau dia ketua ATAU anggota yang statusnya sudah diterima.
     */
    private function bolehUndang(Tugas $tugas, ?MahasiswaProfile $mahasiswaProfile): bool
    {
        if (!$mahasiswaProfile) {
            return false;
        }

        if ($tugas->mahasiswa_profile_id === $mahasiswaProfile->id) {
            return true; // ketua
        }

        return $tugas->anggota()->where('mahasiswa_profile_id', $mahasiswaProfile->id)->where('status', 'diterima')->exists();
    }
}
