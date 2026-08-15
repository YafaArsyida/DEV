<div class="row justify-content-center">
    <div class="col-xxl-6">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
           <div class="card-header">
                <div class="d-flex align-items-center flex-wrap gap-3">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-sm flex-shrink-0">
                                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                    <i class="ri-bar-chart-box-line"></i>
                                </div>
                            </div>

                            <div>
                                <h5 class="fw-bold mb-1">
                                    Laporan Neraca
                                </h5>
                                <small class="text-muted">
                                    Ringkasan posisi aset, kewajiban, dan ekuitas sekolah berdasarkan periode yang dipilih.
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        {{-- EXPORT & CETAK --}}
                        @if ($selectedJenjang)

                            <button type="button"
                                data-bs-toggle="modal"
                                data-bs-target="#ExportLaporan"
                                class="btn btn-success rounded-pill px-4 d-inline-flex align-items-center gap-1">

                                <i class="ri-file-excel-2-line"></i>
                                <span>Export</span>

                            </button>

                            <button type="button"
                                wire:click="cetakLaporan"
                                class="btn btn-danger rounded-pill px-4 d-inline-flex align-items-center gap-1">

                                <i class="ri-printer-line"></i>
                                <span>Cetak</span>

                            </button>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3 mb-3">
                    <div class="col-xxl-12 col-sm-12"> 
                        <div class="row g-2 align-items-center">
                            <!-- Label di sisi kiri -->
                            <div class="col-auto">
                                <label for="startDate" class="form-label text-muted text-uppercase fs-12 fw-medium mb-0">Periode </label>
                            </div>
                            <!-- Input tanggal di sisi kanan -->
                            <div class="col">
                                <div class="row g-2 align-items-center">
                                    <div class="col-lg">
                                        <input type="date" id="endDate" class="form-control" wire:model="endDate" placeholder="0">
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-soft-secondary btn-icon rounded-circle" wire:click="resetTanggal" title="Reset Tanggal">
                                    <i class="ri-refresh-line fs-16"></i>
                                </button>                                
                            </div>
                        </div>
                    </div>
                </div>
                {{-- DATA --}}
                <div class="live-preview">
                    <div class="table-responsive">
                        <div class="text-center py-3">
                            <h4 class="fw-bold mb-1 text-dark">Laporan Neraca</h4>

                            <div class="text-muted fs-13 mb-2">
                                Yayasan Drul Khukama
                                <span class="mx-1">•</span>
                                Unit {{ $namaJenjang }}
                            </div>

                            @if ($endDate)
                                <div class="d-inline-flex align-items-center gap-2 bg-primary-subtle text-primary px-3 py-2 rounded-pill fs-13">
                                    <i class="ri-calendar-line"></i>
                                    <span>
                                        Posisi per
                                        <strong>
                                            {{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($endDate, 'd F Y') }}
                                        </strong>
                                    </span>
                                </div>
                            @else
                                <div class="d-inline-flex align-items-center gap-2 bg-secondary-subtle text-secondary px-3 py-2 rounded-pill fs-13">
                                    <i class="ri-calendar-check-line"></i>
                                    <strong>
                                        Semua Periode
                                    </strong>
                                </div>
                            @endif
                        </div>

                        <table class="table table-hover table-nowrap align-middle mb-0" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-start">
                                        Nama Akun
                                    </th>

                                    <th class="text-end">
                                        Saldo
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                    {{-- ASET --}}
                                <tr>
                                    <td colspan="2" class="bg-primary-subtle text-primary fw-bold text-uppercase fs-12 py-2">
                                        <i class="ri-building-line me-1"></i>
                                        Aset
                                    </td>
                                </tr>

                                @foreach ($kelompok['aset'] as $akun)
                                <tr>
                                    <td class="ps-4">
                                        <span class="fw-medium">
                                            {{ $akun['kode'] }}
                                        </span>

                                        <span class="text-muted">
                                            - {{ $akun['nama'] }}
                                        </span>
                                    </td>

                                    <td class="text-end text-nowrap">
                                        Rp{{ number_format($akun['saldo'], 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach

                                {{-- TOTAL ASET --}}
                                <tr class="border-top border-2">
                                    <td class="fw-bold">
                                        Total Aset
                                    </td>

                                    <td class="text-end fw-bold text-nowrap">
                                        Rp{{ number_format($totalAset, 0, ',', '.' ) }}
                                    </td>
                                </tr>

                                    {{-- KEWAJIBAN --}}
                                <tr>
                                    <td colspan="2" class="bg-warning-subtle text-warning-emphasis fw-bold text-uppercase fs-12 py-2">
                                        <i class="ri-file-list-3-line me-1"></i>
                                        Kewajiban
                                    </td>
                                </tr>

                                @foreach ($kelompok['kewajiban'] as $akun)
                                    <tr>
                                        <td class="ps-4">
                                            <span class="fw-medium">
                                                {{ $akun['kode'] }}
                                            </span>

                                            <span class="text-muted">
                                                - {{ $akun['nama'] }}
                                            </span>
                                        </td>

                                        <td class="text-end text-nowrap">
                                            Rp{{ number_format($akun['saldo'], 0, ',', '.' ) }}
                                        </td>
                                    </tr>
                                @endforeach

                                {{-- TOTAL KEWAJIBAN --}}
                                <tr class="border-top border-2">

                                    <td class="fw-bold">
                                        Total Kewajiban
                                    </td>

                                    <td class="text-end fw-bold text-nowrap">
                                        Rp{{ number_format(
                                            $totalKewajiban,
                                            0,
                                            ',',
                                            '.'
                                        ) }}
                                    </td>

                                </tr>


                                {{-- =================================================
                                    EKUITAS
                                ================================================== --}}
                                <tr>
                                    <td colspan="2"
                                        class="bg-success-subtle text-success fw-bold text-uppercase fs-12 py-2">
                                        <i class="ri-funds-line me-1"></i>
                                        Ekuitas
                                    </td>
                                </tr>

                                {{-- LABA / RUGI --}}
                                @if (isset($labaRugi))
                                    <tr>
                                        <td class="ps-4">
                                            <span class="fw-medium">
                                                Surplus/Defisit Tahun Berjalan
                                            </span>
                                        </td>

                                        <td class="text-end text-nowrap fw-medium">
                                            Rp{{ number_format($labaRugi, 0, ',', '.' ) }}
                                        </td>
                                    </tr>
                                @endif

                                @foreach ($kelompok['ekuitas'] as $akun)
                                    <tr>
                                        <td class="ps-4">
                                            <span class="fw-medium">
                                                {{ $akun['kode'] }}
                                            </span>

                                            <span class="text-muted">
                                                - {{ $akun['nama'] }}
                                            </span>
                                        </td>

                                        <td class="text-end text-nowrap">
                                            Rp{{ number_format($akun['saldo'], 0, ',', '.' ) }}
                                        </td>
                                    </tr>
                                @endforeach

                                {{-- TOTAL EKUITAS --}}
                                <tr class="border-top border-2">
                                    <td class="fw-bold">
                                        Total Ekuitas
                                    </td>

                                    <td class="text-end fw-bold text-nowrap">
                                        Rp{{ number_format($totalEkuitas, 0, ',', '.' ) }}
                                    </td>
                                </tr>

                                    {{-- TOTAL PASSIVA --}}
                                <tr>
                                    <td colspan="2" class="py-2 border-0"></td>
                                </tr>

                                <tr class="fw-bold">
                                    <td class="bg-dark text-white py-3 text-uppercase">
                                        <i class="ri-scales-3-line me-1"></i>
                                        Total Kewajiban + Ekuitas

                                    </td>

                                    <td class="bg-dark text-white text-end py-3 text-nowrap">
                                        Rp{{ number_format($totalPassiva,0,',', '.' ) }}
                                    </td>

                                </tr>

                            </tbody>

                        </table>


                        {{-- =====================================================
                            VALIDASI NERACA
                        ====================================================== --}}
                        @if (isset($selisihNeraca) && $selisihNeraca != 0)
                            <div class="alert alert-warning border-0 rounded-4 mt-3 mb-0">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="avatar-sm flex-shrink-0">
                                        <div class="avatar-title bg-warning-subtle text-warning rounded-circle">
                                            <i class="ri-error-warning-line fs-18"></i>
                                        </div>
                                    </div>

                                    <div>

                                        <h6 class="fw-bold mb-1">
                                            Neraca Belum Seimbang
                                        </h6>

                                        <p class="mb-0 text-muted fs-13">

                                            Terdapat selisih antara Total Aset dan
                                            Total Kewajiban + Ekuitas sebesar

                                            <strong class="text-warning-emphasis">
                                                Rp{{ number_format(abs($selisihNeraca), 0, ',', '.') }}
                                            </strong>.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-success border-0 rounded-4 mt-3 mb-0">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="avatar-sm flex-shrink-0">
                                        <div class="avatar-title bg-success-subtle text-success rounded-circle">
                                            <i class="ri-checkbox-circle-line fs-18"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <h6 class="fw-bold mb-1">
                                            Neraca Seimbang
                                        </h6>

                                        <p class="mb-0 text-muted fs-13">
                                            Total Aset sama dengan Total Kewajiban + Ekuitas.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>  
        </div>
        <div class="modal fade zoomIn" id="ExportLaporan" tabindex="-1" aria-labelledby="exportNeracaLabel" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="modal-header border-0 pb-0">
                        <button type="button"
                            class="btn btn-light btn-icon rounded-circle ms-auto"
                            data-bs-dismiss="modal"
                            aria-label="Close">
                            <i class="ri-close-line fs-18"></i>
                        </button>
                    </div>

                    <div class="modal-body px-4 pb-5 pt-2 text-center">
                        <div class="mb-4">
                            <div class="avatar-xl mx-auto">
                                <div class="avatar-title bg-success-subtle text-success rounded-circle">
                                    <lord-icon src="https://cdn.lordicon.com/fjvfsqea.json"
                                        trigger="loop"
                                        colors="primary:#405189,secondary:#0ab39c"
                                        style="width:70px;height:70px">
                                    </lord-icon>
                                </div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill mb-3">
                                Export Excel
                            </span>

                            <h3 class="fw-bold mb-2" id="exportNeracaLabel">
                                Export Laporan Neraca?
                            </h3>

                            <p class="text-muted mb-0 lh-lg px-lg-4">
                                Apakah Anda yakin ingin mengekspor laporan Neraca
                                <strong class="text-dark">{{ $namaJenjang ?? 'Unit' }}</strong>
                                sesuai periode yang sedang ditampilkan?
                            </p>
                        </div>

                        <div class="alert alert-light border rounded-4 text-start mt-4 mb-0">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                            <i class="ri-bar-chart-box-line fs-18"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex-grow-1">
                                    <h6 class="fw-semibold mb-1">
                                        Laporan Neraca
                                    </h6>

                                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                        <span class="badge bg-primary-subtle text-primary">
                                            {{ $namaJenjang ?? 'Semua Unit' }}
                                        </span>

                                        <span class="text-muted fs-13">
                                            @if ($endDate)
                                                Periode: {{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($endDate, 'd F Y') }}
                                            @else
                                                Semua Periode
                                            @endif
                                        </span>
                                    </div>

                                    <p class="text-muted mb-0 fs-13">
                                        Data laporan akan diekspor sesuai tampilan tabel yang sedang ditampilkan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer border-0 pt-0 px-4 pb-4 justify-content-center">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
                            <i class="ri-close-line me-1"></i>
                            Batal
                        </button>

                        <button type="button"
                            class="btn btn-success rounded-pill px-4"
                            id="konfirmasiExportLaporan"
                            data-bs-dismiss="modal">
                            <i class="ri-file-excel-2-line me-1"></i>
                            Ya, Export
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.getElementById('konfirmasiExportLaporan').addEventListener('click', function () {
                alertify.success("Menyiapkan Dokumen Excel");

                setTimeout(function () {
                    var table = document.querySelector("table");

                    if (!table) {
                        alertify.error("Tabel Laporan Neraca tidak ditemukan.");
                        return;
                    }

                    var workbook = XLSX.utils.table_to_book(table, { sheet: "Neraca" });
                    XLSX.writeFile(workbook, "Laporan-Neraca.xlsx");
                }, 500);
            });
        </script>
    </div>
    <div class="col-xxl-4">
         <div class="card shadow-none mb-3">
            <div class="card-body bg-info-subtle rounded">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <i class="bx bx-info-circle text-info fs-22"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="fs-15 mb-2">Apa itu Laporan Neraca?</h6>
                        <p class="text-muted mb-0">
                            Laporan Neraca (Balance Sheet) adalah laporan keuangan yang menunjukkan kondisi keuangan sekolah pada suatu titik waktu tertentu (biasanya akhir bulan atau akhir tahun ajaran). Neraca selalu dalam keadaan seimbang mengikuti rumus: 
                            <strong>ASET = KEWAJIBAN + EKUITAS</strong>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-none">
            <div class="card-body bg-success-subtle rounded">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <i class="bx bx-list-check text-success fs-22"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="fs-15 mb-2">Fungsi Laporan Neraca</h6>
                        <ul class="mb-0 text-muted">
                            <li>Menampilkan posisi aset, kewajiban, dan ekuitas sekolah</li>
                            <li>Menggambarkan kesehatan keuangan secara menyeluruh</li>
                            <li>Membantu pihak yayasan atau manajemen mengambil keputusan keuangan</li>
                            <li>Menjadi dasar untuk menyusun anggaran di periode berikutnya</li>
                            <li>Digunakan untuk audit dan transparansi keuangan lembaga</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>