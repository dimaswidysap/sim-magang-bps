@vite(['resources/js/fitur-search.js'])
<section class="w-full p-2 font-montserrat">
    <section class="container-dalam">

        <!-- Alert Sukses (Merespons session 'success' dari controller) -->
        @if (session('success'))
            <div class="mt-4 mb-2 p-4 bg-success/10 border border-success rounded-lg flex items-center gap-3 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-success shrink-0" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <span class="text-sm font-medium text-success">{{ session('success') }}</span>
            </div>
        @endif

        <section class="w-full flex flex-col sm:flex-row justify-between items-center gap-4  mb-8 font-montserrat">

            <!-- Kolom Pencarian (Search) -->
            <div class="relative w-full sm:w-72 md:w-80">
                <!-- Ikon Kaca Pembesar -->
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-text" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <input type="text" name="search" placeholder="Cari data..."
                    class="input-search w-full pl-9 pr-4 py-2 bg-surface border border-border rounded-lg text-sm text-text focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors shadow-sm">
            </div>

            <!-- Tombol Tambah Data -->
            <x-main-button
                class="w-full sm:w-auto bg-primary hover:bg-primary-dark text-xs px-4 py-2 rounded-lg text-white transition-colors shadow-sm inline-flex justify-center items-center gap-2 shrink-0"
                href="{{ route('admin.mahasiswa.create') }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-5 h-5 shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Tambah data</span>
            </x-main-button>

        </section>

        {{-- table --}}
        <div class="bg-surface rounded-2xl shadow-sm border border-border overflow-hidden font-montserrat">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <!-- Header Tabel -->
                    <thead
                        class="bg-background border-b border-border text-text-light text-sm uppercase tracking-wider">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-semibold">NIM</th>
                            <th scope="col" class="px-6 py-4 font-semibold">Nama</th>
                            <th scope="col" class="px-6 py-4 font-semibold">Instansi Asal</th>
                            <th scope="col" class="px-6 py-4 font-semibold">Jurusan (Jenjang)</th>
                            <th scope="col" class="px-6 py-4 font-semibold">Periode Magang</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center">Status</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>

                    <!-- Body Tabel -->
                    <tbody class="divide-y divide-border">
                        @forelse ($dataMahasiswa as $mhs)
                            <tr class="data-row hover:bg-background/50 transition-colors duration-200 group">

                                <!-- NIM -->
                                <td class="px-6 py-4">
                                    <span
                                        class="text-[12px] font-mono text-primary-dark font-medium bg-primary-light/20 px-2 py-1 rounded">
                                        {{ $mhs->mahasiswaProfile->nim ?? '-' }}
                                    </span>
                                </td>

                                <!-- Nama -->
                                <td class="px-6 py-4 text-[12px] font-medium text-text whitespace-nowrap">
                                    {{ $mhs->name }}
                                </td>

                                <!-- Instansi Asal -->
                                <td class="px-6 py-4 text-[12px] text-text">
                                    {{ $mhs->mahasiswaProfile->instansi_asal ?? '-' }}
                                </td>

                                <!-- Jurusan & Jenjang -->
                                <td class="px-6 py-4">
                                    <p class="text-[12px] font-medium text-text">
                                        {{ $mhs->mahasiswaProfile->jurusan ?? '-' }}</p>
                                    <p class="text-xs text-text-light mt-0.5">
                                        ({{ $mhs->mahasiswaProfile->jenjang ?? '-' }})
                                    </p>
                                </td>

                                <!-- Periode Magang -->
                                <td class="px-6 py-4 text-[12px] text-text flex flex-col justify-center">
                                    <span>{{ \Carbon\Carbon::parse($mhs->tanggal_mulai)->translatedFormat('d M Y') ?? '-' }}</span>
                                    <span class="text-text-light">s.d</span>
                                    <span>{{ \Carbon\Carbon::parse($mhs->tanggal_selesai)->translatedFormat('d M Y') ?? '-' }}</span>
                                </td>

                                <!-- Status Badge -->
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center">
                                        @if ($mhs->is_active)
                                            <span
                                                class="inline-flex px-3 py-1 bg-success text-surface text-xs font-semibold rounded-full items-center gap-2 shadow-sm">
                                                <span class="w-1.5 h-1.5 rounded-full bg-surface animate-pulse"></span>
                                                Aktif
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex px-3 py-1 bg-danger text-surface text-xs font-semibold rounded-full items-center gap-2 shadow-sm">
                                                <span class="w-1.5 h-1.5 rounded-full bg-surface"></span>
                                                Nonaktif
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Aksi -->
                                <td class="px-6 py-4 text-center">
                                    <x-main-button
                                        class="bg-primary hover:bg-primary-dark  text-xs px-4 py-2 rounded-lg text-white transition-colors shadow-sm inline-flex items-center gap-2"
                                        href="{{ route('admin-mahasiswa-detail', $mhs->id) }}">
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
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-text-light">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-3 text-border"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    <p class="font-medium text-text">Belum ada data mahasiswa</p>
                                    <p class="text-sm mt-1">Tambahkan data mahasiswa magang terlebih dahulu.</p>
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
    </section>
</section>
