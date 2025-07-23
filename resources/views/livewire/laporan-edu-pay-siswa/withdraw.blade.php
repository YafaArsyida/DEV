{{-- Nothing in the world is as soft and yielding as water. --}}
<div>
    <div class="modal fade zoomIn" id="WithdrawEduPay" tabindex="-1" aria-labelledby="exportRecordLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-5 text-center">
                    <lord-icon src="https://cdn.lordicon.com/qhviklyi.json" trigger="loop" colors="primary:#405189,secondary:#0ab39c" style="width:90px;height:90px"></lord-icon>
                    <div class="mt-4 text-center">
                        <h4 class="fs-semibold">Konfirmasi Withdraw</h4>
                        <p class="text-muted fs-14 mb-4 pt-1">
                            Yakin ingin mengosongkan saldo EduPay? Unduh laporan terlebih dulu, lalu serahkan dana tunai ke wali murid.
                        </p>
                        <div class="hstack gap-2 justify-content-center remove">
                            <button class="btn btn-link link-success fw-medium text-decoration-none shadow-none" data-bs-dismiss="modal">
                                <i class="ri-close-line me-1 align-middle"></i> Batal
                            </button>
                            <button class="btn btn-danger" wire:click="confirmWithdraw" data-bs-dismiss="modal">Ya, Kosongkan!</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
