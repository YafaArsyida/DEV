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
                            <div class="col-12">
                                <label for="nama_jabatan" class="form-label fw-semibold">
                                    Nama Jabatan <span class="text-danger">*</span>
                                </label>
                                <input type="text" wire:model.defer="nama_jabatan" id="nama_jabatan"
                                    class="form-control @error('nama_jabatan') is-invalid @enderror" placeholder="Contoh: Kepala Sekolah" />
                        
                                @error('nama_jabatan')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="deskripsi" class="form-label fw-semibold">
                                    Deskripsi
                                </label>
                                <textarea wire:model.defer="deskripsi" id="deskripsi"
                                    class="form-control @error('deskripsi') is-invalid @enderror" rows="3"
                                    placeholder="Contoh: Bertanggung jawab atas operasional sekolah"></textarea>
                        
                                @error('deskripsi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
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

