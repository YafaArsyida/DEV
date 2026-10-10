<div
    wire:ignore.self
    class="modal fade"
    id="ModalEditPeriode"
    tabindex="-1"
    aria-labelledby="ModalEditPeriodeLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden d-flex flex-column">
            {{-- HEADER --}}
            <div class="modal-header border-0 px-4 pt-4 pb-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm flex-shrink-0">
                        <div
                            class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20"
                        >
                            <i class="ri-calendar-event-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5
                            class="fw-bold mb-1"
                            id="ModalEditPeriodeLabel"
                        >
                            Edit Periode PPDB
                        </h5>
                        <small class="text-muted">
                            Perbarui identitas, jadwal, dan status penerimaan siswa baru.
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

            <form class="d-flex flex-column overflow-hidden" wire:submit.prevent="updatePeriode">
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
                            <h6 class="fw-semibold mb-1">
                                Konteks Penerimaan
                            </h6>
                            <small class="text-muted">
                                Jenjang dan tahun ajaran tidak berubah saat periode diedit.
                            </small>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Jenjang</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    value="{{ \App\Models\Jenjang::find($ms_jenjang_id)?->nama_jenjang ?? 'Belum dipilih' }}"
                                    readonly
                                >
                                <input
                                    type="hidden"
                                    wire:model="ms_jenjang_id"
                                >
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Tahun Ajaran</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    value="{{ \App\Models\TahunAjar::find($ms_tahun_ajar_id)?->nama_tahun_ajar ?? 'Belum dipilih' }}"
                                    readonly
                                >
                                <input
                                    type="hidden"
                                    wire:model="ms_tahun_ajar_id"
                                >
                            </div>
                        </div>
                    </section>

                    {{-- IDENTITAS PERIODE --}}
                    <section class="mb-4">
                        <div class="mb-3">
                            <h6 class="fw-semibold mb-1">
                                Identitas Periode
                            </h6>
                            <small class="text-muted">
                                Perbarui nama dan keterangan periode pendaftaran.
                            </small>
                        </div>

                        <div class="mb-3">
                            <label
                                for="edit_nama_periode"
                                class="form-label"
                            >
                                Nama Periode
                                <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                id="edit_nama_periode"
                                wire:model.defer="nama_periode"
                                class="form-control @error('nama_periode') is-invalid @enderror"
                                placeholder="Contoh: PPDB Tahun Ajaran 2027/2028"
                            >
                            @error('nama_periode')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label
                                for="edit_deskripsi_periode"
                                class="form-label"
                            >
                                Deskripsi
                            </label>
                            <textarea
                                id="edit_deskripsi_periode"
                                wire:model.defer="deskripsi"
                                class="form-control @error('deskripsi') is-invalid @enderror"
                                rows="2"
                                placeholder="deskripsi tambahan tentang periode"
                            ></textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </section>

                    {{-- JADWAL --}}
                    <section class="mb-4">
                        <div class="mb-3">
                            <h6 class="fw-semibold mb-1">
                                Jadwal Pendaftaran
                            </h6>
                            <small class="text-muted">
                                Tentukan rentang waktu berlakunya periode.
                            </small>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label
                                    for="edit_tanggal_mulai"
                                    class="form-label"
                                >
                                    Tanggal Mulai
                                    <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="date"
                                    id="edit_tanggal_mulai"
                                    wire:model.defer="tanggal_mulai"
                                    class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                >
                                @error('tanggal_mulai')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label
                                    for="edit_tanggal_selesai"
                                    class="form-label"
                                >
                                    Tanggal Selesai
                                    <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="date"
                                    id="edit_tanggal_selesai"
                                    wire:model.defer="tanggal_selesai"
                                    class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                >
                                @error('tanggal_selesai')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </section>

                    {{-- STATUS --}}
                    <section>
                        <div class="mb-3">
                            <h6 class="fw-semibold mb-1">
                                Status Periode
                            </h6>
                            <small class="text-muted">
                                Atur status periode sesuai kondisi penerimaan.
                            </small>
                        </div>

                        <div class="mb-0">
                            <label
                                for="edit_status_periode"
                                class="form-label"
                            >
                                Status
                                <span class="text-danger">*</span>
                            </label>
                            <select
                                id="edit_status_periode"
                                wire:model.defer="status"
                                class="form-select @error('status') is-invalid @enderror"
                            >
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Tidak Aktif</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
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