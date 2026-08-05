@extends('layouts.app')

@section('content')
    <main class="font-montserrat w-full h-screen relative overflow-hidden bg-background md:bg-surface">

        {{-- Container Background Image --}}
        {{-- Dibuat absolute inset-0 agar memenuhi seluruh layar di belakang form --}}
        <section class="absolute inset-0 w-full h-full">
            @include('components.BuldingBg')
        </section>

        <!-- Login Card / Sidebar -->
        <section
            class="absolute z-10 flex flex-col justify-center overflow-y-auto transition-all duration-300
            /* Mobile: Melayang di tengah, efek Glassmorphism */
            top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2
            w-[90%] h-[90%] max-w-md rounded-3xl bg-black/40 backdrop-blur-md shadow-2xl

            /* Tablet & Desktop (md ke atas): Mepet KANAN, tinggi 100%, solid background */
            md:top-0 md:right-0 md:left-auto md:translate-x-0 md:translate-y-0
            md:h-screen md:w-[50%] lg:w-[35%] md:max-w-none md:rounded-none
            md:bg-background md:backdrop-blur-none md:shadow-[-4px_0_24px_rgba(0,0,0,0.05)] md:border-l md:border-border">

            <div class="w-full max-w-md mx-auto p-8 text-[80%] md:text-[100%]">
                <!-- Header -->
                <div class="text-center mb-8">
                    <!-- Teks putih di mobile, mengikuti text normal di desktop -->
                    <h2 class="text-3xl font-bold text-white md:text-text">Selamat Datang</h2>
                    <p class="mt-2 text-white/80 md:text-text-light">Login ke Portal SIM Magang BPS</p>
                </div>

                <!-- Form -->
                <form class="space-y-6" method="POST" action="{{ route('login.post') }}">
                    @csrf

                    <!-- Email -->
                    <div>
                        <!-- Teks putih di mobile, mengikuti text normal di desktop -->
                        <label class="mb-2 block text-sm font-semibold text-white md:text-text">
                            Email
                        </label>

                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Masukkan Email"
                            class="w-full rounded-xl border border-border bg-background px-4 py-3 text-text placeholder:text-text-light outline-none transition duration-300 focus:border-primary focus:ring-4 focus:ring-primary/20" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-white md:text-text">
                            Password
                        </label>

                        <input type="password" name="password" placeholder="Masukkan Password"
                            class="w-full rounded-xl border border-border bg-background px-4 py-3 text-text placeholder:text-text-light outline-none transition duration-300 focus:border-primary focus:ring-4 focus:ring-primary/20" />
                    </div>

                    {{-- Pesan Error --}}
                    @error('email')
                        <div class="w-full py-2">
                            <span class="text-danger text-sm font-bold">{{ $message }}</span>
                        </div>
                    @enderror

                    <!-- Tombol Login -->
                    <x-main-button
                        class="bg-primary w-full justify-center hover:bg-primary-dark text-xs px-4 py-3 rounded-xl text-white transition-colors shadow-sm inline-flex items-center gap-2"
                        type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                            <polyline points="10 17 15 12 10 7" />
                            <line x1="15" y1="12" x2="3" y2="12" />
                        </svg>
                        <span class="font-semibold text-sm">MASUK</span>
                    </x-main-button>

                    <!-- Divider -->
                    <div class="relative flex items-center pt-2">
                        <div class="flex-1 border-t border-white/20 md:border-border"></div>
                        <span class="mx-4 text-xs font-semibold text-white/60 md:text-text-light"> ATAU </span>
                        <div class="flex-1 border-t border-white/20 md:border-border"></div>
                    </div>

                    <!-- Back -->
                    <div class="text-center pt-2">
                        <a href="{{ route('landing-page') }}"
                            class="font-medium text-white md:text-primary hover:text-white/80 md:hover:text-primary-dark transition-colors text-sm">
                            Kembali ke Beranda
                        </a>
                    </div>
                </form>
            </div>
        </section>
    </main>

    @include('components.Footer')
@endsection
