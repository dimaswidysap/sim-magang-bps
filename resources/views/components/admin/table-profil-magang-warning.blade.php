<div class="bg-surface border border-border rounded-xl shadow-sm overflow-hidden font-montserrat ">

    <!-- Header Tabel Peringatan -->
    <div class="p-5 md:p-6 border-b border-border bg-background flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <!-- Icon Warning -->
            <div class="w-12 h-12 rounded-full bg-warning/10 text-warning flex items-center justify-center shrink-0 border border-warning/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <h2 class="text-base md:text-lg font-bold text-text">Perhatian: Profil Belum Lengkap</h2>
                <p class="text-xs text-text-light mt-1">Daftar mahasiswa magang yang belum melengkapi data diri mereka di sistem.</p>
            </div>
        </div>
    </div>

    <!-- Wrapper Table (Bisa di-scroll horizontal di HP) -->
    <div class="overflow-x-auto hide-scrollbar">
        <table class="w-full text-left border-collapse whitespace-nowrap">

            <!-- Table Header -->
            <thead class="bg-background/50 border-b border-border">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold text-text-light uppercase tracking-wider">Nama Mahasiswa</th>
                    <th class="px-6 py-4 text-xs font-bold text-text-light uppercase tracking-wider">Kontak (Email)</th>
                    <th class="px-6 py-4 text-xs font-bold text-text-light uppercase tracking-wider text-center">Status</th>
                    <th class="px-6 py-4 text-xs font-bold text-text-light uppercase tracking-wider text-right">Aksi</th>
                </tr>
            </thead>

            <!-- Table Body -->
            <tbody class="divide-y divide-border">
                @forelse ($daftarMahasiswaProfilWarning as $mhs)
                    <tr class="hover:bg-primary/5 transition-colors duration-200 group">

                        <!-- Kolom Nama -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <!-- Avatar Inisial -->
                                <div class="w-9 h-9 rounded-full bg-primary/10 text-primary-dark flex items-center justify-center text-xs font-bold shrink-0 border border-primary/20">
                                    {{ strtoupper(substr($mhs->name, 0, 1)) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-text group-hover:text-primary transition-colors">{{ $mhs->name }}</span>
                                    <span class="text-[10px] text-text-light mt-0.5">
                                        Bergabung: {{ \Carbon\Carbon::parse($mhs->created_at)->translatedFormat('d M Y') }}
                                    </span>
                                </div>
                            </div>
                        </td>

                        <!-- Kolom Email -->
                        <td class="px-6 py-4">
                            <span class="text-sm text-text-light">
                                {{ $mhs->email }}
                            </span>
                        </td>

                        <!-- Kolom Status -->
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-warning/10 text-warning-dark text-[10px] font-bold rounded-md uppercase tracking-wide border border-warning/20">
                                <span class="w-1.5 h-1.5 rounded-full bg-warning"></span>
                                Belum Lengkap
                            </span>
                        </td>

                        <!-- Kolom Aksi -->
                        <td class="px-6 py-4 text-right">
                            <!-- Sesuaikan route ini dengan halaman detail mahasiswa atau fitur kirim notifikasi Anda -->
                            {{-- href="{{ route('admin-mahasiswa-show', $mhs->id) }}" --}}
                            <x-main-button
                                class="bg-surface hover:bg-primary hover:text-white hover:border-primary text-text border border-border text-[10px] px-3 py-2 rounded-lg transition-all shadow-sm inline-flex justify-center items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-primary group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <!-- Ikon Lonceng (Mengingatkan) -->
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <span>Kirim Peringatan</span>
                            </x-main-button>
                        </td>

                    </tr>
                @empty
                    <!-- Tampilan Jika Data Kosong (Semua Mahasiswa Sudah Lengkap Profilnya) -->
                    <tr>
                        <td colspan="4" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-14 h-14 rounded-full bg-success/10 text-success flex items-center justify-center mb-4 border border-success/20">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <p class="text-base font-bold text-text">Semua Profil Telah Lengkap</p>
                                <p class="text-sm text-text-light mt-1">Saat ini tidak ada mahasiswa yang belum melengkapi data dirinya.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>
</div>
