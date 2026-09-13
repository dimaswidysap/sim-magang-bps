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

        // Filter siapa saja yang disembunyikan dari daftar undangan
        $idSudahTerlibat = collect([$tugas->mahasiswa_profile_id]);

        // Kumpulkan ID anggota yang statusnya 'diundang' atau 'diterima'
        $idAnggotaTerlibat = $tugas
            ->anggota()
            ->whereIn('status', ['diundang', 'diterima'])
            ->pluck('mahasiswa_profile_id');

        // Gabungkan ID ketua dan anggota aktif
        $idSudahTerlibat = $idSudahTerlibat->merge($idAnggotaTerlibat)->filter()->toArray();

        // Ambil daftar mahasiswa yang belum terlibat
        $daftarMahasiswa = MahasiswaProfile::whereNotIn('id', $idSudahTerlibat)
            ->where('status', 'aktif')
            ->whereHas('user', fn($q) => $q->where('is_active', true))
            ->with('user')
            ->get();

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

        // Validasi: Pastikan input berbentuk Array dan minimal pilih 1
        $validated = $request->validate([
            'mahasiswa_profile_ids'   => 'required|array|min:1',
            'mahasiswa_profile_ids.*' => 'exists:mahasiswa_profiles,id',
        ], [
            'mahasiswa_profile_ids.required' => 'Pilihlah setidaknya satu mahasiswa untuk diundang.',
            'mahasiswa_profile_ids.min'      => 'Pilihlah setidaknya satu mahasiswa untuk diundang.',
        ]);

        $invitedCount = 0;

        // Loop setiap ID mahasiswa yang dipilih
        foreach ($validated['mahasiswa_profile_ids'] as $mhsId) {
            $mhsId = (int) $mhsId;

            // Cegah undang diri sendiri
            if ($mhsId === $mahasiswaProfileSaya->id) {
                continue;
            }

            // Validasi apakah dia ketua / sedang aktif
            $isKetua = $tugas->mahasiswa_profile_id == $mhsId;
            $isAnggotaAktif = $tugas->anggota()
                ->where('mahasiswa_profile_id', $mhsId)
                ->whereIn('status', ['diundang', 'diterima'])
                ->exists();

            if ($isKetua || $isAnggotaAktif) {
                continue;
            }

            // Update atau buat undangan baru
            TugasAnggota::updateOrCreate(
                [
                    'tugas_id' => $tugas->id,
                    'mahasiswa_profile_id' => $mhsId,
                ],
                [
                    'status' => 'diundang',
                    'diundang_oleh' => $mahasiswaProfileSaya->id,
                    'sumber' => 'undangan_teman',
                ]
            );

            $invitedCount++;
        }

        if ($invitedCount === 0) {
            return back()->with('error', 'Tidak ada mahasiswa baru yang berhasil diundang (mungkin sudah menjadi anggota/ketua).');
        }

        return redirect()->route('tugas-saya')->with('success', "Berhasil mengundang {$invitedCount} mahasiswa ke tugas ini.");
    }

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
