{{-- container luar --}}
<header class="w-full flex p-2">

    <section class="w-full h-full container-dalam">
        <div class="flex gap-2 items-center">
            <a href="{{ route('mahasiswa-profil') }}" class="inline-flex items-center gap-1.5">

                <figure class="h-12 aspect-square rounded-md bg-primary text-text">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1">

                        <!-- Topi Wisuda -->
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4L3 8l9 4 9-4-9-4z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 10v3c0 2 2.5 4 5 4s5-2 5-4v-3" />

                        <!-- Kepala -->
                        <circle cx="12" cy="18" r="2" />

                        <!-- Badan -->
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.5 22a3.5 3.5 0 017 0" />
                    </svg>
                </figure>
                <p class="font-montserrat font-semibold text-text inline-flex flex-col">
                    <span>{{ auth()->user()->name }}</span>
                    <span class="text-[10px]">{{ auth()->user()->email }}</span>
                </p>
            </a>
        </div>
    </section>

</header>
