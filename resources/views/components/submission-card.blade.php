<div class="font-montserrat">

    <!-- Header Component -->
    <div class="mb-6 flex items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-text flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                Tugas Menunggu Review
            </h1>
            <p class="text-sm text-text-light mt-1">Daftar tugas yang telah dikumpulkan mahasiswa dan perlu Anda
                periksa.</p>
        </div>
    </div>

    <!-- Kondisi Jika Data Kosong -->
    @if ($tugasMenungguReview->isEmpty())
        <div class="py-16 bg-surface border border-border rounded-2xl text-center shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 mx-auto text-border mb-4" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
            <h3 class="text-lg font-bold text-text">Belum Ada Tugas yang Dikumpulkan</h3>
            <p class="text-sm text-text-light mt-1">Belum ada mahasiswa yang mengirimkan hasil pekerjaannya saat ini.
            </p>
        </div>
    @else
        <!-- Grid List Tugas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($tugasMenungguReview as $tugas)
                <div
                    class="bg-surface border border-border rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 flex flex-col overflow-hidden group">

                    <div class="p-5 flex-1 space-y-4">
                        <!-- Judul Tugas -->
                        <h3
                            class="text-base font-bold text-text leading-snug line-clamp-2 group-hover:text-primary transition-colors">
                            {{ $tugas->judul }}
                        </h3>

                        <div class="space-y-2.5">
                            <!-- Dikumpulkan Oleh (Ketua & Tim) -->
                            <div class="flex items-start gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-text-light shrink-0 mt-0.5"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <div>
                                    <p class="text-xs font-semibold text-text">
                                        {{ $tugas->mahasiswaProfile->user->name ?? 'Mahasiswa (N/A)' }}
                                    </p>
                                    @if ($tugas->anggotaDiterima->isNotEmpty())
                                        <p class="text-[11px] text-text-light">
                                            Bersama {{ $tugas->anggotaDiterima->count() }} rekan tim lainnya
                                        </p>
                                    @else
                                        <p class="text-[11px] text-text-light">Mengerjakan secara individu</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Waktu Pengumpulan -->
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-text-light shrink-0"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-xs text-text-light">
                                    Diserahkan: <span
                                        class="font-medium text-text">{{ $tugas->submissions->first()?->created_at?->translatedFormat('d M Y, H:i') ?? '-' }}</span>
                                </p>
                            </div>

                            <!-- Indikator Lampiran File -->
                            <div class="pt-2">
                                @if ($tugas->submissions->first()?->file_path)
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-primary/10 text-primary-dark text-[10px] font-bold rounded-md uppercase tracking-wider border border-primary/20">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                        </svg>
                                        Ada Lampiran File
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-background text-text-light text-[10px] font-bold rounded-md uppercase tracking-wider border border-border">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M4 6h16M4 12h16M4 18h7" />
                                        </svg>
                                        Hanya Teks/Catatan
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Footer / Aksi -->
                    <div class="p-4 bg-background border-t border-border flex justify-end">

                        <x-buttonv2 href="{{ route('asn-submission-detail', $tugas->id) }}" color="accent-dark"
                            class="w-full sm:w-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Periksa Tugas
                        </x-buttonv2>
                    </div>

                </div>
            @endforeach
        </div>
    @endif
</div>
