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
        .report-description { color: #64748b; font-size: 8px; margin-top: 3px; }
        .muted { color: #64748b; }
        .printed { color: #64748b; font-size: 8px; text-align: right; }
        .printed strong { color: #0f172a; font-size: 10px; }
        .filters {
            border-collapse: separate;
            border-spacing: 4px;
            margin: 8px -4px;
            width: 100%;
        }
        .filters td { background-color: #f1f5f9; padding: 7px; }
        .jurnals { border-collapse: collapse; width: 100%; }
        .jurnals thead { display: table-header-group; }
        .jurnals th {
            background-color: #1e3a5f;
            border: 1px solid #1e3a5f;
            color: #ffffff;
            font-size: 8px;
            padding: 7px 5px;
            text-align: left;
        }
        .jurnals td { border-bottom: 1px solid #e2e8f0; padding: 6px 5px; vertical-align: top; }
        .jurnals .row-alt td { background-color: #f8fafc; }
        .jurnals .total-row td {
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
            <td width="34%"><span class="muted">PERIODE</span><br><strong>{{ $periode }}</strong></td>
            <td width="33%"><span class="muted">DEPARTEMEN</span><br><strong>{{ $departemen }}</strong></td>
            <td width="33%"><span class="muted">PENCARIAN</span><br><strong>{{ $pencarian }}</strong></td>
        </tr>
    </table>

    <table class="jurnals">
        <thead>
            <tr>
                <th class="center" width="4%">No.</th>
                <th width="10%">Tanggal</th>
                <th width="29%">Deskripsi Transaksi</th>
                <th width="10%">Petugas</th>
                <th width="17%">Akun Debit</th>
                <th width="17%">Akun Kredit</th>
                <th class="right" width="13%">Nominal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $index => $jurnal)
                <tr class="{{ $index % 2 === 1 ? 'row-alt' : '' }}">
                    <td class="center muted">{{ $jurnal['nomor'] }}.</td>
                    <td>{{ $jurnal['tanggal'] }}</td>
                    <td>{{ $jurnal['deskripsi'] }}</td>
                    <td>{{ $jurnal['petugas'] }}</td>
                    <td>
                        @forelse ($jurnal['akunDebit'] as $akun)
                            {{ $akun }}@if (!$loop->last)<br>@endif
                        @empty
                            -
                        @endforelse
                    </td>
                    <td>
                        @forelse ($jurnal['akunKredit'] as $akun)
                            {{ $akun }}@if (!$loop->last)<br>@endif
                        @empty
                            -
                        @endforelse
                    </td>
                    <td class="right">Rp{{ number_format($jurnal['nominal'], 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td class="empty" colspan="7">Tidak ada data jurnal pada filter ini.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="6" class="right">TOTAL NOMINAL</td>
                <td class="right">Rp{{ number_format($totalNominal, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
