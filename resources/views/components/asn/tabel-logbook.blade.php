<div class="bg-surface rounded-xl shadow-sm border border-border overflow-hidden font-montserrat w-full mb-8">

    <!-- Wrapper Table (Responsive Stacked) -->
    <div class="w-full">
        <table class="w-full text-left border-collapse">

            <!-- Table Header (Disembunyikan di HP) -->
            <thead
                class="hidden md:table-header-group bg-background/50 border-b border-border text-text-light text-xs uppercase tracking-wider">
                <tr>
                    <th scope="col" class="px-6 py-4 font-bold">Nama Mahasiswa</th>
                    <th scope="col" class="px-6 py-4 font-bold">Jurusan</th>
                    <th scope="col" class="px-6 py-4 font-bold text-center">Tugas Aktif</th>
                    <th scope="col" class="px-6 py-4 font-bold text-center">Tugas Selesai</th>
                    <th scope="col" class="px-6 py-4 font-bold text-center w-40">Aksi</th>
                </tr>
            </thead>

            <!-- Table Body -->
            <tbody class="block md:table-row-group divide-y divide-border md:divide-y-0">
                @forelse ($daftarMahasiswa as $mhs)
                    <!-- Tr: Menjadi tumpukan (flex-col) di HP -->
                    <tr
                        class="data-row block md:table-row flex-col p-4 md:p-0 hover:bg-primary/5 transition-colors duration-200 group md:border-b md:border-border last:border-b-0">

                        <!-- Kolom Nama -->
                        <td
                            class="block md:table-cell md:px-6 md:py-4 mb-3 md:mb-0 border-b border-border border-dashed md:border-none pb-3 md:pb-0">
                            <div class="flex justify-between md:justify-start items-center gap-3">
                                <span class="md:hidden text-[10px] font-bold text-text-light uppercase">Nama:</span>
                                <div class="flex items-center gap-3">
                                    <!-- Avatar Inisial -->
                                    <div
                                        class="w-9 h-9 rounded-full bg-primary/10 text-primary-dark flex items-center justify-center text-xs font-bold shrink-0 border border-primary/20">
                                        {{ strtoupper(substr($mhs->user->name, 0, 1)) }}
                                    </div>
                                    <span
                                        class="text-sm font-bold text-text group-hover:text-primary transition-colors">
                                        {{ $mhs->user->name }}
                                    </span>
                                </div>
                            </div>
                        </td>

                        <!-- Kolom Jurusan -->
                        <td class="block md:table-cell md:px-6 md:py-4 mb-2 md:mb-0">
                            <div class="flex justify-between items-center md:block">
                                <span class="md:hidden text-[10px] font-bold text-text-light uppercase">Jurusan:</span>
                                <span class="text-sm text-text-light" title="{{ $mhs->jurusan ?? '-' }}">
                                    {{ \Illuminate\Support\Str::limit($mhs->jurusan ?? '-', 25) }}
                                </span>
                            </div>
                        </td>

                        <!-- Kolom Jumlah Tugas Aktif (Warning) -->
                        <td class="block md:table-cell md:px-6 md:py-4 md:text-center mb-2 md:mb-0">
                            <div class="flex justify-between items-center md:block md:mx-auto">
                                <span class="md:hidden text-[10px] font-bold text-text-light uppercase">Tugas
                                    Aktif:</span>
                                @if ($mhs->jumlah_tugas_aktif > 0)
                                    <span
                                        class="inline-flex items-center justify-center min-w-[2rem] px-2 py-1 bg-warning/10 text-warning-dark text-xs font-bold rounded-full border border-warning/20 shadow-sm">
                                        {{ $mhs->jumlah_tugas_aktif }}
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center justify-center min-w-[2rem] px-2 py-1 bg-background text-text-light text-xs font-bold rounded-full border border-border">
                                        0
                                    </span>
                                @endif
                            </div>
                        </td>

                        <!-- Kolom Jumlah Tugas Selesai (Success) -->
                        <td class="block md:table-cell md:px-6 md:py-4 md:text-center mb-4 md:mb-0">
                            <div class="flex justify-between items-center md:block md:mx-auto">
                                <span class="md:hidden text-[10px] font-bold text-text-light uppercase">Tugas
                                    Selesai:</span>
                                @if ($mhs->jumlah_tugas_selesai > 0)
                                    <span
                                        class="inline-flex items-center justify-center min-w-[2rem] px-2 py-1 bg-success/10 text-success-dark text-xs font-bold rounded-full border border-success/20 shadow-sm">
                                        {{ $mhs->jumlah_tugas_selesai }}
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center justify-center min-w-[2rem] px-2 py-1 bg-background text-text-light text-xs font-bold rounded-full border border-border">
                                        0
                                    </span>
                                @endif
                            </div>
                        </td>

                        <!-- Kolom Aksi -->
                        <td
                            class="block md:table-cell md:px-6 md:py-4 md:text-center pt-4 md:pt-0 border-t border-border md:border-0">
                            <x-main-button href="{{ route('asn-logbook-mahasiswa-kalender', $mhs->id) }}"
                                class="w-full md:w-auto bg-surface text-primary border border-primary hover:bg-primary hover:text-white text-[11px] md:text-xs px-4 py-2 rounded-lg transition-all shadow-sm inline-flex justify-center items-center gap-1.5 group">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0 transition-colors"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>Lihat Logbook</span>
                            </x-main-button>
                        </td>

                    </tr>
                @empty
                    <!-- Tampilan Jika Data Kosong (Colspan diubah menjadi 5) -->
                    <tr class="block md:table-row">
                        <td colspan="5" class="block md:table-cell px-6 py-12 md:py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div
                                    class="w-14 h-14 rounded-full bg-border/30 text-text-light flex items-center justify-center mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <p class="text-base font-bold text-text">Belum Ada Data Mahasiswa</p>
                                <p class="text-[11px] md:text-sm text-text-light mt-1">Daftar mahasiswa magang akan
                                    muncul di sini.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>

        <!-- Pesan JS Pencarian Kosong -->
        <div id="noDataMessage" class="hidden text-center py-12">
            <div class="flex flex-col items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-text-light/50 mb-3" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <p class="font-semibold text-text">Data Tidak Ditemukan</p>
                <p class="text-sm text-text-light mt-1">Coba gunakan kata kunci pencarian yang lain.</p>
            </div>
        </div>

    </div>
</div>
