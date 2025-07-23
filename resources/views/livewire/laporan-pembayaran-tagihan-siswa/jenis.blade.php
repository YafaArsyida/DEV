{{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}
<div class="card mt-1">
    <div class="card-header border-0 pb-0">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1">Laporan Jenis Tagihan</h5>
            @if ($selectedJenjang && $selectedTahunAjar)
            <div class="flex-shrink-0">
                <div class="d-flex gap-2 flex-wrap">
                    <button data-bs-toggle="modal" data-bs-target="#ExportPembayaranJenis" wire:click.prevent="showExportPembayaranJenis"  class="btn btn-soft-success"><i class="ri-file-excel-2-line me-1 align-bottom"></i> Export</button>
                </div>
            </div>
            @endif
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3 mb-3">
            <div class="col-xxl-8 col-sm-12">
                <div class="search-box">
                    <input type="text" class="form-control search" wire:model.debounce.300ms="search" placeholder="cari nama, deskripsi atau lainnya...">
                    <i class="ri-search-line search-icon"></i>
                </div>
            </div>
            <div class="col-xxl-4 col-sm-6"> 
                <select wire:model="selectedKategoriTagihan" style="cursor: pointer" class="form-select" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Kelas">
                    <option value="">Semua Kategori</option>
                    @foreach ($select_kategori as $kategori)
                        <option value="{{ $kategori->ms_kategori_tagihan_siswa_id_siswa }}">{{ $kategori->nama_kategori_tagihan_siswa_siswa }}</option>
                    @endforeach
                </select>
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
                            <th style="white-space: nowrap;" class="text-uppercase">Jenis Tagihan</th>
                            <th class="text-uppercase text-start">Kategori</th>
                            <th class="text-uppercase text-center">Estimasi</th>
                            <th class="text-uppercase text-center">Dibayarkan</th>
                            <th class="text-uppercase text-center">Kekurangan</th>
                            <th class="text-uppercase text-center">Presentase</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($laporans as $item)
                        <tr>
                            <td>{{ $loop->iteration }}.</td>
                            <td style="white-space: nowrap;">{{ $item['nama_jenis_tagihan_siswa'] }}</td>
                            <td style="white-space: nowrap;">{{ $item['kategori_tagihan_siswa'] }}</td>
                            <td class="text-center">
                                <span class="fs-14 text-info">
                                    Rp{{ number_format($item['estimasi'], 0, ',', '.') }}</span>
                            </td>
                            <td class="text-center">
                                <span class="fs-14 text-success">
                                    Rp{{ number_format($item['dibayarkan'], 0, ',', '.') }}</span>
                            </td>
                            <td class="text-center">
                                <span class="fs-14 text-danger">
                                    Rp{{ number_format($item['kekurangan'], 0, ',', '.') }}</span>
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
                            <td colspan="6">
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


