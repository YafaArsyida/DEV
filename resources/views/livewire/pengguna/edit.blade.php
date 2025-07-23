<div>
    {{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
    <div wire:ignore.self class="modal fade" id="ModalEditPengguna" tabindex="-1" aria-labelledby="ModalAddSiswa" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title">edit data petugas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
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
                                    <option value="administrasi">Administrasi</option>
                                    <option value="superadmin">Super Admin</option>
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
                    <div class="modal-footer">
                        <a href="javascript:void(0);" class="btn btn-link link-success shadow-none fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Tutup</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
