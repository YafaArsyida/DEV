{{-- A good traveler has no fixed plans and is not intent upon arriving. --}}
<div class="card">
    <div class="card-header border-0 pb-0">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1">Laporan Piutang Tagihan Siswa</h5>
            @if ($selectedJenjang && $selectedTahunAjar)
            <div class="flex-shrink-0">
                <div class="d-flex gap-2 flex-wrap">
                    @if ($selectedKelas)
                        <button type="button" class="btn btn-success" wire:click="$emit('cetakSuratKelas')"><i class="ri-vip-crown-fill text-warning me-1"></i> Kirim Semua Pesan</button>
                        <button type="button" class="btn btn-danger" wire:click="cetakSuratKelas({{ $selectedKelas }})"><i class="ri-vip-crown-fill text-warning me-1"></i> Cetak Semua Surat</button>
                    @else
                        <button type="button" class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#suratTagihan" aria-controls="suratTagihan" wire:click="$emit('refreshSurat', {{ $selectedJenjang }})"><i class="ri-file-paper-2-line me-1"></i> Format Surat</button>
                    @endif
                    <button data-bs-toggle="modal" data-bs-target="#ExportTagihanSiswa" wire:click.prevent="showExportTagihanSiswa" class="btn btn-soft-success"><i class="ri-file-excel-2-line me-1"></i> Export</button>
                    <button type="button" class="btn btn-info" data-bs-toggle="offcanvas" data-bs-target="#filterTagihan" aria-controls="filterTagihan"><i class="ri-filter-3-line me-1"></i> Fliters</button>
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
            <div class="table-responsive" style="max-height: 1000px;" data-simplebar>
                <table class="table table-hover nowrap align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-uppercase" style="width: 50px;">NO</th>
                            <th style="white-space: nowrap;" class="text-uppercase">Nama Siswa</th>
                            <th class="text-uppercase text-center">Tagihan</th>
                            <th class="text-uppercase" style="min-width: 650px;">Rincian</th>
                            <th class="text-uppercase" style="min-width: 250px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($laporans as $laporan)
                        <tr>
                            <td>{{ $loop->iteration }}. </td>
                            <td style="white-space: nowrap;" class="text-start">
                                <span class="fw-medium">
                                    {{ $laporan['nama_siswa'] }}
                                </span>
                                <p class="text-muted mb-0">{{ $laporan['nama_kelas'] }}</p>
                            </td>
                            <td class="text-center bg-light">
                                <span class="fs-14 text-danger">
                                    RP{{ number_format($laporan['total_tagihan'], 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                @foreach ($laporan['rincian_tagihan'] as $rincian)
                                    {{ $rincian['nama_jenis_tagihan_siswa'] }}
                                    <span class="fs-14 text-danger">
                                        RP{{ number_format($rincian['jumlah_kekurangan'], 0, ',', '.') }}
                                    </span>
                                    @php
                                        $status = $rincian['status'];
                                    @endphp
                                    {{-- <span class="text-muted">{{ $status }}</span> --}}
                                    @if (!$loop->last)
                                        ,
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                <ul class="list-inline hstack gap-2 mb-0">
                                    <li class="list-inline-item detail" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Kirim Tagihan">
                                        <a href="" wire:click.prevent="kirimWhatsappTagihan({{ $laporan['ms_penempatan_siswa_id'] }})" class="btn btn-success btn-sm d-inline-flex align-items-center gap-1" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Kirim Pesan WA">
                                            <i class="ri-whatsapp-line fs-16 align-middle"></i> Kirim Pesan
                                        </a>
                                        <!-- Tombol Cetak -->
                                        <a wire:click="cetakSurat({{ $laporan['ms_penempatan_siswa_id'] }})" 
                                            class="btn btn-info btn-sm d-inline-flex align-items-center gap-1" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Cetak Surat">
                                            <i class="ri-printer-line fs-16 align-middle"></i>
                                            <span> Cetak Surat</span>
                                        </a>
                                    </li>
                                </ul>
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
                        <tr class="">
                            <td></td>
                            <td class="text-start"><strong>TOTAL</strong></td>
                            <td class="text-center bg-light">
                                <span class="fs-14 text-danger">RP {{ number_format($totalTagihan, 0, ',', '.') }}</span>
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
