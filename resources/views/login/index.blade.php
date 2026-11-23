@extends('layouts.app')

@section('content')
    <main class="font-montserrat w-full h-screen relative overflow-hidden bg-background md:bg-surface">

        {{-- Container Background Image --}}
        <section class="absolute inset-0 w-full h-full">
            @include('components.BuldingBg')
        </section>

        <!-- Login Card / Sidebar -->
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
                    <h2 class="text-3xl font-bold text-white md:text-text">Selamat Datang</h2>
                    <p class="mt-2 text-white/80 md:text-text-light">Login ke Portal SIM Magang BPS</p>
                </div>

                {{-- Notifikasi Sukses (Misal setelah berhasil reset password) --}}
                @if (session('success'))
                    <div class="mb-5 rounded-xl border border-success/30 bg-success/15 px-4 py-3 text-xs sm:text-sm font-medium text-white md:text-success-dark">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Notifikasi Error (Misal tautan reset kedaluwarsa) --}}
                @if (session('error'))
                    <div class="mb-5 rounded-xl border border-danger/30 bg-danger/15 px-4 py-3 text-xs sm:text-sm font-medium text-white md:text-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Form -->
                <form class="space-y-6" method="POST" action="{{ route('login.post') }}">
                    @csrf

                    <!-- Email atau Nama -->
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-white md:text-text">
                            Email atau Nama
                        </label>

                        <input type="text" name="login" value="{{ old('login') }}" autocomplete="username"
                            placeholder="Masukkan Email atau Nama"
                            class="w-full rounded-xl border border-border bg-background px-4 py-3 text-text placeholder:text-text-light outline-none transition duration-300 focus:border-primary focus:ring-4 focus:ring-primary/20" />
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <label for="login-password" class="block text-sm font-semibold text-white md:text-text">
                                Password
                            </label>
                            <a href="{{ route('password.request') }}"
                                class="text-xs font-semibold text-white/90 md:text-primary hover:underline transition-colors">
                                Lupa Password?
                            </a>
                        </div>

                        <div class="relative">
                            <input id="login-password" type="password" name="password" autocomplete="current-password"
                                placeholder="Masukkan Password"
                                class="w-full rounded-xl border border-border bg-background px-4 py-3 pr-12 text-text placeholder:text-text-light outline-none transition duration-300 focus:border-primary focus:ring-4 focus:ring-primary/20" />

                            <button type="button" onclick="togglePassword('login-password', this)"
                                aria-label="Tampilkan atau sembunyikan password"
                                class="absolute inset-y-0 right-0 flex items-center px-3.5 text-text-light hover:text-text transition-colors cursor-pointer">
                                {{-- Ikon Mata Terbuka (Show) --}}
                                <svg class="icon-eye h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                {{-- Ikon Mata Tercoret (Hide) --}}
                                <svg class="icon-eye-off hidden h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49" />
                                    <path d="M14.084 14.158a3 3 0 0 1-4.242-4.242" />
                                    <path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143" />
                                    <path d="m2 2 20 20" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Pesan Error --}}
                    @error('login')
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

    <script>
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const iconEye = button.querySelector('.icon-eye');
            const iconEyeOff = button.querySelector('.icon-eye-off');

            if (input.type === 'password') {
                input.type = 'text';
                iconEye.classList.add('hidden');
                iconEyeOff.classList.remove('hidden');
            } else {
                input.type = 'password';
                iconEye.classList.remove('hidden');
                iconEyeOff.classList.add('hidden');
            }
        }
    </script>
@endsection
