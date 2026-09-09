@extends('layouts.app')

@section('content')
    <section class="w-full p-3 md:p-6 font-montserrat">
        <div class="max-w-7xl mx-auto">

            <!-- Tombol Kembali -->
            <div class="mb-5 flex justify-start">
                <a href="{{ route('admin-index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 text-xs md:text-sm font-semibold text-text-light bg-surface border border-border rounded-lg shadow-sm hover:bg-background transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>

            <!-- Header Halaman -->
            <div class="mb-6">
                <h1 class="text-xl md:text-2xl font-bold text-text">Data Magang Nonaktif</h1>
                <p class="text-xs md:text-sm text-text-light mt-1">Daftar mahasiswa magang yang status akun atau kegiatannya
                    sedang tidak aktif.</p>
            </div>

            <!-- Navigation Tabs -->
            <div class="mb-6 border-b border-border overflow-x-auto scrollbar-none">
                <nav class="flex space-x-2 md:space-x-4 min-w-max pb-2">
                    <a href="{{ route('magang-aktif') }}"
                        class="px-4 py-2 text-xs md:text-sm font-medium text-text-light hover:text-text hover:bg-background rounded-lg transition-all">
                        Aktif
                    </a>
                    <a href="{{ route('magang-nonaktif') }}"
                        class="px-4 py-2 text-xs md:text-sm font-semibold rounded-lg bg-slate-700 text-white shadow-sm transition-all">
                        Nonaktif
                    </a>
                    <a href="{{ route('magang-pending') }}"
                        class="px-4 py-2 text-xs md:text-sm font-medium text-text-light hover:text-text hover:bg-background rounded-lg transition-all">
                        Pending
                    </a>
                    <a href="{{ route('magang-selesai') }}"
                        class="px-4 py-2 text-xs md:text-sm font-medium text-text-light hover:text-text hover:bg-background rounded-lg transition-all">
                        Selesai
                    </a>
                    <a href="{{ route('magang-batal') }}"
                        class="px-4 py-2 text-xs md:text-sm font-medium text-text-light hover:text-text hover:bg-background rounded-lg transition-all">
                        Batal
                    </a>
                </nav>
            </div>

            <!-- Data Table Container -->
            <div class="w-full bg-surface rounded-xl shadow-sm border border-border overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <!-- Table Header -->
                        <thead
                            class="bg-background/60 border-b border-border text-text-light text-xs uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-4 font-bold">Mahasiswa</th>
                                <th class="px-6 py-4 font-bold">Instansi & Jurusan</th>
                                <th class="px-6 py-4 font-bold">Periode Magang</th>
                                <th class="px-6 py-4 font-bold">Kontak</th>
                                <th class="px-6 py-4 font-bold text-center">Status</th>
                            </tr>
                        </thead>

                        <!-- Table Body -->
                        <tbody class="divide-y divide-border text-xs md:text-sm">
                            @forelse ($magangNonaktif as $item)
                                @php
                                    $profile = is_array($item)
                                        ? $item['mahasiswa_profile'] ?? null
                                        : $item->mahasiswaProfile ?? ($item->mahasiswa_profile ?? null);
                                    $name = is_array($item) ? $item['name'] : $item->name;
                                    $email = is_array($item) ? $item['email'] : $item->email;
                                    $phone = is_array($item) ? $item['phone'] : $item->phone;

                                    $nim = $profile['nim'] ?? ($profile->nim ?? '-');
                                    $instansi = $profile['instansi_asal'] ?? ($profile->instansi_asal ?? '-');
                                    $jenjang = $profile['jenjang'] ?? ($profile->jenjang ?? '');
                                    $jurusan = $profile['jurusan'] ?? ($profile->jurusan ?? '-');
                                    $tglMulai = isset($profile['tanggal_mulai'])
                                        ? \Carbon\Carbon::parse($profile['tanggal_mulai'])->format('d M Y')
                                        : '-';
                                    $tglSelesai = isset($profile['tanggal_selesai'])
                                        ? \Carbon\Carbon::parse($profile['tanggal_selesai'])->format('d M Y')
                                        : '-';
                                @endphp

                                <tr class="hover:bg-background/80 transition-colors duration-150 align-middle">
                                    <!-- Nama & NIM -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-full bg-slate-100 text-slate-600 font-bold flex items-center justify-center shrink-0 border border-slate-200 text-sm">
                                                {{ strtoupper(substr($name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-text">{{ $name }}</div>
                                                <div class="text-xs text-text-light">NIM: {{ $nim }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Instansi & Jurusan -->
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-text">{{ $instansi }}</div>
                                        <div class="text-xs text-text-light">{{ $jenjang }} - {{ $jurusan }}
                                        </div>
                                    </td>

                                    <!-- Periode Magang -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-text font-medium">{{ $tglMulai }} – {{ $tglSelesai }}</div>
                                    </td>

                                    <!-- Kontak -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-text font-medium">{{ $email }}</div>
                                        <div class="text-xs text-text-light">{{ $phone ?? 'Tidak ada No HP' }}</div>
                                    </td>

                                    <!-- Status Badge -->
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-100 border border-slate-200 text-slate-600 text-xs font-bold rounded-full">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                            Nonaktif
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-text-light">
                                        <p class="text-sm">Tidak ada data mahasiswa magang yang nonaktif.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>
@endsection
