{{-- If your happiness depends on money, you will never be happy with yourself. --}}
<div class="card border-0 shadow-sm rounded-4 overflow-hidden sticky-side-div">
    <div class="card-header">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">
            {{-- TITLE --}}
            <div>
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-calendar-event-line">
                            </i>
                        </div>
                    </div>
    
                    <div>
                        <h5 class="fw-bold mb-1">
                            Overview
                        </h5>
                        <small>
                            Rangkuman Pembayaran Siswa
                        </small>
                    </div>
                </div>
            </div>
    
            {{-- ACTION --}}
            @if ($selectedJenjang && $selectedTahunAjar)
            <div class="d-flex gap-2 flex-wrap">
                <button data-bs-toggle="modal" data-bs-target="#ExportOverview" class="btn rounded-pill px-4 btn-success"><i class="ri-file-excel-2-line me-1 align-bottom"></i> Export</button>
            </div>
            @endif
        </div>
    </div>
    <div class="card-body">
        <div class="row g-2 align-items-center mb-3">
            <!-- Label di sisi kiri -->
            <div class="col-xxl-12">
                <label class="form-label fw-semibold">Periode</label>
                <div class="d-flex align-items-center gap-2">
                    <input type="date" class="form-control" wire:model="startDate">
                    <span class="text-muted">–</span>
                    <input type="date" class="form-control" wire:model="endDate">
                    <button type="button" class="btn btn-soft-secondary" wire:click="resetTanggal" title="Reset Tanggal">
                        <i class="ri-refresh-line"></i>
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
                                <td class="text-end fs-12 fw-medium">Rp{{ number_format($month['total'], 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-3 text-muted">
                                    <em>Data periode tidak ditemukan</em>
                                </td>
                            </tr>
                        @endforelse
                        <tr class="fw-semibold">
                            <td></td>
                            <td>TOTAL</td>
                            <td class="text-end fs-12">Rp{{ number_format($totalMonths, 0, ',', '.') }}</td>
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
                                <td class="text-end fs-12 fw-medium">Rp{{ number_format($item['total'], 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-3 text-muted">
                                    <em>Data metode tidak ditemukan</em>
                                </td>
                            </tr>
                        @endforelse
                        <tr class="fw-semibold">
                            <td></td>
                            <td>TOTAL</td>
                            <td class="text-end fs-12">Rp{{ number_format($totalMethods, 0, ',', '.') }}</td>
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
                                <td class="text-end fs-12 fw-medium">Rp{{ number_format($class['total'], 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-3 text-muted">
                                    <em>Data kelas tidak ditemukan</em>
                                </td>
                            </tr>
                        @endforelse
                        <tr class="fw-semibold">
                            <td></td>
                            <td>TOTAL</td>
                            <td class="text-end fs-12">Rp{{ number_format($totalClasses, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>

                </table>
            </div>

            @endif
        </div>

        {{-- end data --}}
    </div>
</div>
 {{-- MODAL EXPORT OVERVIEW --}}
<div class="modal fade zoomIn" id="ExportOverview" tabindex="-1"
    aria-labelledby="exportOverviewLabel" aria-hidden="true">
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

                    <h3 class="fw-bold mb-2" id="exportOverviewLabel">
                        Export Overview Pembayaran Siswa?
                    </h3>

                    <p class="text-muted mb-0 lh-lg px-lg-4">
                        Data Overview Pembayaran Tagihan Siswa yang diekspor
                        akan mengikuti data pada tabel yang sedang ditampilkan,
                        sehingga hasil export sesuai dengan data yang Anda lihat
                        saat ini.
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
                                Pastikan data Overview Pembayaran Tagihan Siswa
                                yang ditampilkan sudah sesuai sebelum melakukan
                                export laporan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FOOTER --}}
            <div class="modal-footer border-0 pt-0 px-4 pb-4 justify-content-center">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>
                    Batal

                </button>
                <button type="button"
                    class="btn btn-primary rounded-pill px-4"
                    id="konfirmasiExportOverview"
                    data-bs-dismiss="modal">

                    <i class="ri-download-2-line me-1"></i>
                    Ya, Export
                </button>
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
