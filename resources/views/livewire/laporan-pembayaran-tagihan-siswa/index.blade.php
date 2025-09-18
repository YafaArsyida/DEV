<div class="card">
    <div class="card-header border-0 pb-0">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1">Laporan Pembayaran Tagihan Siswa</h5>
            @if ($selectedJenjang && $selectedTahunAjar)
            <div class="flex-shrink-0">
                <div class="d-flex gap-2 flex-wrap">
                    <button wire:click="cetakLaporanPembayaran" class="btn btn-danger d-inline-flex align-items-center gap-1">
                        <i class="ri-printer-line align-bottom"></i>
                        <span>Cetak Laporan</span>
                    </button>
                    <button data-bs-toggle="modal" data-bs-target="#ExportLaporanExcel" class="btn btn-soft-success"><i class="ri-file-excel-2-line pb-0"></i> Export</button>
                    {{-- <button data-bs-toggle="modal" data-bs-target="#ExportPembayaranSiswa" wire:click.prevent="showExportPembayaranSiswa"  class="btn btn-soft-success"><i class="ri-file-excel-2-line me-1 align-bottom"></i> Export</button> --}}
                    <button type="button" class="btn btn-info" data-bs-toggle="offcanvas" data-bs-target="#filterPembayaran" aria-controls="filterTabungan"><i class="ri-filter-3-line align-bottom me-1"></i> Fliters</button>
                </div>
            </div>
            @endif
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3 mb-3">
            <div class="col-xxl-12 col-sm-12">
                <div class="search-box">
                    <input type="text" class="form-control search" wire:model.debounce.300ms="search" placeholder="cari nama, deskripsi atau lainnya...">
                    <i class="ri-search-line search-icon"></i>
                </div>
            </div>
        </div>
        <!--end row-->
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
                <table id="tabelPembayaran" class="table table-hover nowrap align-middle" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th class="text-uppercase">No</th>
                            <th class="text-uppercase">Tanggal</th>
                            <th class="text-uppercase">Siswa</th>
                            <th class="text-uppercase">Kelas</th>
                            <th class="text-uppercase">Tagihan</th>
                            {{-- <th class="text-uppercase">Katgeori</th> --}}
                            <th class="text-uppercase">Petugas</th>
                            <th class="text-uppercase">Metode</th>
                            <th class="text-uppercase text-end">Dibayarkan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($laporans as $index => $item)
                            <tr>
                                <td>{{ $laporans->firstItem() + $index }}.</td>
                                <td class="text-uppercase text-start">
                                    {{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($item->ms_transaksi_tagihan_siswa->tanggal_transaksi, 'd F Y') }}
                                </td>
                                <td style="white-space: nowrap;">{{ $item->ms_transaksi_tagihan_siswa->ms_penempatan_siswa->ms_siswa->nama_siswa }}</td>
                                <td>{{ $item->ms_transaksi_tagihan_siswa->ms_penempatan_siswa->ms_kelas->nama_kelas ?? '-' }}</td>
                                <td>{{ $item->ms_tagihan_siswa->ms_jenis_tagihan_siswa->nama_jenis_tagihan_siswa }}</td>
                                {{-- <td>{{ $item->ms_tagihan_siswa->ms_jenis_tagihan_siswa->ms_kategori_tagihan_siswa->nama_kategori_tagihan_siswa }}</td> --}}
                                <td style="white-space: nowrap;">{{ $item->ms_transaksi_tagihan_siswa->ms_pengguna->nama ?? '-' }}</td>
                                <td class="">{{ $item->ms_transaksi_tagihan_siswa->metode_pembayaran }}</td>
                                <td class="text-end">
                                    <span class="fs-14 fw-medium text-success">
                                        Rp{{ number_format($item->jumlah_bayar, 0, ',', '.') }}
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
                        <tr class="">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-start"><strong>TOTAL</strong></td>
                            <td class="text-end">
                                <span class="fs-14 fw-medium text-success">Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                {{ $laporans->links() }}
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
