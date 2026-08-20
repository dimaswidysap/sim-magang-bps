@php
    // Default-nya adalah 'diambil'
    $currentStatus = request('status', 'diambil');
@endphp

<!-- Wrapper Filter -->
<div class="mb-6 font-montserrat w-full">
    <!-- Menggunakan flex-wrap agar tombol otomatis turun dan menyesuaikan ukuran di layar sempit -->
    <div class="flex flex-wrap items-center gap-2 md:gap-3">

        <!-- Tombol Diambil -->
        <x-buttonv2 href="{{ request()->fullUrlWithQuery(['status' => 'diambil']) }}"
            color="{{ $currentStatus === 'diambil' ? 'primary' : 'surface' }}" class="grow sm:grow-0">
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </x-slot>

            <span>Diambil</span>
        </x-buttonv2>

        <!-- Tombol Menunggu Review -->
        <x-buttonv2 href="{{ request()->fullUrlWithQuery(['status' => 'menunggu_review']) }}"
            color="{{ $currentStatus === 'menunggu_review' ? 'primary' : 'surface' }}" class="grow sm:grow-0">
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </x-slot>

            <span>Menunggu Review</span>
        </x-buttonv2>
        <!-- Tombol Menunggu Review -->
        <x-buttonv2 href="{{ request()->fullUrlWithQuery(['status' => 'revisi']) }}"
            color="{{ $currentStatus === 'revisi' ? 'primary' : 'surface' }}" class="grow sm:grow-0">
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                    <path d="M12 20h9" />
                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z" />
                    <path d="M15 5l3 3" />
                </svg>
            </x-slot>

            <span>Revisi</span>
        </x-buttonv2>

        <!-- Tombol Selesai -->
        <x-buttonv2 href="{{ request()->fullUrlWithQuery(['status' => 'selesai']) }}"
            color="{{ $currentStatus === 'selesai' ? 'primary' : 'surface' }}" class="grow sm:grow-0">
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </x-slot>

            <span>Selesai</span>
        </x-buttonv2>

    </div>
</div>
