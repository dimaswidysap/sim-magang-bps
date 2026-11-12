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
                <h1 class="text-xl md:text-2xl font-bold text-text">Data Magang Batal</h1>
                <p class="text-xs md:text-sm text-text-light mt-1">Daftar mahasiswa yang pengajuan magangnya dibatalkan.</p>
            </div>

          @include('pages.admin.statistik-mahasiswa.navigasi')

            <!-- Data Table Container -->
            <div class="w-full bg-surface rounded-xl shadow-sm border border-border overflow-hidden">
                <div class="overflow-x-auto">
                     @include('pages.admin.statistik-mahasiswa.tabel-component', [
                        'items' => $magangBatal,
                        'status' => 'aktif',
                        'emptyMessage' => 'Belum ada data peserta magang/PKL yang dibatalkan',
                    ])
                </div>
            </div>

        </div>
    </section>
@endsection
