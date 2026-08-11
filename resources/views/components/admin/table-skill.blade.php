<!-- Alert Sukses (Akan muncul ketika ada session 'success' dari controller) -->
@vite(['resources/js/fitur-search.js'])
@if (session('success'))
    <div class="mb-6 p-4 bg-success/10 border border-success rounded-lg flex items-center gap-3 shadow-sm font-montserrat">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-success shrink-0" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        <span class="text-sm font-medium text-success">{{ session('success') }}</span>
    </div>
@endif

<div class="bg-surface rounded-[10px] shadow-sm border border-border overflow-hidden font-montserrat">

    <!-- Header Bagian Atas Tabel (Opsional: Jika ingin ada tombol Tambah) -->
    <div class="p-1 border-b border-border flex justify-between items-center bg-surface">
        <div class="relative w-full sm:w-72 md:w-80">
        <!-- Ikon Kaca Pembesar -->
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-text" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>

        <input type="text" name="search" placeholder="Cari data..."
            class="input-search w-full pl-9 pr-4 py-2 bg-surface border border-border rounded-lg text-sm text-text focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors shadow-sm">
    </div>

        <!-- Tombol Tambah Skill -->
        <x-main-button href="{{ route('create-skill') }}"
            class="bg-primary text-xs px-4 py-2 rounded-lg text-white transition-colors shadow-sm inline-flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            <span>Tambah skill</span>
        </x-main-button>
    </div>

    <!-- Container Tabel -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-background border-b border-border text-text-light text-sm uppercase tracking-wider">
                <tr>
                    <th scope="col" class="px-6 py-4 font-semibold text-center w-16">No</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Nama Skill</th>
                    <th scope="col" class="px-6 py-4 font-semibold text-center w-48">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-border">
                @forelse ($dataSkill as $skill)
                    <tr class="data-row hover:bg-background/50 transition-colors duration-200 group">

                        <!-- Nomor Urut Otomatis menggunakan $loop->iteration -->
                        <td class="px-6 py-4 text-sm text-text-light text-center">
                            {{ $loop->iteration }}
                        </td>

                        <!-- Nama Skill -->
                        <td class="px-6 py-4">
                            <span class="text-sm font-medium text-text">
                                {{ $skill->nama_skill }}
                            </span>
                        </td>

                        <!-- Kolom Aksi (Edit & Hapus) -->
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">

                                <!-- Tombol Edit -->
                                <x-main-button
                                    class="bg-primary text-xs px-4 py-2 rounded-lg text-white transition-colors shadow-sm inline-flex items-center gap-2"
                                    href="{{ route('admin-skill-edit', $skill->id) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    <span>Edit</span>
                                </x-main-button>

                                <!-- Tombol Hapus (Berada di dalam Form) -->
                                <form action="{{ route('admin-skill-destroy', $skill->id) }}" method="POST" class="m-0 inline-block"
                                    onsubmit="return confirm('Yakin ingin menghapus skill ini? Semua data terkait akan ikut terhapus dan tidak bisa dikembalikan.')">
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
                        <td colspan="3" class="px-6 py-12 text-center text-text-light">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-3 text-border"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <p class="font-medium text-text">Belum ada data skill</p>
                            <p class="text-sm mt-1">Silakan tambahkan data skill baru.</p>
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
