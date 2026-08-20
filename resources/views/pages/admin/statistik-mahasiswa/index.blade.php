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

            <!-- Tab Navigation -->
            <div class="mb-6 w-full">
                <div class="flex flex-wrap items-center gap-2 md:gap-3">

                    <!-- Tombol Tab: Pending (Default Active) -->
                    <button type="button" onclick="switchTab('pending')" id="tab-pending"
                        class="tab-btn px-3 sm:px-4 py-2 sm:py-2.5 text-[11px] sm:text-xs md:text-sm font-semibold rounded-md border transition-all duration-200 flex items-center justify-center gap-1.5 sm:gap-2 grow sm:grow-0 shadow-sm bg-primary border-primary text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 sm:h-4 sm:w-4 shrink-0" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Status Pending</span>
                    </button>

                    <!-- Tombol Tab: Selesai -->
                    <button type="button" onclick="switchTab('selesai')" id="tab-selesai"
                        class="tab-btn px-3 sm:px-4 py-2 sm:py-2.5 text-[11px] sm:text-xs md:text-sm font-semibold rounded-md border transition-all duration-200 flex items-center justify-center gap-1.5 sm:gap-2 grow sm:grow-0 shadow-sm bg-surface border-border text-text-light hover:bg-primary/5 hover:border-primary/50 hover:text-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 sm:h-4 sm:w-4 shrink-0" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Status Selesai</span>
                    </button>

                    <!-- Tombol Tab: Dibatalkan -->
                    <button type="button" onclick="switchTab('dibatalkan')" id="tab-dibatalkan"
                        class="tab-btn px-3 sm:px-4 py-2 sm:py-2.5 text-[11px] sm:text-xs md:text-sm font-semibold rounded-md border transition-all duration-200 flex items-center justify-center gap-1.5 sm:gap-2 grow sm:grow-0 shadow-sm bg-surface border-border text-text-light hover:bg-danger/5 hover:border-danger/50 hover:text-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 sm:h-4 sm:w-4 shrink-0" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Status Dibatalkan</span>
                    </button>

                </div>
            </div>

            <!-- Content Area -->
            <div class="w-full">

                <!-- Konten: Pending (Default Tampil) -->
                <div id="content-pending" class="tab-content block animate-fadeIn">
                    @include('components.admin.table-magang-pending')
                </div>

                <!-- Konten: Selesai (Default Sembunyi) -->
                <div id="content-selesai" class="tab-content hidden animate-fadeIn">
                    @include('components.admin.table-magang-selesai')
                </div>

                <!-- Konten: Dibatalkan (Default Sembunyi) -->
                <div id="content-dibatalkan" class="tab-content hidden animate-fadeIn">
                    @include('components.admin.table-magang-dibatalkan')
                </div>

            </div>

        </div>
    </section>

    <!-- Script Tab Switcher -->
    <script>
        function switchTab(tabName) {
            // 1. Sembunyikan semua konten tabel
            const contents = document.querySelectorAll('.tab-content');
            contents.forEach(content => {
                content.classList.remove('block');
                content.classList.add('hidden');
            });

            // 2. Tampilkan konten tabel yang diklik
            const activeContent = document.getElementById('content-' + tabName);
            if (activeContent) {
                activeContent.classList.remove('hidden');
                activeContent.classList.add('block');
            }

            // 3. Reset style semua tombol tab (Kembali ke mode inactive/outline)
            const buttons = document.querySelectorAll('.tab-btn');
            buttons.forEach(btn => {
                btn.classList.remove('bg-primary', 'border-primary', 'text-white', 'bg-danger', 'border-danger',
                    'text-danger');

                // Tambah style inactive (kecuali tombol batal kita beri hover spesifik merah)
                if (btn.id === 'tab-dibatalkan') {
                    btn.className =
                        "tab-btn px-3 sm:px-4 py-2 sm:py-2.5 text-[11px] sm:text-xs md:text-sm font-semibold rounded-md border transition-all duration-200 flex items-center justify-center gap-1.5 sm:gap-2 grow sm:grow-0 shadow-sm bg-surface border-border text-text-light hover:bg-danger/5 hover:border-danger/50 hover:text-danger";
                } else {
                    btn.className =
                        "tab-btn px-3 sm:px-4 py-2 sm:py-2.5 text-[11px] sm:text-xs md:text-sm font-semibold rounded-md border transition-all duration-200 flex items-center justify-center gap-1.5 sm:gap-2 grow sm:grow-0 shadow-sm bg-surface border-border text-text-light hover:bg-primary/5 hover:border-primary/50 hover:text-primary";
                }
            });

            // 4. Set style pada tombol tab yang sedang aktif (Ubah ke mode solid)
            const activeBtn = document.getElementById('tab-' + tabName);
            if (activeBtn) {
                // Hapus class inactive
                activeBtn.classList.remove('bg-surface', 'border-border', 'text-text-light', 'hover:bg-primary/5',
                    'hover:border-primary/50', 'hover:text-primary', 'hover:bg-danger/5', 'hover:border-danger/50',
                    'hover:text-danger');

                // Tambah class active (Warna khusus untuk 'dibatalkan')
                if (tabName === 'dibatalkan') {
                    activeBtn.classList.add('bg-danger', 'border-danger', 'text-white');
                } else {
                    activeBtn.classList.add('bg-primary', 'border-primary', 'text-white');
                }
            }
        }
    </script>

    <!-- CSS Tambahan (Opsional) untuk Animasi Halus -->
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
