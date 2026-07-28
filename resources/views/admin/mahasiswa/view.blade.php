@extends('layouts.app')

@section('content')
    <main class="w-full p-4 md:p-8 bg-background min-h-screen font-montserrat">
        <section class="container-dalam max-w-4xl mx-auto">

            {{-- {{ $detailUser }} --}}

            <!-- Tombol Kembali -->
            <div class="mb-6">


                <x-main-button
                    class="bg-primary text-white text-xs px-4 py-2 rounded-lg  transition-colors shadow-sm inline-flex items-center gap-2"
                   href="{{ route('admin-mahasiswa') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>Kembali</span>
                </x-main-button>
            </div>

            <!-- Card Detail Profil -->
            <div class="bg-surface rounded-2xl shadow-sm border border-border overflow-hidden font-montserrat">

                <!-- Cover Header -->
                <div class="h-32 md:h-40 bg-primary-light"></div>

                <!-- Bagian Atas Profil -->
                <div class="px-6 md:px-10 pb-6 relative">

                    <!-- Foto Profil (Absolute Position agar menimpa cover) -->
                    <div class="absolute -top-16 flex items-end">
                        <div class="w-32 h-32 rounded-full border-4 border-surface bg-background overflow-hidden shadow-sm">
                            @if (!empty($detailUser->foto))
                                <img src="{{ asset('storage/' . $detailUser->foto) }}" alt="Foto {{ $detailUser->name }}"
                                    class="w-full h-full object-cover">
                            @else
                                <!-- Placeholder jika tidak ada foto -->
                                <div
                                    class="w-full h-full bg-primary flex items-center justify-center text-surface text-4xl font-bold">
                                    {{ substr($detailUser->nama ?? 'M', 0, 1) }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Status Badge -->
                    <div class="flex justify-end pt-4">
                        @if ($detailUser->is_active)
                            <span
                                class="px-4 py-1.5 bg-success text-surface text-sm font-semibold rounded-full flex items-center gap-2 shadow-sm">
                                <span class="w-2 h-2 rounded-full bg-surface animate-pulse"></span>
                                Aktif
                            </span>
                        @else
                            <span
                                class="px-4 py-1.5 bg-danger text-surface text-sm font-semibold rounded-full flex items-center gap-2 shadow-sm">
                                <span class="w-2 h-2 rounded-full bg-surface animate-pulse"></span>
                                Nonaktif
                            </span>
                        @endif

                    </div>

                    <!-- Nama dan NPM -->
                    <div class="mt-4">
                        <h1 class="text-3xl font-bold text-text mb-1">{{ $detailUser->name ?? 'Nama Lengkap' }}</h1>
                        <p class="text-primary-dark text-lg font-medium ">
                            {{ $detailUser->mahasiswaProfile->nim ?? 'NPM Mahasiswa' }}</p>
                    </div>
                </div>

                <hr class="border-border">

                <!-- Grid Informasi Detail -->
                <div class="p-6 md:p-10 space-y-8">

                    <!-- Informasi Akademik -->
                    <div>
                        <h2 class="text-xl font-semibold text-text mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 14l9-5-9-5-9 5 9 5z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            </svg>
                            Informasi Akademik
                        </h2>

                        <div
                            class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-background p-6 rounded-xl border border-border">
                            <div>
                                <p class="text-sm text-text-light mb-1">Program Studi</p>
                                <p class="font-medium text-text">{{ $detailUser->mahasiswaProfile->jurusan ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-text-light mb-1">Jenjang</p>
                                <p class="font-medium text-text">
                                    {{ $detailUser->mahasiswaProfile?->tanggal_mulai?->format('Y-m-d') ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-text-light mb-1">Tanggal mulai</p>
                                <p class="font-medium text-text">
                                    {{ $detailUser->mahasiswaProfile?->tanggal_mulai?->format('d-m-Y') ?? '-' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-text-light mb-1">Tanggal Selesai
                                <p class="font-medium text-text">
                                    {{ $detailUser->mahasiswaProfile?->tanggal_selesai?->format('d-m-Y') ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-text-light mb-1">Periode Magang
                                <p class="font-medium text-text">
                                    {{ $detailUser->mahasiswaProfile?->periode_magang_id ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-text-light mb-1">Keahlian
                                <p class="font-medium text-text">

                                    @if ($detailUser->mahasiswaProfile && count($detailUser->mahasiswaProfile->skills) > 0)
                                        <div class="skills-container">
                                            <ul>
                                                @foreach ($detailUser->mahasiswaProfile->skills as $skill)
                                                    <li>{{ $skill->nama_skill }} </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @else
                                        <p>Belum ada data skill yang ditambahkan.</p>
                                    @endif
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Kontak -->
                    <div>
                        <h2 class="text-xl font-semibold text-text mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-secondary" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                            </svg>
                            Data Pribadi
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
                            <div>
                                <p class="text-sm text-text-light mb-1">Email Lengkap</p>
                                <p class="font-medium text-text flex items-center gap-2">
                                    {{ $detailUser->email ?? '-' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-text-light mb-1">Nomor Telepon</p>
                                <p class="font-medium text-text">{{ $detailUser->no_telp ?? '-' }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <p class="text-sm text-text-light mb-1">Alamat Asal</p>
                                <p class="font-medium text-text">{{ $detailUser->alamat ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi lainya -->
                    <div>
                        <h2 class="text-xl font-semibold text-text mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-secondary" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                            </svg>
                            Data Lainya
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
                            <div>
                                <p class="text-sm text-text-light mb-1">ID User</p>
                                <p class="font-medium text-text flex items-center gap-2">
                                    {{ $detailUser->id ?? '-' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-text-light mb-1">Surat Pengantar</p>
                                <p class="font-medium text-text">
                                    {{ $detailUser->surat_pengantar_path ? 'Ada' : 'Belum ada' }}</p>
                            </div>


                        </div>
                    </div>

                </div>

                <!-- Footer Actions -->
                <div class="bg-footer  px-6 md:px-10 py-5 flex  flex-col md:flex-row gap-3 justify-end items-center">
                    <form action="{{ route('admin-mahasiswa-destroy', $detailUser->id) }}" method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus data mahasiswa ini? Semua data terkait (absensi, tugas) ikut terhapus dan tidak bisa dikembalikan.')">
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
                        href="{{ route('form-mahasiswa-edit', $detailUser->id) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.862 3.487a2.25 2.25 0 113.182 3.182L8.25 18.463 3 21l2.537-5.25L16.862 3.487z" />
                        </svg>
                        <span>Edit data</span>
                    </x-main-button>
                </div>

            </div>
        </section>
    </main>
@endsection
