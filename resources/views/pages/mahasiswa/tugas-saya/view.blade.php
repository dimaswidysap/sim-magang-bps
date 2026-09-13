@extends('layouts.app')

@section('content')
    <main class="w-full p-4 md:p-8 bg-background min-h-screen font-montserrat">
        <section class="container-dalam max-w-4xl mx-auto">

            <!-- Alert Sukses (Jika berhasil mengambil tugas) -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-success/10 border border-success rounded-lg flex items-center gap-3 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-success shrink-0" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm font-medium text-success">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-surface rounded-[10px] shadow-sm border border-border overflow-hidden">

                <!-- Header Card Detail -->
                <div class="p-6 md:p-8 border-b border-border bg-background">
                    <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-2">
                        <h1 class="text-2xl font-bold text-text leading-snug">
                            {{ $detailTugas->judul }}
                        </h1>

                        <!-- Status Badge -->
                        <div class="shrink-0 mt-1 md:mt-0">
                            @if (strtolower($detailTugas->status) === 'tersedia')
                                <span
                                    class="inline-flex px-3 py-1 bg-success/10 text-success text-xs font-bold rounded-full uppercase tracking-wider border border-success/20">
                                    {{ $detailTugas->status }}
                                </span>
                            @elseif (strtolower($detailTugas->status) === 'revisi')
                                <span
                                    class="inline-flex px-3 py-1 bg-danger/10 text-danger text-xs font-bold rounded-full uppercase tracking-wider border border-danger/20">
                                    Perlu Revisi
                                </span>
                            @elseif (strtolower($detailTugas->status) === 'selesai')
                                <span
                                    class="inline-flex px-3 py-1 bg-success/10 text-success text-xs font-bold rounded-full uppercase tracking-wider border border-success/20">
                                    Selesai
                                </span>
                            @else
                                <span
                                    class="inline-flex px-3 py-1 bg-warning/10 text-warning text-xs font-bold rounded-full uppercase tracking-wider border border-warning/20">
                                    {{ $detailTugas->status ?? 'Status Tidak Diketahui' }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <p class="text-sm text-text-light flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Dibuat pada: {{ \Carbon\Carbon::parse($detailTugas->created_at)->translatedFormat('d F Y, H:i') }} WIB
                    </p>
                </div>

                <!-- Body Card Detail -->
                <div class="p-6 md:p-8 space-y-8">

                    <!-- Info Grid (Deadline & Pembuat) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-5 bg-background border border-border rounded-xl">
                        <!-- Deadline -->
                        <div>
                            <p class="text-xs font-semibold text-text-light uppercase tracking-wider mb-1.5">
                                Tenggat Waktu (Deadline)
                            </p>
                            <p class="text-base font-bold text-danger flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ \Carbon\Carbon::parse($detailTugas->deadline)->translatedFormat('l, d F Y - H:i') }} WIB
                            </p>
                        </div>

                        <!-- Pembuat (ASN) -->
                        <div>
                            <p class="text-xs font-semibold text-text-light uppercase tracking-wider mb-1.5">
                                Pemberi Tugas (ASN)
                            </p>
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-full bg-primary-light/20 text-primary-dark flex items-center justify-center font-bold text-sm shrink-0 border border-primary/20">
                                    {{ strtoupper(substr($detailTugas->asn->name ?? 'A', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-text">{{ $detailTugas->asn->name ?? '-' }}</p>
                                    <p class="text-xs text-text-light flex items-center gap-1 mt-0.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        {{ $detailTugas->asn->phone ?? '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Deskripsi Tugas -->
                    <div>
                        <h2 class="text-sm font-semibold text-text-light uppercase tracking-wider mb-3">
                            Deskripsi / Detail Pekerjaan
                        </h2>
                        <div class="text-sm text-text leading-relaxed bg-surface border border-border p-5 rounded-xl">
                            {{ $detailTugas->deskripsi }}
                        </div>
                    </div>

                    <!-- Lampiran dari ASN -->
                    @if ($detailTugas->attachments && $detailTugas->attachments->isNotEmpty())
                        <div>
                            <h2 class="text-sm font-semibold text-text-light uppercase tracking-wider mb-3">
                                Lampiran dari ASN
                            </h2>
                            <div class="space-y-2">
                                @foreach ($detailTugas->attachments as $lampiran)
                                    <a href="{{ Storage::url($lampiran->file_path) }}" target="_blank"
                                        class="flex items-center gap-3 bg-surface border border-border p-4 rounded-xl hover:border-primary transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary shrink-0"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-text">{{ $lampiran->file_name }}</p>
                                            <p class="text-xs text-text-light">
                                                {{ number_format($lampiran->file_size / 1024, 0) }} KB
                                            </p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- PESAN REVISI TERBARU DARI ASN (Teks Saja) -->
                    @if (strtolower($detailTugas->status) === 'revisi' && isset($detailTugas->latestSubmission) && $detailTugas->latestSubmission->catatan_asn)
                        <div class="p-5 bg-danger/10 border border-danger/30 rounded-xl space-y-2">
                            <h2 class="text-xs font-bold text-danger uppercase tracking-wider flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-danger shrink-0"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z"
                                        clip-rule="evenodd" />
                                </svg>
                                Pesan Revisi dari ASN
                            </h2>
                            <div class="text-sm text-text leading-relaxed font-medium bg-surface/60 border border-danger/20 p-4 rounded-lg">
                                {{ $detailTugas->latestSubmission->catatan_asn }}
                            </div>
                        </div>
                    @endif

                    <!-- ================================================================= -->
                    <!-- RIWAYAT PENGUMPULAN TUGAS (DIKELOMPOKKAN BERDASARKAN MENIT)        -->
                    <!-- ================================================================= -->
                    @if ($detailTugas->submissions && $detailTugas->submissions->isNotEmpty())
                        @php
                            // Kelompokkan pengumpulan berdasarkan Tahun-Bulan-Hari Jam:Menit (mengabaikan detik)
                            $groupedSubmissions = $detailTugas->submissions->groupBy(function ($item) {
                                return \Carbon\Carbon::parse($item->created_at)->format('Y-m-d H:i');
                            });
                            $totalGroups = $groupedSubmissions->count();
                        @endphp

                        <div class="border-t border-border pt-6">
                            <h2 class="text-sm font-semibold text-text-light uppercase tracking-wider mb-4 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Riwayat Pengumpulan Tugas
                            </h2>

                            <div class="space-y-4">
                                @foreach ($groupedSubmissions as $timestampKey => $submissionsInGroup)
                                    @php
                                        // Urutan pengumpulan (Pengumpulan #1, #2, dst.)
                                        $groupNumber = $totalGroups - $loop->index;
                                        $formattedTime = \Carbon\Carbon::parse($timestampKey)->translatedFormat('d F Y, H:i');

                                        // Ambil catatan mahasiswa & ASN yang ada di dalam batch pengumpulan ini
                                        $catatanMahasiswa = $submissionsInGroup->pluck('catatan_mahasiswa')->filter()->first();
                                        $catatanAsn = $submissionsInGroup->pluck('catatan_asn')->filter()->first();
                                    @endphp

                                    <div class="p-5 bg-background border border-border rounded-xl space-y-4">

                                        <!-- Header Container Batch Pengumpulan -->
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-border/60">
                                            <div class="flex items-center gap-2">
                                                <span class="px-2.5 py-0.5 bg-primary/10 text-primary text-xs font-bold rounded-full border border-primary/20">
                                                    Pengumpulan #{{ $groupNumber }}
                                                </span>

                                                @if (strtolower($detailTugas->status) === 'selesai' && $loop->first)
                                                    <span class="px-2.5 py-0.5 bg-success/10 text-success text-xs font-bold rounded-full border border-success/20">
                                                        Diterima (Selesai)
                                                    </span>
                                                @elseif ($catatanAsn)
                                                    <span class="px-2.5 py-0.5 bg-danger/10 text-danger text-xs font-bold rounded-full border border-danger/20">
                                                        Perlu Revisi
                                                    </span>
                                                @endif
                                            </div>

                                            <span class="text-xs text-text-light font-medium flex items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                {{ $formattedTime }} WIB
                                            </span>
                                        </div>

                                        <!-- Catatan Mahasiswa pada batch ini (Jika ada) -->
                                        @if ($catatanMahasiswa)
                                            <div>
                                                <p class="text-xs font-semibold text-text-light uppercase tracking-wider mb-1">
                                                    Catatan Mahasiswa:
                                                </p>
                                                <p class="text-sm text-text font-medium bg-surface p-3 rounded-lg border border-border">
                                                    {{ $catatanMahasiswa }}
                                                </p>
                                            </div>
                                        @endif

                                        <!-- Daftar File yang Dikirim Bersamaan di Jam & Menit Ini -->
                                        <div>
                                            <p class="text-xs font-semibold text-text-light uppercase tracking-wider mb-2">
                                                File Hasil Pekerjaan ({{ $submissionsInGroup->count() }} File):
                                            </p>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                @foreach ($submissionsInGroup as $submission)
                                                    @if ($submission->file_path)
                                                        <a href="{{ $submission->file_url }}" target="_blank"
                                                            class="flex items-center gap-3 p-3 bg-surface border border-border rounded-xl hover:border-primary transition-colors group">
                                                            <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                </svg>
                                                            </div>
                                                            <div class="min-w-0 flex-1">
                                                                <p class="text-xs font-semibold text-text truncate group-hover:text-primary transition-colors">
                                                                    {{ $submission->file_name ?? 'Download File' }}
                                                                </p>
                                                                <p class="text-[10px] text-text-light mt-0.5">
                                                                    {{ $submission->formatted_file_size }}
                                                                </p>
                                                            </div>
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- Pesan / Catatan Revisi dari ASN untuk Batch Ini (Jika ada) -->
                                        @if ($catatanAsn)
                                            <div class="p-3 bg-danger/5 border border-danger/20 rounded-lg">
                                                <p class="text-xs font-bold text-danger mb-1 flex items-center gap-1.5">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                                    </svg>
                                                    Pesan Revisi dari ASN:
                                                </p>
                                                <p class="text-xs text-text">{{ $catatanAsn }}</p>
                                            </div>
                                        @endif

                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>

                <!-- Footer Card / Aksi -->
                <div class="p-6 md:px-8 md:py-6 bg-background border-t border-border flex flex-col sm:flex-row justify-between items-center gap-4">

                    <!-- Tombol Kembali -->
                    <x-buttonv2 href="{{ route('tugas-saya') }}" color="primary" class="w-full sm:w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </x-buttonv2>

                    <div class="flex gap-4">
                        {{-- AJAK TEMAN --}}
                        @if ($detailTugas->status !== 'selesai' && auth()->user()->mahasiswaProfile->id == $detailTugas->mahasiswa_profile_id)
                            <x-buttonv2 href="{{ route('mahasiswa-tugas-undang', $detailTugas->id) }}" color="accent-dark"
                                class="w-full sm:w-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                                Ajak Teman
                            </x-buttonv2>
                        @endif

                        {{-- KIRIM TUGAS --}}
                        @if ($detailTugas->status !== 'selesai')
                            <x-buttonv2 href="{{ route('mahasiswa-tugas-submit-form', $detailTugas->id) }}"
                                color="accent-dark" class="w-full sm:w-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                                Kirim Tugas
                            </x-buttonv2>
                        @endif
                    </div>
                </div>

            </div>
        </section>
    </main>
@endsection
