
<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Kartu Aljabar</title>
        <style>
            /* =========================================================
            1. PENGATURAN GLOBAL DAN HALAMAN
            ========================================================= */

            @page {
                size: A4 landscape;
                margin: 10mm;
            }

            * {
                box-sizing: border-box;
            }

            html,
            body {
                margin: 0;
                padding: 0;
                font-family: "DejaVu Sans", sans-serif;
                font-size: 10px;
                color: #222;
            }

            .page-content {
                position: relative;
                width: 100%;
            }

            .page-break {
                page-break-after: always;
            }

            /* Background untuk seluruh halaman PDF */
            .page-background {
                position: fixed;
                width: 297mm;
                height: 210mm;
                z-index: -1000;
            }

            .page-background img {
                display: block;
                width: 297mm;
                height: 210mm;
            }


            /* =========================================================
            2. IDENTITAS SEKOLAH
            ========================================================= */

            .school-header {
                width: 100%;
                margin-top: 20mm;
                margin-bottom: 7mm;
                border-collapse: collapse;
                border-bottom: none;
            }

            .school-header td {
                padding: 1mm 2mm 4mm;
                vertical-align: middle;
            }

            .logo-cell {
                width: 19%;
                text-align: center;
            }

            .school-header td:first-child {
                padding-left: 15mm;
            }

            .school-header td:last-child {
                padding-right: 20mm;
            }

            .school-info {
                width: 62%;
                text-align: center;
            }

            .school-logo {
                width: 30mm;
                height: 30mm;
            }

            .school-info h2 {
                margin: 0 0 3mm;
                color: #84212b;
                font-size: 30px;
                font-weight: bold;
                line-height: 1.2;
            }

            .school-info p {
                margin: 1mm 0;
                font-size: 12px;
                font-weight: bold;
                line-height: 1;
            }

            .header-divider {
                width: 85%;
                margin: -7mm auto 7mm;
                border-collapse: collapse;
                table-layout: fixed;
            }

            .header-divider td {
                height: 0;
                padding: 0;
                border-top: 3px solid #333;
                font-size: 0;
                line-height: 0;
            }


            /* =========================================================
            3. JUDUL HALAMAN PERTAMA
            ========================================================= */

            .document-title {
                position: relative;
                top: -4mm;
                margin: 0;
                color: #111;
                font-family: "DejaVu Sans", sans-serif;
                font-size: 34px;
                font-weight: bold;
                line-height: 1.15;
                text-align: center;
            }

            .document-title-sub {
                margin-top: 1mm;
                font-family: "DejaVu Sans", sans-serif;
                font-size: 27px;
                line-height: 1.2;
            }

            .document-subtitle {
                position: relative;
                top: -3mm;
                margin: 1mm 0 11mm;
                font-size: 16px;
                font-weight: bold;
                line-height: 1;
                text-align: center;
            }


            /* =========================================================
            4. PERSYARATAN DAN NOMOR PENDAFTARAN
            ========================================================= */

            .requirements-layout {
                width: 80%;
                margin-right: auto;
                margin-left: auto;
                border-collapse: collapse;
            }

            .requirements-layout > tbody > tr > td {
                vertical-align: middle;
            }

            .requirements {
                width: 73%;
                padding-right: 3mm;
            }

            .registration-column {
                width: 27%;
                padding-left: 2mm;
                text-align: center;
            }

            .requirement-table {
                width: 100%;
                border-collapse: separate;
                border-spacing: 0 1.5mm;
            }

            .requirement-table td {
                vertical-align: middle;
            }

            .requirement-text {
                padding: 2.5mm 3mm;
                border-radius: 2mm;
                background-color: #bd2035;
                color: #fff;
                font-size: 10.5px;
                font-weight: bold;
                line-height: 1.25;
            }

            .requirement-check {
                width: 24mm;
                text-align: center;
            }

            .check-box {
                display: inline-block;
                width: 19mm;
                height: 8mm;
                border: 1.2px solid #871628;
                border-radius: 5mm;
                background-color: #fff;
            }

            .registration-box {
                width: 49mm;
                margin: 0 auto;
                padding: 5mm 3mm;
                border: 2px solid #e56c20;
                border-radius: 5mm;
                background-color: #fff;
                text-align: center;
            }

            .registration-label {
                margin-bottom: 4mm;
                font-size: 12px;
                font-weight: bold;
            }

            .registration-number {
                font-size: 12px;
                font-weight: bold;
                line-height: 1.5;
                overflow-wrap: break-word;
            }

            .registration-note {
                margin-top: 4mm;
                color: #555;
                font-size: 8px;
                line-height: 1.4;
            }


            /* =========================================================
            5. DESIGN SYSTEM HALAMAN KEDUA
            ========================================================= */

            :root {
                --color-primary: #b32332;
                --color-border: #a92b35;
                --color-border-dark: #8e1c29;
                --color-payment: #ff994b;
                --color-payment-text: #54291b;
                --color-white: #fff;

                --font-body: 10px;
                --font-small: 8.5px;
                --font-heading: 12px;
                --font-subheading: 10px;

                --line-body: 1.4;
                --line-compact: 1.25;
            }

            /* =========================================================
            6. AREA KONTEN DAN TATA LETAK
            ========================================================= */

            .page-2-content {
                position: relative;
                width: 100%;
                margin-top: 15mm;
                padding: 0 1mm;
            }

            .student-header,
            .info-row {
                width: 90%;
                margin-right: auto;
                margin-left: auto;
                table-layout: fixed;
            }

            .student-header {
                margin-bottom: 2mm;
                border: 1.2px solid #bd2035;
                border-collapse: separate;
                border-spacing: 0;
                border-radius: 5mm;
                background-color: #fff;
            }

            .info-row {
                margin-bottom: 3mm;
                border-collapse: collapse;
            }

            .info-row-bottom {
                margin-bottom: 0;
            }

            .info-row > tbody > tr > td {
                vertical-align: top;
            }

            .info-left {
                width: 40%;
                padding: 0 3mm 0 0;
            }

            .info-right {
                width: 60%;
                padding: 0 0 0 1mm;
            }

            /* =========================================================
            7. IDENTITAS SISWA
            ========================================================= */

            .student-header > tbody > tr > td {
                padding: 3mm 4mm;
                vertical-align: middle;
            }

            .student-identity {
                width: 70%;
            }

            .student-identity table {
                width: 100%;
                border-collapse: collapse;
            }

            .student-identity td {
                padding: 1mm 0;
                font-size: var(--font-body);
                line-height: var(--line-compact);
            }

            .student-label,
            .student-value,
            .student-separator {
                font-weight: bold;
            }

            .student-label {
                width: 43mm;
            }

            .student-separator {
                width: 5mm;
                text-align: center;
            }

            /* .student-spmb {
                width: 30%;
                text-align: center;
            }

            .student-spmb img {
                width: 37mm;
                height: 24mm;
            } */

            /* =========================================================
            8. PANEL DAN JUDUL BERSAMA
            ========================================================= */

            .info-panel {
                width: 100%;
                border: 1px solid var(--color-border);
                background-color: var(--color-white);
            }

            .panel-title {
                padding: 2.5mm 1.5mm;
                background-color: var(--color-primary);
                color: var(--color-white);
                font-size: var(--font-heading);
                font-weight: bold;
                line-height: var(--line-compact);
                text-align: center;
            }

            .panel-body {
                padding: 2.5mm 3mm;
                background-color: var(--color-white);
                font-size: var(--font-body);
                line-height: var(--line-body);
            }

            .panel-body p {
                margin: 0 0 1.5mm;
            }

            .panel-body ul,
            .activity-table ul {
                margin: 0;
                padding-left: 5mm;
            }

            .panel-body li,
            .activity-table li {
                margin-bottom: 1mm;
                padding-left: 0.5mm;
                font-weight: 700;
            }

            /* =========================================================
            9. KATEGORI DAN TEKS ISI
            ========================================================= */

            /* Dipakai untuk label kecil seperti Jalur, Potongan, Kegiatan */
            .category-heading {
                margin-bottom: 1.5mm;
                font-size: var(--font-subheading);
                font-weight: bold;
                line-height: var(--line-body);
            }

            .category-heading-potongan {
                margin-top: 2mm;
            }

            .category-subitem {
                margin: 1.5mm 0;
                font-size: var(--font-body);
                font-weight: 700;
                line-height: var(--line-body);
            }

            /* Indentasi kategori, bukan aturan font tersendiri */
            .category-panel .category-subitem {
                margin-left: 6mm;
            }

            .category-checkbox {
                display: inline-block;
                width: 5mm;
                height: 5mm;
                margin-right: 2mm;
                border: 1px solid #333;
                background-color: var(--color-white);
                vertical-align: middle;
            }

            /* =========================================================
            10. TABEL KEGIATAN
            ========================================================= */

            .activity-table {
                width: 100%;
                border-collapse: collapse;
                table-layout: fixed;
            }

            .activity-table th,
            .activity-table td {
                width: 50%;
                padding: 2mm;
                border: 1px solid var(--color-border);
                font-size: var(--font-body);
                line-height: var(--line-body);
                vertical-align: top;
            }

            .activity-table th {
                padding: 2.5mm 1mm;
                border-color: var(--color-border-dark);
                background-color: var(--color-primary);
                color: var(--color-white);
                font-size: var(--font-heading);
                font-weight: bold;
                line-height: var(--line-compact);
                text-align: center;
                vertical-align: middle;
            }

            /* =========================================================
            11. TABEL PEMBAYARAN
            ========================================================= */

            .payment-table {
                width: 100%;
                border-collapse: collapse;
                table-layout: fixed;
                font-size: var(--font-small);
                line-height: var(--line-body);
            }

            .payment-table th,
            .payment-table td {
                padding: 1.5mm 1.2mm;
                border: 1px solid var(--color-border);
                vertical-align: top;
            }

            .payment-table th,
            .payment-table .payment-stage td {
                background-color: var(--color-payment);
                color: var(--color-payment-text);
                font-weight: bold;
                text-align: center;
            }

            .payment-table .payment-stage td {
                padding: 1.5mm;
            }

            .payment-table .payment-item {
                width: 43%;
            }

            .payment-table .payment-nominal {
                width: 25%;
                text-align: center;
            }

            .payment-table .payment-paraf {
                width: 18%;
                text-align: center;
            }

            .payment-table .payment-date {
                width: 14%;
                text-align: center;
            }

            .payment-table .payment-amount {
                font-weight: bold;
                text-align: center;
                white-space: nowrap;
            }

            .payment-table .payment-detail td {
                height: 5mm;
                font-weight: 700;
            }

            .payment-note {
                padding: 2mm 2mm 2.5mm;
                color: #bd2035;
                font-size: 8px;
                font-style: italic;
                font-weight: bold;
                line-height: 1.4;
            }

        </style>

    </head>

    <body>

        {{-- BACKGROUND DIGUNAKAN PADA SEMUA HALAMAN --}}
        <div class="page-background">
            <img
                src="{{ public_path('assets/pdf/kartu-aljabar-background.png') }}"
                alt=""
            >
        </div>

        {{-- ==========================================================
            HALAMAN 1: KARTU ALJABAR
        =========================================================== --}}

        <div class="page-content">
            <table class="school-header">
                <tr>
                    {{-- LOGO SEKOLAH --}}
                    <td class="logo-cell">
                        <img
                            src="{{ public_path('assets/logo/logo.png') }}"
                            class="school-logo"
                            alt="Logo sekolah"
                        >
                    </td>

                    {{-- IDENTITAS SEKOLAH --}}
                    <td class="school-info">
                        <h2>SD ISLAM AL HIDAYAH</h2>

                        <p>
                            Dusun Kebonan RT 04/RW 01, Desa Kebonan,
                            Kecamatan Karanggede
                        </p>

                        <p>
                            Kabupaten Boyolali, Jawa Tengah 57381
                        </p>

                        <p>HP. 0822 2626 6861</p>
                    </td>

                    {{-- LOGO SPMB --}}
                    <td class="logo-cell">
                        <img
                            src="{{ public_path('assets/pdf/spmb.png') }}"
                            class="spmb-logo"
                            alt="Logo SPMB"
                        >
                    </td>
                </tr>
            </table>

            {{-- GARIS PEMISAH HEADER --}}
            <table class="header-divider">
                <tr>
                    <td></td>
                </tr>
            </table>


            <div class="document-title">
                KARTU ALJABAR

                <div class="document-title-sub">
                    (Al Hidayah Jejak Baru)
                </div>
            </div>

            <div class="document-subtitle">
                Syarat Pendataan SPMB Tahun Pelajaran 2026/2027
            </div>

            <table class="requirements-layout">
                <tr>
                    <td class="requirements">
                        <table class="requirement-table">
                            @foreach ($persyaratan as $item)
                                <tr>
                                    <td class="requirement-text">
                                        {{ $item }}
                                    </td>

                                    <td class="requirement-check">
                                        <span class="check-box"></span>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </td>

                    <td class="registration-column">
                        <div class="registration-box">
                            <div class="registration-label">
                                NOMOR PENDAFTARAN
                            </div>

                            <div class="registration-number">
                                {{ $nomor_pendaftaran }}
                            </div>

                            <div class="registration-note">
                                Simpan kartu ini sebagai bukti
                                pendaftaran dan referensi administrasi.
                            </div>
                        </div>
                    </td>
                </tr>
            </table>

        </div>

        <div class="page-break"></div>

        <div class="page-content page-2-content">

            {{-- ======================================================
                HEADER IDENTITAS SISWA
            ======================================================= --}}
            <table class="student-header">
                <tbody>
                    <tr>
                        <td class="student-identity">
                            <table>
                                <tbody>
                                    <tr>
                                        <td class="student-label">Nama</td>
                                        <td class="student-separator">:</td>
                                        <td class="student-value">
                                            {{ $nama_siswa ?? '-' }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="student-label">Tempat, Tanggal Lahir</td>
                                        <td class="student-separator">:</td>
                                        <td class="student-value">
                                            {{ $tempat_lahir ?? '-' }},
                                            {{ !empty($tanggal_lahir) ? $tanggal_lahir : '-' }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="student-label">Jenis Kelamin</td>
                                        <td class="student-separator">:</td>
                                        <td class="student-value">
                                            {{ $jenis_kelamin ?? '-' }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="student-label">Nomor Telepon</td>
                                        <td class="student-separator">:</td>
                                        <td class="student-value">
                                            {{ $telepon ?? '-' }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>

                        <td class="student-spmb">
                            <img
                                src="{{ public_path('assets/pdf/spmb.png') }}"
                                alt="Logo SPMB"
                            >
                        </td>
                    </tr>
                </tbody>
            </table>


            {{-- ======================================================
                BARIS PERTAMA: KATEGORI SISWA DAN KEGIATAN
            ======================================================= --}}
            <table class="info-row">
                <tbody>
                    <tr>

                        {{-- KOLOM KIRI: KATEGORI SISWA --}}
                        <td class="info-left">
                            <div class="info-panel category-panel">

                                <div class="panel-title">
                                    Kategori Siswa
                                </div>

                                <div class="panel-body">

                                    {{-- JALUR --}}
                                    <div class="category-heading">
                                        Jalur
                                    </div>

                                    <div class="category-subitem">
                                        <span class="category-checkbox"></span>
                                        {{ $nama_jalur }}
                                    </div>
                                    {{--
                                    <div class="category-subitem">
                                        <span class="category-checkbox"></span>
                                        Alumni
                                    </div>
                                    --}}

                                    {{-- POTONGAN --}}
                                    <div class="category-heading category-heading-potongan">
                                        Potongan
                                    </div>

                                    <div class="category-subitem">
                                        <span class="category-checkbox"></span>
                                        Pengembangan 100% (November - Desember)
                                    </div>

                                    <div class="category-subitem">
                                        <span class="category-checkbox"></span>
                                        Kakak Adik (20% APP)
                                    </div>

                                </div>
                            </div>
                        </td>


                        {{-- KOLOM KANAN: KEGIATAN --}}
                        <td class="info-right">
                            <table class="activity-table">
                                <thead>
                                    <tr>
                                        <th>Januari – Februari</th>
                                        <th>Mei – Juni</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="category-heading">
                                                Kegiatan:
                                            </div>

                                            <ul>
                                                <li>Pengukuran Seragam</li>
                                                <li>Skrining Psikologi</li>
                                            </ul>
                                        </td>

                                        <td>
                                            <div class="category-heading">
                                                Kegiatan:
                                            </div>

                                            <ul>
                                                <li>Pengambilan Buku</li>
                                                <li>Pengambilan Seragam</li>
                                                <li>Golden Gate</li>
                                            </ul>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>

                    </tr>
                </tbody>
            </table>


            {{-- ======================================================
                BARIS KEDUA: SYARAT DAN PEMBAYARAN
            ======================================================= --}}
            <table class="info-row info-row-bottom">
                <tbody>
                    <tr>

                        {{-- KOLOM KIRI: SYARAT PENDATAAN SPMB --}}
                        <td class="info-left">
                            <div class="info-panel">

                                <div class="panel-title">
                                    Syarat Pendataan SPMB
                                </div>

                                <div class="panel-body">
                                    <p>
                                        <strong>Kegiatan:</strong>
                                    </p>

                                    <ul>
                                        <li>Isi Formulir</li>
                                        <li>Pengumpulan Syarat SPMB</li>
                                        <li>Wawancara Wali Murid</li>
                                    </ul>
                                </div>

                                {{-- PEMBAYARAN PENDATAAN --}}
                                <table class="payment-table">
                                    <thead>
                                        <tr>
                                            <th class="payment-item">Pembayaran</th>
                                            <th class="payment-nominal">Nominal</th>
                                            <th class="payment-paraf">Paraf</th>
                                            <th class="payment-date">Tgl.</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr class="payment-detail">
                                            <td>Pendataan</td>
                                            <td class="payment-amount">Rp 150.000</td>
                                            <td class="payment-paraf"></td>
                                            <td class="payment-date"></td>
                                        </tr>

                                        <tr class="payment-detail">
                                            <td>Skrining Psikologi</td>
                                            <td class="payment-amount">Rp 150.000</td>
                                            <td class="payment-paraf"></td>
                                            <td class="payment-date"></td>
                                        </tr>

                                        <tr class="payment-detail">
                                            <td>
                                                Seragam 5 Stel
                                                <br>
                                                <i>(3 Seragam Jadi dan 2 Kaos Olah Raga)</i>

                                                @if ($jenis_kelamin === 'Laki-Laki')
                                                    <br>
                                                    &nbsp;&nbsp;- Laki-Laki
                                                @elseif ($jenis_kelamin === 'Perempuan')
                                                    <br>
                                                    &nbsp;&nbsp;- Perempuan + Jilbab
                                                @endif
                                            </td>

                                            <td class="payment-amount">
                                                @if ($jenis_kelamin === 'Laki-Laki')
                                                    Rp 740.000 
                                                @elseif ($jenis_kelamin === 'Perempuan')
                                                    Rp 890.000 
                                                @endif
                                            </td>

                                            <td class="payment-paraf"></td>
                                            <td class="payment-date"></td>
                                        </tr>

                                    </tbody>
                                </table>
                                {{-- CATATAN SKRINING PSIKOLOGI --}}
                                <div class="payment-note">
                                    *Jika skrining psikologi susulan akan dikenai biaya tambahan sebesar Rp100.000.
                                </div>

                            </div>
                        </td>


                        {{-- KOLOM KANAN: PEMBAYARAN --}}
                        <td class="info-right">
                            <div class="info-panel">

                                <div class="panel-title">
                                    Pembayaran
                                </div>

                                <table class="payment-table">
                                    <colgroup>
                                        <col style="width: 43%">
                                        <col style="width: 25%">
                                        <col style="width: 18%">
                                        <col style="width: 14%">
                                    </colgroup>

                                    <tbody>

                                        {{-- TAHAP I --}}
                                        <tr>
                                            <th class="payment-item">Tahap I (Januari - Februari)</th>
                                            <th class="payment-nominal">Nominal</th>
                                            <th class="payment-paraf">Paraf</th>
                                            <th class="payment-date">Tgl.</th>
                                        </tr>

                                        <tr class="payment-detail">
                                            <td>Buku 1 Tahun</td>
                                            <td class="payment-amount">Rp 640.000</td>
                                            <td class="payment-paraf"></td>
                                            <td class="payment-date"></td>
                                        </tr>


                                        {{-- TAHAP II --}}
                                        <tr>
                                            <th class="payment-item">Tahap II (Maret - April)</th>
                                            <th class="payment-nominal">Nominal</th>
                                            <th class="payment-paraf">Paraf</th>
                                            <th class="payment-date">Tgl.</th>
                                        </tr>

                                        <tr class="payment-detail">
                                            <td>Kegiatan 1 Tahun</td>
                                            <td class="payment-amount">Rp 450.000</td>
                                            <td class="payment-paraf"></td>
                                            <td class="payment-date"></td>
                                        </tr>

                                        <tr class="payment-detail">
                                            <td>SPP Bulan Juli</td>
                                            <td class="payment-amount">Rp 370.000</td>
                                            <td class="payment-paraf"></td>
                                            <td class="payment-date"></td>
                                        </tr>

                                        <tr class="payment-detail">
                                            <td>Perlengkapan</td>
                                            <td class="payment-amount">Rp 375.000</td>
                                            <td class="payment-paraf"></td>
                                            <td class="payment-date"></td>
                                        </tr>


                                        {{-- TAHAP III --}}
                                        <tr>
                                            <th class="payment-item">Tahap III (Mei - Juni)</th>
                                            <th class="payment-nominal">Nominal</th>
                                            <th class="payment-paraf">Paraf</th>
                                            <th class="payment-date">Tgl.</th>
                                        </tr>

                                        <tr class="payment-detail">
                                            <td>Alat Penunjang Pendidikan</td>
                                            <td class="payment-amount">Rp 620.000</td>
                                            <td class="payment-paraf"></td>
                                            <td class="payment-date"></td>
                                        </tr>

                                        <tr class="payment-detail">
                                            <td>Pengembangan</td>
                                            <td class="payment-amount">Rp 800.000</td>
                                            <td class="payment-paraf"></td>
                                            <td class="payment-date"></td>
                                        </tr>

                                    </tbody>
                                </table>

                            </div>
                        </td>

                    </tr>
                </tbody>
            </table>

        </div>
    </body>
</html>
