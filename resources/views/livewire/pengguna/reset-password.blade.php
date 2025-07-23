<div>
    <div class="modal fade zoomIn" id="ModalKonfirmasiReset" tabindex="-1" aria-labelledby="deleteRecordLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-5 text-center">
                    <lord-icon src="https://cdn.lordicon.com/dxjqoygy.json" trigger="loop" 
                            colors="primary:#405189,secondary:#f06548" style="width:90px;height:90px">
                    </lord-icon>

                    <div class="mt-4 text-center">
                        <h4 class="fs-semibold">Reset Password Pengguna</h4>
                        <p class="text-muted fs-14 mb-4 pt-1">Anda yakin ingin mereset password pengguna ini? 
                            Password akan diubah menjadi default.</p>
                        <div class="hstack gap-2 justify-content-center remove">
                            <button class="btn btn-link link-success fw-medium text-decoration-none shadow-none" 
                                    data-bs-dismiss="modal">
                                <i class="ri-close-line me-1 align-middle"></i> Batal
                            </button>
                            <button class="btn btn-danger" wire:click="resetPass">
                                <i class="ri-check-line me-1 align-middle"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
