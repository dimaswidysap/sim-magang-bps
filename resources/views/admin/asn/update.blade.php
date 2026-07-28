@extends('layouts.app')

@section('content')
    <main class="w-full p-4 md:p-8 bg-background min-h-screen font-montserrat">
        <section class="container-dalam max-w-5xl mx-auto bg-surface rounded-2xl shadow-sm border border-border p-6 md:p-10">

            <!-- Header Halaman -->
            <div class="mb-8 pb-4 border-b border-border flex justify-between items-end">
                <div>
                    <h1 class="text-2xl font-bold text-text">Edit Data Pegawai ASN</h1>
                    <p class="text-sm text-text-light mt-1">Perbarui informasi akun, kontak, dan data kedinasan pegawai.</p>
                </div>
                <!-- Badge Status Saat Ini -->
                <div>
                    <span
                        class="px-4 py-1.5 bg-background border border-border text-text-light text-sm font-semibold rounded-full">
                        ID: {{ $dataAsn->id }}
                    </span>
                </div>
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

            <form method="POST" action="{{ route('admin-asn-update', $dataAsn->id) }}" class="space-y-8">
                @csrf
                @method('PUT')

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
                            <input type="text" name="name" value="{{ old('name', $dataAsn->name) }}"
                                class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-text-light mb-1.5">Email</label>
                            <input type="email" name="email" value="{{ old('email', $dataAsn->email) }}"
                                class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-text-light mb-1.5">No. HP</label>
                            <input type="text" name="phone" value="{{ old('phone', $dataAsn->phone) }}"
                                placeholder="Contoh: 08123456789"
                                class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-text-light mb-1.5">Password Baru</label>
                            <input type="password" name="password" placeholder="••••••••"
                                class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                            <p class="text-xs text-text-light mt-1.5 italic">* Kosongkan jika tidak ingin mengubah password.
                            </p>
                        </div>

                        <div class="flex items-center">
                            <label
                                class="flex items-center justify-between p-4 bg-surface border border-border rounded-lg cursor-pointer hover:border-primary transition-colors w-full h-full mt-6 md:mt-0 group">

                                <!-- Teks Keterangan -->
                                <div>
                                    <span
                                        class="block text-sm font-medium text-text group-hover:text-primary transition-colors">Akun
                                        Aktif</span>
                                    <span class="block text-xs text-text-light mt-0.5">Berikan akses masuk ke dalam
                                        sistem</span>
                                </div>

                                <!-- Komponen Lever/Toggle -->
                                <div class="relative flex items-center">
                                    <!-- Checkbox Asli (Disembunyikan dengan sr-only) -->
                                    <input type="checkbox" name="is_active" value="1"
                                        {{ old('is_active', $dataAsn->is_active) ? 'checked' : '' }} class="sr-only peer">

                                    <!-- Background Toggle (Berubah warna saat checked) -->
                                    <div
                                        class="w-12 h-6 bg-text-light/50 rounded-full peer peer-checked:bg-primary transition-colors duration-300">
                                    </div>

                                    <!-- Lingkaran Sakelar (Bergeser ke kanan saat checked) -->
                                    <div
                                        class="absolute left-1 top-1 w-4 h-4 bg-surface rounded-full transition-transform duration-300 peer-checked:translate-x-6 shadow-sm">
                                    </div>
                                </div>

                            </label>
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
                            <input type="text" name="nip" value="{{ old('nip', $dataAsn->asnProfile->nip ?? '') }}"
                                class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors font-mono">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-light mb-1.5">Jabatan</label>
                            <input type="text" name="jabatan"
                                value="{{ old('jabatan', $dataAsn->asnProfile->jabatan ?? '') }}"
                                class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-light mb-1.5">Unit Kerja</label>
                            <input type="text" name="unit_kerja"
                                value="{{ old('unit_kerja', $dataAsn->asnProfile->unit_kerja ?? '') }}"
                                class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                        </div>
                    </div>
                </div>

                <hr class="border-border mt-8">

                <!-- Footer Buttons -->
                <div class="flex flex-col sm:flex-row justify-end items-center gap-4 pt-4">
                    <!-- Tombol Kembali -->




                    <x-main-button
                        class=" text-xs px-4 py-2 rounded-lg text-text border-text border transition-colors shadow-sm inline-flex items-center gap-2"
                        href="{{ route('admin-asn') }}">
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Batal</span>
                    </x-main-button>
                    <x-main-button
                        class="bg-primary hover:bg-primary-dark  text-xs px-4 py-2 rounded-lg text-white transition-colors shadow-sm inline-flex items-center gap-2"
                        type="submit">
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Simpan perubahan</span>
                    </x-main-button>
                </div>

            </form>
        </section>
    </main>
@endsection
