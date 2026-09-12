<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Tugas;
use App\Models\TugasSubmission;

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

        // 1. Validasi Input Multiple Files & Catatan
        $validated = $request->validate(
            [
                'files' => 'nullable|array|required_without:catatan_mahasiswa',
                'files.*' => 'file|max:10240|mimes:doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,svg,webp,bmp,pdf,zip',
                'catatan_mahasiswa' => 'nullable|string|required_without:files',
            ],
            [
                'files.required_without' => 'Isi salah satu: upload file atau tulis pesan.',
                'catatan_mahasiswa.required_without' => 'Isi salah satu: upload file atau tulis pesan.',
                'files.*.max' => 'Ukuran masing-masing file tidak boleh melebihi 10 MB.',
                'files.*.mimes' => 'Format file yang diunggah tidak didukung.',
            ],
        );

        $catatan = $validated['catatan_mahasiswa'] ?? null;

        // 2. Simpan File (Jika ada file diunggah)
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $index => $file) {
                $path = $file->store('tugas-submissions', 'public');

                TugasSubmission::create([
                    'tugas_id' => $tugas->id,
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getClientMimeType(),
                    // Catatan mahasiswa dilekatkan pada file pertama agar tidak berulang
                    'catatan_mahasiswa' => $index === 0 ? $catatan : null,
                    'status' => 'menunggu',
                ]);
            }
        } else {
            // Jika hanya mengirimkan catatan teks tanpa file
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

        // 3. Update Status Tugas
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
                'submissions' => fn($q) => $q->latest()->limit(1),
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
            'anggota' => fn($q) => $q->where('status', 'diterima')->with('mahasiswaProfile.user'),
            'anggotaDiterima.mahasiswaProfile.user',
            // PERBAIKAN: Hapus with('files')
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
            ],
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
