@extends('layouts.app')

@section('content')
    <main class=" relative w-full flex bg-background">
        @include('components.admin.sidebar-admin')

        {{-- container-content --}}
        <section class="flex flex-col flex-1 pl-60">

            {{-- header --}}
            @include('components.admin.header-admin')

            <section class="w-full p-2">
                <section class="container-dalam">
                    <section class="w-full flex justify-end mt-4 mb-8">
                        <x-main-button class="bg-accent-dark text-white" href="{{ route('admin.mahasiswa.create') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            <span>Tambah Data</span>
                        </x-main-button>
                    </section>
                    {{-- table --}}
                    <div class="overflow-x-auto font-montserrat">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-background border-b border-border">
                                <tr>
                                    <th class="p-4 text-sm font-semibold text-text-light">NIM</th>
                                    <th class="p-4 text-sm font-semibold text-text-light">Nama</th>
                                    <th class="p-4 text-sm font-semibold text-text-light">Instansi Asal</th>
                                    <th class="p-4 text-sm font-semibold text-text-light">Jurusan (Jenjang)</th>
                                    <th class="p-4 text-sm font-semibold text-text-light">Periode Magang</th>
                                    <th class="p-4 text-sm font-semibold text-text-light text-center">Status</th>
                                    <th class="p-4 text-sm font-semibold text-text-light text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($dataMahasiswa as $mhs)
                                    <tr class="border-b border-border hover:bg-background transition-colors">
                                        <td class="p-4 text-sm font-medium text-text">
                                            {{ $mhs->mahasiswaProfile->nim ?? '-' }}</td>
                                        <td class="p-4 text-sm font-medium text-text">{{ $mhs->name }}</td>
                                        <td class="p-4 text-sm text-text">{{ $mhs->mahasiswaProfile->instansi_asal ?? '-' }}
                                        </td>
                                        <td class="p-4 text-sm text-text">{{ $mhs->mahasiswaProfile->jurusan ?? '-' }}
                                            ({{ $mhs->mahasiswaProfile->jenjang ?? '-' }})
                                        </td>
                                        <td class="p-4 text-sm text-text">
                                            {{ \Carbon\Carbon::parse($mhs->tanggal_mulai)->translatedFormat('d M Y') ?? '-' }}
                                            -
                                            {{ \Carbon\Carbon::parse($mhs->tanggal_selesai)->translatedFormat('d M Y') ?? '-' }}
                                        </td>
                                        <td class="p-4 text-center">
                                            @if ($mhs->is_active)
                                                <span
                                                    class="px-3  py-0.5 bg-success text-surface text-sm font-semibold rounded-full flex items-center gap-2 shadow-sm">
                                                    <span class="w-2 h-2 rounded-full bg-surface animate-pulse"></span>
                                                    Aktif
                                                </span>
                                            @else
                                                <span
                                                    class="px-3  py-0.5 bg-danger text-surface text-sm font-semibold rounded-full flex items-center gap-2 shadow-sm">
                                                    <span class="w-2 h-2 rounded-full bg-surface animate-pulse"></span>
                                                    Nonaktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="p-4 text-sm text-center">
                                            <x-main-button class="bg-primary text-white"
                                                href="{{ route('admin-mahasiswa-detail', $mhs->id) }}">
                                                {{-- <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 4.5v15m7.5-7.5h-15" />
                                                </svg> --}}
                                                <span>Detail</span>
                                            </x-main-button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-4 text-sm text-center text-gray-500">
                                            Belum ada data mahasiswa.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </section>
        </section>
    </main>
@endsection
