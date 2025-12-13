{{-- A good traveler has no fixed plans and is not intent upon arriving. --}}
<div class="card">
    <div class="card-header border-0 pb-0">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1">Laporan Piutang Tagihan Siswa</h5>
            @if ($selectedJenjang && $selectedTahunAjar)
            <div class="flex-shrink-0">
                <div class="d-flex gap-2 flex-wrap">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tipsCetakSuratModal">
                        <i class="ri-lightbulb-flash-fill text-warning me-1"></i> Tips fitur Unggulan
                    </button>
                    @if ($selectedKelas)
                        <button type="button" class="btn btn-primary" wire:click="cetakSuratKelas({{ $selectedKelas }})"><i class="ri-vip-crown-fill text-warning me-1"></i> Cetak Semua Surat</button>
                    @endif

                    @if (!$pesans)
                        <button data-bs-target="#createPesanTagihanSiswa" data-bs-toggle="modal" wire:click="$emit('createPesanTagihanSiswa', {{ $selectedJenjang }})" class="btn btn-success shadow-none"><i class="ri-whatsapp-line align-bottom me-1"></i> Setting WhatsApp</button>
                    @else
                    <button data-bs-target="#ModalEditTagihanSiswa" data-bs-toggle="modal" wire:click="$emit('loadPesanTagihanSiswa', {{ $ms_pesan_id }})" class="btn btn-success shadow-none"><i class="ri-whatsapp-line align-bottom me-1"></i> Edit WhatsApp</button>
                    @endif

                    <button type="button" class="btn btn-danger" data-bs-toggle="offcanvas" data-bs-target="#suratTagihan" aria-controls="suratTagihan" wire:click="$emit('refreshSurat', {{ $selectedJenjang }})"><i class="ri-file-paper-2-line me-1"></i> Format Surat</button>
                    {{-- <button data-bs-toggle="modal" data-bs-target="#ExportTagihanSiswa" wire:click.prevent="showExportTagihanSiswa" class="btn btn-soft-success"><i class="ri-file-excel-2-line me-1"></i> Export</button> --}}
                    <button type="button" class="btn btn-info" data-bs-toggle="offcanvas" data-bs-target="#filterTagihan" aria-controls="filterTagihan"><i class="ri-filter-3-line me-1"></i> Filters</button>
                </div>
            </div>
            @endif
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3 align-items-end mb-3">
            <!-- Dropdown Kelas -->
            <div class="col-xxl-2 col-sm-2"> 
                <label for="selectKelas" class="form-label">Kelas</label>
                <select id="selectKelas" 
                        wire:model="selectedKelas" 
                        class="form-select" style="cursor: pointer"
                        data-bs-toggle="tooltip" data-bs-trigger="hover" 
                        data-bs-placement="top" title="Pilih Kelas">
                    <option value="">Semua Kelas</option>
                    @foreach ($select_kelas as $item)    
                        <option value="{{ $item->ms_kelas_id }}">{{ $item->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Input Pencarian -->
            <div class="col-xxl-8 col-sm-8">
                <label for="searchInput" class="form-label">Pencarian</label>
                <div class="position-relative">
                    <input type="text" id="searchInput" 
                        class="form-control ps-4" 
                        wire:model.debounce.300ms="search" 
                        placeholder="Cari nama, deskripsi, atau lainnya...">
                    <i class="ri-search-line position-absolute top-50 start-0 translate-middle-y ms-2 text-muted"></i>
                </div>
            </div>
            <!-- Filter Periode -->
            <div class="col-xxl-2 col-sm-2">
                <label class="form-label fw-semibold">Jatuh Tempo</label>
                <div class="d-flex align-items-center gap-2">
                    <input type="date" id="endDate" class="form-control" wire:model="endDate" value="{{ $endDate }}">
                    <div class="col-auto">
                        <button type="button" class="btn btn-soft-secondary btn-icon rounded-circle" wire:click="resetTanggal" title="Reset Tanggal">
                            <i class="ri-refresh-line fs-16"></i>
                        </button>    
                    </div>
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
                <table class="table table-hover nowrap align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-uppercase" style="width: 50px;">NO</th>
                            <th style="white-space: nowrap;" class="text-uppercase">Nama Siswa</th>
                            <th class="text-uppercase text-center">Total</th>
                            <th class="text-uppercase" style="min-width: 650px;">Rincian Piutang</th>
                            <th class="text-uppercase" style="min-width: 250px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($laporans as $laporan)
                        <tr>
                            <td>
                                {{ ($pagination->currentPage() - 1) * $pagination->perPage() + $loop->iteration }}.
                            </td>
                            <td>
                                <span class="fw-medium">
                                   {{ $laporan['nama_siswa'] }}
                                </span>
                                <p class="text-muted mb-0">{{ $laporan['nama_kelas'] }}</p>
                            </td>

                            <td class="text-center bg-light">
                                <span class="fs-14 text-primary">
                                    RP{{ number_format($laporan['total_tagihan'], 0, ',', '.') }}
                                </span>
                            </td>

                            <td class="text-primary">
                                @foreach ($laporan['rincian_tagihan'] as $r)
                                    {{ $r['nama_jenis_tagihan_siswa'] }}
                                    RP{{ number_format($r['jumlah_kekurangan'], 0, ',', '.') }}
                                    {{-- <span class="text-primary">
                                    </span> --}}

                                    @unless($loop->last); @endunless
                                @endforeach
                            </td>

                            <td>
                                <ul class="list-inline hstack gap-2 mb-0">
                                    <li class="list-inline-item detail" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Kirim Tagihan">
                                        <a href="" wire:click.prevent="kirimWhatsappTagihan({{ $laporan['ms_penempatan_siswa_id'] }})" class="btn btn-success btn-sm d-inline-flex align-items-center gap-1" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Kirim Pesan WA">
                                            <i class="ri-whatsapp-line fs-14 align-middle"></i> Kirim Pesan
                                        </a>
                                        <!-- Tombol Cetak -->
                                        <a wire:click="cetakSurat({{ $laporan['ms_penempatan_siswa_id'] }})" 
                                            class="btn btn-danger btn-sm d-inline-flex align-items-center gap-1" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Cetak Surat">
                                            <i class="ri-printer-line fs-14 align-middle"></i>
                                            <span> Cetak Surat</span>
                                        </a>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td></td>
                            <td class="text-start"><strong>TOTAL</strong></td>
                            <td style="white-space: nowrap;" class="text-center bg-light">
                                <span class="fs-14 text-primary">
                                    RP {{ number_format($totalTagihan, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @endif
        </div>
        
        {{-- Pagination --}}
        <div class="mt-3">
            {{ $pagination->links() }}
        </div>

        {{-- end data --}}
    </div>
    <div class="modal fade zoomIn" id="tipsCetakSuratModal" tabindex="-1" aria-labelledby="tipsCetakSuratLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-5 text-center">
                    <lord-icon
                        src="https://cdn.lordicon.com/lupuorrc.json"
                        trigger="loop"
                        colors="primary:#405189,secondary:#f06548"
                        style="width:90px;height:90px">
                    </lord-icon>

                    <div class="mt-4 text-center">
                        <h4 class="fs-semibold">Tips Fitur Unggulan</h4>
                        <p class="text-muted fs-14 mb-4 pt-1">
                            Fitur <strong>Cetak Surat Massal</strong> memungkinkan Anda mencetak semua surat piutang tagihan untuk satu kelas sekaligus.  
                            <br><br>
                            <span class="text-danger fw-semibold">Pilih kelas terlebih dahulu</span> untuk mengaktifkan tombol <strong>Cetak Semua Surat</strong>.
                        </p>
                        <div class="hstack gap-2 justify-content-center">
                            <button class="btn btn-link link-secondary fw-medium text-decoration-none shadow-none" data-bs-dismiss="modal">
                                <i class="ri-close-line me-1 align-middle"></i> Mengerti
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
