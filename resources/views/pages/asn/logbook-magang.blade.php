@extends('layouts.app')

@section('content')

<main class="w-full p-4 md:p-8 bg-background min-h-screen font-montserrat">
    <section class="max-w-5xl mx-auto space-y-6">

        <!-- Header Halaman -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-border">
            <div>
                <h1 class="text-2xl font-bold text-text leading-snug">Logbook Mahasiswa</h1>
                <p class="text-sm font-semibold text-primary mt-1 flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    {{ $mahasiswa->user->name }}
                    -
                    {{ $mahasiswa->jurusan }}
                </p>
            </div>

            <x-buttonv2 href="{{ route('asn-index') }}" color="accent-dark" class="w-full sm:w-auto">
               <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Daftar
            </x-buttonv2>
        </div>

        @if ($bulanList->isEmpty())
            <!-- Tampilan Jika Data Kosong -->
            <div class="py-16 bg-surface border border-border rounded-2xl text-center shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 mx-auto text-border mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 class="text-lg font-bold text-text">Periode Magang Belum Diatur</h3>
                <p class="text-sm text-text-light mt-1">Tanggal mulai dan selesai magang mahasiswa ini belum diisi di dalam sistem.</p>
            </div>
        @else
            <!-- Legenda / Keterangan -->
            <div class="flex items-center gap-5 bg-surface border border-border px-4 py-3 rounded-xl w-fit shadow-sm">
                <div class="flex items-center gap-2">
                    <div class="relative w-5 h-5 bg-primary/10 border border-primary/30 rounded flex items-center justify-center">
                        <span class="absolute -top-1 -right-1 w-2 h-2 bg-danger rounded-full"></span>
                    </div>
                    <span class="text-xs font-semibold text-text">Ada Kegiatan</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-5 h-5 bg-background border border-border rounded opacity-50"></div>
                    <span class="text-xs font-semibold text-text-light">Kosong / Libur</span>
                </div>
            </div>

            <!-- Grid Kalender (Bulan) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($bulanList as $bulan)
                    <div class="bg-surface border border-border rounded-xl shadow-sm p-5 hover:shadow-md transition-shadow">

                        <!-- Header Bulan -->
                        <h2 class="text-sm font-bold text-text mb-4 pb-3 border-b border-border flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ $bulan['label'] }}
                        </h2>

                        <!-- Grid Tanggal -->
                        <div class="flex flex-wrap gap-2">
                            @for ($tanggal = 1; $tanggal <= $bulan['jumlah_hari']; $tanggal++)
                                @php
                                    $tanggalFormat = sprintf('%04d-%02d-%02d', $bulan['tahun'], $bulan['bulan'], $tanggal);
                                    $adaKegiatan = $tanggalAktif->contains($tanggalFormat);
                                @endphp

                                @if ($adaKegiatan)
                                    <!-- Tanggal dengan Kegiatan -->
                                    <a href="{{ route('asn-logbook-mahasiswa-tanggal', [$mahasiswa->id, $tanggalFormat]) }}"
                                        class="relative w-9 h-9 flex items-center justify-center bg-primary/10 border border-primary/30 rounded-lg text-sm font-bold text-primary-dark hover:bg-primary hover:text-white hover:border-primary hover:-translate-y-0.5 transition-all shadow-sm group">
                                        {{ $tanggal }}
                                        <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-danger rounded-full border-2 border-surface group-hover:border-primary transition-colors"></span>
                                    </a>
                                @else
                                    <!-- Tanggal Kosong -->
                                    <button type="button" disabled
                                        class="relative w-9 h-9 flex items-center justify-center bg-background border border-border rounded-lg text-sm font-medium text-text-light/50 cursor-not-allowed">
                                        {{ $tanggal }}
                                    </button>
                                @endif
                            @endfor
                        </div>

                    </div>
                @endforeach
            </div>
        @endif

    </section>
</main>
@endsection
