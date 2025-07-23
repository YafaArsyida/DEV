<div class="card">
    <div class="card-header border-0 pb-0">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1"> Jenis Tagihan Siswa</h5>
            @if ($selectedJenjang && $selectedTahunAjar)
            <div class="flex-shrink-0">
                <div class="d-flex gap-2 flex-wrap">
                    <button wire:click="cetakLaporanJenisTagihan" class="btn btn-danger d-inline-flex align-items-center gap-1">
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
                <select wire:model="selectedKategoriTagihan" style="cursor: pointer" class="form-select" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Kelas">
                    <option value="">Semua Kategori</option>
                    @foreach ($select_kategori as $kategori)
                        <option value="{{ $kategori->ms_kategori_tagihan_siswa_id }}">{{ $kategori->nama_kategori_tagihan_siswa }}</option>
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
                <table class="table table-hover nowrap align-middle" style="width:100%">
                    <thead class="table-light">
                        <tr style="white-space: nowrap;">
                            <th class="text-uppercase" style="width: 50px;">NO</th>
                            <th class="text-uppercase">Jenis Tagihan</th>
                            <th class="text-uppercase">Kategori</th>
                            {{-- <th class="text-uppercase">cicilan</th> --}}
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
                        <tr style="white-space: nowrap;">
                            <td>{{ $loop->iteration }}.</td>
                            <td class="text-start">
                                <span class="fw-medium">
                                {{ $item->nama_jenis_tagihan_siswa }}
                                </span>
                                <p class="text-muted mb-0">{{ $item->deskripsi }}</p>
                            </td>
                            <td>{{ $item->nama_kategori_tagihan_siswa() }}</td>
                            {{-- <td class="{{ $item->cicilan_status == 'Aktif' ? 'text-success' : 'text-danger' }}"><i class="ri-{{ $item->cicilan_status == 'Aktif' ? 'checkbox' : 'close' }}-circle-line fs-17 align-middle"></i> {{ $item->cicilan_status }}</td> --}}
                            <td>{{ $item->jumlah_tagihan_siswa() }} item</td>
                            <td>
                                <span class="fs-14 fw-medium text-info">
                                    RP{{ number_format($item->total_tagihan_siswa(), 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                <span class="fs-14 fw-medium text-success">
                                    RP{{ number_format($item->total_tagihan_siswa_dibayarkan(), 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                <span class="fs-14 fw-medium text-danger">
                                    RP{{ number_format($item->total_tagihan_siswa() - $item->total_tagihan_siswa_dibayarkan(), 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="fs-14 fw-medium text-start">
                                @php
                                    $estimasi = $item->total_tagihan_siswa();
                                    $dibayarkan = $item->total_tagihan_siswa_dibayarkan();
                                @endphp
                                <span class="mb-0">
                                    @if ($estimasi > 0)
                                    {{ number_format(($dibayarkan / $estimasi) * 100, 2) }}% <i class="ri-bar-chart-fill text-success fs-16 align-middle ms-2"></i>
                                @else
                                    -
                                @endif</span>
                            </td>
                            
                            <td>
                                <div class="hstack gap-2">
                                    {{-- Kelola Tagihan --}}
                                    <button class="btn btn-sm btn-primary d-inline-flex align-items-center" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#ModalKelolaTagihan"
                                            wire:click.prevent="$emit('showTagihan', {
                                                ms_jenis_tagihan_siswa_id: {{ $item->ms_jenis_tagihan_siswa_id }},
                                                jenjang: {{ $item->ms_jenjang_id }},
                                                tahunAjar: {{ $item->ms_tahun_ajar_id }}
                                            })">
                                        <i class="ri-settings-3-line align-bottom me-1"></i> Kelola
                                    </button>
                            
                                    {{-- Detail Tagihan --}}
                                    <button class="btn btn-sm btn-secondary d-inline-flex align-items-center"
                                            data-bs-toggle="modal"
                                            data-bs-target="#ModalDetailTagihan"
                                            wire:click.prevent="$emit('showDetailTagihan', {
                                                ms_jenis_tagihan_siswa_id: {{ $item->ms_jenis_tagihan_siswa_id }},
                                                jenjang: {{ $item->ms_jenjang_id }},
                                                tahunAjar: {{ $item->ms_tahun_ajar_id }}
                                            })">
                                        <i class="ri-eye-line align-bottom me-1"></i> Detail
                                    </button>
                                </div>
                            </td>                            
                        </tr>
                        @empty
                            <tr>
                                <td colspan="7">
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
                            <td>{{ $totalSiswa }} item</td>
                            <td>
                                <span class="fs-14 fw-medium text-info">
                                    RP{{ number_format($totalEstimasi, 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                <span class="fs-14 fw-medium text-success">
                                    RP{{ number_format($totalDibayarkan, 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                <span class="fs-14 fw-medium text-danger">
                                    RP{{ number_format($totalKekurangan, 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                <span class="fs-14 fw-medium">
                                    {{ number_format($totalPersen, 2) }}% <i class="ri-bar-chart-fill text-success fs-16 align-middle ms-2"></i>
                                </span>
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