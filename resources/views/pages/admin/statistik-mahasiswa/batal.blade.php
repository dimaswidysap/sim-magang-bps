@extends('layouts.app')

@section('content')
    <section class="w-full p-3 md:p-6 font-montserrat">
        <div class="max-w-7xl mx-auto">



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
