@extends('layouts.app')

@section('content')
    <main class="w-full p-4 md:p-8 bg-background min-h-screen font-montserrat">
        <section class="max-w-4xl mx-auto space-y-6">

            <!-- Header & Judul Halaman -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-border">
                <div>
                    <h1 class="text-2xl font-bold text-text leading-snug">Profil Anda</h1>
                    {{-- <p class="text-sm text-text-light mt-1">Informasi lengkap mengenai data dan profil Aparatur Sipil Negara.</p> --}}
                </div>
            </div>

            <!-- Card Profil -->
            <div class="bg-surface border border-border rounded-xl shadow-sm overflow-hidden">

                <!-- Bagian 1: Avatar & Info Utama -->
                <div class="p-6 sm:p-8 border-b border-border bg-black/[0.02]">
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">

                        <!-- Avatar -->
                        <div class="flex-shrink-0">
                            @if ($profil->avatar)
                                <img src="{{ asset('storage/' . $profil->avatar) }}" alt="Foto {{ $profil->name }}"
                                    class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-sm">
                            @else
                                <div
                                    class="w-24 h-24 rounded-full border-4 border-white shadow-sm bg-primary/10 text-primary flex items-center justify-center text-3xl font-bold uppercase">
                                    {{ substr($profil->name, 0, 1) }}
                                </div>
                            @endif
                        </div>

                        <!-- Info Nama & Jabatan -->
                        <div class="flex-1 text-center sm:text-left">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div>
                                    <h2 class="text-2xl font-bold text-text">{{ $profil->name }}</h2>
                                    <p class="text-sm font-medium text-text-light mt-1">
                                        NIP. {{ $profil->asnProfile->nip ?? '-' }}
                                    </p>
                                </div>

                                <!-- Badge Status -->
                                <div>
                                    @if ($profil->is_active)
                                        <span
                                            class="inline-flex items-center px-3 py-1 bg-primary/10 text-primary-dark text-xs font-bold uppercase rounded-md border border-primary/20">
                                            Aktif
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-3 py-1 bg-danger/10 text-danger text-xs font-bold uppercase rounded-md border border-danger/20">
                                            Nonaktif
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div
                                class="mt-4 inline-block px-4 py-2 bg-white border border-border rounded-lg text-sm font-semibold text-text shadow-sm">
                                {{ $profil->asnProfile->jabatan ?? 'Jabatan Belum Diatur' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bagian 2: Grid Informasi Detail -->
                <div class="p-6 sm:p-8">
                    <h3 class="text-lg font-bold text-text mb-6">Informasi Pribadi & Kontak</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">

                        <div class="space-y-1">
                            <p class="text-xs font-semibold text-text-light uppercase tracking-wide">Alamat Email</p>
                            <p class="text-sm font-medium text-text">{{ $profil->email }}</p>
                        </div>

                        <div class="space-y-1">
                            <p class="text-xs font-semibold text-text-light uppercase tracking-wide">Nomor Telepon</p>
                            <p class="text-sm font-medium text-text">{{ $profil->phone ?? '-' }}</p>
                        </div>

                        <div class="space-y-1">
                            <p class="text-xs font-semibold text-text-light uppercase tracking-wide">Unit Kerja</p>
                            <p class="text-sm font-medium text-text">{{ $profil->asnProfile->unit_kerja ?? '-' }}</p>
                        </div>

                        <div class="space-y-1">
                            <p class="text-xs font-semibold text-text-light uppercase tracking-wide">Tanggal Lahir</p>
                            <p class="text-sm font-medium text-text">
                                {{ $profil->asnProfile->tanggal_lahir ? \Carbon\Carbon::parse($profil->asnProfile->tanggal_lahir)->translatedFormat('d F Y') : '-' }}
                            </p>
                        </div>

                        <div class="space-y-1 md:col-span-2">
                            <p class="text-xs font-semibold text-text-light uppercase tracking-wide">Alamat Lengkap</p>
                            <p class="text-sm font-medium text-text leading-relaxed">
                                {{ $profil->asnProfile->alamat ?? 'Alamat belum ditambahkan.' }}
                            </p>
                        </div>

                    </div>
                </div>

                <!-- Bagian 3: Tombol Aksi (Diperbaiki Padding & Alignment-nya) -->
                <div class="px-6 sm:px-8 pb-6 pt-4 border-t border-border/60 flex flex-col-reverse sm:flex-row gap-3 justify-end items-center">
                    <x-buttonv2 href="{{ route('asn-index') }}" color="primary" class="w-full sm:w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="3" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali
                    </x-buttonv2>

                    <x-buttonv2 href="{{ route('asn-profil-form') }}"  color="accent-dark" class="w-full sm:w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Data Saya
                    </x-buttonv2>
                </div>

                <!-- Bagian 4: Footer Info Update -->
                <div class="px-6 py-4 bg-black/5 border-t border-border text-xs text-text-light text-center sm:text-left">
                    Terakhir diperbarui: {{ $profil->updated_at->diffForHumans() }}
                </div>

            </div>

        </section>
    </main>
@endsection
