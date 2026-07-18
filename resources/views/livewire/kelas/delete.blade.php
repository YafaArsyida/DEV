<div>
    <div wire:ignore.self class="modal fade" id="ModalDeleteKelas" tabindex="-1" aria-labelledby="deleteRecordLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                {{-- CLOSE BUTTON --}}
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn btn-light btn-icon rounded-circle ms-auto" 
                        data-bs-dismiss="modal" aria-label="Close">
                        <i class="ri-close-line fs-18"></i>
                    </button>
                </div>

                {{-- BODY --}}
                <div class="modal-body px-4 pb-5 pt-2 text-center">
                    {{-- ICON --}}
                    <div class="mb-4">
                        <div class="avatar-xl mx-auto">
                            <div class="avatar-title bg-danger-subtle text-danger rounded-circle">
                                <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" 
                                    trigger="loop" colors="primary:#dc3545,secondary:#dc3545" 
                                    style="width:70px;height:70px">
                                </lord-icon>
                            </div>
                        </div>
                    </div>

                    {{-- TITLE --}}
                    <div class="mb-2">
                        <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill mb-3">
                            Konfirmasi Penghapusan
                        </span>

                        <h3 class="fw-bold mb-2" id="deleteRecordLabel">
                            Hapus Data Kelas
                        </h3>

                        <p class="text-muted mb-0 lh-lg px-lg-4">
                            Data kelas akan dihapus secara permanen dari sistem. Tindakan ini tidak dapat dibatalkan.
                        </p>
                    </div>

                    {{-- WARNING --}}
                    <div class="alert alert-light border rounded-4 text-start mt-4 mb-0">
                        <div class="d-flex align-items-start gap-3">
                            <div class="flex-shrink-0">
                                <i class="ri-error-warning-line text-warning fs-20"></i>
                            </div>

                            <div>
                                <h6 class="fw-semibold mb-1">Perhatian</h6>
                                <p class="text-muted mb-0 fs-13">
                                    Pastikan data kelas tidak memiliki penempatan
                                    atau relasi data penting sebelum melakukan
                                    penghapusan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="modal-footer border-0 pt-0 px-4 pb-4 justify-content-center">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>
                        Batal
                    </button>

                    <button type="button" class="btn btn-danger rounded-pill px-4" wire:click="deleteKelas">
                        <i class="ri-delete-bin-6-line me-1"></i>
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>