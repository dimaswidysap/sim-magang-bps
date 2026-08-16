{{-- container luar --}}
<header class="w-full flex p-2">

    <section class="w-full h-full container-dalam">
        <div class="flex items-center font-montserrat">
            <a href="{{ route('asn-profil') }}"
                class="flex items-center gap-3 p-1.5 md:p-2 pr-4 md:pr-5 bg-transparent hover:bg-surface border border-transparent hover:border-border rounded-full transition-all duration-300 group">

                <!-- Avatar Ikon -->
                <figure
                    class="h-10 w-10 md:h-11 md:w-11 rounded-full bg-primary/10 text-primary flex items-center justify-center shrink-0 border border-primary/20 group-hover:bg-primary group-hover:text-white transition-colors duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5" class="w-5 h-5 md:w-6 md:h-6">
                        <circle cx="12" cy="8" r="4" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 20a8 8 0 0116 0" />
                    </svg>
                </figure>

                <!-- Informasi Text -->
                <div class="flex flex-col justify-center">
                    <span
                        class="font-bold text-text text-sm leading-tight group-hover:text-primary transition-colors duration-300">
                        {{ auth()->user()->name }}
                    </span>
                    <span class="text-[10px] md:text-[11px] font-medium text-text-light mt-0.5">
                        {{ auth()->user()->email }}
                    </span>
                </div>

            </a>
        </div>
    </section>

</header>
