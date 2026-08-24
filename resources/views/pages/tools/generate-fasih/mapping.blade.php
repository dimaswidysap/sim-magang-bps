@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 font-montserrat my-6 sm:my-10">

        <!-- Header Section with Action Buttons Group -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 sm:mb-8">
            <div>
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary-light/10 border border-primary/20 text-primary text-[11px] font-bold tracking-wide mb-2">
                    <span>Langkah 02</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-text tracking-tight">Kolom Terdeteksi</h1>
                <p class="text-xs sm:text-sm text-text-light mt-1">Berikut adalah daftar kolom yang berhasil dibaca dari file
                    Excel Anda:</p>
            </div>

            <!-- Grouping Tombol Aksi -->
            <div class="flex flex-col sm:flex-row items-center gap-2.5 sm:gap-3 w-full sm:w-auto shrink-0">
                <x-buttonv2 href="{{ route('generate.form') }}" color="primary" class="w-full sm:w-auto justify-center">
                    <x-slot name="icon">
                        <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </x-slot>
                    Kembali
                </x-buttonv2>

                <x-buttonv2 href="{{ route('generate.headerForm') }}" color="accent-dark"
                    class="w-full sm:w-auto justify-center">
                    <span>Lanjut ke Header</span>
                    <x-slot name="icon">
                        <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </x-slot>
                </x-buttonv2>
            </div>
        </div>

        <!-- Card Content Container -->
        <div class="bg-surface border border-border/80 rounded-2xl p-4 sm:p-6 md:p-8 shadow-xs">
            <ol class="grid grid-cols-1 sm:grid-cols-2 gap-3 list-none p-0 m-0">
                @foreach ($headers as $header)
                    <li
                        class="flex items-center gap-3 p-3 bg-background border border-border/80 rounded-xl text-xs sm:text-sm text-text font-medium hover:border-primary/40 transition-colors">
                        <span
                            class="flex items-center justify-center w-6 h-6 rounded-lg bg-primary-light/15 border border-primary/20 text-primary font-bold text-xs shrink-0">
                            {{ $loop->iteration }}
                        </span>
                        <span class="truncate" title="{{ $header }}">{{ $header }}</span>
                    </li>
                @endforeach
            </ol>
        </div>

    </div>
@endsection
