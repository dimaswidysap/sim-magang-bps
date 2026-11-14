<?php

namespace App\Http\Controllers\Mahasiswa;
use DomainException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MahasiswaProfile;
use App\Models\Tugas;
use App\Models\TugasAnggota;
use App\Services\PenugasanService;

class TugasanggotacontrollerInvite extends Controller
{
    // service
    public function __construct(protected PenugasanService $penugasanService) {}
    //

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
        $daftarMahasiswa = MahasiswaProfile::whereNotIn('id', $idSudahTerlibat)->where('status', 'aktif')->whereHas('user', fn($q) => $q->where('is_active', true))->with('user')->get();

        return view('pages.mahasiswa.tugas-saya.invite', compact('tugas', 'daftarMahasiswa'));
    }

    public function undangAnggota(Request $request, $tugasId)
    {
        $validated = $request->validate([
            'mahasiswa_profile_id' => 'required|exists:mahasiswa_profiles,id',
        ]);

        $ketuaProfileId = auth()->user()->mahasiswaProfile?->id;
        $targetMahasiswaId = (int) $validated['mahasiswa_profile_id'];

        if (!$ketuaProfileId) {
            return back()->with('error', 'Profil Anda belum lengkap.');
        }

        try {
            DB::transaction(function () use ($tugasId, $ketuaProfileId, $targetMahasiswaId) {
                // 1. Lock & validasi target mahasiswa (harus aktif & belum punya tugas mandiri/undangan aktif)
                $this->penugasanService->validasiBolehAmbilTugasMandiri($targetMahasiswaId, 'Mahasiswa yang diundang masih memiliki tugas aktif lain atau statusnya tidak aktif.');

                // 2. Lock baris tugas & pastikan pengundang adalah ketua tugas yang masih aktif
                $tugas = Tugas::where('id', $tugasId)->lockForUpdate()->firstOrFail();

                if ((int) $tugas->mahasiswa_profile_id !== (int) $ketuaProfileId) {
                    throw new DomainException('Hanya ketua tugas yang dapat mengundang anggota.');
                }

                if ($tugas->status === 'selesai') {
                    throw new DomainException('Tugas sudah selesai, tidak dapat menambah anggota.');
                }

                if ($targetMahasiswaId === (int) $ketuaProfileId) {
                    throw new DomainException('Anda sudah menjadi ketua pada tugas ini.');
                }

                $sudahAda = $tugas
                    ->anggota()
                    ->where('mahasiswa_profile_id', $targetMahasiswaId)
                    ->whereIn('status', ['pending', 'diterima'])
                    ->exists();

                if ($sudahAda) {
                    throw new DomainException('Mahasiswa tersebut sudah diundang atau sudah menjadi anggota tugas ini.');
                }

                $tugas->anggota()->create([
                    'mahasiswa_profile_id' => $targetMahasiswaId,
                    'status' => 'pending',
                    'diundang_oleh' => $ketuaProfileId,
                    'sumber' => 'undangan',
                ]);
            });
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Undangan berhasil dikirim ke mahasiswa.');
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
