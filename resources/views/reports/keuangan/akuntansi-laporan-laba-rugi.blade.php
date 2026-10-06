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
            font-size: 8px;
        }

        .report-kicker { color: #64748b; font-size: 8px; font-weight: bold; }
        .report-title { color: #0f172a; font-size: 18px; font-weight: bold; margin: 3px 0; }
        .report-identity { color: #334155; font-size: 10px; }
        .report-description { color: #64748b; font-size: 8px; margin-top: 3px; }
        .printed { color: #64748b; font-size: 8px; text-align: right; }
        .printed strong { color: #0f172a; font-size: 10px; }
        .period { background-color: #eff6ff; color: #1e3a5f; margin: 10px 0; padding: 7px; }
        .income-statement { border-collapse: collapse; table-layout: fixed; width: 100%; }
        .income-statement thead { display: table-header-group; }
        .income-statement th {
            background-color: #1e3a5f;
            border: 1px solid #1e3a5f;
            color: #ffffff;
            font-size: 7px;
            padding: 6px 4px;
            text-align: left;
        }
        .income-statement td { border-bottom: 1px solid #e2e8f0; padding: 5px 4px; }
        .income-statement .account { overflow-wrap: break-word; width: 20%; }
        .income-statement .amount { text-align: right; }
        .income-statement .section td {
            background-color: #eaf0f6;
            color: #1e3a5f;
            font-weight: bold;
            text-transform: uppercase;
        }
        .income-statement .expense-section td { background-color: #fff1f2; color: #9f1239; }
        .income-statement .row-alt td { background-color: #f8fafc; }
        .income-statement .subtotal td {
            background-color: #eef2f7;
            border-top: 1px solid #cbd5e1;
            font-weight: bold;
        }
        .income-statement .result td {
            background-color: #1e293b;
            border-bottom: 1px solid #1e293b;
            color: #ffffff;
            font-weight: bold;
            padding-bottom: 8px;
            padding-top: 8px;
        }
        .empty { color: #64748b; padding: 12px !important; text-align: center; }
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

    <table class="income-statement">
        <thead>
            <tr>
                <th class="account">Nama Rekening</th>
                @foreach ($bulanIndo as $namaBulan)
                    <th class="amount">{{ $namaBulan }}</th>
                @endforeach
                <th class="amount">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            <tr class="section">
                <td colspan="{{ $bulanIndo->count() + 2 }}">Pendapatan</td>
            </tr>
            @forelse ($pendapatanPerBulan as $namaRekening => $dataPerBulan)
                <tr class="{{ $loop->iteration % 2 === 0 ? 'row-alt' : '' }}">
                    <td class="account">{{ $namaRekening }}</td>
                    @foreach ($bulanHeaders as $bulan)
                        <td class="amount">
                            Rp{{ number_format(($dataPerBulan->get($bulan) ?? collect())->sum('nominal_laporan'), 0, ',', '.') }}
                        </td>
                    @endforeach
                    <td class="amount">
                        <strong>Rp{{ number_format($totalPendapatanRekening[$namaRekening] ?? 0, 0, ',', '.') }}</strong>
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="empty" colspan="{{ $bulanIndo->count() + 2 }}">Tidak ada data pendapatan pada periode ini.</td>
                </tr>
            @endforelse
            <tr class="subtotal">
                <td>Total Pendapatan</td>
                @foreach ($bulanHeaders as $bulan)
                    <td class="amount">Rp{{ number_format($totalPendapatanPerBulan[$bulan] ?? 0, 0, ',', '.') }}</td>
                @endforeach
                <td class="amount">Rp{{ number_format($totalPendapatan, 0, ',', '.') }}</td>
            </tr>

            <tr class="section expense-section">
                <td colspan="{{ $bulanIndo->count() + 2 }}">Beban</td>
            </tr>
            @forelse ($bebanPerBulan as $namaRekening => $dataPerBulan)
                <tr class="{{ $loop->iteration % 2 === 0 ? 'row-alt' : '' }}">
                    <td class="account">{{ $namaRekening }}</td>
                    @foreach ($bulanHeaders as $bulan)
                        <td class="amount">
                            Rp{{ number_format(($dataPerBulan->get($bulan) ?? collect())->sum('nominal_laporan'), 0, ',', '.') }}
                        </td>
                    @endforeach
                    <td class="amount">
                        <strong>Rp{{ number_format($totalBebanRekening[$namaRekening] ?? 0, 0, ',', '.') }}</strong>
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="empty" colspan="{{ $bulanIndo->count() + 2 }}">Tidak ada data beban pada periode ini.</td>
                </tr>
            @endforelse
            <tr class="subtotal">
                <td>Total Beban</td>
                @foreach ($bulanHeaders as $bulan)
                    <td class="amount">Rp{{ number_format($totalBebanPerBulan[$bulan] ?? 0, 0, ',', '.') }}</td>
                @endforeach
                <td class="amount">Rp{{ number_format($totalBeban, 0, ',', '.') }}</td>
            </tr>

            <tr class="result">
                <td>Laba (Rugi)</td>
                @foreach ($bulanHeaders as $bulan)
                    <td class="amount">Rp{{ number_format($labaRugiPerBulan[$bulan] ?? 0, 0, ',', '.') }}</td>
                @endforeach
                <td class="amount">Rp{{ number_format($totalLabaRugi, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
