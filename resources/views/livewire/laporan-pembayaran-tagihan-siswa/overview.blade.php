{{-- If your happiness depends on money, you will never be happy with yourself. --}}
<div class="card mb-1">
    <div class="card-header border-0 pb-0">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1">Rangkuman Pembayaran Siswa</h5>
            @if ($selectedJenjang && $selectedTahunAjar)
            <div class="flex-shrink-0">
                <div class="d-flex gap-2 flex-wrap">
                    {{-- <button wire:click="cetakOverviewPembayaran" class="btn btn-danger d-inline-flex align-items-center gap-1">
                        <i class="ri-printer-line align-bottom"></i>
                        <span>Cetak</span>
                    </button> --}}
                    <button data-bs-toggle="modal" data-bs-target="#ExportOverviewExcel" class="btn btn-soft-success"><i class="ri-file-excel-2-line pb-0"></i> Export</button>
                    {{-- <button data-bs-toggle="modal" data-bs-target="#ExportOverviewPembayaran" wire:click.prevent="showExportOverviewPembayaran"  class="btn btn-soft-success"><i class="ri-file-excel-2-line pb-0"></i> Export</button> --}}
                </div>
            </div>
            @endif
        </div>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <div class="row g-2 align-items-center">
                <!-- Label di sisi kiri -->
                <div class="col-auto">
                    <label for="startDate" class="form-label text-muted text-uppercase fs-12 fw-medium mb-0">Periode </label>
                </div>
                <!-- Input tanggal di sisi kanan -->
                <div class="col">
                    <div class="row g-2 align-items-center">
                        <div class="col-lg">
                            <input type="date" class="form-control" wire:model="startDate" placeholder="0">
                        </div>
                        <div class="col-lg">
                            <input type="date" class="form-control" wire:model="endDate" placeholder="0">
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
        {{-- DATA --}}
        <div class="live-preview">
            @if (!$selectedJenjang || !$selectedTahunAjar)
                <div class="text-center py-4">
                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                        colors="primary:#405189,secondary:#08a88a"
                        style="width:75px;height:75px">
                    </lord-icon>
                    <h5 class="mt-2">Silakan Pilih Jenjang dan Tahun Ajar</h5>
                    <p class="text-muted mb-0">Untuk melihat data kelas, harap pilih Jenjang dan Tahun Ajar terlebih dahulu.</p>
                </div>
            @else
            @php
                $totalMonths  = array_sum(array_column($months, 'total'));
                $totalMethods = array_sum(array_column($methods, 'total'));
                $totalClasses = array_sum(array_column($classes, 'total'));
            @endphp

            <div class="table-responsive">
                <table id="tabelOverview" class="table table-hover nowrap align-middle" style="width:100%">
                    
                    <!-- ================= SECTION PERIODE ================= -->
                    <tbody>
                        <tr>
                            <th colspan="3" class="text-uppercase bg-dark text-white text-center">Overview Periode</th>
                        </tr>
                        <tr class="table-light">
                            <th class="text-uppercase">No</th>
                            <th class="text-uppercase">Periode</th>
                            <th class="text-uppercase text-end">Pembayaran</th>
                        </tr>
                        @forelse($months as $month)
                            <tr>
                                <td style="width: 50px">{{ $loop->iteration }}.</td>
                                <td class="text-uppercase">{{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($month['bulan'], 'F Y') }}</td>
                                <td class="text-end fs-14 text-success">RP{{ number_format($month['total'], 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-3 text-muted">
                                    <em>Data periode tidak ditemukan</em>
                                </td>
                            </tr>
                        @endforelse
                        <tr class="fw-bold">
                            <td></td>
                            <td>TOTAL</td>
                            <td class="text-end fs-14 text-success">RP{{ number_format($totalMonths, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>

                    <!-- ================= SECTION METODE ================= -->
                    <tbody>
                        <tr>
                            <th colspan="3" class="text-uppercase bg-dark text-white text-center">Overview Metode Pembayaran</th>
                        </tr>
                        <tr class="table-light">
                            <th class="text-uppercase">No</th>
                            <th class="text-uppercase">Metode</th>
                            <th class="text-uppercase text-end">Pembayaran</th>
                        </tr>
                        @forelse($methods as $item)
                            <tr>
                                <td style="width: 50px">{{ $loop->iteration }}.</td>
                                <td class="text-uppercase">{{ $item['metode'] }}</td>
                                <td class="text-end fs-14 text-success">RP{{ number_format($item['total'], 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-3 text-muted">
                                    <em>Data metode tidak ditemukan</em>
                                </td>
                            </tr>
                        @endforelse
                        <tr class="fw-bold">
                            <td></td>
                            <td>TOTAL</td>
                            <td class="text-end fs-14 text-success">RP{{ number_format($totalMethods, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>

                    <!-- ================= SECTION KELAS ================= -->
                    <tbody>
                        <tr>
                            <th colspan="3" class="text-uppercase bg-dark text-white text-center">Overview Pembayaran Kelas</th>
                        </tr>
                        <tr class="table-light">
                            <th class="text-uppercase">No</th>
                            <th class="text-uppercase">Kelas</th>
                            <th class="text-uppercase text-end">Pembayaran</th>
                        </tr>
                        @forelse($classes as $class)
                            <tr>
                                <td style="width: 50px">{{ $loop->iteration }}.</td>
                                <td class="text-uppercase">{{ $class['nama_kelas'] }}</td>
                                <td class="text-end fs-14 text-success">RP{{ number_format($class['total'], 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-3 text-muted">
                                    <em>Data kelas tidak ditemukan</em>
                                </td>
                            </tr>
                        @endforelse
                        <tr class="fw-bold">
                            <td></td>
                            <td>TOTAL</td>
                            <td class="text-end text-success fs-14">RP{{ number_format($totalClasses, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>

                </table>
            </div>

            @endif
        </div>

        {{-- end data --}}
    </div>
    <div class="modal fade zoomIn" id="ExportOverviewExcel" tabindex="-1" aria-labelledby="exportRecordLabel" aria-hidden="true" wire:ignore.self>
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
                            Apakah Anda yakin ingin mengekspor Overview Pembayaran Tagihan Siswa? Data yang diekspor akan sesuai dengan tabel yang ditampilkan.
                        </p>
                        <div class="hstack gap-2 justify-content-center remove">
                            <button class="btn btn-link link-success fw-medium text-decoration-none shadow-none" data-bs-dismiss="modal">
                                <i class="ri-close-line me-1 align-middle"></i> Batal
                            </button>
                            <button class="btn btn-primary" id="konfirmasiExportOverview" data-bs-dismiss="modal">Ya, Export!</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('konfirmasiExportOverview').addEventListener('click', function () {
            alertify.success("Menyiapkan Dokumen");
            // Tambahkan delay 1 detik
            setTimeout(function () {
                // Ambil elemen tabel berdasarkan ID
                var table = document.getElementById("tabelOverview"); // ganti sesuai kebutuhan
        
                // Konversi tabel ke format Excel
                var workbook = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
                
                // Simpan file Excel
                XLSX.writeFile(workbook, "Overview-Pembayaran-Siswa.xlsx");
            }, 1000); // 1000 ms = 1 detik
        });
    </script>
</div>
