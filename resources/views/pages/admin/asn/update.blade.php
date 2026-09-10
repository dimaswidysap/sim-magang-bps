@extends('layouts.app')
@vite(['resources/js/validasi-number.js'])

@section('content')
    <main class="w-full p-4 md:p-8 bg-background min-h-screen font-montserrat">
        <section class="container-dalam max-w-4xl mx-auto">

            <!-- Card Utama Form -->
            <div class="bg-surface rounded-md shadow-sm border border-border p-6 md:p-10">

                <!-- Header Halaman -->
                <div class="mb-8 pb-6 border-b border-border flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-text">Edit Data Pegawai ASN</h1>
                        <p class="text-sm text-text-light mt-1">Perbarui informasi personal, kontak, kedinasan, dan akses akun pegawai.</p>
                    </div>
                    <div>
                        <span class="inline-flex items-center px-3.5 py-1.5 bg-background border border-border text-text-light text-xs font-semibold rounded-full font-mono">
                            ID: {{ $dataAsn->id }}
                        </span>
                    </div>
                </div>

                <!-- Alert Error Validation -->
                @if ($errors->any())
                    <div class="mb-8 p-4 bg-danger/10 border border-danger/30 rounded-xl">
                        <div class="flex items-center gap-2 text-danger font-semibold mb-2 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            Terdapat kesalahan pada input Anda:
                        </div>
                        <ul class="list-disc list-inside text-xs text-danger/90 ml-2 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin-asn-update', $dataAsn->id) }}" class="space-y-8" data-confirm="Apakah anda yakin ingin melakukan perubahan?">
                    @csrf
                    @method('PUT')

                    <!-- SECTION 1: Informasi Personal & Kontak -->
                    <div class="space-y-4">
                        <h2 class="text-base font-bold text-primary flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Informasi Personal & Kontak
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 bg-background p-6 rounded-xl border border-border">

                            <!-- Nama Lengkap -->
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-text-light uppercase tracking-wider mb-2">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name', $dataAsn->name) }}" required
                                    class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text text-sm focus:outline-none focus:border-primary transition-colors">
                            </div>

                            <!-- Tanggal Lahir -->
                            <div>
                                <label class="block text-xs font-semibold text-text-light uppercase tracking-wider mb-2">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $dataAsn->asnProfile->tanggal_lahir ?? $dataAsn->tanggal_lahir ?? '') }}"
                                    class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text text-sm focus:outline-none focus:border-primary transition-colors">
                            </div>

                            <!-- No. HP -->
                            <div>
                                <label class="block text-xs font-semibold text-text-light uppercase tracking-wider mb-2">No. Telepon / HP</label>
                                <input type="text" name="phone" value="{{ old('phone', $dataAsn->phone) }}"
                                    placeholder="Contoh: 08123456789"
                                    class="hanya-angka w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text text-sm focus:outline-none focus:border-primary transition-colors">
                            </div>

                            <!-- Email -->
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-text-light uppercase tracking-wider mb-2">Alamat Email</label>
                                <input type="email" name="email" value="{{ old('email', $dataAsn->email) }}" required
                                    class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text text-sm focus:outline-none focus:border-primary transition-colors">
                            </div>

                            <!-- Alamat Tempat Tinggal -->
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-text-light uppercase tracking-wider mb-2">Alamat Tempat Tinggal</label>
                                <textarea name="alamat" rows="3" placeholder="Masukkan alamat lengkap pegawai..."
                                    class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text text-sm focus:outline-none focus:border-primary transition-colors resize-y">{{ old('alamat', $dataAsn->asnProfile->alamat ?? $dataAsn->alamat ?? '') }}</textarea>
                            </div>

                        </div>
                    </div>

                    <!-- SECTION 2: Data Kedinasan -->
                    <div class="space-y-4">
                        <h2 class="text-base font-bold text-primary flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Data Kedinasan (ASN)
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 bg-background p-6 rounded-xl border border-border">

                            <!-- NIP -->
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-text-light uppercase tracking-wider mb-2">Nomor Induk Pegawai (NIP)</label>
                                <input type="text" name="nip" value="{{ old('nip', $dataAsn->asnProfile->nip ?? '') }}"
                                    class="hanya-angka w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text text-sm focus:outline-none focus:border-primary transition-colors font-mono" placeholder="Masukkan NIP 18 digit">
                            </div>

                            <!-- Jabatan -->
                            <div>
                                <label class="block text-xs font-semibold text-text-light uppercase tracking-wider mb-2">Jabatan</label>
                                <input type="text" name="jabatan" value="{{ old('jabatan', $dataAsn->asnProfile->jabatan ?? '') }}"
                                    class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text text-sm focus:outline-none focus:border-primary transition-colors">
                            </div>

                            <!-- Unit Kerja -->
                            <div>
                                <label class="block text-xs font-semibold text-text-light uppercase tracking-wider mb-2">Unit Kerja</label>
                                <input type="text" name="unit_kerja" value="{{ old('unit_kerja', $dataAsn->asnProfile->unit_kerja ?? '') }}"
                                    class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text text-sm focus:outline-none focus:border-primary transition-colors">
                            </div>

                        </div>
                    </div>

                    <!-- SECTION 3: Pengaturan Akun & Akses -->
                    <div class="space-y-4">
                        <h2 class="text-base font-bold text-primary flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Keamanan & Status Akun
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 bg-background p-6 rounded-xl border border-border">

                            <!-- Password Baru -->
                            <div>
                                <label class="block text-xs font-semibold text-text-light uppercase tracking-wider mb-2">Password Baru</label>
                                <input type="password" name="password" placeholder="••••••••"
                                    class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text text-sm focus:outline-none focus:border-primary transition-colors">
                                <p class="text-xs text-text-light mt-1.5 italic">* Kosongkan jika tidak ingin merubah password.</p>
                            </div>

                            <!-- Status Toggle -->
                            <div class="flex items-start">
                                <label class="flex items-center justify-between p-4 bg-surface border border-border rounded-xl cursor-pointer hover:border-primary transition-colors w-full group">
                                    <div>
                                        <span class="block text-sm font-semibold text-text group-hover:text-primary transition-colors">Status Akun Aktif</span>
                                        <span class="block text-xs text-text-light mt-0.5">Izinkan pengguna masuk ke dalam sistem</span>
                                    </div>

                                    <div class="relative flex items-center shrink-0">
                                        <input type="checkbox" name="is_active" value="1"
                                            {{ old('is_active', $dataAsn->is_active) ? 'checked' : '' }} class="sr-only peer">
                                        <div class="w-11 h-6 bg-text-light/30 rounded-full peer peer-checked:bg-primary transition-colors duration-200"></div>
                                        <div class="absolute left-1 top-1 w-4 h-4 bg-surface rounded-full transition-transform duration-200 peer-checked:translate-x-5 shadow-sm"></div>
                                    </div>
                                </label>
                            </div>

                        </div>
                    </div>

                    <hr class="border-border">

                    <!-- Footer Action Buttons -->
                    <div class="flex flex-col-reverse sm:flex-row justify-end items-center gap-3 pt-2">
                        <!-- Batal -->
                        <x-main-button
                            class="w-full sm:w-auto text-xs px-5 py-2.5 rounded-lg text-text border border-border bg-surface hover:bg-background transition-colors shadow-sm inline-flex justify-center items-center gap-2"
                            href="{{ route('admin-asn') }}">
                            <span>Batal</span>
                        </x-main-button>

                        <!-- Simpan -->
                        <x-main-button
                            class="w-full sm:w-auto bg-primary hover:bg-primary-dark text-xs px-5 py-2.5 rounded-lg text-white transition-colors shadow-sm inline-flex justify-center items-center gap-2 font-medium"
                            type="submit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span>Simpan Perubahan</span>
                        </x-main-button>
                    </div>

                </form>
            </div>
        </section>
    </main>
@endsection
