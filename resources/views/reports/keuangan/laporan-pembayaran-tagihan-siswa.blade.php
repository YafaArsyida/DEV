<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $judul }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 14mm 10mm 16mm;
        }

        body {
            color: #0f172a;
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
        }

        .report-kicker {
            color: #64748b;
            font-size: 8px;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .report-title {
            color: #0f172a;
            font-size: 18px;
            font-weight: bold;
            margin: 3px 0;
        }
        .report-identity { color: #334155; font-size: 10px; }
        .report-description { color: #64748b; font-size: 8px; margin-top: 3px; }
        .printed { color: #64748b; font-size: 8px; text-align: right; }
        .printed strong { color: #0f172a; font-size: 10px; }
        .filters, .summary {
            border-collapse: separate;
            border-spacing: 4px;
            margin: 8px -4px;
            width: 100%;
        }
        .filter-cell, .summary-cell {
            background-color: #f1f5f9;
            padding: 7px;
            vertical-align: top;
        }
        .summary-cell { background-color: #eff6ff; width: 50%; }
        .summary-label {
            color: #64748b;
            font-size: 7px;
            font-weight: bold;
        }
        .summary-value {
            color: #1d4ed8;
            font-size: 12px;
            font-weight: bold;
            margin-top: 4px;
        }
        .transactions {
            border-collapse: collapse;
            width: 100%;
        }
        .transactions thead { display: table-header-group; }
        .transactions tfoot { display: table-row-group; }
        .transactions th {
            background-color: #1e3a5f;
            border: 1px solid #1e3a5f;
            color: #ffffff;
            font-size: 8px;
            padding: 7px 5px;
            text-align: left;
        }
        .transactions td {
            border-bottom: 1px solid #e2e8f0;
            padding: 6px 5px;
            vertical-align: top;
        }
        .transactions .row-alt td { background-color: #f8fafc; }
        .transactions .total-row td {
            background-color: #eaf0f6;
            border-top: 1px solid #cbd5e1;
            color: #0f172a;
            font-weight: bold;
        }
        .center { text-align: center !important; }
        .right { text-align: right !important; }
        .muted { color: #64748b; }
        .amount { color: #15803d; font-weight: bold; }
        .empty { color: #64748b; padding: 14px !important; text-align: center; }
        .page-footer {
            bottom: -9mm;
            color: #94a3b8;
            font-size: 7px;
            position: fixed;
            right: 0;
            text-align: right;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="page-footer">Laporan Pembayaran Siswa</div>

    <table width="100%">
        <tr>
            <td width="70%">
                <div class="report-kicker">LAPORAN KEUANGAN</div>
                <div class="report-title">{{ $judul }}</div>
                <div class="report-identity">{{ $yayasan }}</div>
                <div class="report-description">{{ $deskripsiJenjang }}</div>
            </td>
            <td class="printed" width="30%" valign="top">
                DICETAK<br>
                <strong>{{ $dicetakPada }}</strong>
            </td>
        </tr>
    </table>

    <table class="filters">
        <tr>
            <td class="filter-cell" width="40%">
                <span class="summary-label">TAHUN AJARAN</span><br>
                <strong>{{ $tahunAjar }}</strong>
            </td>
            <td class="filter-cell" width="40%">
                <span class="summary-label">PERIODE PEMBAYARAN</span><br>
                <strong>{{ $periode }}</strong>
            </td>
            <td class="filter-cell" width="20%">
                <span class="summary-label">PENCARIAN</span><br>
                <strong>{{ $pencarian }}</strong>
            </td>
        </tr>
    </table>

    <table class="summary">
        <tr>
            <td class="summary-cell">
                <div class="summary-label">JUMLAH TRANSAKSI</div>
                <div class="summary-value">{{ $jumlahTransaksi }}</div>
            </td>
            <td class="summary-cell">
                <div class="summary-label">TOTAL DIBAYARKAN</div>
                <div class="summary-value">Rp{{ $total }}</div>
            </td>
        </tr>
    </table>

    <table class="transactions">
        <thead>
            <tr>
                <th class="center" width="5%">No.</th>
                <th width="12%">Tanggal</th>
                <th width="20%">Siswa</th>
                <th width="11%">Kelas</th>
                <th width="18%">Tagihan</th>
                <th width="12%">Petugas</th>
                <th width="12%">Metode</th>
                <th class="right" width="10%">Dibayarkan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $item)
                <tr class="{{ $loop->even ? 'row-alt' : '' }}">
                    <td class="center muted">{{ $item['nomor'] }}</td>
                    <td>{{ $item['tanggal'] }}</td>
                    <td>{{ $item['siswa'] }}</td>
                    <td>{{ $item['kelas'] }}</td>
                    <td>{{ $item['tagihan'] }}</td>
                    <td>{{ $item['petugas'] }}</td>
                    <td>{{ $item['metode'] }}</td>
                    <td class="right amount">Rp{{ $item['jumlahBayar'] }}</td>
                </tr>
            @empty
                <tr>
                    <td class="empty" colspan="8">Tidak ada pembayaran pada kriteria laporan ini.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="7" class="right">TOTAL PEMBAYARAN</td>
                <td class="right">Rp{{ $total }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
