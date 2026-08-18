<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header">
        <div class="d-flex align-items-center flex-wrap gap-3">
            {{-- Judul --}}
            <div class="flex-grow-1">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-group-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1">
                            Petugas Administrasi
                        </h5>

                        <small class="text-muted">
                            Kelola data petugas administrasi sekolah.
                        </small>
                    </div>
                </div>
            </div>

            {{-- Tombol --}}
            <div class="flex-shrink-0">
                <button type="button" data-bs-toggle="modal" data-bs-target="#ModalAddPengguna"
                    class="btn btn-primary rounded-pill px-4 d-inline-flex align-items-center gap-1">
                    <i class="ri-add-line align-bottom"></i>
                    <span>
                        Petugas Baru
                    </span>
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3 mb-4">
            <div class="col-xxl-12 col-sm-12">
                <label for="searchData" class="form-label small text-muted text-uppercase fw-medium mb-2">Pencarian</label>
                <div class="search-box">
                    <input type="text" id="searchData" class="form-control search" wire:model.debounce.300ms="search"
                        placeholder="Cari nama, deskripsi, atau lainnya...">
                    <i class="ri-search-line search-icon"></i>
                </div>
            </div>
            {{-- <div class="col-xxl-4 col-sm-12">
                <div>
                    <select wire:model="selectedStatus" style="cursor: pointer" class="form-select" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Status">
                        <option value=""> Semua</option>
                        <option value="Aktif"> Aktif</option>
                        <option value="Tidak Aktif"> Tidak Aktif</option>
                    </select>
                </div>
            </div> --}}
        </div>
        <!--end row-->
        <div class="live-preview">
            <div class="table-responsive">
                <table class="table table-hover table-nowrap align-middle" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th class="text-uppercase text-center" width="50px">no</th>
                            <th class="text-uppercase text-center" width="50px">hapus</th>
                            <th class="text-uppercase">petugas</th>
                            <th class="text-uppercase">username</th>
                            <th class="text-uppercase">peran</th>
                            <th class="text-uppercase">akses jenjang</th>
                            <th class="text-uppercase text-center">aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($pengguna as $index => $user)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">
                                <!-- Hapus Pengguna -->
                                <a href="#ModalDeletePengguna" data-bs-toggle="modal" class="text-danger d-inline-block remove-item-btn" 
                                wire:click.prevent="$emit('deletePengguna', {{ $user['ms_pengguna_id'] }})" 
                                data-bs-trigger="hover" data-bs-placement="top" title="Hapus Pengguna">
                                    <i class="ri-delete-bin-5-fill fs-16"></i>
                                </a>
                            </td>
                            <td>{{ $user['nama'] }}</td>
                            <td>{{ $user['email'] }}</td>
                            <td class="text-uppercase text-secondary">{{ $user['peran'] }}</td>
                            <td>{{ implode(', ', $user['aksesJenjang']) }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    {{-- DETAIL --}}
                                    <a href="#ModalDetailPengguna" data-bs-toggle="modal"
                                        class="btn btn-soft-primary btn-sm rounded-pill px-3"
                                        title="Detail Pengguna" wire:click.prevent="$emit('detailPengguna', {{ $user['ms_pengguna_id'] }})">

                                        <i class="ri-eye-line me-1"></i>
                                        Detail
                                    </a>

                                    {{-- EDIT --}}
                                    <a href="#ModalEditPengguna" data-bs-toggle="modal"
                                        class="btn btn-primary btn-sm rounded-pill px-3"
                                        title="Edit Pengguna" wire:click.prevent="$emit('editPengguna', {{ $user['ms_pengguna_id'] }})">
                                        <i class="ri-mark-pen-line me-1"></i> Edit
                                    </a>

                                    {{-- RESET PASSWORD --}}
                                    <a href="#ModalKonfirmasiReset"
                                        data-bs-toggle="modal" class="btn btn-soft-danger btn-sm rounded-pill px-3"
                                        title="Reset Password" wire:click.prevent="$emit('resetPassword', {{ $user['ms_pengguna_id'] }})">

                                        <i class="ri-lock-unlock-line me-1"></i>
                                        Reset
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
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
                {{-- {{ $users->links() }} --}}
            </div>
        </div>
    </div>
</div>