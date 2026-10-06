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
        .cash-summary {
            border-collapse: separate;
            border-spacing: 4px;
            margin: 8px -4px;
            width: 100%;
        }
        .cash-summary td { background-color: #f1f5f9; padding: 7px; vertical-align: top; }
        .summary-label { color: #64748b; font-size: 7px; font-weight: bold; }
        .summary-value { color: #0f172a; font-size: 11px; font-weight: bold; margin-top: 4px; }
        .cash-in { color: #15803d; }
        .cash-out { color: #b91c1c; }
        .transactions { border-collapse: collapse; width: 100%; }
        .transactions thead { display: table-header-group; }
        .transactions th {
            background-color: #1e3a5f;
            border: 1px solid #1e3a5f;
            color: #ffffff;
            font-size: 7px;
            padding: 7px 5px;
            text-align: left;
        }
        .transactions th.amount, .transactions td.amount { text-align: right; }
        .center { text-align: center !important; }
        .transactions td { border-bottom: 1px solid #e2e8f0; padding: 6px 5px; vertical-align: top; }
        .transactions .row-alt td { background-color: #f8fafc; }
        .transactions tfoot td {
            background-color: #eef2f7;
            border-top: 1px solid #cbd5e1;
            font-weight: bold;
        }
        .empty { color: #64748b; padding: 14px !important; text-align: center; }
        .balance { border-collapse: collapse; margin: 8px 0 0 auto; width: 43%; }
        .balance td { border-bottom: 1px solid #e2e8f0; padding: 7px; }
        .balance .final td {
            background-color: #1e293b;
            border-bottom: 1px solid #1e293b;
            color: #ffffff;
            font-weight: bold;
        }
        .balance .amount { text-align: right; }
    </style>
</head>
<body>
    <table width="100%">
        <tr>
            <td width="70%">
                <div class="report-kicker">LAPORAN KEUANGAN</div>
                <div class="report-title">{{ $judul }}</div>
                <div class="report-identity">{{ $yayasan }} &middot; Unit {{ $unit }}</div>
                <div class="report-description">{{ $deskripsiJenjang }}</div>
            </td>
            <td class="printed" width="30%" valign="top">
                DICETAK<br>
                <strong>{{ $dicetakPada }}</strong>
            </td>
        </tr>
    </table>

    <div class="period"><strong>{{ $periode }}</strong></div>

    <table class="cash-summary">
        <tr>
            <td width="33%">
                <div class="summary-label">SALDO AWAL {{ strtoupper($namaRekening) }}</div>
                <div class="summary-value">Rp{{ number_format($saldoAwal, 0, ',', '.') }}</div>
            </td>
            <td width="33%">
                <div class="summary-label">TOTAL KAS MASUK</div>
                <div class="summary-value cash-in">Rp{{ number_format($totalKasMasuk, 0, ',', '.') }}</div>
            </td>
            <td width="34%">
                <div class="summary-label">TOTAL KAS KELUAR</div>
                <div class="summary-value cash-out">Rp{{ number_format($totalKasKeluar, 0, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    <table class="transactions">
        <thead>
            <tr>
                <th class="center" width="5%">No.</th>
                <th width="12%">Tanggal</th>
                <th width="15%">Akun</th>
                <th width="13%">Petugas</th>
                <th width="35%">Deskripsi Transaksi</th>
                <th class="amount" width="10%">Kas Masuk</th>
                <th class="amount" width="10%">Kas Keluar</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transaksi as $index => $trx)
                <tr class="{{ $index % 2 === 1 ? 'row-alt' : '' }}">
                    <td class="center">{{ $index + 1 }}.</td>
                    <td>{{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($trx->akuntansi_jurnal->tanggal_transaksi ?? null, 'd F Y') }}</td>
                    <td>
                        {{ $trx->akuntansi_rekening->kode_rekening ?? '-' }}
                        - {{ $trx->akuntansi_rekening->nama_rekening ?? '-' }}
                    </td>
                    <td>{{ $trx->akuntansi_jurnal->ms_pengguna->nama ?? '-' }}</td>
                    <td>{{ $trx->akuntansi_jurnal->deskripsi ?? '-' }}</td>
                    <td class="amount">
                        @if ($trx->posisi === 'debit')
                            <span class="cash-in">Rp{{ number_format($trx->nominal, 0, ',', '.') }}</span>
                        @else
                            -
                        @endif
                    </td>
                    <td class="amount">
                        @if ($trx->posisi === 'kredit')
                            <span class="cash-out">Rp{{ number_format($trx->nominal, 0, ',', '.') }}</span>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="empty" colspan="7">Tidak ada transaksi pada periode yang dipilih.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="amount">TOTAL</td>
                <td class="amount cash-in">Rp{{ number_format($totalKasMasuk, 0, ',', '.') }}</td>
                <td class="amount cash-out">Rp{{ number_format($totalKasKeluar, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <table class="balance">
        <tr>
            <td>Saldo Awal</td>
            <td class="amount">Rp{{ number_format($saldoAwal, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Total Kas Masuk</td>
            <td class="amount cash-in">+ Rp{{ number_format($totalKasMasuk, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Total Kas Keluar</td>
            <td class="amount cash-out">- Rp{{ number_format($totalKasKeluar, 0, ',', '.') }}</td>
        </tr>
        <tr class="final">
            <td>Saldo Akhir</td>
            <td class="amount">Rp{{ number_format($saldoAkhir, 0, ',', '.') }}</td>
        </tr>
    </table>
</body>
</html>
