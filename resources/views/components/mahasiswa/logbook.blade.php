<div class="bg-surface border border-border rounded-xl p-6">
    @if (!$profil || !$profil->tanggal_mulai || !$profil->tanggal_selesai)
        <p class="text-sm text-text-light">
            Tanggal mulai/selesai magang belum diisi - lengkapi profil dulu untuk melihat kalender logbook.
        </p>
    @else
        <div class="py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-border/60 mb-6">
            <div>
                <h2 class="text-lg font-semibold text-primary">Logbook Kegiatan</h2>
                <div class="flex flex-wrap items-center gap-2 mt-2">
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-success/10 text-success border border-success/25">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Total Hari Masuk: {{ $jumlahHariMasuk ?? 0 }} Hari
                    </span>

                    @if (isset($logbookMenungguValidasi) && $logbookMenungguValidasi->isNotEmpty())
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-warning/10 text-warning-dark border border-warning/25">
                            <span class="w-1.5 h-1.5 rounded-full bg-warning"></span>
                            {{ $logbookMenungguValidasi->count() }} Menunggu Validasi
                        </span>
                    @endif
                </div>
            </div>

            <x-buttonv2 href="{{ route('magang-logbook-form') }}" color="accent-dark" class="w-full sm:w-auto">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
                    stroke="currentColor" class="w-4 h-4 shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Data
            </x-buttonv2>
        </div>
        @foreach ($bulanList as $bulan)
            <div class="mb-6">
                <p class="text-sm font-semibold text-text mb-2">{{ $bulan['label'] }}</p>

                <div class="flex flex-wrap gap-1.5">
                    @for ($tanggal = 1; $tanggal <= $bulan['jumlah_hari']; $tanggal++)
                        @php
                            $tanggalFormat = sprintf('%04d-%02d-%02d', $bulan['tahun'], $bulan['bulan'], $tanggal);
                            $adaKegiatan = $tanggalAktif->contains($tanggalFormat);
                        @endphp

                        @if ($adaKegiatan)
                            <a href="{{ route('mahasiswa-logbook-tanggal', $tanggalFormat) }}"
                                class="relative w-8 h-8 flex items-center justify-center bg-background border border-border rounded-md text-xs text-text hover:border-primary hover:bg-primary/5 transition-colors cursor-pointer">
                                {{ $tanggal }}
                                <span
                                    class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-danger rounded-full border border-surface"></span>
                            </a>
                        @else
                            <button type="button" disabled
                                class="relative w-8 h-8 flex items-center justify-center bg-background border border-border rounded-md text-xs text-text-light opacity-50 cursor-not-allowed">
                                {{ $tanggal }}
                            </button>
                        @endif
                    @endfor
                </div>
            </div>
        @endforeach
    @endif
</div>
