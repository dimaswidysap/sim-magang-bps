@extends('layouts.app')

@section('content')
    <main class="font-montserrat w-full h-screen relative overflow-hidden bg-background md:bg-surface">

        {{-- Container Background Image --}}
        <section class="absolute inset-0 w-full h-full">
            @include('components.BuldingBg')
        </section>

        <!-- Card / Sidebar -->
        <section
            class="absolute z-10 flex flex-col justify-center overflow-y-auto transition-all duration-300
            top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2
            w-[90%] h-[90%] max-w-md rounded-3xl bg-black/40 backdrop-blur-md shadow-2xl
            md:top-0 md:right-0 md:left-auto md:translate-x-0 md:translate-y-0
            md:h-screen md:w-[50%] lg:w-[35%] md:max-w-none md:rounded-none
            md:bg-background md:backdrop-blur-none md:shadow-[-4px_0_24px_rgba(0,0,0,0.05)] md:border-l md:border-border">

            <div class="w-full max-w-md mx-auto p-8 text-[80%] md:text-[100%]">
                <!-- Header -->
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-white md:text-text">Lupa Password</h2>
                    <p class="mt-2 text-white/80 md:text-text-light text-sm">
                        Masukkan email akun Anda (Admin, ASN, atau Mahasiswa) untuk menerima tautan reset password.
                    </p>
                </div>

                {{-- Alert Sukses --}}
                @if (session('success'))
                    <div class="mb-5 rounded-xl border border-success/30 bg-success/15 px-4 py-3 text-xs sm:text-sm font-medium text-white md:text-success-dark">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Alert Error Sistem --}}
                @if (session('error'))
                    <div class="mb-5 rounded-xl border border-danger/30 bg-danger/15 px-4 py-3 text-xs sm:text-sm font-medium text-white md:text-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Form Kirim Link Reset -->
                <form class="space-y-6" method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-white md:text-text">
                            Email Terdaftar
                        </label>

                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            placeholder="Contoh: admin1@gmail.com"
                            class="w-full rounded-xl border border-border bg-background px-4 py-3 text-text placeholder:text-text-light outline-none transition duration-300 focus:border-primary focus:ring-4 focus:ring-primary/20" />
                    </div>

                    {{-- Pesan Error Validasi --}}
                    @error('email')
                        <div class="w-full py-1">
                            <span class="text-danger text-sm font-bold">{{ $message }}</span>
                        </div>
                    @enderror

                    <!-- Tombol Kirim -->
                    <x-main-button
                        class="bg-primary w-full justify-center hover:bg-primary-dark text-xs px-4 py-3 rounded-xl text-white transition-colors shadow-sm inline-flex items-center gap-2"
                        type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <rect width="20" height="16" x="2" y="4" rx="2" />
                            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
                        </svg>
                        <span class="font-semibold text-sm">KIRIM TAUTAN RESET</span>
                    </x-main-button>

                    <!-- Kembali ke Login -->
                    <div class="text-center pt-2">
                        <a href="{{ route('login-form') }}"
                            class="font-medium text-white md:text-primary hover:text-white/80 md:hover:text-primary-dark transition-colors text-sm">
                            Kembali ke Halaman Login
                        </a>
                    </div>
                </form>
            </div>
        </section>
    </main>

    @include('components.Footer')
@endsection
