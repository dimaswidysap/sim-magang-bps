@extends('layouts.app')

@section('content')

<main class="w-full p-4 md:p-8 bg-background min-h-screen font-montserrat">
    <section class="max-w-5xl mx-auto space-y-6">

        @php
            $avatarPath = $mahasiswa->foto_profil_path
                ?? $mahasiswa->foto
                ?? $mahasiswa->avatar
                ?? $mahasiswa->user?->foto_profil_path
                ?? $mahasiswa->user?->avatar
                ?? $mahasiswa->user?->foto;

            $avatarUrl = null;
            if ($avatarPath) {
                $avatarUrl = filter_var($avatarPath, FILTER_VALIDATE_URL)
                    ? $avatarPath
                    : \Illuminate\Support\Facades\Storage::url($avatarPath);
            }

            $jumlahMenungguValidasi = $logbookMenungguValidasi->count();
        @endphp

        <!-- Flash Alert -->
        @if (session('success'))
            <div class="p-4 bg-surface border border-success/40 text-success rounded-xl text-sm font-semibold flex items-center gap-2.5 shadow-xs">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="p-4 bg-surface border border-danger/40 text-danger rounded-xl text-sm font-semibold flex items-center gap-2.5 shadow-xs">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Header Halaman + Foto Profil + Tombol Aksi -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-border">
            <div class="flex items-center gap-4">

                <!-- Avatar / Foto Profil Mahasiswa -->
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-primary/10 text-primary-dark flex items-center justify-center text-xl sm:text-2xl font-bold shrink-0 border-2 border-primary/20 overflow-hidden shadow-sm">
                    @if ($avatarUrl)
                        <img src="{{ $avatarUrl }}"
                             alt="{{ $mahasiswa->user?->name ?? 'Mahasiswa' }}"
                             class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr($mahasiswa->user?->name ?? 'M', 0, 1)) }}
                    @endif
                </div>

                <!-- Informasi Mahasiswa -->
                <div>
                    <h1 class="text-2xl font-bold text-text leading-snug">Logbook Mahasiswa</h1>
                    <p class="text-sm font-semibold text-primary mt-1 flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        {{ $mahasiswa->user?->name ?? '-' }}
                        -
                        {{ $mahasiswa->jurusan ?? '-' }}
                    </p>
                    <div class="mt-2">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-success/10 text-success border border-success/25">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Total Hari Masuk: {{ $jumlahHariMasuk ?? 0 }} Hari
                        </span>
                    </div>
                </div>

            </div>

            <!-- Tombol Validasi Logbook & Kembali -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <button type="button" id="btn-open-validasi-logbook"
                    class="relative inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition-all shadow-sm cursor-pointer border {{ $jumlahMenungguValidasi > 0 ? 'bg-surface border-danger/50 text-text hover:bg-danger/5' : 'bg-surface border-border text-text hover:border-primary' }}">

                    @if ($jumlahMenungguValidasi > 0)
                        <!-- Ikon Pengingat Merah -->
                        <span class="relative flex h-5 w-5 items-center justify-center">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-danger opacity-40"></span>
                            <span class="relative inline-flex items-center justify-center rounded-full h-5 w-5 bg-danger text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @endif

                    <span>Validasi Logbook</span>

                    @if ($jumlahMenungguValidasi > 0)
                        <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-danger text-white">
                            {{ $jumlahMenungguValidasi }}
                        </span>
                    @endif
                </button>

                <x-buttonv2 href="{{ route('asn-index') }}" color="accent-dark" class="w-full sm:w-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Daftar
                </x-buttonv2>
            </div>
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
            <div class="flex flex-wrap items-center gap-5 bg-surface border border-border px-4 py-3 rounded-xl w-fit shadow-sm">
                <div class="flex items-center gap-2">
                    <div class="relative w-5 h-5 bg-primary/10 border border-primary/30 rounded flex items-center justify-center">
                        <span class="absolute -top-1 -right-1 w-2 h-2 bg-danger rounded-full"></span>
                    </div>
                    <span class="text-xs font-semibold text-text">Ada Kegiatan (Tervalidasi)</span>
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
                                    <a href="{{ route('asn-logbook-mahasiswa-tanggal', [$mahasiswa->id, $tanggalFormat]) }}"
                                        class="relative w-9 h-9 flex items-center justify-center bg-primary/10 border border-primary/30 rounded-lg text-sm font-bold text-primary-dark hover:bg-primary hover:text-white hover:border-primary hover:-translate-y-0.5 transition-all shadow-sm group">
                                        {{ $tanggal }}
                                        <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-danger rounded-full border-2 border-surface group-hover:border-primary transition-colors"></span>
                                    </a>
                                @else
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

<!-- ====================================================================== -->
<!-- POPUP MODAL VALIDASI LOGBOOK MANDIRI                                   -->
<!-- ====================================================================== -->
<div id="modal-validasi-logbook"
    class="fixed inset-0 z-40 hidden items-center justify-center bg-black/50 backdrop-blur-xs p-4 font-montserrat"
    role="dialog" aria-modal="true" aria-labelledby="modal-validasi-title">

    <div class="bg-surface border border-border rounded-2xl shadow-xl w-full max-w-3xl max-h-[88vh] flex flex-col overflow-hidden">

        <!-- Header Modal -->
        <div class="px-6 py-4 border-b border-border flex items-start justify-between gap-4 bg-surface">
            <div>
                <div class="flex items-center gap-2">
                    <h3 id="modal-validasi-title" class="text-lg font-bold text-text">
                        Validasi Logbook Mandiri
                    </h3>
                    @if ($jumlahMenungguValidasi > 0)
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-danger/10 text-danger border border-danger/25">
                            {{ $jumlahMenungguValidasi }} Menunggu
                        </span>
                    @endif
                </div>
                <p class="text-xs text-text-light mt-0.5">
                    Logbook mandiri yang divalidasi akan masuk ke kalender logbook dan tidak dapat diubah lagi oleh peserta.
                </p>
            </div>

            <button type="button" data-close-validasi-modal
                class="p-1.5 rounded-lg text-text-light hover:text-text hover:bg-background transition-colors cursor-pointer"
                aria-label="Tutup popup">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Isi Daftar Logbook Menunggu Validasi -->
        <div class="p-6 overflow-y-auto flex-1 bg-background/50 space-y-4">
            @forelse ($logbookMenungguValidasi as $item)
                @php
                    $lampiranList = is_array($item->file_lampiran)
                        ? $item->file_lampiran
                        : (is_string($item->file_lampiran) ? (json_decode($item->file_lampiran, true) ?? []) : []);
                    $lampiranList = array_filter($lampiranList);
                @endphp

                <div class="bg-surface border border-border rounded-xl p-4 sm:p-5 shadow-xs space-y-3">
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                        <div class="space-y-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-xs font-bold bg-primary/10 text-primary-dark border border-primary/20">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $item->tanggal_kegiatan?->translatedFormat('d F Y') ?? '-' }}
                                </span>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-danger/10 text-danger border border-danger/20">
                                    Menunggu Validasi
                                </span>
                            </div>

                            <h4 class="text-base font-bold text-text pt-1">
                                {{ $item->judul_kegiatan }}
                            </h4>
                        </div>

                        <form method="POST" action="{{ route('asn-logbook-mandiri-validasi', $item->id) }}"
                            data-confirm="Apakah Anda yakin ingin memvalidasi logbook ini? Setelah divalidasi, peserta tidak dapat mengubah atau menghapusnya."
                            class="shrink-0">
                            @csrf
                            <button type="submit"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-primary hover:bg-primary-dark text-white text-xs font-bold rounded-lg transition-colors cursor-pointer shadow-xs">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                Validasi
                            </button>
                        </form>
                    </div>

                    <p class="text-sm text-text-light leading-relaxed whitespace-pre-line">
                        {{ $item->deskripsi_kegiatan }}
                    </p>

                    @if (!empty($lampiranList))
                        <div class="pt-2 border-t border-border/70">
                            <p class="text-xs font-semibold text-text-light mb-2">
                                Lampiran Dokumentasi ({{ count($lampiranList) }} file):
                            </p>
                            <div class="flex flex-wrap gap-2.5">
                                @foreach ($lampiranList as $file)
                                    @php
                                        $fileUrl = filter_var($file, FILTER_VALIDATE_URL)
                                            ? $file
                                            : \Illuminate\Support\Facades\Storage::url($file);
                                    @endphp
                                    <a href="{{ $fileUrl }}" target="_blank" rel="noopener noreferrer"
                                        class="group relative w-20 h-20 rounded-lg overflow-hidden border border-border hover:border-primary transition-all shadow-xs"
                                        title="Klik untuk memperbesar lampiran">
                                        <img src="{{ $fileUrl }}" alt="Lampiran Logbook"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-12 bg-surface rounded-xl border border-border">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-success mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm font-bold text-text">Semua Logbook Sudah Divalidasi</p>
                    <p class="text-xs text-text-light mt-1">Tidak ada logbook mandiri yang menunggu validasi saat ini.</p>
                </div>
            @endforelse
        </div>

        <!-- Footer Modal -->
        <div class="px-6 py-3.5 border-t border-border bg-surface flex flex-col sm:flex-row items-center justify-between gap-3">
            @if ($jumlahMenungguValidasi > 1)
                <form method="POST" action="{{ route('asn-logbook-mandiri-validasi-semua', $mahasiswa->id) }}"
                    data-confirm="Apakah Anda yakin ingin memvalidasi seluruh ({{ $jumlahMenungguValidasi }}) logbook mandiri sekaligus?"
                    class="w-full sm:w-auto">
                    @csrf
                    <button type="submit"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-success hover:opacity-90 text-white text-xs font-bold rounded-lg transition-opacity cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Validasi Semua ({{ $jumlahMenungguValidasi }})
                    </button>
                </form>
            @endif

            <button type="button" data-close-validasi-modal
                class="w-full sm:w-auto sm:ml-auto px-4 py-2 bg-background hover:bg-border/50 border border-border text-text text-xs font-semibold rounded-lg transition-colors cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('modal-validasi-logbook');
        const openBtn = document.getElementById('btn-open-validasi-logbook');
        const closeBtns = document.querySelectorAll('[data-close-validasi-modal]');

        if (!modal || !openBtn) return;

        function openModal() {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }

        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }

        openBtn.addEventListener('click', openModal);
        closeBtns.forEach((btn) => btn.addEventListener('click', closeModal));

        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                closeModal();
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });
    });
</script>
@endsection
