@extends('layouts.app')

@section('content')
    <main class="w-full p-4 md:p-8 bg-background min-h-screen font-montserrat">
        <section class="container-dalam max-w-4xl mx-auto">

            <!-- Card Utama Profil -->
            <div class="bg-surface rounded-md shadow-sm border border-border overflow-hidden">

                <!-- Header Cover Banner -->
                <div class="h-32 md:h-44 bg-gradient-to-r from-primary-dark via-primary to-secondary relative"></div>

                <!-- Section Top Profil & Foto -->
                <div class="px-6 md:px-10 pb-6 relative">

                    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 -mt-16 md:-mt-20 mb-6">
                        <!-- Wrapper Foto Profil (Siap untuk Fitur Foto) -->
                        <div class="relative group w-32 h-32 md:w-36 md:h-36 shrink-0">
                            <div
                                class="w-full h-full rounded-full border-4 border-surface bg-background shadow-md overflow-hidden flex items-center justify-center">
                                @if (!empty($profil->avatar))
                                    <img src="{{ asset('storage/' . $profil->avatar) }}" alt="{{ $profil->name }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <!-- Fallback Inisial Nama -->
                                    <div
                                        class="w-full h-full bg-primary/10 text-primary flex items-center justify-center text-4xl md:text-5xl font-bold uppercase">
                                        {{ strtoupper(substr($profil->name ?? 'M', 0, 1)) }}
                                    </div>
                                @endif
                            </div>

                            <!-- Overlay Placeholder untuk Fitur Unggah Foto (Belum Aktif) -->
                            <div class="absolute inset-0 rounded-full bg-black/40 flex flex-col items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-opacity cursor-not-allowed"
                                title="Fitur ubah foto belum tersedia">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 9a2 2 0 012-2h0.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="text-[10px] font-semibold mt-1">Ubah Foto</span>
                            </div>
                        </div>

                        <!-- Status Badge & Action -->
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-semibold text-text-light uppercase tracking-wider">Status
                                Magang:</span>
                            @php
                                $statusMagang = strtolower(
                                    $profil->mahasiswaProfile->status ??
                                        ($profil->mahasiswa_profile->status ?? 'nonaktif'),
                                );
                            @endphp
                            @if ($statusMagang === 'aktif')
                                <span
                                    class="inline-flex px-3.5 py-1.5 bg-success/10 border border-success/20 text-success-dark text-xs font-bold rounded-full items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-success animate-pulse"></span>
                                    Aktif
                                </span>
                            @else
                                <span
                                    class="inline-flex px-3.5 py-1.5 bg-text-light/10 border border-border text-text-light text-xs font-bold rounded-full items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-text-light"></span>
                                    {{ ucfirst($statusMagang) }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Informasi Nama & NIM -->
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-text">{{ $profil->name ?? '-' }}</h1>
                        <div class="flex flex-wrap items-center gap-2 md:gap-4 mt-1.5 text-sm text-text-light">
                            <p class="font-mono font-medium text-primary">NIM.
                                {{ $profil->mahasiswaProfile->nim ?? ($profil->mahasiswa_profile->nim ?? '-') }}</p>
                            <span>•</span>
                            <p
                                class="uppercase font-semibold tracking-wide text-xs bg-background border border-border px-2.5 py-0.5 rounded-md text-text-light">
                                {{ $profil->role ?? 'Mahasiswa' }}
                            </p>
                        </div>
                    </div>

                </div>

                <hr class="border-border">

                <!-- Grid Detail Informasi -->
                <div class="p-6 md:p-10 space-y-8">

                    <!-- SEKSI 1: Informasi Akademik & Magang -->
                    <div class="space-y-4">
                        <h2 class="text-lg font-bold text-text flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            </svg>
                            Informasi Akademik & Magang
                        </h2>

                        <div
                            class="grid grid-cols-1 md:grid-cols-2 gap-5 bg-background p-6 rounded-xl border border-border">
                            <div>
                                <p class="text-xs font-semibold text-text-light uppercase tracking-wider mb-1">Instansi Asal
                                </p>
                                <p class="font-medium text-text text-sm">
                                    {{ $profil->mahasiswaProfile->instansi_asal ?? ($profil->mahasiswa_profile->instansi_asal ?? '-') }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs font-semibold text-text-light uppercase tracking-wider mb-1">Jenjang &
                                    Program Studi</p>
                                <p class="font-medium text-text text-sm">
                                    {{ $profil->mahasiswaProfile->jenjang ?? ($profil->mahasiswa_profile->jenjang ?? '') }} -
                                    {{ $profil->mahasiswaProfile->jurusan ?? ($profil->mahasiswa_profile->jurusan ?? '-') }}
                                </p>
                            </div>

                            <div class="md:col-span-2">
                                <p class="text-xs font-semibold text-text-light uppercase tracking-wider mb-1">Periode
                                    Magang</p>
                                <div class="font-medium text-text text-sm flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary shrink-0"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    @php
                                        $tglMulai =
                                            $profil->mahasiswaProfile->tanggal_mulai ??
                                            ($profil->mahasiswa_profile->tanggal_mulai ?? null);
                                        $tglSelesai =
                                            $profil->mahasiswaProfile->tanggal_selesai ??
                                            ($profil->mahasiswa_profile->tanggal_selesai ?? null);
                                    @endphp
                                    <span>
                                        {{ $tglMulai ? \Carbon\Carbon::parse($tglMulai)->translatedFormat('d F Y') : '-' }}
                                        &nbsp;—&nbsp;
                                        {{ $tglSelesai ? \Carbon\Carbon::parse($tglSelesai)->translatedFormat('d F Y') : '-' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SEKSI 2: Kontak & Personal -->
                    <div class="space-y-4">
                        <h2 class="text-lg font-bold text-text flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Kontak & Data Pribadi
                        </h2>

                        <div
                            class="grid grid-cols-1 md:grid-cols-2 gap-5 bg-background p-6 rounded-xl border border-border">
                            <div>
                                <p class="text-xs font-semibold text-text-light uppercase tracking-wider mb-1">Email</p>
                                <p class="font-medium text-text text-sm">{{ $profil->email ?? '-' }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-semibold text-text-light uppercase tracking-wider mb-1">No. Telepon /
                                    WhatsApp</p>
                                <p class="font-medium text-text text-sm">{{ $profil->phone ?? '-' }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-semibold text-text-light uppercase tracking-wider mb-1">Tanggal Lahir
                                </p>
                                @php
                                    $tglLahir =
                                        $profil->mahasiswaProfile->tanggal_lahir ??
                                        ($profil->mahasiswa_profile->tanggal_lahir ?? null);
                                @endphp
                                <p class="font-medium text-text text-sm">
                                    @if ($tglLahir)
                                        {{ \Carbon\Carbon::parse($tglLahir)->translatedFormat('d F Y') }}
                                        <span
                                            class="text-xs text-text-light font-normal">({{ \Carbon\Carbon::parse($tglLahir)->age }}
                                            tahun)</span>
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>

                            <div class="md:col-span-2">
                                <p class="text-xs font-semibold text-text-light uppercase tracking-wider mb-1">Alamat Tempat
                                    Tinggal</p>
                                <p class="font-medium text-text text-sm leading-relaxed">
                                    {{ $profil->mahasiswaProfile->alamat ?? ($profil->mahasiswa_profile->alamat ?? '-') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- SEKSI 3: Keahlian / Skills -->
                    <div class="space-y-4">
                        <h2 class="text-lg font-bold text-text flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            Keahlian & Keterampilan
                        </h2>

                        <div class="bg-background p-6 rounded-xl border border-border">
                            @php
                                $skills =
                                    $profil->mahasiswaProfile->skills ?? ($profil->mahasiswa_profile->skills ?? []);
                            @endphp

                            @if (count($skills) > 0)
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($skills as $skill)
                                        <span
                                            class="px-3.5 py-1.5 bg-surface border border-border text-primary font-medium text-xs rounded-lg shadow-sm">
                                            {{ $skill->nama_skill ?? $skill['nama_skill'] }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-text-light italic">Belum ada keahlian yang ditambahkan.</p>
                            @endif
                        </div>
                    </div>

                    <div
                    class="bg-footer rounded-xl border-t border-border px-6 md:px-10 py-5 flex flex-col sm:flex-row gap-3 sm:justify-between items-center font-montserrat">

                    <!-- Aksi Hapus & Edit (Di Sisi Kanan) -->
                    <div class="flex  flex-col-reverse sm:flex-row gap-3 w-full sm:w-auto items-center">

                        <!-- Tombol Edit Data -->
                        <x-buttonv2 href="{{ route('mahasiswa-profil-form', $profil->id) }}" color="accent-dark"
                            class="w-full sm:w-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="3" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.862 3.487a2.25 2.25 0 113.182 3.182L8.25 18.463 3 21l2.537-5.25L16.862 3.487z" />
                            </svg>
                            Edit profil saya
                        </x-buttonv2>

                    </div>

                    <!-- Tombol Kembali (Di Sisi Kiri) -->
                    <x-buttonv2 href="{{ route('mahasiswa-index') }}" color="accent-primary" class="w-full sm:w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" stroke-width="3" class="h-4 w-4" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                                clip-rule="evenodd" />
                        </svg>
                        Kembali
                    </x-buttonv2>

                </div>

                </div>

            </div>
        </section>
    </main>
@endsection
