<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Enums\StatusTugas;
use App\Http\Controllers\Controller;
use App\Models\Logbook;
use App\Models\Tugas;
use App\Models\TugasAnggota;
use DomainException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class TugasAnggotaControllerRespond extends Controller
{
    public function daftarUndangan()
    {
        $mahasiswaProfileSaya = auth()->user()->mahasiswaProfile;

        if ($mahasiswaProfileSaya) {
            $undangan = $mahasiswaProfileSaya
                ->undanganMenunggu()
                ->with(['tugas.asn', 'pengundang.user'])
                ->latest()
                ->get();
        } else {
            $undangan = collect();
        }

        return view('pages.mahasiswa.invitation', compact('undangan'));
    }

    public function terimaUndangan($id): RedirectResponse
    {
        $mahasiswaProfileSaya = auth()->user()->mahasiswaProfile;

        if (!$mahasiswaProfileSaya) {
            return back()->with('error', 'Lengkapi profil mahasiswa Anda terlebih dahulu.');
        }

        if ($mahasiswaProfileSaya->status !== 'aktif') {
            return back()->with('error', 'Status magang Anda tidak aktif, tidak dapat menerima undangan tugas.');
        }

        try {
            DB::transaction(function () use ($id, $mahasiswaProfileSaya) {
                $undangan = TugasAnggota::where('id', $id)
                    ->where('mahasiswa_profile_id', $mahasiswaProfileSaya->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $tugas = Tugas::where('id', $undangan->tugas_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                // 1. Cek apakah tugas sudah selesai atau melewati deadline (kedaluwarsa)
                if ($tugas->status === StatusTugas::Selesai) {
                    throw new DomainException('Tugas ini sudah selesai, undangan tidak berlaku lagi. Silakan hapus undangan.');
                }

                if ($tugas->isExpired()) {
                    throw new DomainException('Tugas sudah melewati batas waktu (deadline), undangan kedaluwarsa dan tidak dapat diterima.');
                }

                if (!in_array($tugas->status, [StatusTugas::Diambil, StatusTugas::Revisi], true)) {
                    throw new DomainException('Tugas sedang dalam proses review ASN, undangan tidak dapat diterima saat ini.');
                }

                // 2. Pastikan status undangan masih menunggu konfirmasi
                if ($undangan->status !== TugasAnggota::STATUS_DIUNDANG) {
                    throw new DomainException('Undangan ini sudah tidak berlaku atau telah direspons sebelumnya.');
                }

                // 3. Cek apakah mahasiswa sudah memiliki tugas aktif lain (sebagai ketua atau anggota)
                $sudahJadiKetuaTugasAktif = Tugas::where('mahasiswa_profile_id', $mahasiswaProfileSaya->id)
                    ->where('status', '!=', StatusTugas::Selesai)
                    ->exists();

                $sudahJadiAnggotaTugasAktif = TugasAnggota::where('mahasiswa_profile_id', $mahasiswaProfileSaya->id)
                    ->where('status', TugasAnggota::STATUS_DITERIMA)
                    ->whereHas('tugas', fn ($q) => $q->where('status', '!=', StatusTugas::Selesai))
                    ->exists();

                if ($sudahJadiKetuaTugasAktif || $sudahJadiAnggotaTugasAktif) {
                    throw new DomainException('Anda masih punya tugas aktif lain. Selesaikan dulu sebelum menerima undangan baru.');
                }

                $undangan->update([
                    'status' => TugasAnggota::STATUS_DITERIMA,
                ]);

                Logbook::firstOrCreate([
                    'tugas_id' => $undangan->tugas_id,
                    'mahasiswa_profile_id' => $mahasiswaProfileSaya->id,
                ]);
            });
        } catch (ModelNotFoundException) {
            return redirect()->route('mahasiswa-undangan')->with('error', 'Undangan tidak ditemukan.');
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', 'Terjadi kesalahan saat memproses undangan. Silakan coba lagi.');
        }

        return redirect()->route('mahasiswa-undangan')->with('success', 'Undangan diterima. Tugas masuk ke logbook Anda.');
    }

    public function tolakUndangan($id): RedirectResponse
    {
        $mahasiswaProfileSaya = auth()->user()->mahasiswaProfile;

        if (!$mahasiswaProfileSaya) {
            return back()->with('error', 'Lengkapi profil mahasiswa Anda terlebih dahulu.');
        }

        $pesanSukses = 'Undangan ditolak.';

        try {
            DB::transaction(function () use ($id, $mahasiswaProfileSaya, &$pesanSukses) {
                $undangan = TugasAnggota::where('id', $id)
                    ->where('mahasiswa_profile_id', $mahasiswaProfileSaya->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($undangan->status !== TugasAnggota::STATUS_DIUNDANG) {
                    throw new DomainException('Undangan ini sudah tidak berlaku atau telah direspons sebelumnya.');
                }

                $tugas = Tugas::where('id', $undangan->tugas_id)
                    ->lockForUpdate()
                    ->first();

                // Jika tugas sudah selesai, dihapus, atau deadline terlewat -> hapus record undangan
                if (!$tugas || $tugas->status === StatusTugas::Selesai || $tugas->isExpired()) {
                    $undangan->delete();
                    $pesanSukses = 'Undangan kedaluwarsa berhasil dihapus.';
                    return;
                }

                // Jika tugas masih aktif -> ubah status menjadi ditolak
                $undangan->update([
                    'status' => TugasAnggota::STATUS_DITOLAK,
                ]);
            });
        } catch (ModelNotFoundException) {
            return redirect()->route('mahasiswa-undangan')->with('error', 'Undangan tidak ditemukan.');
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', 'Terjadi kesalahan saat memproses undangan. Silakan coba lagi.');
        }

        return redirect()->route('mahasiswa-undangan')->with('success', $pesanSukses);
    }
}
