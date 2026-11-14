<?php

namespace App\Http\Controllers;

use App\Enums\StatusTugas;
use App\Models\Tugas;
use App\Models\TugasSubmission;
use DomainException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class TugasSubmissionController extends Controller
{
    public function formSubmitTugas($tugasId)
    {
        $profil = Auth::user()->mahasiswaProfile;

        if (!$profil) {
            return redirect()->route('tugas-saya')->with('error', 'Lengkapi profil mahasiswa Anda terlebih dahulu.');
        }

        try {
            $tugas = Tugas::milikMahasiswa($profil->id)
                ->with('submissions')
                ->findOrFail($tugasId);
        } catch (ModelNotFoundException) {
            return redirect()->route('tugas-saya')->with('error', 'Tugas tidak ditemukan atau Anda tidak memiliki akses ke tugas ini.');
        }

        if (!in_array($tugas->status, [StatusTugas::Diambil, StatusTugas::Revisi], true)) {
            return redirect()->route('tugas-saya')->with('error', 'Tugas ini tidak sedang dalam status yang bisa dikumpulkan.');
        }

        return view('pages.mahasiswa.sumbit-tugas', compact('tugas'));
    }

    public function storeSubmission(Request $request, $tugasId)
    {
        $profil = Auth::user()->mahasiswaProfile;

        if (!$profil) {
            return back()->with('error', 'Lengkapi profil mahasiswa Anda terlebih dahulu.');
        }

        if ($profil->status !== 'aktif') {
            return back()->with('error', 'Status magang Anda tidak aktif, tidak dapat mengumpulkan tugas.');
        }

        $validated = $request->validate(
            [
                'files' => 'nullable|array|required_without:catatan_mahasiswa',
                'files.*' => 'file|max:10240|mimes:doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,svg,webp,bmp,pdf,zip',
                'catatan_mahasiswa' => 'nullable|string|required_without:files',
            ],
            [
                'files.required_without' => 'Isi salah satu: upload file atau tulis pesan.',
                'catatan_mahasiswa.required_without' => 'Isi salah satu: upload file atau tulis pesan.',
                'files.*.file' => 'Salah satu file yang diunggah tidak valid.',
                'files.*.max' => 'Ukuran masing-masing file tidak boleh melebihi 10 MB.',
                'files.*.mimes' => 'Format file yang diunggah tidak didukung.',
            ],
        );

        $catatan = $validated['catatan_mahasiswa'] ?? null;
        $uploadedPaths = [];

        try {
            DB::transaction(function () use ($request, $profil, $tugasId, $catatan, &$uploadedPaths) {
                // 1. Kunci baris tugas terlebih dahulu untuk mencegah pengumpulan ganda bersamaan
                $tugas = Tugas::milikMahasiswa($profil->id)
                    ->where('id', $tugasId)
                    ->lockForUpdate()
                    ->firstOrFail();

                // 2. Validasi status & state machine sebelum mengunggah file ke storage
                if (!in_array($tugas->status, [StatusTugas::Diambil, StatusTugas::Revisi], true)) {
                    throw new DomainException('Tugas ini tidak sedang dalam status yang bisa dikumpulkan.');
                }

                $tugas->assertCanTransitionTo(
                    StatusTugas::MenungguReview,
                    'Status tugas tidak dapat diubah menjadi Menunggu Review.'
                );

                // 3. Simpan file lampiran hasil kerja & catat riwayat submission
                if ($request->hasFile('files')) {
                    foreach ($request->file('files') as $index => $file) {
                        $path = $file->store('tugas-submissions', 'public');
                        $uploadedPaths[] = $path;

                        TugasSubmission::create([
                            'tugas_id' => $tugas->id,
                            'file_path' => $path,
                            'file_name' => $file->getClientOriginalName(),
                            'file_size' => $file->getSize(),
                            'mime_type' => $file->getClientMimeType(),
                            'catatan_mahasiswa' => $index === 0 ? $catatan : null,
                            'status' => 'menunggu',
                        ]);
                    }
                } else {
                    TugasSubmission::create([
                        'tugas_id' => $tugas->id,
                        'file_path' => null,
                        'file_name' => null,
                        'file_size' => null,
                        'mime_type' => null,
                        'catatan_mahasiswa' => $catatan,
                        'status' => 'menunggu',
                    ]);
                }

                $tugas->update([
                    'status' => StatusTugas::MenungguReview,
                ]);
            });
        } catch (ModelNotFoundException) {
            return redirect()->route('tugas-saya')->with('error', 'Tugas tidak ditemukan atau Anda tidak terdaftar pada tugas ini.');
        } catch (DomainException $e) {
            if (!empty($uploadedPaths)) {
                Storage::disk('public')->delete($uploadedPaths);
            }
            return back()->withInput()->with('error', $e->getMessage());
        } catch (Throwable $e) {
            if (!empty($uploadedPaths)) {
                Storage::disk('public')->delete($uploadedPaths);
            }
            report($e);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat mengumpulkan tugas. Silakan coba lagi.');
        }

        return redirect()->route('tugas-saya')->with('success', 'Tugas berhasil dikumpulkan, menunggu review dari ASN.');
    }

    // ============================================================
    // BAGIAN ASN
    // ============================================================

    public function daftarSubmissionMasuk()
    {
        $tugasMenungguReview = Tugas::milikAsn(Auth::id())
            ->where('status', StatusTugas::MenungguReview)
            ->with([
                'mahasiswaProfile.user',
                'anggota' => fn ($q) => $q->where('status', 'diterima')->with('mahasiswaProfile.user'),
                'anggotaDiterima.mahasiswaProfile.user',
                'submissions' => fn ($q) => $q->orderByDesc('created_at')->orderBy('id')->limit(1),
            ])
            ->orderBy('deadline')
            ->get();

        return view('pages.asn.submission-index', compact('tugasMenungguReview'));
    }

    public function detailSubmission($tugasId)
    {
        try {
            $tugas = Tugas::query()
                ->where('id', $tugasId)
                ->where('asn_id', Auth::id())
                ->with([
                    'mahasiswaProfile.user',
                    'anggota' => fn ($q) => $q->where('status', 'diterima')->with('mahasiswaProfile.user'),
                    'anggotaDiterima.mahasiswaProfile.user',
                    'submissions' => fn ($q) => $q->orderByDesc('created_at')->orderBy('id'),
                ])
                ->firstOrFail();
        } catch (ModelNotFoundException) {
            return redirect()->route('asn-submission-index')->with('error', 'Data pengumpulan tugas tidak ditemukan.');
        }

        return view('pages.asn.submission-detail', compact('tugas'));
    }

    public function approveSubmission($submissionId)
    {
        try {
            DB::transaction(function () use ($submissionId) {
                $submission = TugasSubmission::whereHas('tugas', fn ($q) => $q->where('asn_id', Auth::id()))
                    ->findOrFail($submissionId);

                // Kunci baris Tugas untuk menjaga konsistensi state machine
                $tugas = Tugas::where('id', $submission->tugas_id)
                    ->where('asn_id', Auth::id())
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($tugas->status !== StatusTugas::MenungguReview) {
                    throw new DomainException('Tugas ini tidak sedang menunggu review.');
                }

                $tugas->assertCanTransitionTo(
                    StatusTugas::Selesai,
                    'Status tugas tidak dapat diselesaikan dari status saat ini.'
                );

                // Perbarui SEMUA baris file pada batch pengumpulan yang sedang menunggu review
                TugasSubmission::where('tugas_id', $tugas->id)
                    ->where('status', 'menunggu')
                    ->update([
                        'status' => 'disetujui',
                        'direview_oleh' => Auth::id(),
                        'direview_at' => now(),
                    ]);

                $tugas->update([
                    'status' => StatusTugas::Selesai,
                    'selesai_at' => now(),
                ]);
            });
        } catch (ModelNotFoundException) {
            return redirect()->route('asn-submission-index')->with('error', 'Data pengumpulan tugas tidak ditemukan.');
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', 'Terjadi kesalahan saat menyetujui tugas. Silakan coba lagi.');
        }

        return redirect()->route('asn-submission-index')->with('success', 'Tugas disetujui dan ditandai selesai.');
    }

    public function mintaRevisi(Request $request, $submissionId)
    {
        $validated = $request->validate(
            [
                'catatan_asn' => 'required|string',
            ],
            [
                'catatan_asn.required' => 'Jelaskan apa yang perlu diperbaiki sebelum minta revisi.',
            ],
        );

        try {
            DB::transaction(function () use ($submissionId, $validated) {
                $submission = TugasSubmission::whereHas('tugas', fn ($q) => $q->where('asn_id', Auth::id()))
                    ->findOrFail($submissionId);

                // Kunci baris Tugas untuk menjaga konsistensi state machine
                $tugas = Tugas::where('id', $submission->tugas_id)
                    ->where('asn_id', Auth::id())
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($tugas->status !== StatusTugas::MenungguReview) {
                    throw new DomainException('Tugas ini tidak sedang menunggu review.');
                }

                $tugas->assertCanTransitionTo(
                    StatusTugas::Revisi,
                    'Status tugas tidak dapat dikembalikan ke Revisi dari status saat ini.'
                );

                // Perbarui SEMUA baris file pada batch pengumpulan saat ini agar status & catatan konsisten
                TugasSubmission::where('tugas_id', $tugas->id)
                    ->where('status', 'menunggu')
                    ->update([
                        'status' => 'revisi',
                        'catatan_asn' => $validated['catatan_asn'],
                        'direview_oleh' => Auth::id(),
                        'direview_at' => now(),
                    ]);

                $tugas->update([
                    'status' => StatusTugas::Revisi,
                ]);
            });
        } catch (ModelNotFoundException) {
            return redirect()->route('asn-submission-index')->with('error', 'Data pengumpulan tugas tidak ditemukan.');
        } catch (DomainException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        } catch (Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat mengirim permintaan revisi. Silakan coba lagi.');
        }

        return redirect()->route('asn-submission-index')->with('success', 'Permintaan revisi terkirim ke mahasiswa.');
    }
}
