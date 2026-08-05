@extends('layouts.app')

@section('content')
    @include('components.HeaderHome')

    <main class="font-montserrat w-full h-screen relative overflow-hidden bg-background md:bg-surface">

        {{-- Container Background Image --}}
        <section class="absolute inset-0 w-full h-full">
            @include('components.BuldingBg')
        </section>

        {{-- Content / Sidebar Right --}}
        <section
            class="absolute z-10 flex flex-col justify-center transition-all duration-300
            /* Mobile: Melayang di tengah, efek Glassmorphism */
            top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2
            w-[90%] max-w-md rounded-3xl bg-black/40 backdrop-blur-md shadow-2xl p-8

            /* Tablet & Desktop (md ke atas): Mepet Kanan, tinggi 100%, solid background */
            md:top-0 md:right-0 md:left-auto md:translate-x-0 md:translate-y-0
            md:h-screen md:w-[50%] lg:w-[35%] md:max-w-none md:rounded-none
            md:bg-background md:backdrop-blur-none md:shadow-[-4px_0_24px_rgba(0,0,0,0.05)] md:border-l md:border-border md:p-12">

            <div class="w-full max-w-md mx-auto">
                <h1 class="font-black text-center md:text-left drop-shadow-md md:drop-shadow-none">
                    <!-- Teks putih di mobile, mengikuti warna text default di desktop -->
                    <span class="block text-sm md:text-base uppercase tracking-[0.35em] text-white md:text-text">
                        Selamat Datang di
                    </span>

                    <span class="mt-3 block text-4xl md:text-5xl lg:text-6xl text-white md:text-text leading-tight">
                        SIM MAGANG
                    </span>

                    <span class="block text-4xl md:text-5xl lg:text-6xl text-accent leading-tight">
                        BPS
                    </span>
                </h1>

                <!-- Teks paragraf disesuaikan contrast-nya -->
                <p class="mt-6 text-center md:text-left text-sm text-white/80 md:text-text-light leading-relaxed">
                    Sistem Informasi Manajemen Magang Badan Pusat Statistik yang
                    dirancang untuk memudahkan proses administrasi, monitoring,
                    serta pengelolaan kegiatan magang secara efektif, efisien,
                    dan terintegrasi.
                </p>

                <div class="mt-8 flex justify-center md:justify-start">
                    <x-main-button
                        class="bg-primary hover:bg-primary-dark text-sm px-6 py-2.5 rounded-xl text-white transition-colors shadow-md inline-flex items-center gap-2 font-semibold"
                        href="{{ route('login-form') }}">
                        <span>Masuk / Login</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </x-main-button>
                </div>
            </div>
        </section>
    </main>

    @include('components.Footer')
@endsection
