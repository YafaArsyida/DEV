{{-- Care about people's approval and you will be their prisoner. --}}
<div class="">
    <div class="row g-3 mb-3">
        <div class="col-xxl-8 col-sm-6">
            <div class="search-box">
                <input type="text" class="form-control search" wire:model.debounce.300ms="search"
                    placeholder="cari jabatan...">
                <i class="ri-search-line search-icon"></i>
            </div>
        </div>
        <div class="col-xxl-4 col-sm-6">
            <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal"
                data-bs-target="#ModalJabatanCreate" wire:click="$emit('JabatanCreate')">
                <i class="ri-add-fill me-1 align-bottom"></i> Tambah Jabatan
            </button>
        </div>
    </div>

    <div class="col-xl-12">
        <div class="mt-4">
            <div class="live-preview">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover nowrap align-middle">
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
                                <td class="text-center">
                                    <div class="hstack gap-2 justify-content-center">
                                
                                        {{-- Edit --}}
                                        <a href="#ModalEditJabatan" data-bs-toggle="modal" class="text-warning d-inline-block" title="Edit Jabatan"
                                            wire:click.prevent="$emit('loadDataJabatan', {{ $item->ms_jabatan_id }})">
                                            <i class="ri-mark-pen-line fs-17 align-middle"></i> Edit
                                        </a>
                                
                                        {{-- Delete --}}
                                        <a href="#ModalDeleteJabatan" data-bs-toggle="modal" class="text-danger d-inline-block" title="Hapus Jabatan"
                                            wire:click.prevent="$emit('confirmDeleteJabatan', {{ $item->ms_jabatan_id }})">
                                            <i class="ri-delete-bin-5-line fs-17 align-middle"></i> Hapus
                                        </a>
                                
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <!-- Jika Tidak Ada Data Kelas -->
                            <tr>
                                <td colspan="7">
                                    <div class="noresult text-center py-3">
                                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                            colors="primary:#405189,secondary:#08a88a" style="width:75px;height:75px">
                                        </lord-icon>
                                        <h5 class="mt-2">Maaf, Tidak Ada Data yang Ditemukan</h5>
                                        <p class="text-muted mb-0">Kami telah mencari keseluruhan data, namun tidak ditemukan hasil yang sesuai.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>