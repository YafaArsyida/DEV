<div class="row justify-content-center">
    <div class="col-xxl-12">
        <div class="card">
            <div class="card-header border-0 pb-0">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="card-title mb-0">
                            Laporan Pengeluaran
                        </h5>
                        <p class="mb-0">kjsbhdjjsjjs</p>       
                    </div>
                    <div class="flex-shrink-0">
                        <div class="d-flex gap-2 flex-wrap">
                            <button data-bs-toggle="modal" data-bs-target="#ExportLaporan" class="btn btn-soft-success"><i class="ri-file-excel-2-line pb-0"></i> Export</button>
                            @if ($selectedJenjang && $selectedTahunAjar)
                            <div class="flex-shrink-0">
                                <div class="d-flex gap-2 flex-wrap">
                                    <button wire:click="cetakLaporan" class="btn btn-danger d-inline-flex align-items-center gap-1">
                                        <i class="ri-printer-line align-bottom"></i>
                                        <span>Cetak Laporan</span>
                                    </button>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div><!-- end card header -->
            <div class="card-body">
                <div class="row g-3 mb-3">
                    {{-- <div class="col-xxl-4 col-sm-6">
                        <div class="search-box">
                            <input type="text" class="form-control search" wire:model.debounce.300ms="search" placeholder="cari nama, deskripsi atau lainnya...">
                            <i class="ri-search-line search-icon"></i>
                        </div>
                    </div> --}}
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
                                        <input type="date" id="startDate" class="form-control" wire:model="startDate" placeholder="0">
                                    </div>
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
                        <div class="text-center my-3">
                            <h4 class="mb-0">Laporan Pengeluaran</h4>
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
                        <table class="table table-bordered table-hover table-nowrap align-middle" style="width:100%">
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
                                @foreach ($bebanPerBulan  as $namaRekening => $dataPerBulan)
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
                                            $totalBulan = $bebanPerBulan ->reduce(function ($carry, $dataPerBulan) use ($key) {
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
        <div class="modal fade zoomIn" id="ExportLaporan" tabindex="-1" aria-labelledby="exportRecordLabel" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-5 text-center">
                        <lord-icon src="https://cdn.lordicon.com/fjvfsqea.json" trigger="loop" colors="primary:#405189,secondary:#f06548" style="width:90px;height:90px"></lord-icon>
                        <div class="mt-4 text-center">
                            <h4 class="fs-semibold">Konfirmasi Export</h4>
                            <p class="text-muted fs-14 mb-4 pt-1">
                                Apakah Anda yakin ingin mengekspor laporan Rekapitulasi? Data yang diekspor akan sesuai dengan tabel yang ditampilkan.
                            </p>
                            <div class="hstack gap-2 justify-content-center remove">
                                <button class="btn btn-link link-success fw-medium text-decoration-none shadow-none" data-bs-dismiss="modal">
                                    <i class="ri-close-line me-1 align-middle"></i> Batal
                                </button>
                                <button class="btn btn-primary" id="konfirmasiExportLaporan" data-bs-dismiss="modal">Ya, Export!</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.getElementById('konfirmasiExportLaporan').addEventListener('click', function () {
                alertify.success("Menyiapkan Dokumen");
                // Tambahkan delay 1 detik
                setTimeout(function () {
                    // Ambil elemen tabel berdasarkan ID
                    var table = document.querySelector("table");
                    
                    // Konversi tabel ke format Excel
                    var workbook = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
                    
                    // Simpan file Excel
                    XLSX.writeFile(workbook, "Laporan-Laba-Rugi.xlsx");
                }, 1000); // 1000 ms = 1 detik
            });
        </script>
    </div>
</div>