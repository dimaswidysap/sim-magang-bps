@extends('layouts.app')

@section('content')
    <main class="relative w-full min-h-screen flex bg-background overflow-x-hidden">
        @include('components.header-mobile')
        @include('components.asn.asn-sidebar')

        <section class="flex flex-col flex-1 min-w-0 md:pl-60 container-content-mobile">
            @include('components.asn.header-asn')

            <section class="w-full p-2">
                <section class="container-dalam flex flex-col gap-4 font-montserrat">

                    <!-- Header Halaman -->
                    <div>
                        <h1 class="text-xl md:text-2xl font-bold text-text">Pengajuan Libur Peserta Magang</h1>
                        <p class="text-xs md:text-sm text-text-light mt-0.5">
                            Tinjau surat pengajuan izin/libur dari peserta magang bimbingan Anda, lalu setujui atau tolak disertai catatan.
                        </p>
                    </div>

                    <!-- Alert Notifikasi -->
                    @if (session('success'))
                        <div class="p-4 rounded-xl bg-success/10 border border-success/30 text-success-dark text-sm font-medium">
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
                        <a href="{{ route('asn.pengajuan-libur.index', ['status' => 'pending']) }}"
                            class="px-4 py-2 rounded-lg text-xs font-bold transition-colors {{ $filterStatus === 'pending' ? 'bg-primary text-white shadow-xs' : 'bg-surface border border-border text-text-light hover:text-text' }}">
                            Menunggu Persetujuan ({{ $jumlahPending }})
                        </a>

                        <a href="{{ route('asn.pengajuan-libur.index', ['status' => 'disetujui']) }}"
                            class="px-4 py-2 rounded-lg text-xs font-bold transition-colors {{ $filterStatus === 'disetujui' ? 'bg-success text-white shadow-xs' : 'bg-surface border border-border text-text-light hover:text-text' }}">
                            Disetujui ({{ $jumlahDisetujui }})
                        </a>

                        <a href="{{ route('asn.pengajuan-libur.index', ['status' => 'ditolak']) }}"
                            class="px-4 py-2 rounded-lg text-xs font-bold transition-colors {{ $filterStatus === 'ditolak' ? 'bg-danger text-white shadow-xs' : 'bg-surface border border-border text-text-light hover:text-text' }}">
                            Ditolak ({{ $jumlahDitolak }})
                        </a>

                        <a href="{{ route('asn.pengajuan-libur.index', ['status' => 'semua']) }}"
                            class="px-4 py-2 rounded-lg text-xs font-bold transition-colors {{ $filterStatus === 'semua' ? 'bg-text text-white shadow-xs' : 'bg-surface border border-border text-text-light hover:text-text' }}">
                            Semua ({{ $jumlahSemua }})
                        </a>
                    </div>

                    <!-- Daftar Pengajuan Libur -->
                    <div class="flex flex-col gap-4">
                        @forelse ($daftarPengajuan as $item)
                            @php
                                $mhsProfil = $item->mahasiswaProfile;
                                $mhsUser = $mhsProfil?->user;
                            @endphp

                            <div class="bg-surface border border-border rounded-2xl p-5 md:p-6 shadow-xs space-y-4">
                                <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4 border-b border-border pb-4">
                                    <div class="space-y-1.5">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-primary/10 text-primary border border-primary/20">
                                                {{ $item->jumlah_hari }} Hari Libur
                                            </span>

                                            @if ($item->status === 'pending')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-warning/15 text-warning-dark border border-warning/30">
                                                    Menunggu Konfirmasi Anda
                                                </span>
                                            @elseif ($item->status === 'disetujui')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-success/10 text-success border border-success/20">
                                                    Disetujui
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-danger/10 text-danger border border-danger/20">
                                                    Ditolak
                                                </span>
                                            @endif
                                        </div>

                                        <h2 class="text-lg font-bold text-text">
                                            {{ $mhsUser?->name ?? '-' }}
                                        </h2>

                                        <p class="text-xs text-text-light">
                                            NIM: <span class="font-mono font-semibold text-text">{{ $mhsProfil?->nim ?? '-' }}</span>
                                            • {{ $mhsProfil?->instansi_asal ?? '-' }} ({{ $mhsProfil?->jurusan ?? '-' }})
                                        </p>

                                        <p class="text-xs text-text-light">
                                            Tanggal Libur:
                                            <strong class="text-text">{{ $item->tanggal_mulai->translatedFormat('d M Y') }}</strong>
                                            @if (! $item->tanggal_mulai->equalTo($item->tanggal_selesai))
                                                s.d.
                                                <strong class="text-text">{{ $item->tanggal_selesai->translatedFormat('d M Y') }}</strong>
                                            @endif
                                            <span class="ml-2">• Diajukan {{ $item->created_at->diffForHumans() }}</span>
                                        </p>
                                    </div>

                                    <!-- Tombol Aksi ASN -->
                                    <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                                        <a href="{{ route('asn.pengajuan-libur.show', $item->id) }}"
                                            class="px-3.5 py-2.5 bg-background hover:bg-border/40 border border-border text-text text-xs font-semibold rounded-lg transition-colors inline-flex items-center gap-1.5">
                                            Lihat Detail & Lampiran
                                        </a>

                                        @if ($item->status === 'pending')
                                            <form method="POST"
                                                action="{{ route('asn.pengajuan-libur.setujui', $item->id) }}"
                                                data-confirm="Apakah Anda yakin menyetujui pengajuan libur dari {{ $mhsUser?->name ?? 'peserta ini' }}?">
                                                @csrf
                                                <button type="submit"
                                                    class="px-4 py-2.5 bg-success hover:bg-success-dark text-white text-xs font-bold rounded-lg transition-colors cursor-pointer shadow-xs">
                                                    Setujui
                                                </button>
                                            </form>

                                            <button type="button"
                                                onclick="bukaModalTolakLibur({{ $item->id }}, @js($mhsUser?->name ?? 'Peserta'))"
                                                class="px-4 py-2.5 bg-danger hover:opacity-90 text-white text-xs font-bold rounded-lg transition-opacity cursor-pointer shadow-xs">
                                                Tolak
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                <!-- Ringkasan Alasan -->
                                <div class="text-xs text-text bg-background border border-border rounded-xl p-3.5">
                                    <span class="font-bold text-text-light uppercase block mb-1">Alasan:</span>
                                    <p class="leading-relaxed">{{ $item->alasan }}</p>
                                </div>

                                @if ($item->status !== 'pending' && $item->catatan_asn)
                                    <div class="text-xs p-3.5 rounded-xl border {{ $item->status === 'disetujui' ? 'bg-success/5 border-success/20 text-success-dark' : 'bg-danger/5 border-danger/20 text-danger' }}">
                                        <strong>Catatan Anda:</strong> {{ $item->catatan_asn }}
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="bg-surface border border-border rounded-2xl p-12 text-center">
                                <p class="text-base font-bold text-text">Tidak Ada Pengajuan Libur</p>
                                <p class="text-sm text-text-light mt-1">
                                    Belum ada pengajuan surat libur dari peserta magang bimbingan Anda pada kategori ini.
                                </p>
                            </div>
                        @endforelse
                    </div>

                    @if ($daftarPengajuan->total() > 0)
                        <div class="pt-2">
                            {{ $daftarPengajuan->links() }}
                        </div>
                    @endif

                </section>
            </section>
        </section>
    </main>

    <!-- MODAL PENOLAKAN PENGAJUAN LIBUR OLEH ASN -->
    <div id="modal-tolak-libur"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-xs p-4">
        <div class="bg-surface border border-border rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-5 font-montserrat">
            <div class="flex items-start justify-between gap-4 border-b border-border pb-3">
                <div>
                    <h3 class="text-lg font-bold text-danger">Tolak Pengajuan Libur</h3>
                    <p id="modal-tolak-libur-subtitle" class="text-xs text-text-light mt-0.5">
                        Tuliskan alasan penolakan surat libur peserta.
                    </p>
                </div>
                <button type="button" onclick="tutupModalTolakLibur()"
                    class="text-text-light hover:text-text text-lg font-bold cursor-pointer">✕</button>
            </div>

            <form id="form-tolak-libur" method="POST" action="">
                @csrf
                <div class="space-y-2">
                    <label for="textarea-catatan-tolak-libur" class="block text-xs font-bold text-text">
                        Alasan Penolakan <span class="text-danger">*</span>
                    </label>
                    <textarea id="textarea-catatan-tolak-libur" name="catatan_asn" rows="4" required
                        placeholder="Tuliskan alasan penolakan pengajuan libur secara jelas..."
                        class="w-full rounded-xl border border-border bg-background p-3.5 text-sm text-text placeholder:text-text-light focus:outline-none focus:border-danger focus:ring-1 focus:ring-danger"></textarea>
                </div>

                <div class="flex justify-end gap-2.5 pt-5 mt-5 border-t border-border">
                    <button type="button" onclick="tutupModalTolakLibur()"
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

    <script>
        const baseAsnLiburUrl = @json(url('/asn/pengajuan-libur'));

        function bukaModalTolakLibur(liburId, namaPeserta) {
            const modal = document.getElementById('modal-tolak-libur');
            const form = document.getElementById('form-tolak-libur');
            const subtitle = document.getElementById('modal-tolak-libur-subtitle');
            const textarea = document.getElementById('textarea-catatan-tolak-libur');

            form.action = `${baseAsnLiburUrl}/${liburId}/tolak`;
            subtitle.textContent = `Tuliskan alasan penolakan pengajuan libur atas nama ${namaPeserta}.`;
            textarea.value = '';

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            textarea.focus();
        }

        function tutupModalTolakLibur() {
            const modal = document.getElementById('modal-tolak-libur');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
@endsection
