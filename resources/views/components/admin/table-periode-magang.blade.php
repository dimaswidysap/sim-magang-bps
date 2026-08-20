<!-- Alert Sukses -->
@if (session('success'))
    <div
        class="mb-6 p-4 bg-success/10 border border-success/30 rounded-xl flex items-center gap-3 shadow-sm font-montserrat">
        <div class="w-8 h-8 rounded-full bg-success/20 flex items-center justify-center shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-success shrink-0" viewBox="0 0 20 20"
                fill="currentColor">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd" />
            </svg>
        </div>
        <span class="text-sm font-semibold text-success">{{ session('success') }}</span>
    </div>
@endif

<!-- Komponen Card Tabel -->
<div class="bg-surface rounded-xl shadow-sm border border-border overflow-hidden font-montserrat w-full ">

    <!-- Header Tabel -->
    <div
        class="p-5 md:p-6 border-b border-border bg-background flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-base md:text-lg font-bold text-text">Data Periode Magang</h2>
            <p class="text-[11px] md:text-xs text-text-light mt-1">Kelola gelombang dan waktu pelaksanaan magang di
                lingkungan BPS.</p>
        </div>

        <!-- Tombol Tambah -->


        <x-buttonv2 href="{{ route('admin-periode-create') }}" color="accent-dark" class="w-full sm:w-auto">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Periode
        </x-buttonv2>
    </div>

    <!-- Container Tabel (Responsive Stacked) -->
    <div class="w-full">
        <table class="w-full text-left border-collapse">

            <!-- Table Header (Sembunyi di Mobile) -->
            <thead
                class="hidden md:table-header-group bg-background/50 border-b border-border text-text-light text-xs uppercase tracking-wider">
                <tr>
                    <th scope="col" class="px-6 py-4 font-bold text-center w-16">No</th>
                    <th scope="col" class="px-6 py-4 font-bold">Nama Periode</th>
                    <th scope="col" class="px-6 py-4 font-bold">Waktu Pelaksanaan</th>
                    <th scope="col" class="px-6 py-4 font-bold text-center">Kuota</th>
                    <th scope="col" class="px-6 py-4 font-bold text-center">Status</th>
                    <th scope="col" class="px-6 py-4 font-bold text-center w-56">Aksi</th>
                </tr>
            </thead>

            <!-- Table Body -->
            <tbody class="block md:table-row-group divide-y divide-border md:divide-y-0">
                @forelse ($periodeMagang as $periode)
                    <!-- Tr: Berubah jadi tumpukan (flex-col) di HP -->
                    <tr
                        class="block md:table-row flex-col p-4 md:p-0 hover:bg-primary/5 transition-colors duration-200 group md:border-b md:border-border last:border-b-0">

                        <!-- Nomor Urut -->
                        <td class="hidden md:table-cell px-6 py-4 text-sm text-text-light text-center">
                            {{ $loop->iteration }}
                        </td>

                        <!-- Nama Periode -->
                        <td
                            class="block md:table-cell md:px-6 md:py-0 mb-3 md:mb-0 border-b border-border border-dashed md:border-none pb-3 md:pb-0">
                            <div class="flex justify-between items-start md:block">
                                <span
                                    class="md:hidden text-[10px] font-bold text-text-light uppercase mt-0.5">Nama:</span>
                                <div>
                                    <p
                                        class="text-sm font-bold text-text group-hover:text-primary transition-colors text-right md:text-left">
                                        {{ $periode->nama_periode }}</p>
                                    @if ($periode->keterangan)
                                        <p class="text-[11px] text-text-light mt-0.5 md:line-clamp-1 text-right md:text-left"
                                            title="{{ $periode->keterangan }}">
                                            {{ $periode->keterangan }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Waktu Pelaksanaan -->
                        <td class="block md:table-cell md:px-6 md:py-4 mb-2 md:mb-0">
                            <div class="flex justify-between items-center md:block">
                                <span class="md:hidden text-[10px] font-bold text-text-light uppercase">Waktu:</span>
                                <div class="text-xs md:text-sm font-medium text-text text-right md:text-left">
                                    {{ \Carbon\Carbon::parse($periode->tanggal_mulai)->translatedFormat('d M Y') }}
                                    <span class="text-text-light mx-1">s.d</span>
                                    {{ \Carbon\Carbon::parse($periode->tanggal_selesai)->translatedFormat('d M Y') }}
                                </div>
                            </div>
                        </td>

                        <!-- Kuota -->
                        <td class="block md:table-cell md:px-6 md:py-4 md:text-center mb-3 md:mb-0">
                            <div class="flex justify-between items-center md:block md:mx-auto">
                                <span class="md:hidden text-[10px] font-bold text-text-light uppercase">Kuota:</span>
                                <span class="text-sm font-bold text-text">
                                    {{ $periode->kuota }} <span
                                        class="text-[10px] md:text-xs text-text-light font-medium">org</span>
                                </span>
                            </div>
                        </td>

                        <!-- Status Badge -->
                        <td class="block md:table-cell md:px-6 md:py-4 md:text-center mb-4 md:mb-0">
                            <div class="flex justify-between md:justify-center items-center">
                                <span class="md:hidden text-[10px] font-bold text-text-light uppercase">Status:</span>
                                @if ($periode->status === 'berlangsung')
                                    <span
                                        class="inline-flex px-2.5 py-1 bg-success/10 text-success-dark text-[10px] font-bold rounded-md uppercase tracking-wider border border-success/20 items-center gap-1.5 shadow-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-success animate-pulse"></span>
                                        Berlangsung
                                    </span>
                                @elseif ($periode->status === 'akan_datang')
                                    <span
                                        class="inline-flex px-2.5 py-1 bg-primary/10 text-primary-dark text-[10px] font-bold rounded-md uppercase tracking-wider border border-primary/20 items-center gap-1.5 shadow-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                                        Akan Datang
                                    </span>
                                @elseif ($periode->status === 'selesai')
                                    <span
                                        class="inline-flex px-2.5 py-1 bg-background text-text-light text-[10px] font-bold rounded-md uppercase tracking-wider border border-border items-center gap-1.5 shadow-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-text-light"></span>
                                        Selesai
                                    </span>
                                @else
                                    <span
                                        class="inline-flex px-2.5 py-1 bg-background text-text-light text-[10px] font-bold rounded-md uppercase tracking-wider border border-border items-center gap-1.5">
                                        {{ $periode->status }}
                                    </span>
                                @endif
                            </div>
                        </td>

                        <!-- Kolom Aksi -->
                        <td
                            class="block md:table-cell md:px-6 md:py-0 md:text-center pt-4 md:pt-0 border-t border-border md:border-0">
                            <div class="flex items-center justify-end md:justify-center gap-2">

                                <!-- Tombol Edit (Outline to Solid) -->
                                <x-main-button href="{{ route('admin-periode-edit', $periode->id) }}"
                                    class="bg-surface text-primary border border-text/20 hover:bg-primary hover:text-white text-[11px] md:text-xs px-3 md:px-4 py-2 rounded-lg transition-all shadow-sm inline-flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    <span>Edit</span>
                                </x-main-button>

                                <!-- Form Hapus (Outline to Solid) -->
                                <form method="POST" action="{{ route('admin-periode-destroy', $periode->id) }}"
                                    class="m-0 inline-block"
                                    data-confirm="Yakin ingin menghapus periode ini? Mahasiswa yang terhubung akan kehilangan keterkaitan periodenya.">
                                    @csrf
                                    @method('DELETE')

                                    <x-main-button type="submit"
                                        class="bg-surface text-danger border border-text/20 hover:bg-danger hover:text-white text-[11px] md:text-xs px-3 md:px-4 py-2 rounded-lg transition-all shadow-sm inline-flex items-center gap-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        <span>Hapus</span>
                                    </x-main-button>
                                </form>

                            </div>
                        </td>

                    </tr>
                @empty
                    <!-- Tampilan Jika Data Kosong -->
                    <tr class="block md:table-row">
                        <td colspan="6" class="block md:table-cell px-6 py-12 md:py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div
                                    class="w-14 h-14 rounded-full bg-border/30 text-text-light flex items-center justify-center mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <p class="text-sm md:text-base font-bold text-text">Belum Ada Data Periode</p>
                                <p class="text-[11px] md:text-sm text-text-light mt-1">Silakan tambahkan periode baru
                                    terlebih dahulu.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>
</div>
