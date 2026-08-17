@php
    // Default-nya adalah 'diambil'
    $currentStatus = request('status', 'diambil');
@endphp

<!-- Wrapper Filter -->
<div class="mb-6 font-montserrat w-full">
    <!-- Menggunakan flex-wrap agar tombol otomatis turun dan menyesuaikan ukuran di layar sempit -->
    <div class="flex flex-wrap items-center gap-2 md:gap-3">

        <!-- Tombol Diambil -->
        <a href="{{ request()->fullUrlWithQuery(['status' => 'diambil']) }}"
            class="px-3 sm:px-4 py-2 sm:py-2.5 text-[11px] sm:text-xs md:text-sm font-semibold rounded-xl border transition-all duration-200 flex items-center justify-center gap-1.5 sm:gap-2 grow sm:grow-0
            {{ $currentStatus === 'diambil'
                ? 'bg-primary border-primary text-white shadow-sm'
                : 'bg-surface border-border text-text-light hover:bg-primary/5 hover:border-primary/50 hover:text-primary shadow-sm transition-colors' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 sm:h-4 sm:w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            <span>Diambil</span>
        </a>

        <!-- Tombol Menunggu Review -->
        <a href="{{ request()->fullUrlWithQuery(['status' => 'menunggu_review']) }}"
            class="px-3 sm:px-4 py-2 sm:py-2.5 text-[11px] sm:text-xs md:text-sm font-semibold rounded-xl border transition-all duration-200 flex items-center justify-center gap-1.5 sm:gap-2 grow sm:grow-0
            {{ $currentStatus === 'menunggu_review'
                ? 'bg-primary border-primary text-white shadow-sm'
                : 'bg-surface border-border text-text-light hover:bg-primary/5 hover:border-primary/50 hover:text-primary shadow-sm transition-colors' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 sm:h-4 sm:w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Menunggu Review</span>
        </a>

        <!-- Tombol Selesai -->
        <a href="{{ request()->fullUrlWithQuery(['status' => 'selesai']) }}"
            class="px-3 sm:px-4 py-2 sm:py-2.5 text-[11px] sm:text-xs md:text-sm font-semibold rounded-xl border transition-all duration-200 flex items-center justify-center gap-1.5 sm:gap-2 grow sm:grow-0
            {{ $currentStatus === 'selesai'
                ? 'bg-primary border-primary text-white shadow-sm'
                : 'bg-surface border-border text-text-light hover:bg-primary/5 hover:border-primary/50 hover:text-primary shadow-sm transition-colors' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 sm:h-4 sm:w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Selesai</span>
        </a>

    </div>
</div>
