{{-- container luar --}}
@php
    $jumlahPermintaanPending = \App\Models\MahasiswaProfile::query()
        ->where('status_pengajuan', 'menunggu_verifikasi')
        ->where(function ($q) {
            $q->whereNull('kode_kelompok')->orWhere('is_ketua_kelompok', true);
        })
        ->count();
@endphp

<section class="fixed w-60 h-full p-2 sidebar-mobile z-10">

    {{-- container dalam --}}
    <section class="h-full w-full bg-text container-dalam relative overflow-y-auto">

        {{-- container logo bps --}}
        @include('components.icon-sidebar')

        <nav class="mt-8 pb-6">
            <ul class="font-montserrat flex flex-col gap-1.5">
                <!-- Menu Dashboard -->
                <li class="text-bold font-black text-white/50 text-[10px] ml-[5%] translate-y-2.5 mb-1">Main</li>
                <li>
                    <x-nav-link :href="route('admin-index')" :active="request()->routeIs('admin-index')">
                        <x-slot name="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2" class="w-5 h-5">
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
                    <x-nav-link :href="route('berita-index')" :active="request()->routeIs('berita-*')">
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

                <li class="text-bold font-black text-white/50 text-[10px] ml-[5%] translate-y-2.5 mb-1">Management User</li>

                <!-- Menu Permintaan Magang (Baru) -->
                <li>
                    <x-nav-link :href="route('admin-permintaan-magang')" :active="request()->routeIs('admin-permintaan-magang') || request()->routeIs('admin.permintaan.*')">
                        <x-slot name="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </x-slot>
                        <span class="flex items-center justify-between w-full gap-2">
                            <span>Permintaan Pendamping</span>
                            @if ($jumlahPermintaanPending > 0)
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-danger text-white">
                                    {{ $jumlahPermintaanPending }}
                                </span>
                            @endif
                        </span>
                    </x-nav-link>
                </li>

                <!-- Menu Mahasiswa -->
                <li>
                    <x-nav-link :href="route('admin-mahasiswa')" :active="request()->routeIs('admin-mahasiswa*') || request()->routeIs('form-mahasiswa-edit')">
                        <x-slot name="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            </svg>
                        </x-slot>
                        Magang/PKL
                    </x-nav-link>
                </li>

                <!-- Menu Asn -->
                <li>
                    <x-nav-link :href="route('admin-asn')" :active="request()->routeIs('admin-asn*') || request()->routeIs('form-asn-edit') || request()->routeIs('asn.mahasiswa.create')">
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
                        ASN (Pembimbing magang)
                    </x-nav-link>
                </li>

                <li class="text-bold font-black text-white/50 text-[10px] ml-[5%] translate-y-2.5 mb-1">Management Magang</li>
                <li>
                    <x-nav-link :href="route('admin-skill')" :active="request()->routeIs('admin-skill*') || request()->routeIs('create-skill')">
                        <x-slot name="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2" class="w-5 h-5">
                                <circle cx="8" cy="8" r="3" />
                                <circle cx="16" cy="8" r="3" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 19a5 5 0 0110 0M11 19a5 5 0 0110 0" />
                            </svg>
                        </x-slot>
                        Skills (Keahlian)
                    </x-nav-link>
                </li>

                <li class="text-bold font-black text-white/50 text-[10px] ml-[5%] translate-y-2.5 mb-1">Laporan</li>
                <li>
                    <x-nav-link :href="route('admin-laporan')" :active="request()->routeIs('admin-laporan') || request()->routeIs('admin.laporan.*')">
                        <x-slot name="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2" class="w-5 h-5">
                                <rect x="3" y="3" width="7" height="7" rx="1" />
                                <rect x="14" y="3" width="7" height="7" rx="1" />
                                <rect x="3" y="14" width="7" height="7" rx="1" />
                                <rect x="14" y="14" width="7" height="7" rx="1" />
                            </svg>
                        </x-slot>
                        Laporan Magang
                    </x-nav-link>
                </li>

                @include('components.button-logout')
            </ul>
        </nav>
    </section>

    @include('components.button-close-sidebar')

</section>
