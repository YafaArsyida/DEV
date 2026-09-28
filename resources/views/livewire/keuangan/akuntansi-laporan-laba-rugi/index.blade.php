<div class="row justify-content-center">
    <div class="col-xxl-12">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header">
                <div class="d-flex align-items-center flex-wrap gap-3">
                    {{-- JUDUL --}}
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-sm flex-shrink-0">
                                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                    <i class="ri-line-chart-line"></i>
                                </div>
                            </div>

                            <div>
                                <h5 class="fw-bold mb-1">
                                    Laporan Laba Rugi
                                </h5>
                                <small class="text-muted">
                                    Ringkasan pendapatan, pengeluaran, dan laba rugi berdasarkan periode yang dipilih.
                                </small>
                            </div>

                        </div>
                    </div>

                    {{-- ACTION & PERIODE --}}
                    <div class="d-flex align-items-center gap-2 flex-wrap">

                        {{-- EXPORT --}}
                        <button type="button"
                            data-bs-toggle="modal"
                            data-bs-target="#ExportLaporan"
                            class="btn rounded-pill px-4 btn-success d-inline-flex align-items-center gap-1">
                            <i class="ri-file-excel-2-line"></i>
                            <span>Export</span>
                        </button>

                        {{-- CETAK --}}
                        @if ($selectedJenjang)
                            <button type="button"
                                wire:click="cetakLaporan"
                                class="btn rounded-pill px-4 btn-danger d-inline-flex align-items-center gap-1">
                                <i class="ri-printer-line"></i>
                                <span>Cetak</span>
                            </button>
                        @endif

                        {{-- PEMBATAS --}}
                        <div class="vr d-none d-lg-block"></div>
                        {{-- PERIODE --}}
                        <div class="d-flex align-items-center gap-2">
                            <input type="date"
                                class="form-control"
                                wire:model="startDate"
                                title="Tanggal Mulai">

                            <span class="text-muted">–</span>

                            <input type="date"
                                class="form-control"
                                wire:model="endDate"
                                title="Tanggal Akhir">

                            <button type="button"
                                class="btn btn-soft-secondary"
                                wire:click="resetTanggal"
                                title="Reset Tanggal">
                                <i class="ri-refresh-line"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                {{-- DATA --}}
                <div class="live-preview">
                    <div class="table-responsive">
                        <div class="text-center py-3">
                            {{-- JUDUL --}}
                            <h4 class="fw-bold mb-1 text-dark">
                                Laporan Laba Rugi
                            </h4>

                            {{-- UNIT --}}
                            <div class="text-muted fs-13 mb-2">
                                Teman Sekolah
                                <span class="mx-1">•</span>
                                Unit {{ $namaJenjang }}
                            </div>

                            {{-- PERIODE --}}
                            @if ($startDate && $endDate)

                                <div class="d-inline-flex align-items-center gap-2 bg-primary-subtle text-primary px-3 py-2 rounded-pill fs-13">
                                    <i class="ri-calendar-line"></i>
                                    <span>
                                        Periode
                                        <strong>
                                            {{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($startDate, 'd F Y') }}
                                        </strong>
                                        <span class="mx-1">–</span>
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
                        <table id="DataIndex" class="table table-bordered table-hover table-nowrap align-middle text-center" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-start">Nama Rekening</th>
                                    @foreach ($bulanIndo as $bulan)
                                        <th>{{ $bulan }}</th>
                                    @endforeach
                                    <th>TOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- PENDAPATAN --}}
                                <tr>
                                    <td colspan="{{ $bulanIndo->count() + 2 }}"
                                        class="text-start bg-primary-subtle text-primary fw-bold text-uppercase fs-12 py-2">

                                        <i class="ri-arrow-up-circle-line me-1"></i>
                                        Pendapatan

                                    </td>
                                </tr>

                                @foreach ($pendapatanPerBulan as $namaRekening => $dataPerBulan)
                                    <tr>
                                        <td class="text-start ps-4">
                                            {{ $namaRekening }}
                                        </td>

                                        @foreach ($bulanIndo as $key => $namaBulan)

                                            <td class="text-end text-nowrap">
                                                Rp{{ number_format(
                                                    optional($dataPerBulan[$key] ?? null)->sum('nominal_laporan'),
                                                    0,
                                                    ',',
                                                    '.'
                                                ) }}
                                            </td>

                                        @endforeach

                                        <td class="text-end text-nowrap fw-semibold">
                                            Rp{{ number_format(
                                                $totalPendapatanRekening[$namaRekening] ?? 0,
                                                0,
                                                ',',
                                                '.'
                                            ) }}
                                        </td>
                                    </tr>

                                @endforeach


                                {{-- TOTAL PENDAPATAN --}}
                                <tr class="border-top border-2">

                                    <td class="text-start fw-bold">
                                        Total Pendapatan
                                    </td>

                                    @foreach ($bulanIndo as $key => $namaBulan)

                                        <td class="text-end fw-bold text-nowrap">
                                            Rp{{ number_format(
                                                $totalPendapatanPerBulan[$key] ?? 0,
                                                0,
                                                ',',
                                                '.'
                                            ) }}
                                        </td>

                                    @endforeach

                                    <td class="text-end fw-bold text-nowrap">
                                        Rp{{ number_format(
                                            $totalPendapatan,
                                            0,
                                            ',',
                                            '.'
                                        ) }}
                                    </td>

                                </tr>


                                {{-- =====================================================
                                    BEBAN
                                ====================================================== --}}
                                <tr>
                                    <td colspan="{{ $bulanIndo->count() + 2 }}"
                                        class="text-start bg-danger-subtle text-danger fw-bold text-uppercase fs-12 py-2">

                                        <i class="ri-arrow-down-circle-line me-1"></i>
                                        Beban

                                    </td>
                                </tr>

                                @foreach ($bebanPerBulan as $namaRekening => $dataPerBulan)

                                    <tr>
                                        <td class="text-start ps-4">
                                            {{ $namaRekening }}
                                        </td>

                                        @foreach ($bulanIndo as $key => $namaBulan)

                                            <td class="text-end text-nowrap">
                                                Rp{{ number_format(
                                                    optional($dataPerBulan[$key] ?? null)->sum('nominal_laporan'),
                                                    0,
                                                    ',',
                                                    '.'
                                                ) }}
                                            </td>

                                        @endforeach

                                        <td class="text-end text-nowrap fw-semibold">
                                            Rp{{ number_format(
                                                $totalBebanRekening[$namaRekening] ?? 0,
                                                0,
                                                ',',
                                                '.'
                                            ) }}
                                        </td>
                                    </tr>

                                @endforeach


                                {{-- TOTAL BEBAN --}}
                                <tr class="border-top border-2">

                                    <td class="text-start fw-bold">
                                        Total Beban
                                    </td>

                                    @foreach ($bulanIndo as $key => $namaBulan)

                                        <td class="text-end fw-bold text-nowrap">
                                            Rp{{ number_format(
                                                $totalBebanPerBulan[$key] ?? 0,
                                                0,
                                                ',',
                                                '.'
                                            ) }}
                                        </td>

                                    @endforeach

                                    <td class="text-end fw-bold text-nowrap">
                                        Rp{{ number_format(
                                            $totalBeban,
                                            0,
                                            ',',
                                            '.'
                                        ) }}
                                    </td>

                                </tr>


                                    {{-- LABA / RUGI --}}
                                <tr>
                                    <td colspan="{{ $bulanIndo->count() + 2 }}"
                                        class="py-1 border-0">
                                    </td>
                                </tr>

                                <tr class="fw-bold">

                                    <td class="text-start bg-dark text-white py-3">
                                        <i class="ri-line-chart-line me-1"></i>
                                        Laba (Rugi)
                                    </td>

                                    @foreach ($bulanIndo as $key => $namaBulan)

                                        <td class="text-end bg-dark text-white py-3 text-nowrap">
                                            Rp{{ number_format(
                                                $labaRugiPerBulan[$key] ?? 0,
                                                0,
                                                ',',
                                                '.'
                                            ) }}
                                        </td>

                                    @endforeach

                                    <td class="text-end bg-dark text-white py-3 text-nowrap">
                                        Rp{{ number_format(
                                            $totalLabaRugi,
                                            0,
                                            ',',
                                            '.'
                                        ) }}
                                    </td>

                                </tr>

                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>  
        </div>
        <div class="modal fade zoomIn"
            id="ExportLaporan"
            tabindex="-1"
            aria-labelledby="exportLaporanLabel"
            aria-hidden="true"
            wire:ignore.self>

            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

                    {{-- CLOSE BUTTON --}}
                    <div class="modal-header border-0 pb-0">
                        <button type="button"
                            class="btn btn-light btn-icon rounded-circle ms-auto"
                            data-bs-dismiss="modal"
                            aria-label="Close">
                            <i class="ri-close-line fs-18"></i>
                        </button>
                    </div>

                    {{-- BODY --}}
                    <div class="modal-body px-4 pb-5 pt-2 text-center">

                        {{-- ICON --}}
                        <div class="mb-4">
                            <div class="avatar-xl mx-auto">
                                <div class="avatar-title bg-success-subtle text-success rounded-circle">
                                    <lord-icon
                                        src="https://cdn.lordicon.com/fjvfsqea.json"
                                        trigger="loop"
                                        colors="primary:#405189,secondary:#0ab39c"
                                        style="width:70px;height:70px">
                                    </lord-icon>
                                </div>
                            </div>
                        </div>

                        {{-- TITLE --}}
                        <div class="mb-2">

                            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill mb-3">
                                <i class="ri-file-excel-2-line me-1"></i>
                                Export Excel
                            </span>

                            <h3 class="fw-bold mb-2" id="exportLaporanLabel">
                                Export Laporan Laba Rugi?
                            </h3>

                            <p class="text-muted mb-0 lh-lg px-lg-3">
                                Apakah Anda yakin ingin mengekspor
                                <strong class="text-dark">
                                    Laporan Laba Rugi
                                </strong>
                                ke Excel?
                            </p>

                        </div>

                        {{-- INFORMATION --}}
                        <div class="alert alert-light border rounded-4 text-start mt-4 mb-0">

                            {{-- JUDUL --}}
                            <div class="d-flex align-items-start gap-3">

                                <div class="flex-shrink-0">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                            <i class="ri-line-chart-line fs-18"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex-grow-1">

                                    <h6 class="fw-semibold mb-1">
                                        Laporan Laba Rugi
                                    </h6>

                                    <p class="text-muted mb-3 fs-13">
                                        Data yang diekspor akan mengikuti periode
                                        dan filter laporan yang sedang digunakan.
                                    </p>

                                    {{-- PERIODE --}}
                                    <div class="d-flex align-items-center gap-2 mb-2">

                                        <i class="ri-calendar-line text-primary"></i>

                                        <span class="text-muted fs-13">
                                            Periode:
                                        </span>

                                        <span class="fw-medium fs-13 text-dark">
                                            {{ $startDate
                                                ? \App\Http\Controllers\HelperController::formatTanggalIndonesia($startDate, 'd F Y')
                                                : '-' }}

                                            <span class="text-muted mx-1">–</span>

                                            {{ $endDate
                                                ? \App\Http\Controllers\HelperController::formatTanggalIndonesia($endDate, 'd F Y')
                                                : '-' }}
                                        </span>

                                    </div>

                                    {{-- JENJANG --}}
                                    @if ($selectedJenjang)
                                        <div class="d-flex align-items-center gap-2">

                                            <i class="ri-school-line text-primary"></i>

                                            <span class="text-muted fs-13">
                                                Jenjang:
                                            </span>

                                            <span class="badge bg-primary-subtle text-primary">
                                                {{ $namaJenjang }}
                                            </span>

                                        </div>
                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- FOOTER --}}
                    <div class="modal-footer border-0 pt-0 px-4 pb-4 justify-content-center">

                        <button type="button"
                            class="btn btn-light rounded-pill px-4"
                            data-bs-dismiss="modal">

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
            document.getElementById('konfirmasiExportLaporan')
                .addEventListener('click', function () {

                    alertify.success("Menyiapkan Dokumen Excel");

                    setTimeout(function () {

                        // Ambil tabel laporan
                        const table = document.getElementById('DataIndex');

                        if (!table) {
                            alertify.error("Tabel Laporan Laba Rugi tidak ditemukan.");
                            return;
                        }

                        // Konversi tabel ke Excel
                        const workbook = XLSX.utils.table_to_book(table, {
                            sheet: "Laba Rugi"
                        });

                        // Format tanggal untuk nama file
                        const startDate = @json($startDate);
                        const endDate = @json($endDate);

                        const formatTanggal = function (tanggal) {

                            if (!tanggal) {
                                return '';
                            }

                            return tanggal.replace(/-/g, '');
                        };

                        const periode = startDate && endDate
                            ? `${formatTanggal(startDate)}-${formatTanggal(endDate)}`
                            : 'Semua-Periode';

                        // Nama file
                        const namaFile = `Laporan-Laba-Rugi-${periode}.xlsx`;

                        XLSX.writeFile(workbook, namaFile);

                    }, 500);
                });
        </script>
    </div>
</div>