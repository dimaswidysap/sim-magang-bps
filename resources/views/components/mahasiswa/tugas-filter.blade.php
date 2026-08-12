@php
    // Ambil status saat ini dari URL. Jika tidak ada, default-nya adalah 'semua'
    $currentStatus = request('status', 'semua');
@endphp

<!-- Wrapper Filter dengan Horizontal Scroll untuk Mobile -->
<div class="mb-6 font-montserrat w-full overflow-x-auto hide-scrollbar">
    <!-- min-w-max memastikan tombol tidak terlipat/menyempit di layar kecil -->
    <div class="flex items-center gap-2 md:gap-3 min-w-max pb-2">

        <!-- Tombol Semua (Menghapus parameter status dari URL) -->
        <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}"
            class="px-4 py-2.5 text-xs md:text-sm font-semibold rounded-lg border transition-all duration-200 flex items-center gap-2
            {{ $currentStatus === 'semua'
                ? 'bg-primary border-primary text-white shadow-sm'
                : 'bg-surface border-border text-text-light hover:border-primary/50 hover:text-primary transition-colors' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
            </svg>
            Semua Tugas
        </a>

        <!-- Tombol Tersedia -->
        <a href="{{ request()->fullUrlWithQuery(['status' => 'tersedia']) }}"
            class="px-4 py-2.5 text-xs md:text-sm font-semibold rounded-lg border transition-all duration-200 flex items-center gap-2
            {{ $currentStatus === 'tersedia'
                ? 'bg-primary border-primary text-white shadow-sm'
                : 'bg-surface border-border text-text-light hover:border-primary/50 hover:text-primary transition-colors' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z" />
            </svg>
            Tersedia
        </a>

        <!-- Tombol Diambil -->
        <a href="{{ request()->fullUrlWithQuery(['status' => 'diambil']) }}"
            class="px-4 py-2.5 text-xs md:text-sm font-semibold rounded-lg border transition-all duration-200 flex items-center gap-2
            {{ $currentStatus === 'diambil'
                ? 'bg-primary border-primary text-white shadow-sm'
                : 'bg-surface border-border text-text-light hover:border-primary/50 hover:text-primary transition-colors' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Diambil / Proses
        </a>

        <!-- Tombol Selesai -->
        <a href="{{ request()->fullUrlWithQuery(['status' => 'selesai']) }}"
            class="px-4 py-2.5 text-xs md:text-sm font-semibold rounded-lg border transition-all duration-200 flex items-center gap-2
            {{ $currentStatus === 'selesai'
                ? 'bg-primary border-primary text-white shadow-sm'
                : 'bg-surface border-border text-text-light hover:border-primary/50 hover:text-primary transition-colors' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Selesai
        </a>

    </div>
</div>




