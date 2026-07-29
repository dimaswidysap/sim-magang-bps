{{-- container luar --}}
<header class="w-full flex p-2">

    <section class="w-full h-full container-dalam">
        <div class="flex gap-2 items-center">
            <a href="{{ route('asn-profil') }}" class="inline-flex items-center gap-1.5">

                <figure class="h-12 aspect-square rounded-md bg-primary text-text">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1" >

                        <circle cx="12" cy="8" r="4" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 20a8 8 0 0116 0" />
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
