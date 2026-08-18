<div wire:ignore.self class="modal fade" id="ModalEditPengguna" tabindex="-1" aria-labelledby="ModalAddSiswa" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-user-line"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">
                            Perbarui Petugas
                        </h5>
                    </div>
                </div>

                <button type="button"
                    class="btn btn-light btn-icon rounded-circle ms-auto"
                    data-bs-dismiss="modal" aria-label="Close" id="close-modal">
                    <i class="ri-close-line fs-18"></i>
                </button>
            </div>
            <form wire:submit.prevent="updatePengguna">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-lg-6">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" wire:model="nama" id="nama" class="form-control" placeholder="Nama lengkap petugas" />
                            @error('nama') 
                                <footer class="text-danger mt-0">{{ $message }}</footer>
                            @enderror
                        </div>
                        <div class="col-lg-6">
                            <label for="peran" class="form-label">Peran</label>
                            <select id="peran" wire:model="peran" class="form-select">
                                <option value="">Pilih Peran</option>
                                <option value="SUPERADMIN">SUPERADMIN</option>
                                <option value="ADMIN">ADMINISTRASI</option>
                                <option value="KANTIN">PETUGAS KANTIN</option>
                                <option value="KOPERASI">PETUGAS KOPERASI</option>
                            </select>
                            @error('peran') 
                                <footer class="text-danger mt-0">{{ $message }}</footer>
                            @enderror
                        </div>
                        <div class="col-lg-6">
                            <label for="email" class="form-label">Email/Username</label>
                            <input type="text" wire:model="email" id="email" class="form-control" placeholder="user@example.com/jajangsukma" />
                            @error('email') 
                                <footer class="text-danger mt-0">{{ $message }}</footer>
                            @enderror
                        </div>
                        <div class="col-lg-6">
                            <label for="password" class="form-label">Password Baru</label>
                            <input type="password" class="form-control" id="password" wire:model.defer="password">
                            @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-lg-12">
                            <label for="ms_jenjang_id" class="form-label">Akses Jenjang</label>
                            <div class="form-check">
                            @foreach ($select_jenjang as $item)
                                <input 
                                    class="form-check-input" 
                                    type="checkbox" 
                                    id="edit_{{ $item->ms_jenjang_id }}" 
                                    wire:model="ms_jenjang_id" 
                                    value="{{ $item->ms_jenjang_id }}" 
                                    @if(in_array($item->ms_jenjang_id, $selectedJenjang)) checked @endif
                                >
                                <label class="form-check-label" for="edit_{{ $item->ms_jenjang_id }}">
                                    {{ $item->nama_jenjang }}
                                </label>
                                <br>
                            @endforeach
                            </div>
                            @error('ms_jenjang_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="ri-save-3-line me-1"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>