@extends('layouts.app')

@section('content')
    <main class="relative w-full min-h-screen flex bg-background overflow-x-hidden">
        {{-- @include('components.header-mobile') --}}
        {{-- @include('components.asn.asn-sidebar') --}}

        <section class="flex flex-col flex-1 min-w-0 container-content-mobile">
            {{-- @include('components.asn.header-asn') --}}

            @php
                $profil = $detailUser->mahasiswaProfile;
                $isKelompok = $profil && $profil->isKelompok();
            @endphp

            <section class="w-full min-w-0 p-4 md:p-6 font-montserrat">
                <div class="max-w-5xl mx-auto space-y-6">

                    <!-- Bar Navigasi Atas -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <a href="{{ route('asn-permintaan-bimbingan') }}"
                            class="inline-flex items-center gap-2 text-xs font-bold text-text-light hover:text-primary transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                            Kembali ke Permintaan Bimbingan
                        </a>

                        @if ($profil?->status_pengajuan === 'menunggu_asn')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-warning/15 text-warning-dark border border-warning/30">
                                Menunggu Konfirmasi Anda
                            </span>
                        @elseif ($profil?->status_pengajuan === 'diterima')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-success/10 text-success border border-success/20">
                                Bimbingan Aktif / Diterima
                            </span>
                        @endif
                    </div>

                    <!-- Kartu Utama Profil -->
                    <div class="bg-surface border border-border rounded-2xl p-6 shadow-xs space-y-6">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5 border-b border-border pb-6">
                            @if ($profil?->foto_profil_path)
                                <img src="{{ asset('storage/' . $profil->foto_profil_path) }}"
                                    alt="{{ $detailUser->name }}"
                                    class="w-20 h-20 rounded-2xl object-cover border border-primary/20 shrink-0">
                            @else
                                <div class="w-20 h-20 rounded-2xl bg-primary/10 text-primary border border-primary/20 flex items-center justify-center text-2xl font-bold shrink-0">
                                    {{ strtoupper(substr($detailUser->name, 0, 1)) }}
                                </div>
                            @endif

                            <div class="space-y-1.5 min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h1 class="text-xl font-bold text-text">{{ $detailUser->name }}</h1>
                                    @if ($isKelompok)
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-primary/10 text-primary border border-primary/20">
                                            {{ $profil->is_ketua_kelompok ? 'Ketua Kelompok' : 'Anggota Kelompok' }}
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-background text-text border border-border">
                                            Peserta Individu
                                        </span>
                                    @endif
                                </div>

                                <p class="text-xs text-text-light">{{ $detailUser->email }}</p>
                                <p class="text-xs text-text-light">
                                    No. Telepon / WA: <strong class="text-text">{{ $detailUser->phone ?? '-' }}</strong>
                                </p>
                            </div>
                        </div>

                        <!-- Grid Informasi Akademik & Magang -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-xs">
                            <div class="bg-background border border-border rounded-xl p-4 space-y-3">
                                <p class="font-bold uppercase tracking-wider text-text-light">Informasi Akademik</p>

                                <div class="flex justify-between gap-4 border-b border-border/60 pb-2">
                                    <span class="text-text-light">NIM / NIS</span>
                                    <span class="font-mono font-bold text-text">{{ $profil->nim ?? '-' }}</span>
                                </div>

                                <div class="flex justify-between gap-4 border-b border-border/60 pb-2">
                                    <span class="text-text-light">Instansi Asal</span>
                                    <span class="font-semibold text-text text-right">{{ $profil->instansi_asal ?? '-' }}</span>
                                </div>

                                <div class="flex justify-between gap-4 border-b border-border/60 pb-2">
                                    <span class="text-text-light">Jenjang</span>
                                    <span class="font-semibold text-text">{{ $profil->jenjang ?? '-' }}</span>
                                </div>

                                <div class="flex justify-between gap-4">
                                    <span class="text-text-light">Jurusan / Prodi</span>
                                    <span class="font-semibold text-text text-right">{{ $profil->jurusan ?? '-' }}</span>
                                </div>
                            </div>

                            <div class="bg-background border border-border rounded-xl p-4 space-y-3">
                                <p class="font-bold uppercase tracking-wider text-text-light">Periode & Biodata</p>

                                <div class="flex justify-between gap-4 border-b border-border/60 pb-2">
                                    <span class="text-text-light">Tanggal Mulai</span>
                                    <span class="font-semibold text-text">
                                        {{ $profil?->tanggal_mulai?->translatedFormat('d F Y') ?? '-' }}
                                    </span>
                                </div>

                                <div class="flex justify-between gap-4 border-b border-border/60 pb-2">
                                    <span class="text-text-light">Tanggal Selesai</span>
                                    <span class="font-semibold text-text">
                                        {{ $profil?->tanggal_selesai?->translatedFormat('d F Y') ?? '-' }}
                                    </span>
                                </div>

                                <div class="flex justify-between gap-4 border-b border-border/60 pb-2">
                                    <span class="text-text-light">Tanggal Lahir</span>
                                    <span class="font-semibold text-text">
                                        {{ $profil?->tanggal_lahir?->translatedFormat('d F Y') ?? '-' }}
                                    </span>
                                </div>

                                <div class="flex justify-between gap-4">
                                    <span class="text-text-light">Alamat</span>
                                    <span class="font-semibold text-text text-right">{{ $profil->alamat ?? '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Keahlian / Skills -->
                        <div class="space-y-2">
                            <p class="text-xs font-bold uppercase tracking-wider text-text-light">Keahlian / Kompetensi</p>
                            @if ($profil && $profil->skills->isNotEmpty())
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($profil->skills as $skill)
                                        <span class="px-3 py-1 rounded-lg text-xs font-semibold bg-primary/10 text-primary border border-primary/20">
                                            {{ $skill->nama_skill }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-xs text-text-light italic">Belum ada data keahlian yang ditambahkan.</p>
                            @endif
                        </div>

                        <!-- Surat Pengantar (Jika Ada) -->
                        @if ($profil?->surat_pengantar_path)
                            <div class="pt-4 border-t border-border flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div>
                                    <p class="text-xs font-bold text-text">Dokumen Surat Pengantar</p>
                                    <p class="text-[11px] text-text-light">Lampiran surat pengantar magang dari instansi asal peserta.</p>
                                </div>
                                <a href="{{ asset('storage/' . $profil->surat_pengantar_path) }}" target="_blank"
                                    class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg bg-primary hover:bg-primary-dark text-white text-xs font-bold transition-colors">
                                    Lihat Dokumen
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Daftar Anggota Kelompok (Jika Kelompok) -->
                    @if ($isKelompok && $anggotaKelompok->count() > 1)
                        <div class="bg-surface border border-border rounded-2xl p-6 shadow-xs space-y-4">
                            <div>
                                <h2 class="text-base font-bold text-text">Anggota Kelompok ({{ $anggotaKelompok->count() }} Peserta)</h2>
                                <p class="text-xs text-text-light">Kode Kelompok: <span class="font-mono">{{ $profil->kode_kelompok }}</span></p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach ($anggotaKelompok as $anggota)
                                    <div class="bg-background border border-border rounded-xl p-3.5 flex items-center justify-between gap-3">
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-text truncate">
                                                {{ $loop->iteration }}. {{ $anggota->user?->name ?? '-' }}
                                                @if ($anggota->is_ketua_kelompok)
                                                    <span class="text-primary">(Ketua)</span>
                                                @endif
                                            </p>
                                            <p class="text-[11px] text-text-light truncate">
                                                NIM: {{ $anggota->nim ?? '-' }} • {{ $anggota->jurusan ?? '-' }}
                                            </p>
                                        </div>

                                        @if ($anggota->user && $anggota->user->id !== $detailUser->id)
                                            <a href="{{ route('asn-mahasiswa-detail', $anggota->user->id) }}"
                                                class="shrink-0 px-3 py-1.5 rounded-lg bg-surface hover:bg-primary border border-border hover:border-primary text-text hover:text-white text-[11px] font-semibold transition-colors">
                                                Lihat Profil
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>
            </section>
        </section>
    </main>
@endsection
