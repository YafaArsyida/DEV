
<div
    wire:ignore.self
    class="modal fade"
    id="ModalEditJalur"
    tabindex="-1"
    aria-labelledby="ModalEditJalurLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            {{-- HEADER --}}
            <div class="modal-header border-0 px-4 pt-4 pb-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-git-branch-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1" id="ModalEditJalurLabel">
                            Edit Jalur PPDB
                        </h5>
                        <small class="text-muted">
                            Perbarui identitas, kuota, biaya, dan status jalur pendaftaran.
                        </small>
                    </div>
                </div>

                <button
                    type="button"
                    class="btn btn-light btn-icon rounded-circle"
                    data-bs-dismiss="modal"
                    aria-label="Tutup"
                >
                    <i class="ri-close-line fs-18"></i>
                </button>
            </div>

            <form class="d-flex flex-column overflow-hidden" wire:submit.prevent="updateJalur">
                {{-- BODY --}}
                <div class="modal-body px-4 pt-2 pb-4">

                    <div class="d-flex justify-content-end mb-3">
                        <small class="text-muted">
                            <span class="text-danger">*</span>
                            Wajib diisi
                        </small>
                    </div>

                    {{-- PERIODE --}}
                    <section class="mb-4">
                        <div class="mb-3">
                            <h6 class="fw-semibold mb-1">Periode PPDB</h6>
                            <small class="text-muted">
                                Jalur tetap terhubung dengan periode asalnya.
                            </small>
                        </div>

                        <label for="edit_periode_jalur" class="form-label">
                            Periode
                        </label>

                        <input
                            type="text"
                            id="edit_periode_jalur"
                            class="form-control"
                            value="{{ $periode->nama_periode ?? 'Periode tidak ditemukan' }}"
                            readonly
                        >

                        @error('ppdb_periode_id')
                            <div class="text-danger fs-12 mt-1">{{ $message }}</div>
                        @enderror
                    </section>

                    {{-- IDENTITAS --}}
                    <section class="mb-4">
                        <div class="mb-3">
                            <h6 class="fw-semibold mb-1">Identitas Jalur</h6>
                            <small class="text-muted">
                                Tentukan kode dan nama jalur penerimaan siswa.
                            </small>
                        </div>

                        <div class="mb-3">
                            <label for="edit_kode_jalur" class="form-label">
                                Kode Jalur <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                id="edit_kode_jalur"
                                wire:model.defer="kode_jalur"
                                class="form-control @error('kode_jalur') is-invalid @enderror"
                                placeholder="Contoh: REGULER"
                                maxlength="50"
                            >

                            @error('kode_jalur')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="edit_nama_jalur" class="form-label">
                                Nama Jalur <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                id="edit_nama_jalur"
                                wire:model.defer="nama_jalur"
                                class="form-control @error('nama_jalur') is-invalid @enderror"
                                placeholder="Contoh: Jalur Reguler"
                                maxlength="255"
                            >

                            @error('nama_jalur')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="edit_deskripsi_jalur" class="form-label">
                                Deskripsi
                            </label>

                            <textarea
                                id="edit_deskripsi_jalur"
                                wire:model.defer="deskripsi"
                                class="form-control @error('deskripsi') is-invalid @enderror"
                                rows="3"
                                maxlength="2000"
                                placeholder="Tambahkan keterangan jalur jika diperlukan."
                            ></textarea>

                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </section>

                    {{-- KUOTA DAN BIAYA --}}
                    <section class="mb-4">
                        <div class="mb-3">
                            <h6 class="fw-semibold mb-1">Kuota dan Biaya</h6>
                            <small class="text-muted">
                                Atur kapasitas penerimaan dan biaya pendaftaran.
                            </small>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="edit_kuota_jalur" class="form-label">
                                    Kuota Siswa <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="number"
                                    id="edit_kuota_jalur"
                                    wire:model.defer="kuota"
                                    min="1"
                                    step="1"
                                    class="form-control @error('kuota') is-invalid @enderror"
                                    placeholder="Contoh: 100"
                                >

                                @error('kuota')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="edit_biaya_jalur" class="form-label">
                                    Biaya Pendaftaran <span class="text-danger">*</span>
                                </label>

                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>

                                    <input
                                        type="number"
                                        id="edit_biaya_jalur"
                                        wire:model.defer="biaya_pendaftaran"
                                        min="0"
                                        step="any"
                                        class="form-control @error('biaya_pendaftaran') is-invalid @enderror"
                                        placeholder="0"
                                    >
                                </div>

                                @error('biaya_pendaftaran')
                                    <div class="text-danger fs-12 mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <small class="text-muted">
                                    Isi 0 jika pendaftaran gratis.
                                </small>
                            </div>
                        </div>
                    </section>

                    {{-- STATUS --}}
                    <section>
                        <div class="mb-3">
                            <h6 class="fw-semibold mb-1">Status Jalur</h6>
                            <small class="text-muted">
                                Jalur nonaktif tidak ditawarkan untuk pendaftaran baru.
                            </small>
                        </div>

                        <label for="edit_status_jalur" class="form-label">
                            Status <span class="text-danger">*</span>
                        </label>

                        <select
                            id="edit_status_jalur"
                            wire:model.defer="status"
                            class="form-select @error('status') is-invalid @enderror"
                        >
                            <option value="Aktif">Aktif</option>
                            <option value="Non Aktif">Tidak Aktif</option>
                        </select>

                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </section>
                </div>

                {{-- FOOTER --}}
                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <button
                        type="button"
                        class="btn btn-light rounded-pill px-4"
                        data-bs-dismiss="modal"
                    >
                        <i class="ri-close-line me-1"></i>
                        Tutup
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary rounded-pill px-4"
                        wire:loading.attr="disabled"
                        wire:target="updateJalur"
                    >
                        <span wire:loading.remove wire:target="updateJalur">
                            <i class="ri-save-3-line me-1"></i>
                            Simpan Perubahan
                        </span>

                        <span wire:loading wire:target="updateJalur">
                            <span
                                class="spinner-border spinner-border-sm me-1"
                                role="status"
                                aria-hidden="true"
                            ></span>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
