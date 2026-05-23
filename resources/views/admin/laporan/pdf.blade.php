<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Alumni - SD Muhammadiyah Birrul Walidain</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Serif', serif;
            font-size: 11px;
            color: #000;
            background: #fff;
            line-height: 1.4;
        }

        /* ── PAGE LAYOUT ── */
        @page {
            size: A4 portrait;
            margin: 1.5cm 1.8cm;
        }

        .page-wrapper {
            width: 100%;
        }

        /* ── HEADER ── */
        .report-header {
            border-bottom: 3px solid #000;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }

        .header-flex {
            display: table;
            width: 100%;
        }

        .header-logo-cell {
            display: table-cell;
            width: 70px;
            vertical-align: middle;
        }

        .header-logo {
            width: 60px;
            height: 60px;
        }

        .header-text-cell {
            display: table-cell;
            vertical-align: middle;
            padding-left: 12px;
        }

        .school-name {
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .school-subtitle {
            font-size: 10px;
            color: #333;
            margin-top: 2px;
        }

        .report-title-bar {
            margin-top: 10px;
            text-align: center;
        }

        .report-title {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .report-meta {
            font-size: 9.5px;
            color: #444;
            margin-top: 4px;
        }

        /* ── FILTER INFO ── */
        .filter-info {
            background: #f5f5f5;
            border: 1px solid #ddd;
            padding: 6px 10px;
            margin-bottom: 14px;
            font-size: 9.5px;
        }

        .filter-info strong {
            font-weight: bold;
        }

        /* ── TABLE ── */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            font-size: 10px;
        }

        .data-table thead tr {
            background: #1B3A52;
            color: #fff;
        }

        .data-table thead th {
            padding: 7px 6px;
            text-align: left;
            font-weight: bold;
            font-size: 9.5px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border: 1px solid #1B3A52;
        }

        .data-table thead th.text-center {
            text-align: center;
        }

        .data-table tbody tr {
            border-bottom: 1px solid #ddd;
        }

        .data-table tbody tr:nth-child(even) {
            background: #f9f9f9;
        }

        .data-table tbody td {
            padding: 6px 6px;
            vertical-align: top;
            border: 1px solid #ddd;
            color: #000;
        }

        .data-table tbody td.text-center {
            text-align: center;
        }

        .data-table tbody td.text-muted {
            color: #666;
        }

        .no-col    { width: 4%; }
        .nisn-col  { width: 10%; }
        .nama-col  { width: 20%; }
        .akt-col   { width: 11%; }
        .pek-col   { width: 22%; }
        .pend-col  { width: 22%; }
        .kota-col  { width: 11%; }

        .nama-bold { font-weight: bold; }

        /* ── FOOTER / SUMMARY ── */
        .summary-section {
            border-top: 2px solid #000;
            padding-top: 10px;
            margin-top: 4px;
            display: table;
            width: 100%;
        }

        .summary-left {
            display: table-cell;
            vertical-align: top;
            width: 60%;
        }

        .summary-right {
            display: table-cell;
            vertical-align: top;
            width: 40%;
            text-align: right;
        }

        .summary-item {
            font-size: 9.5px;
            margin-bottom: 3px;
        }

        .summary-total {
            font-size: 11px;
            font-weight: bold;
            border-top: 1px solid #000;
            padding-top: 4px;
            margin-top: 4px;
        }

        .signature-block {
            font-size: 9.5px;
        }

        .signature-block .sign-date {
            margin-bottom: 50px;
        }

        .signature-block .sign-name {
            font-weight: bold;
            border-top: 1px solid #000;
            padding-top: 4px;
            display: inline-block;
            min-width: 150px;
        }

        /* ── PAGE NUMBER (via dompdf) ── */
        .page-number {
            position: fixed;
            bottom: -1cm;
            right: 0;
            font-size: 9px;
            color: #555;
        }

        /* ── UTILITIES ── */
        .text-bold { font-weight: bold; }
        .divider { border: none; border-top: 1px solid #ccc; margin: 10px 0; }
    </style>
</head>
<body>

<div class="page-wrapper">

    {{-- ── HEADER ── --}}
    <div class="report-header">
        <div class="header-flex">
            <div class="header-text-cell">
                <div class="school-name">SD Muhammadiyah Birrul Walidain</div>
                <div class="school-subtitle">Kudus, Jawa Tengah &bull; Sistem Informasi Alumni</div>
            </div>
        </div>

        <div class="report-title-bar">
            <div class="report-title">
                Laporan Data Alumni
                @if($angkatan)
                    &mdash; {{ $angkatan->nama_angkatan }}
                @endif
            </div>
            <div class="report-meta">
                Tanggal Cetak: {{ $tanggal_cetak }}
                @if($filter_status)
                    &bull; Filter Status: <strong>{{ ucfirst($filter_status) }}</strong>
                @endif
            </div>
        </div>
    </div>

    {{-- ── FILTER INFO ── --}}
    @if($angkatan || $filter_status)
    <div class="filter-info">
        <strong>Filter Aktif:</strong>
        @if($angkatan) Angkatan: {{ $angkatan->nama_angkatan }} ({{ $angkatan->tahun_ajaran }}) @endif
        @if($filter_status) | Status Verifikasi: {{ ucfirst($filter_status) }} @endif
    </div>
    @endif

    {{-- ── DATA TABLE ── --}}
    <table class="data-table">
        <thead>
            <tr>
                <th class="no-col text-center">No</th>
                <th class="nisn-col">NISN</th>
                <th class="nama-col">Nama Lengkap</th>
                <th class="akt-col">Angkatan</th>
                <th class="pek-col">Pekerjaan Terkini</th>
                <th class="pend-col">Pendidikan Terakhir</th>
                <th class="kota-col">Kota Domisili</th>
            </tr>
        </thead>
        <tbody>
            @forelse($alumni as $index => $item)
                <tr>
                    <td class="text-center text-muted">{{ $index + 1 }}</td>
                    <td>{{ $item['nisn'] }}</td>
                    <td class="nama-bold">{{ $item['nama_lengkap'] }}</td>
                    <td>{{ $item['angkatan'] }}</td>
                    <td>{{ $item['pekerjaan_terkini'] }}</td>
                    <td>{{ $item['pendidikan_terakhir'] }}</td>
                    <td>{{ $item['kota_domisili'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 20px; color: #666;">
                        Tidak ada data alumni untuk ditampilkan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ── SUMMARY FOOTER ── --}}
    <div class="summary-section">
        <div class="summary-left">
            <div class="summary-item"><strong>Total Data:</strong> {{ count($alumni) }} alumni</div>
            @if($angkatan)
            <div class="summary-item"><strong>Angkatan:</strong> {{ $angkatan->nama_angkatan }} ({{ $angkatan->tahun_ajaran }})</div>
            @endif
            <div class="summary-item" style="margin-top: 8px; font-size: 9px; color: #666;">
                * Laporan ini dicetak secara otomatis oleh Sistem Informasi Alumni.<br>
                * Data bersumber dari database per tanggal cetak.
            </div>
        </div>
        <div class="summary-right">
            <div class="signature-block">
                <div class="sign-date">Kudus, {{ $tanggal_cetak }}</div>
                <div>Kepala Sekolah,</div>
                <br><br><br>
                <div class="sign-name">_________________________</div>
            </div>
        </div>
    </div>

</div>

<div class="page-number">
    Halaman <script type="text/php">
        if (isset($pdf)) {
            $pdf->page_script('
                $font = $fontMetrics->getFont("DejaVu Serif", "normal");
                $pdf->text(520, 810, "Hal. " . $PAGE_NUM . " / " . $PAGE_COUNT, $font, 8);
            ');
        }
    </script>
</div>

</body>
</html>
