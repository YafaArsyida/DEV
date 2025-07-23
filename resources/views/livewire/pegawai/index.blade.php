{{-- Do your work, then step back. --}}
<div class="card">
    <div class="card-header border-0 pb-0">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1">Data Pegawai</h5>
            <div class="flex-shrink-0">
                <div class="d-flex gap-2 flex-wrap">
                    @if ($selectedJenjang)
                    <button data-bs-toggle="modal" data-bs-target="#ModalImportKontakPegawai" wire:click.prevent="$emit('showImportKontakPegawai', {{ $selectedJenjang }})" class="btn btn-success"><i class="ri-whatsapp-line me-1 align-bottom"></i> Import Kontak</button>                        
                    <button data-bs-toggle="modal" data-bs-target="#ModalImportEduCardPegawai" wire:click.prevent="$emit('showImportEduCardPegawai', {{ $selectedJenjang }})" class="btn btn-warning"><i class="ri-bank-card-line me-1 align-bottom"></i> Import EduCard</button>                        
                    <button data-bs-toggle="modal" data-bs-target="#ModalImportPegawai" wire:click.prevent="$emit('showImportPegawai', {{ $selectedJenjang }})" class="btn btn-secondary"><i class="ri-contacts-line me-1 align-bottom"></i> Import Pegawai</button>
                    <button data-bs-toggle="modal" data-bs-target="#ModalAddPegawai" wire:click.prevent="$emit('showAddPegawai', {{ $selectedJenjang }})" class="btn btn-primary"><i class="ri-play-list-add-line align-bottom me-1"></i>Pegawai Baru</button>
                    @endif
                    <button data-bs-toggle="modal" data-bs-target="#ModalExportSiswa" wire:click.prevent="showExportSiswa"  class="btn btn-soft-success"><i class="ri-file-excel-2-line me-1 align-bottom"></i> Export</button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3 mb-3">
            <div class="col-xxl-8 col-sm-6">
                <div class="search-box">
                    <input type="text" class="form-control search" wire:model.debounce.300ms="search" placeholder="cari nama, deskripsi atau lainnya...">
                    <i class="ri-search-line search-icon"></i>
                </div>
            </div>
            <div class="col-xxl-4 col-sm-6"> 
                <div class="input-group">
                    <select wire:model="selectedJenjang" style="cursor: pointer" class="form-select border-1 dash-filter-picker shadow" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Unit">
                        <option value="">Semua Unit</option>
                        @foreach ($select_jenjang as $item)
                            <option value="{{ $item->ms_jenjang_id }}">{{ $item->nama_jenjang }}</option>
                        @endforeach
                    </select>
                    <div class="input-group-text bg-primary border-primary text-white">
                        <i class=" ri-government-line"></i>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
        {{-- DATA --}}
        <div class="live-preview">
           <div class="table-responsive">
                <table class="table table-hover nowrap align-middle" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th class="text-uppercase" width="50px">no</th>
                            <th class="text-uppercase">pegawai</th>
                            <th class="text-uppercase">jabatan</th>
                            <th class="text-uppercase">kontak</th>
                            <th class="text-uppercase">Nomor Induk</th>
                            <th class="text-uppercase">Educard</th>
                            <th class="text-uppercase">aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pegawais as $item)
                            <tr>
                                <td>{{ $loop->iteration }}.</td>
                                <td>
                                    <span class="fw-medium">
                                        {{ $item->nama_pegawai }}
                                    </span>
                                    <p class="text-muted mb-0">{{ $item->deskripsi }}</p>
                                </td>
                                <td>
                                    <span class="fw-medium">
                                        {{ $item->ms_jabatan->nama_jabatan }}
                                    </span>
                                    <p class="text-muted mb-0">Unit : {{ $item->ms_jenjang->nama_jenjang }}</p>
                                </td>
                                <td>
                                    <span class="fw-medium text-success">
                                        Telepon : {{ $item->telepon }}
                                    </span>
                                    <p class="text-muted mb-0">E-mail : {{ $item->email }}</p>
                                </td>
                                <td>{{ $item->nip }}</td>
                                <td>
                                    @if ($item->ms_educard)
                                        {{ $item->ms_educard->kode_kartu }}
                                    @else
                                        <em>Belum memiliki kartu</em>
                                    @endif
                                </td>
                                <td>
                                    <div class="hstack gap-2">
                                        {{-- Tombol Edit Pegawai --}}
                                        <button class="btn btn-sm btn-info d-inline-flex align-items-center"
                                                data-bs-toggle="modal"
                                                data-bs-target="#ModalEditPegawai"
                                                title="Edit Pegawai"
                                                wire:click.prevent="$emit('loadDataPegawai', {{ $item->ms_pegawai_id }})">
                                            <i class="ri-quill-pen-line align-bottom me-1"></i> Edit
                                        </button>

                                        {{-- Tombol Hapus Pegawai --}}
                                        <button class="btn btn-sm btn-soft-danger d-inline-flex align-items-center"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deletePegawai"
                                                title="Hapus Pegawai"
                                                wire:click.prevent="$emit('confirmDeletePegawai', {{ $item->ms_pegawai_id }})">
                                            <i class="ri-delete-bin-5-line align-bottom me-1"></i>
                                        </button>
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <!-- Jika Tidak Ada Data Kelas -->
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
                </table>
                <!-- Pagination -->
                {{ $pegawais->links() }}
            </div>
        </div>
        {{-- DATA --}}
    </div>
</div>