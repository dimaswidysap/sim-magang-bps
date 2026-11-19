@extends('layouts.app')

@section('content')
    <main class="relative w-full min-h-screen flex bg-background overflow-x-hidden">
        <section class="flex flex-col flex-1 min-w-0  container-content-mobile">
            <section class="w-full min-w-0 p-4 md:p-6 font-montserrat">
                <div class="max-w-5xl mx-auto space-y-6">

                    <!-- Toolbar Aksi Atas -->
                    <div
                        class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-surface border border-border rounded-xl p-4 shadow-xs">
                        <div>
                            <h1 class="text-lg md:text-xl font-bold text-text">Pratinjau Laporan Magang</h1>
                            <p class="text-xs text-text-light mt-0.5">
                                Periksa rincian rekapitulasi kegiatan peserta sebelum mengunduh berkas PDF.
                            </p>
                        </div>

                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5">
                            <x-buttonv2 href="{{ route('admin-laporan') }}" color="primary" class="w-full sm:w-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Kembali
                            </x-buttonv2>

                            <x-buttonv2 href="{{ route('admin.laporan.pdf', $mahasiswa->id) }}" color="accent-dark"
                                class="w-full sm:w-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Download PDF
                            </x-buttonv2>
                        </div>
                    </div>

                    <!-- Lembar Dokumen Laporan -->
                    <div class="bg-surface border border-border rounded-2xl shadow-sm p-6 md:p-10 space-y-8">

                        <!-- Kop Laporan -->
                        <div class="border-b-2 border-text pb-5 text-center space-y-1">
                            <p class="text-xs font-bold uppercase tracking-widest text-primary">
                                Sistem Informasi Manajemen Magang / Praktik Kerja Lapangan
                            </p>
                            <h2 class="text-xl md:text-2xl font-bold text-text uppercase tracking-wide">
                                Laporan Akhir Kegiatan Peserta Magang
                            </h2>
                            <p class="text-xs text-text-light">
                                Dicetak pada: {{ $tanggalCetak->translatedFormat('d F Y, H:i') }} WIB
                            </p>
                        </div>

                        <!-- Bagian A: Identitas Peserta & Pembimbing -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Data Peserta -->
                            <div class="bg-background border border-border rounded-xl p-5 space-y-3">
                                <h3
                                    class="text-xs font-bold uppercase tracking-wider text-primary border-b border-border pb-2">
                                    A. Identitas Peserta Magang
                                </h3>
                                <dl class="space-y-2 text-xs sm:text-sm">
                                    <div class="flex justify-between gap-4">
                                        <dt class="text-text-light">Nama Lengkap</dt>
                                        <dd class="font-bold text-text text-right">{{ $mahasiswa->name }}</dd>
                                    </div>
                                    <div class="flex justify-between gap-4">
                                        <dt class="text-text-light">NIM / NIS</dt>
                                        <dd class="font-mono font-semibold text-text text-right">{{ $profil->nim ?? '-' }}
                                        </dd>
                                    </div>
                                    <div class="flex justify-between gap-4">
                                        <dt class="text-text-light">Instansi Asal</dt>
                                        <dd class="font-semibold text-text text-right">{{ $profil->instansi_asal ?? '-' }}
                                        </dd>
                                    </div>
                                    <div class="flex justify-between gap-4">
                                        <dt class="text-text-light">Jenjang / Jurusan</dt>
                                        <dd class="font-medium text-text text-right">
                                            {{ $profil->jenjang ?? '-' }} - {{ $profil->jurusan ?? '-' }}
                                        </dd>
                                    </div>
                                    <div class="flex justify-between gap-4">
                                        <dt class="text-text-light">Email / No. HP</dt>
                                        <dd class="text-text text-right">{{ $mahasiswa->email }}
                                            {{ $mahasiswa->phone ? ' / ' . $mahasiswa->phone : '' }}</dd>
                                    </div>
                                </dl>
                            </div>

                            <!-- Data Pelaksanaan & Pembimbing -->
                            <div class="bg-background border border-border rounded-xl p-5 space-y-3">
                                <h3
                                    class="text-xs font-bold uppercase tracking-wider text-primary border-b border-border pb-2">
                                    B. Pelaksanaan & Pembimbing ASN
                                </h3>
                                <dl class="space-y-2 text-xs sm:text-sm">
                                    <div class="flex justify-between gap-4">
                                        <dt class="text-text-light">Pembimbing ASN</dt>
                                        <dd class="font-bold text-text text-right">{{ $pembimbing?->user?->name ?? '-' }}
                                        </dd>
                                    </div>
                                    <div class="flex justify-between gap-4">
                                        <dt class="text-text-light">NIP Pembimbing</dt>
                                        <dd class="font-mono text-text text-right">{{ $pembimbing?->nip ?? '-' }}</dd>
                                    </div>
                                    <div class="flex justify-between gap-4">
                                        <dt class="text-text-light">Jabatan / Unit</dt>
                                        <dd class="text-text text-right">
                                            {{ $pembimbing?->jabatan ?? '-' }}
                                            @if ($pembimbing?->unit_kerja)
                                                <span
                                                    class="block text-xs text-text-light">{{ $pembimbing->unit_kerja }}</span>
                                            @endif
                                        </dd>
                                    </div>
                                    <div class="flex justify-between gap-4">
                                        <dt class="text-text-light">Periode Magang</dt>
                                        <dd class="font-semibold text-text text-right">
                                            {{ $profil->tanggal_mulai?->translatedFormat('d F Y') ?? '-' }}
                                            s.d.
                                            {{ $profil->tanggal_selesai?->translatedFormat('d F Y') ?? '-' }}
                                        </dd>
                                    </div>
                                    <div class="flex justify-between gap-4">
                                        <dt class="text-text-light">Status Magang</dt>
                                        <dd class="text-right">
                                            <span
                                                class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-bold bg-success/10 text-success border border-success/25 uppercase">
                                                Selesai
                                            </span>
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <!-- Kartu Statistik Capaian -->
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                            <div class="bg-background border border-border rounded-xl p-4 text-center">
                                <p class="text-xs text-text-light font-medium">Total Hari Masuk</p>
                                <p class="text-2xl font-bold text-success mt-1">{{ $totalHariMasuk }}</p>
                                <p class="text-[11px] text-text-light mt-0.5">Hari aktif tervalidasi</p>
                            </div>
                            <div class="bg-background border border-border rounded-xl p-4 text-center">
                                <p class="text-xs text-text-light font-medium">Total Hari Libur</p>
                                <p class="text-2xl font-bold text-warning-dark mt-1">{{ $totalHariLibur }}</p>
                                <p class="text-[11px] text-text-light mt-0.5">Hari izin/libur disetujui</p>
                            </div>
                            <div class="bg-background border border-border rounded-xl p-4 text-center">
                                <p class="text-xs text-text-light font-medium">Tugas Selesai</p>
                                <p class="text-2xl font-bold text-primary mt-1">{{ $totalTugasSelesai }} <span
                                        class="text-sm font-normal text-text-light">/ {{ $totalTugasDiikuti }}</span></p>
                                <p class="text-[11px] text-text-light mt-0.5">Penugasan ASN</p>
                            </div>
                            <div class="bg-background border border-border rounded-xl p-4 text-center">
                                <p class="text-xs text-text-light font-medium">Logbook Mandiri</p>
                                <p class="text-2xl font-bold text-text mt-1">{{ $totalLogbookMandiri }}</p>
                                <p class="text-[11px] text-text-light mt-0.5">Catatan tervalidasi</p>
                            </div>
                            <div class="bg-background border border-border rounded-xl p-4 text-center col-span-2 sm:col-span-1">
                                <p class="text-xs text-text-light font-medium">Durasi Kalender</p>
                                <p class="text-2xl font-bold text-text mt-1">{{ $durasiHariKalender }}</p>
                                <p class="text-[11px] text-text-light mt-0.5">Hari periode magang</p>
                            </div>
                        </div>

                        <!-- Bagian C: Rekapitulasi Tugas -->
                        <div class="space-y-3">
                            <h3 class="text-sm font-bold uppercase tracking-wider text-primary border-b border-border pb-2">
                                C. Rekapitulasi Penugasan Selama Magang
                            </h3>

                            @if ($daftarTugas->isEmpty())
                                <p class="text-xs text-text-light italic py-4">Tidak ada catatan penugasan pada periode ini.
                                </p>
                            @else
                                <div class="overflow-x-auto border border-border rounded-xl">
                                    <table class="w-full text-left border-collapse text-xs sm:text-sm">
                                        <thead
                                            class="bg-background border-b border-border text-[11px] uppercase text-text-light">
                                            <tr>
                                                <th class="px-4 py-3 text-center w-10">No</th>
                                                <th class="px-4 py-3">Judul & Deskripsi Tugas</th>
                                                <th class="px-4 py-3 whitespace-nowrap">Pemberi Tugas</th>
                                                <th class="px-4 py-3 whitespace-nowrap">Tanggal Selesai</th>
                                                <th class="px-4 py-3 text-center whitespace-nowrap">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-border">
                                            @foreach ($daftarTugas as $tugas)
                                                @php
                                                    $statusVal =
                                                        $tugas->status instanceof \BackedEnum
                                                            ? $tugas->status->value
                                                            : (string) $tugas->status;
                                                    $statusLabel = method_exists($tugas->status, 'label')
                                                        ? $tugas->status->label()
                                                        : ucfirst(str_replace('_', ' ', $statusVal));
                                                @endphp
                                                <tr>
                                                    <td class="px-4 py-3 text-center text-text-light">
                                                        {{ $loop->iteration }}</td>
                                                    <td class="px-4 py-3">
                                                        <p class="font-bold text-text">{{ $tugas->judul }}</p>
                                                        <p class="text-xs text-text-light mt-0.5 leading-relaxed">
                                                            {{ $tugas->deskripsi }}</p>
                                                        @if ($tugas->latestSubmission?->catatan_asn)
                                                            <p class="text-[11px] text-primary-dark mt-1">
                                                                <strong>Evaluasi Pembimbing:</strong>
                                                                {{ $tugas->latestSubmission->catatan_asn }}
                                                            </p>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-text">
                                                        {{ $tugas->asn?->name ?? '-' }}</td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-text-light">
                                                        {{ $tugas->selesai_at ? $tugas->selesai_at->translatedFormat('d M Y') : '-' }}
                                                    </td>
                                                    <td class="px-4 py-3 text-center whitespace-nowrap">
                                                        <span
                                                            class="inline-flex px-2.5 py-0.5 rounded-md text-[11px] font-bold uppercase {{ $statusVal === 'selesai' ? 'bg-success/10 text-success border border-success/20' : 'bg-warning/10 text-warning-dark border border-warning/20' }}">
                                                            {{ $statusLabel }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                        <!-- Bagian D: Rekapitulasi Logbook Kegiatan Harian -->
                        <div class="space-y-3">
                            <h3
                                class="text-sm font-bold uppercase tracking-wider text-primary border-b border-border pb-2">
                                D. Rekapitulasi Logbook Kegiatan Harian (Tervalidasi)
                            </h3>

                            @if ($logbookMandiri->isEmpty())
                                <p class="text-xs text-text-light italic py-4">Tidak ada logbook mandiri yang tervalidasi.
                                </p>
                            @else
                                <div class="overflow-x-auto border border-border rounded-xl">
                                    <table class="w-full text-left border-collapse text-xs sm:text-sm">
                                        <thead
                                            class="bg-background border-b border-border text-[11px] uppercase text-text-light">
                                            <tr>
                                                <th class="px-4 py-3 text-center w-10">No</th>
                                                <th class="px-4 py-3 whitespace-nowrap">Tanggal</th>
                                                <th class="px-4 py-3">Kegiatan & Uraian Aktivitas</th>
                                                <th class="px-4 py-3 text-center whitespace-nowrap">Validasi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-border">
                                            @foreach ($logbookMandiri as $item)
                                                <tr>
                                                    <td class="px-4 py-3 text-center text-text-light">
                                                        {{ $loop->iteration }}</td>
                                                    <td class="px-4 py-3 whitespace-nowrap font-medium text-text">
                                                        {{ $item->tanggal_kegiatan?->translatedFormat('d M Y') ?? '-' }}
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <p class="font-bold text-text">{{ $item->judul_kegiatan }}</p>
                                                        <p class="text-xs text-text-light mt-0.5 leading-relaxed">
                                                            {{ $item->deskripsi_kegiatan }}</p>
                                                    </td>
                                                    <td class="px-4 py-3 text-center whitespace-nowrap">
                                                        <span
                                                            class="inline-flex px-2 py-0.5 rounded-full text-[11px] font-bold bg-success/10 text-success border border-success/20">
                                                            Tervalidasi
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                        <!-- Bagian E: Rekapitulasi Pengajuan Libur (Disetujui) -->
                        <div class="space-y-3">
                            <h3
                                class="text-sm font-bold uppercase tracking-wider text-primary border-b border-border pb-2">
                                E. Rekapitulasi Hari Libur / Izin (Disetujui ASN)
                            </h3>

                            @if ($daftarLiburDisetujui->isEmpty())
                                <p class="text-xs text-text-light italic py-4">Tidak ada riwayat pengajuan libur yang disetujui selama periode magang.
                                </p>
                            @else
                                <div class="overflow-x-auto border border-border rounded-xl">
                                    <table class="w-full text-left border-collapse text-xs sm:text-sm">
                                        <thead
                                            class="bg-background border-b border-border text-[11px] uppercase text-text-light">
                                            <tr>
                                                <th class="px-4 py-3 text-center w-10">No</th>
                                                <th class="px-4 py-3 whitespace-nowrap">Tanggal Libur</th>
                                                <th class="px-4 py-3 text-center whitespace-nowrap">Durasi</th>
                                                <th class="px-4 py-3">Alasan Libur</th>
                                                <th class="px-4 py-3 whitespace-nowrap">Disetujui Oleh</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-border">
                                            @foreach ($daftarLiburDisetujui as $libur)
                                                <tr>
                                                    <td class="px-4 py-3 text-center text-text-light">
                                                        {{ $loop->iteration }}</td>
                                                    <td class="px-4 py-3 whitespace-nowrap font-medium text-text">
                                                        {{ $libur->tanggal_mulai?->translatedFormat('d M Y') ?? '-' }}
                                                        @if ($libur->tanggal_mulai && $libur->tanggal_selesai && ! $libur->tanggal_mulai->equalTo($libur->tanggal_selesai))
                                                            <span class="text-text-light font-normal"> s.d. </span>
                                                            {{ $libur->tanggal_selesai->translatedFormat('d M Y') }}
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-3 text-center whitespace-nowrap">
                                                        <span
                                                            class="inline-flex px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-warning/15 text-warning-dark border border-warning/30">
                                                            {{ $libur->jumlah_hari }} Hari
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <p class="text-text leading-relaxed">{{ $libur->alasan }}</p>
                                                        @if ($libur->catatan_asn)
                                                            <p class="text-[11px] text-text-light mt-1">
                                                                <strong>Catatan ASN:</strong> {{ $libur->catatan_asn }}
                                                            </p>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-text">
                                                        {{ $libur->pembimbing?->user?->name ?? ($pembimbing?->user?->name ?? '-') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                        <!-- Kolom Pengesahan / Tanda Tangan -->
                        <div class="pt-6 grid grid-cols-1 sm:grid-cols-2 gap-8 text-xs sm:text-sm">
                            <div class="text-center space-y-16">
                                <div>
                                    <p class="text-text-light">Peserta Magang / PKL,</p>
                                </div>
                                <div>
                                    <p class="font-bold text-text underline">{{ $mahasiswa->name }}</p>
                                    <p class="text-xs text-text-light">NIM. {{ $profil->nim ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="text-center space-y-16">
                                <div>
                                    <p class="text-text-light">Mengetahui,</p>
                                    <p class="font-medium text-text">Pembimbing Lapangan (ASN)</p>
                                </div>
                                <div>
                                    <p class="font-bold text-text underline">
                                        {{ $pembimbing?->user?->name ?? '........................................' }}</p>
                                    <p class="text-xs text-text-light">NIP. {{ $pembimbing?->nip ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
        </section>
    </main>
@endsection
