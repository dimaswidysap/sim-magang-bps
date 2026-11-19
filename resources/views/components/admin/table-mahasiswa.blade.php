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

    $belumDikirimEmailList = $mahasiswaBelumDikirimEmail ?? collect();
    $adaFilterAktif = $kategoriAktif !== 'semua' || $tahunAktif !== '' || $tanggalAktif !== '' || $searchAktif !== '';
@endphp

<!-- Flash Alert -->
@if (session('success'))
    <div class="p-4 bg-surface border border-success/40 text-success rounded-xl text-sm font-semibold flex items-center gap-2.5 shadow-xs">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if (session('error'))
    <div class="p-4 bg-surface border border-danger/40 text-danger rounded-xl text-sm font-semibold flex items-center gap-2.5 shadow-xs">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
        </svg>
        <span>{{ session('error') }}</span>
    </div>
@endif

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

<!-- Tab / Panel Info Atas: Peserta Magang yang Belum Pernah Dikirimi Email -->
@if ($belumDikirimEmailList->isNotEmpty())
    <div class="bg-surface border border-warning/40 rounded-xl p-4 shadow-xs space-y-3">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div class="flex items-center gap-2.5">
                <span class="w-8 h-8 rounded-lg bg-warning/15 text-warning-dark flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </span>
                <div>
                    <h2 class="text-xs md:text-sm font-bold text-text flex items-center gap-2">
                        <span>Belum Dikirimi Email Penerimaan &amp; Info Login</span>
                        <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-warning/20 text-warning-dark border border-warning/30">
                            {{ $belumDikirimEmailList->count() }} Peserta
                        </span>
                    </h2>
                    <p class="text-[11px] text-text-light">
                        Daftar peserta berikut belum pernah menerima email pemberitahuan. Peserta otomatis hilang dari daftar ini setelah email dikirim.
                    </p>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap gap-2 max-h-36 overflow-y-auto pt-1">
            @foreach ($belumDikirimEmailList as $mhsBelum)
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-background border border-border text-xs">
                    <span class="w-2 h-2 rounded-full bg-warning shrink-0"></span>
                    <span class="font-semibold text-text">{{ $mhsBelum->name }}</span>
                    @if ($mhsBelum->mahasiswaProfile?->instansi_asal)
                        <span class="text-[11px] text-text-light">({{ $mhsBelum->mahasiswaProfile->instansi_asal }})</span>
                    @endif
                    <form method="POST" action="{{ route('admin.mahasiswa.kirim-email', $mhsBelum->id) }}"
                        data-confirm="Kirim email penerimaan dan informasi login ke {{ $mhsBelum->name }}?"
                        class="inline-flex">
                        @csrf
                        <button type="submit"
                            class="ml-1 px-2 py-0.5 rounded bg-primary/10 hover:bg-primary text-primary-dark hover:text-white text-[11px] font-bold transition-colors cursor-pointer"
                            title="Kirim Email ke {{ $mhsBelum->name }}">
                            Kirim
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
@endif

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
            <label for="filter-search-mhs" class="block text-[11px] font-bold uppercase text-text-light mb-1.5">
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
                    class="input-search w-full h-10 pl-10 pr-4 bg-background border border-border rounded-lg text-sm text-text placeholder:text-text-light focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
            </div>
        </div>

        <!-- Filter Tanggal -->
        <div class="lg:col-span-3">
            <label for="filter-tanggal-mhs" class="block text-[11px] font-bold uppercase text-text-light mb-1.5">
                Filter Tanggal
            </label>
            <input id="filter-tanggal-mhs" type="date" name="tanggal" value="{{ $tanggalAktif }}"
                class="w-full h-10 px-3 bg-background border border-border rounded-lg text-sm text-text focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
        </div>

        <!-- Filter Tahun -->
        <div class="lg:col-span-2">
            <label for="filter-tahun-mhs" class="block text-[11px] font-bold uppercase text-text-light mb-1.5">
                Filter Tahun
            </label>
            <select id="filter-tahun-mhs" name="tahun"
                class="w-full h-10 px-3 bg-background border border-border rounded-lg text-sm text-text focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
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
                class="flex-1 h-10 px-4 bg-primary hover:bg-primary-dark text-white text-xs font-bold rounded-lg transition-colors cursor-pointer shadow-xs">
                Filter
            </button>

            @if ($adaFilterAktif)
                <a href="{{ route('admin-mahasiswa') }}"
                    class="h-10 px-3 inline-flex items-center justify-center bg-background hover:bg-border/40 border border-border text-text-light hover:text-text text-xs font-semibold rounded-lg transition-colors"
                    title="Reset Semua Filter">
                    Reset
                </a>
            @endif
        </div>
    </div>
</form>

<!-- Tabel Data Mahasiswa (Responsive Stacked) -->
<div class="bg-surface rounded-xl shadow-xs border border-border overflow-hidden w-full">
    <div class="w-full overflow-x-auto">
        <table class="w-full text-left border-collapse">

            <!-- Header Tabel (Hidden on Mobile) -->
            <thead
                class="hidden md:table-header-group bg-background/50 border-b border-border text-text-light text-xs uppercase tracking-wider">
                <tr>
                    <th scope="col" class="px-4 py-3.5 font-bold text-center whitespace-nowrap">No</th>
                    <th scope="col" class="px-4 py-3.5 font-bold">Nama Peserta</th>
                    <th scope="col" class="px-4 py-3.5 font-bold whitespace-nowrap">NIM</th>
                    <th scope="col" class="px-4 py-3.5 font-bold">Instansi &amp; Jurusan</th>
                    <th scope="col" class="px-4 py-3.5 font-bold whitespace-nowrap">Periode Magang</th>
                    <th scope="col" class="px-4 py-3.5 font-bold text-center whitespace-nowrap">Status Magang</th>
                    <th scope="col" class="px-4 py-3.5 font-bold text-center whitespace-nowrap">Aksi</th>
                </tr>
            </thead>

            <!-- Body Tabel -->
            <tbody class="block md:table-row-group divide-y divide-border md:divide-y-0">
                @forelse ($dataMahasiswa as $index => $mhs)
                    @php
                        $profil = $mhs->mahasiswaProfile;
                        $statusMagang = $profil?->status ?? 'pending';
                        $jumlahEmailTerkirim = (int) ($mhs->email_sent_count ?? 0);
                    @endphp
                    <tr
                        class="data-row block md:table-row flex-col p-4 md:p-0 hover:bg-primary/5 transition-colors duration-200 group md:border-b md:border-border last:border-b-0">

                        <!-- Nomor -->
                        <td class="hidden md:table-cell px-4 py-3.5 text-sm text-text-light text-center whitespace-nowrap">
                            {{ ($dataMahasiswa->firstItem() ?? 0) + $index }}
                        </td>

                        <!-- Nama & Avatar -->
                        <td class="block md:table-cell md:px-4 md:py-3.5 mb-3 md:mb-0">
                            <div class="flex justify-between md:justify-start items-center gap-3">
                                <span class="md:hidden text-[10px] font-bold text-text-light uppercase">Nama:</span>
                                <div class="flex items-center gap-3 min-w-0">
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

                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-text group-hover:text-primary transition-colors truncate">
                                            {{ $mhs->name }}
                                        </p>
                                        <p class="text-[11px] text-text-light truncate">
                                            {{ $mhs->email }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- NIM -->
                        <td class="block md:table-cell md:px-4 md:py-3.5 mb-2 md:mb-0 whitespace-nowrap">
                            <div class="flex justify-between items-center md:block">
                                <span class="md:hidden text-[10px] font-bold text-text-light uppercase">NIM:</span>
                                <span
                                    class="text-xs font-mono text-primary-dark font-semibold bg-primary/10 border border-primary/20 px-2 py-1 rounded-md">
                                    {{ $profil->nim ?? '-' }}
                                </span>
                            </div>
                        </td>

                        <!-- Instansi Asal & Jurusan -->
                        <td class="block md:table-cell md:px-4 md:py-3.5 mb-2 md:mb-0">
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
                        <td class="block md:table-cell md:px-4 md:py-3.5 mb-2 md:mb-0 whitespace-nowrap">
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
                        <td class="block md:table-cell md:px-4 md:py-3.5 md:text-center mt-2 md:mt-0 whitespace-nowrap">
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

                        <!-- Aksi (Dropdown Menu: Detail & Kirim Email + Counter) -->
                        <td
                            class="block md:table-cell md:px-4 md:py-3.5 md:text-center mt-4 md:mt-0 pt-4 md:pt-0 border-t border-border md:border-0 whitespace-nowrap">
                            <div class="relative inline-block w-full md:w-auto text-left" data-aksi-dropdown>
                                <button type="button" data-aksi-trigger
                                    class="w-full md:w-auto bg-surface hover:bg-primary text-text hover:text-white border border-border hover:border-primary text-xs font-semibold px-3.5 py-2 rounded-lg transition-all shadow-xs inline-flex justify-center items-center gap-1.5 cursor-pointer">
                                    <span>Aksi</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 shrink-0" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <div data-aksi-menu
                                    class="hidden fixed z-50 w-52 bg-surface border border-border rounded-xl shadow-lg py-1.5 text-left">
                                    <!-- Menu 1: Detail -->
                                    <a href="{{ route('admin-mahasiswa-detail', $mhs->id) }}"
                                        class="flex items-center gap-2.5 px-3.5 py-2 text-xs font-semibold text-text hover:bg-primary/10 hover:text-primary-dark transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2" stroke="currentColor" class="w-4 h-4 shrink-0 text-primary">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span>Detail</span>
                                    </a>

                                    <div class="my-1 border-t border-border/60"></div>

                                    <!-- Menu 2: Kirim Email + Counter -->
                                    <form method="POST" action="{{ route('admin.mahasiswa.kirim-email', $mhs->id) }}"
                                        data-confirm="Kirim email pemberitahuan penerimaan magang & informasi login ke {{ $mhs->name }}?">
                                        @csrf
                                        <button type="submit"
                                            class="w-full flex items-center justify-between gap-2 px-3.5 py-2 text-xs font-semibold text-text hover:bg-primary/10 hover:text-primary-dark transition-colors cursor-pointer">
                                            <span class="flex items-center gap-2.5">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="2" stroke="currentColor" class="w-4 h-4 shrink-0 text-accent-dark">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                                </svg>
                                                <span>Kirim Email</span>
                                            </span>

                                            <span
                                                class="px-2 py-0.5 rounded-full text-[11px] font-bold {{ $jumlahEmailTerkirim > 0 ? 'bg-success/15 text-success-dark border border-success/30' : 'bg-warning/20 text-warning-dark border border-warning/30' }}"
                                                title="Jumlah email yang telah dikirim">
                                                {{ $jumlahEmailTerkirim }}
                                            </span>
                                        </button>
                                    </form>
                                </div>
                            </div>
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

<!-- Script Dropdown Menu Aksi (Anti-Clip di dalam Tabel) -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let activeMenu = null;

        function closeAllMenus() {
            document.querySelectorAll('[data-aksi-menu]').forEach(function (menu) {
                menu.classList.add('hidden');
            });
            activeMenu = null;
        }

        document.querySelectorAll('[data-aksi-trigger]').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                const wrapper = btn.closest('[data-aksi-dropdown]');
                const menu = wrapper ? wrapper.querySelector('[data-aksi-menu]') : null;
                if (!menu) return;

                const isCurrentlyOpen = !menu.classList.contains('hidden');
                closeAllMenus();

                if (!isCurrentlyOpen) {
                    menu.classList.remove('hidden');
                    const rect = btn.getBoundingClientRect();
                    const menuWidth = menu.offsetWidth || 208;
                    const menuHeight = menu.offsetHeight || 96;

                    let left = rect.right - menuWidth;
                    if (left < 12) left = 12;

                    let top = rect.bottom + 6;
                    if (top + menuHeight > window.innerHeight - 12) {
                        top = rect.top - menuHeight - 6;
                    }

                    menu.style.left = left + 'px';
                    menu.style.top = top + 'px';
                    activeMenu = menu;
                }
            });
        });

        document.addEventListener('click', function (e) {
            if (activeMenu && !activeMenu.contains(e.target)) {
                closeAllMenus();
            }
        });

        window.addEventListener('scroll', closeAllMenus, true);
        window.addEventListener('resize', closeAllMenus);
    });
</script>
