@extends('layouts.app')

@section('content')
    <main class="relative w-full min-h-screen flex bg-background overflow-x-hidden">
        {{-- container-sidebar-admin --}}
        @include('components.header-mobile')
        @include('components.admin.sidebar-admin')

        {{-- container-content --}}
        <section class="flex flex-col flex-1 min-w-0 md:pl-60 container-content-mobile">

            {{-- header --}}
            @include('components.admin.header-admin')

            <section class="w-full p-2">
                <section class="container-dalam flex flex-col gap-4">
                    <section class="w-full min-w-0 font-montserrat">
                        <div class="max-w-7xl mx-auto space-y-6">

                            <!-- Header, Filter Tahun & Pencarian -->
                            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                                <div>
                                    <h1 class="text-xl md:text-2xl font-bold text-text">Laporan Peserta Magang Selesai</h1>
                                    <p class="text-xs md:text-sm text-text-light mt-1">
                                        Daftar peserta magang/PKL yang telah menyelesaikan masa magang dan siap dicetak
                                        laporannya.
                                    </p>
                                </div>

                                <!-- Form Filter Tahun & Pencarian Server-Side -->
                                <form method="GET" action="{{ url()->current() }}"
                                    class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full lg:w-auto">

                                    <!-- Dropdown Filter Tahun -->
                                    <div class="relative sm:w-40 shrink-0">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-text-light"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <select name="tahun" onchange="this.form.submit()"
                                            class="w-full pl-9 pr-8 py-2.5 bg-surface border border-border rounded-lg text-sm text-text focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors shadow-sm cursor-pointer">
                                            <option value="">Semua Tahun</option>
                                            @foreach ($daftarTahun as $tahunItem)
                                                <option value="{{ $tahunItem }}"
                                                    {{ (string) request('tahun') === (string) $tahunItem ? 'selected' : '' }}>
                                                    Tahun {{ $tahunItem }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Input Pencarian -->
                                    <div class="relative flex-1 sm:w-64">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-text-light"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                        <input type="text" name="search" value="{{ request('search') }}"
                                            placeholder="Cari nama, NIM, instansi..."
                                            class="w-full pl-10 pr-4 py-2.5 bg-surface border border-border rounded-lg text-sm text-text placeholder:text-text-light focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors shadow-sm">
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <button type="submit"
                                            class="flex-1 sm:flex-initial px-4 py-2.5 bg-primary hover:bg-primary-dark text-white text-xs font-bold rounded-lg transition-colors cursor-pointer shrink-0 shadow-sm">
                                            Cari
                                        </button>

                                        @if (request()->filled('search') || request()->filled('tahun'))
                                            <a href="{{ url()->current() }}"
                                                class="flex-1 sm:flex-initial text-center px-3 py-2.5 bg-surface hover:bg-background border border-border text-text-light text-xs font-semibold rounded-lg transition-colors shrink-0">
                                                Reset
                                            </a>
                                        @endif
                                    </div>
                                </form>
                            </div>

                            <!-- Tabel Daftar Peserta Magang Selesai -->
                            <div class="bg-surface rounded-xl shadow-sm border border-border overflow-hidden w-full">
                                <div class="w-full overflow-x-auto">
                                    <table class="w-full text-left border-collapse">
                                        <thead
                                            class="hidden md:table-header-group bg-background/60 border-b border-border text-text-light text-[11px] uppercase tracking-wider">
                                            <tr>
                                                <th scope="col" class="px-3.5 py-3.5 font-bold text-center w-12">No</th>
                                                <th scope="col" class="px-4 py-3.5 font-bold min-w-[210px]">Peserta
                                                    Magang</th>
                                                <th scope="col" class="px-3.5 py-3.5 font-bold min-w-[170px]">Instansi &
                                                    Jurusan</th>
                                                <th scope="col" class="px-3.5 py-3.5 font-bold min-w-[160px]">Pembimbing
                                                    ASN</th>
                                                <th scope="col" class="px-3.5 py-3.5 font-bold whitespace-nowrap">Periode
                                                    Magang</th>
                                                <th scope="col"
                                                    class="px-3.5 py-3.5 font-bold text-center whitespace-nowrap">Capaian
                                                </th>
                                                <th scope="col"
                                                    class="px-4 py-3.5 font-bold text-center whitespace-nowrap">Aksi</th>
                                            </tr>
                                        </thead>

                                        <tbody class="block md:table-row-group divide-y divide-border md:divide-y-0">
                                            @forelse ($daftarLaporan as $index => $mhs)
                                                @php
                                                    $profil = $mhs->mahasiswaProfile;
                                                    $nomorUrut = ($daftarLaporan->firstItem() ?? 1) + $index;
                                                @endphp

                                                <tr
                                                    class="block md:table-row p-4 md:p-0 hover:bg-primary/5 transition-colors duration-200 group md:border-b md:border-border last:border-b-0">

                                                    <td
                                                        class="hidden md:table-cell px-3.5 py-4 text-xs font-medium text-text-light text-center align-middle">
                                                        {{ $nomorUrut }}
                                                    </td>

                                                    <!-- Nama & NIM -->
                                                    <td
                                                        class="block md:table-cell md:px-4 md:py-4 mb-3 md:mb-0 align-middle">
                                                        <div
                                                            class="flex justify-between md:justify-start items-center gap-3">
                                                            <span
                                                                class="md:hidden text-[10px] font-bold text-text-light uppercase shrink-0">Peserta:</span>
                                                            <div class="flex items-center gap-3 min-w-0">
                                                                @if ($profil?->foto_profil_path)
                                                                    <img src="{{ asset('storage/' . $profil->foto_profil_path) }}"
                                                                        alt="{{ $mhs->name }}"
                                                                        class="hidden md:block w-9 h-9 rounded-full object-cover shrink-0 border border-primary/20">
                                                                @else
                                                                    <div
                                                                        class="hidden md:flex w-9 h-9 rounded-full bg-primary/10 text-primary shrink-0 items-center justify-center text-xs font-bold border border-primary/20">
                                                                        {{ strtoupper(substr($mhs->name, 0, 1)) }}
                                                                    </div>
                                                                @endif

                                                                <div class="min-w-0 text-right md:text-left">
                                                                    <p
                                                                        class="text-sm font-bold text-text group-hover:text-primary transition-colors leading-snug">
                                                                        {{ $mhs->name }}
                                                                    </p>
                                                                    <p class="text-[11px] font-mono text-text-light mt-0.5">
                                                                        NIM: {{ $profil->nim ?? '-' }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <!-- Instansi & Jurusan -->
                                                    <td
                                                        class="block md:table-cell md:px-3.5 md:py-4 mb-2 md:mb-0 align-middle">
                                                        <div class="flex justify-between items-center md:block gap-3">
                                                            <span
                                                                class="md:hidden text-[10px] font-bold text-text-light uppercase shrink-0">Instansi:</span>
                                                            <div class="text-right md:text-left">
                                                                <p
                                                                    class="text-xs sm:text-sm font-medium text-text leading-snug">
                                                                    {{ $profil->instansi_asal ?? '-' }}
                                                                </p>
                                                                <p class="text-[11px] text-text-light mt-0.5">
                                                                    {{ $profil->jurusan ?? '-' }}
                                                                    ({{ $profil->jenjang ?? '-' }})
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <!-- Pembimbing ASN -->
                                                    <td
                                                        class="block md:table-cell md:px-3.5 md:py-4 mb-2 md:mb-0 align-middle">
                                                        <div class="flex justify-between items-center md:block gap-3">
                                                            <span
                                                                class="md:hidden text-[10px] font-bold text-text-light uppercase shrink-0">Pembimbing:</span>
                                                            <div class="text-right md:text-left">
                                                                <p class="text-xs font-semibold text-text">
                                                                    {{ $profil?->pembimbing?->user?->name ?? '-' }}
                                                                </p>
                                                                <p class="text-[11px] text-text-light mt-0.5">
                                                                    {{ $profil?->pembimbing?->unit_kerja ?? '-' }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <!-- Periode Magang -->
                                                    <td
                                                        class="block md:table-cell md:px-3.5 md:py-4 mb-2 md:mb-0 align-middle whitespace-nowrap">
                                                        <div class="flex justify-between items-center md:block">
                                                            <span
                                                                class="md:hidden text-[10px] font-bold text-text-light uppercase">Periode:</span>
                                                            <div
                                                                class="flex flex-col text-right md:text-left text-xs text-text-light">
                                                                <span class="font-medium text-text">
                                                                    {{ $profil?->tanggal_mulai ? $profil->tanggal_mulai->translatedFormat('d M Y') : '-' }}
                                                                </span>
                                                                <span class="text-[10px] leading-tight">s.d</span>
                                                                <span class="font-medium text-text">
                                                                    {{ $profil?->tanggal_selesai ? $profil->tanggal_selesai->translatedFormat('d M Y') : '-' }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <!-- Ringkasan Capaian -->
                                                    <td
                                                        class="block md:table-cell md:px-3.5 md:py-4 mb-3 md:mb-0 md:text-center align-middle whitespace-nowrap">
                                                        <div
                                                            class="flex justify-between md:flex-col md:justify-center items-center gap-1.5">
                                                            <span
                                                                class="md:hidden text-[10px] font-bold text-text-light uppercase">Capaian:</span>
                                                            <div
                                                                class="flex flex-wrap justify-end md:justify-center gap-1.5">
                                                                <span
                                                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-success/10 text-success border border-success/20">
                                                                    {{ $mhs->total_hari_masuk }} Hari Masuk
                                                                </span>
                                                                <span
                                                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-warning/15 text-warning-dark border border-warning/30">
                                                                    {{ $mhs->total_hari_libur }} Hari Libur
                                                                </span>
                                                                <span
                                                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-primary/10 text-primary-dark border border-primary/20">
                                                                    {{ $mhs->total_tugas_selesai }} Tugas Selesai
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <!-- Aksi Lihat Laporan -->
                                                    <td
                                                        class="block md:table-cell md:px-4 md:py-4 md:text-center mt-3 md:mt-0 pt-3 md:pt-0 border-t border-border md:border-0 align-middle whitespace-nowrap">
                                                        <x-buttonv2 href="{{ route('admin.laporan.show', $mhs->id) }}"
                                                            color="accent-dark"
                                                            class="w-full md:w-auto text-xs px-3.5 py-2">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24" stroke-width="2"
                                                                stroke="currentColor" class="w-4 h-4 shrink-0">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                            </svg>
                                                            <span>Laporan</span>
                                                        </x-buttonv2>
                                                    </td>

                                                </tr>
                                            @empty
                                                <tr class="block md:table-row">
                                                    <td colspan="7" class="block md:table-cell px-6 py-14 text-center">
                                                        <div class="flex flex-col items-center justify-center">
                                                            <div
                                                                class="w-14 h-14 rounded-full bg-border/30 text-text-light flex items-center justify-center mb-4">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7"
                                                                    fill="none" viewBox="0 0 24 24"
                                                                    stroke="currentColor" stroke-width="1.5">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                </svg>
                                                            </div>
                                                            <p class="text-base font-bold text-text">
                                                                {{ request()->filled('search') || request()->filled('tahun')
                                                                    ? 'Data Laporan Tidak Ditemukan'
                                                                    : 'Belum Ada Peserta Magang Selesai' }}
                                                            </p>
                                                            <p class="text-sm text-text-light mt-1">
                                                                {{ request()->filled('search') || request()->filled('tahun')
                                                                    ? 'Tidak ada peserta selesai yang sesuai dengan filter tahun atau kata kunci pencarian.'
                                                                    : 'Laporan akan muncul otomatis setelah peserta magang menyelesaikan periode magangnya.' }}
                                                            </p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                @if ($daftarLaporan->hasPages())
                                    <div class="px-4 py-3.5 border-t border-border bg-background/40">
                                        {{ $daftarLaporan->links() }}
                                    </div>
                                @endif
                            </div>

                        </div>
                    </section>
                </section>
            </section>
        </section>
    </main>
@endsection
