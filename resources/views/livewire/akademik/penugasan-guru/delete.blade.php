<div>
    <div wire:ignore.self class="modal fade" id="ModalDeletePenugasanGuru" tabindex="-1"
        aria-labelledby="deletePenugasanGuruLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn btn-light btn-icon rounded-circle ms-auto"
                        data-bs-dismiss="modal" aria-label="Tutup">
                        <i class="ri-close-line fs-18"></i>
                    </button>
                </div>

                <div class="modal-body px-4 pb-4 pt-2 text-center">
                    <div class="mb-4">
                        <div class="avatar-xl mx-auto">
                            <div class="avatar-title bg-danger-subtle text-danger rounded-circle">
                                <i class="ri-delete-bin-6-line fs-32"></i>
                            </div>
                        </div>
                    </div>

                    <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill mb-3">
                        Konfirmasi Penghapusan
                    </span>
                    <h3 class="fw-bold mb-2" id="deletePenugasanGuruLabel">Hapus Penugasan Guru</h3>
                    <p class="text-muted mb-0 lh-lg px-lg-4">
                        Penugasan <strong>{{ $namaGuru }}</strong>
                        @if ($namaMapel)
                            untuk <strong>{{ $namaMapel }}</strong>
                        @endif
                        di kelas <strong>{{ $namaKelas }}</strong>
                        akan dihapus.
                    </p>

                    <div class="alert alert-light border rounded-4 text-start mt-4 mb-0">
                        <div class="d-flex align-items-start gap-3">
                            <i class="ri-error-warning-line text-warning fs-20"></i>
                            <div>
                                <h6 class="fw-semibold mb-1">Perhatian</h6>
                                <p class="text-muted mb-0 fs-13">
                                    Penugasan yang sudah memiliki jadwal mengajar tidak dapat dihapus.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 pt-0 px-4 pb-4 justify-content-center">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i> Batal
                    </button>
                    <button type="button" class="btn btn-danger rounded-pill px-4"
                        wire:click="deletePenugasanGuru" wire:loading.attr="disabled" wire:target="deletePenugasanGuru">
                        <span wire:loading.remove wire:target="deletePenugasanGuru">
                            <i class="ri-delete-bin-6-line me-1"></i> Ya, Hapus
                        </span>
                        <span wire:loading wire:target="deletePenugasanGuru">
                            <span class="spinner-border spinner-border-sm me-1"></span> Menghapus...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
