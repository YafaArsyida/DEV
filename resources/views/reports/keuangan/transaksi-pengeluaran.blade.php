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

        .muted { color: #64748b; }
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
        .summary-cell { background-color: #f8fafc; width: 25%; }
        .summary-label {
            color: #64748b;
            font-size: 7px;
            font-weight: bold;
        }
        .summary-value {
            color: #0f172a;
            font-size: 12px;
            font-weight: bold;
            margin-top: 4px;
        }
        .summary-opening { background-color: #eff6ff; }
        .summary-expense { background-color: #fef2f2; }
        .summary-ending { background-color: #f8fafc; }
        .summary-cancelled { background-color: #fff7ed; }
        .summary-opening .summary-value { color: #1d4ed8; }
        .summary-expense .summary-value { color: #b91c1c; }
        .summary-cancelled .summary-value { color: #9a3412; }
        .note { color: #64748b; font-size: 7px; margin: 4px 0 7px; }
        .transactions {
            border-collapse: collapse;
            width: 100%;
        }
        .transactions thead { display: table-header-group; }
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
        .transactions .opening-row td {
            background-color: #eaf0f6;
            color: #0f172a;
            font-weight: bold;
        }
        .transactions .row-alt td { background-color: #f8fafc; }
        .center { text-align: center !important; }
        .right { text-align: right !important; }
        .transaction-name { color: #0f172a; font-weight: bold; }
        .description, .method { color: #64748b; font-size: 8px; }
        .amount-active { color: #b91c1c; font-weight: bold; }
        .amount-cancelled {
            color: #94a3b8;
            text-decoration: line-through;
        }
        .cancelled-label {
            color: #b91c1c;
            font-size: 7px;
            font-weight: bold;
        }
        .balance { color: #0f172a; font-weight: bold; }
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
    <div class="page-footer">Laporan Transaksi Pengeluaran</div>

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
            <td class="filter-cell" width="35%">
                <span class="summary-label">PERIODE LAPORAN</span><br>
                <strong>{{ $periode }}</strong>
            </td>
            <td class="filter-cell" width="35%">
                <span class="summary-label">JENIS TRANSAKSI</span><br>
                <strong>{{ $rekening }}</strong>
            </td>
            <td class="filter-cell" width="30%">
                <span class="summary-label">PENCARIAN</span><br>
                <strong>{{ $pencarian }}</strong>
            </td>
        </tr>
    </table>

    <table class="summary">
        <tr>
            <td class="summary-cell summary-opening">
                <div class="summary-label">SALDO SEBELUM PERIODE</div>
                <div class="summary-value">Rp{{ $saldoAwal }}</div>
            </td>
            <td class="summary-cell summary-expense">
                <div class="summary-label">PENGELUARAN AKTIF</div>
                <div class="summary-value">Rp{{ $total }}</div>
            </td>
            <td class="summary-cell summary-ending">
                <div class="summary-label">SALDO AKHIR</div>
                <div class="summary-value">Rp{{ $saldoAkhir }}</div>
            </td>
            <td class="summary-cell summary-cancelled">
                <div class="summary-label">TRANSAKSI / DIBATALKAN</div>
                <div class="summary-value">{{ $jumlahTransaksi }} / {{ $jumlahDibatalkan }}</div>
            </td>
        </tr>
    </table>

    <div class="note">
        Transaksi dibatalkan tetap ditampilkan, tetapi tidak dihitung dalam total pengeluaran aktif maupun saldo berjalan.
    </div>

    <table class="transactions">
        <thead>
            <tr>
                <th class="center" width="5%">No.</th>
                <th width="13%">Tanggal</th>
                <th width="42%">Transaksi</th>
                <th width="15%">Petugas / Metode</th>
                <th class="right" width="12%">Nominal</th>
                <th class="right" width="13%">Saldo</th>
            </tr>
        </thead>
        <tbody>
            <tr class="opening-row">
                <td colspan="4">Pengeluaran Sebelum Periode</td>
                <td class="right">-</td>
                <td class="right">Rp{{ $saldoAwal }}</td>
            </tr>
            @forelse ($data as $item)
                <tr class="{{ $loop->even ? 'row-alt' : '' }}">
                    <td class="center muted">{{ $item['nomor'] }}</td>
                    <td>{{ $item['tanggal'] }}</td>
                    <td>
                        <span class="transaction-name">{{ $item['rekening'] }}</span>
                        @if ($item['dibatalkan'])
                            <br><span class="cancelled-label">DIBATALKAN</span>
                        @endif
                        <br><span class="description">{{ $item['deskripsi'] }}</span>
                    </td>
                    <td>
                        {{ $item['petugas'] }}<br>
                        <span class="method">{{ $item['metode'] }}</span>
                    </td>
                    <td class="right {{ $item['dibatalkan'] ? 'amount-cancelled' : 'amount-active' }}">
                        Rp{{ $item['nominal'] }}
                    </td>
                    <td class="right balance">Rp{{ $item['saldo'] }}</td>
                </tr>
            @empty
                <tr>
                    <td class="empty" colspan="6">Tidak ada transaksi pada kriteria laporan ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
