
{{-- MODAL KONFIRMASI DITERIMA --}}
<div
    wire:ignore.self
    class="modal fade"
    id="ModalKonfirmasiDiterima"
    tabindex="-1"
    aria-labelledby="diterimaLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            {{-- HEADER --}}
            <div class="modal-header border-0 pb-0">
                <button
                    type="button"
                    class="btn btn-light btn-icon rounded-circle ms-auto"
                    data-bs-dismiss="modal"
                    aria-label="Tutup"
                >
                    <i class="ri-close-line fs-18"></i>
                </button>
            </div>

            {{-- BODY --}}
            <div class="modal-body px-4 pb-5 pt-2 text-center">

                <div class="mb-4">
                    <div class="avatar-xl mx-auto">
                        <div class="avatar-title bg-success-subtle text-success rounded-circle">
                            <lord-icon
                                src="https://cdn.lordicon.com/guqkthkk.json"
                                trigger="loop"
                                colors="primary:#198754,secondary:#198754"
                                style="width:70px;height:70px"
                            >
                            </lord-icon>
                        </div>
                    </div>
                </div>

                <div class="mb-2">
                    <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill mb-3">
                        Konfirmasi Penerimaan
                    </span>

                    <h3 class="fw-bold mb-2" id="diterimaLabel">
                        Terima Pendaftaran?
                    </h3>

                    <p class="text-muted mb-0 lh-lg px-lg-3">
                        Pastikan data calon siswa dan dokumen persyaratan
                        sudah diperiksa. Pendaftaran akan ditandai
                        <strong>diterima</strong> setelah Anda mengonfirmasi.
                    </p>
                </div>

                {{-- PERINGATAN --}}
                <div class="alert alert-light border rounded-4 text-start mt-4 mb-0">
                    <div class="d-flex align-items-start gap-3">
                        <i class="ri-shield-check-line text-success fs-20"></i>

                        <div>
                            <h6 class="fw-semibold mb-1">
                                Pastikan sudah sesuai
                            </h6>

                            <p class="text-muted mb-0 fs-13">
                                Periksa kembali data dan berkas sebelum
                                menetapkan status penerimaan.
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            {{-- FOOTER --}}
            <div class="modal-footer border-0 pt-0 px-4 pb-4 justify-content-center">

                {{-- BATAL --}}
                <button
                    type="button"
                    class="btn btn-light rounded-pill px-4"
                    data-bs-dismiss="modal"
                    wire:loading.attr="disabled"
                    wire:target="terimaPendaftaran"
                >
                    <i class="ri-close-line me-1"></i>
                    Batal
                </button>

                {{-- KONFIRMASI DITERIMA --}}
                <button
                    type="button"
                    class="btn btn-success rounded-pill px-4"
                    wire:click="terimaPendaftaran"
                    wire:loading.attr="disabled"
                    wire:target="terimaPendaftaran"
                    @if (empty($pendaftaranId))
                        disabled
                    @endif
                >
                    <span
                        wire:loading.remove
                        wire:target="terimaPendaftaran"
                    >
                        <i class="ri-checkbox-circle-line me-1"></i>
                        Ya, Terima
                    </span>

                    <span
                        wire:loading
                        wire:target="terimaPendaftaran"
                    >
                        <span class="spinner-border spinner-border-sm me-1"></span>
                        Memproses...
                    </span>
                </button>

            </div>

        </div>
    </div>
</div>
