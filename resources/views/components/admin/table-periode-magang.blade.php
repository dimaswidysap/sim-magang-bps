<!-- Alert Sukses (Akan muncul ketika ada session 'success' dari controller) -->
@if (session('success'))
    <div
        class="mb-6 p-4 bg-success/10 border border-success rounded-lg flex items-center gap-3 shadow-sm font-montserrat">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-success shrink-0" viewBox="0 0 20 20"
            fill="currentColor">
            <path fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                clip-rule="evenodd" />
        </svg>
        <span class="text-sm font-medium text-success">{{ session('success') }}</span>
    </div>
@endif

<!-- Komponen Card Tabel -->
<div class="bg-surface rounded-[10px] shadow-sm border border-border overflow-hidden font-montserrat">

    <!-- Header Tabel -->
    <div class="p-5 border-b border-border flex justify-between items-center bg-surface">
        <div>
            <h2 class="text-lg font-bold text-text">Data Periode Magang</h2>
            <p class="text-sm text-text-light mt-0.5">Kelola gelombang dan waktu pelaksanaan magang.</p>
        </div>

        <!-- Tombol Tambah -->
        <x-main-button href="{{ route('admin-periode-create') }}"
            class="bg-primary text-xs px-4 py-2 rounded-lg text-white transition-colors shadow-sm inline-flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            <span>Tambah Periode</span>
        </x-main-button>
    </div>

    <!-- Container Tabel -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-background border-b border-border text-text-light text-sm uppercase tracking-wider">
                <tr>
                    <th scope="col" class="px-6 py-4 font-semibold text-center w-16">No</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Nama Periode</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Waktu Pelaksanaan</th>
                    <th scope="col" class="px-6 py-4 font-semibold text-center">Kuota</th>
                    <th scope="col" class="px-6 py-4 font-semibold text-center">Status</th>
                    <th scope="col" class="px-6 py-4 font-semibold text-center w-56">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-border">
                @forelse ($periodeMagang as $periode)
                    <tr class="hover:bg-background/50 transition-colors duration-200 group">

                        <!-- Nomor Urut -->
                        <td class="px-6 py-4 text-sm text-text-light text-center">
                            {{ $loop->iteration }}
                        </td>

                        <!-- Nama Periode & Keterangan -->
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-text">{{ $periode->nama_periode }}</p>
                            @if ($periode->keterangan)
                                <p class="text-xs text-text-light mt-0.5 line-clamp-1"
                                    title="{{ $periode->keterangan }}">
                                    {{ $periode->keterangan }}
                                </p>
                            @endif
                        </td>

                        <!-- Waktu Pelaksanaan -->
                        <td class="px-6 py-4 text-sm text-text whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($periode->tanggal_mulai)->translatedFormat('d M Y') }}
                            <span class="text-text-light mx-1">-</span>
                            {{ \Carbon\Carbon::parse($periode->tanggal_selesai)->translatedFormat('d M Y') }}
                        </td>

                        <!-- Kuota -->
                        <td class="px-6 py-4 text-sm text-text text-center font-medium">
                            {{ $periode->kuota }} <span class="text-xs text-text-light font-normal">org</span>
                        </td>

                        <!-- Status Badge -->
                        <td class="px-6 py-4 text-center">
                            @if ($periode->status === 'berlangsung')
                                <span
                                    class="inline-flex px-2.5 py-1 bg-success/10 text-success text-[10px] font-bold rounded-md uppercase tracking-wider border border-success/20">
                                    Berlangsung
                                </span>
                            @elseif ($periode->status === 'akan_datang')
                                <span
                                    class="inline-flex px-2.5 py-1 bg-primary/10 text-primary-dark text-[10px] font-bold rounded-md uppercase tracking-wider border border-primary/20">
                                    Akan Datang
                                </span>
                            @elseif ($periode->status === 'selesai')
                                <span
                                    class="inline-flex px-2.5 py-1 bg-background text-text-light text-[10px] font-bold rounded-md uppercase tracking-wider border border-border">
                                    Selesai
                                </span>
                            @else
                                <span
                                    class="inline-flex px-2.5 py-1 bg-background text-text-light text-[10px] font-bold rounded-md uppercase tracking-wider border border-border">
                                    {{ $periode->status }}
                                </span>
                            @endif
                        </td>

                        <!-- Kolom Aksi (Menggunakan Komponen Sesuai Instruksi) -->
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">

                                <!-- Tombol Edit -->
                                <x-main-button href="{{ route('admin-periode-edit', $periode->id) }}"
                                    class="bg-primary text-xs px-4 py-2 rounded-lg text-white transition-colors shadow-sm inline-flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    <span>Edit</span>
                                </x-main-button>

                                <!-- Tombol Hapus -->
                                <form method="POST" action="{{ route('admin-periode-destroy', $periode->id) }}"
                                    class="m-0 inline-block"
                                    data-confirm="Yakin ingin menghapus periode ini? Mahasiswa yang terhubung akan kehilangan keterkaitan periodenya.">
                                    @csrf
                                    @method('DELETE')

                                    <x-main-button
                                        class="bg-danger text-xs px-4 py-2 rounded-lg text-white transition-colors shadow-sm inline-flex items-center gap-2"
                                        type="submit">
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
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-text-light">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-3 text-border"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="font-medium text-text">Belum ada data periode magang</p>
                            <p class="text-sm mt-1">Silakan tambahkan periode baru terlebih dahulu.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
