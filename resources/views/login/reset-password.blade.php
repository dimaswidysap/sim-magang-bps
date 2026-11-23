@extends('layouts.app')

@section('content')
    <main class="font-montserrat w-full h-screen relative overflow-hidden bg-background md:bg-surface">

        <section class="absolute inset-0 w-full h-full">
            @include('components.BuldingBg')
        </section>

        <section
            class="absolute z-10 flex flex-col justify-center overflow-y-auto transition-all duration-300
            top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2
            w-[90%] h-[90%] max-w-md rounded-3xl bg-black/40 backdrop-blur-md shadow-2xl
            md:top-0 md:right-0 md:left-auto md:translate-x-0 md:translate-y-0
            md:h-screen md:w-[50%] lg:w-[35%] md:max-w-none md:rounded-none
            md:bg-background md:backdrop-blur-none md:shadow-[-4px_0_24px_rgba(0,0,0,0.05)] md:border-l md:border-border">

            <div class="w-full max-w-md mx-auto p-8 text-[80%] md:text-[100%]">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-white md:text-text">Password Baru</h2>
                    <p class="mt-2 text-white/80 md:text-text-light">Masukkan password baru untuk akun
                        <span class="font-semibold break-all">{{ $email }}</span>
                    </p>
                </div>

                <form class="space-y-6" method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ old('email', $email) }}">

                    <!-- Password baru -->
                    <div>
                        <label for="new-password" class="mb-2 block text-sm font-semibold text-white md:text-text">Password Baru</label>
                        <div class="relative">
                            <input id="new-password" type="password" name="password" placeholder="Minimal 8 karakter" required
                                autocomplete="new-password"
                                class="w-full rounded-xl border border-border bg-background px-4 py-3 pr-12 text-text placeholder:text-text-light outline-none transition duration-300 focus:border-primary focus:ring-4 focus:ring-primary/20" />

                            <button type="button" onclick="togglePassword('new-password', this)"
                                aria-label="Tampilkan atau sembunyikan password baru"
                                class="absolute inset-y-0 right-0 flex items-center px-3.5 text-text-light hover:text-text transition-colors cursor-pointer">
                                <svg class="icon-eye h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
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

                    <!-- Konfirmasi password -->
                    <div>
                        <label for="confirm-password" class="mb-2 block text-sm font-semibold text-white md:text-text">Konfirmasi Password</label>
                        <div class="relative">
                            <input id="confirm-password" type="password" name="password_confirmation" placeholder="Ulangi password baru" required
                                autocomplete="new-password"
                                class="w-full rounded-xl border border-border bg-background px-4 py-3 pr-12 text-text placeholder:text-text-light outline-none transition duration-300 focus:border-primary focus:ring-4 focus:ring-primary/20" />

                            <button type="button" onclick="togglePassword('confirm-password', this)"
                                aria-label="Tampilkan atau sembunyikan konfirmasi password"
                                class="absolute inset-y-0 right-0 flex items-center px-3.5 text-text-light hover:text-text transition-colors cursor-pointer">
                                <svg class="icon-eye h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
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
                    @foreach (['password', 'email', 'token'] as $field)
                        @error($field)
                            <div class="w-full py-1">
                                <span class="text-danger text-sm font-bold">{{ $message }}</span>
                            </div>
                        @enderror
                    @endforeach

                    <x-main-button
                        class="bg-primary w-full justify-center hover:bg-primary-dark text-xs px-4 py-3 rounded-xl text-white transition-colors shadow-sm inline-flex items-center gap-2"
                        type="submit">
                        <span class="font-semibold text-sm">SIMPAN PASSWORD BARU</span>
                    </x-main-button>

                    <div class="text-center pt-2">
                        <a href="{{ route('login-form') }}"
                            class="font-medium text-white md:text-primary hover:text-white/80 md:hover:text-primary-dark transition-colors text-sm">
                            Batal, kembali ke Login
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
