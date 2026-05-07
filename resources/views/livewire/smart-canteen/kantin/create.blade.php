<div wire:ignore.self class="modal fade" id="ModalAddKantin" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-light p-3">
                <h5 class="modal-title">Tambah Kantin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form wire:submit.prevent="save">
                <div class="modal-body">
                    <div class="row g-3">

                        <div class="col-lg-12">
                            <label class="form-label">Nama Kantin</label>
                            <input type="text" wire:model.defer="nama_kantin"
                                class="form-control @error('nama_kantin') is-invalid @enderror"
                                placeholder="Kantin Putra / Kantin Sehat / dll">

                            @error('nama_kantin')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-12">
                            <label class="form-label">Deskripsi</label>
                            <textarea wire:model.defer="deskripsi"
                                class="form-control @error('deskripsi') is-invalid @enderror"
                                placeholder="Opsional deskripsi kantin"></textarea>

                            @error('deskripsi')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <a href="javascript:void(0);" class="btn btn-link link-success shadow-none fw-medium" data-bs-dismiss="modal"><i
                            class="ri-close-line me-1 align-middle"></i> Tutup</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>

        </div>
    </div>
</div>