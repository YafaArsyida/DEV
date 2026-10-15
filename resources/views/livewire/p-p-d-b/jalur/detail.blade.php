
<div
    wire:ignore.self
    class="modal fade"
    id="ModalDetailJalur"
    tabindex="-1"
    aria-labelledby="ModalDetailJalurLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
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
                        <h5 class="fw-bold mb-1" id="ModalDetailJalurLabel">
                            Detail Jalur PPDB
                        </h5>
                        <small class="text-muted">
                            Informasi jalur penerimaan, kuota, biaya, dan periode pendaftaran siswa baru.
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
                @if ($jalurDetail)

                    {{-- IDENTITAS JALUR --}}
                    <section class="mb-4">
                        <div class="mb-3">
                            <h6 class="fw-semibold mb-1">
                                Identitas Jalur
                            </h6>
                            <small class="text-muted">
                                Informasi utama jalur penerimaan siswa baru.
                            </small>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Kode Jalur</label>
                                <div class="form-control bg-light text-break">
                                    {{ $jalurDetail->kode_jalur ?: '-' }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Nama Jalur</label>
                                <div class="form-control bg-light text-break">
                                    {{ $jalurDetail->nama_jalur ?: '-' }}
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Deskripsi</label>
                                <div
                                    class="form-control bg-light text-break"
                                    style="min-height: 80px; height: auto; white-space: pre-line;"
                                >{{ filled($jalurDetail->deskripsi) ? $jalurDetail->deskripsi : '-' }}</div>
                            </div>
                        </div>
                    </section>

                    {{-- PERIODE PPDB --}}
                    <section class="mb-4">
                        <div class="mb-3">
                            <h6 class="fw-semibold mb-1">
                                Periode PPDB
                            </h6>
                            <small class="text-muted">
                                Informasi periode dan tahun ajaran jalur penerimaan.
                            </small>
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Nama Periode</label>
                                <div class="form-control bg-light text-break">
                                    {{ $jalurDetail->ppdb_periode?->nama_periode ?: '-' }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Jenjang</label>
                                <div class="form-control bg-light text-break">
                                    {{ $jalurDetail->ppdb_periode?->ms_jenjang?->nama_jenjang ?: '-' }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Tahun Ajaran</label>
                                <div class="form-control bg-light text-break">
                                    {{ $jalurDetail->ppdb_periode?->ms_tahun_ajar?->nama_tahun_ajar ?: '-' }}
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
                                Batas penerimaan dan biaya pendaftaran yang ditetapkan untuk jalur ini.
                            </small>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Kuota Penerimaan</label>
                                <div class="form-control bg-light">
                                    {{ number_format($jalurDetail->kuota ?? 0, 0, ',', '.') }}
                                    siswa
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Biaya Pendaftaran</label>
                                <div class="form-control bg-light text-break">
                                    Rp {{ number_format((float) $jalurDetail->biaya_pendaftaran, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- STATUS --}}
                    <section>
                        <div class="mb-3">
                            <h6 class="fw-semibold mb-1">
                                Status Jalur
                            </h6>
                            <small class="text-muted">
                                Status konfigurasi jalur penerimaan siswa baru.
                            </small>
                        </div>

                        <label class="form-label">Status</label>

                        <div class="form-control bg-light d-flex align-items-center">
                            @if (in_array($jalurDetail->status, ['Aktif', 1, '1', true], true))
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
                    </section>

                @else
                    {{-- EMPTY STATE --}}
                    <div class="text-center py-5">
                        <div class="avatar-md mx-auto mb-3">
                            <div class="avatar-title bg-light text-muted rounded-circle fs-24">
                                <i class="ri-git-branch-line"></i>
                            </div>
                        </div>

                        <h6 class="fw-semibold mb-1">
                            Data Jalur Tidak Ditemukan
                        </h6>
                        <p class="text-muted mb-0">
                            Jalur yang dipilih tidak tersedia atau telah dihapus.
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
