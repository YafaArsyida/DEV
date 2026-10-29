<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Formulir Pendaftaran PPDB</title>

    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 10mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 10px;
            line-height: 1.35;
            color: #222;
            margin: 0;
        }
        
        h1, h2, h3, p {
            margin: 0;
        }

        /* HEADER / KOP FORMULIR */
        .header {
            width: 100%;
            border-bottom: 4px solid #000;
            padding-bottom: 8px;
            margin-bottom: 22px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .header-table td {
            border: none;
            vertical-align: middle;
            text-align: center;
            padding: 0;
        }

        .header-logo {
            width: 18%;
        }

        .header-logo img {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }

        .header-title {
            width: 64%;
            text-align: center;
            font-family: "DejaVu Serif", serif;
            font-weight: bold;
        }

        .header-title h2 {
            font-size: 16px;
            margin-bottom: 7px;
        }

        .header-title h3 {
            font-size: 15px;
            margin-bottom: 7px;
        }

        .header-title p {
            font-size: 15px;
        }

        /* IDENTITAS PENDAFTARAN */
        .meta {
            margin-bottom: 12px;
            font-size: 11px;
        }

        .meta-item {
            display: flex;
            gap: 10px;
            margin-bottom: 4px;
        }

        .meta-separator {
            margin-left: 8px;
            margin-right: 5px;
        }

        .meta-label {
            width: 200px;
        }

        /* JUDUL BAGIAN */
        .section-title {
            background-color: #84212b;
            color: #fff;
            text-align: center;
            font-weight: bold;
            font-size: 11px;
            padding: 5px 7px;
            margin-top: 12px;
            margin-bottom: 8px;
        }

        /* TABEL DATA TANPA BORDER */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .data-table td {
            border: none;
            padding: 2px 4px;
            vertical-align: top;
        }

        .number {
            width: 5%;
            text-align: center;
        }

        .label {
            width: 40%;
        }

        .separator {
            width: 5%;
            text-align: right;
        }

        .value {
            width: auto;
        }

        /* TANDA TANGAN DAN FOTO */
        .signature-layout {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            page-break-inside: avoid;
        }

        .signature-layout td {
            width: 50%;
            border: none;
            vertical-align: top;
            text-align: center;
            padding: 5px;
        }

        .signature-space {
            height: 85px;
        }

        .signature-name {
            margin-top: 4px;
        }

        .photo-box {
            width: 90px;
            height: 115px;
            border: 1px solid #777;
            margin: 8px auto;
            text-align: center;
            padding-top: 45px;
            color: #777;
            font-size: 8px;
        }

        .small-note {
            /* font-size: 9px; */
            width: 95%;
            margin-top: 12px;
            margin-right: auto;
            margin-left: auto;
            text-align: justify;
            text-indent: 25px;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>
    {{-- KOP FORMULIR --}}
    <div class="header">
        <table class="header-table">
            <tr>
                {{-- LOGO SEKOLAH --}}
                <td class="header-logo">
                    <img
                        src="{{ public_path('assets/logo/logo.webp') }}"
                        alt="Logo SD Islam Al Hidayah"
                    >
                </td>

                {{-- JUDUL --}}
                <td class="header-title">
                    <h2>PANITIA PPDB</h2>
                    <h3>SD ISLAM AL HIDAYAH KARANGGEDE</h3>
                    <p>TAHUN PELAJARAN 2026/2027</p>
                </td>

                {{-- LOGO YAYASAN --}}
                <td class="header-logo">
                    <img
                        src="{{ public_path('assets/logo/yayasan.webp') }}"
                        alt="Logo Yayasan Al Hidayah"
                    >
                </td>
            </tr>
        </table>
    </div>

    {{-- IDENTITAS PENDAFTARAN --}}
    <div class="meta">
        <div class="meta-item">
            <span class="meta-label">Tanggal Pendataan</span>
            <span class="meta-separator">:</span>
            <span>
                {{ $pendaftaran->created_at
                ? \Carbon\Carbon::parse($pendaftaran->created_at)
                    ->locale('id')
                    ->translatedFormat('d F Y')
                : '-' }}
            </span>
        </div>

        <div class="meta-item">
            <span class="meta-label">Nomor Pendaftaran</span>
            <span class="meta-separator">:</span>
            <span>
                {{ $pendaftaran->nomor_pendaftaran ?? '-' }}
            </span>
        </div>
    </div>

    {{-- REGISTRASI PESERTA DIDIK --}}
    <div class="section-title">REGISTRASI PESERTA DIDIK</div>
    @php
        $dataRegistrasi = [
            [
                'Apakah pernah TK',
                ($pendidikan?->jenjang_asal === 'TK') ? 'Ya' : (
                    filled($pendidikan?->jenjang_asal) ? 'Tidak' : '-'
                ),
            ],
            [
                'Nama TK Asal',
                $pendidikan?->jenjang_asal === 'TK'
                    ? ($pendidikan?->asal_sekolah ?? '-')
                    : '-',
            ],
            [
                'Alamat TK Asal',
                $pendidikan?->jenjang_asal === 'TK'
                    ? ($pendidikan?->alamat_asal_sekolah ?? '-')
                    : '-',
            ],
        ];
    @endphp

    <table class="data-table">
        <tbody>
            @foreach ($dataRegistrasi as $i => $item)
                <tr>
                    <td class="number">{{ $i + 1 }}.</td>
                    <td class="label">{{ $item[0] }}</td>
                    <td class="separator">:</td>
                    <td class="value">{{ $item[1] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- DATA PRIBADI --}}
    <div class="section-title">DATA PRIBADI</div>

    @php
        $dataPribadi = [
            ['Nama Lengkap Calon Peserta Didik', $calonSiswa->nama_siswa ?? '-'],
            ['Nama Panggilan', $calonSiswa->nama_panggilan ?? '-'],
            ['Jenis Kelamin', $calonSiswa->jenis_kelamin ?? '-'],
            ['NIK (Nomor Induk Kependudukan)', $calonSiswa->nik ?? '-'],
            ['Tempat Lahir', $calonSiswa->tempat_lahir ?? '-'],
            [
                'Tanggal Lahir',
                !empty($calonSiswa->tanggal_lahir)
                    ? \Carbon\Carbon::parse($calonSiswa->tanggal_lahir)
                        ->locale('id')
                        ->translatedFormat('d F Y')
                    : '-',
            ],
            ['Cita-cita', $calonSiswa->cita_cita ?? '-'],
            ['Hobi', $calonSiswa->hobi ?? '-'],
            ['Agama', $calonSiswa->agama ?? '-'],
            ['Kewarganegaraan', $calonSiswa->kewarganegaraan ?? '-'],
            ['Anak Ke-',  $calonSiswa->anak_ke ?? '-'],
            ['Jumlah Saudara Kandung', $calonSiswa->jumlah_saudara_kandung ?? '-'],
            ['Jumlah Saudara Tiri', $calonSiswa->jumlah_saudara_tiri ?? '-'],
            ['Anak Yatim/Piatu/Yatim Piatu', $calonSiswa->status_yatim_piatu ??'-'],
            ['Berkebutuhan Khusus', $calonSiswa->berkebutuhan_khusus ??'-'],
            ['Penyakit yang Pernah atau Sedang Diderita', $calonSiswa->penyakit ??'-'],
            ['Alergi yang Diderita Anak', $calonSiswa->alergi ??'-'],
            [
                'Tinggi Badan',
                is_numeric($calonSiswa->tinggi_badan ?? null)
                    ? number_format((float) $calonSiswa->tinggi_badan, 0, ',', '.') . ' cm'
                    : '-',
            ],
            [
                'Berat Badan',
                is_numeric($calonSiswa->berat_badan ?? null)
                    ? number_format((float) $calonSiswa->berat_badan, 0, ',', '.') . ' kg'
                    : '-',
            ],
            [
                'Tempat Tinggal',
                match ($alamat->jenis_tempat_tinggal ?? null) {
                    'rumah_orang_tua' => 'Rumah Orang Tua',
                    'rumah_wali' => 'Rumah Wali',
                    'asrama' => 'Asrama',
                    'panti_asuhan' => 'Panti Asuhan',
                    'kos' => 'Kos',
                    'kontrak' => 'Rumah Kontrak',
                    default => $alamat->jenis_tempat_tinggal ?? '-',
                },
            ],
            ['Alamat Jalan', $alamat->alamat_lengkap ?? '-'],
            // ['Nama Dusun', $alamat->dusun ?? '-'],
            [
                'RT/RW',
                isset($alamat)
                    ? (($alamat->rt ?? '-') . '/' . ($alamat->rw ?? '-'))
                    : '-',
            ],
            ['Nama Kelurahan/Desa', $alamat->kelurahan_desa ?? '-'],
            ['Kecamatan', $alamat->kecamatan ?? '-'],
            ['Kabupaten', $alamat->kabupaten ?? '-'],
            ['Kode Pos', $alamat->kode_pos ?? '-'],
            ['Penerima KPS/PKH/KIP', $calonSiswa->penerima_bantuan_sosial ?? '-'],
            ['No. KS/KKS/PKH/KIP (jika ada)', $calonSiswa->nomor_bantuan_sosial ?? '-'],
        ];
    @endphp

    <table class="data-table">
        <colgroup>
            <col style="width: 25px;">
            <col style="width: 35%;">
            <col style="width: 10px;">
            <col>
        </colgroup>

        <tbody>
            @foreach ($dataPribadi as $i => $item)
                <tr>
                    <td class="number">{{ $i + 4 }}.</td>
                    <td class="label">{{ $item[0] }}</td>
                    <td class="separator">:</td>
                    <td class="value">{{ $item[1] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- DATA AYAH --}}
    <div class="page-break"></div>

    <div class="section-title">DATA AYAH KANDUNG</div>

    @php
        $dataAyah = [
            ['Nama Ayah Kandung', $orangTua->nama_ayah ?? '-'],
            ['NIK Ayah', $orangTua->nik_ayah ?? '-'],
            ['Tempat Lahir Ayah', $orangTua->tempat_lahir_ayah ?? '-'],
            [
                'Tanggal Lahir Ayah',
                !empty($orangTua->tanggal_lahir_ayah)
                    ? \Carbon\Carbon::parse($orangTua->tanggal_lahir_ayah)
                        ->locale('id')
                        ->translatedFormat('d F Y')
                    : '-',
            ],
            ['Pendidikan Terakhir Ayah', $orangTua->pendidikan_ayah ?? '-'],
            ['Pekerjaan Ayah', $orangTua->pekerjaan_ayah ?? '-'],
            [
                'Penghasilan Bulanan Ayah',
                match ($orangTua->penghasilan_ayah ?? null) {
                    'DI_BAWAH_1JT' => 'Di bawah Rp 1.000.000',
                    '1JT_3JT' => 'Rp 1.000.000 – Rp 3.000.000',
                    '3JT_5JT' => 'Rp 3.000.000 – Rp 5.000.000',
                    '5JT_7JT' => 'Rp 5.000.000 – Rp 7.000.000',
                    'LEBIH_7JT' => 'Lebih dari Rp 7.000.000',
                    default => $orangTua->penghasilan_ayah ?? '-',
                },
            ],
            [
                'Berkebutuhan Khusus (Ayah)',
                match ($orangTua->berkebutuhan_khusus_ayah ?? null) {
                    'ya' => 'Ya',
                    'tidak' => 'Tidak',
                    default => $orangTua->berkebutuhan_khusus_ayah ?? '-',
                },
            ],
            ['No. HP Ayah', $orangTua->telepon_ayah ?? '-'],
        ];
    @endphp

    <table class="data-table">
        <colgroup>
            <col style="width: 25px;">
            <col style="width: 35%;">
            <col style="width: 10px;">
            <col>
        </colgroup>

        <tbody>
            @foreach ($dataAyah as $i => $item)
                <tr>
                    <td class="number">{{ $i + 32 }}.</td>
                    <td class="label">{{ $item[0] }}</td>
                    <td class="separator">:</td>
                    <td class="value">{{ $item[1] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- DATA IBU --}}
    <div class="section-title">DATA IBU KANDUNG</div>

    @php
        $dataIbu = [
            ['Nama Ibu Kandung', $orangTua->nama_ibu ?? '-'],
            ['NIK Ibu', $orangTua->nik_ibu ?? '-'],
            ['Tempat Lahir Ibu', $orangTua->tempat_lahir_ibu ?? '-'],
            [
                'Tanggal Lahir Ibu',
                !empty($orangTua->tanggal_lahir_ibu)
                    ? \Carbon\Carbon::parse($orangTua->tanggal_lahir_ibu)
                        ->locale('id')
                        ->translatedFormat('d F Y')
                    : '-',
            ],
            ['Pendidikan Terakhir Ibu', $orangTua->pendidikan_ibu ?? '-'],
            ['Pekerjaan Ibu', $orangTua->pekerjaan_ibu ?? '-'],
            [
                'Penghasilan Bulanan Ibu',
                match ($orangTua->penghasilan_ibu ?? null) {
                    'DI_BAWAH_1JT' => 'Di bawah Rp 1.000.000',
                    '1JT_3JT' => 'Rp 1.000.000 – Rp 3.000.000',
                    '3JT_5JT' => 'Rp 3.000.000 – Rp 5.000.000',
                    '5JT_7JT' => 'Rp 5.000.000 – Rp 7.000.000',
                    'LEBIH_7JT' => 'Lebih dari Rp 7.000.000',
                    default => $orangTua->penghasilan_ibu ?? '-',
                },
            ],
            [
                'Berkebutuhan Khusus (Ibu)',
                match ($orangTua->berkebutuhan_khusus_ibu ?? null) {
                    'ya' => 'Ya',
                    'tidak' => 'Tidak',
                    default => $orangTua->berkebutuhan_khusus_ibu ?? '-',
                },
            ],
            ['No. HP Ibu', $orangTua->telepon_ibu ?? '-'],
        ];
    @endphp

    <table class="data-table">
        <colgroup>
            <col style="width: 25px;">
            <col style="width: 35%;">
            <col style="width: 10px;">
            <col>
        </colgroup>

        <tbody>
            @foreach ($dataIbu as $i => $item)
                <tr>
                    <td class="number">{{ $i + 41 }}.</td>
                    <td class="label">{{ $item[0] }}</td>
                    <td class="separator">:</td>
                    <td class="value">{{ $item[1] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- DATA WALI --}}
    <div class="section-title">DATA WALI</div>

    @php
        $dataWali = [
            ['Nama Wali', $orangTua->nama_wali ?? '-'],
            ['NIK Wali', $orangTua->nik_wali ?? '-'],
            ['Tempat Lahir Wali', $orangTua->tempat_lahir_wali ?? '-'],
            [
                'Tanggal Lahir Wali',
                !empty($orangTua->tanggal_lahir_wali)
                    ? \Carbon\Carbon::parse($orangTua->tanggal_lahir_wali)
                        ->locale('id')
                        ->translatedFormat('d F Y')
                    : '-',
            ],
            [
                'Pendidikan Terakhir Wali',
                $orangTua->pendidikan_wali ?? '-',
            ],
            ['Pekerjaan Wali', $orangTua->pekerjaan_wali ?? '-'],
            [
                'Penghasilan Bulanan Wali',
                match ($orangTua->penghasilan_wali ?? null) {
                    'DI_BAWAH_1JT' => 'Di bawah Rp 1.000.000',
                    '1JT_3JT' => 'Rp 1.000.000 – Rp 3.000.000',
                    '3JT_5JT' => 'Rp 3.000.000 – Rp 5.000.000',
                    '5JT_7JT' => 'Rp 5.000.000 – Rp 7.000.000',
                    'LEBIH_7JT' => 'Lebih dari Rp 7.000.000',
                    default => $orangTua->penghasilan_wali ?? '-',
                },
            ],
            [
                'Berkebutuhan Khusus (Wali)',
                match ($orangTua->berkebutuhan_khusus_wali ?? null) {
                    'ya' => 'Ya',
                    'tidak' => 'Tidak',
                    default => $orangTua->berkebutuhan_khusus_wali ?? '-',
                },
            ],
            ['No. HP Wali', $orangTua->telepon_wali ?? '-'],
        ];
    @endphp

    <table class="data-table">
        <colgroup>
            <col style="width: 25px;">
            <col style="width: 35%;">
            <col style="width: 10px;">
            <col>
        </colgroup>

        <tbody>
            @foreach ($dataWali as $i => $item)
                <tr>
                    <td class="number">{{ $i + 50 }}.</td>
                    <td class="label">{{ $item[0] }}</td>
                    <td class="separator">:</td>
                    <td class="value">{{ $item[1] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- MOTIVASI ORANG TUA --}}
    <div class="section-title">MOTIVASI ORANG TUA</div>
    <table class="data-table">
        <colgroup>
            <col style="width: 25px;">
            <col style="width: 40%;">
            <col style="width: 10px;">
            <col>
        </colgroup>

        <tbody>
            @forelse ($jawaban as $item)
                <tr>
                    <td class="number">{{ 58 + $loop->iteration }}.</td>

                    <td class="label">
                        {{ $item->ppdb_pertanyaan->pertanyaan
                            ?? 'Pertanyaan tidak tersedia' }}
                    </td>

                    <td class="separator">:</td>

                    <td class="value">
                        {{ filled($item->jawaban) ? $item->jawaban : '—' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="value">
                        Belum ada informasi tambahan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- MUTASI --}}
    <div class="page-break"></div>
    <div class="section-title">MUTASI</div>
    <table class="data-table">
        @foreach ([
            'Diterima Tanggal',
            'Ditempatkan di Kelompok',
            'Berasal dari SD/MI',
        ] as $i => $label)
            <tr>
                <td class="number">{{ $i + 61 }}.</td>
                <td class="label">{{ $label }}</td>
                <td class="separator">:</td>
                <td class="value"></td>
            </tr>
        @endforeach
    </table>

    {{-- DATA RINCIAN --}}
    <div class="section-title">DATA RINCIAN PESERTA DIDIK</div>
    @php
        $dataRincian = [
            [
                'Jarak Tempat Tinggal ke Sekolah',
                is_numeric($alamat?->jarak_ke_sekolah)
                    ? number_format(
                        (float) $alamat->jarak_ke_sekolah,
                        0,
                        ',',
                        '.'
                    ) . ' km'
                    : '-',
            ],
            [
                'Waktu Tempuh ke Sekolah',
                is_numeric($alamat?->waktu_tempuh_ke_sekolah)
                    ? number_format(
                        (float) $alamat->waktu_tempuh_ke_sekolah,
                        0,
                        ',',
                        '.'
                    ) . ' menit'
                    : '-',
            ],
            [
                'Mode Transportasi',
                filled($alamat?->moda_transportasi)
                    ? match ($alamat->moda_transportasi) {
                        'jalan_kaki' => 'Jalan Kaki',
                        'sepeda' => 'Sepeda',
                        'sepeda_motor' => 'Sepeda Motor',
                        'mobil' => 'Mobil',
                        'angkutan_umum' => 'Angkutan Umum',
                        'antar_jemput' => 'Antar Jemput',
                        default => $alamat->moda_transportasi,
                    }
                    : '-',
            ],
        ];
    @endphp

    <table class="data-table">
        <colgroup>
            <col style="width: 25px;">
            <col style="width: 35%;">
            <col style="width: 10px;">
            <col>
        </colgroup>

        <tbody>
            @foreach ($dataRincian as $i => $item)
                <tr>
                    <td class="number">{{ $i + 64 }}.</td>
                    <td class="label">{{ $item[0] }}</td>
                    <td class="separator">:</td>
                    <td class="value">{{ $item[1] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- PERNYATAAN DAN TANDA TANGAN --}}
    <p class="small-note">
        Yang bertanda tangan sebagai orang tua/wali atau siswa bertanggung
        jawab secara hukum terhadap kebenaran data yang tercantum dalam
        formulir pendaftaran ini.
    </p>

    <table class="signature-layout">
        <tr>
            <td>
                <div class="photo-box">
                    FOTO SISWA
                </div>
            </td>
            <td>
                Kebonan, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}
                <br>
                Orang Tua/Wali Siswa
                <div class="signature-space"></div>
                <div class="signature-name">
                    (
                        {{ $orangTua->nama_ayah
                            ?: ($orangTua->nama_ibu
                                ?: ($orangTua->nama_wali ?: '................................')) }}
                    )
                </div>
            </td>
        </tr>
    </table>

</body>
</html>