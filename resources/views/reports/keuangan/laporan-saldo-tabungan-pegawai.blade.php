<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $judul }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 14mm 12mm 16mm;
        }

        body {
            color: #0f172a;
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
        }

        .report-title { font-size: 18px; font-weight: bold; margin: 3px 0; }
        .report-identity { color: #334155; font-size: 10px; }
        .muted { color: #64748b; }
        .printed { color: #64748b; font-size: 8px; text-align: right; }
        .printed strong { color: #0f172a; font-size: 10px; }
        .summary {
            border-collapse: separate;
            border-spacing: 4px;
            margin: 10px -4px;
            width: 100%;
        }
        .summary td { background-color: #eff6ff; padding: 8px; }
        .summary-label { color: #64748b; font-size: 8px; font-weight: bold; }
        .summary-value { color: #1d4ed8; font-size: 14px; font-weight: bold; margin-top: 4px; }
        .balances { border-collapse: collapse; width: 100%; }
        .balances thead { display: table-header-group; }
        .balances th {
            background-color: #1e3a5f;
            border: 1px solid #1e3a5f;
            color: #ffffff;
            padding: 8px 6px;
            text-align: left;
        }
        .balances td { border-bottom: 1px solid #e2e8f0; padding: 7px 6px; }
        .balances .row-alt td { background-color: #f8fafc; }
        .balances .total-row td {
            background-color: #eaf0f6;
            border-top: 1px solid #cbd5e1;
            font-weight: bold;
        }
        .center { text-align: center !important; }
        .right { text-align: right !important; }
        .empty { color: #64748b; padding: 14px !important; text-align: center; }
    </style>
</head>
<body>
    <table width="100%">
        <tr>
            <td width="70%">
                <div class="muted">LAPORAN KEUANGAN</div>
                <div class="report-title">{{ $judul }}</div>
                <div class="report-identity">{{ $yayasan }} &middot; Tahun Ajaran {{ $tahunAjar }}</div>
                <div class="report-identity">Jabatan: {{ $jabatan }}</div>
            </td>
            <td class="printed" width="30%" valign="top">
                DICETAK<br>
                <strong>{{ $dicetakPada }}</strong>
            </td>
        </tr>
    </table>

    <table class="summary">
        <tr>
            <td width="50%">
                <div class="summary-label">JUMLAH PEGAWAI</div>
                <div class="summary-value">{{ $jumlahPegawai }}</div>
            </td>
            <td width="50%">
                <div class="summary-label">TOTAL SALDO</div>
                <div class="summary-value">Rp{{ $totalSaldo }}</div>
            </td>
        </tr>
    </table>

    <table class="balances">
        <thead>
            <tr>
                <th class="center" width="8%">No.</th>
                <th width="42%">Pegawai</th>
                <th width="25%">Jabatan</th>
                <th class="right" width="25%">Saldo</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $item)
                <tr class="{{ $loop->even ? 'row-alt' : '' }}">
                    <td class="center muted">{{ $item['nomor'] }}</td>
                    <td>{{ $item['pegawai'] }}</td>
                    <td>{{ $item['jabatan'] }}</td>
                    <td class="right">Rp{{ $item['saldo'] }}</td>
                </tr>
            @empty
                <tr>
                    <td class="empty" colspan="4">Tidak ada saldo tabungan pada kriteria laporan ini.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3" class="right">TOTAL SALDO</td>
                <td class="right">Rp{{ $totalSaldo }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
