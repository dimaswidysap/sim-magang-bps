@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 font-montserrat my-6 sm:my-10">
        <!-- Header Section with Button on Top -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-text">Langkah 2: Kolom Terdeteksi</h1>
                <p class="text-xs sm:text-sm text-text-light mt-1">Berikut kolom yang terbaca dari file Excel Anda:</p>
            </div>

            <!-- Tombol Aksi (Di Atas) -->

            <x-buttonv2 href="{{ route('generate.form') }}" color="primary" class="w-full sm:w-auto">
                Kembali
            </x-buttonv2>
            <x-buttonv2 href="{{ route('generate.headerForm') }}" color="accent-dark" class="w-full sm:w-auto">
                <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
                Lanjut ke Pengaturan Header
            </x-buttonv2>
        </div>

        <!-- Card Content Container -->
        <div class="bg-surface border border-border rounded-xl sm:rounded-2xl p-4 sm:p-6 md:p-8 shadow-xs">
            <!-- List Kolom (Grid 1 Kolom di HP, 2 Kolom di Tablet/Desktop) -->
            <ol class="grid grid-cols-1 sm:grid-cols-2 gap-3 list-none p-0 m-0">
                @foreach ($headers as $header)
                    <li
                        class="flex items-center gap-3 p-3 bg-background border border-border rounded-xl text-xs sm:text-sm text-text font-medium">
                        <span
                            class="flex items-center justify-center w-6 h-6 rounded-lg bg-primary-light/20 text-primary font-bold text-xs shrink-0">
                            {{ $loop->iteration }}
                        </span>
                        <span class="truncate">{{ $header }}</span>
                    </li>
                @endforeach
            </ol>
        </div>
    </div>
@endsection
