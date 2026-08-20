@extends('layouts.app')

@section('content')
    <section class="w-full p-2 md:p-4 font-montserrat">
        <div class="container-dalam max-w-7xl mx-auto">

            <!-- Tombol Kembali -->
            <div class="mb-6 flex justify-start w-full">
                <x-buttonv2 href="{{ route('admin-index') }}" color="accent-dark" class="w-full sm:w-auto">
                    <x-slot name="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:text-white transition-colors"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </x-slot>
                    Kembali
                </x-buttonv2>
            </div>

            <!-- Header Halaman -->
            <div class="mb-6">
                <h1 class="text-xl md:text-2xl font-bold text-text">Data Pegawai ASN</h1>
                <p class="text-xs md:text-sm text-text-light mt-1">Kelola daftar pegawai ASN yang aktif maupun nonaktif di
                    sistem.</p>
            </div>

            <!-- Tab Navigation -->
            <div class="mb-6 w-full">
                <div class="flex flex-wrap items-center gap-2 md:gap-3">

                    <!-- Tombol Tab: ASN Aktif (Default Active) -->
                    <button type="button" onclick="switchTab('aktif')" id="tab-aktif"
                        class="tab-btn px-4 py-2.5 text-xs md:text-sm font-semibold rounded-md border transition-all duration-200 flex items-center justify-center gap-2 grow sm:grow-0 shadow-sm bg-primary border-primary text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>ASN Aktif ({{ count($aktif) }})</span>
                    </button>

                    <!-- Tombol Tab: ASN Nonaktif -->
                    <button type="button" onclick="switchTab('nonaktif')" id="tab-nonaktif"
                        class="tab-btn px-4 py-2.5 text-xs md:text-sm font-semibold rounded-md border transition-all duration-200 flex items-center justify-center gap-2 grow sm:grow-0 shadow-sm bg-surface border-border text-text-light hover:bg-danger/5 hover:border-danger/50 hover:text-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                        <span>ASN Nonaktif ({{ count($nonAktif) }})</span>
                    </button>

                </div>
            </div>

            <!-- Content Area -->
            <div class="w-full bg-surface rounded-md shadow-sm border border-border overflow-hidden">

                <!-- Konten: ASN Aktif -->
                <div id="content-aktif" class="tab-content block animate-fadeIn">
                    <div class="w-full">
                        <table class="w-full text-left border-collapse">
                            <!-- Header (Ditambahkan persentase lebar kolom agar simetris) -->
                            <thead
                                class="hidden md:table-header-group bg-background/50 border-b border-border text-text-light text-xs uppercase tracking-wider">
                                <tr>
                                    <th class="px-6 py-4 font-bold whitespace-nowrap w-[45%]">Nama Pegawai</th>
                                    <th class="px-6 py-4 font-bold whitespace-nowrap w-[40%]">Email</th>
                                    <th class="px-6 py-4 font-bold text-center whitespace-nowrap w-[15%]">Status</th>
                                </tr>
                            </thead>
                            <!-- Body -->
                            <tbody class="block md:table-row-group divide-y divide-border md:divide-y-0">
                                @forelse ($aktif as $asn)
                                    <tr
                                        class="block md:table-row flex-col p-4 md:p-0 hover:bg-primary/5 transition-colors duration-200 md:border-b md:border-border last:border-b-0 align-middle">
                                        <td
                                            class="block md:table-cell md:px-6 md:py-0 mb-3 md:mb-0 border-b border-border border-dashed md:border-none pb-3 md:pb-0 align-middle">
                                            <div class="flex justify-between md:justify-start items-center gap-3">
                                                <span
                                                    class="md:hidden text-[10px] font-bold text-text-light uppercase tracking-wider">Nama:</span>
                                                <div class="flex items-center gap-3">
                                                    <div
                                                        class="w-9 h-9 rounded-full bg-primary/10 text-primary-dark flex items-center justify-center text-xs font-bold shrink-0 border border-primary/20">
                                                        {{ strtoupper(substr($asn->name ?? 'A', 0, 1)) }}
                                                    </div>
                                                    <span class="text-sm font-bold text-text">{{ $asn->name }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="block md:table-cell md:px-6 md:py-4 mb-3 md:mb-0 align-middle">
                                            <div class="flex justify-between items-center md:block">
                                                <span
                                                    class="md:hidden text-[10px] font-bold text-text-light uppercase tracking-wider">Email:</span>
                                                <span class="text-sm text-text-light">{{ $asn->email }}</span>
                                            </div>
                                        </td>
                                        <td
                                            class="block md:table-cell md:px-6 md:py-4 md:text-center mb-2 md:mb-0 align-middle">
                                            <div class="flex justify-between items-center md:flex md:justify-center w-full">
                                                <span
                                                    class="md:hidden text-[10px] font-bold text-text-light uppercase tracking-wider">Status:</span>
                                                <span
                                                    class="inline-flex px-3 py-1 bg-success/10 border border-success/20 text-success-dark text-xs font-bold rounded-full items-center gap-1.5 shadow-sm md:whitespace-nowrap">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-success animate-pulse"></span>
                                                    Aktif
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="block md:table-row">
                                        <td colspan="3"
                                            class="block md:table-cell px-6 py-12 text-center text-text-light">
                                            <p class="text-sm">Tidak ada data ASN yang aktif.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Konten: ASN Nonaktif -->
                <div id="content-nonaktif" class="tab-content hidden animate-fadeIn">
                    <div class="w-full">
                        <table class="w-full text-left border-collapse">
                            <!-- Header (Ditambahkan persentase lebar kolom agar simetris) -->
                            <thead
                                class="hidden md:table-header-group bg-background/50 border-b border-border text-text-light text-xs uppercase tracking-wider">
                                <tr>
                                    <th class="px-6 py-4 font-bold whitespace-nowrap w-[45%]">Nama Pegawai</th>
                                    <th class="px-6 py-4 font-bold whitespace-nowrap w-[40%]">No Telepon</th>
                                    <th class="px-6 py-4 font-bold text-center whitespace-nowrap w-[15%]">Status</th>
                                </tr>
                            </thead>
                            <!-- Body -->
                            <tbody class="block md:table-row-group divide-y divide-border md:divide-y-0">
                                @forelse ($nonAktif as $asn)
                                    <tr
                                        class="block md:table-row flex-col p-4 md:p-0 hover:bg-danger/5 transition-colors duration-200 md:border-b md:border-border last:border-b-0 align-middle">
                                        <td
                                            class="block md:table-cell md:px-6 md:py-0 mb-3 md:mb-0 border-b border-border border-dashed md:border-none pb-3 md:pb-0 align-middle">
                                            <div class="flex justify-between md:justify-start items-center gap-3">
                                                <span
                                                    class="md:hidden text-[10px] font-bold text-text-light uppercase tracking-wider">Nama:</span>
                                                <div class="flex items-center gap-3">
                                                    <div
                                                        class="w-9 h-9 rounded-full bg-danger/10 text-danger-dark flex items-center justify-center text-xs font-bold shrink-0 border border-danger/20">
                                                        {{ strtoupper(substr($asn->name ?? 'A', 0, 1)) }}
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span class="text-sm font-bold text-text">{{ $asn->name }}</span>
                                                        <span class="text-[10px] text-text-light">{{ $asn->email }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="block md:table-cell md:px-6 md:py-4 mb-3 md:mb-0 align-middle">
                                            <div class="flex justify-between items-center md:block">
                                                <span
                                                    class="md:hidden text-[10px] font-bold text-text-light uppercase tracking-wider">No
                                                    HP:</span>
                                                <span
                                                    class="text-sm text-text-light">{{ $asn->phone ?? 'Belum ditambahkan' }}</span>
                                            </div>
                                        </td>
                                        <td
                                            class="block md:table-cell md:px-6 md:py-4 md:text-center mb-2 md:mb-0 align-middle">
                                            <div class="flex justify-between items-center md:flex md:justify-center w-full">
                                                <span
                                                    class="md:hidden text-[10px] font-bold text-text-light uppercase tracking-wider">Status:</span>
                                                <span
                                                    class="inline-flex px-3 py-1 bg-danger/10 border border-danger/20 text-danger-dark text-xs font-bold rounded-full items-center gap-1.5 shadow-sm md:whitespace-nowrap">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-danger"></span>
                                                    Nonaktif
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="block md:table-row">
                                        <td colspan="3"
                                            class="block md:table-cell px-6 py-12 text-center text-text-light">
                                            <p class="text-sm">Tidak ada data ASN yang nonaktif.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- Script Tab Switcher -->
    <script>
        function switchTab(tabName) {
            // Sembunyikan semua konten
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('block');
                content.classList.add('hidden');
            });

            // Tampilkan konten yang dipilih
            document.getElementById('content-' + tabName).classList.remove('hidden');
            document.getElementById('content-' + tabName).classList.add('block');

            // Reset semua tombol (kembali ke outline style)
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.className =
                    "tab-btn px-4 py-2.5 text-xs md:text-sm font-semibold rounded-md border transition-all duration-200 flex items-center justify-center gap-2 grow sm:grow-0 shadow-sm bg-surface border-border text-text-light hover:bg-primary/5 hover:border-primary/50 hover:text-primary";
            });

            // Khusus tombol 'Nonaktif', beri hover default warna merah (danger) saat tidak aktif
            document.getElementById('tab-nonaktif').className =
                "tab-btn px-4 py-2.5 text-xs md:text-sm font-semibold rounded-md border transition-all duration-200 flex items-center justify-center gap-2 grow sm:grow-0 shadow-sm bg-surface border-border text-text-light hover:bg-danger/5 hover:border-danger/50 hover:text-danger";

            // Set tombol yang aktif (Solid style)
            const activeBtn = document.getElementById('tab-' + tabName);
            if (tabName === 'aktif') {
                activeBtn.className =
                    "tab-btn px-4 py-2.5 text-xs md:text-sm font-semibold rounded-md border transition-all duration-200 flex items-center justify-center gap-2 grow sm:grow-0 shadow-sm bg-primary border-primary text-white";
            } else if (tabName === 'nonaktif') {
                activeBtn.className =
                    "tab-btn px-4 py-2.5 text-xs md:text-sm font-semibold rounded-md border transition-all duration-200 flex items-center justify-center gap-2 grow sm:grow-0 shadow-sm bg-danger border-danger text-white";
            }
        }
    </script>

    <style>
        .animate-fadeIn {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection
