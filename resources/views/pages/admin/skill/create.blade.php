@extends('layouts.app')

@section('content')
    <main class="w-full p-4 md:p-8 bg-background min-h-screen font-montserrat">
        <section class="container-dalam max-w-2xl mx-auto bg-surface rounded-2xl shadow-sm border border-border p-6 md:p-8">

            <!-- Header Halaman -->
            <div class="mb-8 pb-4 border-b border-border">
                <h1 class="text-2xl font-bold text-text">Tambah Skill Baru</h1>
                <p class="text-sm text-text-light mt-1">Tambahkan referensi keahlian baru ke dalam sistem.</p>
            </div>

            <!-- Alert Error -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-surface border border-danger rounded-lg shadow-sm">
                    <div class="flex items-center gap-2 text-danger font-semibold mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 20 20"
                            fill="currentColor">
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

            <form method="POST" action="{{ route('admin-skill-store') }}"
                data-confirm="Apakah anda yakin ingin menambah data?">
                @csrf

                <!-- Form Input -->
                <div class="bg-background p-5 rounded-xl border border-border mb-8">
                    <label class="block text-sm font-medium text-text-light mb-1.5 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Nama Skill / Keahlian
                    </label>
                    <input type="text" name="nama_skill" value="{{ old('nama_skill') }}"
                        placeholder="Contoh: Web Development, Microsoft Word, dll."
                        class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                </div>

                <hr class="border-border mb-6">

                <!-- Footer Buttons -->
                <div class="flex flex-col sm:flex-row justify-end items-center gap-4">
                    <!-- Tombol Kembali / Batal (Anda bisa sesuaikan route-nya, di sini saya pakai url()->previous() sebagai contoh) -->



                    <x-buttonv2 href="{{ route('admin-skill') }}" color="primary" class="w-full sm:w-auto">
                        Kembali
                    </x-buttonv2>




                    <x-buttonv2 type="submit" color="accent-dark" class="w-full sm:w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Tambahkan Data
                    </x-buttonv2>
                </div>
            </form>
        </section>
    </main>
@endsection
