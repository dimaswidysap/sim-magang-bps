@if (auth()->check() && auth()->user()->role === 'mahasiswa')
    @php
        $userMahasiswa = auth()->user();
        $profilMahasiswa = $profil ?? $userMahasiswa->mahasiswaProfile;
        $profilBelumLengkap = ! $userMahasiswa->isProfileComplete();
        $tanggalBelumDiisi = ! $profilMahasiswa
            || (is_null($profilMahasiswa->tanggal_mulai) && is_null($profilMahasiswa->tanggal_selesai));

        $batasPengisianTanggal = $tanggalBelumDiisi && $userMahasiswa->created_at
            ? $userMahasiswa->created_at->copy()->addMonth()
            : null;

        $sisaHari = $batasPengisianTanggal
            ? max(0, (int) now()->startOfDay()->diffInDays($batasPengisianTanggal->copy()->startOfDay(), false))
            : null;
    @endphp

    @if ($profilBelumLengkap || $tanggalBelumDiisi)
        <div
            class="{{ $tanggalBelumDiisi ? 'bg-danger/10 border-danger/35' : 'bg-warning/10 border-warning/30' }} border rounded-xl p-4 md:p-5 mb-6 shadow-sm flex flex-col sm:flex-row items-start sm:items-center gap-4 md:gap-5 font-montserrat">

            <!-- Ikon Peringatan -->
            <div
                class="w-10 h-10 md:w-12 md:h-12 rounded-full {{ $tanggalBelumDiisi ? 'bg-danger/20 text-danger' : 'bg-warning/20 text-warning' }} flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>

            <!-- Teks Peringatan -->
            <div class="flex-1 space-y-1.5">
                <div class="flex flex-wrap items-center gap-2">
                    <h3 class="text-base md:text-lg font-bold text-text">
                        {{ $tanggalBelumDiisi ? 'Segera Lengkapi Tanggal Mulai & Selesai Magang!' : 'Peringatan: Profil Belum Lengkap!' }}
                    </h3>

                    @if ($tanggalBelumDiisi && ! is_null($sisaHari))
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-danger text-white">
                            Sisa {{ $sisaHari }} hari lagi
                        </span>
                    @endif
                </div>

                @if ($tanggalBelumDiisi)
                    <p class="text-sm text-text-light leading-relaxed">
                        Anda belum mengisi <strong class="text-text">Tanggal Mulai</strong> dan <strong class="text-text">Tanggal Selesai Magang</strong>.
                        Jika tidak dilengkapi dalam waktu <strong class="text-danger">1 bulan</strong> sejak akun dibuat
                        @if ($batasPengisianTanggal)
                            (maksimal <strong class="text-text">{{ $batasPengisianTanggal->translatedFormat('d F Y') }}</strong>),
                        @else
                            ,
                        @endif
                        akun Anda akan <strong class="text-danger">terhapus secara otomatis</strong> oleh sistem.
                    </p>
                @else
                    <p class="text-sm text-text-light leading-relaxed">
                        Data profil magang Anda belum lengkap. Silakan lengkapi data diri Anda segera agar bisa menggunakan
                        fitur sistem secara penuh.
                    </p>
                @endif
            </div>

            <!-- Tombol Call to Action (CTA) -->
            <a href="{{ route('mahasiswa-profil-form') }}"
                class="shrink-0 w-full sm:w-auto {{ $tanggalBelumDiisi ? 'bg-danger hover:opacity-90' : 'bg-warning hover:bg-orange-500' }} text-white text-xs md:text-sm font-bold px-5 py-2.5 rounded-lg transition-all shadow-sm text-center inline-flex justify-center items-center gap-2 mt-2 sm:mt-0">
                <span>{{ $tanggalBelumDiisi ? 'Isi Tanggal & Profil' : 'Lengkapi Profil' }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>

        </div>
    @endif
@endif
