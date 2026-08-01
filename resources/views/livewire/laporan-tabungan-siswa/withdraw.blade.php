<div class="modal fade zoomIn" id="WithdrawTabungan"
    tabindex="-1"
    aria-labelledby="withdrawTabunganLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            {{-- CLOSE BUTTON --}}
            <div class="modal-header border-0 pb-0">
                <button type="button"
                    class="btn btn-light btn-icon rounded-circle ms-auto"
                    data-bs-dismiss="modal"
                    aria-label="Close">
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
                                src="https://cdn.lordicon.com/qhviklyi.json"
                                trigger="loop"
                                colors="primary:#dc3545,secondary:#f06548"
                                style="width:70px;height:70px">
                            </lord-icon>
                        </div>
                    </div>
                </div>

                {{-- TITLE --}}
                <div class="mb-2">

                    <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill mb-3">
                        Konfirmasi Withdraw
                    </span>

                    <h3 class="fw-bold mb-2" id="withdrawTabunganLabel">
                        Kosongkan Saldo Tabungan Siswa?
                    </h3>

                    <p class="text-muted mb-0 lh-lg px-lg-4">
                        Seluruh saldo Tabungan Siswa akan dikosongkan melalui
                        proses withdraw. Pastikan dana telah disiapkan untuk
                        diserahkan kepada wali murid sesuai dengan saldo yang
                        tercatat.
                    </p>

                </div>

                {{-- INFORMATION --}}
                <div class="alert alert-danger border rounded-4 text-start mt-4 mb-0">
                    <div class="d-flex align-items-start gap-3">
                        <div class="flex-shrink-0">
                            <i class="ri-error-warning-line text-danger fs-20"></i>
                        </div>

                        <div>
                            <h6 class="fw-semibold mb-1">
                                Perhatian
                            </h6>

                            <p class="text-muted mb-0 fs-13">
                                Sebelum melakukan withdraw, pastikan Anda
                                telah mengunduh laporan Tabungan Siswa sebagai
                                arsip. Setelah proses ini dilakukan, saldo
                                akan menjadi <strong>Rp0</strong> dan dana
                                harus diserahkan kepada wali murid.
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            {{-- FOOTER --}}
            <div class="modal-footer border-0 pt-0 px-4 pb-4 justify-content-center">

                <button type="button"
                    class="btn btn-light rounded-pill px-4"
                    data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>
                    Batal
                </button>

                <button type="button"
                    class="btn btn-danger rounded-pill px-4"
                    wire:click="confirmWithdraw"
                    data-bs-dismiss="modal">

                    <i class="ri-delete-bin-6-line me-1"></i>
                    Ya, Kosongkan Saldo
                </button>

            </div>

        </div>
    </div>
</div>