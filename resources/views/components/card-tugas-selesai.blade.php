<div class="font-montserrat">
    @if ($tugasSelesai->isEmpty())
        <!-- Tampilan Jika Data Tugas Selesai Kosong -->
        <div class="py-16 bg-surface border border-border rounded-2xl text-center shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 mx-auto text-border mb-4" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="text-lg font-bold text-text">Belum Ada Tugas Selesai</h3>
            <p class="text-sm text-text-light mt-1">Riwayat tugas yang telah Anda selesaikan akan muncul di sini.</p>
        </div>
    @else
        {{-- ========================================================= --}}
        {{-- 1. TAMPILAN DESKTOP & TABLET (Tabel Tugas Selesai)       --}}
        {{-- ========================================================= --}}
        <div class="hidden md:block overflow-x-auto bg-surface border border-border rounded-[10px] shadow-sm">
            <table class="w-full text-left border-collapse">
                <thead class="bg-black/5 border-b border-border">
                    <tr>
                        <th
                            class="px-5 py-4 text-xs font-semibold text-text-light uppercase tracking-wider text-center w-12 align-middle">
                            No</th>
                        <th
                            class="px-5 py-4 text-xs font-semibold text-text-light uppercase tracking-wider w-1/2 align-middle">
                            Detail Tugas</th>
                        <th
                            class="px-5 py-4 text-xs font-semibold text-text-light uppercase tracking-wider text-center whitespace-nowrap align-middle">
                            Status</th>
                        <th
                            class="px-5 py-4 text-xs font-semibold text-text-light uppercase tracking-wider whitespace-nowrap align-middle">
                            Diselesaikan Pada</th>
                        <th
                            class="px-5 py-4 text-xs font-semibold text-text-light uppercase tracking-wider text-center whitespace-nowrap align-middle">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach ($tugasSelesai as $tugas)
                        <tr class="hover:bg-black/[0.02] transition-colors duration-200 group">

                            <!-- Nomor -->
                            <td class="px-5 py-4 align-middle text-center text-xs font-semibold text-text-light">
                                {{ $loop->iteration }}
                            </td>

                            <!-- Detail Tugas (Judul & Deskripsi) -->
                            <td class="px-5 py-4 align-middle">
                                <h3
                                    class="text-sm font-bold text-text group-hover:text-primary transition-colors line-clamp-1">
                                    {{ $tugas->judul }}
                                </h3>
                                <p class="text-xs text-text-light line-clamp-2 mt-1 leading-relaxed">
                                    {{ $tugas->deskripsi }}
                                </p>
                            </td>

                            <!-- Status Badge -->
                            <td class="px-5 py-4 align-middle text-center whitespace-nowrap">
                                <span
                                    class="inline-flex px-2.5 py-1 bg-success/10 text-success text-[10px] font-bold rounded-md uppercase tracking-wide border border-success/20">
                                    {{ $tugas->status }}
                                </span>
                            </td>

                            <!-- Tanggal Diselesaikan -->
                            <td class="px-5 py-4 align-middle whitespace-nowrap">
                                <p class="text-xs font-bold text-success flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $tugas->selesai_at ? \Carbon\Carbon::parse($tugas->selesai_at)->translatedFormat('d M Y, H:i') : '-' }}
                                </p>
                            </td>

                            <!-- Button Lihat Detail -->
                            <td class="px-5 py-4 align-middle text-center whitespace-nowrap">
                                <x-buttonv2 href="{{ route('asn-tugas-selesai-detail', $tugas->id) }}"
                                    color="accent-dark" class="text-xs py-1.5 px-3 inline-flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 shrink-0">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Lihat Detail
                                </x-buttonv2>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- ========================================================= --}}
        {{-- 2. TAMPILAN MOBILE (Kartu Tugas Ringkas)                 --}}
        {{-- ========================================================= --}}
        <div class="grid grid-cols-1 gap-4 md:hidden">
            @foreach ($tugasSelesai as $tugas)
                <div
                    class="bg-surface border border-border rounded-[10px] p-4 shadow-sm flex flex-col justify-between gap-3">

                    <!-- Header Mobile: Status Badge & Waktu Diselesaikan -->
                    <div class="flex items-center justify-between border-b border-border/60 pb-2.5">
                        <span
                            class="inline-flex px-2.5 py-0.5 bg-success/10 text-success text-[10px] font-bold rounded-md uppercase tracking-wide border border-success/20">
                            {{ $tugas->status }}
                        </span>
                        <p class="text-xs font-bold text-success flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $tugas->selesai_at ? \Carbon\Carbon::parse($tugas->selesai_at)->translatedFormat('d M Y, H:i') : '-' }}
                        </p>
                    </div>

                    <!-- Judul & Deskripsi -->
                    <div>
                        <h3 class="text-base font-bold text-text leading-snug">
                            {{ $tugas->judul }}
                        </h3>
                        <p class="text-xs text-text-light mt-1.5 line-clamp-3 leading-relaxed">
                            {{ $tugas->deskripsi }}
                        </p>
                    </div>

                    <!-- Footer Mobile: Button Detail -->
                    <div class="pt-2 border-t border-border/60 flex items-center justify-end mt-1">
                        <x-buttonv2 href="{{ route('asn-tugas-selesai-detail', $tugas->id) }}" color="accent-dark"
                            class="w-full text-xs py-2 justify-center inline-flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-3.5 h-3.5 shrink-0">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Lihat Detail
                        </x-buttonv2>
                    </div>

                </div>
            @endforeach
        </div>

    @endif
</div>
