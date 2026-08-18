<div class="bg-surface border border-border rounded-xl shadow-sm overflow-hidden font-montserrat w-full">

    <!-- Header Tabel (Status Selesai) -->
    <div
        class="p-5 md:p-6 border-b border-border bg-background flex flex-col sm:flex-row sm:items-center justify-between gap-4 font-montserrat">
        <div class="flex items-center gap-4">

            <!-- Icon Success (Check-Circle) -->
            <div
                class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-success/10 text-success-dark flex items-center justify-center shrink-0 border border-success/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 md:h-6 md:w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            <!-- Teks Keterangan -->
            <div>
                <h2 class="text-base md:text-lg font-bold text-text leading-tight">
                    Status Magang Selesai
                </h2>
                <p class="text-[11px] md:text-xs text-text-light mt-1">
                    Daftar mahasiswa yang telah berhasil menyelesaikan program magang.
                </p>
            </div>

        </div>
    </div>
    <!-- Wrapper Table -->
    <div class="w-full">
        <table class="w-full text-left border-collapse">

            <!-- Table Header (Disembunyikan di Mobile, Tampil di MD ke atas) -->
            <thead class="hidden md:table-header-group bg-background/50 border-b border-border">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold text-text-light uppercase tracking-wider">Nama Mahasiswa</th>
                    <th class="px-6 py-4 text-xs font-bold text-text-light uppercase tracking-wider">Kontak (Email)</th>
                    <th class="px-6 py-4 text-xs font-bold text-text-light uppercase tracking-wider text-center">Status
                        Magang</th>
                </tr>
            </thead>

            <!-- Table Body -->
            <tbody class="block md:table-row-group divide-y divide-border md:divide-y-0">
                @forelse ($jumlahMahasiswaSelesai as $mhs)
                    <!-- Tr: Menjadi flex column di HP, dan table-row di PC -->
                    <tr
                        class="block md:table-row flex-col p-4 md:p-0 hover:bg-primary/5 transition-colors duration-200 group md:border-b md:border-border last:border-b-0">

                        <!-- Kolom Nama -->
                        <td class="block md:table-cell md:px-6 md:py-4 mb-3 md:mb-0">
                            <div class="flex items-center gap-3">
                                <!-- Avatar Inisial -->
                                <div
                                    class="w-10 h-10 md:w-9 md:h-9 rounded-full bg-primary/10 text-primary-dark flex items-center justify-center text-xs font-bold shrink-0 border border-primary/20">
                                    {{ strtoupper(substr($mhs->name, 0, 1)) }}
                                </div>
                                <div class="flex flex-col">
                                    <span
                                        class="text-sm font-bold text-text group-hover:text-primary transition-colors">{{ $mhs->name }}</span>
                                    <span class="text-[10px] text-text-light mt-0.5">
                                        Bergabung:
                                        {{ \Carbon\Carbon::parse($mhs->created_at)->translatedFormat('d M Y') }}
                                    </span>
                                </div>
                            </div>
                        </td>

                        <!-- Kolom Email -->
                        <td class="block md:table-cell md:px-6 md:py-4 mb-2 md:mb-0">
                            <!-- Wrapper untuk Mobile: Label Kiri, Value Kanan -->
                            <div class="flex justify-between items-center md:block">
                                <span class="md:hidden text-[10px] font-bold text-text-light uppercase">Email:</span>
                                <span class="text-sm text-text-light">
                                    {{ $mhs->email }}
                                </span>
                            </div>
                        </td>

                        <!-- Kolom Status -->
                        <td class="block md:table-cell md:px-6 md:py-4 md:text-center mt-2 md:mt-0 align-middle">
                            <!-- Wrapper untuk Mobile: Label Kiri, Value Kanan -->
                            <div class="flex justify-between items-center md:flex md:justify-center w-full">
                                <span
                                    class="md:hidden text-[10px] font-bold text-text-light uppercase tracking-wider">Status:</span>

                                @php
                                    // Normalisasi status ke huruf kecil untuk mempermudah pengecekan
                                    $statusMagang = strtolower($mhs->mahasiswaProfile->status ?? '');
                                @endphp

                                @if ($statusMagang === 'selesai')
                                    <!-- Status Selesai (Success - Hijau) -->
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-success/10 text-success-dark text-[10px] font-bold rounded-md uppercase tracking-wide border border-success/20 shadow-sm md:whitespace-nowrap">
                                        <span class="w-1.5 h-1.5 rounded-full bg-success"></span>
                                        {{ $mhs->mahasiswaProfile->status }}
                                    </span>
                                @elseif ($statusMagang === 'dibatalkan' || $statusMagang === 'ditolak')
                                    <!-- Status Dibatalkan/Ditolak (Danger - Merah) -->
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-danger/10 text-danger-dark text-[10px] font-bold rounded-md uppercase tracking-wide border border-danger/20 shadow-sm md:whitespace-nowrap">
                                        <span class="w-1.5 h-1.5 rounded-full bg-danger"></span>
                                        {{ $mhs->mahasiswaProfile->status }}
                                    </span>
                                @elseif ($statusMagang === 'aktif' || $statusMagang === 'diterima')
                                    <!-- Status Aktif/Diterima (Primary - Biru/Warna Utama) -->
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-primary/10 text-primary-dark text-[10px] font-bold rounded-md uppercase tracking-wide border border-primary/20 shadow-sm md:whitespace-nowrap">
                                        <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                                        {{ $mhs->mahasiswaProfile->status }}
                                    </span>
                                @else
                                    <!-- Status Menunggu/Lainnya (Warning - Kuning/Oranye) -->
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-warning/10 text-warning-dark text-[10px] font-bold rounded-md uppercase tracking-wide border border-warning/20 shadow-sm md:whitespace-nowrap">
                                        <span class="w-1.5 h-1.5 rounded-full bg-warning"></span>
                                        {{ $mhs->mahasiswaProfile->status ?? 'Menunggu' }}
                                    </span>
                                @endif

                            </div>
                        </td>

                    </tr>
                @empty
                    <!-- Tampilan Jika Data Kosong -->
                    <tr class="block md:table-row">
                        <!-- Colspan diubah menjadi 3 karena hanya ada 3 kolom (Nama, Email, Status) -->
                        <td colspan="3" class="block md:table-cell px-6 py-12 md:py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div
                                    class="w-12 h-12 md:w-14 md:h-14 rounded-full bg-success/10 text-success flex items-center justify-center mb-3 md:mb-4 border border-success/20">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 md:h-7 md:w-7" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <p class="text-sm md:text-base font-bold text-text">Data Kosong</p>
                                <p class="text-[11px] md:text-sm text-text-light mt-1 text-center">Saat ini tidak ada
                                    mahasiswa yang selesai melaksanakan magang.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>
</div>
