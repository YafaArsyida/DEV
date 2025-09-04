<div class="modal fade zoomIn" id="ModalDeleteProduk" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-5 text-center">
                <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                    colors="primary:#405189,secondary:#f06548"
                    style="width:90px;height:90px">
                </lord-icon>
                <div class="mt-4">
                    <h4 class="fs-semibold">Yakin ingin menghapus produk ini?</h4>
                    <p class="text-muted fs-14 mb-4">Produk akan terhapus permanen dari sistem.</p>
                    <div class="hstack gap-2 justify-content-center">
                        <button class="btn btn-link link-success fw-medium shadow-none"
                            data-bs-dismiss="modal">
                            <i class="ri-close-line me-1 align-middle"></i> Batal
                        </button>
                        <button class="btn btn-danger" wire:click="deleteProduk">
                            <i class="ri-delete-bin-line me-1 align-middle"></i> Ya, Hapus!
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>  