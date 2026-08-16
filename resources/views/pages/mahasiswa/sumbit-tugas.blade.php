@extends('layouts.app')

@section('content')
    <main class="w-full p-4 md:p-8 bg-background min-h-screen font-montserrat">
        <section class="max-w-4xl mx-auto space-y-6">

            <!-- Header Halaman -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-border">
                <div>
                    <h1 class="text-2xl font-bold text-text leading-snug">Kumpulkan Tugas</h1>
                    <p class="text-sm font-semibold text-primary mt-1">{{ $tugas->judul }}</p>
                </div>

                <x-main-button href="{{ route('tugas-saya') }}"
                    class="w-full sm:w-auto bg-surface text-text border border-border hover:bg-background text-xs px-4 py-2 rounded-lg transition-colors shadow-sm inline-flex justify-center items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Kembali</span>
                </x-main-button>
            </div>

            <!-- Alert Error (Validasi & Session) -->
            @if (session('error') || $errors->any())
                <div class="bg-danger/10 border border-danger p-4 rounded-xl flex items-start gap-3 shadow-sm">
                    <svg class="h-5 w-5 text-danger shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    <div>
                        <h3 class="text-sm font-semibold text-danger">Pengumpulan gagal:</h3>
                        <ul class="list-disc list-inside text-sm text-danger mt-1 space-y-1">
                            @if (session('error'))
                                <li>{{ session('error') }}</li>
                            @endif
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Card Form Pengumpulan -->
            <div class="bg-surface rounded-xl shadow-sm border border-border overflow-hidden">
                <div class="p-5 border-b border-border bg-background">
                    <h2 class="text-base font-bold text-text flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        Form Penyerahan Hasil
                    </h2>
                </div>

                <form method="POST" action="{{ route('mahasiswa-tugas-submit', $tugas->id) }}" data-confirm="Apakah anda yakin ingin mengirimkan tugas?"
                    enctype="multipart/form-data" class="m-0">
                    @csrf
                    <div class="p-6 md:p-8 space-y-6">

                        <!-- Info Alert: Instruksi -->
                        <div
                            class="flex items-center gap-2 text-sm text-text-light bg-primary/5 border border-primary/10 px-4 py-3 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary shrink-0" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Silakan isi <strong>salah satu</strong> atau <strong>keduanya</strong> (File Lampiran
                                dan/atau Pesan Teks).</span>
                        </div>

                        <!-- Input File -->
                        <div>
                            <label class="block text-sm font-semibold text-text-light uppercase tracking-wider mb-2">
                                File Hasil Kerja <span class="normal-case font-normal text-[11px]">(Opsional, maks. 10
                                    MB)</span>
                            </label>
                            <div
                                class="border-2 border-dashed border-border rounded-xl p-4 bg-[#F8FAFC] hover:border-primary transition-colors">
                                <input type="file" name="file"
                                    class="block w-full text-sm text-text-light cursor-pointer
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-lg file:border-0
                                file:text-xs file:font-semibold
                                file:bg-primary file:text-white
                                hover:file:bg-primary-dark transition-colors">
                            </div>
                        </div>

                        <!-- Input Pesan/Catatan -->
                        <div>
                            <label class="block text-sm font-semibold text-text-light uppercase tracking-wider mb-2">
                                Pesan / Catatan Pekerjaan <span
                                    class="normal-case font-normal text-[11px]">(Opsional)</span>
                            </label>
                            <textarea name="catatan_mahasiswa" rows="4"
                                class="w-full bg-surface border border-border rounded-xl px-4 py-3 text-sm text-text focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors resize-y"
                                placeholder="Tuliskan keterangan mengenai hasil kerja, link eksternal (misal: Google Drive/Figma), atau kendala yang dialami...">{{ old('catatan_mahasiswa') }}</textarea>
                        </div>

                    </div>

                    <!-- Footer Form (Tombol Submit) -->
                    <div class="p-6 md:px-8 md:py-5 bg-background border-t border-border flex justify-end">
                        <x-main-button type="submit"
                            class="w-full sm:w-auto bg-primary hover:bg-primary-dark text-xs px-6 py-2.5 rounded-lg text-white transition-colors shadow-sm inline-flex justify-center items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Kumpulkan Tugas</span>
                        </x-main-button>
                    </div>
                </form>
            </div>

            <!-- Section Riwayat Pengumpulan -->
            @if ($tugas->submissions->isNotEmpty())
                <div class="mt-8">
                    <h2 class="text-lg font-bold text-text flex items-center gap-2 mb-4 border-b border-border pb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Riwayat Pengumpulan Sebelumnya
                    </h2>

                    <div class="space-y-4">
                        @foreach ($tugas->submissions->sortByDesc('created_at') as $submission)
                            <div
                                class="bg-surface border {{ $loop->first ? 'border-primary' : 'border-border' }} rounded-xl shadow-sm overflow-hidden relative">

                                @if ($loop->first)
                                    <div
                                        class="absolute top-0 right-0 bg-primary text-white text-[10px] font-bold px-3 py-1 rounded-bl-lg uppercase tracking-wider">
                                        Terbaru
                                    </div>
                                @endif

                                <div class="p-5 space-y-4">
                                    <!-- Meta (Tanggal & Status) -->
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                        <div>
                                            <p
                                                class="text-[11px] font-semibold text-text-light uppercase tracking-wider mb-0.5">
                                                Dikirim Pada</p>
                                            <p class="text-sm font-bold text-text">
                                                {{ $submission->created_at->format('d M Y, H:i') }} WIB</p>
                                        </div>

                                        <div>
                                            @if (strtolower($submission->status) === 'menunggu')
                                                <span
                                                    class="inline-flex px-3 py-1 bg-warning/10 text-warning text-[11px] font-bold rounded-full uppercase tracking-wider border border-warning/20">
                                                    Menunggu Review
                                                </span>
                                            @elseif (strtolower($submission->status) === 'revisi')
                                                <span
                                                    class="inline-flex px-3 py-1 bg-danger/10 text-danger text-[11px] font-bold rounded-full uppercase tracking-wider border border-danger/20">
                                                    Revisi
                                                </span>
                                            @elseif (strtolower($submission->status) === 'disetujui' || strtolower($submission->status) === 'selesai')
                                                <span
                                                    class="inline-flex px-3 py-1 bg-success/10 text-success text-[11px] font-bold rounded-full uppercase tracking-wider border border-success/20">
                                                    Disetujui
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex px-3 py-1 bg-background text-text-light text-[11px] font-bold rounded-full uppercase tracking-wider border border-border">
                                                    {{ $submission->status }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <hr class="border-border">

                                    <!-- Konten yang dikirim -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                        <!-- Bagian Mahasiswa -->
                                        <div class="space-y-3">
                                            <!-- Lampiran File -->
                                            <div>
                                                <p
                                                    class="text-[10px] font-semibold text-text-light uppercase tracking-wider mb-1.5">
                                                    File Lampiran</p>
                                                @if ($submission->file_path)
                                                    <div
                                                        class="inline-flex items-center gap-2 p-2 bg-[#F8FAFC] border border-border rounded-lg text-sm w-full">
                                                        <div
                                                            class="w-7 h-7 rounded bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5"
                                                                fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                                stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                            </svg>
                                                        </div>
                                                        <span
                                                            class="text-text font-medium truncate">{{ $submission->file_name ?? 'File terlampir' }}</span>
                                                    </div>
                                                @else
                                                    <p
                                                        class="text-xs text-text-light italic bg-background p-2 rounded-lg border border-border border-dashed">
                                                        Tidak ada file terlampir</p>
                                                @endif
                                            </div>

                                            <!-- Pesan Mahasiswa -->
                                            @if ($submission->catatan_mahasiswa)
                                                <div>
                                                    <p
                                                        class="text-[10px] font-semibold text-text-light uppercase tracking-wider mb-1.5">
                                                        Pesan Anda</p>
                                                    <div
                                                        class="text-xs text-text leading-relaxed bg-[#F8FAFC] border border-border p-3 rounded-lg ">
                                                        {{ $submission->catatan_mahasiswa }}</div>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Bagian Feedback ASN -->
                                        @if ($submission->catatan_asn)
                                            <div>
                                                <p
                                                    class="text-[10px] font-semibold text-primary uppercase tracking-wider mb-1.5 flex items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    Catatan / Feedback ASN
                                                </p>
                                                <div
                                                    class="text-xs text-text leading-relaxed bg-primary/5 border border-primary/20 p-3 rounded-lg ">
                                                    {{ $submission->catatan_asn }}</div>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </section>
    </main>
@endsection
