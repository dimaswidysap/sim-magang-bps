<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MahasiswaProfile;
use App\Models\Tugas;
use App\Models\TugasAnggota;

class TugasanggotacontrollerInvite extends Controller
{
    public function formUndangAnggota($tugasId)
    {
        $tugas = Tugas::with(['anggota.mahasiswaProfile.user', 'mahasiswaProfile.user'])->findOrFail($tugasId);

        $mahasiswaProfileSaya = auth()->user()->mahasiswaProfile;

        if (!$this->bolehUndang($tugas, $mahasiswaProfileSaya)) {
            abort(403, 'Anda bukan bagian dari tugas ini, tidak bisa mengundang orang lain.');
        }

        // PERBAIKAN 1: Filter siapa saja yang disembunyikan dari daftar undangan
        // Kumpulkan ID ketua tugas
        $idSudahTerlibat = collect([$tugas->mahasiswa_profile_id]);

        // Kumpulkan ID anggota yang statusnya 'diundang' atau 'diterima'
        // Yang statusnya 'ditolak' TIDAK dimasukkan ke sini agar muncul lagi di form
        $idAnggotaTerlibat = $tugas
            ->anggota()
            ->whereIn('status', ['diundang', 'diterima'])
            ->pluck('mahasiswa_profile_id');

        // Gabungkan ID ketua dan anggota aktif
        $idSudahTerlibat = $idSudahTerlibat->merge($idAnggotaTerlibat)->filter()->toArray();

        // Ambil daftar mahasiswa yang belum terlibat
        $daftarMahasiswa = MahasiswaProfile::whereNotIn('id', $idSudahTerlibat)->where('status', 'aktif')->whereHas('user', fn($q) => $q->where('is_active', true))->with('user')->get();

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

        // PERBAIKAN 2: Validasi apakah dia sedang diundang atau sudah jadi anggota
        $isKetua = $tugas->mahasiswa_profile_id == $validated['mahasiswa_profile_id'];

        $isAnggotaAktif = $tugas
            ->anggota()
            ->where('mahasiswa_profile_id', $validated['mahasiswa_profile_id'])
            ->whereIn('status', ['diundang', 'diterima']) // Cek hanya yang aktif/menunggu
            ->exists();

        if ($isKetua || $isAnggotaAktif) {
            return back()->with('error', 'Mahasiswa ini sudah terlibat di tugas ini.');
        }

        // PERBAIKAN 3: Gunakan updateOrCreate, bukan create!
        // Jika sebelumnya dia menolak (ada di database dgn status 'ditolak'), datanya cukup di-update.
        // Jika belum ada sama sekali, data baru akan dibuat (create).
        TugasAnggota::updateOrCreate(
            [
                'tugas_id' => $tugas->id,
                'mahasiswa_profile_id' => $validated['mahasiswa_profile_id'],
            ],
            [
                'status' => 'diundang',
                'diundang_oleh' => $mahasiswaProfileSaya->id,
                'sumber' => 'undangan_teman',
            ],
        );

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
