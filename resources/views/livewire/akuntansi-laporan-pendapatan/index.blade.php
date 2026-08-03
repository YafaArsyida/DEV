<div class="">
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header border-0 pb-0">
            <div class="d-flex align-items-center flex-wrap gap-3">
                {{-- Judul --}}
                <h5 class="card-title mb-0 flex-grow-1">Laporan Pendapatan</h5>
            
                {{-- Tombol Export & Cetak --}}
                <div class="d-flex gap-2 flex-wrap">
                    <button data-bs-toggle="modal" data-bs-target="#ExportLaporan" class="btn rounded-pill px-4 btn-success">
                        <i class="ri-file-excel-2-line pb-0"></i> Export
                    </button>
                    <button wire:click="cetakLaporan" class="btn rounded-pill px-4 btn-danger d-inline-flex align-items-center gap-1">
                        <i class="ri-printer-line align-bottom"></i>
                        <span>Cetak Laporan</span>
                    </button>
                </div>
            
                <div class="d-flex align-items-center gap-2">
                    <input type="date" class="form-control" wire:model="startDate">
                    <span class="text-muted">–</span>
                    <input type="date" class="form-control" wire:model="endDate">
                    <button type="button" class="btn btn-soft-secondary" wire:click="resetTanggal" title="Reset Tanggal">
                        <i class="ri-refresh-line"></i>
                    </button>
                </div>
            </div>
        </div><!-- end card header -->
        <div class="card-body">
            <div class="live-preview">
                <div class="table-responsive">
                    <div class="text-center my-3">
                        <h4 class="mb-0">Laporan Pendapatan Sekolah</h4>
                        <div>Yayasan Drul Khukama Unit {{ $namaJenjang }}</div>
                        @if ($startDate && $endDate)
                            <div>
                                <strong>
                                    Periode {{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($startDate, 'F Y') }}
                                    sampai
                                    {{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($endDate, 'F Y') }}
                                </strong>
                            </div>
                            @else
                            <div>
                                <strong>
                                    Semua Periode
                                </strong>
                            </div>
                        @endif
                    </div>
                    <table id="tabelPendapatan" class="table table-bordered table-hover table-nowrap align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>Nama Rekening</th>
                                @foreach ($bulanIndo as $key => $namaBulan)
                                    <th>{{ $namaBulan }}</th>
                                @endforeach
                                <th>TOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pendapatanPerBulan as $namaRekening => $dataPerBulan)
                                <tr>
                                    <td>{{ $namaRekening }}</td>
                                    @php $totalRekening = 0; @endphp
                                    @foreach ($bulanIndo as $key => $namaBulan)
                                        @php
                                            $jumlah = optional($dataPerBulan[$key] ?? null)->sum('nominal');
                                            $totalRekening += $jumlah;
                                        @endphp
                                        <td>RP{{ number_format($jumlah, 0, ',', '.') }}</td>
                                    @endforeach
                                    <td><strong>RP{{ number_format($totalRekening, 0, ',', '.') }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>   
                        <tfoot>
                            <tr class="fw-semibold bg-secondary-subtle">
                                <th>TOTAL</th>
                                @php $grandTotal = 0; @endphp
                                @foreach ($bulanIndo as $key => $namaBulan)
                                    @php
                                        $totalBulan = $pendapatanPerBulan->reduce(function ($carry, $dataPerBulan) use ($key) {
                                            return $carry + optional($dataPerBulan[$key] ?? null)->sum('nominal');
                                        }, 0);
                                        $grandTotal += $totalBulan;
                                    @endphp
                                    <th>RP{{ number_format($totalBulan, 0, ',', '.') }}</th>
                                @endforeach
                                <th>RP{{ number_format($grandTotal, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>                                                     
                    </table>
                </div>
                
            </div>
        </div>  
    </div>
    <div class="modal fade zoomIn"
        id="ExportLaporan"
        tabindex="-1"
        aria-labelledby="exportRecordLabel"
        aria-hidden="true"
        wire:ignore.self>

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

                {{-- HEADER --}}
                <div class="modal-header border-0 pb-0">

                    <button
                        type="button"
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

                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle">

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

                        <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill mb-3">
                            Konfirmasi Export
                        </span>

                        <h3 class="fw-bold mb-2" id="exportRecordLabel">
                            Export Laporan Pendapatan?
                        </h3>

                        <p class="text-muted mb-0 lh-lg px-lg-4">
                            Data laporan Pendapatan yang diekspor akan mengikuti data
                            pada tabel yang sedang ditampilkan sehingga hasil export
                            sesuai dengan informasi yang Anda lihat saat ini.
                        </p>

                    </div>

                    {{-- INFORMATION --}}
                    <div class="alert alert-light border rounded-4 text-start mt-4 mb-0">

                        <div class="d-flex align-items-start gap-3">

                            <div class="flex-shrink-0">
                                <i class="ri-information-line text-primary fs-20"></i>
                            </div>

                            <div>

                                <h6 class="fw-semibold mb-1">
                                    Informasi
                                </h6>

                                <p class="text-muted mb-0 fs-13">
                                    Pastikan data laporan Pendapatan yang ditampilkan
                                    sudah sesuai sebelum melakukan export Excel.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="modal-footer border-0 pt-0 px-4 pb-4 justify-content-center">

                    <button
                        type="button"
                        class="btn btn-light rounded-pill px-4"
                        data-bs-dismiss="modal">

                        <i class="ri-close-line me-1"></i>
                        Batal

                    </button>

                    <button
                        type="button"
                        class="btn btn-primary rounded-pill px-4"
                        id="konfirmasiExportLaporan"
                        data-bs-dismiss="modal">

                        <i class="ri-download-2-line me-1"></i>
                        Ya, Export

                    </button>

                </div>

            </div>

        </div>

    </div>

    <script>
    document.getElementById('konfirmasiExportLaporan').addEventListener('click', function () {

        alertify.success("Menyiapkan Dokumen");

        setTimeout(function () {

            // Ambil tabel berdasarkan ID (lebih aman daripada querySelector)
            var table = document.getElementById("tabelPendapatan");

            // Konversi tabel menjadi workbook Excel
            var workbook = XLSX.utils.table_to_book(table, {
                sheet: "Pendapatan"
            });

            // Nama file
            let fileName = `Laporan-Pendapatan-Sekolah-{{ date('Y-m-d') }}.xlsx`;

            // Download Excel
            XLSX.writeFile(workbook, fileName);

        }, 1000);

    });
    </script>
</div>