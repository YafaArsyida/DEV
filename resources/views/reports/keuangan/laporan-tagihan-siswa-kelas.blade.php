<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Tagihan Siswa Kelas</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm 12mm 12mm;
        }

        body {
            color: #0f172a;
            /* font-family: DejaVu Sans, sans-serif; */
            font-family: "Times New Roman", Times, serif;
            font-size: 11px;
            line-height: 1.5;
        }

        .header-image img {
            display: block;
            width: 100%;
            max-height: 120px;
            object-fit: contain;
        }

        .meta {
            width: 100%;
            margin-top: 8px;
            margin-bottom: 10px;
        }

        .meta td {
            vertical-align: top;
            padding: 2px 0;
        }

        .label {
            font-weight: bold;
            width: 90px;
        }

        .address {
            margin: 10px 0;
        }

        .content {
            text-align: justify;
            margin: 8px 0;
        }

        .content p {
            margin: 8px 0;
        }

        .signature {
            width: 100%;
            margin-top: 16px;
        }

        .signature td {
            vertical-align: top;
            padding: 2px 0;
        }

        .signature .space {
            width: 85%;
        }

        .signature .name {
            width: 35%;
            text-align: left;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        .summary-table th,
        .summary-table td {
            border: 1px solid #64748b;
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
        }

        .summary-table th {
            background: #f1f5f9;
            font-weight: bold;
        }

        .summary-table .right {
            text-align: right;
        }

        .total {
            margin-top: 10px;
            font-size: 13px;
            font-weight: bold;
        }

        .notes {
            margin-top: 10px;
        }

        .notes p {
            margin: 5px 0;
        }

        .page-break {
            page-break-before: always;
        }

        .small {
            font-size: 10px;
        }

        .report-block {
            page-break-inside: avoid;
            margin-bottom: 12px;
        }
    </style>
</head>
<body>
    @foreach ($reports as $report)
        @php
            $surat = $report['surat'];
            $namaSiswa = $report['namaSiswa'];
            $namaKelas = $report['namaKelas'];
            $tagihanRincian = $report['tagihanRincian'];
            $totalTagihan = $report['totalTagihan'];
            $kopBase64 = $report['kopBase64'];
            $tandaTanganBase64 = $report['tandaTanganBase64'];
        @endphp

        <div class="report-block">
            <div class="header-image">
                <img src="{{ $kopBase64 }}" alt="Kop Surat">
            </div>

            <table class="meta">
                <tr>
                    <td colspan="2" style="text-align: right;">
                        {{ $surat->tempat_tanggal }}
                    </td>
                <tr>
                    <td class="label">No</td>
                    <td>: {{ $surat->nomor_surat }}</td>
                </tr>
                <tr>
                    <td class="label">Lampiran</td>
                    <td>: {{ $surat->lampiran }}</td>
                </tr>
                <tr>
                    <td class="label">Hal</td>
                    <td>: <i>{{ $surat->hal }}</i></td>
                </tr>
            </table>

            <div class="address">
                Kepada Yth.<br>
                Bapak/Ibu Wali Murid Ananda <i>{{ $namaSiswa }}</i><br>
                {{ $namaKelas }}
            </div>

            <div class="content">
                <p>{{ $surat->salam_pembuka }}</p>
            </div>

            <div class="content">
                <p style="text-indent: 20px; text-align: justify;">{{ $surat->pembuka }}</p>
            </div>

            <div class="content">
                <p style="text-indent: 20px; text-align: justify;">{{ $surat->isi }}</p>
            </div>

            @if(!empty($surat->rincian))
                <div class="content">
                    <p style="text-indent: 20px; text-align: justify;">{{ $surat->rincian }} <strong>Rp{{ number_format($totalTagihan, 0, ',', '.') }}</strong> dengan rincian terlampir</p>
                </div>
            @endif

            <div class="content">
                @if(!empty($surat->panduan))
                    <p>{{ $surat->panduan }}</p>
                @endif
                @if(!empty($surat->instruksi_1))
                    <p>{{ $surat->instruksi_1 }}</p>
                @endif
                @if(!empty($surat->instruksi_2))
                    <p>{{ $surat->instruksi_2 }}</p>
                @endif
                @if(!empty($surat->instruksi_3))
                    <p>{{ $surat->instruksi_3 }}</p>
                @endif
                @if(!empty($surat->instruksi_4))
                    <p>{{ $surat->instruksi_4 }}</p>
                @endif
                @if(!empty($surat->instruksi_5))
                    <p>{{ $surat->instruksi_5 }}</p>
                @endif
            </div>

            <div class="content">
                <p style="text-indent: 20px; text-align: justify;">{{ $surat->penutup }}</p>
            </div>

            <div class="content">
                <p>{{ $surat->salam_penutup }}</p>
            </div>

            <table class="signature">
                <tr>
                    <td class="space"></td>
                    <td class="name"><strong>{{ $surat->jabatan }}</strong></td>
                </tr>
                <tr>
                    <td class="space"></td>
                    <td class="name"><img src="{{ $tandaTanganBase64 }}" alt="Tanda Tangan" style="max-height: 60px; max-width: 180px;"></td>
                </tr>
                <tr>
                    <td class="space"></td>
                    <td class="name">{{ $surat->nama_petugas }}</td>
                </tr>
                @if(!empty($surat->nomor_petugas))
                    <tr>
                        <td class="space"></td>
                        <td class="name">{{ $surat->nomor_petugas }}</td>
                    </tr>
                @endif
            </table>

            <div class="page-break"></div>

            <div class="header-image">
                <img src="{{ $kopBase64 }}" alt="Kop Surat">
            </div>

            <p style="font-weight: bold; margin-top: 12px;">Rincian Tagihan Administrasi Sekolah</p>

            <table class="summary-table">
                <thead>
                    <tr>
                        <th style="width: 38%;">Tagihan</th>
                        <th style="width: 22%;">Estimasi</th>
                        <th style="width: 20%;">Dibayarkan</th>
                        <th style="width: 20%;">Kekurangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tagihanRincian as $tagihan)
                        <tr>
                            <td>{{ $tagihan['nama'] }}</td>
                            <td class="right">Rp{{ number_format($tagihan['estimasi'], 0, ',', '.') }}</td>
                            <td class="right">Rp{{ number_format($tagihan['dibayarkan'], 0, ',', '.') }}</td>
                            <td class="right">Rp{{ number_format($tagihan['kekurangan'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="total">Total Kekurangan: Rp{{ number_format($totalTagihan, 0, ',', '.') }}</div>

            <div class="notes">
                @if(!empty($surat->catatan_1))
                    <p>{{ $surat->catatan_1 }}</p>
                @endif
                @if(!empty($surat->catatan_2))
                    <p>{{ $surat->catatan_2 }}</p>
                @endif
                @if(!empty($surat->catatan_3))
                    <p>{{ $surat->catatan_3 }}</p>
                @endif
            </div>
        </div>

        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>
</html>
