<div wire:ignore.self
    class="modal fade"
    id="ModalDetailKelas"
    tabindex="-1"
    aria-labelledby="ModalDetailKelasLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            {{-- HEADER --}}
            <div class="modal-header border-0 px-4 pt-4 pb-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-building-4-line"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1" id="ModalDetailKelasLabel">Detail Kelas</h5>
                        <small class="text-muted">Informasi penempatan dan ringkasan kelas.</small>
                    </div>
                </div>

                <button type="button"
                    class="btn btn-light btn-icon rounded-circle"
                    data-bs-dismiss="modal"
                    aria-label="Tutup">
                    <i class="ri-close-line fs-18"></i>
                </button>
            </div>

            {{-- BODY --}}
            <div class="modal-body px-4 pt-2 pb-4">
                <section>
                    <div class="mb-3">
                        <h6 class="fw-semibold mb-1">Data Kelas</h6>
                        <small class="text-muted">Identitas dan penempatan akademik.</small>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Nama Kelas</label>
                            <div class="form-control bg-light text-break">{{ $kelasDetail?->nama_kelas ?: '-' }}</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Urutan</label>
                            <div class="form-control bg-light">{{ $kelasDetail?->urutan ?? '-' }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Jenjang</label>
                            <div class="form-control bg-light">{{ $kelasDetail?->ms_jenjang?->nama_jenjang ?: '-' }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tahun Ajaran</label>
                            <div class="form-control bg-light">{{ $kelasDetail?->ms_tahun_ajar?->nama_tahun_ajar ?: '-' }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Jumlah Siswa</label>
                            <div class="form-control bg-light">{{ $jumlahSiswa }} siswa</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Deskripsi</label>
                            <div class="form-control bg-light text-break">{{ $kelasDetail?->deskripsi ?: '-' }}</div>
                        </div>
                    </div>
                </section>
            </div>

            {{-- FOOTER --}}
            <div class="modal-footer border-0 px-4 pb-4 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>