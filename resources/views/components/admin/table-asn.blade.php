@vite(['resources/js/fitur-search.js'])

<!-- Alert Sukses -->
@if (session('success'))
    <div class="mb-6 p-4 bg-success/10 border border-success rounded-xl flex items-center gap-3 shadow-sm font-montserrat mt-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-success shrink-0" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        <span class="text-sm font-semibold text-success">{{ session('success') }}</span>
    </div>
@endif

<!-- Toolbar: Search & Action -->
<section class="w-full flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 font-montserrat">

    <!-- Input Pencarian -->
    <div class="relative w-full sm:max-w-xs md:max-w-sm">
        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-text-light" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <input type="text" name="search" placeholder="Cari data pegawai..."
            class="input-search w-full pl-10 pr-4 py-2.5 bg-surface border border-border rounded-xl text-sm text-text placeholder:text-text-light focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors shadow-sm">
    </div>

    <!-- Tombol Tambah Data -->
    <x-main-button href="{{ route('asn.mahasiswa.create') }}"
        class="w-full sm:w-auto bg-primary hover:bg-primary-dark text-xs px-5 py-2.5 rounded-xl text-white transition-colors shadow-sm inline-flex justify-center items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        <span>Tambah Data</span>
    </x-main-button>

</section>

<!-- Tabel Data ASN -->
<div class="bg-surface border border-border rounded-xl shadow-sm overflow-hidden font-montserrat w-full">
    <div class="w-full">
        <table class="w-full text-left border-collapse">

            <!-- Table Header (Hidden on Mobile) -->
            <thead class="hidden md:table-header-group bg-background/50 border-b border-border">
                <tr>
                    <th scope="col" class="px-6 py-4 text-xs font-bold text-text-light uppercase tracking-wider text-center w-16">No</th>
                    <th scope="col" class="px-6 py-4 text-xs font-bold text-text-light uppercase tracking-wider">Nama Pegawai</th>
                    <th scope="col" class="px-6 py-4 text-xs font-bold text-text-light uppercase tracking-wider">NIP</th>
                    <th scope="col" class="px-6 py-4 text-xs font-bold text-text-light uppercase tracking-wider">Jabatan</th>
                    <th scope="col" class="px-6 py-4 text-xs font-bold text-text-light uppercase tracking-wider">Unit Kerja</th>
                    <th scope="col" class="px-6 py-4 text-xs font-bold text-text-light uppercase tracking-wider text-center">Aksi</th>
                </tr>
            </thead>

            <!-- Table Body -->
            <tbody class="block md:table-row-group divide-y divide-border md:divide-y-0">
                @forelse ($dataAsn as $index => $asn)
                    <!-- Tr: Menjadi flex column di HP -->
                    <tr class="data-row block md:table-row flex-col p-4 md:p-0 hover:bg-primary/5 transition-colors duration-200 group md:border-b md:border-border last:border-b-0">

                        <!-- Kolom No -->
                        <td class="hidden md:table-cell px-6 py-4 text-sm text-text-light text-center">
                            {{ $index + 1 }}
                        </td>

                        <!-- Kolom Nama Pegawai -->
                        <td class="block md:table-cell md:px-6 md:py-4 mb-4 md:mb-0">
                            <div class="flex items-center gap-3">
                                <!-- Avatar Inisial -->
                                <div class="w-10 h-10 rounded-full bg-primary/10 text-primary-dark flex items-center justify-center text-xs font-bold shrink-0 border border-primary/20">
                                    {{ strtoupper(substr($asn->name ?? 'U', 0, 1)) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-text group-hover:text-primary transition-colors">{{ $asn->name ?? '-' }}</span>
                                    <span class="text-[10px] md:text-xs text-text-light mt-0.5">{{ $asn->email ?? 'Tidak ada email' }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- Kolom NIP -->
                        <td class="block md:table-cell md:px-6 md:py-4 mb-2 md:mb-0">
                            <div class="flex justify-between items-center md:block">
                                <span class="md:hidden text-[10px] font-bold text-text-light uppercase">NIP:</span>
                                <span class="text-xs md:text-sm font-mono font-semibold text-primary-dark bg-primary/10 px-2.5 py-1 rounded-md border border-primary/20">
                                    {{ $asn->asnProfile->nip ?? '-' }}
                                </span>
                            </div>
                        </td>

                        <!-- Kolom Jabatan -->
                        <td class="block md:table-cell md:px-6 md:py-4 mb-2 md:mb-0">
                            <div class="flex justify-between items-center md:block">
                                <span class="md:hidden text-[10px] font-bold text-text-light uppercase">Jabatan:</span>
                                <span class="text-sm font-medium text-text">
                                    {{ $asn->asnProfile->jabatan ?? '-' }}
                                </span>
                            </div>
                        </td>

                        <!-- Kolom Unit Kerja -->
                        <td class="block md:table-cell md:px-6 md:py-4 mb-4 md:mb-0">
                            <div class="flex justify-between items-center md:block">
                                <span class="md:hidden text-[10px] font-bold text-text-light uppercase">Unit Kerja:</span>
                                <span class="text-sm text-text-light">
                                    {{ $asn->asnProfile->unit_kerja ?? '-' }}
                                </span>
                            </div>
                        </td>

                        <!-- Kolom Aksi -->
                        <td class="block md:table-cell md:px-6 md:py-4 md:text-center mt-2 md:mt-0">
                            <x-main-button href="{{ route('admin-asn-detail', $asn->id) }}"
                                class="w-full md:w-auto bg-surface hover:bg-primary text-text hover:text-white border border-border hover:border-primary text-xs px-4 py-2 rounded-lg transition-all shadow-sm inline-flex justify-center items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 shrink-0">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>Detail</span>
                            </x-main-button>
                        </td>

                    </tr>
                @empty
                    <!-- Tampilan Jika Data Kosong -->
                    <tr class="block md:table-row">
                        <td colspan="6" class="block md:table-cell px-6 py-12 md:py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-14 h-14 rounded-full bg-border/30 text-text-light flex items-center justify-center mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <p class="text-base font-bold text-text">Belum Ada Data ASN</p>
                                <p class="text-sm text-text-light mt-1">Data pegawai yang ditambahkan akan muncul di sini.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pesan Tidak Ditemukan dari JavaScript -->
        <div id="noDataMessage" class="hidden text-center py-12">
            <div class="flex flex-col items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-text-light/50 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <p class="font-semibold text-text">Data Tidak Ditemukan</p>
                <p class="text-sm text-text-light mt-1">Coba gunakan kata kunci pencarian yang lain.</p>
            </div>
        </div>

    </div>
</div>
