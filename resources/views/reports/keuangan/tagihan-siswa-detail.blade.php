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

        .identity {
            border-collapse: collapse;
            margin-bottom: 10px;
            width: 100%;
        }

        .identity td {
            padding: 4px 0;
            vertical-align: top;
        }

        .transactions {
            border-collapse: collapse;
            width: 100%;
        }

        .transactions th {
            background-color: #f0f0f0;
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
    <div class="page-footer">Detail Tagihan Siswa</div>

    <div class="report-title">{{ $judul }}</div>
    <div class="report-subtitle">{{ $subjudul }}</div>

    <table class="identity">
        <tr>
            <td width="15%"><strong>Nama Siswa</strong></td>
            <td width="35%">: {{ $nama_siswa }}</td>
            <td width="15%"><strong>Kelas</strong></td>
            <td width="35%">: {{ $kelas }}</td>
        </tr>
        <tr>
            <td width="15%"><strong>Kategori</strong></td>
            <td width="35%">: {{ $kategori }}</td>
            <td width="15%"><strong>Jumlah Tagihan</strong></td>
            <td width="35%">: {{ $jumlah_tagihan }} item</td>
        </tr>
    </table>

    <table class="transactions">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="17%">Jenis Tagihan</th>
                <th width="13%">Kategori</th>
                <th width="10%">Cicilan</th>
                <th width="12%">Estimasi</th>
                <th width="12%">Dibayarkan</th>
                <th width="12%">Kekurangan</th>
                <th width="10%">Jatuh Tempo</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $item)
                <tr>
                    <td class="center muted">{{ $item['nomor'] }}</td>
                    <td>{{ $item['jenis_tagihan'] }}</td>
                    <td>{{ $item['kategori'] }}</td>
                    <td class="center">{{ $item['cicilan'] }}</td>
                    <td class="right amount">Rp{{ number_format($item['estimasi'], 0, ',', '.') }}</td>
                    <td class="right amount">Rp{{ number_format($item['dibayarkan'], 0, ',', '.') }}</td>
                    <td class="right amount">Rp{{ number_format($item['kekurangan'], 0, ',', '.') }}</td>
                    <td class="center">{{ $item['jatuh_tempo'] }}</td>
                    <td class="center">{{ $item['status'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="center muted" style="padding: 18px;">Tidak ada data tagihan siswa untuk siswa ini.</td>
                </tr>
            @endforelse
            <tr class="total-row">
                <td colspan="4" class="right">TOTAL</td>
                <td class="right">Rp{{ number_format($total_estimasi, 0, ',', '.') }}</td>
                <td class="right">Rp{{ number_format($total_dibayarkan, 0, ',', '.') }}</td>
                <td class="right">Rp{{ number_format($total_kekurangan, 0, ',', '.') }}</td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>
</body>
</html>
