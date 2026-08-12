@php
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
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
            </svg>
            Semua
        </a>

        <!-- Tombol Diambil -->
        <a href="{{ request()->fullUrlWithQuery(['status' => 'diambil']) }}"
            class="px-4 py-2.5 text-xs md:text-sm font-semibold rounded-lg border transition-all duration-200 flex items-center gap-2
            {{ $currentStatus === 'diambil'
                ? 'bg-primary border-primary text-white shadow-sm'
                : 'bg-surface border-border text-text-light hover:border-primary/50 hover:text-primary transition-colors' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Diambil
        </a>

        <!-- Tombol Mengunggu review -->
        <a href="{{ request()->fullUrlWithQuery(['status' => 'menunggu_review']) }}"
            class="px-4 py-2.5 text-xs md:text-sm font-semibold rounded-lg border transition-all duration-200 flex items-center gap-2
            {{ $currentStatus === 'menunggu_review'
                ? 'bg-primary border-primary text-white shadow-sm'
                : 'bg-surface border-border text-text-light hover:border-primary/50 hover:text-primary transition-colors' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Mengungu review
        </a>

        <!-- Tombol Selesai -->
        <a href="{{ request()->fullUrlWithQuery(['status' => 'selesai']) }}"
            class="px-4 py-2.5 text-xs md:text-sm font-semibold rounded-lg border transition-all duration-200 flex items-center gap-2
            {{ $currentStatus === 'selesai'
                ? 'bg-primary border-primary text-white shadow-sm'
                : 'bg-surface border-border text-text-light hover:border-primary/50 hover:text-primary transition-colors' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Selesai
        </a>

    </div>
</div>
