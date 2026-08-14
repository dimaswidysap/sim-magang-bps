@extends('layouts.app')

@section('content')
    <main class="w-full p-4 md:p-8 bg-background min-h-screen font-montserrat">
        <section class="container-dalam max-w-4xl mx-auto">

            <!-- Tombol Kembali -->
            <div class="mb-6 flex justify-end w-full">



                <x-main-button
                    class="bg-primary text-white text-xs px-4 py-2 rounded-lg  transition-colors shadow-sm inline-flex items-center gap-2"
                    href="{{ route('admin-asn') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>Kembali</span>
                </x-main-button>
            </div>

            <!-- Card Detail Profil ASN -->
            <div class="bg-surface rounded-2xl shadow-sm border border-border overflow-hidden">

                <!-- Cover Header (Warna lebih gelap/formal untuk ASN) -->
                <div class="h-32 md:h-40 bg-primary-dark"></div>

                <!-- Bagian Atas Profil -->
                <div class="px-6 md:px-10 pb-6 relative">

                    <!-- Foto Profil (Absolute Position) -->
                    <div class="absolute -top-16 flex items-end">
                        <div
                            class="w-32 h-32 rounded-full border-4 border-surface bg-background overflow-hidden shadow-sm flex items-center justify-center">
                            @if (!empty($detailAsn->avatar))
                                <img src="{{ asset('storage/' . $detailAsn->avatar) }}" alt="Foto {{ $detailAsn->name }}"
                                    class="w-full h-full object-cover">
                            @else
                                <!-- Placeholder Initials jika tidak ada foto -->
                                <div
                                    class="w-full h-full bg-primary flex items-center justify-center text-surface text-5xl font-bold">
                                    {{ strtoupper(substr($detailAsn->name ?? 'A', 0, 1)) }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Status Badge -->
                    <div class="flex justify-end pt-4">
                        @if ($detailAsn->is_active)
                            <span
                                class="px-4 py-1.5 bg-success text-surface text-sm font-semibold rounded-full flex items-center gap-2 shadow-sm">
                                <span class="w-2 h-2 rounded-full bg-surface animate-pulse"></span>
                                ASN Aktif
                            </span>
                        @else
                            <span
                                class="px-4 py-1.5 bg-danger text-surface text-sm font-semibold rounded-full flex items-center gap-2 shadow-sm">
                                <span class="w-2 h-2 rounded-full bg-surface"></span>
                                Nonaktif
                            </span>
                        @endif
                    </div>

                    <!-- Nama dan NIP -->
                    <div class="mt-4">
                        <h1 class="text-3xl font-bold text-text mb-1">{{ $detailAsn->name ?? 'Nama Pegawai' }}</h1>
                        <p class="text-primary-dark text-lg font-medium font-mono flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                            </svg>
                            NIP. {{ $detailAsn->asnProfile->nip ?? '-' }}
                        </p>
                    </div>
                </div>

                <hr class="border-border">

                <!-- Grid Informasi Detail -->
                <div class="p-6 md:p-10 space-y-8">

                    <!-- Informasi Kedinasan/Jabatan -->
                    <div>
                        <h2 class="text-xl font-semibold text-text mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Informasi Kedinasan
                        </h2>

                        <div
                            class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-background p-6 rounded-xl border border-border">
                            <div>
                                <p class="text-sm text-text-light mb-1">Jabatan</p>
                                <p class="font-medium text-text">{{ $detailAsn->asnProfile->jabatan ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-text-light mb-1">Unit Kerja</p>
                                <p class="font-medium text-text">{{ $detailAsn->asnProfile->unit_kerja ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-text-light mb-1">Hak Akses Sistem (Role)</p>
                                <p class="font-medium text-text uppercase">{{ $detailAsn->role ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-text-light mb-1">Tanggal Terdaftar</p>
                                <p class="font-medium text-text">
                                    {{ $detailAsn->created_at ? \Carbon\Carbon::parse($detailAsn->created_at)->translatedFormat('d F Y') : '-' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Kontak -->
                    <div>
                        <h2 class="text-xl font-semibold text-text mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-secondary" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Informasi Kontak
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
                            <div>
                                <p class="text-sm text-text-light mb-1">Alamat Email</p>
                                <a href="mailto:{{ $detailAsn->email }}"
                                    class="font-medium text-primary hover:text-primary-dark transition-colors inline-flex items-center gap-1.5">
                                    {{ $detailAsn->email ?? '-' }}
                                    @if ($detailAsn->email_verified_at)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-success"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    @endif
                                </a>
                            </div>
                            <div>
                                <p class="text-sm text-text-light mb-1">Nomor Telepon</p>
                                <p class="font-medium text-text">{{ $detailAsn->phone ?? 'Belum ditambahkan' }}</p>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Footer Actions -->
                <div class="bg-footer px-6 md:px-10 py-5 flex flex-col md:flex-row gap-3 justify-end items-center">

                    <form method="POST" action="{{ route('admin-asn-destroy', $detailAsn->id) }}"
                        data-confirm="Yakin ingin menghapus data asn ini? Semua data terkait ikut terhapus dan tidak bisa dikembalikan.">
                        @csrf
                        @method('DELETE')


                        <x-main-button
                            class="border border-danger text-danger! text-xs px-4 py-2 rounded-lg  transition-colors shadow-sm inline-flex items-center gap-2"
                            type="submit">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6 7h12M9 7V5h6v2m-8 0v12a2 2 0 002 2h6a2 2 0 002-2V7M10 11l4 4M14 11l-4 4" />
                            </svg>
                            <span>Hapus data</span>
                        </x-main-button>>

                    </form>
                    <x-main-button
                        class="bg-primary hover:bg-primary-dark  text-xs px-4 py-2 rounded-lg text-white transition-colors shadow-sm inline-flex items-center gap-2"
                        href="{{ route('form-asn-edit', $detailAsn->id) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span>Edit data</span>
                    </x-main-button>
                </div>

            </div>
        </section>
    </main>
@endsection
