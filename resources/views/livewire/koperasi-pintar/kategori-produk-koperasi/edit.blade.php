<div wire:ignore.self class="modal fade" id="ModalEditKategoriKoperasi" tabindex="-1" aria-labelledby="ModalEditKategoriLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title" id="ModalEditKategoriLabel">Edit Kategori Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit.prevent="update">
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Nama kategori -->
                        <div class="col-lg-12">
                            <label for="nama_kategori_produk_koperasi" class="form-label">Nama Kategori</label>
                            <input type="text" wire:model.defer="nama_kategori_produk_koperasi" id="nama_kategori_produk_koperasi"
                                   class="form-control" placeholder="Makanan, Minuman, Snack..." />
                            @error('nama_kategori_produk_koperasi')
                                <footer class="text-danger mt-0">{{ $message }}</footer>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="col-lg-12">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <input type="text" wire:model.defer="deskripsi" id="deskripsi" class="form-control"
                                   placeholder="Keterangan singkat kategori..." />
                            @error('deskripsi')
                                <footer class="text-danger mt-0">{{ $message }}</footer>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <a href="javascript:void(0);" class="btn btn-link link-success shadow-none fw-medium"
                       data-bs-dismiss="modal">
                        <i class="ri-close-line me-1 align-middle"></i> Tutup
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-save-3-line me-1"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
