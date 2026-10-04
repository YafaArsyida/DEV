<div wire:ignore.self
    class="modal fade"
    id="ModalEditKelas"
    tabindex="-1"
    aria-labelledby="ModalEditKelasLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            {{-- HEADER --}}
            <div class="modal-header border-0 px-4 pt-4 pb-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-building-4-line"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1" id="ModalEditKelasLabel">Perbarui Data Kelas</h5>
                        <small class="text-muted">Periksa kembali informasi kelas sebelum disimpan.</small>
                    </div>
                </div>

                <button type="button"
                    class="btn btn-light btn-icon rounded-circle"
                    data-bs-dismiss="modal"
                    aria-label="Tutup">
                    <i class="ri-close-line fs-18"></i>
                </button>
            </div>

            <form wire:submit.prevent="updateKelas">
                {{-- BODY --}}
                <div class="modal-body px-4 pt-2 pb-4">
                    <div class="d-flex justify-content-end mb-3">
                        <small class="text-muted">
                            <span class="text-danger">*</span> Wajib diisi
                        </small>
                    </div>

                    <section>
                        <div class="mb-3">
                            <h6 class="fw-semibold mb-1">Data Kelas</h6>
                            <small class="text-muted">Nama dan urutan digunakan untuk identifikasi kelas.</small>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-8">
                                <label for="edit_nama_kelas" class="form-label">
                                    Nama Kelas <span class="text-danger" aria-hidden="true">*</span>
                                </label>
                                <input type="text"
                                    wire:model.defer="nama_kelas"
                                    id="edit_nama_kelas"
                                    class="form-control @error('nama_kelas') is-invalid @enderror"
                                    placeholder="Contoh: 7 A atau VII D">
                                @error('nama_kelas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="edit_urutan" class="form-label">
                                    Urutan <span class="text-danger" aria-hidden="true">*</span>
                                </label>
                                <input type="number"
                                    wire:model.defer="urutan"
                                    id="edit_urutan"
                                    class="form-control @error('urutan') is-invalid @enderror"
                                    placeholder="1, 2, 3, ...">
                                @error('urutan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="edit_deskripsi" class="form-label">Deskripsi</label>
                                <textarea wire:model.defer="deskripsi"
                                    id="edit_deskripsi"
                                    class="form-control @error('deskripsi') is-invalid @enderror"
                                    rows="3"
                                    placeholder="Keterangan tambahan tentang kelas"></textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </section>
                </div>

                {{-- FOOTER --}}
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