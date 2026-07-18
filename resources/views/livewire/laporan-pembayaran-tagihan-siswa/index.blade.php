<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">
            {{-- TITLE --}}
            <div>
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-money-dollar-circle-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1">
                            Riwayat Pembayaran Siswa
                        </h5>
                        {{-- <small>
                            Kelola dan cetak riwayat pembayaran siswa
                        </small> --}}
                    </div>
                </div>
            </div>

            {{-- ACTION --}}
            @if ($selectedJenjang && $selectedTahunAjar)
                <div class="d-flex gap-2 flex-wrap">
                    <button wire:click="cetakLaporanPembayaran"
                        class="btn rounded-pill px-4 btn-danger d-inline-flex align-items-center gap-1">
                        <i class="ri-printer-line"></i>
                        Cetak Laporan
                    </button>

                    <button data-bs-toggle="modal"
                        data-bs-target="#ExportLaporanExcel"
                        class="btn rounded-pill px-4 btn-soft-success">
                        <i class="ri-file-excel-2-line me-1 align-bottom"></i>
                        Export
                    </button>

                    <button type="button"
                        class="btn rounded-pill px-4 btn-info"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#filterPembayaran"
                        aria-controls="filterPembayaran">
                        <i class="ri-filter-3-line me-1 align-bottom"></i>
                        Filter
                    </button>
                </div>
            @endif

        </div>
    </div>
    <div class="card-body">
        <div class="row g-3 align-items-end mb-3">
            <!-- Input Pencarian -->
            <div class="col-xxl-6 col-sm-6">
                <label for="searchData" class="form-label">Pencarian</label>
                <div class="search-box">
                    <input type="text" id="searchData" class="form-control search" wire:model.debounce.300ms="search"
                        placeholder="Cari nama, deskripsi, atau lainnya...">
                    <i class="ri-search-line search-icon"></i>
                </div>
            </div>

            <!-- Filter Periode -->
            <div class="col-xxl-6 col-sm-6">
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
            <!-- Jika Jenjang atau Tahun Ajar belum dipilih -->
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
            <div class="table-responsive">
                <table id="tabelPembayaran" class="table table-hover table-nowrap align-middle" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th class="text-uppercase">No</th>
                            <th class="text-uppercase">Tanggal</th>
                            <th class="text-uppercase">Siswa</th>
                            <th class="text-uppercase">Kelas</th>
                            <th class="text-uppercase">Tagihan</th>
                            {{-- <th class="text-uppercase">Katgeori</th> --}}
                            <th class="text-uppercase text-center">Petugas</th>
                            <th class="text-uppercase text-center">Metode</th>
                            <th class="text-uppercase text-start">Dibayarkan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($laporans as $index => $item)
                            <tr>
                                <td>{{ $laporans->firstItem() + $index }}.</td>
                                <td class="text-uppercase text-start">
                                    {{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($item->ms_transaksi_tagihan_siswa->tanggal_transaksi, 'd F Y') }}
                                </td>
                                <td>{{ $item->ms_transaksi_tagihan_siswa->ms_penempatan_siswa->ms_siswa->nama_siswa }}</td>
                                <td>{{ $item->ms_transaksi_tagihan_siswa->ms_penempatan_siswa->ms_kelas->nama_kelas ?? '-' }}</td>
                                <td>{{ $item->ms_tagihan_siswa->ms_jenis_tagihan_siswa->nama_jenis_tagihan_siswa }}</td>
                                {{-- <td>{{ $item->ms_tagihan_siswa->ms_jenis_tagihan_siswa->ms_kategori_tagihan_siswa->nama_kategori_tagihan_siswa }}</td> --}}
                                <td class="text-center">{{ $item->ms_transaksi_tagihan_siswa->ms_pengguna->nama ?? '-' }}</td>
                                <td class="text-center">{{ $item->ms_transaksi_tagihan_siswa->metode_pembayaran }}</td>
                                <td class="text-start">
                                    <span class="fs-12 fw-medium text-success">
                                        RP{{ number_format($item->jumlah_bayar, 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9">
                                    <div class="noresult text-center py-3">
                                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                            colors="primary:#405189,secondary:#08a88a"
                                            style="width:75px;height:75px">
                                        </lord-icon>
                                        <h5 class="mt-2">Maaf, Tidak Ada Data yang Ditemukan</h5>
                                        <p class="text-muted mb-0">Kami telah mencari keseluruhan data, namun tidak ditemukan hasil yang sesuai.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-start"><strong>TOTAL</strong></td>
                            <td class="text-start">
                                <span class="fs-12 text-success">RP{{ number_format($totalPembayaran, 0, ',', '.') }}</span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="text-muted fs-13">
                            Menampilkan
                            <span class="fw-semibold">
                                {{ $laporans->firstItem() ?? 0 }}
                            </span>
                            -
                            <span class="fw-semibold">
                                {{ $laporans->lastItem() ?? 0 }}
                            </span>
                            dari
                            <span class="fw-semibold">
                                {{ $laporans->total() }}
                            </span>
                            data pembayaran
                        </div>
                        <div>
                            {{ $laporans->links() }}
                        </div>
                    </div>
                </div>
            </div>

            @endif
        </div>
        {{-- end data --}}
    </div>
    {{-- MODAL --}}
    <div class="modal fade zoomIn" id="ExportLaporanExcel" tabindex="-1" aria-labelledby="exportRecordLabel" aria-hidden="true" wire:ignore.self>
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
                            Apakah Anda yakin ingin mengekspor laporan Pembayaran Tagihan Siswa? Data yang diekspor akan sesuai dengan tabel yang ditampilkan.
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
                var table = document.getElementById("tabelPembayaran"); // ganti sesuai kebutuhan
        
                // Konversi tabel ke format Excel
                var workbook = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
                
                // Simpan file Excel
                XLSX.writeFile(workbook, "Laporan-Pembayaran-Siswa.xlsx");
            }, 1000); // 1000 ms = 1 detik
        });
    </script>
</div>
