<div>
    <div wire:ignore.self class="modal fade" id="ModalKonfirmasiReset" tabindex="-1"
        aria-labelledby="resetPasswordLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                {{-- CLOSE BUTTON --}}
                <div class="modal-header border-0 pb-0">
                    <button type="button"
                        class="btn btn-light btn-icon rounded-circle ms-auto"
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
                                <lord-icon
                                    src="https://cdn.lordicon.com/dxjqoygy.json"
                                    trigger="loop"
                                    colors="primary:#dc3545,secondary:#dc3545"
                                    style="width:70px;height:70px">
                                </lord-icon>
                            </div>
                        </div>
                    </div>

                    {{-- TITLE --}}
                    <div class="mb-2">
                        <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill mb-3">
                            Konfirmasi Reset Password
                        </span>

                        <h3 class="fw-bold mb-2" id="resetPasswordLabel">
                            Reset Password Pengguna
                        </h3>

                        <p class="text-muted mb-0 lh-lg px-lg-4">
                            Password pengguna akan dikembalikan ke
                            password default. Pastikan tindakan ini
                            memang diperlukan.
                        </p>
                    </div>

                    {{-- INFORMATION --}}
                    <div class="alert alert-light border rounded-4 text-start mt-4 mb-0">
                        <div class="d-flex align-items-start gap-3">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <div class="avatar-title bg-danger-subtle text-danger rounded-circle">
                                        <i class="ri-lock-unlock-line fs-18"></i>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-semibold mb-1">
                                    Perhatian
                                </h6>

                                <p class="text-muted mb-0 fs-13">
                                    Setelah password direset, pengguna perlu
                                    menggunakan password default untuk
                                    login kembali.
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

                    <button type="button" class="btn btn-danger rounded-pill px-4" wire:click="resetPass">
                        <i class="ri-lock-unlock-line me-1"></i>
                        Ya, Reset Password
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>