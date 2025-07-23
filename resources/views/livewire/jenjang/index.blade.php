<div class="card">
    <div class="card-header border-0 pb-0">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1">Jenjang</h5>
            <div class="flex-shrink-0">
                <div class="d-flex gap-2 flex-wrap">
                    <button data-bs-toggle="modal" id="create-btn" data-bs-target="#ModalAddJenjang" class="btn btn-primary"><i class="ri-add-line align-bottom me-1"></i> jenjang baru</button>
                </div>
            </div>
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
            <div class="col-xxl-4 col-sm-12">
                <div>
                    <select wire:model="selectedStatus" style="cursor: pointer" class="form-select" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Status">
                        <option value=""> Semua</option>
                        <option value="Aktif"> Aktif</option>
                        <option value="Tidak Aktif"> Tidak Aktif</option>
                    </select>
                </div>
            </div>
        </div>
        <!--end row-->
        <div class="live-preview">
            <div class="table-responsive">
                <table class="table table-hover nowrap align-middle" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th class="text-uppercase" width="50px">status</th>
                            <th class="text-uppercase">jenjang</th>
                            <th class="text-uppercase">keterangan</th>
                            <th class="text-uppercase">status</th>
                            <th class="text-uppercase">aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jenjangs as $item)
                        <tr>
                            <td>
                                <div class="form-check ps-3 form-switch form-switch-md" dir="ltr" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Ubah Status">
                                    <input type="checkbox" class="form-check-input" id="customSwitchsizemd-{{ $item->ms_jenjang_id }}" 
                                        {{ $item->status == 'Aktif' ? 'checked' : '' }}
                                        wire:change="toggleStatus('{{ $item->ms_jenjang_id }}', $event.target.checked)">
                                </div>
                            </td>
                            <td>{{ $item->nama_jenjang }}</td>
                            <td>{{ $item->deskripsi }}</td>
                            <td class="{{ $item->status == 'Aktif' ? 'text-success' : 'text-danger' }}"><i class="ri-{{ $item->status == 'Aktif' ? 'checkbox' : 'close' }}-circle-line fs-17 align-middle"></i> {{ $item->status }}</td>
                            <td>
                                <ul class="list-inline hstack gap-2 mb-0">
                                    <li class="list-inline-item edit" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit Jenjang">
                                        <a href="#ModalEditJenjang" data-bs-toggle="modal" class="text-success d-inline-block edit-item-btn" wire:click.prevent="$emit('loadDataJenjang', {{ $item->ms_jenjang_id }})">
                                            <i class="ri-pencil-line fs-17 align-middle"></i> Edit
                                        </a>
                                    </li>

                                    <li class="list-inline-item delete" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Hapus Jenjang">
                                        <a href="#ModalDeleteJenjang" data-bs-toggle="modal" class="text-danger d-inline-block delete-item-btn" wire:click.prevent="$emit('confirmDelete', {{ $item->ms_jenjang_id }})">
                                            <i class="ri-delete-bin-5-line fs-17 align-middle"></i> Hapus
                                        </a>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="noresult text-center py-3">
                                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" 
                                                colors="primary:#405189,secondary:#08a88a" 
                                                style="width:75px;height:75px"></lord-icon>
                                        <h5 class="mt-2">Maaf, Tidak Ada Data yang Ditemukan</h5>
                                        <p class="text-muted mb-0">Kami telah mencari keseluruhan data, namun tidak ditemukan hasil yang sesuai.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $jenjangs->links() }}
            </div>
        </div>
    </div>
</div>