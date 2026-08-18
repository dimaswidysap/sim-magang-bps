<section class="w-full flex flex-col lg:flex-row gap-4 md:gap-6 font-montserrat mb-8">

    <!-- Card Statistik Mahasiswa (Full Clickable Area) -->
    <a href="{{ route('statistik-user') }}"
        class="flex-1 bg-surface border border-border rounded-xl shadow-sm p-5 md:p-6 flex flex-col sm:flex-row sm:items-center gap-4 md:gap-5 hover:shadow-md hover:border-primary/50 transition-all duration-300 group">

        <!-- Ikon Mahasiswa -->
        <figure
            class="h-14 w-14 md:h-16 md:w-16 rounded-full bg-primary/10 text-primary flex items-center justify-center shrink-0 border border-primary/20 group-hover:bg-primary group-hover:text-white transition-colors duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 md:h-8 md:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
        </figure>

        <!-- Informasi Data -->
        <div class="flex-1">
            <h3 class="text-[11px] md:text-xs font-bold text-text-light uppercase tracking-wider mb-3 group-hover:text-primary transition-colors">
                Mahasiswa Magang
            </h3>

            <!-- Flex Wrap untuk responsivitas Mobile -->
            <div class="flex flex-wrap items-center gap-y-3 gap-x-4 md:gap-x-5">

                <!-- Data Aktif -->
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-text leading-none">{{ $jumlahMahasiswaAktif->count() }}</h2>
                    <p class="text-[10px] md:text-[11px] text-success-dark font-bold flex items-center gap-1.5 mt-1.5 uppercase tracking-wide">
                        <span class="w-1.5 h-1.5 rounded-full bg-success animate-pulse"></span> Aktif
                    </p>
                </div>

                <!-- Garis Pemisah -->
                <div class="hidden sm:block h-8 w-px bg-border"></div>

                <!-- Data Pending -->
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-text leading-none">{{ $jumlahMahasiswaNonAktif->count() }}</h2>
                    <p class="text-[10px] md:text-[11px] text-accent font-bold flex items-center gap-1.5 mt-1.5 uppercase tracking-wide">
                        <span class="w-1.5 h-1.5 rounded-full bg-accent"></span> Pending
                    </p>
                </div>

                <!-- Garis Pemisah -->
                <div class="hidden sm:block h-8 w-px bg-border"></div>

                <!-- Data Selesai -->
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-text leading-none">{{ $jumlahMahasiswaSelesai->count() }}</h2>
                    <p class="text-[10px] md:text-[11px] text-primary-dark font-bold flex items-center gap-1.5 mt-1.5 uppercase tracking-wide">
                        <span class="w-1.5 h-1.5 rounded-full bg-primary"></span> Selesai
                    </p>
                </div>

                <!-- Garis Pemisah -->
                <div class="hidden sm:block h-8 w-px bg-border"></div>

                <!-- Data Batal -->
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-text leading-none">{{ $jumlahMahasiswaBatal->count() }}</h2>
                    <p class="text-[10px] md:text-[11px] text-danger-dark font-bold flex items-center gap-1.5 mt-1.5 uppercase tracking-wide">
                        <span class="w-1.5 h-1.5 rounded-full bg-danger"></span> Batal
                    </p>
                </div>

            </div>
        </div>
    </a>

    <!-- Card Statistik ASN (Full Clickable Area) -->
    <a href="#"
        class="flex-1 bg-surface border border-border rounded-xl shadow-sm p-5 md:p-6 flex flex-col sm:flex-row sm:items-center gap-4 md:gap-5 hover:shadow-md hover:border-warning/50 transition-all duration-300 group">

        <!-- Ikon ASN / Pegawai -->
        <figure
            class="h-14 w-14 md:h-16 md:w-16 rounded-full bg-warning/10 text-warning-dark flex items-center justify-center shrink-0 border border-warning/20 group-hover:bg-warning group-hover:text-white transition-colors duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 md:h-8 md:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
        </figure>

        <!-- Informasi Data -->
        <div class="flex-1">
            <h3 class="text-[11px] md:text-xs font-bold text-text-light uppercase tracking-wider mb-3 group-hover:text-warning-dark transition-colors">
                Pegawai ASN
            </h3>

            <!-- Flex Wrap untuk responsivitas Mobile -->
            <div class="flex flex-wrap items-center gap-y-3 gap-x-4 md:gap-x-5">

                <!-- Data Aktif -->
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-text leading-none">{{ $jumlahAsnAktif }}</h2>
                    <p class="text-[10px] md:text-[11px] text-success-dark font-bold flex items-center gap-1.5 mt-1.5 uppercase tracking-wide">
                        <span class="w-1.5 h-1.5 rounded-full bg-success animate-pulse"></span> Aktif
                    </p>
                </div>

                <!-- Garis Pemisah -->
                <div class="hidden sm:block h-8 w-px bg-border"></div>

                <!-- Data Nonaktif -->
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-text leading-none">{{ $jumlahAsnNonAktif }}</h2>
                    <p class="text-[10px] md:text-[11px] text-danger-dark font-bold flex items-center gap-1.5 mt-1.5 uppercase tracking-wide">
                        <span class="w-1.5 h-1.5 rounded-full bg-danger"></span> Nonaktif
                    </p>
                </div>

            </div>
        </div>
    </a>

</section>
