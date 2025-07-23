{{-- Care about people's approval and you will be their prisoner. --}}
<div class="card">
    <div class="card-header border-0 pb-0">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1"> Jabatan</h5>
            <div class="flex-shrink-0">
                <div class="d-flex gap-2 flex-wrap">
                    <button data-bs-toggle="modal" data-bs-target="#ModalAddJabatan" class="btn btn-primary"><i class="ri-play-list-add-line align-bottom me-1"></i> Jabatan Baru</button>
                </div>
            </div>
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
           <div class="table-responsive">
                <table class="table table-hover nowrap align-middle" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th class="text-uppercase" width="50px">no</th>
                            <th class="text-uppercase">jabatan</th>
                            <th class="text-uppercase">pegawai</th>
                            <th class="text-uppercase">aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jabatans as $item)
                            <tr>
                                <td>{{ $loop->iteration }}.</td>
                                <td>
                                    <span class="fw-medium">
                                        {{ $item->nama_jabatan }}
                                    </span>
                                    <p class="text-muted mb-0">{{ $item->deskripsi }}</p>
                                </td>
                                <td>{{ $item->jumlah_pegawai() ?? '0' }} pegawai</td>
                                <td>
                                    <div class="hstack gap-2">
                                        {{-- Tombol Edit Jabatan --}}
                                        <button class="btn btn-sm btn-info d-inline-flex align-items-center"
                                                data-bs-toggle="modal"
                                                data-bs-target="#ModalEditJabatan"
                                                title="Edit Jabatan"
                                                wire:click.prevent="$emit('loadDataJabatan', {{ $item->ms_jabatan_id }})">
                                            <i class="ri-quill-pen-line align-bottom me-1"></i> Edit
                                        </button>

                                        {{-- Tombol Hapus Jabatan --}}
                                        <button class="btn btn-sm btn-soft-danger d-inline-flex align-items-center"
                                                data-bs-toggle="modal"
                                                data-bs-target="#ModalDeleteJabatan"
                                                title="Hapus Jabatan"
                                                wire:click.prevent="$emit('confirmDelete', {{ $item->ms_jabatan_id }})">
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
                {{ $jabatans->links() }}
            </div>
        </div>
        {{-- DATA --}}
    </div>
</div>
