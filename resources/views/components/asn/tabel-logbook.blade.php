{{-- {{ $daftarMahasiswa }} --}}
<div class="bg-surface border border-border rounded-[10px] shadow-sm overflow-hidden font-montserrat">

    <!-- Wrapper dengan overflow-x-auto agar tabel bisa di-scroll horizontal di layar HP -->
    <div class="overflow-x-auto hide-scrollbar">
        <table class="w-full text-left border-collapse whitespace-nowrap">

            <!-- Table Header -->
            <thead class="bg-background border-b border-border">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold text-text-light uppercase tracking-wider">Nama Mahasiswa</th>
                    <th class="px-6 py-4 text-xs font-bold text-text-light uppercase tracking-wider">Jurusan</th>
                    <th class="px-6 py-4 text-xs font-bold text-text-light uppercase tracking-wider text-center">Tugas
                        Aktif</th>
                    <th class="px-6 py-4 text-xs font-bold text-text-light uppercase tracking-wider text-center">Tugas
                        Selesai</th>
                    <th class="px-6 py-4 text-xs font-bold text-text-light uppercase tracking-wider text-right">Aksi</th>
                </tr>
            </thead>

            <!-- Table Body -->
            <tbody class="divide-y divide-border">
                @forelse ($daftarMahasiswa as $mhs)
                    <tr class="data-row hover:bg-primary/5 transition-colors duration-200">

                        <!-- Kolom Nama -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <!-- Avatar Inisial -->
                                <div
                                    class="w-8 h-8 rounded-full bg-primary/10 text-primary-dark flex items-center justify-center text-xs font-bold shrink-0 border border-primary/20">
                                    {{ strtoupper(substr($mhs->user->name, 0, 1)) }}
                                </div>
                                <span class="text-sm font-bold text-text">{{ $mhs->user->name }}</span>
                            </div>
                        </td>

                        <!-- Kolom Jurusan -->
                        <td class="px-6 py-4">
                            <span class="text-sm text-text-light">
                                {{ $mhs->jurusan ?? '-' }}
                            </span>
                        </td>

                        <!-- Kolom Jumlah Tugas Aktif -->
                        <td class="px-6 py-4 text-center">
                            @if ($mhs->jumlah_tugas_aktif > 0)
                                <span
                                    class="inline-flex items-center justify-center min-w-[2rem] px-2 py-1 bg-warning/10 text-warning-dark text-xs font-bold rounded-full border border-warning/20">
                                    {{ $mhs->jumlah_tugas_aktif }}
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center justify-center min-w-[2rem] px-2 py-1 bg-background border border-border text-text-light text-xs font-bold rounded-full">
                                    0
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if ($mhs->jumlah_tugas_selesai > 0)
                                <span
                                    class="inline-flex items-center justify-center min-w-[2rem] px-2 py-1 bg-warning/10 text-warning-dark text-xs font-bold rounded-full border border-warning/20">
                                    {{ $mhs->jumlah_tugas_selesai }}
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center justify-center min-w-[2rem] px-2 py-1 bg-background border border-border text-text-light text-xs font-bold rounded-full">
                                    0
                                </span>
                            @endif
                        </td>



                        <!-- Kolom Aksi -->
                        <td class="px-6 py-4 text-right">
                            <x-main-button href="{{ route('asn-logbook-mahasiswa-kalender', $mhs->id) }}"
                                class="bg-surface hover:bg-primary hover:text-white hover:border-primary text-text border border-border text-[10px] px-3 py-1.5 rounded-lg transition-all shadow-sm inline-flex justify-center items-center gap-1.5 group">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-3.5 w-3.5 text-primary group-hover:text-white transition-colors"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>Lihat Logbook</span>
                            </x-main-button>
                        </td>

                    </tr>
                @empty
                    <!-- Tampilan Jika Data Kosong -->
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-border mb-3"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <p class="text-sm font-semibold text-text">Belum Ada Data Mahasiswa</p>
                                <p class="text-xs text-text-light mt-1">Daftar mahasiswa magang akan muncul di sini.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
        <div id="noDataMessage" class="hidden text-center py-8 text-text-light italic">
            Data tidak ditemukan.
        </div>
    </div>
</div>
