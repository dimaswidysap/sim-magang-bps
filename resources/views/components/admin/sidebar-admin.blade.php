{{-- container luar --}}
<section class="fixed w-60 h-full p-2 ">

    {{-- container dalam --}}

    <section class="h-full w-full container-dalam">

        {{-- conatiner logo bps --}}

        <figure class="w-16 aspect-square flex justify-center items-center">
            <img src="{{ asset('images/assets/logo-bps.png') }}" alt="">
        </figure>

        <nav class="mt-10">
            <ul class="font-montserrat flex flex-col gap-1.5">
                <!-- Menu Dashboard -->
                <li>
                    <x-nav-link :href="route('admin-index')" :active="request()->routeIs('admin-index')">
                        <x-slot name="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2" class="w-5 h-5">
                                <rect x="3" y="3" width="7" height="7" rx="1" />
                                <rect x="14" y="3" width="7" height="7" rx="1" />
                                <rect x="3" y="14" width="7" height="7" rx="1" />
                                <rect x="14" y="14" width="7" height="7" rx="1" />
                            </svg>
                        </x-slot>
                        Dashboard
                    </x-nav-link>
                </li>

                <!-- Menu Mahasiswa -->
                <li>
                    <x-nav-link :href="route('admin-mahasiswa')" :active="request()->routeIs('admin-mahasiswa*')">
                        <x-slot name="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            </svg>
                        </x-slot>
                        Mahasiswa
                    </x-nav-link>
                </li>
                <!-- Menu Asn -->
                <li>
                    <x-nav-link :href="route('admin-asn')" :active="request()->routeIs('admin-asn*')">
                        <x-slot name="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2" class="w-5 h-5">
                                <rect x="3" y="5" width="18" height="14" rx="2" />
                                <circle cx="8" cy="10" r="2" />
                                <path d="M6 15c.5-1.5 3.5-1.5 4 0" />
                                <path d="M13 9h5" />
                                <path d="M13 12h5" />
                                <path d="M13 15h5" />
                            </svg>
                        </x-slot>
                        ASN
                    </x-nav-link>
                </li>
            </ul>
        </nav>
    </section>

</section>
