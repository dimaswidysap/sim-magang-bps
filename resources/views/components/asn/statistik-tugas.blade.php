<section class="w-full grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6 font-montserrat ">

    <!-- Card Statistik Tugas Selesai -->
    <div
        class="bg-surface border border-border rounded-[11px] shadow-sm p-5 md:p-6 flex items-center gap-4 md:gap-6 hover:shadow-md transition-shadow duration-300">

        <!-- Ikon Check / Selesai -->
        <figure
            class="h-14 w-14 md:h-16 md:w-16 rounded-full bg-success/10 text-success flex items-center justify-center shrink-0 border border-success/20">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 md:h-8 md:w-8" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </figure>

        <!-- Informasi Data -->
        <div class="flex-1">
            <h3 class="text-xs md:text-sm font-semibold text-text-light uppercase tracking-wider mb-1">
                Tugas Selesai
            </h3>
            <div class="flex items-end gap-2">
                <h2 class="text-2xl md:text-3xl font-bold text-text leading-none">{{ $totalSelesai }}</h2>
                <span class="text-[10px] md:text-xs text-success font-medium mb-0.5">Tugas</span>
            </div>
        </div>
    </div>

    <!-- Card Statistik Tugas Belum Selesai -->
    <div
        class="bg-surface border border-border rounded-[11px] shadow-sm p-5 md:p-6 flex items-center gap-4 md:gap-6 hover:shadow-md transition-shadow duration-300">

        <!-- Ikon Clock / Belum Selesai -->
        <figure
            class="h-14 w-14 md:h-16 md:w-16 rounded-full bg-warning/10 text-warning-dark flex items-center justify-center shrink-0 border border-warning/20">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 md:h-8 md:w-8" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </figure>

        <!-- Informasi Data -->
        <div class="flex-1">
            <h3 class="text-xs md:text-sm font-semibold text-text-light uppercase tracking-wider mb-1">
                Tugas Belum Selesai
            </h3>
            <div class="flex items-end gap-2">
                <h2 class="text-2xl md:text-3xl font-bold text-text leading-none">{{ $totalBelumSelesai }}</h2>
                <span class="text-[10px] md:text-xs text-warning-dark font-medium mb-0.5">Tugas</span>
            </div>
        </div>
    </div>

</section>
