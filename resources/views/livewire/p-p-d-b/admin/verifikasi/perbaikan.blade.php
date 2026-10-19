
{{-- MODAL KONFIRMASI PERBAIKAN --}}
<div
    wire:ignore.self
    class="modal fade"
    id="ModalKonfirmasiPerbaikan"
    tabindex="-1"
    aria-labelledby="perbaikanLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
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
            <div class="modal-body px-4 pb-4 pt-2">

                <div class="text-center mb-4">
                    <div class="avatar-xl mx-auto mb-3">
                        <div class="avatar-title bg-danger-subtle text-danger rounded-circle">
                            <lord-icon
                                src="https://cdn.lordicon.com/sbnjyzil.json"
                                trigger="loop"
                                colors="primary:#dc3545,secondary:#dc3545"
                                style="width:70px;height:70px"
                            >
                            </lord-icon>
                        </div>
                    </div>

                    <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill mb-3">
                        Konfirmasi Perbaikan
                    </span>

                    <h4 class="fw-bold mb-2" id="perbaikanLabel">
                        Minta Perbaikan Pendaftaran
                    </h4>

                    <p class="text-muted mb-0 lh-lg">
                        Pendaftaran akan dikembalikan kepada orang tua
                        untuk diperbaiki. Tuliskan bagian yang perlu
                        dilengkapi atau diperbaiki dengan jelas.
                    </p>
                </div>

                {{-- CATATAN PERBAIKAN --}}
                <div class="mb-3">
                    <label
                        for="catatanPerbaikan"
                        class="form-label fw-semibold"
                    >
                        Catatan Perbaikan
                        <span class="text-danger">*</span>
                    </label>

                    <textarea
                        id="catatanPerbaikan"
                        wire:model.defer="catatanPerbaikan"
                        class="form-control rounded-3 @error('catatanPerbaikan') is-invalid @enderror"
                        rows="4"
                        maxlength="2000"
                        placeholder="Contoh: Mohon unggah ulang kartu keluarga dengan foto yang jelas."
                    ></textarea>

                    @error('catatanPerbaikan')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="form-text">
                        Jelaskan dokumen atau data yang harus diperbaiki
                        agar orang tua dapat menindaklanjutinya.
                    </div>
                </div>

                {{-- PERINGATAN --}}
                <div class="alert alert-light border rounded-4 mb-0">
                    <div class="d-flex align-items-start gap-3">
                        <i class="ri-information-line text-warning fs-20"></i>

                        <div>
                            <h6 class="fw-semibold mb-1">Perhatian</h6>
                            <p class="text-muted mb-0 fs-13">
                                Catatan akan ditampilkan kepada orang tua
                                pada halaman status pendaftaran.
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            {{-- FOOTER --}}
            <div class="modal-footer border-0 pt-0 px-4 pb-4 justify-content-center">

                <button
                    type="button"
                    class="btn btn-light rounded-pill px-4"
                    data-bs-dismiss="modal"
                    wire:loading.attr="disabled"
                    wire:target="mintaPerbaikan"
                >
                    <i class="ri-close-line me-1"></i>
                    Batal
                </button>

                <button
                    type="button"
                    class="btn btn-danger rounded-pill px-4"
                    wire:click="mintaPerbaikan"
                    wire:loading.attr="disabled"
                    wire:target="mintaPerbaikan"
                    @if (empty($pendaftaranId))
                        disabled
                    @endif
                >
                    <span wire:loading.remove wire:target="mintaPerbaikan">
                        <i class="ri-send-plane-line me-1"></i>
                        Kirim Perbaikan
                    </span>

                    <span wire:loading wire:target="mintaPerbaikan">
                        <span class="spinner-border spinner-border-sm me-1"></span>
                        Memproses...
                    </span>
                </button>

            </div>

        </div>
    </div>
</div>
