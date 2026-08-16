{{-- container luar --}}
<section class="fixed w-60 h-full p-2 ">

    {{-- container dalam --}}

    <section class="h-full w-full bg-text container-dalam">

        {{-- conatiner logo bps --}}

        @include('components.icon-sidebar')

        <nav class="mt-10">
            <ul class="font-montserrat flex flex-col gap-1.5">
                <!-- Menu Dashboard -->
                <li class="text-bold font-black text-white/50 text-[10px] ml-[5%] translate-y-2.5 mb-1">Main</li>
                <li>
                    <x-nav-link :href="route('mahasiswa-index')" :active="request()->routeIs('mahasiswa-index')">
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
                <li class="text-bold font-black text-white/50 text-[10px] ml-[5%] translate-y-2.5 mb-1">Berita</li>
                <li>
                    <x-nav-link :href="route('berita-index')" :active="request()->routeIs('berita-index')">
                        <x-slot name="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                        </x-slot>
                        Berita
                    </x-nav-link>
                </li>

                <li class="text-bold font-black text-white/50 text-[10px] ml-[5%] translate-y-2.5 mb-1">Management Tugas
                </li>
                <!-- Menu Mahasiswa -->
                <li>
                    <x-nav-link :href="route('mahasiswa-undangan')" :active="request()->routeIs('mahasiswa-undangan')">
                        <x-slot name="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2" class="w-5 h-5">

                                <rect x="3" y="5" width="18" height="14" rx="2" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7l9 6 9-6" />
                            </svg>
                        </x-slot>
                        Undangan Tugas
                    </x-nav-link>
                </li>
                <!-- Menu Mahasiswa -->
                <li>
                    <x-nav-link :href="route('tugas')" :active="request()->routeIs('tugas')">
                        <x-slot name="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M14 2H7a2 2 0 00-2 2v16a2 2 0 002 2h10a2 2 0 002-2V7l-5-5z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 10h6M9 14h6M9 18h4" />
                            </svg>
                        </x-slot>
                        Tugas
                    </x-nav-link>
                </li>
                <!-- Menu Asn -->
                <li>
                    <x-nav-link :href="route('tugas-saya')" :active="request()->routeIs('tugas-saya')">
                        <x-slot name="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2" class="w-5 h-5">

                                <!-- Clipboard -->
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 3h6a1 1 0 011 1v2h2a2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2h2V4a1 1 0 011-1z" />

                                <!-- User -->
                                <circle cx="12" cy="11" r="2" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.5 16a2.5 2.5 0 015 0" />
                            </svg>
                        </x-slot>
                        Tugas saya
                    </x-nav-link>
                </li>

                <li class="text-bold font-black text-danger/50 text-[10px] ml-[5%] translate-y-2.5 mb-1">Logout</li>

                @include('components.button-logout')
            </ul>
        </nav>
    </section>

</section>
