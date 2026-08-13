@extends('layouts.app')

@section('content')
    <main class="w-full p-4 md:p-8 bg-background min-h-screen font-montserrat">
        <section class="max-w-4xl mx-auto space-y-6">

            <!-- Header Navigasi -->
            <div class="flex items-center justify-between pb-4 border-b border-border">
                <x-main-button href="{{ route('berita-index') }}"
                    class="bg-surface text-text border border-border hover:bg-background text-xs px-4 py-2.5 rounded-lg transition-colors shadow-sm inline-flex justify-center items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Kembali ke Daftar Berita</span>
                </x-main-button>
            </div>

            <!-- Card Artikel Utama -->
            <div class="bg-surface rounded-2xl shadow-sm border border-border overflow-hidden">

                <!-- Header Artikel -->
                <div class="p-6 md:p-8 md:px-10 border-b border-border bg-[#F8FAFC]">
                    <h1 class="text-2xl md:text-3xl font-bold text-text leading-snug mb-4">
                        {{ $berita->judul }}
                    </h1>

                    <!-- Meta Data (Penulis & Waktu) -->
                    <div class="flex flex-wrap items-center gap-4 text-xs font-semibold uppercase tracking-wider">
                        <!-- Penulis -->
                        <span
                            class="flex items-center gap-1.5 text-primary bg-primary/10 px-3 py-1.5 rounded-md border border-primary/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            {{ $berita->user->name }}
                        </span>
                        <!-- Tanggal -->
                        <span class="flex items-center gap-1.5 text-text-light">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ $berita->created_at->translatedFormat('d F Y, H:i') }}
                        </span>
                    </div>
                </div>

                <!-- Isi Konten Artikel -->
                <div class="p-6 md:p-8 md:px-10 text-sm md:text-base text-text leading-relaxed">
                    {!! nl2br(e($berita->konten)) !!}
                </div>

                <!-- Bagian Lampiran -->
                @if ($berita->attachments->isNotEmpty())

                    @php
                        $hasImages = $berita->attachments->contains(fn($l) => $l->isGambar());
                        $hasFiles = $berita->attachments->contains(fn($l) => !$l->isGambar());
                    @endphp

                    <div class="p-6 md:p-8 md:px-10 border-t border-border bg-background/50 space-y-8">

                        <!-- Gallery Foto -->
                        @if ($hasImages)
                            <div>
                                <h2
                                    class="text-sm font-bold text-text-light uppercase tracking-wider flex items-center gap-2 mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Lampiran Foto
                                </h2>

                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                    @foreach ($berita->attachments as $lampiran)
                                        @if ($lampiran->isGambar())
                                            <a href="{{ Storage::url($lampiran->file_path) }}" target="_blank"
                                                class="block aspect-square overflow-hidden rounded-xl border border-border hover:border-primary hover:shadow-md transition-all group relative">
                                                <img src="{{ Storage::url($lampiran->file_path) }}"
                                                    alt="{{ $lampiran->file_name }}"
                                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">

                                                <!-- Overlay Hover Info -->
                                                <div
                                                    class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                        stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                                    </svg>
                                                </div>
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- List Dokumen / File -->
                        @if ($hasFiles)
                            <div>
                                <h2
                                    class="text-sm font-bold text-text-light uppercase tracking-wider flex items-center gap-2 mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                    Lampiran Dokumen
                                </h2>

                                <ul class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach ($berita->attachments as $lampiran)
                                        @if (!$lampiran->isGambar())
                                            <li>
                                                <a href="{{ Storage::url($lampiran->file_path) }}" target="_blank"
                                                    class="flex items-center p-4 bg-surface border border-border rounded-xl hover:border-primary hover:shadow-sm transition-all group">

                                                    <!-- Icon Dokumen -->
                                                    <div
                                                        class="w-10 h-10 rounded-lg bg-primary/10 text-primary-dark flex items-center justify-center shrink-0 border border-primary/20 mr-4 group-hover:bg-primary group-hover:text-white transition-colors">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                            stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                        </svg>
                                                    </div>

                                                    <!-- Info Dokumen -->
                                                    <div class="flex-1 overflow-hidden">
                                                        <p
                                                            class="text-sm font-semibold text-text truncate group-hover:text-primary transition-colors">
                                                            {{ $lampiran->file_name }}
                                                        </p>
                                                        <p class="text-[11px] text-text-light mt-0.5">
                                                            Ukuran: {{ number_format($lampiran->file_size / 1024, 0) }} KB
                                                        </p>
                                                    </div>

                                                    <!-- Icon Download -->
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-5 w-5 text-text-light group-hover:text-primary transition-colors shrink-0 ml-2"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                        stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                    </svg>
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                    </div>
                @endif
            </div>

        </section>
    </main>
@endsection
