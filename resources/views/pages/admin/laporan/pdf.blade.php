<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Magang - {{ $mahasiswa->name }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 18mm 16mm 18mm 16mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: "DejaVu Sans", Arial, Helvetica, sans-serif;
            font-size: 11px;
            line-height: 1.45;
            color: #1f2937;
            margin: 0;
            padding: 0;
            background: #ffffff;
        }

        .print-toolbar {
            background: #f3f4f6;
            border-bottom: 1px solid #d1d5db;
            padding: 10px 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .btn-action {
            display: inline-block;
            padding: 7px 14px;
            font-size: 12px;
            font-weight: bold;
            text-decoration: none;
            border-radius: 6px;
            cursor: pointer;
            border: 1px solid #d1d5db;
            background: #ffffff;
            color: #111827;
        }

        .btn-primary {
            background: #1e3a8a;
            border-color: #1e3a8a;
            color: #ffffff;
        }

        .container {
            max-width: 780px;
            margin: 0 auto;
            padding: {{ $isPdfEngine ?? false ? '0' : '12px 20px 32px 20px' }};
        }

        .kop {
            text-align: center;
            border-bottom: 2.5px solid #111827;
            padding-bottom: 10px;
            margin-bottom: 18px;
        }

        .kop-sub {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #1e3a8a;
            margin: 0 0 3px 0;
        }

        .kop-title {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0 0 4px 0;
            color: #111827;
        }

        .kop-meta {
            font-size: 9.5px;
            color: #6b7280;
            margin: 0;
        }

        .section-title {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            color: #1e3a8a;
            border-bottom: 1px solid #d1d5db;
            padding-bottom: 4px;
            margin: 16px 0 8px 0;
        }

        table.info-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        table.info-grid td {
            vertical-align: top;
            width: 50%;
            padding: 0;
        }

        table.info-grid td.left-col {
            padding-right: 10px;
        }

        table.info-grid td.right-col {
            padding-left: 10px;
        }

        table.kv {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #e5e7eb;
        }

        table.kv td {
            padding: 5px 8px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 10.5px;
        }

        table.kv td.label {
            width: 38%;
            color: #4b5563;
            background: #f9fafb;
        }

        table.kv td.value {
            font-weight: bold;
            color: #111827;
        }

        table.stats {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0 16px 0;
        }

        table.stats td {
            width: 25%;
            border: 1px solid #d1d5db;
            background: #f9fafb;
            text-align: center;
            padding: 8px 6px;
        }

        .stat-label {
            font-size: 9.5px;
            color: #4b5563;
            display: block;
        }

        .stat-num {
            font-size: 15px;
            font-weight: bold;
            color: #111827;
            display: block;
            margin: 2px 0;
        }

        .stat-sub {
            font-size: 8.5px;
            color: #6b7280;
            display: block;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        table.data-table th,
        table.data-table td {
            border: 1px solid #d1d5db;
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
            font-size: 10px;
        }

        table.data-table th {
            background: #f3f4f6;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
            color: #374151;
        }

        .text-center {
            text-align: center !important;
        }

        .nowrap {
            white-space: nowrap;
        }

        .item-title {
            font-weight: bold;
            color: #111827;
            margin-bottom: 2px;
        }

        .item-desc {
            color: #4b5563;
            font-size: 9.5px;
        }

        .badge-selesai {
            display: inline-block;
            padding: 2px 6px;
            font-size: 8.5px;
            font-weight: bold;
            text-transform: uppercase;
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
            border-radius: 4px;
        }

        table.ttd {
            width: 100%;
            border-collapse: collapse;
            margin-top: 26px;
            page-break-inside: avoid;
        }

        table.ttd td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            font-size: 10.5px;
        }

        .ttd-space {
            height: 62px;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            .container {
                padding: 0;
                max-width: 100%;
            }
        }
    </style>
</head>

<body>

    @if (!($isPdfEngine ?? false))
        <div class="print-toolbar no-print">
            <span style="font-size: 12px; color: #374151;">
                Gunakan tombol <strong>Simpan / Cetak PDF</strong> (pilih tujuan <em>Save as PDF</em>).
            </span>
            <div style="display: flex; gap: 8px;">
                <a href="{{ route('admin.laporan.show', $mahasiswa->id) }}" class="btn-action">Kembali</a>
                <button type="button" onclick="window.print()" class="btn-action btn-primary">Simpan / Cetak
                    PDF</button>
            </div>
        </div>
    @endif

    <div class="container">
        <!-- Kop Laporan -->
        <div class="kop">
            <p class="kop-sub">Sistem Informasi Manajemen Magang / Praktik Kerja Lapangan</p>
            <h1 class="kop-title">Laporan Akhir Kegiatan Peserta Magang</h1>
            <p class="kop-meta">Dicetak pada: {{ $tanggalCetak->translatedFormat('d F Y, H:i') }} WIB</p>
        </div>

        <!-- Identitas & Pembimbing -->
        <table class="info-grid">
            <tr>
                <td class="left-col">
                    <div class="section-title" style="margin-top: 0;">A. Identitas Peserta Magang</div>
                    <table class="kv">
                        <tr>
                            <td class="label">Nama Lengkap</td>
                            <td class="value">{{ $mahasiswa->name }}</td>
                        </tr>
                        <tr>
                            <td class="label">NIM / NIS</td>
                            <td class="value">{{ $profil->nim ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Instansi Asal</td>
                            <td class="value">{{ $profil->instansi_asal ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Jenjang / Jurusan</td>
                            <td class="value">{{ $profil->jenjang ?? '-' }} - {{ $profil->jurusan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Email / Telepon</td>
                            <td class="value" style="font-weight: normal;">
                                {{ $mahasiswa->email }}{{ $mahasiswa->phone ? ' / ' . $mahasiswa->phone : '' }}
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="right-col">
                    <div class="section-title" style="margin-top: 0;">B. Pelaksanaan & Pembimbing ASN</div>
                    <table class="kv">
                        <tr>
                            <td class="label">Pembimbing ASN</td>
                            <td class="value">{{ $pembimbing?->user?->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">NIP Pembimbing</td>
                            <td class="value">{{ $pembimbing?->nip ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Jabatan / Unit</td>
                            <td class="value" style="font-weight: normal;">
                                {{ $pembimbing?->jabatan ?? '-' }}
                                @if ($pembimbing?->unit_kerja)
                                    ({{ $pembimbing->unit_kerja }})
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Periode Magang</td>
                            <td class="value">
                                {{ $profil->tanggal_mulai?->translatedFormat('d M Y') ?? '-' }}
                                s.d.
                                {{ $profil->tanggal_selesai?->translatedFormat('d M Y') ?? '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Status Akhir</td>
                            <td class="value"><span class="badge-selesai">Selesai</span></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Ringkasan Statistik -->
        <table class="stats">
            <tr>
                <td>
                    <span class="stat-label">Total Hari Masuk</span>
                    <span class="stat-num">{{ $totalHariMasuk }} Hari</span>
                    <span class="stat-sub">Hari aktif tervalidasi</span>
                </td>
                <td>
                    <span class="stat-label">Tugas Selesai</span>
                    <span class="stat-num">{{ $totalTugasSelesai }} / {{ $totalTugasDiikuti }}</span>
                    <span class="stat-sub">Penugasan ASN</span>
                </td>
                <td>
                    <span class="stat-label">Logbook Mandiri</span>
                    <span class="stat-num">{{ $totalLogbookMandiri }} Catatan</span>
                    <span class="stat-sub">Telah divalidasi pembimbing</span>
                </td>
                <td>
                    <span class="stat-label">Durasi Periode</span>
                    <span class="stat-num">{{ $durasiHariKalender }} Hari</span>
                    <span class="stat-sub">Kalender pelaksanaan</span>
                </td>
            </tr>
        </table>

        <!-- Bagian C: Rekapitulasi Tugas -->
        <div class="section-title">C. Rekapitulasi Penugasan Selama Magang</div>
        @if ($daftarTugas->isEmpty())
            <p style="font-size: 10px; color: #6b7280; font-style: italic;">Tidak ada catatan penugasan pada periode
                ini.</p>
        @else
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 28px;">No</th>
                        <th>Judul & Deskripsi Tugas</th>
                        <th class="nowrap" style="width: 145px;">Pemberi Tugas</th>
                        <th class="nowrap" style="width: 85px;">Selesai</th>
                        <th class="text-center nowrap" style="width: 72px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($daftarTugas as $tugas)
                        @php
                            $statusVal =
                                $tugas->status instanceof \BackedEnum ? $tugas->status->value : (string) $tugas->status;
                            $statusLabel = method_exists($tugas->status, 'label')
                                ? $tugas->status->label()
                                : ucfirst(str_replace('_', ' ', $statusVal));
                        @endphp
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>
                                <div class="item-title">{{ $tugas->judul }}</div>
                                <div class="item-desc">{{ $tugas->deskripsi }}</div>
                            </td>
                            <td>{{ $tugas->asn?->name ?? '-' }}</td>
                            <td class="nowrap">
                                {{ $tugas->selesai_at ? $tugas->selesai_at->translatedFormat('d M Y') : '-' }}</td>
                            <td class="text-center nowrap"><span class="badge-selesai">{{ $statusLabel }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <!-- Bagian D: Rekapitulasi Logbook Kegiatan Harian -->
        <div class="section-title">D. Rekapitulasi Logbook Kegiatan Harian (Tervalidasi)</div>
        @if ($logbookMandiri->isEmpty())
            <p style="font-size: 10px; color: #6b7280; font-style: italic;">Tidak ada logbook mandiri yang tervalidasi.
            </p>
        @else
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 28px;">No</th>
                        <th class="nowrap" style="width: 88px;">Tanggal</th>
                        <th>Kegiatan & Uraian Aktivitas</th>
                        <th class="text-center nowrap" style="width: 78px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logbookMandiri as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="nowrap">{{ $item->tanggal_kegiatan?->translatedFormat('d M Y') ?? '-' }}</td>
                            <td>
                                <div class="item-title">{{ $item->judul_kegiatan }}</div>
                                <div class="item-desc">{{ $item->deskripsi_kegiatan }}</div>
                            </td>
                            <td class="text-center nowrap"><span class="badge-selesai">Tervalidasi</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <!-- Pengesahan -->
        <table class="ttd">
            <tr>
                <td>
                    <div>Peserta Magang / PKL,</div>
                    <div class="ttd-space"></div>
                    <div style="font-weight: bold; text-decoration: underline;">{{ $mahasiswa->name }}</div>
                    <div style="font-size: 9.5px; color: #4b5563;">NIM. {{ $profil->nim ?? '-' }}</div>
                </td>
                <td>
                    <div>Mengetahui,</div>
                    <div>Pembimbing Lapangan (ASN)</div>
                    <div class="ttd-space"></div>
                    <div style="font-weight: bold; text-decoration: underline;">
                        {{ $pembimbing?->user?->name ?? '........................................' }}</div>
                    <div style="font-size: 9.5px; color: #4b5563;">NIP. {{ $pembimbing?->nip ?? '-' }}</div>
                </td>
            </tr>
        </table>
    </div>

    @if (!($isPdfEngine ?? false))
        <script>
            window.addEventListener('load', function() {
                window.print();
            });
        </script>
    @endif
</body>

</html>
