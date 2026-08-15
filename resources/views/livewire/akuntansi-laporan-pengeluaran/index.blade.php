<div class="">
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header">
            <div class="d-flex align-items-center flex-wrap gap-3">
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-sm flex-shrink-0">
                            <div class="avatar-title bg-danger-subtle text-danger rounded-circle fs-20">
                                <i class="ri-wallet-3-line"></i>
                            </div>
                        </div>

                        <div>
                            <h5 class="fw-bold mb-1">
                                Laporan Pengeluaran
                            </h5>

                            <small class="text-muted">
                                Ringkasan pengeluaran sekolah berdasarkan periode yang dipilih.
                            </small>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2 flex-wrap">
                    {{-- EXPORT --}}
                    <button type="button" data-bs-toggle="modal"
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

                    <div class="vr d-none d-lg-block"></div>

                    {{-- PERIODE --}}
                    <div class="d-flex align-items-center gap-2">
                        <input type="date" class="form-control"
                            wire:model="startDate" title="Tanggal Mulai">

                        <span class="text-muted">
                            –
                        </span>

                        <input type="date" class="form-control"
                            wire:model="endDate" title="Tanggal Akhir">

                        <button type="button" class="btn btn-soft-secondary"
                            wire:click="resetTanggal" title="Reset Tanggal">

                            <i class="ri-refresh-line"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="live-preview">
                <div class="table-responsive">
                    <div class="text-center py-3">
                        {{-- JUDUL --}}
                        <h4 class="fw-bold mb-1 text-dark">
                            Laporan Pengeluaran Sekolah
                        </h4>

                        {{-- UNIT --}}
                        <div class="text-muted fs-13 mb-2">
                            Yayasan Drul Khukama
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
                    <table id="tabelPengeluaran" class="table table-bordered table-hover table-nowrap align-middle" style="width:100%">
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
                            @foreach ($bebanPerBulan as $namaRekening => $dataPerBulan)
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
                                        $totalBulan = $bebanPerBulan->reduce(function ($carry, $dataPerBulan) use ($key) {
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
                            Export Laporan Pengeluaran?
                        </h3>

                        <p class="text-muted mb-0 lh-lg px-lg-4">
                            Data laporan Pengeluaran yang diekspor akan mengikuti data
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
                                    Pastikan data laporan Pengeluaran yang ditampilkan
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
            var table = document.getElementById("tabelPengeluaran");

            // Konversi tabel menjadi workbook Excel
            var workbook = XLSX.utils.table_to_book(table, {
                sheet: "Pengeluaran"
            });

            // Nama file
            let fileName = `Laporan-Pengeluaran-Sekolah-{{ date('Y-m-d') }}.xlsx`;

            // Download Excel
            XLSX.writeFile(workbook, fileName);

        }, 1000);

    });
    </script>
</div>