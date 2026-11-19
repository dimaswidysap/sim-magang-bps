@extends('layouts.app')

@section('content')
    <main class="relative w-full min-h-screen flex bg-background overflow-x-hidden">


        <section class="flex flex-col flex-1 min-w-0  container-content-mobile">


            <section class="w-full p-2">
                <section class="container-dalam max-w-4xl mx-auto flex flex-col gap-4 font-montserrat">

                    <!-- Bar Navigasi & Aksi Cepat -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <a href="{{ route('mahasiswa.pengajuan-libur.index') }}"
                            class="inline-flex items-center gap-2 text-xs font-bold text-text-light hover:text-primary transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                            Kembali ke Daftar Pengajuan Libur
                        </a>

                        @if ($pengajuan->bisaDieditAtauDihapus())
                            <div class="flex items-center gap-2">
                                <a href="{{ route('mahasiswa.pengajuan-libur.edit', $pengajuan->id) }}"
                                    class="px-4 py-2 rounded-lg bg-warning text-white text-xs font-bold hover:opacity-90 transition-opacity">
                                    Edit Pengajuan
                                </a>

                                <form method="POST"
                                    action="{{ route('mahasiswa.pengajuan-libur.destroy', $pengajuan->id) }}"
                                    data-confirm="Apakah Anda yakin ingin menghapus pengajuan surat libur ini?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-4 py-2 rounded-lg bg-danger text-white text-xs font-bold hover:opacity-90 transition-opacity cursor-pointer">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>

                    <!-- Kartu Detail Pengajuan -->
                    <div class="bg-surface border border-border rounded-2xl p-6 md:p-8 shadow-xs space-y-6">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-border pb-4">
                            <div>
                                <h1 class="text-xl font-bold text-text">Detail Surat Pengajuan Libur</h1>
                                <p class="text-xs text-text-light mt-0.5">
                                    Diajukan pada {{ $pengajuan->created_at->translatedFormat('d F Y, H:i') }} WIB
                                </p>
                            </div>

                            @if ($pengajuan->status === 'disetujui')
                                <span class="self-start sm:self-center inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-success/10 border border-success/20 text-success-dark">
                                    <span class="w-2 h-2 rounded-full bg-success"></span>
                                    Disetujui Pembimbing ASN
                                </span>
                            @elseif ($pengajuan->status === 'ditolak')
                                <span class="self-start sm:self-center inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-danger/10 border border-danger/20 text-danger-dark">
                                    <span class="w-2 h-2 rounded-full bg-danger"></span>
                                    Ditolak Pembimbing ASN
                                </span>
                            @else
                                <span class="self-start sm:self-center inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-warning/15 border border-warning/30 text-warning-dark">
                                    <span class="w-2 h-2 rounded-full bg-warning"></span>
                                    Menunggu Persetujuan ASN
                                </span>
                            @endif
                        </div>

                        <!-- Informasi Rentang Tanggal & Pembimbing -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-background p-5 rounded-xl border border-border text-xs">
                            <div>
                                <p class="font-bold uppercase tracking-wider text-text-light mb-1">Tanggal Mulai</p>
                                <p class="text-sm font-bold text-text">
                                    {{ $pengajuan->tanggal_mulai->translatedFormat('d F Y') }}
                                </p>
                            </div>

                            <div>
                                <p class="font-bold uppercase tracking-wider text-text-light mb-1">Tanggal Selesai</p>
                                <p class="text-sm font-bold text-text">
                                    {{ $pengajuan->tanggal_selesai->translatedFormat('d F Y') }}
                                </p>
                            </div>

                            <div>
                                <p class="font-bold uppercase tracking-wider text-text-light mb-1">Total Durasi</p>
                                <p class="text-sm font-bold text-primary">
                                    {{ $pengajuan->jumlah_hari }} Hari
                                </p>
                            </div>

                            <div class="md:col-span-3 pt-3 border-t border-border/60">
                                <p class="font-bold uppercase tracking-wider text-text-light mb-1">Pembimbing ASN</p>
                                <p class="text-sm font-semibold text-text">
                                    {{ $pengajuan->pembimbing?->user?->name ?? '-' }}
                                    @if ($pengajuan->pembimbing?->nip)
                                        <span class="text-xs font-mono text-text-light ml-1">(NIP. {{ $pengajuan->pembimbing->nip }})</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Alasan Pengajuan -->
                        <div class="space-y-2">
                            <p class="text-xs font-bold uppercase tracking-wider text-text-light">Alasan Pengajuan Libur</p>
                            <div class="p-4 bg-background border border-border rounded-xl text-sm text-text leading-relaxed whitespace-pre-line">
                                {{ $pengajuan->alasan }}
                            </div>
                        </div>

                        <!-- Catatan / Respons dari Pembimbing ASN -->
                        @if ($pengajuan->status !== 'pending')
                            <div class="p-4 rounded-xl border text-xs space-y-1 {{ $pengajuan->status === 'disetujui' ? 'bg-success/5 border-success/20' : 'bg-danger/5 border-danger/20' }}">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                                    <p class="font-bold {{ $pengajuan->status === 'disetujui' ? 'text-success-dark' : 'text-danger' }}">
                                        {{ $pengajuan->status === 'disetujui' ? 'Catatan Persetujuan ASN:' : 'Alasan Penolakan ASN:' }}
                                    </p>
                                    @if ($pengajuan->direspon_at)
                                        <span class="text-text-light">
                                            Direspons {{ $pengajuan->direspon_at->translatedFormat('d M Y, H:i') }} WIB
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-text">
                                    {{ $pengajuan->catatan_asn ?: 'Tidak ada catatan tambahan.' }}
                                </p>
                            </div>
                        @endif

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
                    </div>

                </section>
            </section>
        </section>
    </main>
@endsection
