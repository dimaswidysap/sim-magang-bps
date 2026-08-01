@extends('layouts.app')

@section('content')
    <main class="font-montserrat w-full h-screen relative overflow-hidden flex justify-center items-center">
        {{-- container background image --}}
        <section class="w-full aspect-video">
            @include('components.BuldingBg')
        </section>
        <!-- Login Card -->
        <section
            class="absolute h-[90%] text-[80%] md:text-[100%] lg:right-[5%] w-[90%] bg-black/20 backdrop-blur-md max-w-md lg:w-1/3 rounded-3xl shadow-2xl overflow-hidden"
        >
            <!-- Header -->
            <div class="px-8 py-8 text-center">
                <h2 class="text-3xl font-bold text-white">Selamat Datang</h2>

                <p class="mt-2 text-white/80">Login ke Portal SIM Magang BPS</p>
            </div>

            <!-- Form -->
            <form class="space-y-6 p-8" method="POST" action="{{ route('login.post') }}">
                @csrf

                <!-- Email -->
                <div>
                    <label class="mb-2 block text-sm font-semibold text-white">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Masukkan Email"
                        class="w-full rounded-xl border border-border bg-background px-4 py-3 text-text placeholder:text-text-light outline-none transition duration-300 focus:border-primary focus:ring-4 focus:ring-primary/20"
                    />
                </div>

                <!-- Password -->
                <div>
                    <label class="mb-2 block text-sm font-semibold text-white">
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        placeholder="Masukkan Password"
                        class="w-full rounded-xl border border-border bg-background px-4 py-3 text-text placeholder:text-text-light outline-none transition duration-300 focus:border-primary focus:ring-4 focus:ring-primary/20"
                    />
                </div>

                {{-- pesan error - otomatis muncul kalau login gagal --}}
                @error('email')
                    <div class="w-full py-2">
                        <span class="text-danger text-sm font-bold">{{ $message }}</span>
                    </div>
                @enderror

                <!-- Remember -->
                {{-- <div class="flex items-center justify-between">
                    <label
                        class="flex items-center gap-2 text-sm text-text-light"
                    >
                        <input type="checkbox" class="accent-primary w-4 h-4" />
                        Ingat Saya
                    </label>

                    <a
                        href="#"
                        class="text-sm font-medium text-white hover:underline"
                    >
                        Lupa Password?
                    </a>
                </div> --}}

                <!-- Button -->
                <button
                    type="submit"
                    class="w-full rounded-xl bg-primary py-3 font-semibold text-white shadow-lg transition duration-300 hover:scale-[1.02] hover:bg-primary-dark active:scale-[0.98]"
                >
                    Masuk
                </button>

                <!-- Divider -->
                <div class="relative flex items-center">
                    <div class="flex-1 border-t border-border"></div>

                    <span class="mx-4 text-sm text-text-light"> ATAU </span>

                    <div class="flex-1 border-t border-border"></div>
                </div>


                <!-- Back -->
                <div class="text-center">
                   <a href="{{ route('landing-page') }}" class="font-medium text-primary hover:text-primary-dark hover:underline">Kembali</a>
                </div>
            </form>
        </section>
    </main>
    @include('components.Footer')
@endsection
