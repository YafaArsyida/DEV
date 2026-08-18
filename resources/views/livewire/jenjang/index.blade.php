<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header">
        <div class="d-flex align-items-center flex-wrap gap-3">
            <div class="flex-grow-1">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-graduation-cap-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1">
                            Jenjang
                        </h5>

                        <small class="text-muted">
                            Kelola jenjang pendidikan sekolah.
                        </small>
                    </div>
                </div>
            </div>

            {{-- ACTION --}}
            <div class="flex-shrink-0">
                <button
                    type="button" data-bs-toggle="modal" id="create-btn" data-bs-target="#ModalAddJenjang"
                    class="btn btn-primary rounded-pill px-4 d-inline-flex align-items-center gap-1">
                    <i class="ri-add-line align-bottom"></i>
                    <span>
                        Jenjang Baru
                    </span>
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3 align-items-end mb-4">
            {{-- PENCARIAN --}}
            <div class="col-xxl-8 col-md-12">
                <label for="searchData" class="form-label small text-muted text-uppercase fw-medium mb-2">
                    Pencarian
                </label>

                <div class="search-box">
                    <input type="text" id="searchData" class="form-control search" wire:model.debounce.300ms="search"
                        placeholder="Cari nama, deskripsi, atau lainnya...">
                    <i class="ri-search-line search-icon"></i>
                </div>
            </div>

            {{-- STATUS --}}
            <div class="col-xxl-4 col-md-12">
                <label for="statusSelect" class="form-label small text-muted text-uppercase fw-medium mb-2">
                    Status
                </label>

                <select id="statusSelect" wire:model="selectedStatus"
                    class="form-select" style="cursor: pointer"
                    data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Status">
                    <option value="">Semua</option>
                    <option value="Aktif">Aktif</option>
                    <option value="Tidak Aktif">Tidak Aktif</option>
                </select>
            </div>
        </div>
        <!--end row-->
        <div class="live-preview">
            <div class="table-responsive">
                <table class="table table-hover table-nowrap align-middle" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th class="text-uppercase text-center" width="50px">status</th>
                            <th class="text-uppercase">jenjang</th>
                            <th class="text-uppercase">keterangan</th>
                            <th class="text-uppercase text-center">aksi</th>
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
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    {{-- EDIT --}}
                                    <a href="#ModalEditJenjang" data-bs-toggle="modal"
                                        class="btn btn-primary btn-sm rounded-pill px-3"
                                        title="Edit Jenjang" wire:click.prevent="$emit('loadDataJenjang', {{ $item->ms_jenjang_id }})">
                                        <i class="ri-mark-pen-line me-1"></i> Edit
                                    </a>

                                    {{-- HAPUS --}}
                                    <a href="#ModalDeleteJenjang" data-bs-toggle="modal"
                                        class="btn btn-soft-danger btn-sm rounded-pill px-3"
                                        title="Hapus Jenjang" wire:click.prevent="$emit('confirmDelete', {{ $item->ms_jenjang_id }})">
                                        <i class="ri-delete-bin-5-line me-1"></i>
                                        Hapus
                                    </a>
                                </div>
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