@php
    $userMhs = Auth::user();
    $profilMhs = $userMhs?->mahasiswaProfile;
    $profilLengkap = $userMhs?->isProfileComplete() ?? false;
    $anggotaKelompokList = $profilMhs && $profilMhs->isKelompok() ? $profilMhs->anggotaKelompok() : collect();
    $ketuaKelompok = $anggotaKelompokList->firstWhere('is_ketua_kelompok', true) ?? $anggotaKelompokList->first();
    $sedangDiproses = $profilMhs && in_array($profilMhs->status_pengajuan, ['menunggu_verifikasi', 'menunggu_asn', 'ditolak_asn'], true);
@endphp

@if (session('success'))
    <div class="p-4 rounded-xl bg-success/10 border border-success/30 text-success-dark text-xs sm:text-sm font-medium">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="p-4 rounded-xl bg-danger/10 border border-danger/30 text-danger text-xs sm:text-sm font-medium">
        {{ session('error') }}
    </div>
@endif

@if ($userMhs && $userMhs->isMahasiswa())
    @if (! $profilLengkap)
        <!-- 1. Profil Belum Lengkap -->
        <div class="bg-warning/10 border border-warning/30 rounded-2xl p-4 md:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 font-montserrat">
            <div class="space-y-1">
                <p class="text-sm font-bold text-warning-dark">Lengkapi Profil Terlebih Dahulu</p>
                <p class="text-xs text-text-light">
                    Silakan lengkapi biodata (NIM, instansi asal, jenjang, jurusan) serta tanggal mulai & tanggal selesai magang agar tombol <strong>Ajukan Pembimbing</strong> dapat digunakan.
                </p>
                @if ($profilMhs && $profilMhs->belumMengisiTanggalMagang() && ! is_null($profilMhs->sisaHariPengisianTanggal()))
                    <p class="text-xs font-semibold text-danger">
                        Batas waktu pengisian tanggal magang: {{ $profilMhs->sisaHariPengisianTanggal() }} hari lagi sebelum akun terhapus otomatis.
                    </p>
                @endif
            </div>

            <a href="{{ route('mahasiswa-profil-form') }}"
                class="shrink-0 inline-flex items-center justify-center px-4 py-2.5 bg-primary hover:bg-primary-dark text-white text-xs font-bold rounded-xl transition-colors shadow-xs">
                Lengkapi Profil Sekarang
            </a>
        </div>

    @elseif ($sedangDiproses)
        <!-- 2. Sedang Diproses Admin / Menunggu Konfirmasi Kesediaan Pembimbing ASN -->
        <div class="bg-primary/10 border border-primary/25 rounded-2xl p-4 md:p-5 font-montserrat space-y-1">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-primary animate-pulse"></span>
                <p class="text-sm font-bold text-primary">
                    {{ $profilMhs->status_pengajuan === 'menunggu_asn' ? 'Menunggu Konfirmasi Pembimbing ASN' : 'Pengajuan Pembimbing Sedang Diproses Admin' }}
                </p>
            </div>
            <p class="text-xs text-text-light">
                Permintaan pembimbing {{ $profilMhs->isKelompok() ? 'untuk kelompok Anda' : 'Anda' }} telah dikirim pada
                <strong>{{ $profilMhs->diajukan_at?->translatedFormat('d F Y, H:i') ?? '-' }}</strong> dan sedang dalam proses verifikasi serta konfirmasi kesediaan pembimbing ASN.
            </p>
        </div>

    @elseif ($profilMhs && $profilMhs->bisaMengajukanPembimbing())
        <!-- 3. Profil Lengkap & Siap Klik Tombol "Ajukan Pembimbing" (Individu atau Peserta Pertama Kelompok) -->
        <div class="bg-surface border border-primary/30 rounded-2xl p-4 md:p-5 shadow-xs font-montserrat space-y-4">
            @if ($profilMhs->status_pengajuan === 'ditolak' && $profilMhs->alasan_penolakan)
                <div class="p-3.5 rounded-xl bg-danger/10 border border-danger/30 text-xs space-y-1">
                    <p class="font-bold text-danger">Pengajuan Sebelumnya Ditolak oleh Admin:</p>
                    <p class="text-text">{{ $profilMhs->alasan_penolakan }}</p>
                </div>
            @endif

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="space-y-1">
                    <p class="text-sm font-bold text-text">
                        {{ $profilMhs->status_pengajuan === 'ditolak' ? 'Perbaiki Data & Ajukan Ulang Pembimbing' : 'Profil Sudah Lengkap — Siap Mengajukan Pembimbing' }}
                    </p>
                    <p class="text-xs text-text-light">
                        @if ($profilMhs->isKelompok())
                            Anda terdaftar sebagai <strong>Peserta Pertama (Ketua Kelompok)</strong> untuk <strong>{{ $anggotaKelompokList->count() }} anggota</strong>. Klik tombol di samping untuk mengirimkan pengajuan pembimbing kelompok ke Admin.
                        @else
                            Klik tombol <strong>Ajukan Pembimbing</strong> agar Admin dapat meneruskan permintaan pembimbing ASN untuk Anda.
                        @endif
                    </p>
                </div>

                <div class="flex items-center gap-2.5 shrink-0">
                    <a href="{{ route('mahasiswa-profil-form') }}"
                        class="px-3.5 py-2.5 bg-background hover:bg-border/40 border border-border text-text text-xs font-semibold rounded-xl transition-colors">
                        Edit Profil
                    </a>

                    <form method="POST" action="{{ route('mahasiswa-ajukan-pembimbing') }}">
                        @csrf
                        <button type="submit"
                            class="px-5 py-2.5 bg-primary hover:bg-primary-dark text-white text-xs font-bold rounded-xl transition-colors cursor-pointer shadow-sm inline-flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            <span>{{ $profilMhs->status_pengajuan === 'ditolak' ? 'Ajukan Ulang Pembimbing' : 'Ajukan Pembimbing' }}</span>
                        </button>
                    </form>
                </div>
            </div>

            @if ($profilMhs->isKelompok() && $anggotaKelompokList->isNotEmpty())
                <div class="pt-3 border-t border-border">
                    <p class="text-[11px] font-bold uppercase text-text-light mb-2">Anggota Kelompok Anda:</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($anggotaKelompokList as $anggota)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs border border-border bg-background text-text">
                                <span class="font-semibold">{{ $anggota->user?->name ?? '-' }}</span>
                                <span class="text-[10px] {{ $anggota->user?->isProfileComplete() ? 'text-success font-bold' : 'text-warning-dark' }}">
                                    ({{ $anggota->user?->isProfileComplete() ? 'Profil Lengkap' : 'Belum Lengkap' }})
                                </span>
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

    @elseif ($profilMhs && $profilMhs->isKelompok() && ! $profilMhs->adalahPengajuUtama() && $profilMhs->status_pengajuan !== 'diterima')
        <!-- 4. Anggota Kelompok (Bukan Peserta Pertama) -->
        <div class="bg-surface border border-border rounded-2xl p-4 md:p-5 font-montserrat space-y-2">
            <p class="text-sm font-bold text-text">Terdaftar dalam Magang Kelompok</p>
            <p class="text-xs text-text-light">
                Profil Anda sudah lengkap. Pengajuan pembimbing ke Admin dilakukan melalui akun peserta pertama (Ketua Kelompok):
                <strong class="text-text">{{ $ketuaKelompok?->user?->name ?? '-' }}</strong>.
            </p>
            @if ($profilMhs->status_pengajuan === 'ditolak' && $profilMhs->alasan_penolakan)
                <div class="p-3 rounded-xl bg-danger/10 border border-danger/30 text-xs text-danger">
                    <strong>Catatan Penolakan Admin:</strong> {{ $profilMhs->alasan_penolakan }}
                </div>
            @endif
        </div>
    @endif
@endif
