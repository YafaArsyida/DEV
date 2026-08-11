<div class="">
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header border-0 pb-0">
            <div class="d-flex align-items-center flex-wrap gap-3">

                {{-- Judul --}}
                <h5 class="card-title mb-0 flex-grow-1">
                    Laporan Jurnal Keuangan
                </h5>

                {{-- Tombol Export & Cetak --}}
                <div class="d-flex gap-2 flex-wrap">

                    {{-- Export --}}
                    <button
                        data-bs-toggle="modal"
                        data-bs-target="#ExportLaporan"
                        class="btn rounded-pill px-4 btn-success">
                        <i class="ri-file-excel-2-line pb-0"></i>
                        Export
                    </button>

                    {{-- Cetak --}}
                    @if ($selectedJenjang && $selectedTahunAjar)
                        <button
                            wire:click="cetakLaporan"
                            class="btn rounded-pill px-4 btn-danger d-inline-flex align-items-center gap-1">
                            <i class="ri-printer-line align-bottom"></i>
                            <span>Cetak Laporan</span>
                        </button>
                    @endif

                </div>

            </div>
        </div><!-- end card header -->
        <div class="card-body">
            <div class="row g-3 align-items-end mb-3">
                <!-- Input Pencarian -->
                <div class="col-xxl-8 col-sm-6">
                    <label for="searchEkstrakurikuler" class="form-label">Pencarian</label>
                    <div class="search-box">
                        <input type="text" id="searchEkstrakurikuler" class="form-control search" wire:model.debounce.300ms="search"
                            placeholder="Cari nama, deskripsi...">
                        <i class="ri-search-line search-icon"></i>
                    </div>
                </div>

                <!-- Filter Periode -->
                <div class="col-xxl-4">
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
                <div class="table-responsive">
                {{-- <div class="table-responsive" style="max-height: 1000px;" data-simplebar> --}}
                    @php
                        $saldo = 0;
                    @endphp
                    <table id="tabelJurnal" class="table table-bordered table-hover table-nowrap align-middle">
                        <thead class="table-light">
                            <tr class="text-uppercase">
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Deskripsi Transaksi</th>
                                <th class="text-center">Petugas</th>
                                <th class="text-uppercase">Akun Debit</th>
                                <th class="text-uppercase">Akun Kredit</th>
                                <th class="text-uppercase">Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transaksiJurnal as $jurnal)
                                @php
                                    $debitDetails = $jurnal->akuntansi_jurnal_detail->where('posisi', 'debit');
                                    $kreditDetails = $jurnal->akuntansi_jurnal_detail->where('posisi', 'kredit');

                                    $totalNominal = $debitDetails->sum('nominal');
                                @endphp

                                <tr>
                                    <td class="text-center">
                                        {{ $loop->iteration + ($transaksiJurnal->firstItem() - 1) }}
                                    </td>

                                    <td>
                                        {{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($jurnal->tanggal_transaksi, 'd F Y') }}
                                    </td>

                                    <td>
                                        {{ $jurnal->deskripsi }}
                                    </td>

                                    <td class="text-center">
                                        {{ $jurnal->ms_pengguna->nama ?? '-' }}
                                    </td>

                                    {{-- AKUN DEBIT --}}
                                    <td>
                                        @forelse($debitDetails as $detail)
                                            {{ $detail->kode_rekening }} - {{ $detail->akuntansi_rekening->nama_rekening ?? '-' }}
                                        @empty
                                            -
                                        @endforelse
                                    </td>

                                    {{-- AKUN KREDIT --}}
                                    <td>
                                        @forelse($kreditDetails as $detail)
                                            {{ $detail->kode_rekening }} - {{ $detail->akuntansi_rekening->nama_rekening ?? '-' }}
                                        @empty
                                            -
                                        @endforelse
                                    </td>

                                    {{-- NOMINAL --}}
                                    <td class="fs-12 fw-medium">
                                        <strong>
                                            Rp{{ number_format($totalNominal, 0, ',', '.') }}
                                        </strong>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        Tidak ada data jurnal.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="text-muted fs-13">
                                Menampilkan
                                <span class="fw-semibold">
                                    {{ $transaksiJurnal->firstItem() ?? 0 }}
                                </span>
                                -
                                <span class="fw-semibold">
                                    {{ $transaksiJurnal->lastItem() ?? 0 }}
                                </span>
                                dari
                                <span class="fw-semibold">
                                    {{ $transaksiJurnal->total() }}
                                </span>
                                data
                            </div>

                            <div>
                                {{ $transaksiJurnal->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>  
    </div>
    <div class="modal fade zoomIn" id="ExportLaporan" tabindex="-1" aria-labelledby="exportRecordLabel" aria-hidden="true" wire:ignore.self>
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
                            Export Laporan Jurnal Transaksi?
                        </h3>

                        <p class="text-muted mb-0 lh-lg px-lg-4">
                            Data laporan Jurnal Transaksi yang diekspor akan mengikuti
                            data pada tabel yang sedang ditampilkan sehingga hasil export
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
                                    Pastikan data Jurnal Transaksi yang ditampilkan
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
            // Tambahkan delay 1 detik
            setTimeout(function () {
                // Ambil elemen tabel berdasarkan ID
                var table = document.getElementById("tabelJurnal");
                
                // Konversi tabel ke format Excel
                var workbook = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
                
                // Simpan file Excel
                XLSX.writeFile(workbook, "Laporan-Jurnal-Umum.xlsx");
            }, 1000); // 1000 ms = 1 detik
        });
    </script>
</div>