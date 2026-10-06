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
        .muted { color: #64748b; }
        .printed { color: #64748b; font-size: 8px; text-align: right; }
        .printed strong { color: #0f172a; font-size: 10px; }
        .report-description { color: #64748b; font-size: 8px; margin-top: 3px; }
        .period { background-color: #eff6ff; color: #1e3a5f; margin: 10px 0; padding: 7px; }
        .income { border-collapse: collapse; width: 100%; }
        .income thead { display: table-header-group; }
        .income th {
            background-color: #1e3a5f;
            border: 1px solid #1e3a5f;
            color: #ffffff;
            padding: 7px 5px;
            text-align: left;
        }
        .income td { border-bottom: 1px solid #e2e8f0; padding: 6px 5px; }
        .income .row-alt td { background-color: #f8fafc; }
        .income .total-row td {
            background-color: #eaf0f6;
            border-top: 1px solid #cbd5e1;
            font-weight: bold;
        }
        .right { text-align: right !important; }
        .center { text-align: center !important; }
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

    <div class="period"><strong>{{ $periode }}</strong></div>

    <table class="income">
        <thead>
            <tr>
                <th>Nama Rekening</th>
                @foreach ($bulanIndo as $namaBulan)
                    <th class="center">{{ $namaBulan }}</th>
                @endforeach
                <th class="right">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $index => $item)
                <tr class="{{ $index % 2 === 1 ? 'row-alt' : '' }}">
                    <td>{{ $item['namaRekening'] }}</td>
                    @foreach ($bulanHeaders as $bulan)
                        <td class="right">Rp{{ number_format($item['bulanan']->get($bulan), 0, ',', '.') }}</td>
                    @endforeach
                    <td class="right"><strong>Rp{{ number_format($item['total'], 0, ',', '.') }}</strong></td>
                </tr>
            @empty
                <tr>
                    <td class="empty" colspan="{{ $bulanHeaders->count() + 2 }}">Tidak ada data pendapatan pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td>TOTAL</td>
                @foreach ($bulanHeaders as $bulan)
                    <td class="right">Rp{{ number_format($totalPerBulan->get($bulan), 0, ',', '.') }}</td>
                @endforeach
                <td class="right">Rp{{ number_format($grandTotal, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
