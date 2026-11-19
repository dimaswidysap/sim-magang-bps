<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Magang - {{ $mahasiswa->name }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 18mm 15mm;
        }

        body {
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #1f2937;
            line-height: 1.45;
            margin: 0;
            padding: {{ ! empty($isPdfEngine) ? '0' : '24px' }};
            background: {{ ! empty($isPdfEngine) ? '#ffffff' : '#f3f4f6' }};
        }

        .sheet {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
            padding: {{ ! empty($isPdfEngine) ? '0' : '32px' }};
            border: {{ ! empty($isPdfEngine) ? 'none' : '1px solid #e5e7eb' }};
        }

        .print-bar {
            max-width: 800px;
            margin: 0 auto 14px auto;
            text-align: right;
        }

        .btn-print {
            background: #1d4ed8;
            color: #ffffff;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: bold;
            cursor: pointer;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #111827;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }

        .header .subtitle {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #1d4ed8;
            margin: 0 0 4px 0;
        }

        .header h1 {
            font-size: 16px;
            text-transform: uppercase;
            margin: 0 0 4px 0;
            color: #111827;
        }

        .header .meta {
            font-size: 10px;
            color: #6b7280;
            margin: 0;
        }

        .section-title {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            color: #1d4ed8;
            border-bottom: 1px solid #d1d5db;
            padding-bottom: 4px;
            margin: 18px 0 8px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table.info-table td {
            padding: 4px 6px;
            vertical-align: top;
        }

        table.info-table td.label {
            width: 28%;
            color: #4b5563;
        }

        table.info-table td.sep {
            width: 2%;
        }

        table.info-table td.val {
            width: 70%;
            font-weight: bold;
            color: #111827;
        }

        table.stats-table {
            margin-top: 12px;
        }

        table.stats-table th,
        table.stats-table td {
            border: 1px solid #d1d5db;
            padding: 7px 6px;
            text-align: center;
        }

        table.stats-table th {
            background: #f9fafb;
            font-size: 10px;
            color: #4b5563;
            text-transform: uppercase;
        }

        table.stats-table td {
            font-size: 13px;
            font-weight: bold;
            color: #111827;
        }

        table.data-table th,
        table.data-table td {
            border: 1px solid #d1d5db;
            padding: 6px 8px;
            vertical-align: top;
            font-size: 10.5px;
        }

        table.data-table th {
            background: #f3f4f6;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9.5px;
            color: #374151;
        }

        .text-center {
            text-align: center;
        }

        .nowrap {
            white-space: nowrap;
        }

        .muted {
            color: #6b7280;
            font-size: 9.5px;
        }

        .ttd-table {
            margin-top: 32px;
            page-break-inside: avoid;
        }

        .ttd-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }

        .ttd-space {
            height: 60px;
        }

        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }

            .sheet {
                border: none;
                padding: 0;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body>
    @if (empty($isPdfEngine))
        <div class="print-bar no-print">
            <button type="button" class="btn-print" onclick="window.print()">Cetak / Simpan PDF</button>
        </div>
    @endif

    <div class="sheet">
        <!-- Kop Laporan -->
        <div class="header">
            <p class="subtitle">Sistem Informasi Manajemen Magang / Praktik Kerja Lapangan</p>
            <h1>Laporan Akhir Kegiatan Peserta Magang</h1>
            <p class="meta">Dicetak pada: {{ $tanggalCetak->translatedFormat('d F Y, H:i') }} WIB</p>
        </div>

        <!-- Bagian A: Identitas Peserta -->
        <h2 class="section-title">A. Identitas Peserta Magang</h2>
        <table class="info-table">
            <tr>
                <td class="label">Nama Lengkap</td>
                <td class="sep">:</td>
                <td class="val">{{ $mahasiswa->name }}</td>
            </tr>
            <tr>
                <td class="label">NIM / NIS</td>
                <td class="sep">:</td>
                <td class="val">{{ $profil->nim ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Instansi Asal</td>
                <td class="sep">:</td>
                <td class="val">{{ $profil->instansi_asal ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Jenjang / Program Studi</td>
                <td class="sep">:</td>
                <td class="val">{{ $profil->jenjang ?? '-' }} - {{ $profil->jurusan ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Email / No. Telepon</td>
                <td class="sep">:</td>
                <td class="val">{{ $mahasiswa->email }}{{ $mahasiswa->phone ? ' / ' . $mahasiswa->phone : '' }}</td>
            </tr>
        </table>

        <!-- Bagian B: Pelaksanaan & Pembimbing -->
        <h2 class="section-title">B. Pelaksanaan & Pembimbing ASN</h2>
        <table class="info-table">
            <tr>
                <td class="label">Pembimbing ASN</td>
                <td class="sep">:</td>
                <td class="val">{{ $pembimbing?->user?->name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">NIP Pembimbing</td>
                <td class="sep">:</td>
                <td class="val">{{ $pembimbing?->nip ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Jabatan / Unit Kerja</td>
                <td class="sep">:</td>
                <td class="val">
                    {{ $pembimbing?->jabatan ?? '-' }}
                    {{ $pembimbing?->unit_kerja ? '(' . $pembimbing->unit_kerja . ')' : '' }}
                </td>
            </tr>
            <tr>
                <td class="label">Periode Magang</td>
                <td class="sep">:</td>
                <td class="val">
                    {{ $profil->tanggal_mulai?->translatedFormat('d F Y') ?? '-' }}
                    s.d.
                    {{ $profil->tanggal_selesai?->translatedFormat('d F Y') ?? '-' }}
                </td>
            </tr>
        </table>

        <!-- Ringkasan Capaian -->
        <table class="stats-table">
            <thead>
                <tr>
                    <th>Total Hari Masuk</th>
                    <th>Total Hari Libur</th>
                    <th>Tugas Selesai</th>
                    <th>Logbook Mandiri</th>
                    <th>Durasi Kalender</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $totalHariMasuk }} Hari</td>
                    <td>{{ $totalHariLibur }} Hari</td>
                    <td>{{ $totalTugasSelesai }} / {{ $totalTugasDiikuti }}</td>
                    <td>{{ $totalLogbookMandiri }} Catatan</td>
                    <td>{{ $durasiHariKalender }} Hari</td>
                </tr>
            </tbody>
        </table>

        <!-- Bagian C: Rekapitulasi Tugas -->
        <h2 class="section-title">C. Rekapitulasi Penugasan Selama Magang</h2>
        @if ($daftarTugas->isEmpty())
            <p class="muted">Tidak ada catatan penugasan pada periode ini.</p>
        @else
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 30px;">No</th>
                        <th>Judul & Deskripsi Tugas</th>
                        <th class="nowrap">Pemberi Tugas</th>
                        <th class="nowrap">Tanggal Selesai</th>
                        <th class="text-center nowrap">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($daftarTugas as $tugas)
                        @php
                            $statusVal = $tugas->status instanceof \BackedEnum
                                ? $tugas->status->value
                                : (string) $tugas->status;
                            $statusLabel = method_exists($tugas->status, 'label')
                                ? $tugas->status->label()
                                : ucfirst(str_replace('_', ' ', $statusVal));
                        @endphp
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $tugas->judul }}</strong><br>
                                <span class="muted">{{ $tugas->deskripsi }}</span>
                            </td>
                            <td class="nowrap">{{ $tugas->asn?->name ?? '-' }}</td>
                            <td class="nowrap">{{ $tugas->selesai_at ? $tugas->selesai_at->translatedFormat('d M Y') : '-' }}</td>
                            <td class="text-center nowrap">{{ strtoupper($statusLabel) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <!-- Bagian D: Rekapitulasi Logbook -->
        <h2 class="section-title">D. Rekapitulasi Logbook Kegiatan Harian (Tervalidasi)</h2>
        @if ($logbookMandiri->isEmpty())
            <p class="muted">Tidak ada logbook mandiri yang tervalidasi.</p>
        @else
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 30px;">No</th>
                        <th class="nowrap">Tanggal</th>
                        <th>Kegiatan & Uraian Aktivitas</th>
                        <th class="text-center nowrap">Validasi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logbookMandiri as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="nowrap">{{ $item->tanggal_kegiatan?->translatedFormat('d M Y') ?? '-' }}</td>
                            <td>
                                <strong>{{ $item->judul_kegiatan }}</strong><br>
                                <span class="muted">{{ $item->deskripsi_kegiatan }}</span>
                            </td>
                            <td class="text-center nowrap">Tervalidasi</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <!-- Bagian E: Rekapitulasi Hari Libur -->
        <h2 class="section-title">E. Rekapitulasi Hari Libur / Izin (Disetujui ASN)</h2>
        @if ($daftarLiburDisetujui->isEmpty())
            <p class="muted">Tidak ada riwayat pengajuan libur yang disetujui selama periode magang.</p>
        @else
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 30px;">No</th>
                        <th class="nowrap">Tanggal Libur</th>
                        <th class="text-center nowrap">Durasi</th>
                        <th>Alasan Libur</th>
                        <th class="nowrap">Disetujui Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($daftarLiburDisetujui as $libur)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="nowrap">
                                {{ $libur->tanggal_mulai?->translatedFormat('d M Y') ?? '-' }}
                                @if ($libur->tanggal_mulai && $libur->tanggal_selesai && ! $libur->tanggal_mulai->equalTo($libur->tanggal_selesai))
                                    s.d. {{ $libur->tanggal_selesai->translatedFormat('d M Y') }}
                                @endif
                            </td>
                            <td class="text-center nowrap">{{ $libur->jumlah_hari }} Hari</td>
                            <td>
                                {{ $libur->alasan }}
                                @if ($libur->catatan_asn)
                                    <br><span class="muted">Catatan ASN: {{ $libur->catatan_asn }}</span>
                                @endif
                            </td>
                            <td class="nowrap">{{ $libur->pembimbing?->user?->name ?? ($pembimbing?->user?->name ?? '-') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <!-- Kolom Tanda Tangan -->
        <table class="ttd-table">
            <tr>
                <td>
                    <p>Peserta Magang / PKL,</p>
                    <div class="ttd-space"></div>
                    <p><strong><u>{{ $mahasiswa->name }}</u></strong><br>
                        <span class="muted">NIM. {{ $profil->nim ?? '-' }}</span>
                    </p>
                </td>
                <td>
                    <p>Mengetahui,<br>Pembimbing Lapangan (ASN)</p>
                    <div class="ttd-space"></div>
                    <p><strong><u>{{ $pembimbing?->user?->name ?? '........................................' }}</u></strong><br>
                        <span class="muted">NIP. {{ $pembimbing?->nip ?? '-' }}</span>
                    </p>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
