<div wire:ignore.self class="modal fade" id="ModalAddJenjang" tabindex="-1" aria-labelledby="ModalAddJenjang" aria-hidden="true">
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
                            Jenjang Baru
                        </h5>

                        {{-- <p class="text-muted mb-0 fs-13">
                            Tambahkan periode tahun ajaran baru.
                        </p> --}}
                    </div>
                </div>

                <button type="button"
                    class="btn btn-light btn-icon rounded-circle ms-auto"
                    data-bs-dismiss="modal" aria-label="Close" id="close-modal">
                    <i class="ri-close-line fs-18"></i>
                </button>
            </div>
            <form id="formAddJenjang" wire:submit.prevent="save">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-lg-6">
                            <label for="nama_jenjang" class="form-label">Nama Jenjang</label>
                            <input type="text" wire:model="nama_jenjang" id="nama_jenjang"  class="form-control" placeholder="SD/SMP/SMA" />
                            @error('nama_jenjang') 
                                <footer class="text-danger mt-0">{{ $message }}</footer>
                            @enderror
                        </div>
                        <div class="col-lg-6">
                            <label for="urutan" class="form-label">Urutan</label>
                            <input type="number" wire:model="urutan" class="form-control" placeholder="1, 2, 3, ..." />
                            @error('urutan') 
                                <footer class="text-danger mt-0">{{ $message }}</footer>
                            @enderror
                        </div>
                        <div class="col-lg-12">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <input type="text" wire:model="deskripsi" class="form-control" placeholder="SD N Alamanah/SMP Al..." />
                            @error('deskripsi') 
                                <footer class="text-danger mt-0">{{ $message }}</footer>
                            @enderror
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
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>