<div class="modal fade zoomIn" id="ModalDeleteKantin" tabindex="-1" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-5 text-center">
                <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                    colors="primary:#405189,secondary:#f06548" style="width:90px;height:90px">
                </lord-icon>

                <div class="mt-4">
                    <h4 class="fs-semibold">Hapus Kantin?</h4>
                    <p class="text-muted">
                        Data kantin akan dihapus permanen dan tidak dapat dikembalikan.
                    </p>

                    <div class="hstack gap-2 justify-content-center">
                        <button class="btn btn-light" data-bs-dismiss="modal">
                            Batal
                        </button>

                        <button class="btn btn-danger" wire:click="deleteKantin" wire:loading.attr="disabled">
                            Ya, Hapus
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>