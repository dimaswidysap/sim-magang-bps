@vite(['resources/js/fitur-search.js'])
@extends('layouts.app')

@section('content')

<main class=" relative w-full flex bg-background">
{{-- container-sidebar-admin --}}

@include('components.asn.asn-sidebar')

    {{-- container-content --}}
    <section class="flex flex-col flex-1 pl-60">

        {{-- header --}}
        @include('components.asn.header-asn')

        <section class="w-full p-2">
            <section class="container-dalam flex flex-col gap-4">
                @include('components.asn.statistik-tugas')
                <div class="relative w-full sm:w-72 md:w-80">
                <!-- Ikon Kaca Pembesar -->
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-text" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <input type="text" name="search" placeholder="Cari data..."
                    class="input-search w-full pl-9 pr-4 py-2 bg-surface border border-border rounded-lg text-sm text-text focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors shadow-sm">
            </div>
                @include('components.asn.tabel-logbook')
            </section>
        </section>
    </section>
</main>


@endsection
