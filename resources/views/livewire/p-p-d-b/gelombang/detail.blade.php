
<div
    wire:ignore.self
    class="modal fade"
    id="ModalDetailGelombang"
    tabindex="-1"
    aria-labelledby="ModalDetailGelombangLabel"
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
                            id="ModalDetailGelombangLabel"
                        >
                            Detail Gelombang PPDB
                        </h5>
                        <small class="text-muted">
                            Informasi gelombang, jadwal, kuota, dan biaya pendaftaran siswa baru.
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
                @if ($gelombangDetail)

                    {{-- IDENTITAS GELOMBANG --}}
                    <section class="mb-4">
                        <div class="mb-3">
                            <h6 class="fw-semibold mb-1">
                                Identitas Gelombang
                            </h6>
                            <small class="text-muted">
                                Informasi utama gelombang penerimaan siswa baru.
                            </small>
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">
                                    Nama Gelombang
                                </label>
                                <div class="form-control bg-light text-break">
                                    {{ $gelombangDetail->nama_gelombang ?: '-' }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Periode PPDB
                                </label>
                                <div class="form-control bg-light text-break">
                                    {{ $gelombangDetail->ppdb_periode?->nama_periode ?: '-' }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Jenjang
                                </label>
                                <div class="form-control bg-light text-break">
                                    {{ $gelombangDetail->ppdb_periode?->ms_jenjang?->nama_jenjang ?: '-' }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Tahun Ajaran
                                </label>
                                <div class="form-control bg-light text-break">
                                    {{ $gelombangDetail->ppdb_periode?->ms_tahun_ajar?->nama_tahun_ajar ?: '-' }}
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
                                Rentang waktu pendaftaran pada gelombang ini.
                            </small>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">
                                    Tanggal Mulai
                                </label>
                                <div class="form-control bg-light">
                                    {{ $gelombangDetail->tanggal_mulai
                                        ? \Carbon\Carbon::parse($gelombangDetail->tanggal_mulai)->translatedFormat('d F Y')
                                        : '-' }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Tanggal Selesai
                                </label>
                                <div class="form-control bg-light">
                                    {{ $gelombangDetail->tanggal_selesai
                                        ? \Carbon\Carbon::parse($gelombangDetail->tanggal_selesai)->translatedFormat('d F Y')
                                        : '-' }}
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">
                                    Durasi Pendaftaran
                                </label>
                                <div class="form-control bg-light">
                                    @if ($gelombangDetail->tanggal_mulai && $gelombangDetail->tanggal_selesai)
                                        {{ \Carbon\Carbon::parse($gelombangDetail->tanggal_mulai)
                                            ->diffInDays(\Carbon\Carbon::parse($gelombangDetail->tanggal_selesai)) + 1 }}
                                        hari kalender
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- KUOTA DAN BIAYA --}}
                    <section class="mb-4">
                        <div class="mb-3">
                            <h6 class="fw-semibold mb-1">
                                Kuota dan Biaya
                            </h6>
                            <small class="text-muted">
                                Batas penerimaan dan biaya pendaftaran yang ditetapkan.
                            </small>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">
                                    Kuota Penerimaan
                                </label>
                                <div class="form-control bg-light">
                                    {{ number_format($gelombangDetail->kuota ?? 0, 0, ',', '.') }}
                                    siswa
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Biaya Pendaftaran
                                </label>
                                <div class="form-control bg-light text-break">
                                    Rp {{ number_format((float) $gelombangDetail->biaya_pendaftaran, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- STATUS --}}
                    <section>
                        <div class="mb-3">
                            <h6 class="fw-semibold mb-1">
                                Status Gelombang
                            </h6>
                            <small class="text-muted">
                                Status pengaturan gelombang pendaftaran.
                            </small>
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">
                                    Status
                                </label>

                                <div class="form-control bg-light d-flex align-items-center">
                                    @if ($gelombangDetail->status === 'aktif')
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
                            Data Gelombang Tidak Ditemukan
                        </h6>
                        <p class="text-muted mb-0">
                            Gelombang yang dipilih tidak tersedia atau telah dihapus.
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