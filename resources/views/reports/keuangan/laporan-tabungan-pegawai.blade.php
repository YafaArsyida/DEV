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

        .report-title { font-size: 18px; font-weight: bold; margin: 3px 0; }
        .report-identity { color: #334155; font-size: 10px; }
        .muted { color: #64748b; }
        .printed { color: #64748b; font-size: 8px; text-align: right; }
        .printed strong { color: #0f172a; font-size: 10px; }
        .summary {
            border-collapse: separate;
            border-spacing: 4px;
            margin: 8px -4px;
            width: 100%;
        }
        .summary td { background-color: #eff6ff; padding: 7px; }
        .summary-label { color: #64748b; font-size: 7px; font-weight: bold; }
        .summary-value { color: #1d4ed8; font-size: 12px; font-weight: bold; margin-top: 4px; }
        .transactions { border-collapse: collapse; width: 100%; }
        .transactions thead { display: table-header-group; }
        .transactions th {
            background-color: #1e3a5f;
            border: 1px solid #1e3a5f;
            color: #ffffff;
            font-size: 8px;
            padding: 7px 5px;
            text-align: left;
        }
        .transactions td { border-bottom: 1px solid #e2e8f0; padding: 6px 5px; vertical-align: top; }
        .transactions .row-alt td { background-color: #f8fafc; }
        .transactions .total-row td {
            background-color: #eaf0f6;
            border-top: 1px solid #cbd5e1;
            color: #0f172a;
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
                <div class="muted">{{ $periode }}</div>
            </td>
            <td class="printed" width="30%" valign="top">
                DICETAK<br>
                <strong>{{ $dicetakPada }}</strong>
            </td>
        </tr>
    </table>

    <table class="summary">
        <tr>
            <td width="33%">
                <div class="summary-label">JUMLAH TRANSAKSI</div>
                <div class="summary-value">{{ $jumlahTransaksi }}</div>
            </td>
            <td width="33%">
                <div class="summary-label">TOTAL KREDIT</div>
                <div class="summary-value">Rp{{ $totalKredit }}</div>
            </td>
            <td width="34%">
                <div class="summary-label">TOTAL DEBIT</div>
                <div class="summary-value">Rp{{ $totalDebit }}</div>
            </td>
        </tr>
    </table>

    <table class="transactions">
        <thead>
            <tr>
                <th class="center" width="4%">No.</th>
                <th width="11%">Tanggal</th>
                <th width="16%">Pegawai</th>
                <th width="12%">Jabatan</th>
                <th width="20%">Transaksi</th>
                <th width="10%">Petugas</th>
                <th class="right" width="13%">Kredit</th>
                <th class="right" width="14%">Debit</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $item)
                <tr class="{{ $loop->even ? 'row-alt' : '' }}">
                    <td class="center muted">{{ $item['nomor'] }}</td>
                    <td>{{ $item['tanggal'] }}</td>
                    <td>{{ $item['pegawai'] }}</td>
                    <td>{{ $item['jabatan'] }}</td>
                    <td>
                        <strong>{{ $item['jenisTransaksi'] }}</strong><br>
                        <span class="muted">{{ $item['deskripsi'] }}</span>
                    </td>
                    <td>{{ $item['petugas'] }}</td>
                    <td class="right">{{ $item['kredit'] === '-' ? '-' : 'Rp' . $item['kredit'] }}</td>
                    <td class="right">{{ $item['debit'] === '-' ? '-' : 'Rp' . $item['debit'] }}</td>
                </tr>
            @empty
                <tr>
                    <td class="empty" colspan="8">Tidak ada transaksi tabungan pada kriteria laporan ini.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="6" class="right">TOTAL KREDIT</td>
                <td colspan="2" class="right">Rp{{ $totalKredit }}</td>
            </tr>
            <tr class="total-row">
                <td colspan="6" class="right">TOTAL DEBIT</td>
                <td colspan="2" class="right">Rp{{ $totalDebit }}</td>
            </tr>
            <tr class="total-row">
                <td colspan="6" class="right">TOTAL SALDO</td>
                <td colspan="2" class="right">Rp{{ $totalSaldo }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
