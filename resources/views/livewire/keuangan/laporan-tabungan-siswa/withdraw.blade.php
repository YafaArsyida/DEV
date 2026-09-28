<div wire:ignore.self class="modal fade zoomIn" id="WithdrawTabungan" tabindex="-1" aria-labelledby="withdrawTabunganLabel" aria-hidden="true">

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
                        Kosongkan Saldo Tabungan
                        {{ $namaKelas ? 'Kelas '.$namaKelas : 'Seluruh Siswa' }}?
                    </h3>

                    <p class="text-muted mb-0 lh-lg px-lg-4">
                        Tindakan ini akan melakukan <strong>withdraw seluruh saldo tabungan</strong>
                        {{ $namaKelas ? 'kelas '.$namaKelas : 'semua siswa' }}.
                        Setelah diproses, saldo seluruh siswa menjadi <strong>Rp0</strong> dan
                        proses ini tidak dapat dibatalkan.
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

                            <ul class="mb-0 ps-3 text-muted">
                                <li>Unduh laporan saldo sebagai arsip.</li>
                                <li>Pastikan dana telah disiapkan untuk wali murid.</li>
                                <li>Proses ini tidak dapat dibatalkan.</li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>

            {{-- FOOTER --}}
            <div class="modal-footer border-0 pt-0 px-4 pb-4 flex-column">

                {{-- Download --}}
                <div class="w-100 mb-3">

                    <button
                        type="button"
                        class="btn btn-outline-primary w-100 rounded-pill"
                        wire:click="cetakSaldo">

                        <i class="ri-download-cloud-2-line me-1"></i>
                        Unduh Laporan Saldo Terlebih Dahulu

                    </button>

                </div>

                {{-- Checklist --}}
                <div class="form-check w-100 mb-3">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        id="arsipDownloaded"
                        wire:model="arsipDownloaded">

                    <label class="form-check-label text-muted" for="arsipDownloaded">
                        Saya telah mengunduh laporan dan siap melakukan proses withdraw.
                    </label>

                </div>

                {{-- Action --}}
                <div class="d-flex justify-content-center gap-2 w-100">

                    <button
                        type="button"
                        class="btn btn-light rounded-pill px-4"
                        data-bs-dismiss="modal">

                        <i class="ri-close-line me-1"></i>
                        Batal

                    </button>

                    <button
                        type="button"
                        class="btn btn-danger rounded-pill px-4"
                        wire:click="withdrawTabunganSiswa"
                        @disabled(!$arsipDownloaded)>

                        <i class="ri-delete-bin-6-line me-1"></i>
                        Ya, Kosongkan Saldo

                    </button>

                </div>

            </div>

        </div>
    </div>
    <script>
        window.addEventListener('show-withdraw-modal', () => {
            const modal = new bootstrap.Modal(
                document.getElementById('WithdrawTabungan')
            );

            modal.show();
        });
    </script>
</div>