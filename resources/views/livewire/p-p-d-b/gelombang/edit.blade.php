
<div
    wire:ignore.self
    class="modal fade"
    id="ModalEditGelombang"
    tabindex="-1"
    aria-labelledby="ModalEditGelombangLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden d-flex flex-column">

            {{-- HEADER --}}
            <div class="modal-header border-0 px-4 pt-4 pb-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-calendar-event-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1" id="ModalEditGelombangLabel">
                            Edit Gelombang PPDB
                        </h5>
                        <small class="text-muted">
                            Perbarui identitas, jadwal, kuota, dan biaya pendaftaran.
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

            <form
                class="d-flex flex-column overflow-hidden"
                wire:submit.prevent="updateGelombang"
            >
                {{-- BODY --}}
                <div class="modal-body px-4 pt-2 pb-4">

                    <div class="d-flex justify-content-end mb-3">
                        <small class="text-muted">
                            <span class="text-danger">*</span>
                            Wajib diisi
                        </small>
                    </div>

                    {{-- KONTEKS PERIODE --}}
                    <section class="mb-4">
                        <div class="mb-3">
                            <h6 class="fw-semibold mb-1">Periode PPDB</h6>
                            <small class="text-muted">
                                Gelombang tetap terhubung dengan periode asalnya.
                            </small>
                        </div>

                        <label for="edit_periode_gelombang" class="form-label">
                            Periode
                        </label>

                        <input
                            type="text"
                            id="edit_periode_gelombang"
                            class="form-control"
                            value="{{ $periode->nama_periode ?? 'Periode tidak ditemukan' }}"
                            readonly
                        >

                        @error('ppdb_periode_id')
                            <div class="text-danger fs-12 mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </section>

                    {{-- IDENTITAS --}}
                    <section class="mb-4">
                        <div class="mb-3">
                            <h6 class="fw-semibold mb-1">Identitas Gelombang</h6>
                            <small class="text-muted">
                                Perbarui nama gelombang pendaftaran.
                            </small>
                        </div>

                        <div class="mb-3">
                            <label for="edit_nama_gelombang" class="form-label">
                                Nama Gelombang
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                id="edit_nama_gelombang"
                                wire:model.defer="nama_gelombang"
                                class="form-control @error('nama_gelombang') is-invalid @enderror"
                                placeholder="Contoh: Gelombang 1"
                            >

                            @error('nama_gelombang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </section>

                    {{-- JADWAL --}}
                    <section class="mb-4">
                        <div class="mb-3">
                            <h6 class="fw-semibold mb-1">Jadwal Pendaftaran</h6>
                            <small class="text-muted">
                                Sesuaikan jadwal dengan rentang periode PPDB.
                            </small>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="edit_tanggal_mulai_gelombang" class="form-label">
                                    Tanggal Mulai
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="date"
                                    id="edit_tanggal_mulai_gelombang"
                                    wire:model.defer="tanggal_mulai"
                                    class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                >

                                @error('tanggal_mulai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="edit_tanggal_selesai_gelombang" class="form-label">
                                    Tanggal Selesai
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="date"
                                    id="edit_tanggal_selesai_gelombang"
                                    wire:model.defer="tanggal_selesai"
                                    class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                >

                                @error('tanggal_selesai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </section>

                    {{-- KUOTA DAN BIAYA --}}
                    <section class="mb-4">
                        <div class="mb-3">
                            <h6 class="fw-semibold mb-1">Kuota dan Biaya</h6>
                            <small class="text-muted">
                                Perbarui kapasitas dan biaya pendaftaran.
                            </small>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="edit_kuota_gelombang" class="form-label">
                                    Kuota Siswa
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="number"
                                    id="edit_kuota_gelombang"
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
                                <label for="edit_biaya_gelombang" class="form-label">
                                    Biaya Pendaftaran
                                    <span class="text-danger">*</span>
                                </label>

                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>

                                    <input
                                        type="number"
                                        id="edit_biaya_gelombang"
                                        wire:model.defer="biaya_pendaftaran"
                                        min="0"
                                        step="any"
                                        class="form-control @error('biaya_pendaftaran') is-invalid @enderror"
                                        placeholder="0"
                                    >

                                    @error('biaya_pendaftaran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <small class="text-muted">
                                    Isi 0 jika pendaftaran gratis.
                                </small>
                            </div>
                        </div>
                    </section>

                    {{-- STATUS --}}
                    <section>
                        <div class="mb-3">
                            <h6 class="fw-semibold mb-1">Status Gelombang</h6>
                            <small class="text-muted">
                                Atur status gelombang sesuai kondisi penerimaan.
                            </small>
                        </div>

                        <label for="edit_status_gelombang" class="form-label">
                            Status
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            id="edit_status_gelombang"
                            wire:model.defer="status"
                            class="form-select @error('status') is-invalid @enderror"
                        >
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Tidak Aktif</option>
                        </select>

                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </section>

                </div>

                {{-- FOOTER --}}
                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="ri-save-3-line me-1"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>