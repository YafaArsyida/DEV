{{-- A good traveler has no fixed plans and is not intent upon arriving. --}}
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">

            {{-- TITLE --}}
            <div>
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-file-list-3-line"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">
                            Laporan Piutang Siswa
                        </h5>
                        {{-- <small>
                            Kelola laporan piutang, surat tagihan, dan WhatsApp.
                        </small> --}}
                    </div>
                </div>
            </div>

            {{-- ACTION --}}
            @if ($selectedJenjang && $selectedTahunAjar)
                <div class="d-flex gap-2 flex-wrap">

                    <button type="button"
                        class="btn rounded-pill px-4 btn-primary d-inline-flex align-items-center gap-1"
                        data-bs-toggle="modal"
                        data-bs-target="#tipsCetakSuratModal">
                        <i class="ri-lightbulb-flash-fill text-warning"></i>
                        Tips Fitur Unggulan
                    </button>

                    @if ($selectedKelas)
                        <button type="button"
                            wire:click="cetakSuratKelas({{ $selectedKelas }})"
                            class="btn rounded-pill px-4 btn-primary d-inline-flex align-items-center gap-1">
                            <i class="ri-vip-crown-fill text-warning"></i>
                            Cetak Semua Surat
                        </button>
                    @endif

                    @if (!$pesans)
                        <button
                            data-bs-toggle="modal"
                            data-bs-target="#createPesanTagihanSiswa"
                            wire:click="$emit('createPesanTagihanSiswa', {{ $selectedJenjang }})"
                            class="btn rounded-pill px-4 btn-success d-inline-flex align-items-center gap-1">
                            <i class="ri-whatsapp-line"></i>
                            Setting WhatsApp
                        </button>
                    @else
                        <button
                            data-bs-toggle="modal"
                            data-bs-target="#ModalEditTagihanSiswa"
                            wire:click="$emit('loadPesanTagihanSiswa', {{ $ms_pesan_id }})"
                            class="btn rounded-pill px-4 btn-success d-inline-flex align-items-center gap-1">
                            <i class="ri-whatsapp-line"></i>
                            Edit WhatsApp
                        </button>
                    @endif

                    <button type="button"
                        class="btn rounded-pill px-4 btn-danger d-inline-flex align-items-center gap-1"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#suratTagihan"
                        aria-controls="suratTagihan"
                        wire:click="$emit('refreshSurat', {{ $selectedJenjang }})">
                        <i class="ri-file-paper-2-line"></i>
                        Format Surat
                    </button>

                    {{-- <button
                        data-bs-toggle="modal"
                        data-bs-target="#ExportTagihanSiswa"
                        wire:click.prevent="showExportTagihanSiswa"
                        class="btn rounded-pill px-4 btn-soft-success">
                        <i class="ri-file-excel-2-line"></i>
                        Export
                    </button> --}}

                    <button type="button"
                        class="btn rounded-pill px-4 btn-primary d-inline-flex align-items-center gap-1"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#filterTagihan"
                        aria-controls="filterTagihan">
                        <i class="ri-filter-3-line"></i>
                        Filter
                    </button>

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
                <label for="searchData" class="form-label">Pencarian</label>
                <div class="search-box">
                    <input type="text" id="searchData" class="form-control search" wire:model.debounce.300ms="search"
                        placeholder="Cari nama, deskripsi, atau lainnya...">
                    <i class="ri-search-line search-icon"></i>
                </div>
            </div>
            <!-- Filter Periode -->
            <div class="col-xxl-2 col-sm-2">
                <label class="form-label fw-semibold">Jatuh Tempo</label>
                <div class="d-flex align-items-center gap-2">
                    <input type="date" class="form-control" wire:model="endDate">
                    <button type="button" class="btn btn-soft-secondary" wire:click="resetTanggal" title="Reset Tanggal">
                        <i class="ri-refresh-line"></i>
                    </button>
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
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-uppercase" style="width: 50px;">NO</th>
                            <th style="white-space: nowrap;" class="text-uppercase">Nama Siswa</th>
                            <th class="text-uppercase text-center">Piutang</th>
                            <th class="text-uppercase" style="min-width: 650px;">Rincian Piutang</th>
                            <th class="text-uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($laporans as $laporan)
                        <tr>
                            <td>
                                {{ ($pagination->currentPage() - 1) * $pagination->peRpage() + $loop->iteration }}.
                            </td>
                            <td style="white-space: nowrap">
                                {{ $laporan['nama_siswa'] }}
                                <p class="text-muted mb-0">{{ $laporan['nama_kelas'] }}</p>
                            </td>

                            <td class="text-center bg-light">
                                <span class="fs-12 fw-medium">
                                    Rp{{ number_format($laporan['total_tagihan'], 0, ',', '.') }}
                                </span>
                            </td>

                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach ($laporan['rincian_tagihan'] as $r)
                                        <span class="badge bg-light text-body border px-3 py-2">
                                            {{ $r['nama_jenis_tagihan_siswa'] }}
                                            <strong class="text-danger">
                                                Rp{{ number_format($r['jumlah_kekurangan'],0,',','.') }}
                                            </strong>
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td style="white-space: nowrap">
                                <div class="d-flex justify-content-center gap-2">
                                    {{-- Kirim WhatsApp --}}
                                    <a href="#"
                                        wire:click.prevent="kirimWhatsappTagihan({{ $laporan['ms_penempatan_siswa_id'] }})"
                                        class="btn btn-success btn-sm rounded-pill px-3"
                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Kirim Pesan WhatsApp">
                                        <i class="ri-whatsapp-line me-1"></i>
                                        Pesan
                                    </a>

                                    {{-- Cetak Surat --}}
                                    <a href="#"
                                        wire:click.prevent="cetakSurat({{ $laporan['ms_penempatan_siswa_id'] }})"
                                        class="btn btn-danger btn-sm rounded-pill px-3" 
                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Cetak Surat Tagihan">
                                        <i class="ri-printer-line me-1"></i>
                                        Cetak
                                    </a>

                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td></td>
                            <td class="text-start"><strong>TOTAL</strong></td>
                            <td class="text-center bg-light">
                                <span class="fs-12 fw-semibold">
                                    Rp{{ number_format($totalTagihan, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                {{-- PAGINATION --}}
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="text-muted fs-13">
                            Menampilkan
                            <span class="fw-semibold">
                                {{ $pagination->firstItem() ?? 0 }}
                            </span>
                            -
                            <span class="fw-semibold">
                                {{ $pagination->lastItem() ?? 0 }}
                            </span>
                            dari
                            <span class="fw-semibold">
                                {{ $pagination->total() }}
                            </span>
                            data
                        </div>
                        <div>
                            {{ $pagination->links() }}
                        </div>
                    </div>
                </div>
            </div>
            @endif
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
