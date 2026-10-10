
<div
    wire:ignore.self
    class="modal fade"
    id="ModalDetailPeriode"
    tabindex="-1"
    aria-labelledby="ModalDetailPeriodeLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            {{-- HEADER --}}
            <div class="modal-header border-0 px-4 pt-4 pb-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-calendar-event-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5
                            class="fw-bold mb-1"
                            id="ModalDetailPeriodeLabel"
                        >
                            Detail Periode PPDB
                        </h5>

                        <small class="text-muted">
                            Informasi periode, jadwal pendaftaran, dan gelombang penerimaan.
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

            {{-- BODY --}}
            <div class="modal-body px-4 pt-2 pb-4">
                @if ($periodeDetail)
                    {{-- IDENTITAS PERIODE --}}
                    <section class="mb-4">
                        <div class="mb-3">
                            <h6 class="fw-semibold mb-1">
                                Identitas Periode
                            </h6>

                            <small class="text-muted">
                                Informasi utama periode penerimaan siswa baru.
                            </small>
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">
                                    Nama Periode
                                </label>

                                <div class="form-control bg-light text-break">
                                    {{ $periodeDetail->nama_periode ?: '-' }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Jenjang
                                </label>

                                <div class="form-control bg-light text-break">
                                    {{ $periodeDetail->ms_jenjang?->nama_jenjang ?: '-' }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Tahun Ajaran
                                </label>

                                <div class="form-control bg-light text-break">
                                    {{ $periodeDetail->ms_tahun_ajar?->nama_tahun_ajar ?: '-' }}
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">
                                    Keterangan
                                </label>

                                <div class="form-control bg-light text-break">
                                    {{ $periodeDetail->keterangan ?: '-' }}
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- JADWAL PENDAFTARAN --}}
                    <section class="mb-4">
                        <div class="mb-3">
                            <h6 class="fw-semibold mb-1">
                                Jadwal Pendaftaran
                            </h6>

                            <small class="text-muted">
                                Rentang waktu pelaksanaan penerimaan siswa baru.
                            </small>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">
                                    Tanggal Mulai
                                </label>

                                <div class="form-control bg-light">
                                    {{ $periodeDetail->tanggal_mulai
                                        ? \Carbon\Carbon::parse($periodeDetail->tanggal_mulai)->translatedFormat('d F Y')
                                        : '-' }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Tanggal Selesai
                                </label>

                                <div class="form-control bg-light">
                                    {{ $periodeDetail->tanggal_selesai
                                        ? \Carbon\Carbon::parse($periodeDetail->tanggal_selesai)->translatedFormat('d F Y')
                                        : '-' }}
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- RINGKASAN --}}
                    <section>
                        <div class="mb-3">
                            <h6 class="fw-semibold mb-1">
                                Ringkasan Periode
                            </h6>

                            <small class="text-muted">
                                Status periode dan jumlah gelombang yang terdaftar.
                            </small>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">
                                    Jumlah Gelombang
                                </label>

                                <div class="form-control bg-light">
                                    {{ $periodeDetail->ppdb_gelombang_count }} gelombang
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Status Periode
                                </label>

                                <div class="form-control bg-light d-flex align-items-center">
                                    @if ($periodeDetail->status === 'aktif')
                                        <span class="text-success fw-medium">
                                            <i class="ri-checkbox-circle-line me-1"></i>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="text-muted fw-medium">
                                            <i class="ri-close-circle-line me-1"></i>
                                            Tidak Aktif
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </section>
                @else
                    <div class="text-center py-5">
                        <div class="avatar-md mx-auto mb-3">
                            <div class="avatar-title bg-light text-muted rounded-circle fs-24">
                                <i class="ri-calendar-event-line"></i>
                            </div>
                        </div>

                        <h6 class="fw-semibold mb-1">
                            Data Periode Tidak Ditemukan
                        </h6>

                        <p class="text-muted mb-0">
                            Periode yang dipilih tidak tersedia atau telah dihapus.
                        </p>
                    </div>
                @endif
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
            </div>
        </div>
    </div>
</div>