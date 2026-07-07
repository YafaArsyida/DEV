<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">
            {{-- TITLE --}}
            <div>
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-calendar-event-line">
                            </i>
                        </div>
                    </div>
    
                    <div>
                        <h5 class="fw-bold mb-1">
                            Data Siswa
                        </h5>
                        {{-- <small>
                            Kelola laporan kegiatan generus 
                        </small> --}}
                    </div>
                </div>
            </div>
    
            {{-- ACTION --}}
            @if ($selectedJenjang && $selectedTahunAjar)
            <div class="d-flex gap-2 flex-wrap">
                @if ($siswaSelected)
                    <button href="#ModalBulkDeleteSiswa" data-bs-toggle="modal" class="btn rounded-pill px-4 btn-soft-danger d-inline-flex align-items-center gap-1" wire:click.prevent="$emit('confirmBulkDelete', {{ json_encode($siswaSelected) }})">
                        <i class="ri-delete-bin-2-line me-1 align-bottom"></i> Hapus {{ count($siswaSelected) }}
                    </button>
                @endif
                @if ($selectedKelas)
                    <button data-bs-toggle="modal" data-bs-target="#ModalImportTelepon" wire:click.prevent="$emit('showImportTelepon', {{ $selectedKelas }}, {{ $selectedJenjang }}, {{ $selectedTahunAjar }})" class="btn rounded-pill px-4 btn-success"><i class="ri-whatsapp-line me-1 align-bottom"></i> Import Telepon</button>                        
                    <button data-bs-toggle="modal" data-bs-target="#ModalImportEduCard" wire:click.prevent="$emit('showImportEduCard', {{ $selectedKelas }}, {{ $selectedJenjang }}, {{ $selectedTahunAjar }})"  class="btn rounded-pill px-4 btn-warning"><i class="ri-bank-card-line me-1 align-bottom"></i> Import EduCard</button>                        
                @endif

                <button data-bs-toggle="modal" data-bs-target="#ModalImportSiswa" wire:click.prevent="$emit('showImportSiswa', {{ $selectedJenjang }}, {{ $selectedTahunAjar }})"  class="btn rounded-pill px-4 btn-secondary"><i class="ri-contacts-line me-1 align-bottom"></i> Import Siswa</button>
                <button data-bs-toggle="modal" data-bs-target="#ModalAddSiswa" wire:click.prevent="$emit('showCreateSiswa', {{ $selectedJenjang }}, {{ $selectedTahunAjar }})" class="btn rounded-pill px-4 btn-primary"><i class="ri-play-list-add-line me-1 align-bottom"></i> Siswa Baru</button>
                <button data-bs-toggle="modal" data-bs-target="#ModalExportSiswa" class="btn rounded-pill px-4 btn-soft-success"><i class="ri-file-excel-2-line me-1 align-bottom"></i> Export</button>
            </div>
            @endif
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3 align-items-end mb-3">
            <!-- Input Pencarian -->
            <div class="col-12 col-lg-6">
                <label for="searchData" class="form-label">Pencarian</label>
                <div class="search-box">
                    <input type="text" id="searchData" class="form-control search" wire:model.debounce.300ms="search"
                        placeholder="Cari nama, deskripsi, atau lainnya...">
                    <i class="ri-search-line search-icon"></i>
                </div>
            </div>
            <!-- Dropdown Kelas -->
            <div class="col-6 col-lg-3">
                <label for="filterKelas" class="form-label">Kelas</label>
                <select id="filterKelas" wire:model="selectedKelas" class="form-select" style="cursor: pointer;"
                    data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Kelas">
                    <option value="">Semua Kelas</option>
                    @foreach ($select_kelas as $item)
                    <option value="{{ $item->ms_kelas_id }}">{{ $item->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <!-- Dropdown Jumlah Per Halaman -->
            <div class="col-6 col-lg-3">
                <label for="perPage" class="form-label">Tampilkan</label>
                <select id="perPage" wire:model="perPage" class="form-select" style="cursor: pointer;">
                    <option value="10">10 Data</option>
                    <option value="20">20 Data</option>
                    <option value="30">30 Data</option>
                    <option value="40">40 Data</option>
                    <option value="50">50 Data</option>
                    <option value="75">75 Data</option>
                    <option value="100">100 Data</option>
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
                <table class="table table-hover table-nowrap align-middle" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" style="width: 50px;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="checkAll" wire:model="selectAll">
                                </div>
                            </th>
                            <th class="text-uppercase" width="50px">no</th>
                            <th class="text-uppercase" style="width: 50px;">Hapus</th>
                            <th class="text-uppercase">siswa</th>
                            <th class="text-uppercase">kelas</th>
                            <th class="text-uppercase">whatsapp</th>
                            <th class="text-uppercase">EduCard</th>
                            <th class="text-uppercase">aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @forelse ($siswas as $item) --}}
                        @forelse ($siswas as $key => $item)
                        <tr>
                            <td scope="row">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:key="{{ $item->ms_penempatan_siswa_id }}"
                                        wire:model.live="siswaSelected" value="{{ $item->ms_penempatan_siswa_id }}">
                                </div>
                            </td>
                            <td>{{ $siswas->firstItem() + $key }}.</td>
                            <td class="text-center">
                                <a href="#ModalDeleteSiswa" data-bs-toggle="modal" class="text-danger d-inline-block remove-item-btn"
                                    wire:click.prevent="$emit('confirmDeleteSiswa', {{ $item->ms_penempatan_siswa_id }})"
                                    data-bs-trigger="hover" data-bs-placement="top" title="Hapus Siswa">
                                    <i class="ri-delete-bin-5-fill fs-14"></i>
                                </a>
                            </td>
                            <td>
                                <span class="fw-medium">
                                    {{ $item->ms_siswa->nama_siswa }}
                                </span>
                                <p class="text-muted mb-0">{{ $item->deskripsi }}</p>
                            </td>
                            <td>{{ $item->ms_kelas->nama_kelas }}</td>
                            <td>
                                <span class="fw-semibold text-success">    
                                    {{ $item->ms_siswa->telepon }}
                                </span>
                            </td>
                            <td>
                                @if($item->ms_siswa->ms_educard)
                                <span class="fw-semibold text-warning">    
                                    {{ $item->ms_siswa->ms_educard->kode_kartu }}
                                </span>
                                @else
                                    <em>Belum memiliki kartu</em>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    {{-- Detail/Transfer --}}
                                    <a href="#ModalDetailSiswa" data-bs-toggle="modal" class="btn btn-soft-primary btn-sm rounded-pill px-3"
                                        title="Detail Siswa" wire:click.prevent="$emit('showDetailSiswa', {{ $item->ms_penempatan_siswa_id }})">
                                        <i class="ri-eye-line me-1"></i> Detail
                                    </a>
                                    {{-- edit --}}
                                    <a href="#ModalEditSiswa" data-bs-toggle="modal" class="btn btn-primary btn-sm rounded-pill px-3" title="Edit Siswa" 
                                        wire:click="$emit('loadDataSiswa', {{ $item->ms_penempatan_siswa_id }})">
                                        <i class="ri-mark-pen-line me-1"></i> Edit
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                            <!-- Jika Tidak Ada Data Kelas -->
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
                </table>
                {{-- PAGINATION --}}
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="text-muted fs-13">
                            Menampilkan
                            <span class="fw-semibold">
                                {{ $siswas->firstItem() ?? 0 }}
                            </span>
                            -
                            <span class="fw-semibold">
                                {{ $siswas->lastItem() ?? 0 }}
                            </span>
                            dari
                            <span class="fw-semibold">
                                {{ $siswas->total() }}
                            </span>
                            data siswa
                        </div>
                        <div>
                            {{ $siswas->links() }}
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        {{-- DATA --}}
    </div>
</div>