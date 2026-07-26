@extends('layouts.app')

@section('content')
@include('components.HeaderHome')
<main class="w-full h-[110vh] relative overflow-hidden">
    {{-- container background image --}}
    <section class="w-full aspect-video">
        @include('components.BuldingBg')
    </section>
    {{-- content --}}
    <section
            class="absolute inset-0 z-10 flex h-full items-center justify-center md:justify-end px-6 md:px-12 lg:px-20"
        >
            <div
                class="w-full max-w-md rounded-3xl bg-black/20 backdrop-blur-md p-8 md:p-10"
            >
                <h1
                    class="font-montserrat font-black text-center md:text-left text-shadow"
                >
                    <span
                        class="block text-sm md:text-base uppercase tracking-[0.35em] text-gray-200"
                    >
                        Selamat Datang di
                    </span>

                    <span
                        class="mt-3 block text-4xl md:text-5xl lg:text-6xl text-white leading-tight"
                    >
                        SIM MAGANG
                    </span>

                    <span
                        class="block text-4xl md:text-5xl lg:text-6xl text-accent leading-tight"
                    >
                        BPS
                    </span>
                </h1>

                <p
                    class="mt-6 text-center md:text-left text-sm font-montserrat text-gray-200 leading-7"
                >
                    Sistem Informasi Manajemen Magang Badan Pusat Statistik yang
                    dirancang untuk memudahkan proses administrasi, monitoring,
                    serta pengelolaan kegiatan magang secara efektif, efisien,
                    dan terintegrasi.
                </p>

                <div class="mt-8 flex justify-center md:justify-start">
                   <a href="{{ route('login-form') }}" class="rounded-xl bg-primary px-8 py-3 font-montserrat text-white font-semibold transition duration-300 hover:scale-105 hover:shadow-lg">Login</a>
                </div>
            </div>
        </section>
</main>

@include('components.Footer')
@endsection
