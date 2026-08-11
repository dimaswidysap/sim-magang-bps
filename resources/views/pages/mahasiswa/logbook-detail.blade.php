@extends('layouts.app')

@section('content')
    <main class="w-full p-4 md:p-8 bg-background min-h-screen font-montserrat">
        <section class="max-w-5xl mx-auto space-y-6">

            <!-- Header Halaman -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-border">
                <div>
                    <h1 class="text-2xl font-bold text-text leading-snug">Detail Kegiatan Harian</h1>
                    <p class="text-sm text-text-light mt-1 flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Tanggal: <span
                            class="font-bold text-text">{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</span>
                    </p>
                </div>

                <x-main-button href="{{ route('mahasiswa-index') }}"
                    class="w-full sm:w-auto bg-surface text-text border border-border hover:bg-background text-xs px-4 py-2 rounded-lg transition-colors shadow-sm inline-flex justify-center items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Kembali ke Dashboard</span>
                </x-main-button>
            </div>

            <!-- Cek apakah data logbook kosong -->
            @if ($logbook->isEmpty())
                <div class="py-16 bg-surface border border-border rounded-2xl text-center shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 mx-auto text-border mb-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    <h3 class="text-lg font-bold text-text">Tidak Ada Kegiatan</h3>
                    <p class="text-sm text-text-light mt-1">Belum ada tugas atau aktivitas yang dicatat pada tanggal ini.
                    </p>
                </div>
            @else
                <!-- Grid Card Kegiatan -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach ($logbook as $item)
                        <div
                            class="bg-surface border border-border rounded-xl p-5 md:p-6 shadow-sm hover:shadow-md transition-shadow duration-300 flex flex-col group">

                            <!-- Judul & Status -->
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <h3
                                    class="font-bold text-lg text-text line-clamp-2 group-hover:text-primary transition-colors">
                                    {{ $item->tugas->judul }}
                                </h3>

                                <!-- Status Badge -->
                                <span
                                    class="shrink-0 inline-flex items-center gap-1.5 px-2.5 py-1 bg-primary/10 text-primary-dark text-[10px] font-bold rounded-md uppercase tracking-wide border border-primary/20">
                                    {{ $item->tugas->status }}
                                </span>
                            </div>

                            <!-- Deskripsi -->
                            <p class="text-sm text-text-light leading-relaxed mb-4 line-clamp-3 flex-1">
                                {{ $item->tugas->deskripsi }}
                            </p>

                            <!-- Footer Card -->
                            <div class="mt-auto pt-4 border-t border-border space-y-3">

                                <!-- ASN Pemberi Tugas -->
                                <p class="text-[12px] text-text-light flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-text-light"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    ASN Pemberi Tugas: <span
                                        class="font-semibold text-text">{{ $item->tugas->asn->name }}</span>
                                </p>

                                <!-- Skills -->
                                @if ($item->tugas->skills->isNotEmpty())
                                    <div>
                                        <p
                                            class="text-[10px] font-semibold text-text-light uppercase tracking-wider mb-1.5">
                                            Skill Terkait:</p>
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach ($item->tugas->skills as $skill)
                                                <span
                                                    class="inline-flex items-center px-2 py-1 bg-background border border-border rounded text-[10px] font-medium text-text-light">
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
            @endif

        </section>
    </main>
@endsection
