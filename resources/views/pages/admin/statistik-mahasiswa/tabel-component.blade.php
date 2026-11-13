@php
    $status = $status ?? 'aktif';
    $emptyMessage = $emptyMessage ?? 'Belum ada data mahasiswa magang saat ini.';

    // Pengaturan tampilan badge berdasarkan status halaman
    $badge = match ($status) {
        'aktif' => [
            'label' => 'Aktif',
            'class' => 'bg-success/10 border-success/20 text-success-dark',
            'dot' => 'bg-success animate-pulse',
        ],
        'pending' => [
            'label' => 'Pending',
            'class' => 'bg-amber-500/10 border-amber-500/20 text-amber-700',
            'dot' => 'bg-amber-500',
        ],
        'selesai' => [
            'label' => 'Selesai',
            'class' => 'bg-primary/10 border-primary/20 text-primary-dark',
            'dot' => 'bg-primary',
        ],
        'batal' => [
            'label' => 'Batal',
            'class' => 'bg-red-500/10 border-red-500/20 text-red-700',
            'dot' => 'bg-red-500',
        ],
        default => [
            'label' => ucfirst($status),
            'class' => 'bg-gray-100 border-gray-200 text-gray-700',
            'dot' => 'bg-gray-500',
        ],
    };
@endphp

<table class="w-full text-left border-collapse">
    <!-- Table Header -->
    <thead class="bg-background/60 border-b border-border text-text-light text-xs uppercase tracking-wider">
        <tr>
            <th class="px-6 py-4 font-bold">Mahasiswa</th>
            <th class="px-6 py-4 font-bold">Instansi & Jurusan</th>
            <th class="px-6 py-4 font-bold">Periode Magang</th>
            <th class="px-6 py-4 font-bold">Kontak</th>
            <th class="px-6 py-4 font-bold text-center">Status</th>
            <th class="px-6 py-4 font-bold text-center">Aksi</th>
        </tr>
    </thead>

    <!-- Table Body -->
    <tbody class="divide-y divide-border text-xs md:text-sm">
        @forelse ($items as $item)
            @php
                // data_get() otomatis mendukung tipe data array maupun object/model
                $profile = data_get($item, 'mahasiswaProfile') ?? data_get($item, 'mahasiswa_profile');
                $id = data_get($item, 'id');
                $name = data_get($item, 'name', '-');
                $email = data_get($item, 'email', '-');
                $phone = data_get($item, 'phone');

                $nim = data_get($profile, 'nim', '-');
                $instansi = data_get($profile, 'instansi_asal', '-');
                $jenjang = data_get($profile, 'jenjang', '');
                $jurusan = data_get($profile, 'jurusan', '-');

                $rawMulai = data_get($profile, 'tanggal_mulai');
                $rawSelesai = data_get($profile, 'tanggal_selesai');
                $tglMulai = $rawMulai ? \Carbon\Carbon::parse($rawMulai)->format('d M Y') : '-';
                $tglSelesai = $rawSelesai ? \Carbon\Carbon::parse($rawSelesai)->format('d M Y') : '-';
            @endphp

            <tr class="hover:bg-primary/5 transition-colors duration-150 align-middle">
                <!-- Nama & NIM -->
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-primary/10 text-primary-dark font-bold flex items-center justify-center shrink-0 border border-primary/20 text-sm">
                            {{ strtoupper(substr($name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="font-bold text-text">{{ $name }}</div>
                            <div class="text-xs text-text-light">NIM: {{ $nim }}</div>
                        </div>
                    </div>
                </td>

                <!-- Instansi & Jurusan -->
                <td class="px-6 py-4">
                    <div class="font-medium text-text">{{ $instansi }}</div>
                    <div class="text-xs text-text-light">{{ $jenjang }} {{ $jurusan }}</div>
                </td>

                <!-- Periode Magang -->
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-text font-medium">{{ $tglMulai }} – {{ $tglSelesai }}</div>
                </td>

                <!-- Kontak -->
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-text font-medium">{{ $email }}</div>
                    <div class="text-xs text-text-light">{{ $phone ?? 'Tidak ada No HP' }}</div>
                </td>

                <!-- Status Badge -->
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 border text-xs font-bold rounded-full {{ $badge['class'] }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $badge['dot'] }}"></span>
                        {{ $badge['label'] }}
                    </span>
                </td>

                <!-- Kolom Aksi (Bisa disesuaikan per status) -->
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    @if ($status === 'pending')
                        <div class="inline-flex items-center gap-2">
                            <button type="button" class="px-3 py-1.5 bg-success text-white text-xs font-semibold rounded-lg hover:opacity-90">
                                Terima
                            </button>
                            <button type="button" class="px-3 py-1.5 bg-red-600 text-white text-xs font-semibold rounded-lg hover:opacity-90">
                                Tolak
                            </button>
                        </div>
                    @else
                        <a href={{ route('admin-mahasiswa-detail', $id) }} class="px-3 py-1.5 bg-primary/10 text-primary-dark text-xs font-semibold rounded-lg hover:bg-primary/20">
                            Detail
                        </a>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <!-- Perbaikan colspan menjadi 6 sesuai jumlah kolom header -->
                <td colspan="6" class="px-6 py-12 text-center text-text-light">
                    <p class="text-sm">{{ $emptyMessage }}</p>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
