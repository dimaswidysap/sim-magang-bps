@extends('layouts.app')

@section('content')
    <main class="w-full p-4 md:p-8 bg-background min-h-screen font-montserrat">
        <section class="max-w-5xl mx-auto space-y-6">

            <!-- Header Halaman -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-border">
                <div>
                    <h1 class="text-2xl font-bold text-text leading-snug">Detail Kegiatan Harian</h1>
                    <div class="flex flex-wrap items-center gap-2 mt-1 text-sm text-text-light">
                        <p class="flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="font-bold text-text">{{ $mahasiswa->user->name }}</span>
                        </p>
                        <span class="hidden sm:inline">&bull;</span>
                        <p class="flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</span>
                        </p>
                    </div>
                </div>

                <x-buttonv2 href="{{ route('asn-logbook-mahasiswa-kalender', $mahasiswa->id) }}" color="accent-dark"
                    class="w-full sm:w-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Kalender
                </x-buttonv2>
            </div>

            <!-- Cek apakah KEDUA sumber logbook kosong -->
            @if ($logbookTugas->isEmpty() && $logbookMandiri->isEmpty())
                <div class="py-16 bg-surface border border-border rounded-2xl text-center shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 mx-auto text-border mb-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    <h3 class="text-lg font-bold text-text">Tidak Ada Kegiatan</h3>
                    <p class="text-sm text-text-light mt-1">Mahasiswa tidak memiliki tugas atau aktivitas yang dicatat pada
                        tanggal ini.</p>
                </div>
            @else
                <!-- ===== SECTION 1: DARI TUGAS ASN ===== -->
                @if ($logbookTugas->isNotEmpty())
                    <div class="space-y-3">
                        <h2 class="text-sm font-semibold text-text-light uppercase tracking-wider">Dari Tugas ASN</h2>

                        {{-- Tampilan Desktop & Tablet (Tabel) --}}
                        <div
                            class="hidden md:block overflow-x-auto bg-surface border border-border rounded-[10px] shadow-sm">
                            <table class="w-full text-left border-collapse">
                                <thead class="bg-black/5 border-b border-border">
                                    <tr>
                                        <th
                                            class="px-5 py-4 text-xs font-semibold text-text-light uppercase tracking-wider text-center w-12 align-middle">
                                            No</th>
                                        <th
                                            class="px-5 py-4 text-xs font-semibold text-text-light uppercase tracking-wider w-2/5 align-middle">
                                            Detail Tugas</th>
                                        <th
                                            class="px-5 py-4 text-xs font-semibold text-text-light uppercase tracking-wider whitespace-nowrap align-middle">
                                            Pemberi Tugas</th>
                                        <th
                                            class="px-5 py-4 text-xs font-semibold text-text-light uppercase tracking-wider align-middle">
                                            Skill Terkait</th>
                                        <th
                                            class="px-5 py-4 text-xs font-semibold text-text-light uppercase tracking-wider text-center whitespace-nowrap align-middle">
                                            Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-border">
                                    @foreach ($logbookTugas as $item)
                                        <tr class="hover:bg-black/[0.02] transition-colors duration-200 group">
                                            <!-- Nomor -->
                                            <td
                                                class="px-5 py-4 align-middle text-center text-xs font-semibold text-text-light">
                                                {{ $loop->iteration }}
                                            </td>

                                            <!-- Detail Tugas (Judul & Deskripsi) -->
                                            <td class="px-5 py-4 align-middle">
                                                <h3
                                                    class="text-sm font-bold text-text group-hover:text-primary transition-colors line-clamp-1">
                                                    {{ $item->tugas->judul }}
                                                </h3>
                                                <p class="text-xs text-text-light line-clamp-2 mt-1 leading-relaxed">
                                                    {{ $item->tugas->deskripsi }}
                                                </p>
                                            </td>

                                            <!-- Pemberi Tugas -->
                                            <td class="px-5 py-4 align-middle whitespace-nowrap">
                                                <p class="text-xs text-text-light flex items-center gap-1.5">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-3.5 w-3.5 text-text-light shrink-0" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                    <span
                                                        class="font-semibold text-text">{{ $item->tugas->asn->name }}</span>
                                                </p>
                                            </td>

                                            <!-- Skill Terkait -->
                                            <td class="px-5 py-4 align-middle">
                                                @if ($item->tugas->skills->isNotEmpty())
                                                    <div class="flex flex-wrap gap-1.5">
                                                        @foreach ($item->tugas->skills as $skill)
                                                            <span
                                                                class="inline-flex items-center px-2 py-0.5 bg-background border border-border rounded text-[10px] font-medium text-text-light whitespace-nowrap">
                                                                {{ $skill->nama_skill }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-xs text-text-light italic">-</span>
                                                @endif
                                            </td>

                                            <!-- Status Badge -->
                                            <td class="px-5 py-4 align-middle text-center whitespace-nowrap">
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-primary/10 text-primary-dark text-[10px] font-bold rounded-md uppercase tracking-wide border border-primary/20">
                                                    {{ $item->tugas->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Tampilan Mobile (Stacked Card) --}}
                        <div class="grid grid-cols-1 gap-4 md:hidden">
                            @foreach ($logbookTugas as $item)
                                <div class="bg-surface border border-border rounded-[10px] p-4 shadow-sm space-y-3">
                                    <div class="flex items-center justify-between border-b border-border/60 pb-2">
                                        <span class="text-xs font-bold text-text-light bg-black/5 px-2 py-0.5 rounded">
                                            #{{ $loop->iteration }}
                                        </span>
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 bg-primary/10 text-primary-dark text-[10px] font-bold rounded-md uppercase border border-primary/20">
                                            {{ $item->tugas->status }}
                                        </span>
                                    </div>

                                    <div>
                                        <h3 class="text-base font-bold text-text leading-snug">
                                            {{ $item->tugas->judul }}
                                        </h3>
                                        <p class="text-xs text-text-light mt-1 line-clamp-3 leading-relaxed">
                                            {{ $item->tugas->deskripsi }}
                                        </p>
                                    </div>

                                    <div class="bg-background p-2.5 rounded-md border border-border/50 space-y-2">
                                        <p class="text-xs text-text-light flex items-center gap-1.5">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-3.5 w-3.5 text-text-light shrink-0" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            ASN: <span class="font-semibold text-text">{{ $item->tugas->asn->name }}</span>
                                        </p>

                                        @if ($item->tugas->skills->isNotEmpty())
                                            <div class="pt-1 border-t border-border/40">
                                                <p class="text-[10px] font-semibold text-text-light uppercase mb-1">Skill
                                                    Terkait:</p>
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach ($item->tugas->skills as $skill)
                                                        <span
                                                            class="inline-flex items-center px-2 py-0.5 bg-surface border border-border rounded text-[10px] text-text-light">
                                                            {{ $skill->nama_skill }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- ===== SECTION 2: KEGIATAN MANDIRI ===== -->
                @if ($logbookMandiri->isNotEmpty())
                    <div class="space-y-3 pt-4">
                        <h2 class="text-sm font-semibold text-text-light uppercase tracking-wider">Kegiatan Mandiri</h2>

                        {{-- Tampilan Desktop & Tablet (Tabel) --}}
                        <div
                            class="hidden md:block overflow-x-auto bg-surface border border-border rounded-[10px] shadow-sm">
                            <table class="w-full text-left border-collapse">
                                <thead class="bg-black/5 border-b border-border">
                                    <tr>
                                        <th
                                            class="px-5 py-4 text-xs font-semibold text-text-light uppercase tracking-wider text-center w-12 align-middle">
                                            No</th>
                                        <th
                                            class="px-5 py-4 text-xs font-semibold text-text-light uppercase tracking-wider w-1/2 align-middle">
                                            Judul & Deskripsi Kegiatan</th>
                                        <th
                                            class="px-5 py-4 text-xs font-semibold text-text-light uppercase tracking-wider text-center whitespace-nowrap align-middle">
                                            Kategori</th>
                                        <th
                                            class="px-5 py-4 text-xs font-semibold text-text-light uppercase tracking-wider text-center whitespace-nowrap align-middle">
                                            Lampiran</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-border">
                                    @foreach ($logbookMandiri as $item)
                                        <tr class="hover:bg-black/[0.02] transition-colors duration-200 group">
                                            <!-- Nomor -->
                                            <td
                                                class="px-5 py-4 align-middle text-center text-xs font-semibold text-text-light">
                                                {{ $loop->iteration }}
                                            </td>

                                            <!-- Judul & Deskripsi -->
                                            <td class="px-5 py-4 align-middle">
                                                <h3
                                                    class="text-sm font-bold text-text group-hover:text-primary transition-colors line-clamp-1">
                                                    {{ $item->judul_kegiatan }}
                                                </h3>
                                                <p class="text-xs text-text-light line-clamp-2 mt-1 leading-relaxed">
                                                    {{ $item->deskripsi_kegiatan }}
                                                </p>
                                            </td>

                                            <!-- Kategori Badge -->
                                            <td class="px-5 py-4 align-middle text-center whitespace-nowrap">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 bg-accent/10 text-accent-dark text-[10px] font-bold rounded-md uppercase tracking-wide border border-accent/20">
                                                    Mandiri
                                                </span>
                                            </td>

                                            <!-- Lampiran -->
                                            <td class="px-5 py-4 align-middle text-center whitespace-nowrap">
                                                @if ($item->file_lampiran)
                                                    @if ($item->isGambar())
                                                        <a href="{{ Storage::url($item->file_lampiran) }}"
                                                            target="_blank" class="inline-block group/img">
                                                            <img src="{{ Storage::url($item->file_lampiran) }}"
                                                                alt="Lampiran kegiatan"
                                                                class="w-12 h-12 object-cover rounded-md border border-border group-hover/img:scale-105 transition-transform">
                                                        </a>
                                                    @else
                                                        <a href="{{ Storage::url($item->file_lampiran) }}"
                                                            target="_blank"
                                                            class="inline-flex items-center gap-1.5 text-xs text-primary hover:underline font-medium">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                                fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                                stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                            </svg>
                                                            Lihat File
                                                        </a>
                                                    @endif
                                                @else
                                                    <span class="text-xs text-text-light italic">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Tampilan Mobile (Stacked Card) --}}
                        <div class="grid grid-cols-1 gap-4 md:hidden">
                            @foreach ($logbookMandiri as $item)
                                <div class="bg-surface border border-border rounded-[10px] p-4 shadow-sm space-y-3">
                                    <div class="flex items-center justify-between border-b border-border/60 pb-2">
                                        <span class="text-xs font-bold text-text-light bg-black/5 px-2 py-0.5 rounded">
                                            #{{ $loop->iteration }}
                                        </span>
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 bg-accent/10 text-accent-dark text-[10px] font-bold rounded-md uppercase border border-accent/20">
                                            Mandiri
                                        </span>
                                    </div>

                                    <div>
                                        <h3 class="text-base font-bold text-text leading-snug">
                                            {{ $item->judul_kegiatan }}
                                        </h3>
                                        <p class="text-xs text-text-light mt-1 line-clamp-3 leading-relaxed">
                                            {{ $item->deskripsi_kegiatan }}
                                        </p>
                                    </div>

                                    @if ($item->file_lampiran)
                                        <div class="pt-2 border-t border-border/60">
                                            @if ($item->isGambar())
                                                <a href="{{ Storage::url($item->file_lampiran) }}" target="_blank">
                                                    <img src="{{ Storage::url($item->file_lampiran) }}"
                                                        alt="Lampiran kegiatan"
                                                        class="w-full h-36 object-cover rounded-lg border border-border">
                                                </a>
                                            @else
                                                <a href="{{ Storage::url($item->file_lampiran) }}" target="_blank"
                                                    class="text-xs text-primary hover:underline flex items-center gap-1.5 font-medium">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                        stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    Lihat Lampiran
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            @endif

        </section>
    </main>
@endsection
