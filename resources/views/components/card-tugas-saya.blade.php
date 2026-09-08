<div class="w-full font-montserrat">

    {{-- ========================================================= --}}
    {{-- 1. TAMPILAN DESKTOP & TABLET (Tabel Lengkap)             --}}
    {{-- ========================================================= --}}
    <div class="hidden md:block overflow-x-auto bg-surface border border-border rounded-[10px] shadow-sm">
        <table class="w-full text-left border-collapse">
            <thead class="bg-black/5 border-b border-border">
                <tr>
                    <th
                        class="px-5 py-4 text-xs font-semibold text-text-light uppercase tracking-wider text-center w-12 align-middle">
                        No</th>
                    <th
                        class="px-5 py-4 text-xs font-semibold text-text-light uppercase tracking-wider w-2/5 align-middle">
                        Informasi Tugas</th>
                    <th class="px-5 py-4 text-xs font-semibold text-text-light uppercase tracking-wider align-middle">
                        Status</th>
                    <th class="px-5 py-4 text-xs font-semibold text-text-light uppercase tracking-wider align-middle">
                        Tenggat Waktu</th>
                    <th class="px-5 py-4 text-xs font-semibold text-text-light uppercase tracking-wider align-middle">
                        Dibuat Oleh</th>
                    <th
                        class="px-5 py-4 text-xs font-semibold text-text-light uppercase tracking-wider text-center align-middle">
                        Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @forelse ($dataTugas as $tugas)
                    <tr class="hover:bg-black/[0.02] transition-colors duration-200 group">

                        <!-- Nomor -->
                        <td class="px-5 py-4 align-middle text-center text-xs font-semibold text-text-light">
                            {{ $loop->iteration }}
                        </td>

                        <!-- Judul & Deskripsi -->
                        <td class="px-5 py-4 align-middle">
                            <h3 class="text-base font-bold text-text mb-1 group-hover:text-primary transition-colors">
                                {{ $tugas->judul }}
                            </h3>
                            <p class="text-xs text-text-light line-clamp-2">
                                {{ $tugas->deskripsi }}
                            </p>
                        </td>

                        <!-- Status -->
                        <td class="px-5 py-4 align-middle">
                            @if (strtolower($tugas->status) === 'tersedia')
                                <span
                                    class="inline-flex px-2.5 py-1 bg-success/10 text-success text-xs font-bold rounded-md uppercase tracking-wide border border-success/20">
                                    {{ $tugas->status }}
                                </span>
                            @else
                                <span
                                    class="inline-flex px-2.5 py-1 bg-warning/10 text-warning text-xs font-bold rounded-md uppercase tracking-wide border border-warning/20">
                                    {{ $tugas->status ?? 'Unknown' }}
                                </span>
                            @endif
                        </td>

                        <!-- Tenggat Waktu -->
                        <td class="px-5 py-4 align-middle">
                            <p class="text-xs font-semibold text-danger flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $tugas->deadline ? \Carbon\Carbon::parse($tugas->deadline)->translatedFormat('d M Y, H:i') : '-' }}
                            </p>
                        </td>

                        <!-- ASN Pembuat -->
                        <td class="px-5 py-4 align-middle">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-primary-light/20 text-primary-dark flex items-center justify-center font-bold text-xs shrink-0">
                                    {{ strtoupper(substr($tugas->asn->name ?? 'A', 0, 1)) }}
                                </div>
                                <p class="text-sm font-medium text-text truncate">
                                    {{ $tugas->asn->name ?? 'Admin / ASN' }}
                                </p>
                            </div>
                        </td>

                        <!-- Aksi -->
                        <td class="px-5 py-4 align-middle text-center">
                            <x-buttonv2 href="{{ route('detail-tugas-saya', $tugas->id) }}" color="accent-dark"
                                class="inline-flex justify-center items-center gap-1.5 text-xs py-2">
                                Pengumpulan & Detail
                            </x-buttonv2>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-16 text-center align-middle">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 mx-auto text-border mb-4"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            <h3 class="text-lg font-bold text-text">Belum Ada Tugas</h3>
                            <p class="text-sm text-text-light mt-1">Saat ini tidak ada tugas magang yang tersedia.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ========================================================= --}}
    {{-- 2. TAMPILAN MOBILE (Kartu Stacked)                      --}}
    {{-- ========================================================= --}}
    <div class="grid grid-cols-1 gap-4 md:hidden">
        @forelse ($dataTugas as $tugas)
            <div
                class="bg-surface border border-border rounded-[10px] p-4 shadow-sm flex flex-col justify-center gap-3">

                {{-- Baris Atas Mobile: No, Status, & Tenggat --}}
                <div class="flex items-center justify-between border-b border-border/60 pb-2.5">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-text-light bg-black/5 px-2 py-0.5 rounded">
                            #{{ $loop->iteration }}
                        </span>
                        @if (strtolower($tugas->status) === 'tersedia')
                            <span
                                class="inline-flex px-2 py-0.5 bg-success/10 text-success text-[10px] font-bold rounded uppercase tracking-wide border border-success/20">
                                {{ $tugas->status }}
                            </span>
                        @else
                            <span
                                class="inline-flex px-2 py-0.5 bg-warning/10 text-warning text-[10px] font-bold rounded uppercase tracking-wide border border-warning/20">
                                {{ $tugas->status ?? 'Unknown' }}
                            </span>
                        @endif
                    </div>

                    <div class="text-right flex items-center">
                        <p class="text-[10px] font-semibold text-danger flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $tugas->deadline ? \Carbon\Carbon::parse($tugas->deadline)->translatedFormat('d M Y, H:i') : '-' }}
                        </p>
                    </div>
                </div>

                {{-- Judul & Deskripsi Mobile --}}
                <div class="flex flex-col justify-center">
                    <h3 class="text-base font-bold text-text mb-1">
                        {{ $tugas->judul }}
                    </h3>
                    <p class="text-xs text-text-light line-clamp-3">
                        {{ $tugas->deskripsi }}
                    </p>
                </div>

                {{-- Footer Mobile: Pembuat & Tombol Aksi --}}
                <div class="pt-2 border-t border-border/60 flex items-center justify-between gap-3 mt-1">
                    <div class="flex items-center gap-2 overflow-hidden">
                        <div
                            class="w-7 h-7 rounded-full bg-primary-light/20 text-primary-dark flex items-center justify-center font-bold text-[10px] shrink-0">
                            {{ strtoupper(substr($tugas->asn->name ?? 'A', 0, 1)) }}
                        </div>
                        <span class="text-xs font-medium text-text truncate">
                            {{ $tugas->asn->name ?? 'Admin / ASN' }}
                        </span>
                    </div>

                    <x-buttonv2 href="{{ route('detail-tugas-saya', $tugas->id) }}" color="accent-dark"
                        class="shrink-0 flex items-center gap-1 text-xs py-1.5 px-3">
                        Pengumpulan & Detail
                    </x-buttonv2>
                </div>

            </div>
        @empty
            <div
                class="py-12 bg-surface border border-border rounded-[10px] flex flex-col items-center justify-center text-center px-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-border mb-3" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                <h3 class="text-base font-bold text-text">Belum Ada Tugas</h3>
                <p class="text-xs text-text-light mt-1">Saat ini tidak ada tugas magang yang tersedia.</p>
            </div>
        @endforelse
    </div>

</div>
