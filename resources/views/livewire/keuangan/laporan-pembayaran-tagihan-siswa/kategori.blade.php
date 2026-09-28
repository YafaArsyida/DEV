{{-- Success is as dangerous as failure. --}}
<div class="card mb-1">
    <div class="card-header border-0 pb-0">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1">Laporan Kategori Tagihan</h5>
            @if ($selectedJenjang && $selectedTahunAjar)
            <div class="flex-shrink-0">
                <div class="d-flex gap-2 flex-wrap">
                    <button data-bs-toggle="modal" data-bs-target="#ExportPembayaranKategori" wire:click.prevent="showExportPembayaranKategori"  class="btn btn-soft-success"><i class="ri-file-excel-2-line me-1 align-bottom"></i> Export</button>
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
                            <th class="text-uppercase">Kategori</th>
                            <th class="text-uppercase text-center">Estimasi</th>
                            <th class="text-uppercase text-center">Dibayarkan</th>
                            <th class="text-uppercase text-center">Kekurangan</th>
                            <th class="text-uppercase text-center">Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($laporans as $item)
                        <tr>
                            <td>{{ $loop->iteration }}.</td>
                            <td style="white-space: nowrap;">{{ $item['nama_kategori_tagihan_siswa'] }}</td>
                            <td class="text-center">
                                <span class="fs-14 text-info">
                                    Rp{{ number_format($item['estimasi'], 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="fs-14 text-success">
                                    Rp{{ number_format($item['dibayarkan'], 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="fs-14 text-danger">
                                    Rp{{ number_format($item['kekurangan'], 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="fs-14 badge bg-success-subtle text-success">
                                    @if ($item['estimasi'] > 0)
                                        {{ number_format(($item['dibayarkan'] / $item['estimasi']) * 100, 2) }}% 
                                    @else
                                        -
                                    @endif
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">
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
                        <tr>
                            <td></td>
                            <td class="text-start fw-bold">TOTAL</td>
                            <td class="text-center">
                                <span class="fs-14 text-info">
                                    Rp{{ number_format($totals['totalEstimasi'], 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="fs-14 text-success">
                                    Rp{{ number_format($totals['totalDibayarkan'], 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="fs-14 text-danger">
                                    Rp{{ number_format($totals['totalKekurangan'], 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="fs-14 badge bg-success-subtle text-success">
                                    {{ $totals['totalPresentase'] }}%
                                </span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @endif
        </div>
        {{-- end data --}}
    </div>
</div>

