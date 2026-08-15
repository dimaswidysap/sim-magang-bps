<section class="w-full grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6 font-montserrat">

    <!-- Card Statistik Mahasiswa -->
    <div
        class="bg-surface border border-border rounded-[10px] shadow-sm p-5 md:p-6 flex items-center gap-4 md:gap-6 hover:shadow-md transition-shadow duration-300">

        <!-- Ikon Mahasiswa -->
        <figure
            class="h-14 w-14 md:h-16 md:w-16 rounded-full bg-primary/10 text-primary flex items-center justify-center shrink-0 border border-primary/20">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 md:h-8 md:w-8" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
        </figure>

        <!-- Informasi Data -->
        <div class="flex-1">
            <h3 class="text-xs md:text-sm font-semibold text-text-light uppercase tracking-wider mb-2">
                Mahasiswa Magang
            </h3>

            <div class="flex items-center gap-4 md:gap-6">
                <!-- Data Aktif -->
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-text leading-none">{{ $jumlahMahasiswaAktif }}</h2>
                    <p class="text-[10px] md:text-xs text-success font-medium flex items-center gap-1.5 mt-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-success"></span> Aktif
                    </p>
                </div>

                <!-- Garis Pemisah -->
                <div class="h-8 w-px bg-border"></div>

                <!-- Data Pending -->
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-text leading-none">{{ $jumlahMahasiswaNonAktif }}
                    </h2>
                    <p class="text-[10px] md:text-xs text-accent font-medium flex items-center gap-1.5 mt-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-accent"></span> Pending
                    </p>
                </div>

                <!-- Garis Pemisah -->
                <div class="h-8 w-px bg-border"></div>
                {{-- data selesai --}}
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-text leading-none">{{ $jumlahMahasiswaSelesai }}
                    </h2>
                    <p class="text-[10px] md:text-xs text-primary font-medium flex items-center gap-1.5 mt-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-primary"></span> Selesai
                    </p>
                </div>
                <!-- Garis Pemisah -->
                <div class="h-8 w-px bg-border"></div>
                {{-- data dibatalkan --}}
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-text leading-none">{{ $jumlahMahasiswaBatal }}
                    </h2>
                    <p class="text-[10px] md:text-xs text-danger font-medium flex items-center gap-1.5 mt-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-danger"></span> Batal
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Statistik ASN -->
    <div
        class="bg-surface border border-border rounded-2xl shadow-sm p-5 md:p-6 flex items-center gap-4 md:gap-6 hover:shadow-md transition-shadow duration-300">

        <!-- Ikon ASN / Pegawai -->
        <figure
            class="h-14 w-14 md:h-16 md:w-16 rounded-full bg-warning/10 text-warning-dark flex items-center justify-center shrink-0 border border-warning/20">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 md:h-8 md:w-8" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
        </figure>

        <!-- Informasi Data -->
        <div class="flex-1">
            <h3 class="text-xs md:text-sm font-semibold text-text-light uppercase tracking-wider mb-2">
                Pegawai ASN
            </h3>

            <div class="flex items-center gap-4 md:gap-6">
                <!-- Data Aktif -->
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-text leading-none">{{ $jumlahAsnAktif }}</h2>
                    <p class="text-[10px] md:text-xs text-success font-medium flex items-center gap-1.5 mt-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-success"></span> Aktif
                    </p>
                </div>

                <!-- Garis Pemisah -->
                <div class="h-8 w-px bg-border"></div>

                <!-- Data Nonaktif -->
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-text leading-none">{{ $jumlahAsnNonAktif }}</h2>
                    <p class="text-[10px] md:text-xs text-danger font-medium flex items-center gap-1.5 mt-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-danger"></span> Nonaktif
                    </p>
                </div>
            </div>
        </div>
    </div>

</section>
