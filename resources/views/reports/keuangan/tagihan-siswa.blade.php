<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $judul }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 12mm 10mm 15mm;
        }

        body {
            color: #0f172a;
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
        }

        .report-title {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 4px;
        }

        .report-subtitle {
            font-size: 11px;
            text-align: center;
            margin-bottom: 8px;
        }

        .transactions {
            border-collapse: collapse;
            width: 100%;
        }

        .transactions th {
            background-color: #f5f5f5;
            border: 1px solid #cbd5e1;
            font-size: 8px;
            font-weight: bold;
            padding: 6px 4px;
            text-align: center;
        }

        .transactions td {
            border: 1px solid #e2e8f0;
            padding: 6px 5px;
            vertical-align: top;
        }

        .transactions .total-row td {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .right { text-align: right; }
        .center { text-align: center; }
        .muted { color: #64748b; }
        .amount { font-weight: bold; }
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
    <div class="page-footer">Administrasi Tagihan Siswa</div>

    <div class="report-title">{{ $judul }}</div>
    <div class="report-subtitle">{{ $subjudul }}</div>

    <table class="transactions">
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="25%">Siswa</th>
                <th width="12%">Kelas</th>
                <th width="8%">Tagihan</th>
                <th width="15%">Estimasi</th>
                <th width="15%">Dibayarkan</th>
                <th width="15%">Kekurangan</th>
                <th width="7%">Lunas</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $item)
                <tr>
                    <td class="center muted">{{ $item['nomor'] }}</td>
                    <td>{{ $item['nama_siswa'] }}</td>
                    <td>{{ $item['kelas'] }}</td>
                    <td class="center">{{ $item['jumlah_tagihan'] }} item</td>
                    <td class="right amount">Rp{{ number_format($item['estimasi'], 0, ',', '.') }}</td>
                    <td class="right amount">Rp{{ number_format($item['dibayarkan'], 0, ',', '.') }}</td>
                    <td class="right amount">Rp{{ number_format($item['kekurangan'], 0, ',', '.') }}</td>
                    <td class="center">{{ number_format($item['persen'], 2) }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="center muted" style="padding: 18px;">Tidak ada data tagihan siswa pada kriteria ini.</td>
                </tr>
            @endforelse
            <tr class="total-row">
                <td colspan="3" class="right">TOTAL</td>
                <td class="center">{{ $jumlahItem }} item</td>
                <td class="right">Rp{{ number_format($totalTagihan, 0, ',', '.') }}</td>
                <td class="right">Rp{{ number_format($totalDibayarkan, 0, ',', '.') }}</td>
                <td class="right">Rp{{ number_format($totalKekurangan, 0, ',', '.') }}</td>
                <td class="center">{{ number_format($totalPersen, 2) }}%</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
