@vite(['resources/js/fitur-search.js'])

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

<!-- Toolbar: Search & Action -->
<section class="w-full flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 font-montserrat">

    <!-- Kolom Pencarian (Search) -->
    <div class="relative w-full sm:max-w-xs md:max-w-sm">
        <!-- Ikon Kaca Pembesar -->
        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-text-light" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <input type="text" name="search" placeholder="Cari data skill..."
            class="input-search w-full pl-10 pr-4 py-2.5 bg-surface border border-border rounded-xl text-sm text-text placeholder:text-text-light focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors shadow-sm">
    </div>

    <!-- Tombol Tambah Data -->
    <x-main-button href="{{ route('create-skill') }}"
        class="w-full sm:w-auto bg-primary hover:bg-primary-dark text-xs px-5 py-2.5 rounded-xl text-white transition-colors shadow-sm inline-flex justify-center items-center gap-2 shrink-0">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24"
            stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
        <span>Tambah Skill</span>
    </x-main-button>

</section>

<!-- Komponen Card Tabel -->
<div class="bg-surface rounded-xl shadow-sm border border-border overflow-hidden font-montserrat w-full ">

    <!-- Container Tabel (Responsive Stacked) -->
    <div class="w-full">
        <table class="w-full text-left border-collapse">

            <!-- Table Header (Disembunyikan di Layar HP) -->
            <thead
                class="hidden md:table-header-group bg-background/50 border-b border-border text-text-light text-xs uppercase tracking-wider">
                <tr>
                    <th scope="col" class="px-6 py-4 font-bold text-center w-16">No</th>
                    <th scope="col" class="px-6 py-4 font-bold">Nama Skill</th>
                    <th scope="col" class="px-6 py-4 font-bold text-center w-56">Aksi</th>
                </tr>
            </thead>

            <!-- Table Body -->
            <tbody class="block md:table-row-group divide-y divide-border md:divide-y-0">
                @forelse ($dataSkill as $skill)
                    <!-- Tr: Menjadi tumpukan (flex-col) di layar HP -->
                    <tr
                        class="data-row block md:table-row flex-col p-4 md:p-0 hover:bg-primary/5 transition-colors duration-200 group md:border-b md:border-border last:border-b-0">

                        <!-- Nomor Urut -->
                        <td class="hidden md:table-cell px-6 py-4 text-sm text-text-light text-center">
                            {{ $loop->iteration }}
                        </td>

                        <!-- Nama Skill -->
                        <td class="block md:table-cell md:px-6 md:py-4 mb-3 md:mb-0">
                            <div class="flex justify-between items-center md:block">
                                <span class="md:hidden text-[10px] font-bold text-text-light uppercase">Nama
                                    Skill:</span>
                                <span class="text-sm font-bold text-text group-hover:text-primary transition-colors">
                                    {{ $skill->nama_skill }}
                                </span>
                            </div>
                        </td>

                        <!-- Kolom Aksi -->
                        <td
                            class="block md:table-cell md:px-6 md:py-0 md:text-center pt-3 md:pt-0 border-t border-border md:border-0">
                            <div class="flex items-center justify-end md:justify-center gap-2">

                                <!-- Tombol Edit (Outline) -->
                                <x-main-button href="{{ route('admin-skill-edit', $skill->id) }}"
                                    class="bg-surface text-primary border border-primary hover:bg-primary hover:text-white text-[11px] md:text-xs px-3 md:px-4 py-2 rounded-lg transition-all shadow-sm inline-flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    <span>Edit</span>
                                </x-main-button>

                                <!-- Tombol Hapus (Outline) -->
                                <form action="{{ route('admin-skill-destroy', $skill->id) }}" method="POST"
                                    class="m-0 inline-block"
                                    data-confirm="Yakin ingin menghapus skill ini? Semua data terkait akan ikut terhapus dan tidak bisa dikembalikan.">
                                    @csrf
                                    @method('DELETE')

                                    <x-main-button type="submit"
                                        class="bg-surface text-danger border border-danger hover:bg-danger hover:text-white text-[11px] md:text-xs px-3 md:px-4 py-2 rounded-lg transition-all shadow-sm inline-flex items-center gap-1.5">
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
                        <td colspan="3" class="block md:table-cell px-6 py-12 md:py-16 text-center text-text-light">
                            <div class="flex flex-col items-center justify-center">
                                <div
                                    class="w-14 h-14 rounded-full bg-border/30 text-text-light flex items-center justify-center mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                </div>
                                <p class="text-base font-bold text-text">Belum Ada Data Skill</p>
                                <p class="text-sm text-text-light mt-1">Silakan tambahkan data skill baru.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pesan Tidak Ditemukan dari JavaScript -->
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
