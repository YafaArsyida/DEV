{{-- Nothing in the world is as soft and yielding as water. --}}
<div>
    <div wire:ignore.self class="modal fade" id="ModalEditJabatan" tabindex="-1" aria-labelledby="ModalEditJabatanLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title" id="ModalEditJabatanLabel">Edit Jabatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="updateJabatan">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-lg-12">
                                <label for="nama_jabatan" class="form-label">Nama Jabatan</label>
                                <input type="text" id="nama_jabatan" wire:model="nama_jabatan" class="form-control">
                                @error('nama_jabatan') 
                                    <footer class="text-danger mt-0">{{ $message }}</footer> 
                                @enderror
                            </div>
                            <div class="col-lg-12">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea id="deskripsi" wire:model="deskripsi" class="form-control"></textarea>
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

