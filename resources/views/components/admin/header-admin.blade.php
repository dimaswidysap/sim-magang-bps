{{-- container luar --}}
<header class="w-full flex p-2">

    <section class="w-full h-full container-dalam">
        <div class="flex items-center gap-3 md:gap-4 cursor-default group">

            <!-- Avatar Inisial -->
            <figure
                class="h-10 w-10 md:h-12 md:w-12 rounded-[10px] bg-primary/10 border border-primary/20 flex items-center justify-center shrink-0 transition-colors group-hover:bg-primary/20">
                <span class="text-primary-dark font-bold text-base md:text-lg">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </span>
            </figure>

            <!-- Informasi Nama -->
            <div class="flex flex-col">
                <span class="text-[10px] md:text-[11px] text-text-light font-semibold uppercase tracking-wider mb-0.5">
                    Selamat Datang,
                </span>
                <p class="font-bold text-text text-sm md:text-base leading-none">
                    {{ auth()->user()->name }}
                </p>
            </div>

        </div>
    </section>

</header>
