{{-- Sisipkan di dashboard mahasiswa (pages/mahasiswa/index.blade.php) --}}

<div class="bg-surface border border-border rounded-xl p-6">
    <h2 class="text-lg font-semibold text-primary mb-4">Logbook Kegiatan</h2>

    @if (!$profil || !$profil->tanggal_mulai || !$profil->tanggal_selesai)
        <p class="text-sm text-text-light">
            Tanggal mulai/selesai magang belum diisi - lengkapi profil dulu untuk melihat kalender logbook.
        </p>
    @else
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
