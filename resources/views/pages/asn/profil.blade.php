@extends('layouts.app')
@vite(['resources/js/validasi-number.js'])
@section('content')
    <main class="w-full p-4 md:p-8 bg-background min-h-screen font-montserrat">
        <section class="container-dalam max-w-5xl mx-auto bg-surface rounded-2xl shadow-sm border border-border p-6 md:p-10">

            <!-- Header Halaman -->
            <div class="mb-8 pb-4 border-b border-border">
                <h1 class="text-2xl font-bold text-text">Profil Saya (ASN)</h1>
                <p class="text-sm text-text-light mt-1">Kelola informasi data diri, kontak, dan data kedinasan Anda.</p>
            </div>

            <!-- Alert Sukses -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-success/10 border border-success rounded-lg flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-success shrink-0" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm font-medium text-success">{{ session('success') }}</span>
                </div>
            @endif

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

            <!-- Highlight Box: Data Read-Only -->
            <div
                class="mb-8 p-5 bg-background border border-border rounded-xl flex flex-col md:flex-row md:items-center justify-between gap-4">
                <!-- Info Email -->
                <div class="flex items-start gap-3">
                    <div class="p-2 bg-surface rounded-lg border border-border text-primary shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-text-light font-medium uppercase tracking-wider mb-0.5">Alamat Email Instansi
                        </p>
                        <p class="text-sm font-semibold text-text">{{ $profil->email }}</p>
                        <p class="text-xs text-text-light mt-0.5 italic">* Email tidak dapat diubah</p>
                    </div>
                </div>

                <div class="hidden md:block w-px h-12 bg-border"></div>

                <!-- Status Akun -->
                <div class="flex items-center gap-6">
                    <div>
                        <p class="text-xs text-text-light font-medium uppercase tracking-wider mb-1.5">Status Akun</p>
                        @if ($profil->is_active)
                            <span
                                class="inline-flex px-2.5 py-1 bg-success/10 text-success text-xs font-bold rounded-md items-center gap-1.5 border border-success/20">
                                <span class="w-1.5 h-1.5 rounded-full bg-success animate-pulse"></span> Aktif
                            </span>
                        @else
                            <span
                                class="inline-flex px-2.5 py-1 bg-danger/10 text-danger text-xs font-bold rounded-md items-center gap-1.5 border border-danger/20">
                                <span class="w-1.5 h-1.5 rounded-full bg-danger"></span> Nonaktif
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Form Update Profil -->
            <form method="POST" action="{{ route('asn-profil-update') }}" class="space-y-8" data-confirm="Apakah Anda yakin ingin update profil anda?">
                @csrf
                @method('PUT')

                <!-- SECTION 1: Edit Data Akun -->
                <div>
                    <h2 class="text-lg font-semibold text-text mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Informasi Pribadi & Kontak
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-background p-5 rounded-xl border border-border">
                        <div>
                            <label class="block text-sm font-medium text-text-light mb-1.5">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $profil->name) }}"
                                class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-light mb-1.5">Nomor Handphone</label>
                            <input type="text" name="phone" value="{{ old('phone', $profil->phone) }}"
                                class="hanya-angka w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: Edit Data Kedinasan -->
                <div>
                    <h2 class="text-lg font-semibold text-text mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Data Kedinasan
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-background p-5 rounded-xl border border-border">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-text-light mb-1.5">Nomor Induk Pegawai
                                (NIP)</label>
                            <input type="text" name="nip" value="{{ old('nip', $profil->asnProfile->nip ?? '') }}"
                                class="hanya-angka w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors font-mono tracking-wider">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-light mb-1.5">Jabatan</label>
                            <input type="text" name="jabatan"
                                value="{{ old('jabatan', $profil->asnProfile->jabatan ?? '') }}"
                                class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-light mb-1.5">Unit Kerja</label>
                            <input type="text" name="unit_kerja"
                                value="{{ old('unit_kerja', $profil->asnProfile->unit_kerja ?? '') }}"
                                class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                        </div>
                    </div>
                </div>

                <hr class="border-border mt-8">

                <!-- Footer Button -->
                <div class="flex gap-4 justify-end pt-4">
                    {{-- <button type="submit"
                        class="w-full sm:w-auto bg-primary hover:bg-primary-dark text-surface flex justify-center items-center gap-2 px-10 py-3 rounded-xl font-medium transition-colors shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        Simpan Perubahan
                    </button> --}}
                    <x-main-button
                        class=" text-xs px-4 py-2 rounded-lg text-text border-text border transition-colors shadow-sm inline-flex items-center gap-2"
                        href="{{ route('asn-index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                        <span>Kembali</span>
                    </x-main-button>


                    <x-main-button
                        class="bg-primary hover:bg-primary-dark  text-xs px-4 py-2 rounded-lg text-white transition-colors shadow-sm inline-flex items-center gap-2"
                        type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2" class="w-5 h-5">
                            <circle cx="12" cy="12" r="9" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12l3 3 5-6" />
                        </svg>
                        <span>Simpan perubahan</span>
                    </x-main-button>
                </div>

            </form>
        </section>
    </main>
@endsection
