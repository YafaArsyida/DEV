<div class="modal fade zoomIn" id="ModalDeleteKategori" tabindex="-1" aria-labelledby="deleteRecordLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-5 text-center">
                <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" 
                           trigger="loop" 
                           colors="primary:#405189,secondary:#f06548" 
                           style="width:90px;height:90px">
                </lord-icon>
                <div class="mt-4 text-center">
                    <h4 class="fs-semibold">Anda yakin ingin menghapus kategori ini?</h4>
                    <p class="text-muted fs-14 mb-4 pt-1">
                        Data akan dihapus secara permanen dari sistem dan tidak bisa dikembalikan.
                    </p>
                    <div class="hstack gap-2 justify-content-center remove">
                        <button class="btn btn-link link-success fw-medium text-decoration-none shadow-none" 
                                data-bs-dismiss="modal">
                            <i class="ri-close-line me-1 align-middle"></i> Batal
                        </button>
                        <button class="btn btn-danger" wire:click="deleteKategori">
                            <i class="ri-delete-bin-6-line me-1"></i> Ya, Hapus!
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
