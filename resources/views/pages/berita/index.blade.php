@extends('layouts.app')

@section('content')
    <main class="w-full p-4 md:p-8 bg-background min-h-screen font-montserrat">
        <section class="max-w-6xl mx-auto space-y-6">

            <!-- Header Halaman -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-border">
                <div>
                    <h1 class="text-2xl font-bold text-text leading-snug">Berita & Pengumuman</h1>
                    <p class="text-sm text-text-light mt-1">Informasi dan pembaruan terbaru seputar kegiatan magang.</p>
                </div>

                <!-- Grup Tombol Aksi -->
                <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">

                    @php
                        $rutePulang = match (auth()->user()->role) {
                            'admin' => 'admin-index',
                            'asn' => 'asn-index',
                            'mahasiswa' => 'mahasiswa-index',
                            default => 'landing-page', // jaga-jaga kalau role tidak dikenali
                        };
                    @endphp

                    <!-- Tombol Kembali -->
                    <x-buttonv2 href="{{ route($rutePulang) }}" color="secondary" class="w-full sm:w-auto">
                        <x-slot name="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </x-slot>
                        Kembali
                    </x-buttonv2>

                    <!-- Tombol Tulis Berita (Hanya Admin/ASN) -->
                    @if (in_array(auth()->user()->role, ['admin', 'asn']))
                        <x-buttonv2 href="{{ route('berita-create') }}" color="accent-dark" class="w-full sm:w-auto">
                            <x-slot name="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </x-slot>
                            Tulis Berita
                        </x-buttonv2>
                    @endif

                </div>
            </div>

            <!-- Alert Sukses -->
            @if (session('success'))
                <div class="p-4 bg-success/10 border border-success rounded-lg flex items-center gap-3 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-success shrink-0" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm font-medium text-success">{{ session('success') }}</span>
                </div>
            @endif

            <!-- List Berita -->
            @if ($daftarBerita->isEmpty())
                <!-- Tampilan Jika Berita Kosong -->
                <div class="py-16 bg-surface border border-border rounded-2xl text-center shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 mx-auto text-border mb-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                    <h3 class="text-lg font-bold text-text">Belum Ada Berita</h3>
                    <p class="text-sm text-text-light mt-1">Belum ada pengumuman atau berita yang dipublikasikan saat ini.
                    </p>
                </div>
            @else
                <!-- Grid Card Berita -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($daftarBerita as $berita)
                        <div
                            class="bg-surface border border-border rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 flex flex-col group overflow-hidden">

                            <!-- Body Card -->
                            <div class="p-5 md:p-6 flex-1 space-y-4">
                                <!-- Meta Info -->
                                <div
                                    class="flex items-start justify-between gap-3 text-[10px] font-semibold uppercase tracking-wider">
                                    <span class="text-text-light flex items-center gap-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ \Carbon\Carbon::parse($berita->created_at)->translatedFormat('d M Y, H:i') }}
                                    </span>
                                    <span
                                        class="text-primary flex items-center gap-1.5 bg-primary/10 px-2 py-0.5 rounded-md border border-primary/20">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        {{ $berita->user->name }}
                                    </span>
                                </div>

                                <!-- Judul Berita -->
                                <a href="{{ route('berita-show', $berita->id) }}" class="block">
                                    <h2
                                        class="text-lg font-bold text-text leading-snug line-clamp-2 group-hover:text-primary transition-colors">
                                        {{ $berita->judul }}
                                    </h2>
                                </a>
                            </div>

                            <!-- Footer Card (Aksi) -->
                            <div class="p-4 bg-background border-t border-border flex items-center justify-between gap-3">
                                <a href="{{ route('berita-show', $berita->id) }}"
                                    class="text-xs font-semibold text-primary hover:text-primary-dark transition-colors inline-flex items-center gap-1">
                                    Baca Berita
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>

                                @if ($berita->user_id === auth()->id())
                                    <div class="flex items-center gap-2">
                                        <!-- Tombol Edit -->
                                        <a href="{{ route('berita-edit', $berita->id) }}" title="Edit Berita"
                                            class="p-1.5 text-text-light hover:text-primary transition-colors bg-surface border border-border rounded-lg hover:border-primary shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        <!-- Tombol Hapus -->
                                        <form method="POST" action="{{ route('berita-destroy', $berita->id) }}"
                                            class="m-0" data-confirm="Yakin ingin menghapus berita ini secara permanen?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Hapus Berita"
                                                class="p-1.5 text-text-light hover:text-white transition-colors bg-surface border border-border rounded-lg hover:bg-danger hover:border-danger shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
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
