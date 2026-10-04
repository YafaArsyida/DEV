<div wire:ignore.self class="modal fade" id="ModalEditJenjang" tabindex="-1" aria-labelledby="ModalEditJenjangLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-graduation-cap-line"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">
                            Perbarui Jenjang
                        </h5>

                        <small class="text-muted">Perbarui informasi jenjang pendidikan.</small>
                    </div>
                </div>

                <button type="button"
                    class="btn btn-light btn-icon rounded-circle ms-auto"
                    data-bs-dismiss="modal" aria-label="Close" id="close-modal">
                    <i class="ri-close-line fs-18"></i>
                </button>
            </div>
            <form wire:submit.prevent="updateJenjang">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-lg-6">
                            <label for="nama_jenjang" class="form-label">Nama Jenjang</label>
                            <input type="text" id="nama_jenjang" wire:model="nama_jenjang" class="form-control">
                            @error('nama_jenjang') 
                                <footer class="text-danger mt-0">{{ $message }}</footer> 
                            @enderror
                        </div>
                        <div class="col-lg-6">
                            <label for="urutan" class="form-label">Urutan</label>
                            <input type="number" wire:model="urutan" class="form-control">
                            @error('urutan') 
                                <footer class="text-danger mt-0">{{ $message }}</footer> 
                            @enderror
                        </div>
                        <div class="col-lg-12">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea id="deskripsi" wire:model="deskripsi" class="form-control"></textarea>
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