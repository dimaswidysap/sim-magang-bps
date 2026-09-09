@extends('layouts.app')

@section('content')
    <main class="w-full p-4 md:p-8 bg-background min-h-screen font-montserrat">
        <section class="max-w-6xl mx-auto space-y-6">

            <!-- Header & Navigasi -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-border">
                <div>
                    <h1 class="text-2xl font-bold text-text leading-snug">Daftar ASN Nonaktif</h1>
                    <p class="text-sm text-text-light mt-1">Daftar pengguna ASN yang saat ini dalam status nonaktif.</p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin-index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-surface border border-border rounded-lg text-xs font-semibold text-text hover:bg-black/5 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Filter Tab Navigasi -->
            <div class="flex items-center gap-2 border-b border-border pb-2">
                <a href="{{ route('asn-aktif') }}"
                    class="px-4 py-2 text-xs font-bold rounded-lg transition-colors {{ request()->routeIs('asn-aktif') ? 'bg-primary/10 text-primary border border-primary/20' : 'text-text-light hover:text-text' }}">
                    ASN Aktif
                </a>
                <a href="{{ route('asn-nonaktif') }}"
                    class="px-4 py-2 text-xs font-bold rounded-lg transition-colors {{ request()->routeIs('asn-nonaktif') ? 'bg-danger/10 text-danger border border-danger/20' : 'text-text-light hover:text-text' }}">
                    ASN Nonaktif
                </a>
            </div>

            <!-- Tabel Data ASN Nonaktif -->
            <div class="bg-surface border border-border rounded-xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-black/5 border-b border-border">
                            <tr>
                                <th
                                    class="px-5 py-4 text-xs font-semibold text-text-light uppercase tracking-wider text-center w-16">
                                    No
                                </th>
                                <th class="px-5 py-4 text-xs font-semibold text-text-light uppercase tracking-wider">
                                    Nama
                                </th>
                                <!-- Diubah menjadi text-left -->
                                <th
                                    class="px-5 py-4 text-xs font-semibold text-text-light uppercase tracking-wider text-left">
                                    Jabatan
                                </th>
                                <th class="px-5 py-4 text-xs font-semibold text-text-light uppercase tracking-wider">
                                    Email
                                </th>
                                <th class="px-5 py-4 text-xs font-semibold text-text-light uppercase tracking-wider">
                                    Nomor Telepon
                                </th>
                                <th class="px-5 py-4 text-xs font-semibold text-text-light uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            @forelse ($asnNonAktif as $item)
                                <tr class="hover:bg-black/[0.02] transition-colors duration-200">
                                    <td class="px-5 py-4 text-xs font-semibold text-text-light text-center align-middle">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="px-5 py-4 text-sm font-bold text-text align-middle">
                                        {{ $item->name }}
                                    </td>
                                    <!-- Diubah menjadi text-left -->
                                    <td class="px-5 py-4 text-left align-middle">
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 bg-danger/10 text-danger text-[10px] font-bold uppercase rounded-md border border-danger/20">
                                            {{ $item->asnProfile->jabatan }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-xs text-text-light align-middle">
                                        {{ $item->email }}
                                    </td>
                                    <td class="px-5 py-4 text-xs text-text-light align-middle">
                                        {{ $item->phone ?? '-' }}
                                    </td>
                                    <td class="px-5 py-4 text-xs text-text-light align-middle">
                                        <x-main-button href="{{ route('admin-asn-detail', $item->id) }}"
                                            class="w-full md:w-auto bg-surface hover:bg-primary text-text hover:text-white border border-border hover:border-primary text-xs px-4 py-2 rounded-lg transition-all shadow-sm inline-flex justify-center items-center gap-1.5">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="2" stroke="currentColor" class="w-4 h-4 shrink-0">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <span>Detail</span>
                                        </x-main-button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-8 text-center text-xs text-text-light italic">
                                        Tidak ada data ASN nonaktif yang ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </section>
    </main>
@endsection
