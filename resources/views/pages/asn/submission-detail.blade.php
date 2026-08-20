@extends('layouts.app')

@section('content')
    <main class="w-full p-4 md:p-8 bg-background min-h-screen font-montserrat">
        <section class="max-w-5xl mx-auto space-y-6">

            <!-- Header Halaman -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-border">
                <div>
                    <h1 class="text-2xl font-bold text-text leading-snug">Detail Review Tugas</h1>
                    <p class="text-sm text-text-light mt-1">Periksa hasil pekerjaan mahasiswa dan berikan evaluasi.</p>
                </div>

                <x-buttonv2 href="{{ route('asn-submission-index') }}" color="accent-dark" class="w-full sm:w-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </x-buttonv2>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Kolom Kiri: Info Tugas & Tim -->
                <div class="lg:col-span-1 space-y-6">

                    <!-- Card Informasi Tugas -->
                    <div class="bg-surface rounded-xl shadow-sm border border-border overflow-hidden">
                        <div class="p-5 border-b border-border bg-background">
                            <h2 class="text-base font-bold text-text flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Informasi Tugas
                            </h2>
                        </div>
                        <div class="p-5 space-y-4">
                            <div>
                                <p class="text-[11px] font-semibold text-text-light uppercase tracking-wider mb-1">Judul
                                    Tugas</p>
                                <p class="text-sm font-bold text-text">{{ $tugas->judul }}</p>
                            </div>
                            <div>
                                <p class="text-[11px] font-semibold text-text-light uppercase tracking-wider mb-1">Tenggat
                                    Waktu</p>
                                <p class="text-xs font-bold text-danger flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $tugas->deadline ? \Carbon\Carbon::parse($tugas->deadline)->translatedFormat('d M Y, H:i') : '-' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-[11px] font-semibold text-text-light uppercase tracking-wider mb-1">Deskripsi
                                </p>
                                <p
                                    class="text-xs text-text leading-relaxed  bg-background p-3 rounded-lg border border-border">
                                    {{ $tugas->deskripsi }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card Tim Pengerjaan -->
                    <div class="bg-surface rounded-xl shadow-sm border border-border overflow-hidden">
                        <div class="p-5 border-b border-border bg-background">
                            <h2 class="text-base font-bold text-text flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Tim Pengerjaan
                            </h2>
                        </div>
                        <div class="p-5">
                            <!-- Ketua -->
                            <div class="mb-3">
                                <p class="text-[11px] font-semibold text-text-light uppercase tracking-wider mb-1">Ketua Tim
                                    / Penanggung Jawab</p>
                                <div class="flex items-center gap-2">
                                    <span
                                        class="w-6 h-6 rounded-full bg-primary/20 text-primary-dark flex items-center justify-center text-xs font-bold">K</span>
                                    <p class="text-sm font-medium text-text">
                                        {{ $tugas->mahasiswaProfile->user->name ?? '-' }}</p>
                                </div>
                            </div>

                            <!-- Anggota -->
                            @if ($tugas->anggotaDiterima->isNotEmpty())
                                <div>
                                    <p class="text-[11px] font-semibold text-text-light uppercase tracking-wider mb-2">
                                        Anggota Tim</p>
                                    <ul class="space-y-2">
                                        @foreach ($tugas->anggotaDiterima as $anggota)
                                            <li class="flex items-center gap-2">
                                                <span
                                                    class="w-6 h-6 rounded-full bg-background border border-border text-text-light flex items-center justify-center text-xs font-bold">A</span>
                                                <p class="text-sm text-text">{{ $anggota->mahasiswaProfile->user->name }}
                                                </p>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

                <!-- Kolom Kanan: Riwayat Pengumpulan -->
                <div class="lg:col-span-2 space-y-6">

                    <h2 class="text-lg font-bold text-text flex items-center gap-2 border-b border-border pb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Riwayat Pengumpulan
                    </h2>

                    @if ($tugas->submissions->isEmpty())
                        <div class="py-12 bg-surface border border-border rounded-xl text-center shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-border mb-3"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="font-bold text-text">Belum Ada Pengumpulan</p>
                            <p class="text-sm text-text-light mt-1">Mahasiswa belum mengumpulkan hasil tugas ini.</p>
                        </div>
                    @endif

                    <div class="space-y-6">
                        @foreach ($tugas->submissions as $submission)
                            <div
                                class="bg-surface border {{ $loop->first ? 'border-primary' : 'border-border' }} rounded-xl shadow-sm overflow-hidden relative">

                                <!-- Label Terbaru (Hanya untuk item pertama) -->
                                @if ($loop->first)
                                    <div
                                        class="absolute top-0 right-0 bg-primary text-white text-[10px] font-bold px-3 py-1 rounded-bl-lg uppercase tracking-wider">
                                        Terbaru
                                    </div>
                                @endif

                                <div class="p-5 md:p-6 space-y-5">

                                    <!-- Meta Pengiriman -->
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                        <div>
                                            <p
                                                class="text-[11px] font-semibold text-text-light uppercase tracking-wider mb-0.5">
                                                Dikirim Pada</p>
                                            <p class="text-sm font-bold text-text">
                                                {{ \Carbon\Carbon::parse($submission->created_at)->translatedFormat('d M Y, H:i') }}
                                                WIB</p>
                                        </div>

                                        <!-- Status Badge -->
                                        <div>
                                            @if (strtolower($submission->status) === 'menunggu')
                                                <span
                                                    class="inline-flex px-3 py-1 bg-warning/10 text-warning text-xs font-bold rounded-full uppercase tracking-wider border border-warning/20">
                                                    Menunggu Review
                                                </span>
                                            @elseif (strtolower($submission->status) === 'revisi')
                                                <span
                                                    class="inline-flex px-3 py-1 bg-danger/10 text-danger text-xs font-bold rounded-full uppercase tracking-wider border border-danger/20">
                                                    Revisi
                                                </span>
                                            @elseif (strtolower($submission->status) === 'disetujui' || strtolower($submission->status) === 'selesai')
                                                <span
                                                    class="inline-flex px-3 py-1 bg-success/10 text-success text-xs font-bold rounded-full uppercase tracking-wider border border-success/20">
                                                    Disetujui
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex px-3 py-1 bg-background text-text-light text-xs font-bold rounded-full uppercase tracking-wider border border-border">
                                                    {{ $submission->status }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <hr class="border-border">

                                    <!-- Pesan & Lampiran -->
                                    <div class="space-y-4">

                                        <!-- Lampiran File -->
                                        <div>
                                            <p
                                                class="text-[11px] font-semibold text-text-light uppercase tracking-wider mb-2">
                                                Lampiran File</p>
                                            @if ($submission->file_path)
                                                <a href="{{ Storage::url($submission->file_path) }}" target="_blank"
                                                    class="inline-flex items-center gap-2 p-3 bg-background border border-border hover:border-primary rounded-lg transition-colors group w-full sm:w-auto">
                                                    <div
                                                        class="w-8 h-8 rounded bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                            stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                    </div>
                                                    <span
                                                        class="text-sm font-medium text-text group-hover:text-primary transition-colors line-clamp-1">
                                                        {{ $submission->file_name ?? 'Download Lampiran' }}
                                                    </span>
                                                </a>
                                            @else
                                                <p
                                                    class="text-sm text-text-light italic px-3 py-2 bg-background border border-border rounded-lg border-dashed">
                                                    Tidak ada lampiran file.
                                                </p>
                                            @endif
                                        </div>

                                        <!-- Pesan Mahasiswa -->
                                        @if ($submission->catatan_mahasiswa)
                                            <div>
                                                <p
                                                    class="text-[11px] font-semibold text-text-light uppercase tracking-wider mb-2">
                                                    Pesan Mahasiswa</p>
                                                <div
                                                    class="text-sm text-text leading-relaxed bg-[#F8FAFC] border border-border p-4 rounded-lg ">
                                                    {{ $submission->catatan_mahasiswa }}</div>
                                            </div>
                                        @endif

                                        <!-- Pesan ASN (Feedback sebelumnya) -->
                                        @if ($submission->catatan_asn)
                                            <div>
                                                <p
                                                    class="text-[11px] font-semibold text-text-light uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-3.5 w-3.5 text-primary" viewBox="0 0 20 20"
                                                        fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    Catatan / Feedback Anda Sebelumnya
                                                </p>
                                                <div
                                                    class="text-sm text-text leading-relaxed bg-primary/5 border border-primary/20 p-4 rounded-lg ">
                                                    {{ $submission->catatan_asn }}</div>
                                            </div>
                                        @endif

                                    </div>
                                </div>

                                <!-- Tindakan Review (Hanya muncul jika item paling atas dan statusnya menunggu) -->
                                @if ($loop->first && strtolower($submission->status) === 'menunggu' && strtolower($tugas->status) === 'menunggu_review')
                                    <div class="bg-primary/5 border-t border-primary/20 p-5 md:p-6">
                                        <h3
                                            class="text-sm font-bold text-primary-dark uppercase tracking-wider mb-4 flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </path>
                                                Tindakan Evaluasi
                                        </h3>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <!-- Opsi 1: Minta Revisi -->
                                            <div class="bg-surface border border-border p-4 rounded-xl">
                                                <form data-confirm="Apakah anda yakin ingin meminta revisi?"
                                                    method="POST"
                                                    action="{{ route('asn-submission-revisi', $submission->id) }}"
                                                    class="m-0 space-y-3">
                                                    @csrf
                                                    <label
                                                        class="block text-xs font-semibold text-text-light uppercase tracking-wider">Minta
                                                        Revisi Tugas</label>
                                                    <textarea name="catatan_asn" rows="3" required placeholder="Tuliskan bagian mana yang perlu diperbaiki..."
                                                        class="w-full bg-background border border-border rounded-lg px-3 py-2 text-sm text-text focus:outline-none focus:border-danger focus:ring-1 focus:ring-danger transition-colors resize-y"></textarea>

                                                    <x-buttonv2 type="submit" color="danger" class="w-full sm:w-full">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                            stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                                        </svg>
                                                        Minta Revisi
                                                    </x-buttonv2>
                                                </form>
                                            </div>

                                            <!-- Opsi 2: Setujui -->
                                            <div
                                                class="bg-surface border border-border p-4 rounded-xl flex flex-col justify-between">
                                                <div>
                                                    <label
                                                        class="block text-xs font-semibold text-text-light uppercase tracking-wider mb-2">Terima
                                                        Tugas</label>
                                                    <p class="text-xs text-text-light leading-relaxed">Jika hasil pekerjaan
                                                        sudah sesuai standar dan tidak ada yang perlu direvisi, Anda dapat
                                                        menyetujui tugas ini. Status tugas akan berubah menjadi Selesai.</p>
                                                </div>
                                                <form method="POST"
                                                    action="{{ route('asn-submission-approve', $submission->id) }}"
                                                    class="m-0 mt-3"
                                                    data-confirm="Anda yakin ingin menyetujui tugas ini?">
                                                    @csrf

                                                    <x-buttonv2 type="submit" color="accent-dark"
                                                        class="w-full sm:w-full">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                            stroke-width="3">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        Setujui & Selesaikan
                                                    </x-buttonv2>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </section>
    </main>
@endsection
