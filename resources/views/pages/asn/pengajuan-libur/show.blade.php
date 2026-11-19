@extends('layouts.app')

@section('content')
    <main class="relative w-full min-h-screen flex bg-background overflow-x-hidden">
        @include('components.header-mobile')
        @include('components.asn.asn-sidebar')

        <section class="flex flex-col flex-1 min-w-0 md:pl-60 container-content-mobile">
            @include('components.asn.header-asn')

            @php
                $mhsProfil = $pengajuan->mahasiswaProfile;
                $mhsUser = $mhsProfil?->user;
            @endphp

            <section class="w-full p-2">
                <section class="container-dalam max-w-4xl mx-auto flex flex-col gap-4 font-montserrat">

                    <!-- Bar Navigasi Atas -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <a href="{{ route('asn.pengajuan-libur.index') }}"
                            class="inline-flex items-center gap-2 text-xs font-bold text-text-light hover:text-primary transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                            Kembali ke Daftar Pengajuan Libur
                        </a>

                        @if ($pengajuan->status === 'pending')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-warning/15 text-warning-dark border border-warning/30">
                                Menunggu Persetujuan Anda
                            </span>
                        @elseif ($pengajuan->status === 'disetujui')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-success/10 text-success border border-success/20">
                                Disetujui
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-danger/10 text-danger border border-danger/20">
                                Ditolak
                            </span>
                        @endif
                    </div>

                    @if ($errors->any())
                        <div class="p-4 rounded-xl bg-danger/10 border border-danger/30 text-danger text-xs">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Kartu Detail Pengajuan Libur -->
                    <div class="bg-surface border border-border rounded-2xl p-6 md:p-8 shadow-xs space-y-6">
                        <div class="border-b border-border pb-4">
                            <h1 class="text-xl font-bold text-text">Detail Pengajuan Surat Libur</h1>
                            <p class="text-xs text-text-light mt-0.5">
                                Diajukan pada {{ $pengajuan->created_at->translatedFormat('d F Y, H:i') }} WIB
                            </p>
                        </div>

                        <!-- Identitas Peserta & Rentang Libur -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-background p-5 rounded-xl border border-border text-xs">
                            <div>
                                <p class="font-bold uppercase tracking-wider text-text-light mb-1">Nama Peserta Magang</p>
                                <p class="text-sm font-bold text-text">{{ $mhsUser?->name ?? '-' }}</p>
                                <p class="text-text-light mt-0.5">
                                    NIM: <span class="font-mono font-semibold text-text">{{ $mhsProfil?->nim ?? '-' }}</span>
                                </p>
                            </div>

                            <div>
                                <p class="font-bold uppercase tracking-wider text-text-light mb-1">Instansi & Jurusan</p>
                                <p class="text-sm font-semibold text-text">{{ $mhsProfil?->instansi_asal ?? '-' }}</p>
                                <p class="text-text-light mt-0.5">{{ $mhsProfil?->jurusan ?? '-' }}</p>
                            </div>

                            <div class="pt-3 border-t border-border/60">
                                <p class="font-bold uppercase tracking-wider text-text-light mb-1">Rentang Tanggal Libur</p>
                                <p class="text-sm font-bold text-text">
                                    {{ $pengajuan->tanggal_mulai->translatedFormat('d F Y') }}
                                    s.d.
                                    {{ $pengajuan->tanggal_selesai->translatedFormat('d F Y') }}
                                </p>
                            </div>

                            <div class="pt-3 border-t border-border/60">
                                <p class="font-bold uppercase tracking-wider text-text-light mb-1">Total Durasi</p>
                                <p class="text-sm font-bold text-primary">{{ $pengajuan->jumlah_hari }} Hari</p>
                            </div>
                        </div>

                        <!-- Alasan -->
                        <div class="space-y-2">
                            <p class="text-xs font-bold uppercase tracking-wider text-text-light">Alasan Pengajuan Libur</p>
                            <div class="p-4 bg-background border border-border rounded-xl text-sm text-text leading-relaxed whitespace-pre-line">
                                {{ $pengajuan->alasan }}
                            </div>
                        </div>

                        <!-- Lampiran File -->
                        <div class="space-y-3 pt-2 border-t border-border">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider text-text-light">Lampiran Surat / Bukti</p>
                                    <p class="text-xs font-medium text-text mt-0.5">
                                        {{ $pengajuan->nama_file_asli ?? basename($pengajuan->file_lampiran) }}
                                    </p>
                                </div>

                                <a href="{{ asset('storage/' . $pengajuan->file_lampiran) }}" target="_blank"
                                    class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg bg-primary hover:bg-primary-dark text-white text-xs font-bold transition-colors">
                                    Buka / Unduh Lampiran
                                </a>
                            </div>

                            @if ($pengajuan->isImage())
                                <div class="p-4 bg-background border border-border rounded-xl flex justify-center">
                                    <img src="{{ asset('storage/' . $pengajuan->file_lampiran) }}"
                                        alt="Lampiran Surat Libur"
                                        class="max-h-96 rounded-lg object-contain">
                                </div>
                            @elseif ($pengajuan->isPdf())
                                <div class="border border-border rounded-xl overflow-hidden bg-background">
                                    <iframe src="{{ asset('storage/' . $pengajuan->file_lampiran) }}"
                                        class="w-full h-96" title="Preview Surat Libur PDF"></iframe>
                                </div>
                            @else
                                <div class="p-4 bg-background border border-border rounded-xl text-xs text-text-light">
                                    Dokumen Word ({{ strtoupper($pengajuan->file_extension) }}). Klik tombol <strong>Buka / Unduh Lampiran</strong> di atas untuk melihat isi file.
                                </div>
                            @endif
                        </div>

                        <!-- Form Keputusan ASN (Jika Masih Pending) -->
                        @if ($pengajuan->status === 'pending')
                            <div class="pt-5 border-t border-border space-y-4">
                                <p class="text-xs font-bold uppercase tracking-wider text-text-light">Keputusan Pembimbing ASN</p>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Form Setujui -->
                                    <form method="POST" action="{{ route('asn.pengajuan-libur.setujui', $pengajuan->id) }}"
                                        class="bg-success/5 border border-success/20 rounded-xl p-4 space-y-3">
                                        @csrf
                                        <p class="text-xs font-bold text-success-dark">Setujui Pengajuan Libur</p>
                                        <textarea name="catatan_asn" rows="2"
                                            placeholder="Catatan persetujuan (opsional)..."
                                            class="w-full rounded-lg border border-border bg-surface p-2.5 text-xs text-text focus:outline-none focus:border-success"></textarea>
                                        <button type="submit"
                                            class="w-full py-2.5 rounded-lg bg-success hover:bg-success-dark text-white text-xs font-bold cursor-pointer transition-colors">
                                            Setujui Pengajuan Libur
                                        </button>
                                    </form>

                                    <!-- Form Tolak -->
                                    <form method="POST" action="{{ route('asn.pengajuan-libur.tolak', $pengajuan->id) }}"
                                        class="bg-danger/5 border border-danger/20 rounded-xl p-4 space-y-3">
                                        @csrf
                                        <p class="text-xs font-bold text-danger">Tolak Pengajuan Libur</p>
                                        <textarea name="catatan_asn" rows="2" required
                                            placeholder="Tuliskan alasan penolakan (wajib)..."
                                            class="w-full rounded-lg border border-border bg-surface p-2.5 text-xs text-text focus:outline-none focus:border-danger"></textarea>
                                        <button type="submit"
                                            class="w-full py-2.5 rounded-lg bg-danger hover:opacity-90 text-white text-xs font-bold cursor-pointer transition-opacity">
                                            Tolak Pengajuan Libur
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @elseif ($pengajuan->catatan_asn)
                            <div class="p-4 rounded-xl border text-xs space-y-1 {{ $pengajuan->status === 'disetujui' ? 'bg-success/5 border-success/20' : 'bg-danger/5 border-danger/20' }}">
                                <p class="font-bold {{ $pengajuan->status === 'disetujui' ? 'text-success-dark' : 'text-danger' }}">
                                    Catatan Keputusan Anda:
                                </p>
                                <p class="text-sm text-text">{{ $pengajuan->catatan_asn }}</p>
                            </div>
                        @endif
                    </div>

                </section>
            </section>
        </section>
    </main>
@endsection
