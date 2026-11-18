@vite(['resources/js/fitur-search.js'])
@extends('layouts.app')

@section('content')
    <main class=" relative w-full flex bg-background">
        {{-- container-sidebar-admin --}}
        @include('components.header-mobile')
        @include('components.asn.asn-sidebar')

        {{-- container-content --}}
        <section class="flex flex-col flex-1 md:pl-60 container-content-mobile">

            {{-- header --}}
            @include('components.asn.header-asn')

            <section class="w-full p-2">
                <section class="container-dalam flex flex-col gap-4 font-montserrat">

                    <!-- Header Halaman -->
                    <div>
                        <h1 class="text-xl md:text-2xl font-bold text-text">Permintaan Bimbingan Magang</h1>
                        <p class="text-xs md:text-sm text-text-light mt-1">
                            Konfirmasi kesediaan Anda untuk membimbing peserta magang yang diajukan oleh Admin, atau tolak
                            disertai pesan alasan.
                        </p>
                    </div>

                    <!-- Alert Notifikasi -->
                    @if (session('success'))
                        <div
                            class="p-4 rounded-xl bg-success/10 border border-success/30 text-success-dark text-sm font-medium">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="p-4 rounded-xl bg-danger/10 border border-danger/30 text-danger text-sm font-medium">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="p-4 rounded-xl bg-danger/10 border border-danger/30 text-danger text-sm">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Filter Tab -->
                    <div class="flex flex-wrap items-center gap-2 border-b border-border pb-3">
                        <a href="{{ route('asn-permintaan-bimbingan', ['status' => 'menunggu_asn']) }}"
                            class="px-4 py-2 rounded-lg text-xs font-bold transition-colors {{ $filterStatus === 'menunggu_asn' ? 'bg-primary text-white shadow-xs' : 'bg-surface border border-border text-text-light hover:text-text' }}">
                            Permintaan Masuk ({{ $jumlahMasuk }})
                        </a>

                        <a href="{{ route('asn-permintaan-bimbingan', ['status' => 'diterima']) }}"
                            class="px-4 py-2 rounded-lg text-xs font-bold transition-colors {{ $filterStatus === 'diterima' ? 'bg-success text-white shadow-xs' : 'bg-surface border border-border text-text-light hover:text-text' }}">
                            Sudah Diterima ({{ $jumlahDiterima }})
                        </a>
                    </div>

                    <!-- Daftar Permintaan -->
                    <div class="flex flex-col gap-4">
                        @forelse ($daftarPermintaan as $item)
                            @php
                                $userPemohon = $item->user;
                                $isKelompok = $item->isKelompok();
                                $anggotaList = $item->daftar_anggota ?? collect([$item]);
                            @endphp

                            <div class="bg-surface border border-border rounded-2xl p-5 md:p-6 shadow-xs space-y-5">
                                <div
                                    class="flex flex-col lg:flex-row lg:items-start justify-between gap-4 border-b border-border pb-4">
                                    <div class="space-y-1.5">
                                        <div class="flex flex-wrap items-center gap-2">
                                            @if ($isKelompok)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-primary/10 text-primary border border-primary/20">
                                                    Magang Kelompok ({{ $anggotaList->count() }} Peserta)
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-background text-text border border-border">
                                                    Magang Individu
                                                </span>
                                            @endif

                                            @if ($item->status_pengajuan === 'menunggu_asn')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-warning/15 text-warning-dark border border-warning/30">
                                                    Menunggu Konfirmasi Anda
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-success/10 text-success border border-success/20">
                                                    Diterima sebagai Bimbingan
                                                </span>
                                            @endif
                                        </div>

                                        <h2 class="text-lg font-bold text-text">
                                            {{ $userPemohon?->name ?? '-' }}
                                            @if ($isKelompok)
                                                <span class="text-xs font-normal text-text-light">(Ketua Kelompok)</span>
                                            @endif
                                        </h2>

                                        <p class="text-xs text-text-light">
                                            {{ $item->instansi_asal ?? '-' }} • {{ $item->jurusan ?? '-' }}
                                            ({{ $item->jenjang ?? '-' }})
                                            • NIM: <span
                                                class="font-mono font-semibold text-text">{{ $item->nim ?? '-' }}</span>
                                        </p>

                                        <p class="text-xs text-text-light">
                                            Periode Magang:
                                            <strong
                                                class="text-text">{{ $item->tanggal_mulai?->translatedFormat('d M Y') ?? '-' }}</strong>
                                            s.d.
                                            <strong
                                                class="text-text">{{ $item->tanggal_selesai?->translatedFormat('d M Y') ?? '-' }}</strong>
                                            @if ($item->dikirim_ke_asn_at)
                                                <span class="ml-2">• Dikirim Admin
                                                    {{ $item->dikirim_ke_asn_at->diffForHumans() }}</span>
                                            @endif
                                        </p>

                                        @if ($item->skills->isNotEmpty())
                                            <div class="flex flex-wrap items-center gap-1.5 pt-1">
                                                @foreach ($item->skills as $skill)
                                                    <span
                                                        class="px-2 py-0.5 text-[11px] font-medium rounded-md bg-background border border-border text-text-light">
                                                        {{ $skill->nama_skill }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Tombol Lihat Detail Profil & Terima / Tolak oleh ASN -->
                                    <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                                        @if ($userPemohon)
                                            <a href="{{ route('asn-mahasiswa-detail', $userPemohon->id) }}"
                                                class="px-3.5 py-2.5 bg-background hover:bg-border/40 border border-border text-text text-xs font-semibold rounded-lg transition-colors inline-flex items-center gap-1.5">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Lihat Detail Profil
                                            </a>
                                        @endif

                                        @if ($item->status_pengajuan === 'menunggu_asn')
                                            <form method="POST" action="{{ route('asn.permintaan.terima', $item->id) }}"
                                                data-confirm="Apakah Anda bersedia menerima peserta ini sebagai mahasiswa bimbingan magang Anda?">
                                                @csrf
                                                <button type="submit"
                                                    class="px-4 py-2.5 bg-success hover:bg-success-dark text-white text-xs font-bold rounded-lg transition-colors cursor-pointer shadow-xs inline-flex items-center gap-1.5">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    Terima Bimbingan
                                                </button>
                                            </form>

                                            <button type="button"
                                                onclick="bukaModalTolakAsn({{ $item->id }}, @js($userPemohon?->name ?? 'Peserta'))"
                                                class="px-4 py-2.5 bg-danger hover:opacity-90 text-white text-xs font-bold rounded-lg transition-opacity cursor-pointer shadow-xs inline-flex items-center gap-1.5">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                Tolak & Kirim Pesan
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                <!-- Daftar Anggota Jika Magang Kelompok -->
                                @if ($isKelompok)
                                    <div class="bg-background border border-border rounded-xl p-4 space-y-2.5">
                                        <p class="text-xs font-bold uppercase tracking-wider text-text-light">
                                            Anggota Kelompok ({{ $anggotaList->count() }} Orang)
                                        </p>
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                            @foreach ($anggotaList as $anggota)
                                                <div
                                                    class="bg-surface border border-border rounded-lg p-3 flex items-start justify-between gap-2">
                                                    <div class="min-w-0">
                                                        <p class="text-xs font-bold text-text truncate">
                                                            {{ $loop->iteration }}. {{ $anggota->user?->name ?? '-' }}
                                                        </p>
                                                        <p class="text-[11px] text-text-light truncate">
                                                            {{ $anggota->jurusan ?? '-' }}
                                                        </p>
                                                        <p class="text-[11px] font-mono text-text-light mt-0.5">
                                                            NIM: {{ $anggota->nim ?? '-' }}
                                                        </p>
                                                    </div>
                                                    <div class="flex flex-col items-end gap-1.5 shrink-0">
                                                        @if ($anggota->is_ketua_kelompok)
                                                            <span
                                                                class="px-2 py-0.5 text-[10px] font-bold rounded bg-primary/10 text-primary">
                                                                Ketua
                                                            </span>
                                                        @endif

                                                        @if ($anggota->user)
                                                            <a href="{{ route('asn-mahasiswa-detail', $anggota->user->id) }}"
                                                                class="text-[11px] font-semibold text-primary hover:underline">
                                                                Detail Profil →
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="bg-surface border border-border rounded-2xl p-12 text-center">
                                <p class="text-base font-bold text-text">Tidak Ada Permintaan Bimbingan</p>
                                <p class="text-sm text-text-light mt-1">
                                    Saat ini tidak ada permintaan bimbingan magang yang menunggu konfirmasi Anda.
                                </p>
                            </div>
                        @endforelse
                    </div>

                </section>
            </section>
        </section>
    </main>

    <!-- MODAL PENOLAKAN ASN + FORM PESAN KE ADMIN -->
    <div id="modal-tolak-asn"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-xs p-4">
        <div class="bg-surface border border-border rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-5 font-montserrat">
            <div class="flex items-start justify-between gap-4 border-b border-border pb-3">
                <div>
                    <h3 class="text-lg font-bold text-danger">Tolak Permintaan Bimbingan</h3>
                    <p id="modal-tolak-asn-subtitle" class="text-xs text-text-light mt-0.5">
                        Tuliskan pesan alasan penolakan untuk diteruskan kembali ke Admin.
                    </p>
                </div>
                <button type="button" onclick="tutupModalTolakAsn()"
                    class="text-text-light hover:text-text text-lg font-bold cursor-pointer">✕</button>
            </div>

            <form id="form-tolak-asn" method="POST" action="">
                @csrf
                <div class="space-y-2">
                    <label for="textarea-pesan-penolakan-asn" class="block text-xs font-bold text-text">
                        Pesan untuk Admin <span class="text-danger">*</span>
                    </label>
                    <textarea id="textarea-pesan-penolakan-asn" name="pesan_penolakan_asn" rows="4" required
                        placeholder="Contoh: Mohon maaf, kuota bimbingan di bidang saya bulan ini sudah penuh atau jadwal kegiatan lapangan sedang padat..."
                        class="w-full rounded-xl border border-border bg-background p-3.5 text-sm text-text placeholder:text-text-light focus:outline-none focus:border-danger focus:ring-1 focus:ring-danger"></textarea>
                </div>

                <div class="flex justify-end gap-2.5 pt-5 mt-5 border-t border-border">
                    <button type="button" onclick="tutupModalTolakAsn()"
                        class="px-4 py-2 rounded-lg border border-border bg-background text-xs font-semibold text-text-light hover:text-text cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2 rounded-lg bg-danger hover:opacity-90 text-white text-xs font-bold cursor-pointer shadow-xs">
                        Kirim Penolakan ke Admin
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const baseAsnPermintaanUrl = @json(url('/asn/permintaan-bimbingan'));

        function bukaModalTolakAsn(profilId, namaPemohon) {
            const modal = document.getElementById('modal-tolak-asn');
            const form = document.getElementById('form-tolak-asn');
            const subtitle = document.getElementById('modal-tolak-asn-subtitle');
            const textarea = document.getElementById('textarea-pesan-penolakan-asn');

            form.action = `${baseAsnPermintaanUrl}/${profilId}/tolak`;
            subtitle.textContent = `Tuliskan pesan penolakan untuk permintaan bimbingan atas nama ${namaPemohon}.`;
            textarea.value = '';

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            textarea.focus();
        }

        function tutupModalTolakAsn() {
            const modal = document.getElementById('modal-tolak-asn');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
@endsection
