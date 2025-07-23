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
                    <button type="button" class="btn btn-info" data-bs-toggle="offcanvas" data-bs-target="#filterPembayaran" aria-controls="filterTabungan"><i class="ri-filter-3-line align-bottom me-1"></i> Fliters</button>
                    <button data-bs-toggle="modal" data-bs-target="#ExportPembayaranSiswa" wire:click.prevent="showExportPembayaranSiswa"  class="btn btn-soft-success"><i class="ri-file-excel-2-line me-1 align-bottom"></i> Export</button>
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
                <table class="table table-hover nowrap align-middle" style="width:100%">
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
</div>
