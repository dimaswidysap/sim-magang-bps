@extends('layouts.app')

@section('content')
    <main class="w-full p-4 md:p-8 bg-background min-h-screen font-montserrat">
        <section class="container-dalam max-w-5xl mx-auto bg-surface rounded-2xl shadow-sm border border-border p-6 md:p-10">

            <!-- Header Halaman -->
            <div class="mb-8 pb-4 border-b border-border">
                <h1 class="text-2xl font-bold text-text">Tambah Data Pegawai ASN</h1>
                <p class="text-sm text-text-light mt-1">Lengkapi form di bawah ini untuk menambahkan data pegawai baru ke
                    dalam sistem.</p>
            </div>

            <!-- Alert Error -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-surface border border-danger rounded-lg shadow-sm">
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

            <form method="POST" action="{{ route('admin-asn-store') }}" class="space-y-8" data-confirm="Apakah anda yakin ingin menambah data?">
                @csrf

                <!-- SECTION 1: Data Akun & Kontak -->
                <div>
                    <h2 class="text-lg font-semibold text-primary mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Data Akun & Kontak
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-background p-5 rounded-xl border border-border">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-text-light mb-1.5">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                placeholder="Masukkan nama lengkap"
                                class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-text-light mb-1.5">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="contoh@bps.go.id"
                                class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-text-light mb-1.5">No. HP</label>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                placeholder="Contoh: 08123456789"
                                class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-text-light mb-1.5">Password</label>
                            <input type="password" name="password" placeholder="Buat password untuk akun ini"
                                class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: Data Kedinasan -->
                <div>
                    <h2 class="text-lg font-semibold text-primary mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Data Kedinasan (ASN)
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-background p-5 rounded-xl border border-border">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-text-light mb-1.5">Nomor Induk Pegawai
                                (NIP)</label>
                            <input type="text" name="nip" value="{{ old('nip') }}"
                                placeholder="Masukkan 18 digit NIP"
                                class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors font-mono tracking-wider">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-light mb-1.5">Jabatan</label>
                            <input type="text" name="jabatan" value="{{ old('jabatan') }}"
                                placeholder="Contoh: Statistisi Ahli Muda"
                                class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-light mb-1.5">Unit Kerja</label>
                            <input type="text" name="unit_kerja" value="{{ old('unit_kerja') }}"
                                placeholder="Contoh: BPS Kabupaten Madiun"
                                class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                        </div>
                    </div>
                </div>

                <hr class="border-border mt-8">

                <!-- Footer Buttons -->
                <div class="flex flex-col sm:flex-row justify-end items-center gap-4 pt-4">
                    <!-- Tombol Kembali -->


                    <x-main-button
                        class="border border-text  text-xs px-4 py-2 rounded-lg text-text transition-colors shadow-sm inline-flex items-center gap-2"
                        href="{{ route('admin-asn') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Kembali</span>
                    </x-main-button>



                    <x-main-button
                        class="bg-primary hover:bg-primary-dark  text-xs px-4 py-2 rounded-lg text-white transition-colors shadow-sm inline-flex items-center gap-2"
                        type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Simpan data</span>
                    </x-main-button>

                </div>

            </form>
        </section>
    </main>
@endsection
