@vite(['resources/js/fitur-search.js'])

<section class="w-full p-2  font-montserrat">
    <section class="container-dalam max-w-7xl mx-auto">

        <!-- Alert Sukses -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-success/10 border border-success/30 rounded-xl flex items-center gap-3 shadow-sm">
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

        <!-- Toolbar: Search & Action -->
        <section class="w-full flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">

            <!-- Kolom Pencarian (Search) -->
            <div class="relative w-full sm:max-w-xs md:max-w-sm">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-text-light" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" placeholder="Cari data mahasiswa..."
                    class="input-search w-full pl-10 pr-4 py-2.5 bg-surface border border-border rounded-md text-sm text-text placeholder:text-text-light focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors shadow-sm">
            </div>

            <!-- Tombol Tambah Data -->


            <x-buttonv2 href="{{ route('admin.mahasiswa.create') }}" color="accent-dark" class="w-full sm:w-auto">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
                    stroke="currentColor" class="w-4 h-4 shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Data
            </x-buttonv2>

        </section>

        <!-- Tabel Data Mahasiswa (Responsive Stacked) -->
        <div class="bg-surface rounded-xl shadow-sm border border-border overflow-hidden w-full">
            <div class="w-full">
                <table class="w-full text-left border-collapse">

                    <!-- Header Tabel (Hidden on Mobile) -->
                    <thead
                        class="hidden md:table-header-group bg-background/50 border-b border-border text-text-light text-xs uppercase tracking-wider">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-bold">N0</th>
                            <th scope="col" class="px-6 py-4 font-bold">Nama Mahasiswa</th>
                            <th scope="col" class="px-6 py-4 font-bold">NIM</th>
                            <th scope="col" class="px-6 py-4 font-bold">Instansi Asal</th>
                            <th scope="col" class="px-6 py-4 font-bold">Jurusan (Jenjang)</th>
                            <th scope="col" class="px-6 py-4 font-bold">Periode Magang</th>
                            <th scope="col" class="px-6 py-4 font-bold text-center">Status (Akun)</th>
                            <th scope="col" class="px-6 py-4 font-bold text-center">Aksi</th>
                        </tr>
                    </thead>

                    <!-- Body Tabel -->
                    <tbody class="block md:table-row-group divide-y divide-border md:divide-y-0">
                        @forelse ($dataMahasiswa as $index => $mhs)
                            <!-- Tr: Menjadi flex column di HP -->
                            <tr
                                class="data-row block md:table-row flex-col p-4 md:p-0 hover:bg-primary/5 transition-colors duration-200 group md:border-b md:border-border last:border-b-0">

                                <td class="hidden md:table-cell px-6 py-4 text-sm text-text-light text-center">
                                    {{ $index + 1 }}
                                </td>

                                <!-- Nama (Ditambah Avatar Inisial) -->
                                <td class="block md:table-cell md:px-6 md:py-4 mb-4 md:mb-0">
                                    <div class="flex justify-between md:justify-start items-center gap-3">
                                        <span
                                            class="md:hidden text-[10px] font-bold text-text-light uppercase">Nama:</span>
                                        <div class="flex items-center gap-3">
                                            <!-- Avatar -->
                                            <div
                                                class="hidden md:flex w-9 h-9 rounded-full bg-primary/10 text-primary flex-shrink-0 items-center justify-center text-xs font-bold border border-primary/20">
                                                {{ strtoupper(substr($mhs->name, 0, 1)) }}
                                            </div>
                                            <span
                                                class="text-sm font-bold text-text group-hover:text-primary transition-colors">
                                                {{ $mhs->name }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <!-- NIM -->
                                <td class="block md:table-cell md:px-6 md:py-4 mb-2 md:mb-0">
                                    <div class="flex justify-between items-center md:block">
                                        <span
                                            class="md:hidden text-[10px] font-bold text-text-light uppercase">NIM:</span>
                                        <span
                                            class="text-xs font-mono text-primary-dark font-semibold bg-primary/10 border border-primary/20 px-2 py-1 rounded-md">
                                            {{ $mhs->mahasiswaProfile->nim ?? '-' }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Instansi Asal -->
                                <td class="block md:table-cell md:px-6 md:py-4 mb-2 md:mb-0">
                                    <div class="flex justify-between items-center md:block">
                                        <span
                                            class="md:hidden text-[10px] font-bold text-text-light uppercase">Instansi:</span>
                                        <span class="text-sm text-text-light">
                                            {{ $mhs->mahasiswaProfile->instansi_asal ?? '-' }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Jurusan & Jenjang -->
                                <td class="block md:table-cell md:px-6 md:py-4 mb-2 md:mb-0">
                                    <div
                                        class="flex justify-between md:flex-col md:justify-center items-center md:items-start">
                                        <span
                                            class="md:hidden text-[10px] font-bold text-text-light uppercase">Jurusan:</span>
                                        <div class="text-right md:text-left">
                                            <p class="text-sm font-medium text-text">
                                                {{ $mhs->mahasiswaProfile->jurusan ?? '-' }}</p>
                                            <p class="text-[11px] text-text-light mt-0.5">
                                                ({{ $mhs->mahasiswaProfile->jenjang ?? '-' }})
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Periode Magang -->
                                <td class="block md:table-cell md:px-6 md:py-4 mb-4 md:mb-0">
                                    <div class="flex justify-between items-center md:block">
                                        <span
                                            class="md:hidden text-[10px] font-bold text-text-light uppercase">Periode:</span>
                                        <div class="flex flex-col text-right md:text-left text-xs text-text-light">
                                            <span
                                                class="font-medium text-text">{{ $mhs->tanggal_mulai ? \Carbon\Carbon::parse($mhs->tanggal_mulai)->translatedFormat('d M Y') : '-' }}</span>
                                            <span class="text-[10px] my-0.5">s.d</span>
                                            <span
                                                class="font-medium text-text">{{ $mhs->tanggal_selesai ? \Carbon\Carbon::parse($mhs->tanggal_selesai)->translatedFormat('d M Y') : '-' }}</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Status Badge -->
                                <td class="block md:table-cell md:px-6 md:py-4 md:text-center mt-2 md:mt-0">
                                    <div class="flex justify-between md:justify-center items-center">
                                        <span
                                            class="md:hidden text-[10px] font-bold text-text-light uppercase">Status:</span>
                                        @if ($mhs->is_active)
                                            <span
                                                class="inline-flex px-3 py-1 bg-success/10 border border-success/20 text-success-dark text-xs font-bold rounded-full items-center gap-1.5 shadow-sm">
                                                <span class="w-1.5 h-1.5 rounded-full bg-success animate-pulse"></span>
                                                Aktif
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex px-3 py-1 bg-danger/10 border border-danger/20 text-danger-dark text-xs font-bold rounded-full items-center gap-1.5 shadow-sm">
                                                <span class="w-1.5 h-1.5 rounded-full bg-danger"></span>
                                                Nonaktif
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Aksi -->
                                <td
                                    class="block md:table-cell md:px-6 md:py-0 md:text-center mt-4 md:mt-0  pt-4 md:pt-0 border-t border-border md:border-0">
                                    <x-main-button href="{{ route('admin-mahasiswa-detail', $mhs->id) }}"
                                        class="w-full md:w-auto bg-surface hover:bg-primary text-text hover:text-white border border-border hover:border-primary text-xs px-4 py-2 rounded-lg transition-all shadow-sm inline-flex justify-center items-center gap-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2" stroke="currentColor" class="w-4 h-4 shrink-0">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span>Detail</span>
                                    </x-main-button>
                                </td>

                            </tr>
                        @empty
                            <!-- State Kosong -->
                            <tr class="block md:table-row">
                                <td colspan="7" class="block md:table-cell px-6 py-12 md:py-16 text-center">
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
                                        <p class="text-sm text-text-light mt-1">Tambahkan data mahasiswa magang
                                            terlebih dahulu.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pesan Tidak Ditemukan (JS Search) -->
                <div id="noDataMessage" class="hidden text-center py-12">
                    <div class="flex flex-col items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-text-light/50 mb-3"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <p class="font-semibold text-text">Data Tidak Ditemukan</p>
                        <p class="text-sm text-text-light mt-1">Coba gunakan kata kunci pencarian yang lain.</p>
                    </div>
                </div>

            </div>
        </div>

    </section>
</section>
