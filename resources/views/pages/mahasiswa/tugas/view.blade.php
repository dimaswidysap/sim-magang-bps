@extends('layouts.app')

@section('content')
    <main class="w-full p-4 md:p-8 bg-background min-h-screen font-montserrat">
        <section class="container-dalam max-w-4xl mx-auto">

            <!-- Alert Sukses (Jika berhasil) -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-success/10 border border-success rounded-lg flex items-center gap-3 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-success shrink-0" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm font-medium text-success">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Alert Error (Jika gagal/ada peringatan) -->
            @if (session('error'))
                <div class="mb-6 p-4 bg-danger/10 border border-danger rounded-lg flex items-center gap-3 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-danger shrink-0" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm font-medium text-danger">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-surface rounded-[10px] shadow-sm border border-border overflow-hidden">

                <!-- Header Card Detail -->
                <div class="p-6 md:p-8 border-b border-border bg-background">
                    <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-2">
                        <h1 class="text-2xl font-bold text-text leading-snug">
                            {{ $detailTugas->judul }}
                        </h1>

                        <!-- Status Badge -->
                        <div class="shrink-0 mt-1 md:mt-0">
                            @if (strtolower($detailTugas->status) === 'tersedia')
                                <span
                                    class="inline-flex px-3 py-1 bg-success/10 text-success text-xs font-bold rounded-full uppercase tracking-wider border border-success/20">
                                    {{ $detailTugas->status }}
                                </span>
                            @else
                                <span
                                    class="inline-flex px-3 py-1 bg-warning/10 text-warning text-xs font-bold rounded-full uppercase tracking-wider border border-warning/20">
                                    {{ $detailTugas->status ?? 'Status Tidak Diketahui' }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <p class="text-sm text-text-light flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Dibuat pada: {{ \Carbon\Carbon::parse($detailTugas->created_at)->translatedFormat('d F Y, H:i') }}
                        WIB
                    </p>
                </div>

                <!-- Body Card Detail -->
                <div class="p-6 md:p-8 space-y-8">

                    <!-- Info Grid (Deadline & Pembuat) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-5 bg-background border border-border rounded-xl">
                        <!-- Deadline -->
                        <div>
                            <p class="text-xs font-semibold text-text-light uppercase tracking-wider mb-1.5">Tenggat Waktu
                                (Deadline)</p>
                            <p class="text-base font-bold text-danger flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ \Carbon\Carbon::parse($detailTugas->deadline)->translatedFormat('l, d F Y - H:i') }} WIB
                            </p>
                        </div>

                        <!-- Pembuat (ASN) -->
                        <div>
                            <p class="text-xs font-semibold text-text-light uppercase tracking-wider mb-1.5">Pemberi Tugas
                                (ASN)</p>
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-full bg-primary-light/20 text-primary-dark flex items-center justify-center font-bold text-sm shrink-0 border border-primary/20">
                                    {{ strtoupper(substr($detailTugas->asn->name ?? 'A', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-text">{{ $detailTugas->asn->name ?? '-' }}</p>
                                    <p class="text-xs text-text-light flex items-center gap-1 mt-0.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        {{ $detailTugas->asn->phone ?? '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Deskripsi Tugas -->
                    <div>
                        <h2 class="text-sm font-semibold text-text-light uppercase tracking-wider mb-3">Deskripsi / Detail
                            Pekerjaan</h2>

                        <div class="text-sm text-text leading-relaxed bg-surface border border-border p-5 rounded-xl ">
                            {{ $detailTugas->deskripsi }}</div>
                    </div>

                </div>

                <!-- Footer Card / Aksi -->
                <div
                    class="p-6 md:px-8 md:py-6 bg-background border-t border-border flex flex-col sm:flex-row justify-between items-center gap-4">

                    <!-- Tombol Kembali -->
                    <x-main-button href="{{ route('tugas') }}"
                        class="w-full sm:w-auto bg-surface text-text border border-border hover:bg-background text-xs px-4 py-2 rounded-lg transition-colors shadow-sm inline-flex justify-center items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Kembali</span>
                    </x-main-button>

                    @if ($detailTugas->status === 'tersedia')
                        <form method="POST" action="{{ route('mahasiswa-tugas-ambil', $detailTugas->id) }}">
                            @csrf
                            <x-main-button type="submit"
                                class="w-full sm:w-auto bg-primary hover:bg-primary-dark text-xs px-4 py-2 rounded-lg text-white transition-colors shadow-sm inline-flex justify-center items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Ambil Tugas Ini</span>
                            </x-main-button>
                        </form>
                    @endif

                </div>
            </div>
        </section>
    </main>
@endsection
