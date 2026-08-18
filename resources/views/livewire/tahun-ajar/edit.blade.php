<div wire:ignore.self class="modal fade" id="ModalEditTahunAjar" tabindex="-1" aria-labelledby="ModalEditTahunAjar" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-calendar-line"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">
                            Perbarui Tahun Ajar
                        </h5>

                        {{-- <p class="text-muted mb-0 fs-13">
                            Periode periode tahun ajaran.
                        </p> --}}
                    </div>
                </div>

                <button type="button"
                    class="btn btn-light btn-icon rounded-circle ms-auto"
                    data-bs-dismiss="modal" aria-label="Close" id="close-modal">
                    <i class="ri-close-line fs-18"></i>
                </button>
            </div>
            <form wire:submit.prevent="updateTahunAjar">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-lg-8">
                            <label class="form-label">Nama Tahun Ajar</label>
                            <input type="text" wire:model="nama_tahun_ajar" id="nama_tahun_ajar" class="form-control" placeholder="2024 - 2025..." />
                            @error('nama_tahun_ajar') 
                                <footer class="text-danger mt-0">{{ $message }}</footer>
                            @enderror
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label">Urutan</label>
                            <input type="number" wire:model="urutan" class="form-control" placeholder="1,2,3...." />
                            @error('urutan') 
                                <footer class="text-danger mt-0">{{ $message }}</footer>
                            @enderror
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" wire:model="tanggal_mulai" class="form-control" />
                            @error('tanggal_mulai') 
                                <footer class="text-danger mt-0">{{ $message }}</footer>
                            @enderror
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" wire:model="tanggal_selesai" class="form-control" />
                            @error('tanggal_selesai') 
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
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>