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

        .report-kicker { color: #64748b; font-size: 8px; font-weight: bold; }
        .report-title { color: #0f172a; font-size: 18px; font-weight: bold; margin: 3px 0; }
        .report-identity { color: #334155; font-size: 10px; }
        .printed { color: #64748b; font-size: 8px; text-align: right; }
        .printed strong { color: #0f172a; font-size: 10px; }
        .filters {
            border-collapse: separate;
            border-spacing: 4px;
            margin: 8px -4px;
            width: 100%;
        }
        .filters td { background-color: #f1f5f9; padding: 7px; vertical-align: top; }
        .muted { color: #64748b; }
        .summary {
            border-collapse: separate;
            border-spacing: 4px;
            margin: 8px -4px;
            width: 100%;
        }
        .summary td { background-color: #eff6ff; padding: 7px; }
        .summary-label { color: #64748b; font-size: 7px; font-weight: bold; }
        .summary-value { color: #1d4ed8; font-size: 12px; font-weight: bold; margin-top: 4px; }
        .ledger { border-collapse: collapse; width: 100%; }
        .ledger thead { display: table-header-group; }
        .ledger th {
            background-color: #1e3a5f;
            border: 1px solid #1e3a5f;
            color: #ffffff;
            font-size: 8px;
            padding: 7px 5px;
            text-align: left;
        }
        .ledger td { border-bottom: 1px solid #e2e8f0; padding: 6px 5px; vertical-align: top; }
        .ledger .row-alt td { background-color: #f8fafc; }
        .ledger .opening-row td {
            background-color: #eaf0f6;
            border-bottom: 1px solid #cbd5e1;
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
                <div class="report-kicker">LAPORAN KEUANGAN</div>
                <div class="report-title">{{ $judul }}</div>
                <div class="report-identity">
                    Rekening: {{ $rekening->kode_rekening }} - {{ $rekening->nama_rekening }}
                </div>
                <div class="muted">Posisi normal: {{ ucfirst($rekening->posisi_normal) }}</div>
            </td>
            <td class="printed" width="30%" valign="top">
                DICETAK<br>
                <strong>{{ $dicetakPada }}</strong>
            </td>
        </tr>
    </table>

    <table class="filters">
        <tr>
            <td width="65%"><span class="muted">PERIODE</span><br><strong>{{ $periode }}</strong></td>
            <td width="35%"><span class="muted">PENCARIAN</span><br><strong>{{ $pencarian }}</strong></td>
        </tr>
    </table>

    <table class="summary">
        <tr>
            <td width="25%">
                <div class="summary-label">SALDO AWAL</div>
                <div class="summary-value">Rp{{ number_format($saldoAwal, 0, ',', '.') }}</div>
            </td>
            <td width="25%">
                <div class="summary-label">TOTAL DEBIT</div>
                <div class="summary-value">Rp{{ number_format($totalDebit, 0, ',', '.') }}</div>
            </td>
            <td width="25%">
                <div class="summary-label">TOTAL KREDIT</div>
                <div class="summary-value">Rp{{ number_format($totalKredit, 0, ',', '.') }}</div>
            </td>
            <td width="25%">
                <div class="summary-label">SALDO AKHIR</div>
                <div class="summary-value">Rp{{ number_format($saldoAkhir, 0, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    <table class="ledger">
        <thead>
            <tr>
                <th class="center" width="4%">No.</th>
                <th width="13%">Tanggal</th>
                <th width="12%">Nomor Jurnal</th>
                <th width="12%">Petugas</th>
                <th width="31%">Deskripsi Transaksi</th>
                <th class="right" width="10%">Debit</th>
                <th class="right" width="10%">Kredit</th>
                <th class="right" width="8%">Saldo</th>
            </tr>
        </thead>
        <tbody>
            <tr class="opening-row">
                <td colspan="7" class="right">SALDO AWAL</td>
                <td class="right">Rp{{ number_format($saldoAwal, 0, ',', '.') }}</td>
            </tr>
            @forelse ($data as $index => $transaksi)
                <tr class="{{ $index % 2 === 1 ? 'row-alt' : '' }}">
                    <td class="center muted">{{ $transaksi['nomor'] }}.</td>
                    <td>{{ $transaksi['tanggal'] }}</td>
                    <td>{{ $transaksi['nomorJurnal'] }}</td>
                    <td>{{ $transaksi['petugas'] }}</td>
                    <td>{{ $transaksi['deskripsi'] }}</td>
                    <td class="right">
                        {{ $transaksi['debit'] === null ? '-' : 'Rp' . number_format($transaksi['debit'], 0, ',', '.') }}
                    </td>
                    <td class="right">
                        {{ $transaksi['kredit'] === null ? '-' : 'Rp' . number_format($transaksi['kredit'], 0, ',', '.') }}
                    </td>
                    <td class="right">Rp{{ number_format($transaksi['saldo'], 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td class="empty" colspan="8">Tidak ada transaksi pada periode dan filter ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
