@php
    // Ubah default dari 'semua' menjadi 'tersedia'
    $currentStatus = request('status', 'tersedia');
@endphp

<!-- Wrapper Filter -->
<div class="mb-6 font-montserrat w-full">
    <!-- Menggunakan flex-wrap agar tombol otomatis turun dan menyesuaikan ukuran di layar sempit -->
    <div class="flex flex-wrap items-center gap-2 md:gap-3">

        <!-- Tombol Tersedia -->
        <x-buttonv2 href="{{ request()->fullUrlWithQuery(['status' => 'tersedia']) }}"
            color="{{ $currentStatus === 'tersedia' ? 'primary' : 'surface' }}" class="grow sm:grow-0">
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z" />
                </svg>
            </x-slot>
            <span>Tersedia</span>
        </x-buttonv2>



        <!-- Tombol Diambil -->
        <x-buttonv2 href="{{ request()->fullUrlWithQuery(['status' => 'diambil']) }}"
            color="{{ $currentStatus === 'diambil' ? 'primary' : 'surface' }}" class="grow sm:grow-0">
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </x-slot>

            <span>Diambil / Proses</span>
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
