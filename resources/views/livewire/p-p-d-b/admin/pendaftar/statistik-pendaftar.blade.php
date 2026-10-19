
<div class="row g-3">

    {{-- TOTAL PENDAFTAR --}}
    <div class="col-xl-3 col-md-6">
        <div class="card border rounded-4 mb-0 h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between gap-3">

                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-md flex-shrink-0">
                            <div class="avatar-title bg-light text-primary rounded-circle fs-4">
                                <i class="ri-group-line"></i>
                            </div>
                        </div>

                        <div>
                            <div class="text-muted small mb-1">
                                TOTAL PENDAFTAR
                            </div>
                            <div class="fs-5 fw-semibold text-dark">
                                Seluruh Pendaftar
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <div class="fs-3 fw-bold text-dark">
                            {{ number_format($totalPendaftar, 0, ',', '.') }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- MENUNGGU VERIFIKASI --}}
    <div class="col-xl-3 col-md-6">
        <div class="card border rounded-4 mb-0 h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between gap-3">

                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-md flex-shrink-0">
                            <div class="avatar-title bg-light text-warning rounded-circle fs-4">
                                <i class="ri-time-line"></i>
                            </div>
                        </div>

                        <div>
                            <div class="text-muted small mb-1">
                                PENGECEKAN
                            </div>
                            <div class="fs-5 fw-semibold text-dark">
                                Menunggu Verifikasi
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <div class="fs-3 fw-bold text-dark">
                            {{ number_format($menungguVerifikasi, 0, ',', '.') }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- DITERIMA --}}
    <div class="col-xl-3 col-md-6">
        <div class="card border rounded-4 mb-0 h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between gap-3">

                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-md flex-shrink-0">
                            <div class="avatar-title bg-light text-success rounded-circle fs-4">
                                <i class="ri-checkbox-circle-line"></i>
                            </div>
                        </div>

                        <div>
                            <div class="text-muted small mb-1">
                                DITERIMA
                            </div>
                            <div class="fs-5 fw-semibold text-dark">
                                Pendaftaran Diterima
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <div class="fs-3 fw-bold text-dark">
                            {{ number_format($terverifikasi, 0, ',', '.') }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- PERLU PERBAIKAN --}}
    <div class="col-xl-3 col-md-6">
        <div class="card border rounded-4 mb-0 h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between gap-3">

                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-md flex-shrink-0">
                            <div class="avatar-title bg-light text-danger rounded-circle fs-4">
                                <i class="ri-file-edit-line"></i>
                            </div>
                        </div>

                        <div>
                            <div class="text-muted small mb-1">
                                PERLU PERBAIKAN
                            </div>
                            <div class="fs-5 fw-semibold text-dark">
                                Menunggu Perbaikan
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <div class="fs-3 fw-bold text-dark">
                            {{ number_format($perluPerbaikan, 0, ',', '.') }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
