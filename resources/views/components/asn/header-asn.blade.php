{{-- Header Component ASN --}}
<header
    class="w-full p-2">

    <div class="w-full flex items-center justify-between gap-3 container-dalam">

        <!-- Profil ASN -->
        <div class="flex items-center min-w-0 font-montserrat">
            <a href="{{ route('asn-detail-profil') }}"
                class="flex items-center gap-2.5 sm:gap-3 p-1.5 pr-3 sm:pr-4 bg-background/50 hover:bg-background border border-border/60 hover:border-primary/40 rounded-full transition-all duration-300 group shadow-xs hover:shadow-sm min-w-0">

                <!-- Avatar Ikon ASN -->
                <figure
                    class="h-9 w-9 sm:h-10 sm:w-10 rounded-full bg-primary/10 text-primary flex items-center justify-center shrink-0 border border-primary/20 group-hover:bg-primary group-hover:text-white transition-all duration-300 shadow-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5" class="w-5 h-5">
                        <circle cx="12" cy="8" r="4" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 20a8 8 0 0116 0" />
                    </svg>
                </figure>

                <!-- Informasi Text (Otomatis Truncate di Layar Kecil) -->
                <div class="flex flex-col justify-center min-w-0 pr-1">
                    <span
                        class="font-bold text-text text-xs sm:text-sm leading-tight group-hover:text-primary transition-colors duration-300 truncate max-w-[120px] xs:max-w-[160px] sm:max-w-[220px] md:max-w-xs">
                        {{ auth()->user()->name }}
                    </span>
                    <span
                        class="text-[10px] sm:text-[11px] font-medium text-text-light mt-0.5 truncate max-w-[120px] xs:max-w-[160px] sm:max-w-[220px] md:max-w-xs">
                        {{ auth()->user()->email }}
                    </span>
                </div>

            </a>
        </div>

        <!-- Tombol Menu Tools -->
        <a href="{{ route('tools-index') }}"
            class="group inline-flex items-center gap-2 p-1.5 pr-3 sm:pr-4 bg-background/50 hover:bg-primary/5 border border-border/60 hover:border-primary/40 rounded-full transition-all duration-300 shadow-xs hover:shadow-sm shrink-0">

            {{-- Lingkaran Ikon --}}
            <div
                class="flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 bg-surface text-text-light border border-border/50 rounded-full group-hover:bg-primary/10 group-hover:text-primary group-hover:border-primary/30 transition-all duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 sm:w-4.5 sm:h-4.5" fill="none"
                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z" />
                </svg>
            </div>

            {{-- Teks TOOLS --}}
            <span
                class="font-bold text-xs sm:text-sm text-text tracking-wider group-hover:text-primary transition-colors duration-300">
                TOOLS
            </span>
        </a>

    </div>

</header>
