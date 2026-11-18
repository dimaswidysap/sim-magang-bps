@vite(['resources/js/fitur-search.js'])

@php
    $kategoriAktif = $kategori ?? 'semua';
    $tahunAktif = $tahun ?? '';
    $tanggalAktif = $tanggal ?? '';
    $searchAktif = $search ?? request('search', '');
    $daftarTahunList = $daftarTahun ?? collect([(int) date('Y')]);

    $countSemua = $jumlahSemua ?? $dataMahasiswa->total();
    $countAktif = $jumlahAktif ?? 0;
    $countPending = $jumlahPending ?? 0;
    $countSelesai = $jumlahSelesai ?? 0;
    $countDibatalkan = $jumlahDibatalkan ?? 0;

    $adaFilterAktif = $kategoriAktif !== 'semua' || $tahunAktif !== '' || $tanggalAktif !== '' || $searchAktif !== '';
@endphp

<section class="w-full p-2 md:p-4 font-montserrat">
    <section class="container-dalam max-w-7xl mx-auto space-y-5">

        <!-- Header & Tombol Tambah -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-text">Daftar Peserta Magang</h1>
                <p class="text-xs md:text-sm text-text-light mt-0.5">
                    Menampilkan seluruh data peserta magang (Aktif, Pending, Selesai, maupun Dibatalkan).
                </p>
            </div>

            <x-buttonv2 href="{{ route('admin.mahasiswa.create') }}" color="accent-dark" class="w-full sm:w-auto shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
                    stroke="currentColor" class="w-4 h-4 shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Data
            </x-buttonv2>
        </div>

        <!-- Filter Tab Kategori Status Magang -->
        <div class="flex flex-wrap items-center gap-2 border-b border-border pb-3">
            <a href="{{ route('admin-mahasiswa', array_filter(['kategori' => 'semua', 'tahun' => $tahunAktif, 'tanggal' => $tanggalAktif, 'search' => $searchAktif])) }}"
                class="px-4 py-2 rounded-lg text-xs font-bold transition-colors {{ $kategoriAktif === 'semua' ? 'bg-primary text-white shadow-xs' : 'bg-surface border border-border text-text-light hover:text-text' }}">
                Semua ({{ $countSemua }})
            </a>

            <a href="{{ route('admin-mahasiswa', array_filter(['kategori' => 'aktif', 'tahun' => $tahunAktif, 'tanggal' => $tanggalAktif, 'search' => $searchAktif])) }}"
                class="px-4 py-2 rounded-lg text-xs font-bold transition-colors {{ $kategoriAktif === 'aktif' ? 'bg-success text-white shadow-xs' : 'bg-surface border border-border text-text-light hover:text-text' }}">
                Aktif ({{ $countAktif }})
            </a>

            <a href="{{ route('admin-mahasiswa', array_filter(['kategori' => 'pending', 'tahun' => $tahunAktif, 'tanggal' => $tanggalAktif, 'search' => $searchAktif])) }}"
                class="px-4 py-2 rounded-lg text-xs font-bold transition-colors {{ $kategoriAktif === 'pending' ? 'bg-warning text-white shadow-xs' : 'bg-surface border border-border text-text-light hover:text-text' }}">
                Pending ({{ $countPending }})
            </a>

            <a href="{{ route('admin-mahasiswa', array_filter(['kategori' => 'selesai', 'tahun' => $tahunAktif, 'tanggal' => $tanggalAktif, 'search' => $searchAktif])) }}"
                class="px-4 py-2 rounded-lg text-xs font-bold transition-colors {{ $kategoriAktif === 'selesai' ? 'bg-info text-white shadow-xs' : 'bg-surface border border-border text-text-light hover:text-text' }}">
                Selesai ({{ $countSelesai }})
            </a>

            <a href="{{ route('admin-mahasiswa', array_filter(['kategori' => 'dibatalkan', 'tahun' => $tahunAktif, 'tanggal' => $tanggalAktif, 'search' => $searchAktif])) }}"
                class="px-4 py-2 rounded-lg text-xs font-bold transition-colors {{ $kategoriAktif === 'dibatalkan' ? 'bg-danger text-white shadow-xs' : 'bg-surface border border-border text-text-light hover:text-text' }}">
                Dibatalkan ({{ $countDibatalkan }})
            </a>
        </div>

        <!-- Toolbar Filter: Pencarian, Tanggal, dan Tahun -->
        <form method="GET" action="{{ route('admin-mahasiswa') }}"
            class="bg-surface border border-border rounded-xl p-4 shadow-xs">
            <input type="hidden" name="kategori" value="{{ $kategoriAktif }}">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-end">
                <!-- Pencarian -->
                <div class="lg:col-span-5">
                    <label for="filter-search-mhs" class="block text-[11px] font-bold uppercase text-text-light mb-1">
                        Cari Nama / NIM / Instansi / Jurusan
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-text-light" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input id="filter-search-mhs" type="text" name="search" value="{{ $searchAktif }}"
                            placeholder="Ketik kata kunci..."
                            class="input-search w-full pl-10 pr-4 py-2 bg-background border border-border rounded-lg text-sm text-text placeholder:text-text-light focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                    </div>
                </div>

                <!-- Filter Tanggal -->
                <div class="lg:col-span-3">
                    <label for="filter-tanggal-mhs" class="block text-[11px] font-bold uppercase text-text-light mb-1">
                        Filter Tanggal
                    </label>
                    <input id="filter-tanggal-mhs" type="date" name="tanggal" value="{{ $tanggalAktif }}"
                        class="w-full px-3 py-2 bg-background border border-border rounded-lg text-sm text-text focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                </div>

                <!-- Filter Tahun -->
                <div class="lg:col-span-2">
                    <label for="filter-tahun-mhs" class="block text-[11px] font-bold uppercase text-text-light mb-1">
                        Filter Tahun
                    </label>
                    <select id="filter-tahun-mhs" name="tahun"
                        class="w-full px-3 py-2 bg-background border border-border rounded-lg text-sm text-text focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                        <option value="">Semua Tahun</option>
                        @foreach ($daftarTahunList as $thn)
                            <option value="{{ $thn }}" {{ (string) $tahunAktif === (string) $thn ? 'selected' : '' }}>
                                {{ $thn }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tombol Terapkan / Reset -->
                <div class="lg:col-span-2 flex items-center gap-2">
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-primary hover:bg-primary-dark text-white text-xs font-bold rounded-lg transition-colors cursor-pointer shadow-xs">
                        Filter
                    </button>

                    @if ($adaFilterAktif)
                        <a href="{{ route('admin-mahasiswa') }}"
                            class="px-3 py-2 bg-background hover:bg-border/40 border border-border text-text-light hover:text-text text-xs font-semibold rounded-lg transition-colors"
                            title="Reset Semua Filter">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>

        <!-- Tabel Data Mahasiswa (Responsive Stacked) -->
        <div class="bg-surface rounded-xl shadow-sm border border-border overflow-hidden w-full">
            <div class="w-full overflow-x-auto">
                <table class="w-full text-left border-collapse">

                    <!-- Header Tabel (Hidden on Mobile) -->
                    <thead
                        class="hidden md:table-header-group bg-background/50 border-b border-border text-text-light text-xs uppercase tracking-wider">
                        <tr>
                            <th scope="col" class="px-4 py-4 font-bold text-center">No</th>
                            <th scope="col" class="px-4 py-4 font-bold">Nama Peserta</th>
                            <th scope="col" class="px-4 py-4 font-bold">NIM</th>
                            <th scope="col" class="px-4 py-4 font-bold">Instansi & Jurusan</th>
                            <th scope="col" class="px-4 py-4 font-bold">Periode Magang</th>
                            <th scope="col" class="px-4 py-4 font-bold text-center">Status Magang</th>
                            <th scope="col" class="px-4 py-4 font-bold text-center">Aksi</th>
                        </tr>
                    </thead>

                    <!-- Body Tabel -->
                    <tbody class="block md:table-row-group divide-y divide-border md:divide-y-0">
                        @forelse ($dataMahasiswa as $index => $mhs)
                            @php
                                $profil = $mhs->mahasiswaProfile;
                                $statusMagang = $profil?->status ?? 'pending';
                            @endphp
                            <tr
                                class="data-row block md:table-row flex-col p-4 md:p-0 hover:bg-primary/5 transition-colors duration-200 group md:border-b md:border-border last:border-b-0">

                                <!-- Nomor -->
                                <td class="hidden md:table-cell px-4 py-4 text-sm text-text-light text-center">
                                    {{ ($dataMahasiswa->firstItem() ?? 0) + $index }}
                                </td>

                                <!-- Nama & Avatar -->
                                <td class="block md:table-cell md:px-4 md:py-4 mb-3 md:mb-0">
                                    <div class="flex justify-between md:justify-start items-center gap-3">
                                        <span class="md:hidden text-[10px] font-bold text-text-light uppercase">Nama:</span>
                                        <div class="flex items-center gap-3">
                                            @if ($profil?->foto_profil_path)
                                                <img src="{{ asset('storage/' . $profil->foto_profil_path) }}"
                                                    alt="{{ $mhs->name }}"
                                                    class="hidden md:block w-9 h-9 rounded-full object-cover shrink-0 border border-primary/20">
                                            @elseif (!empty($mhs->avatar))
                                                <img src="{{ asset('storage/' . $mhs->avatar) }}"
                                                    alt="{{ $mhs->name }}"
                                                    class="hidden md:block w-9 h-9 rounded-full object-cover shrink-0 border border-primary/20">
                                            @else
                                                <div
                                                    class="hidden md:flex w-9 h-9 rounded-full bg-primary/10 text-primary shrink-0 items-center justify-center text-xs font-bold border border-primary/20">
                                                    {{ strtoupper(substr($mhs->name, 0, 1)) }}
                                                </div>
                                            @endif

                                            <div>
                                                <p class="text-sm font-bold text-text group-hover:text-primary transition-colors">
                                                    {{ $mhs->name }}
                                                </p>
                                                <p class="text-[11px] text-text-light">
                                                    {{ $mhs->email }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- NIM -->
                                <td class="block md:table-cell md:px-4 md:py-4 mb-2 md:mb-0">
                                    <div class="flex justify-between items-center md:block">
                                        <span class="md:hidden text-[10px] font-bold text-text-light uppercase">NIM:</span>
                                        <span
                                            class="text-xs font-mono text-primary-dark font-semibold bg-primary/10 border border-primary/20 px-2 py-1 rounded-md">
                                            {{ $profil->nim ?? '-' }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Instansi Asal & Jurusan -->
                                <td class="block md:table-cell md:px-4 md:py-4 mb-2 md:mb-0">
                                    <div class="flex justify-between md:flex-col md:justify-center items-center md:items-start">
                                        <span class="md:hidden text-[10px] font-bold text-text-light uppercase">Instansi:</span>
                                        <div class="text-right md:text-left">
                                            <p class="text-sm font-medium text-text">
                                                {{ $profil->instansi_asal ?? '-' }}
                                            </p>
                                            <p class="text-[11px] text-text-light mt-0.5">
                                                {{ $profil->jurusan ?? '-' }}
                                                @if (!empty($profil?->jenjang))
                                                    ({{ $profil->jenjang }})
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Periode Magang -->
                                <td class="block md:table-cell md:px-4 md:py-4 mb-2 md:mb-0">
                                    <div class="flex justify-between items-center md:block">
                                        <span class="md:hidden text-[10px] font-bold text-text-light uppercase">Periode:</span>
                                        @if ($profil?->tanggal_mulai || $profil?->tanggal_selesai)
                                            <div class="flex flex-col text-right md:text-left text-xs text-text-light">
                                                <span class="font-medium text-text">
                                                    {{ $profil->tanggal_mulai ? \Carbon\Carbon::parse($profil->tanggal_mulai)->translatedFormat('d M Y') : '-' }}
                                                </span>
                                                <span class="text-[10px]">s.d.</span>
                                                <span class="font-medium text-text">
                                                    {{ $profil->tanggal_selesai ? \Carbon\Carbon::parse($profil->tanggal_selesai)->translatedFormat('d M Y') : '-' }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-xs text-text-light italic">Belum diisi</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Status Magang & Status Akun -->
                                <td class="block md:table-cell md:px-4 md:py-4 md:text-center mt-2 md:mt-0">
                                    <div class="flex justify-between md:flex-col md:justify-center items-center gap-1">
                                        <span class="md:hidden text-[10px] font-bold text-text-light uppercase">Status:</span>

                                        @if ($statusMagang === 'aktif')
                                            <span class="inline-flex px-3 py-1 bg-success/10 border border-success/20 text-success-dark text-xs font-bold rounded-full items-center gap-1.5">
                                                <span class="w-1.5 h-1.5 rounded-full bg-success"></span>
                                                Aktif
                                            </span>
                                        @elseif ($statusMagang === 'selesai')
                                            <span class="inline-flex px-3 py-1 bg-info/10 border border-info/20 text-info-dark text-xs font-bold rounded-full items-center gap-1.5">
                                                <span class="w-1.5 h-1.5 rounded-full bg-info"></span>
                                                Selesai
                                            </span>
                                        @elseif ($statusMagang === 'dibatalkan')
                                            <span class="inline-flex px-3 py-1 bg-danger/10 border border-danger/20 text-danger-dark text-xs font-bold rounded-full items-center gap-1.5">
                                                <span class="w-1.5 h-1.5 rounded-full bg-danger"></span>
                                                Dibatalkan
                                            </span>
                                        @else
                                            <span class="inline-flex px-3 py-1 bg-warning/15 border border-warning/30 text-warning-dark text-xs font-bold rounded-full items-center gap-1.5">
                                                <span class="w-1.5 h-1.5 rounded-full bg-warning"></span>
                                                Pending
                                            </span>
                                        @endif

                                        @if (! $mhs->is_active)
                                            <span class="text-[10px] font-semibold text-danger">Akun Nonaktif</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Aksi -->
                                <td
                                    class="block md:table-cell md:px-4 md:py-4 md:text-center mt-4 md:mt-0 pt-4 md:pt-0 border-t border-border md:border-0">
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
                                        <p class="text-base font-bold text-text">Tidak Ada Data Peserta Magang</p>
                                        <p class="text-sm text-text-light mt-1">
                                            {{ $adaFilterAktif ? 'Tidak ada data yang cocok dengan filter kategori, tanggal, atau tahun yang dipilih.' : 'Tambahkan data peserta magang terlebih dahulu.' }}
                                        </p>
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
                        <p class="text-sm text-text-light mt-1">Coba gunakan kata kunci pencarian yang lain atau klik tombol Filter.</p>
                    </div>
                </div>
            </div>

            <!-- Footer Info & Pagination -->
            @if ($dataMahasiswa->total() > 0)
                <div class="px-4 py-3.5 bg-background/40 border-t border-border flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <p class="text-xs text-text-light">
                        Menampilkan <strong class="text-text">{{ $dataMahasiswa->firstItem() }}</strong>
                        s.d. <strong class="text-text">{{ $dataMahasiswa->lastItem() }}</strong>
                        dari total <strong class="text-text">{{ $dataMahasiswa->total() }}</strong> peserta magang
                    </p>
                    <div>
                        {{ $dataMahasiswa->links() }}
                    </div>
                </div>
            @endif
        </div>

    </section>
</section>
