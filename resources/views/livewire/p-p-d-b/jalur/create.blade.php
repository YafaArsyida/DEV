<div
    wire:ignore.self
    class="modal fade"
    id="ModalAddJalur"
    tabindex="-1"
    aria-labelledby="ModalAddJalurLabel"
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
                        <h5 class="fw-bold mb-1" id="ModalAddJalurLabel">
                            Tambah Jalur Pendaftaran
                        </h5>

                        <small class="text-muted">
                            Atur identitas, kuota, dan biaya jalur PPDB.
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

            <form class="d-flex flex-column overflow-hidden" wire:submit.prevent="save">
                {{-- BODY --}}
                <div class="modal-body px-4 pt-2 pb-4">

                    <div class="d-flex justify-content-end mb-3">
                        <small class="text-muted">
                            <span class="text-danger">*</span>
                            Wajib diisi
                        </small>
                    </div>

                    {{-- PERIODE PPDB --}}
                    <section class="mb-4">
                        <div class="mb-3">
                            <h6 class="fw-semibold mb-1">
                                Periode PPDB
                            </h6>

                            <small class="text-muted">
                                Jalur akan terhubung dengan periode yang dipilih.
                            </small>
                        </div>

                        <label
                            for="create_periode_jalur"
                            class="form-label"
                        >
                            Periode
                        </label>

                        <input
                            type="text"
                            id="create_periode_jalur"
                            class="form-control"
                            value="{{ $periode->nama_periode ?? 'Belum dipilih' }}"
                            readonly
                        >

                        @if ($periode)
                            <small class="text-muted d-block mt-2">
                                {{ $periode->ms_jenjang?->nama_jenjang ?? '-' }}
                                <span class="mx-1">·</span>
                                {{ $periode->ms_tahun_ajar?->nama_tahun_ajar ?? '-' }}
                            </small>
                        @endif

                        @error('ppdb_periode_id')
                            <div class="text-danger fs-12 mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </section>

                    {{-- IDENTITAS JALUR --}}
                    <section class="mb-4">
                        <div class="mb-3">
                            <h6 class="fw-semibold mb-1">
                                Identitas Jalur
                            </h6>

                            <small class="text-muted">
                                Tentukan kode dan nama jalur pendaftaran.
                            </small>
                        </div>

                        {{-- KODE JALUR --}}
                        <div class="mb-3">
                            <label
                                for="create_kode_jalur"
                                class="form-label"
                            >
                                Kode Jalur
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                id="create_kode_jalur"
                                wire:model.defer="kode_jalur"
                                class="form-control @error('kode_jalur') is-invalid @enderror"
                                placeholder="Contoh: REGULER"
                                maxlength="50"
                                autocomplete="off"
                            >

                            @error('kode_jalur')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <small class="text-muted">
                                Gunakan kode singkat yang mudah dikenali.
                            </small>
                        </div>

                        {{-- NAMA JALUR --}}
                        <div class="mb-3">
                            <label
                                for="create_nama_jalur"
                                class="form-label"
                            >
                                Nama Jalur
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                id="create_nama_jalur"
                                wire:model.defer="nama_jalur"
                                class="form-control @error('nama_jalur') is-invalid @enderror"
                                placeholder="Contoh: Jalur Reguler"
                                maxlength="255"
                            >

                            @error('nama_jalur')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- DESKRIPSI --}}
                        <div>
                            <label
                                for="create_deskripsi_jalur"
                                class="form-label"
                            >
                                Deskripsi
                                <span class="text-muted fw-normal">
                                    (Opsional)
                                </span>
                            </label>

                            <textarea
                                id="create_deskripsi_jalur"
                                wire:model.defer="deskripsi"
                                class="form-control @error('deskripsi') is-invalid @enderror"
                                rows="3"
                                maxlength="2000"
                                placeholder="Jelaskan kriteria atau persyaratan jalur..."
                            ></textarea>

                            @error('deskripsi')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </section>

                    {{-- KUOTA DAN BIAYA --}}
                    <section class="mb-4">
                        <div class="mb-3">
                            <h6 class="fw-semibold mb-1">
                                Kuota dan Biaya
                            </h6>

                            <small class="text-muted">
                                Tentukan kapasitas dan biaya pendaftaran jalur.
                            </small>
                        </div>

                        <div class="row g-3">

                            {{-- KUOTA --}}
                            <div class="col-md-6">
                                <label
                                    for="create_kuota_jalur"
                                    class="form-label"
                                >
                                    Kuota Siswa
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="number"
                                    id="create_kuota_jalur"
                                    wire:model.defer="kuota"
                                    class="form-control @error('kuota') is-invalid @enderror"
                                    min="1"
                                    step="1"
                                    placeholder="Contoh: 100"
                                >

                                @error('kuota')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- BIAYA --}}
                            <div class="col-md-6">
                                <label
                                    for="create_biaya_jalur"
                                    class="form-label"
                                >
                                    Biaya Pendaftaran
                                    <span class="text-danger">*</span>
                                </label>

                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>

                                    <input
                                        type="number"
                                        id="create_biaya_jalur"
                                        wire:model.defer="biaya_pendaftaran"
                                        class="form-control @error('biaya_pendaftaran') is-invalid @enderror"
                                        min="0"
                                        step="1"
                                        placeholder="0"
                                    >
                                </div>

                                @error('biaya_pendaftaran')
                                    <div class="text-danger fs-12 mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <small class="text-muted d-block mt-1">
                                    Isi 0 jika gratis.
                                </small>
                            </div>

                        </div>
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
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary rounded-pill px-4"
                        wire:loading.attr="disabled"
                        wire:target="save"
                    >
                        <span
                            wire:loading.remove
                            wire:target="save"
                        >
                            <i class="ri-save-3-line me-1"></i>
                            Simpan Jalur
                        </span>

                        <span
                            wire:loading
                            wire:target="save"
                        >
                            <span class="spinner-border spinner-border-sm me-1"></span>
                            Menyimpan...
                        </span>
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>