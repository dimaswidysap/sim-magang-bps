{{-- Header Component --}}
<header class="w-full p-2">

    <section class="w-full h-full flex flex-col sm:flex-row items-center justify-between gap-3 container-dalam">

        <!-- Profil Mahasiswa -->
        <div class="flex items-center w-full sm:w-auto font-montserrat">
            <a href="{{ route('mahasiswa-profil') }}"
                class="flex items-center gap-3 p-1.5 md:p-2 pr-4 md:pr-5 w-full sm:w-auto bg-transparent hover:bg-surface border border-transparent hover:border-border rounded-full transition-all duration-300 group">

                <!-- Avatar Ikon Mahasiswa -->
                <figure
                    class="h-10 w-10 md:h-11 md:w-11 rounded-full bg-primary/10 text-primary flex items-center justify-center shrink-0 border border-primary/20 group-hover:bg-primary group-hover:text-white transition-colors duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5" class="w-5 h-5 md:w-6 md:h-6">
                        <!-- Topi Wisuda -->
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4L3 8l9 4 9-4-9-4z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 10v3c0 2 2.5 4 5 4s5-2 5-4v-3" />
                        <!-- Kepala -->
                        <circle cx="12" cy="18" r="2" />
                        <!-- Badan -->
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.5 22a3.5 3.5 0 017 0" />
                    </svg>
                </figure>

                <!-- Informasi Text -->
                <div class="flex flex-col justify-center overflow-hidden">
                    <span
                        class="font-bold text-text text-sm leading-tight group-hover:text-primary transition-colors duration-300 truncate">
                        {{ auth()->user()->name }}
                    </span>
                    <span class="text-[10px] md:text-[11px] font-medium text-text-light mt-0.5 truncate">
                        {{ auth()->user()->email }}
                    </span>
                </div>

            </a>
        </div>

        <!-- Tombol Menu Tools -->
        <x-buttonv2 href="{{ route('tools-index') }}" color="accent-dark" class="w-full sm:w-auto">
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z" />
                </svg>
            </x-slot>
            Menu Tools
        </x-buttonv2>

    </section>

</header>
