@extends('layouts.app')

@section('content')
    <main class="w-full p-4 md:p-8 bg-background min-h-screen font-montserrat">
        <section class="container-dalam max-w-5xl mx-auto bg-surface rounded-2xl shadow-sm border border-border p-6 md:p-10">

            <!-- Header Halaman -->
            <div class="mb-8 pb-4 border-b border-border">
                <h1 class="text-2xl font-bold text-text">Form Data Mahasiswa Magang</h1>
                <p class="text-sm text-text-light mt-1">Lengkapi informasi akun, data diri, dan periode magang di bawah ini.
                </p>
            </div>

            <!-- Alert Error -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-surface border border-danger rounded-lg">
                    <div class="flex items-center gap-2 text-danger font-semibold mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Terdapat kesalahan pada input Anda:
                    </div>
                    <ul class="list-disc list-inside text-sm text-danger ml-2 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.mahasiswa.store') }}" class="space-y-8"
                data-confirm="Apakah anda yakin ingin menambahkan data?">
                @csrf

                <!-- SECTION 1: Data Akun -->
                <div>
                    <h2 class="text-lg font-semibold text-primary mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Data Akun
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-background p-5 rounded-xl border border-border">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-text-light mb-1.5">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-light mb-1.5">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-light mb-1.5">Password</label>
                            <input type="password" name="password"
                                class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                        </div>
                    </div>
                </div>

                <hr class="border-border">

                <!-- Footer Buttons -->
                <div class="flex flex-col sm:flex-row justify-end items-center gap-4 pt-2">



                    <x-buttonv2 href="{{ route('admin-mahasiswa') }}" color="primary" class="w-full sm:w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="3" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7 7-7" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12h18" />
                        </svg>
                        kembali
                    </x-buttonv2>




                    <x-buttonv2 type="submit" color="accent-dark" class="w-full sm:w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Data
                    </x-buttonv2>
                </div>

            </form>
        </section>
    </main>
@endsection
