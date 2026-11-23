@vite(['resources/js/fitur-search.js'])
@extends('layouts.app')

@section('content')
    <main class=" relative w-full flex bg-background">
        {{-- container-sidebar-admin --}}
        @include('components.header-mobile')
        @include('components.admin.sidebar-admin')

        {{-- container-content --}}
        <section class="flex flex-col flex-1 md:pl-60 container-content-mobile">

            {{-- header --}}
            @include('components.admin.header-admin')

            <section class="w-full p-2">
                <section class="container-dalam flex flex-col gap-4 font-montserrat">

                    <!-- Header Halaman -->
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h1 class="text-xl md:text-2xl font-bold text-text">Permintaan Penetapan Pembimbing Magang</h1>
                            <p class="text-xs md:text-sm text-text-light mt-1">
                                Teruskan pengajuan peserta magang (individu/kelompok) ke kandidat pembimbing ASN untuk dikonfirmasi kesediaannya.
                            </p>
                        </div>
                    </div>

                    <!-- Filter Status Tab -->
                    <div class="flex flex-wrap items-center gap-2 border-b border-border pb-3">
                        <a href="{{ route('admin-permintaan-magang', ['status' => 'perlu_tindakan']) }}"
                            class="px-4 py-2 rounded-lg text-xs font-bold transition-colors {{ $filterStatus === 'perlu_tindakan' ? 'bg-primary text-white shadow-xs' : 'bg-surface border border-border text-text-light hover:text-text' }}">
                            Perlu Tindakan Admin ({{ $jumlahPerluTindakan }})
                        </a>

                        <a href="{{ route('admin-permintaan-magang', ['status' => 'menunggu_asn']) }}"
                            class="px-4 py-2 rounded-lg text-xs font-bold transition-colors {{ $filterStatus === 'menunggu_asn' ? 'bg-warning text-white shadow-xs' : 'bg-surface border border-border text-text-light hover:text-text' }}">
                            Menunggu Respons ASN ({{ $jumlahMenungguAsn }})
                        </a>

                        <a href="{{ route('admin-permintaan-magang', ['status' => 'diterima']) }}"
                            class="px-4 py-2 rounded-lg text-xs font-bold transition-colors {{ $filterStatus === 'diterima' ? 'bg-success text-white shadow-xs' : 'bg-surface border border-border text-text-light hover:text-text' }}">
                            Diterima ASN ({{ $jumlahDiterima }})
                        </a>

                        <a href="{{ route('admin-permintaan-magang', ['status' => 'ditolak']) }}"
                            class="px-4 py-2 rounded-lg text-xs font-bold transition-colors {{ $filterStatus === 'ditolak' ? 'bg-danger text-white shadow-xs' : 'bg-surface border border-border text-text-light hover:text-text' }}">
                            Ditolak Admin ({{ $jumlahDitolak }})
                        </a>

                        <a href="{{ route('admin-permintaan-magang', ['status' => 'semua']) }}"
                            class="px-4 py-2 rounded-lg text-xs font-bold transition-colors {{ $filterStatus === 'semua' ? 'bg-text text-white shadow-xs' : 'bg-surface border border-border text-text-light hover:text-text' }}">
                            Semua
                        </a>
                    </div>

                    <!-- Daftar Card Pengajuan -->
                    <div class="flex flex-col gap-4">
                        @forelse ($daftarPengajuan as $pengajuan)
                            @php
                                $userPemohon = $pengajuan->user;
                                $isKelompok = $pengajuan->isKelompok();
                                $anggotaList = $pengajuan->daftar_anggota ?? collect([$pengajuan]);
                                $bisaDikirimKeAsn = in_array($pengajuan->status_pengajuan, ['menunggu_verifikasi', 'ditolak_asn'], true);
                            @endphp

                            <div class="bg-surface border border-border rounded-2xl p-5 md:p-6 shadow-xs space-y-5">
                                <!-- Baris Atas: Identitas Utama & Status -->
                                <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4 border-b border-border pb-4">
                                    <div class="space-y-1.5">
                                        <div class="flex flex-wrap items-center gap-2">
                                            @if ($isKelompok)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-primary/10 text-primary border border-primary/20">
                                                    Kelompok ({{ $anggotaList->count() }} Peserta)
                                                </span>
                                                <span class="text-xs font-mono text-text-light">
                                                    {{ $pengajuan->kode_kelompok }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-background text-text border border-border">
                                                    Individu
                                                </span>
                                            @endif

                                            @if ($pengajuan->status_pengajuan === 'menunggu_verifikasi')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-primary/15 text-primary border border-primary/30">
                                                    Pengajuan Baru
                                                </span>
                                            @elseif ($pengajuan->status_pengajuan === 'menunggu_asn')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-warning/15 text-warning-dark border border-warning/30">
                                                    Menunggu Konfirmasi ASN
                                                </span>
                                            @elseif ($pengajuan->status_pengajuan === 'ditolak_asn')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-danger/15 text-danger border border-danger/30">
                                                    Ditolak oleh ASN — Pilih ASN Lain
                                                </span>
                                            @elseif ($pengajuan->status_pengajuan === 'diterima')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-success/10 text-success border border-success/20">
                                                    Diterima ASN
                                                </span>
                                            @elseif ($pengajuan->status_pengajuan === 'ditolak')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-danger/10 text-danger border border-danger/20">
                                                    Ditolak Admin
                                                </span>
                                            @endif
                                        </div>

                                        <h2 class="text-lg font-bold text-text">
                                            {{ $userPemohon?->name ?? '-' }}
                                            @if ($isKelompok)
                                                <span class="text-xs font-normal text-text-light">(Ketua / Pengaju Kelompok)</span>
                                            @endif
                                        </h2>

                                        <p class="text-xs text-text-light">
                                            {{ $pengajuan->instansi_asal ?? '-' }} • {{ $pengajuan->jurusan ?? '-' }} ({{ $pengajuan->jenjang ?? '-' }}) • NIM: <span class="font-mono font-semibold text-text">{{ $pengajuan->nim ?? '-' }}</span>
                                        </p>

                                        <p class="text-xs text-text-light">
                                            Periode Magang:
                                            <strong class="text-text">{{ $pengajuan->tanggal_mulai?->translatedFormat('d M Y') ?? '-' }}</strong>
                                            s.d.
                                            <strong class="text-text">{{ $pengajuan->tanggal_selesai?->translatedFormat('d M Y') ?? '-' }}</strong>
                                            @if ($pengajuan->diajukan_at)
                                                <span class="ml-2">• Diajukan {{ $pengajuan->diajukan_at->diffForHumans() }}</span>
                                            @endif
                                        </p>
                                    </div>

                                    <!-- Tombol Aksi Admin -->
                                    <div class="flex flex-wrap items-center gap-2.5 shrink-0">


                                        @if ($bisaDikirimKeAsn)
                                            <button type="button"
                                                onclick="bukaModalKirimAsn({{ $pengajuan->id }}, @js($userPemohon?->name ?? 'Peserta'), {{ $isKelompok ? $anggotaList->count() : 1 }}, {{ $pengajuan->status_pengajuan === 'ditolak_asn' ? (int) $pengajuan->calon_asn_profile_id : 0 }})"
                                                class="px-4 py-2 bg-primary hover:bg-primary-dark text-white text-xs font-bold rounded-lg transition-colors cursor-pointer shadow-xs inline-flex items-center gap-1.5">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                                </svg>
                                                {{ $pengajuan->status_pengajuan === 'ditolak_asn' ? 'Ajukan ke ASN Lain' : 'Pilih & Kirim ke ASN' }}
                                            </button>

                                            <button type="button"
                                                onclick="bukaModalTolak({{ $pengajuan->id }}, @js($userPemohon?->name ?? 'Peserta'))"
                                                class="px-4 py-2 bg-danger hover:opacity-90 text-white text-xs font-bold rounded-lg transition-opacity cursor-pointer shadow-xs inline-flex items-center gap-1.5">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                Tolak
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                <!-- Daftar Anggota Jika Magang Kelompok -->
                                @if ($isKelompok)
                                    <div class="bg-background border border-border rounded-xl p-4 space-y-2.5">
                                        <p class="text-xs font-bold uppercase tracking-wider text-text-light">
                                            Daftar Anggota Kelompok ({{ $anggotaList->count() }} Orang)
                                        </p>
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                            @foreach ($anggotaList as $anggota)
                                                @php
                                                    $dataProfilAnggota = [
                                                        'name' => $anggota->user?->name ?? '-',
                                                        'email' => $anggota->user?->email ?? '-',
                                                        'phone' => $anggota->user?->phone ?? '-',
                                                        'peran' => $anggota->is_ketua_kelompok ? 'Ketua Kelompok' : 'Anggota Kelompok',
                                                        'foto' => $anggota->foto_profil_url,
                                                        'nim' => $anggota->nim ?? '-',
                                                        'instansi_asal' => $anggota->instansi_asal ?? '-',
                                                        'jenjang' => $anggota->jenjang ?? '-',
                                                        'jurusan' => $anggota->jurusan ?? '-',
                                                        'tanggal_lahir' => $anggota->tanggal_lahir?->translatedFormat('d M Y') ?? '-',
                                                        'periode' => ($anggota->tanggal_mulai?->translatedFormat('d M Y') ?? '-') . ' s.d. ' . ($anggota->tanggal_selesai?->translatedFormat('d M Y') ?? '-'),
                                                        'alamat' => $anggota->alamat ?? '-',
                                                        'skills' => $anggota->skills->pluck('nama_skill')->values(),
                                                        'surat_url' => $anggota->surat_pengantar_path ? \Illuminate\Support\Facades\Storage::url($anggota->surat_pengantar_path) : null,
                                                        'detail_url' => $anggota->user ? route('admin-mahasiswa-detail', $anggota->user->id) : null,
                                                    ];
                                                @endphp

                                                <div class="bg-surface border border-border rounded-lg p-3 flex flex-col justify-between gap-3">
                                                    <div class="flex items-start justify-between gap-2">
                                                        <div class="min-w-0">
                                                            <p class="text-xs font-bold text-text truncate">
                                                                {{ $loop->iteration }}. {{ $anggota->user?->name ?? '-' }}
                                                            </p>
                                                            <p class="text-[11px] text-text-light truncate">
                                                                {{ $anggota->user?->email ?? '-' }}
                                                            </p>
                                                            <p class="text-[11px] font-mono text-text-light mt-0.5">
                                                                NIM: {{ $anggota->nim ?? 'Belum diisi' }}
                                                            </p>
                                                        </div>
                                                        @if ($anggota->is_ketua_kelompok)
                                                            <span class="shrink-0 px-2 py-0.5 text-[10px] font-bold rounded bg-primary/10 text-primary">
                                                                Ketua
                                                            </span>
                                                        @else
                                                            <span class="shrink-0 px-2 py-0.5 text-[10px] font-semibold rounded bg-background text-text-light border border-border">
                                                                Anggota
                                                            </span>
                                                        @endif
                                                    </div>

                                                    <!-- Tombol Lihat Profil Anggota -->
                                                    <div class="flex items-center gap-2 pt-2 border-t border-border">
                                                        <button type="button"
                                                            onclick="bukaModalProfilAnggota(@js($dataProfilAnggota))"
                                                            class="flex-1 px-2.5 py-1.5 bg-background hover:bg-border/40 border border-border text-text text-[11px] font-semibold rounded-md transition-colors cursor-pointer text-center">
                                                            Pratinjau Profil
                                                        </button>

                                                        @if ($anggota->user)
                                                            <a href="{{ route('admin-mahasiswa-detail', $anggota->user->id) }}"
                                                                class="px-2.5 py-1.5 bg-primary/10 hover:bg-primary/20 text-primary text-[11px] font-bold rounded-md transition-colors">
                                                                Detail Lengkap
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Info Status Konfirmasi ASN / Penolakan -->
                                @if ($pengajuan->status_pengajuan === 'menunggu_asn' && $pengajuan->calonPembimbing)
                                    <div class="bg-warning/10 border border-warning/30 rounded-xl p-3.5 text-xs text-text flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                        <div>
                                            <span class="font-bold text-warning-dark">Menunggu Persetujuan ASN:</span>
                                            <strong class="ml-1">{{ $pengajuan->calonPembimbing->user?->name ?? '-' }}</strong>
                                            ({{ $pengajuan->calonPembimbing->unit_kerja ?? '-' }})
                                        </div>
                                        @if ($pengajuan->dikirim_ke_asn_at)
                                            <span class="text-text-light">
                                                Dikirim pada {{ $pengajuan->dikirim_ke_asn_at->translatedFormat('d M Y H:i') }}
                                            </span>
                                        @endif
                                    </div>
                                @elseif ($pengajuan->status_pengajuan === 'ditolak_asn')
                                    <div class="bg-danger/10 border border-danger/30 rounded-xl p-4 text-xs space-y-1.5">
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                                            <p class="font-bold text-danger">
                                                Ditolak oleh ASN: {{ $pengajuan->calonPembimbing?->user?->name ?? 'Kandidat ASN' }}
                                            </p>
                                            @if ($pengajuan->direspon_asn_at)
                                                <span class="text-text-light">
                                                    Direspons {{ $pengajuan->direspon_asn_at->translatedFormat('d M Y H:i') }}
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-text">
                                            <span class="font-semibold">Pesan ASN:</span> "{{ $pengajuan->pesan_penolakan_asn }}"
                                        </p>
                                    </div>
                                @elseif ($pengajuan->status_pengajuan === 'diterima' && $pengajuan->pembimbing)
                                    <div class="bg-success/5 border border-success/20 rounded-xl p-3.5 text-xs text-text flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                        <div>
                                            <span class="font-semibold text-success-dark">Disetujui oleh Pembimbing ASN:</span>
                                            <strong class="ml-1">{{ $pengajuan->pembimbing->user?->name ?? '-' }}</strong>
                                            ({{ $pengajuan->pembimbing->unit_kerja ?? '-' }})
                                        </div>
                                        @if ($pengajuan->direspon_asn_at)
                                            <span class="text-text-light">
                                                Diterima pada {{ $pengajuan->direspon_asn_at->translatedFormat('d M Y H:i') }}
                                            </span>
                                        @endif
                                    </div>
                                @elseif ($pengajuan->status_pengajuan === 'ditolak')
                                    <div class="bg-danger/5 border border-danger/20 rounded-xl p-3.5 text-xs space-y-1">
                                        <p class="font-bold text-danger">Alasan Penolakan Admin:</p>
                                        <p class="text-text">{{ $pengajuan->alasan_penolakan }}</p>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="bg-surface border border-border rounded-2xl p-12 text-center">
                                <p class="text-base font-bold text-text">Tidak Ada Data Permintaan</p>
                                <p class="text-sm text-text-light mt-1">
                                    Belum ada pengajuan pembimbing dari peserta magang pada kategori ini.
                                </p>
                            </div>
                        @endforelse
                    </div>

                </section>
            </section>
        </section>
    </main>

    <!-- POPUP / MODAL 1: PILIH KANDIDAT ASN & KIRIM PERMINTAAN -->
    <div id="modal-kirim-asn"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-xs p-4">
        <div class="bg-surface border border-border rounded-2xl max-w-xl w-full p-6 shadow-2xl space-y-5 font-montserrat">
            <div class="flex items-start justify-between gap-4 border-b border-border pb-3">
                <div>
                    <h3 class="text-lg font-bold text-text">Kirim Permintaan ke Pembimbing ASN</h3>
                    <p id="modal-asn-subtitle" class="text-xs text-text-light mt-0.5">
                        Pilih ASN untuk dikirimi permintaan kesediaan membimbing peserta.
                    </p>
                </div>
                <button type="button" onclick="tutupModalKirimAsn()"
                    class="text-text-light hover:text-text text-lg font-bold cursor-pointer">✕</button>
            </div>

            <form id="form-kirim-asn" method="POST" action="">
                @csrf
                <div class="space-y-3 max-h-80 overflow-y-auto pr-1">
                    @forelse ($pembimbingList as $asn)
                        <label id="asn-option-{{ $asn->id }}"
                            class="asn-option-label flex items-start gap-3 p-3.5 rounded-xl border border-border bg-background hover:border-primary cursor-pointer transition-colors">
                            <input type="radio" name="asn_profile_id" value="{{ $asn->id }}" required
                                class="asn-radio-input mt-1 text-primary focus:ring-primary">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2">
                                    <p class="text-sm font-bold text-text truncate">
                                        {{ $asn->user?->name ?? '-' }}
                                        <span class="asn-rejected-tag hidden ml-1.5 text-[10px] font-bold text-danger">(Menolak sebelumnya)</span>
                                    </p>
                                    <span class="shrink-0 px-2 py-0.5 rounded-full text-[11px] font-bold bg-primary/10 text-primary">
                                        {{ $asn->jumlah_bimbingan }} Bimbingan Aktif
                                    </span>
                                </div>
                                <p class="text-xs text-text-light mt-0.5">
                                    NIP: {{ $asn->nip }} • {{ $asn->jabatan ?? '-' }}
                                </p>
                                <p class="text-[11px] text-text-light">
                                    {{ $asn->unit_kerja ?? '-' }}
                                </p>
                            </div>
                        </label>
                    @empty
                        <p class="text-sm text-danger">Belum ada akun pembimbing ASN yang aktif.</p>
                    @endforelse
                </div>

                <div class="flex justify-end gap-2.5 pt-5 mt-5 border-t border-border">
                    <button type="button" onclick="tutupModalKirimAsn()"
                        class="px-4 py-2 rounded-lg border border-border bg-background text-xs font-semibold text-text-light hover:text-text cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2 rounded-lg bg-primary hover:bg-primary-dark text-white text-xs font-bold cursor-pointer shadow-xs">
                        Kirim Permintaan ke ASN
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- POPUP / MODAL 2: TOLAK PENGAJUAN + FORM ALASAN PENOLAKAN -->
    <div id="modal-tolak-pengajuan"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-xs p-4">
        <div class="bg-surface border border-border rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-5 font-montserrat">
            <div class="flex items-start justify-between gap-4 border-b border-border pb-3">
                <div>
                    <h3 class="text-lg font-bold text-danger">Tolak Pengajuan Magang</h3>
                    <p id="modal-tolak-subtitle" class="text-xs text-text-light mt-0.5">
                        Masukkan alasan penolakan agar peserta dapat memperbaiki data pengajuannya.
                    </p>
                </div>
                <button type="button" onclick="tutupModalTolak()"
                    class="text-text-light hover:text-text text-lg font-bold cursor-pointer">✕</button>
            </div>

            <form id="form-tolak-pengajuan" method="POST" action="">
                @csrf
                <div class="space-y-2">
                    <label for="textarea-alasan-penolakan" class="block text-xs font-bold text-text">
                        Alasan Penolakan <span class="text-danger">*</span>
                    </label>
                    <textarea id="textarea-alasan-penolakan" name="alasan_penolakan" rows="4" required
                        placeholder="Tuliskan alasan penolakan secara jelas..."
                        class="w-full rounded-xl border border-border bg-background p-3.5 text-sm text-text placeholder:text-text-light focus:outline-none focus:border-danger focus:ring-1 focus:ring-danger"></textarea>
                </div>

                <div class="flex justify-end gap-2.5 pt-5 mt-5 border-t border-border">
                    <button type="button" onclick="tutupModalTolak()"
                        class="px-4 py-2 rounded-lg border border-border bg-background text-xs font-semibold text-text-light hover:text-text cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2 rounded-lg bg-danger hover:opacity-90 text-white text-xs font-bold cursor-pointer shadow-xs">
                        Kirim Penolakan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- POPUP / MODAL 3: DETAIL PROFIL ANGGOTA KELOMPOK -->
    <div id="modal-profil-anggota"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-xs p-4">
        <div class="bg-surface border border-border rounded-2xl max-w-xl w-full p-6 shadow-2xl space-y-5 font-montserrat max-h-[90vh] overflow-y-auto">
            <div class="flex items-start justify-between gap-4 border-b border-border pb-3">
                <div class="flex items-center gap-3">
                    <img id="anggota-modal-foto" src="" alt="Foto Profil"
                        class="w-12 h-12 rounded-full object-cover border border-border">
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 id="anggota-modal-nama" class="text-base font-bold text-text">-</h3>
                            <span id="anggota-modal-peran"
                                class="px-2 py-0.5 text-[10px] font-bold rounded bg-primary/10 text-primary">-</span>
                        </div>
                        <p id="anggota-modal-email" class="text-xs text-text-light">-</p>
                    </div>
                </div>
                <button type="button" onclick="tutupModalProfilAnggota()"
                    class="text-text-light hover:text-text text-lg font-bold cursor-pointer">✕</button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                <div class="bg-background border border-border rounded-xl p-3">
                    <p class="text-text-light">NIM / NIS</p>
                    <p id="anggota-modal-nim" class="font-mono font-bold text-text mt-0.5">-</p>
                </div>
                <div class="bg-background border border-border rounded-xl p-3">
                    <p class="text-text-light">No. Telepon / WhatsApp</p>
                    <p id="anggota-modal-phone" class="font-semibold text-text mt-0.5">-</p>
                </div>
                <div class="bg-background border border-border rounded-xl p-3">
                    <p class="text-text-light">Instansi Asal</p>
                    <p id="anggota-modal-instansi" class="font-semibold text-text mt-0.5">-</p>
                </div>
                <div class="bg-background border border-border rounded-xl p-3">
                    <p class="text-text-light">Jenjang & Jurusan</p>
                    <p id="anggota-modal-jurusan" class="font-semibold text-text mt-0.5">-</p>
                </div>
                <div class="bg-background border border-border rounded-xl p-3">
                    <p class="text-text-light">Tanggal Lahir</p>
                    <p id="anggota-modal-ttl" class="font-semibold text-text mt-0.5">-</p>
                </div>
                <div class="bg-background border border-border rounded-xl p-3">
                    <p class="text-text-light">Periode Magang</p>
                    <p id="anggota-modal-periode" class="font-semibold text-text mt-0.5">-</p>
                </div>
                <div class="sm:col-span-2 bg-background border border-border rounded-xl p-3">
                    <p class="text-text-light">Alamat</p>
                    <p id="anggota-modal-alamat" class="font-semibold text-text mt-0.5">-</p>
                </div>
                <div class="sm:col-span-2 bg-background border border-border rounded-xl p-3">
                    <p class="text-text-light mb-1.5">Keahlian (Skills)</p>
                    <div id="anggota-modal-skills" class="flex flex-wrap gap-1.5"></div>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-2.5 pt-4 border-t border-border">
                <a id="anggota-modal-surat" href="#" target="_blank"
                    class="hidden px-3.5 py-2 rounded-lg border border-primary/30 bg-primary/10 text-primary text-xs font-bold hover:bg-primary/20">
                    Lihat Surat Pengantar
                </a>

                <div class="flex items-center gap-2 ml-auto">
                    <a id="anggota-modal-detail-link" href="#"
                        class="px-4 py-2 rounded-lg bg-primary hover:bg-primary-dark text-white text-xs font-bold">
                        Buka Halaman Detail
                    </a>
                    <button type="button" onclick="tutupModalProfilAnggota()"
                        class="px-4 py-2 rounded-lg border border-border bg-background text-xs font-semibold text-text-light hover:text-text cursor-pointer">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const basePermintaanUrl = @json(url('/admin/permintaan-magang'));

        function bukaModalKirimAsn(profilId, namaPemohon, jumlahOrang, rejectedAsnId = 0) {
            const modal = document.getElementById('modal-kirim-asn');
            const form = document.getElementById('form-kirim-asn');
            const subtitle = document.getElementById('modal-asn-subtitle');

            form.action = `${basePermintaanUrl}/${profilId}/kirim-asn`;
            subtitle.textContent = jumlahOrang > 1
                ? `Pilih ASN untuk dikirimi permintaan membimbing kelompok ${namaPemohon} (${jumlahOrang} peserta).`
                : `Pilih ASN untuk dikirimi permintaan membimbing peserta ${namaPemohon}.`;

            document.querySelectorAll('.asn-option-label').forEach(label => {
                const radio = label.querySelector('.asn-radio-input');
                const tag = label.querySelector('.asn-rejected-tag');
                const isRejected = rejectedAsnId > 0 && Number(radio.value) === Number(rejectedAsnId);

                radio.checked = false;
                radio.disabled = isRejected;
                label.classList.toggle('opacity-50', isRejected);
                label.classList.toggle('cursor-not-allowed', isRejected);
                if (tag) tag.classList.toggle('hidden', !isRejected);
            });

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function tutupModalKirimAsn() {
            const modal = document.getElementById('modal-kirim-asn');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function bukaModalTolak(profilId, namaPemohon) {
            const modal = document.getElementById('modal-tolak-pengajuan');
            const form = document.getElementById('form-tolak-pengajuan');
            const subtitle = document.getElementById('modal-tolak-subtitle');
            const textarea = document.getElementById('textarea-alasan-penolakan');

            form.action = `${basePermintaanUrl}/${profilId}/tolak`;
            subtitle.textContent = `Tuliskan alasan penolakan untuk pengajuan atas nama ${namaPemohon}.`;
            textarea.value = '';

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            textarea.focus();
        }

        function tutupModalTolak() {
            const modal = document.getElementById('modal-tolak-pengajuan');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function bukaModalProfilAnggota(data) {
            document.getElementById('anggota-modal-foto').src = data.foto;
            document.getElementById('anggota-modal-nama').textContent = data.name;
            document.getElementById('anggota-modal-peran').textContent = data.peran;
            document.getElementById('anggota-modal-email').textContent = data.email;
            document.getElementById('anggota-modal-nim').textContent = data.nim;
            document.getElementById('anggota-modal-phone').textContent = data.phone;
            document.getElementById('anggota-modal-instansi').textContent = data.instansi_asal;
            document.getElementById('anggota-modal-jurusan').textContent = `${data.jenjang} - ${data.jurusan}`;
            document.getElementById('anggota-modal-ttl').textContent = data.tanggal_lahir;
            document.getElementById('anggota-modal-periode').textContent = data.periode;
            document.getElementById('anggota-modal-alamat').textContent = data.alamat;

            const skillsContainer = document.getElementById('anggota-modal-skills');
            skillsContainer.innerHTML = '';
            if (Array.isArray(data.skills) && data.skills.length > 0) {
                data.skills.forEach(skill => {
                    const badge = document.createElement('span');
                    badge.className = 'px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-primary/10 text-primary border border-primary/20';
                    badge.textContent = skill;
                    skillsContainer.appendChild(badge);
                });
            } else {
                skillsContainer.innerHTML = '<span class="text-text-light italic">Belum menambahkan skill</span>';
            }

            const suratBtn = document.getElementById('anggota-modal-surat');
            if (data.surat_url) {
                suratBtn.href = data.surat_url;
                suratBtn.classList.remove('hidden');
            } else {
                suratBtn.classList.add('hidden');
            }

            const detailBtn = document.getElementById('anggota-modal-detail-link');
            if (data.detail_url) {
                detailBtn.href = data.detail_url;
                detailBtn.classList.remove('hidden');
            } else {
                detailBtn.classList.add('hidden');
            }

            const modal = document.getElementById('modal-profil-anggota');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function tutupModalProfilAnggota() {
            const modal = document.getElementById('modal-profil-anggota');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
@endsection
