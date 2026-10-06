<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $judul }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 14mm 16mm;
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
        .printed { color: #64748b; font-size: 8px; text-align: right; }
        .printed strong { color: #0f172a; font-size: 10px; }
        .period { background-color: #eff6ff; color: #1e3a5f; margin: 10px 0; padding: 7px; }
        .balance-sheet { border-collapse: collapse; width: 100%; }
        .balance-sheet thead { display: table-header-group; }
        .balance-sheet th {
            background-color: #1e3a5f;
            border: 1px solid #1e3a5f;
            color: #ffffff;
            padding: 7px 6px;
            text-align: left;
        }
        .balance-sheet th.amount, .balance-sheet td.amount { text-align: right; }
        .balance-sheet td { border-bottom: 1px solid #e2e8f0; padding: 6px; }
        .balance-sheet .account td { padding-left: 18px; }
        .balance-sheet .account-code { color: #64748b; }
        .balance-sheet .section td {
            background-color: #eaf0f6;
            color: #1e3a5f;
            font-weight: bold;
            text-transform: uppercase;
        }
        .balance-sheet .liability-section td { background-color: #fff7ed; color: #9a3412; }
        .balance-sheet .equity-section td { background-color: #ecfdf5; color: #166534; }
        .balance-sheet .subtotal td {
            background-color: #f1f5f9;
            border-top: 1px solid #cbd5e1;
            font-weight: bold;
        }
        .balance-sheet .grand-total td {
            background-color: #1e293b;
            border-bottom: 1px solid #1e293b;
            color: #ffffff;
            font-weight: bold;
            padding-bottom: 8px;
            padding-top: 8px;
        }
        .empty { color: #64748b; padding: 10px !important; text-align: center; }
        .validation {
            background-color: #ecfdf5;
            border: 1px solid #bbf7d0;
            color: #166534;
            margin-top: 10px;
            padding: 8px;
        }
        .validation.warning {
            background-color: #fffbeb;
            border-color: #fde68a;
            color: #92400e;
        }
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

    <table class="balance-sheet">
        <thead>
            <tr>
                <th>Nama Akun</th>
                <th class="amount">Saldo</th>
            </tr>
        </thead>
        <tbody>
            <tr class="section">
                <td colspan="2">Aset</td>
            </tr>
            @forelse ($kelompok['aset'] as $akun)
                <tr class="account">
                    <td><span class="account-code">{{ $akun['kode'] }}</span> - {{ $akun['nama'] }}</td>
                    <td class="amount">Rp{{ number_format($akun['saldo'], 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td class="empty" colspan="2">Tidak ada saldo akun aset.</td></tr>
            @endforelse
            <tr class="subtotal">
                <td>Total Aset</td>
                <td class="amount">Rp{{ number_format($totalAset, 0, ',', '.') }}</td>
            </tr>

            <tr class="section liability-section">
                <td colspan="2">Kewajiban</td>
            </tr>
            @forelse ($kelompok['kewajiban'] as $akun)
                <tr class="account">
                    <td><span class="account-code">{{ $akun['kode'] }}</span> - {{ $akun['nama'] }}</td>
                    <td class="amount">Rp{{ number_format($akun['saldo'], 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td class="empty" colspan="2">Tidak ada saldo akun kewajiban.</td></tr>
            @endforelse
            <tr class="subtotal">
                <td>Total Kewajiban</td>
                <td class="amount">Rp{{ number_format($totalKewajiban, 0, ',', '.') }}</td>
            </tr>

            <tr class="section equity-section">
                <td colspan="2">Ekuitas</td>
            </tr>
            <tr class="account">
                <td>Surplus/Defisit Tahun Berjalan</td>
                <td class="amount">Rp{{ number_format($labaRugi, 0, ',', '.') }}</td>
            </tr>
            @forelse ($kelompok['ekuitas'] as $akun)
                <tr class="account">
                    <td><span class="account-code">{{ $akun['kode'] }}</span> - {{ $akun['nama'] }}</td>
                    <td class="amount">Rp{{ number_format($akun['saldo'], 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td class="empty" colspan="2">Tidak ada saldo akun ekuitas.</td></tr>
            @endforelse
            <tr class="subtotal">
                <td>Total Ekuitas</td>
                <td class="amount">Rp{{ number_format($totalEkuitas, 0, ',', '.') }}</td>
            </tr>

            <tr class="grand-total">
                <td>Total Kewajiban + Ekuitas</td>
                <td class="amount">Rp{{ number_format($totalPassiva, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    @if ($selisih != 0)
        <div class="validation warning">
            Neraca belum seimbang. Selisih antara Total Aset dan Total Kewajiban + Ekuitas:
            <strong>Rp{{ number_format(abs($selisih), 0, ',', '.') }}</strong>.
        </div>
    @else
        <div class="validation">
            Neraca seimbang: Total Aset sama dengan Total Kewajiban + Ekuitas.
        </div>
    @endif
</body>
</html>
