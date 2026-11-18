{{-- container luar --}}
@php
    $asnSidebarProfileId = Auth::user()?->asnProfile?->id;
    $jumlahPermintaanBimbinganAsn = $asnSidebarProfileId
        ? \App\Models\MahasiswaProfile::query()
            ->where('calon_asn_profile_id', $asnSidebarProfileId)
            ->where('status_pengajuan', 'menunggu_asn')
            ->where(function ($q) {
                $q->whereNull('kode_kelompok')->orWhere('is_ketua_kelompok', true);
            })
            ->count()
        : 0;
@endphp

<section class="fixed w-60 h-full p-2 z-20 sidebar-mobile">

    {{-- container dalam --}}
    <section class="h-full w-full bg-text container-dalam relative overflow-y-auto">

        {{-- container logo bps --}}
        @include('components.icon-sidebar')

        <nav class="mt-8 pb-6">
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
                        Beranda
                    </x-nav-link>
                </li>

                <!-- Menu Permintaan Bimbingan (Baru) -->
                <li>
                    <x-nav-link :href="route('asn-permintaan-bimbingan')" :active="request()->routeIs('asn-permintaan-bimbingan') || request()->routeIs('asn.permintaan.*')">
                        <x-slot name="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </x-slot>
                        <span class="flex items-center justify-between w-full gap-2">
                            <span>Permintaan Bimbingan</span>
                            @if ($jumlahPermintaanBimbinganAsn > 0)
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-danger text-white">
                                    {{ $jumlahPermintaanBimbinganAsn }}
                                </span>
                            @endif
                        </span>
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

                <li class="text-bold font-black text-white/50 text-[10px] ml-[5%] translate-y-2.5 mb-1">Management Tugas</li>
                <li>
                    <x-nav-link :href="route('asn-create-task-form')" :active="request()->routeIs('asn-create-task-form')">
                        <x-slot name="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 3h6a1 1 0 011 1v2h2a2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2h2V4a1 1 0 011-1z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 11v6M9 14h6" />
                            </svg>
                        </x-slot>
                        Buat tugas
                    </x-nav-link>
                </li>

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

                <li>
                    <x-nav-link :href="route('asn-submission-index')" :active="request()->routeIs('asn-submission-index')">
                        <x-slot name="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M14 2H7a2 2 0 00-2 2v16a2 2 0 002 2h10a2 2 0 002-2V7l-5-5z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 17V9" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l3-3 3 3" />
                            </svg>
                        </x-slot>
                        Pengumpulan
                    </x-nav-link>
                </li>

                @include('components.button-logout')
            </ul>
        </nav>
    </section>

    @include('components.button-close-sidebar')

</section>
