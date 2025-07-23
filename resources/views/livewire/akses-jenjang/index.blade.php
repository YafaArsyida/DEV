<div class="card">
    <div class="card-header border-0">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1">Petugas Administrasi</h5>
            <div class="flex-shrink-0">
                <div class="d-flex gap-2 flex-wrap">
                    <button 
                        class="btn btn-primary"
                        data-bs-toggle="modal" 
                        data-bs-target="#ModalAddPengguna" 
                        data-bs-trigger="hover" 
                        data-bs-placement="top" 
                        title="Petugas Baru">
                        <i class="ri-group-line"></i> Petugas Baru
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body border border-dashed border-end-0 border-start-0">
        <div class="row g-3">
            <div class="col-xxl-12 col-sm-12">
                <div class="search-box">
                    <input type="text" class="form-control search" wire:model.debounce.300ms="search" placeholder="cari nama, deskripsi atau lainnya...">
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
    </div>
    <div class="card-body">
        <div class="live-preview">
            <div class="table-responsive">
                <table class="table table-hover nowrap align-middle" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th class="text-uppercase" width="50px">no</th>
                            <th class="text-uppercase" width="50px">hapus</th>
                            <th class="text-uppercase">petugas</th>
                            <th class="text-uppercase">username</th>
                            <th class="text-uppercase">peran</th>
                            <th class="text-uppercase">akses jenjang</th>
                            <th class="text-uppercase">aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($pengguna as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <!-- Hapus Pengguna -->
                                <a href="#ModalDeletePengguna" data-bs-toggle="modal" class="text-danger d-inline-block remove-item-btn" 
                                wire:click.prevent="$emit('deletePengguna', {{ $user['ms_pengguna_id'] }})" 
                                data-bs-trigger="hover" data-bs-placement="top" title="Hapus Pengguna">
                                    <i class="ri-delete-bin-5-fill fs-16"></i>
                                </a>
                            </td>
                            <td style="white-space: nowrap;">{{ $user['nama'] }}</td>
                            <td>{{ $user['email'] }}</td>
                            <td class="text-uppercase text-secondary">{{ $user['peran'] }}</td>
                            <td style="white-space: nowrap;">{{ implode(', ', $user['aksesJenjang']) }}</td>
                            <td style="white-space: nowrap;">
                                <!-- Detail Pengguna -->
                                <a href="#ModalDetailPengguna" data-bs-toggle="modal" class="text-secondary d-inline-block detail-item-btn" 
                                wire:click.prevent="$emit('detailPengguna', {{ $user['ms_pengguna_id'] }})">
                                    <i class="ri-eye-line fs-17 align-middle"></i> Detail
                                </a>
                                
                                <!-- Edit Pengguna -->
                                <a href="#ModalEditPengguna" data-bs-toggle="modal" class="text-warning d-inline-block detail-item-btn ms-1" 
                                wire:click.prevent="$emit('editPengguna', {{ $user['ms_pengguna_id'] }})">
                                    <i class="ri-mark-pen-line fs-17 align-middle"></i> Edit
                                </a>

                                <!-- Reset Password -->
                                <a href="#ModalKonfirmasiReset" data-bs-toggle="modal" class="text-danger d-inline-block detail-item-btn ms-1" 
                                wire:click.prevent="$emit('resetPassword', {{ $user['ms_pengguna_id'] }})">
                                    <i class="ri-lock-unlock-line fs-17 align-middle"></i> Reset
                                </a>
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