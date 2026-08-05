{{-- container luar --}}
<section class="fixed w-60 h-full p-2 ">

    {{-- container dalam --}}

    <section class="h-full w-full bg-text container-dalam">

        {{-- conatiner logo bps --}}

        <figure class="w-16 aspect-square flex justify-center items-center">
            <img src="{{ asset('images/assets/logo-bps.png') }}" alt="">
        </figure>

        <nav class="mt-10">
            <ul class="font-montserrat flex flex-col gap-1.5">
                <!-- Menu Dashboard -->
                <li class="text-bold font-black text-white/50 text-[10px] ml-[5%] translate-y-2.5 mb-1">Main</li>
                <li>
                    <x-nav-link :href="route('asn-index')" :active="request()->routeIs('asn-index')">
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

                <li class="text-bold font-black text-white/50 text-[10px] ml-[5%] translate-y-2.5 mb-1">Management Tugas
                </li>
                <!-- Menu Mahasiswa -->
                <li>
                    <x-nav-link :href="route('asn-create-task-form')" :active="request()->routeIs('asn-create-task-form')">
                        <x-slot name="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" <svg
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 3h6a1 1 0 011 1v2h2a2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2h2V4a1 1 0 011-1z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 11v6M9 14h6" />
                            </svg>
                        </x-slot>
                        Buat tugas
                    </x-nav-link>
                </li>
                <!-- Menu Asn -->
                <li>
                    <x-nav-link :href="route('task-not-done')" :active="request()->routeIs('task-not-done')">
                        <x-slot name="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 3h6a1 1 0 011 1v2h2a2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2h2V4a1 1 0 011-1z" />
                                <circle cx="16" cy="16" r="3" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 14.5v1.8l1.2.7" />
                            </svg>
                        </x-slot>
                        Tugas belum selesai
                    </x-nav-link>
                </li>
                <li>
                    <x-nav-link :href="route('asn-submission-index')" :active="request()->routeIs('asn-submission-index')">
                        <x-slot name="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2" class="w-5 h-5">

                                <!-- Dokumen -->
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M14 2H7a2 2 0 00-2 2v16a2 2 0 002 2h10a2 2 0 002-2V7l-5-5z" />

                                <!-- Upload -->
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 17V9" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l3-3 3 3" />
                            </svg>
                        </x-slot>
                        Pengumpulan
                    </x-nav-link>
                </li>
                <li>
                    <x-nav-link :href="route('task-done')" :active="request()->routeIs('task-done')">
                        <x-slot name="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 3h6a1 1 0 011 1v2h2a2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2h2V4a1 1 0 011-1z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15l2 2 4-5" />
                            </svg>
                        </x-slot>
                        Tugas Selesai
                    </x-nav-link>
                </li>
                <li class="text-bold font-black text-danger/50 text-[10px] ml-[5%] translate-y-2.5 mb-1">Logout</li>

                @include('components.button-logout')
            </ul>
        </nav>
    </section>

</section>
