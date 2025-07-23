<div class="card">
    <div class="card-header border-0 pb-0">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1">Tagihan Siswa</h5>
            @if ($selectedJenjang && $selectedTahunAjar)
            <div class="flex-shrink-0">
                <div class="d-flex gap-2 flex-wrap">
                    <button wire:click="cetakLaporanTagihan" class="btn btn-danger d-inline-flex align-items-center gap-1">
                        <i class="ri-printer-line align-bottom"></i>
                        <span>Cetak Laporan</span>
                    </button>
                    <button data-bs-toggle="modal" id="create-btn" data-bs-target="#ModalAddTagihan" wire:click.prevent="$emit('showCreateTagihan', {{ $selectedJenjang }}, {{ $selectedTahunAjar }})" class="btn btn-primary"><i class="ri-play-list-add-line"></i> Tagihan Baru</button>
                </div>
            </div>
            @endif
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3 mb-3">
            <div class="col-xxl-2 col-sm-6"> 
                <select wire:model="selectedKelas" style="cursor: pointer" class="form-select" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Kelas">
                    <option value="">Semua Kelas</option>
                    @foreach ($select_kelas as $item)    
                    <option value="{{ $item->ms_kelas_id }}">{{ $item->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-xxl-10 col-sm-6">
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
                {{-- <div class="table-responsive" style="max-height: 1000px;" data-simplebar> --}}
                <table class="table table-hover nowrap align-middle" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th class="text-uppercase" style="width: 50px;">NO</th>
                            <th class="text-uppercase">Siswa</th>
                            <th class="text-uppercase">Kelas</th>
                            <th class="text-uppercase">Tagihan</th>
                            <th class="text-uppercase">Estimasi</th>
                            <th class="text-uppercase">Dibayarkan</th>
                            <th class="text-uppercase">Kekurangan</th>
                            <th class="text-uppercase">Lunas</th>
                            <th class="text-uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tagihans as $item)
                        <tr>
                            <td>{{ $loop->iteration }}.</td>
                            <td class="text-start">
                                <span class="fw-medium">
                                    {{ $item->ms_siswa->nama_siswa }}
                                </span>
                                <p class="text-muted mb-0">{{ $item->ms_siswa->deskripsi }}</p>
                            </td>
                            <td>{{ $item->ms_kelas->nama_kelas }}</td>
                            <td>{{ $item->jumlah_jenis_tagihan_siswa() }} item</td>
                            <td>
                                <span class="fs-14 fw-medium text-info">
                                Rp{{ number_format($item->total_tagihan_siswa(), 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                <span class="fs-14 fw-medium text-success">
                                    Rp{{ number_format($item->total_dibayarkan(), 0, ',', '.') }}</td>
                                </span>
                            </td>
                            <td>
                                <span class="fs-14 fw-medium text-danger">
                                    Rp{{ number_format($item->total_tagihan_siswa() - $item->total_dibayarkan(), 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="text-start">
                                @php
                                    $estimasi = $item->total_tagihan_siswa();
                                    $dibayarkan = $item->total_dibayarkan();
                                @endphp
                                <span class="fs-14 fw-medium mb-0">
                                    @if ($estimasi > 0)
                                    {{ number_format(($dibayarkan / $estimasi) * 100, 2) }}% <i class="ri-bar-chart-fill text-success fs-16 align-middle ms-2"></i>
                                @else
                                    -
                                @endif</span>
                            </td>                            
                            <td>
                                <div class="hstack gap-2">
                                    {{-- Tombol Kelola Tagihan --}}
                                    <button class="btn btn-sm btn-primary d-inline-flex align-items-center"
                                            data-bs-toggle="modal"
                                            data-bs-target="#ModalKelolaTagihan"
                                            title="Kelola Tagihan"
                                            wire:click.prevent="$emit('showTagihan', {
                                                ms_penempatan_siswa_id: {{ $item->ms_penempatan_siswa_id }},
                                                jenjang: {{ $item->ms_jenjang_id }},
                                                tahunAjar: {{ $item->ms_tahun_ajar_id }}
                                            })">
                                        <i class="ri-settings-3-line align-bottom me-1"></i> Kelola
                                    </button>
                            
                                    {{-- Tombol Detail Tagihan --}}
                                    <button class="btn btn-sm btn-secondary d-inline-flex align-items-center"
                                            data-bs-toggle="modal"
                                            data-bs-target="#ModalDetailTagihan"
                                            title="Detail Tagihan"
                                            wire:click.prevent="$emit('showDetailTagihan', {
                                                ms_penempatan_siswa_id: {{ $item->ms_penempatan_siswa_id }},
                                                jenjang: {{ $item->ms_jenjang_id }},
                                                tahunAjar: {{ $item->ms_tahun_ajar_id }}
                                            })">
                                        <i class="ri-eye-line align-bottom me-1"></i> Detail
                                    </button>
                            
                                    {{-- Link Riwayat Transaksi (pakai <a>) --}}
                                    <a href="javascript:void(0);"
                                       class="text-success d-inline-block detail-item-btn"
                                       data-bs-toggle="offcanvas"
                                       data-bs-target="#offcanvasHistori"
                                       aria-controls="offcanvasHistori"
                                       title="Riwayat Transaksi"
                                       wire:click.prevent="$emit('showHistoriTagihan', {
                                           ms_penempatan_siswa_id: {{ $item->ms_penempatan_siswa_id }},
                                           jenjang: {{ $item->ms_jenjang_id }},
                                           tahunAjar: {{ $item->ms_tahun_ajar_id }}
                                       })">
                                        <i class="ri-history-line align-bottom"></i> Riwayat
                                    </a>
                                </div>
                            </td>                            
                        </tr>
                        @empty
                            <tr>
                                <td colspan="8">
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
                            <td class="text-start"><strong>TOTAL</strong></td>
                            <td>
                                {{ $jumlahTagihan }} item
                            </td>
                            <td>
                                <span class="fs-14 fw-medium text-info">
                                    Rp{{ number_format($totalTagihan, 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                <span class="fs-14 fw-medium text-success">
                                    Rp{{ number_format($totalDibayarkan, 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                <span class="fs-14 fw-medium text-danger">
                                    Rp{{ number_format($totalKekurangan, 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                <span class="fs-14 fw-medium">{{ number_format($totalPersen, 2) }}% <i class="ri-bar-chart-fill text-success fs-16 align-middle ms-2"></i></span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                {{-- {{ $tagihans->links() }} --}}
            </div>

            @endif
        </div>
        {{-- end data --}}
    </div>
</div>