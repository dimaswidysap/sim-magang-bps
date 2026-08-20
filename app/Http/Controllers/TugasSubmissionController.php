<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Tugas;
use App\Models\TugasSubmission;
use Illuminate\Http\Request;

class TugasSubmissionController extends Controller
{
    public function formSubmitTugas($tugasId)
    {
        $profil = Auth::user()->mahasiswaProfile;

        $tugas = Tugas::milikMahasiswa($profil?->id ?? 0)
            ->with('submissions')
            ->findOrFail($tugasId);

        if (!in_array($tugas->status, ['diambil', 'revisi'])) {
            return back()->with('error', 'Tugas ini tidak sedang dalam status yang bisa dikumpulkan.');
        }

        return view('pages.mahasiswa.sumbit-tugas', compact('tugas'));
    }

    public function storeSubmission(Request $request, $tugasId)
    {
        $profil = Auth::user()->mahasiswaProfile;

        $tugas = Tugas::milikMahasiswa($profil?->id ?? 0)->findOrFail($tugasId);

        if (!in_array($tugas->status, ['diambil', 'revisi'])) {
            return back()->with('error', 'Tugas ini tidak sedang dalam status yang bisa dikumpulkan.');
        }

        $validated = $request->validate(
            [
                'file' => 'nullable|file|max:10240|required_without:catatan_mahasiswa|mimes:doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,svg,webp,bmp,pdf,zip',
                'catatan_mahasiswa' => 'nullable|string|required_without:file',
            ],
            [
                'file.required_without' => 'Isi salah satu: upload file atau tulis pesan.',
                'catatan_mahasiswa.required_without' => 'Isi salah satu: upload file atau tulis pesan.',
            ]
        );

        $path = null;
        $fileName = null;
        $fileSize = null;
        $mimeType = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('tugas-submissions', 'public');
            $fileName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            $mimeType = $file->getClientMimeType();
        }

        TugasSubmission::create([
            'tugas_id' => $tugas->id,
            'file_path' => $path,
            'file_name' => $fileName,
            'file_size' => $fileSize,
            'mime_type' => $mimeType,
            'catatan_mahasiswa' => $validated['catatan_mahasiswa'] ?? null,
            'status' => 'menunggu',
        ]);

        $tugas->update(['status' => 'menunggu_review']);

        return redirect()->route('tugas-saya')->with('success', 'Tugas berhasil dikumpulkan, menunggu review dari ASN.');
    }

    // ============================================================
    // BAGIAN ASN
    // ============================================================

    public function daftarSubmissionMasuk()
    {
        $tugasMenungguReview = Tugas::milikAsn(Auth::id())
            ->where('status', 'menunggu_review')
            ->with([
                'mahasiswaProfile.user',
                // PERBAIKAN UTAMA: Memaksa relasi 'anggota' HANYA memuat yang statusnya 'diterima'
                // Ini mencegah mahasiswa yang menolak muncul jika Blade memanggil relasi anggota
                'anggota' => fn($q) => $q->where('status', 'diterima')->with('mahasiswaProfile.user'),
                'anggotaDiterima.mahasiswaProfile.user',
                'submissions' => fn($q) => $q->latest()->limit(1)
            ])
            ->orderBy('deadline')
            ->get();

        return view('pages.asn.submission-index', compact('tugasMenungguReview'));
    }

    public function detailSubmission($tugasId)
    {
        $tugas = Tugas::query()->where('id', $tugasId)
            ->where('asn_id', Auth::id())
            ->with([
                'mahasiswaProfile.user',
                // PERBAIKAN UTAMA: Diterapkan juga di halaman detail submission
                'anggota' => fn($q) => $q->where('status', 'diterima')->with('mahasiswaProfile.user'),
                'anggotaDiterima.mahasiswaProfile.user',
                'submissions' => fn($q) => $q->latest(),
            ])
            ->firstOrFail();

        return view('pages.asn.submission-detail', compact('tugas'));
    }

    public function approveSubmission($submissionId)
    {
        $submission = TugasSubmission::with('tugas')->whereHas('tugas', fn($q) => $q->where('asn_id', Auth::id()))->findOrFail($submissionId);

        if ($submission->tugas->status !== 'menunggu_review') {
            return back()->with('error', 'Tugas ini tidak sedang menunggu review.');
        }

        $submission->update([
            'status' => 'disetujui',
            'direview_oleh' => Auth::id(),
            'direview_at' => now(),
        ]);

        $submission->tugas->update([
            'status' => 'selesai',
            'selesai_at' => now(),
        ]);

        return redirect()->route('asn-submission-index')->with('success', 'Tugas disetujui dan ditandai selesai.');
    }

    public function mintaRevisi(Request $request, $submissionId)
    {
        $submission = TugasSubmission::with('tugas')->whereHas('tugas', fn($q) => $q->where('asn_id', Auth::id()))->findOrFail($submissionId);

        if ($submission->tugas->status !== 'menunggu_review') {
            return back()->with('error', 'Tugas ini tidak sedang menunggu review.');
        }

        $validated = $request->validate(
            [
                'catatan_asn' => 'required|string',
            ],
            [
                'catatan_asn.required' => 'Jelaskan apa yang perlu diperbaiki sebelum minta revisi.',
            ]
        );

        $submission->update([
            'status' => 'revisi',
            'catatan_asn' => $validated['catatan_asn'],
            'direview_oleh' => Auth::id(),
            'direview_at' => now(),
        ]);

        $submission->tugas->update(['status' => 'revisi']);

        return redirect()->route('asn-submission-index')->with('success', 'Permintaan revisi terkirim ke mahasiswa.');
    }
}
