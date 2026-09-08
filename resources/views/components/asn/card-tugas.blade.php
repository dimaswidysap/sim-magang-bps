<div class="font-montserrat">
    @if ($tugasBelumSelesai->isEmpty())
        <!-- Tampilan Jika Data Tugas Kosong -->
        <div class="py-16 bg-surface border border-border rounded-2xl text-center shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 mx-auto text-border mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
            <h3 class="text-lg font-bold text-text">Belum Ada Tugas Tersedia</h3>
            <p class="text-sm text-text-light mt-1">Saat ini tidak ada tugas magang yang membutuhkan bantuan.</p>
        </div>
    @else

        {{-- ========================================================= --}}
        {{-- 1. TAMPILAN DESKTOP & TABLET (Tabel Tugas)                --}}
        {{-- ========================================================= --}}
        <div class="hidden md:block overflow-x-auto bg-surface border border-border rounded-[10px] shadow-sm">
            <table class="w-full text-left border-collapse">
                <thead class="bg-black/5 border-b border-border">
                    <tr>
                        <th class="px-5 py-4 text-xs font-semibold text-text-light uppercase tracking-wider text-center w-12 align-middle">No</th>
                        <th class="px-5 py-4 text-xs font-semibold text-text-light uppercase tracking-wider w-2/5 align-middle">Detail Tugas</th>
                        <th class="px-5 py-4 text-xs font-semibold text-text-light uppercase tracking-wider align-middle">Skill Dibutuhkan</th>
                        <th class="px-5 py-4 text-xs font-semibold text-text-light uppercase tracking-wider whitespace-nowrap align-middle">Tenggat Waktu</th>
                        <th class="px-5 py-4 text-xs font-semibold text-text-light uppercase tracking-wider text-center whitespace-nowrap align-middle">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach ($tugasBelumSelesai as $tugas)
                        <tr class="hover:bg-black/[0.02] transition-colors duration-200 group">

                            <!-- Nomor -->
                            <td class="px-5 py-4 align-middle text-center text-xs font-semibold text-text-light">
                                {{ $loop->iteration }}
                            </td>

                            <!-- Detail Tugas (Judul & Deskripsi) -->
                            <td class="px-5 py-4 align-middle">
                                <h3 class="text-sm font-bold text-text group-hover:text-primary transition-colors line-clamp-1">
                                    {{ $tugas->judul }}
                                </h3>
                                <p class="text-xs text-text-light line-clamp-2 mt-1 leading-relaxed">
                                    {{ $tugas->deskripsi }}
                                </p>
                            </td>

                            <!-- Skills Dibutuhkan -->
                            <td class="px-5 py-4 align-middle">
                                @if (isset($tugas->skills) && count($tugas->skills) > 0)
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach ($tugas->skills as $skill)
                                            <span class="inline-flex items-center px-2 py-0.5 bg-background border border-border rounded text-[10px] font-medium text-text-light whitespace-nowrap">
                                                {{ $skill->nama_skill }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-xs text-text-light italic">-</span>
                                @endif
                            </td>

                            <!-- Tenggat Waktu (Deadline) -->
                            <td class="px-5 py-4 align-middle whitespace-nowrap">
                                <p class="text-xs font-bold text-danger flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $tugas->deadline ? \Carbon\Carbon::parse($tugas->deadline)->translatedFormat('d M Y, H:i') : '-' }}
                                </p>
                            </td>

                            <!-- Aksi (Hapus & Edit) -->
                            <td class="px-5 py-4 align-middle text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Form Hapus -->
                                    <form method="POST"
                                        data-confirm="Yakin ingin menghapus data asn ini? Semua data terkait ikut terhapus dan tidak bisa dikembalikan!"
                                        action="{{ route('asn-tugas-destroy', $tugas->id) }}"
                                        class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <x-buttonv2 type="submit" color="danger" class="text-xs py-1.5 px-3">
                                            Hapus
                                        </x-buttonv2>
                                    </form>

                                    <!-- Button Lihat & Edit -->
                                    <x-buttonv2 href="{{ route('edit-tugas-form', $tugas->id) }}" color="accent-dark" class="text-xs py-1.5 px-3">
                                        Lihat & Edit
                                    </x-buttonv2>
                                </div>
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
            @foreach ($tugasBelumSelesai as $tugas)
                <div class="bg-surface border border-border rounded-[10px] p-4 shadow-sm flex flex-col justify-between gap-3">

                    <!-- Header Mobile: No & Deadline -->
                    <div class="flex items-center justify-between border-b border-border/60 pb-2.5">
                        <span class="text-xs font-bold text-text-light bg-black/5 px-2 py-0.5 rounded">
                            #{{ $loop->iteration }}
                        </span>
                        <p class="text-xs font-bold text-danger flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $tugas->deadline ? \Carbon\Carbon::parse($tugas->deadline)->translatedFormat('d M Y, H:i') : '-' }}
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

                    <!-- Skills -->
                    @if (isset($tugas->skills) && count($tugas->skills) > 0)
                        <div class="bg-background p-2.5 rounded-md border border-border/50">
                            <p class="text-[10px] text-text-light uppercase font-semibold mb-1.5">Skill Dibutuhkan:</p>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach ($tugas->skills as $skill)
                                    <span class="inline-flex items-center px-2 py-0.5 bg-surface border border-border rounded text-[10px] font-medium text-text-light">
                                        {{ $skill->nama_skill }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Footer Mobile: Buttons -->
                    <div class="pt-2 border-t border-border/60 flex items-center justify-end gap-2 mt-1">
                        <!-- Form Hapus -->
                        <form method="POST"
                            data-confirm="Yakin ingin menghapus data asn ini? Semua data terkait ikut terhapus dan tidak bisa dikembalikan!"
                            action="{{ route('asn-tugas-destroy', $tugas->id) }}"
                            class="m-0 flex-1 sm:flex-initial">
                            @csrf
                            @method('DELETE')
                            <x-buttonv2 type="submit" color="danger" class="w-full text-xs py-2 justify-center">
                                Hapus
                            </x-buttonv2>
                        </form>

                        <!-- Button Lihat & Edit -->
                        <x-buttonv2 href="{{ route('edit-tugas-form', $tugas->id) }}" color="accent-dark" class="w-full flex-1 sm:flex-initial text-xs py-2 justify-center">
                            Lihat & Edit
                        </x-buttonv2>
                    </div>

                </div>
            @endforeach
        </div>

    @endif
</div>
