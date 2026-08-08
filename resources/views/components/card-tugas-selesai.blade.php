<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 font-montserrat">
    @forelse ($tugasSelesai as $tugas)
        <div
            class="bg-surface border border-border rounded-[10px] shadow-sm hover:shadow-md transition-shadow duration-300 flex flex-col p-5 group">

            <!-- Header Card: Status & Tanggal Selesai -->
            <div class="flex items-start justify-between gap-2 mb-3">
                <!-- Status Badge -->
                <span
                    class="inline-flex px-2.5 py-1 bg-success/10 text-success text-[10px] font-bold rounded-md uppercase tracking-wide border border-success/20">
                    {{ $tugas->status }}
                </span>

                <!-- Waktu Diselesaikan -->
                <div class="text-right">
                    <p class="text-[10px] text-text-light font-medium uppercase tracking-wider mb-0.5">Diselesaikan Pada
                    </p>
                    <p class="text-xs font-bold text-success flex items-center justify-end gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $tugas->selesai_at ? \Carbon\Carbon::parse($tugas->selesai_at)->translatedFormat('d M Y, H:i') : '-' }}
                    </p>
                </div>
            </div>

            <!-- Body Card: Judul & Deskripsi -->
            <div class="flex-1">
                <h3 class="text-lg font-bold text-text mb-2 line-clamp-2 group-hover:text-primary transition-colors">
                    {{ $tugas->judul }}
                </h3>
                <p class="text-sm text-text-light line-clamp-3 mb-4">
                    {{ $tugas->deskripsi }}
                </p>
            </div>

            <hr class="border-border mb-4">

            <!-- Footer: Button Aksi -->
            <div class="flex justify-end">

                <x-main-button href="{{ route('asn-tugas-selesai-detail', $tugas->id) }}"
                    class="bg-primary hover:bg-primary-dark text-xs px-4 py-2 rounded-lg text-white transition-colors shadow-sm inline-flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-4 h-4 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Lihat Detail</span>
                </x-main-button>
            </div>

        </div>
    @empty
        <!-- Tampilan Jika Data Tugas Selesai Kosong -->
        <div
            class="col-span-1 md:col-span-2 lg:col-span-3 py-16 bg-surface border border-border rounded-xl text-center shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 mx-auto text-border mb-4" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="text-lg font-bold text-text">Belum Ada Tugas Selesai</h3>
            <p class="text-sm text-text-light mt-1">Riwayat tugas yang telah Anda selesaikan akan muncul di sini.</p>
        </div>
    @endforelse
</div>
