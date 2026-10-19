
<div class="row g-3">

    {{-- TOTAL ANTRIAN --}}
    <div class="col-xl-4 col-md-6">
        <div class="card border rounded-4 mb-0 h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between gap-3">

                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-md flex-shrink-0">
                            <div class="avatar-title bg-light text-primary rounded-circle fs-4">
                                <i class="ri-list-check-2"></i>
                            </div>
                        </div>

                        <div>
                            <div class="text-muted small mb-1">
                                TOTAL ANTRIAN
                            </div>
                            <div class="fs-5 fw-semibold text-dark">
                                Seluruh Antrean
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <div class="fs-3 fw-bold text-dark">
                            {{ number_format($totalAntrian, 0, ',', '.') }}
                        </div>
                        <div class="text-muted small">
                            Pendaftaran
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- PENGECEKAN --}}
    <div class="col-xl-4 col-md-6">
        <div class="card border rounded-4 mb-0 h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between gap-3">

                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-md flex-shrink-0">
                            <div class="avatar-title bg-light text-warning rounded-circle fs-4">
                                <i class="ri-file-search-line"></i>
                            </div>
                        </div>

                        <div>
                            <div class="text-muted small mb-1">
                                PENGECEKAN
                            </div>
                            <div class="fs-5 fw-semibold text-dark">
                                Menunggu Pemeriksaan
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <div class="fs-3 fw-bold text-dark">
                            {{ number_format($menungguPemeriksaan, 0, ',', '.') }}
                        </div>
                        <div class="text-muted small">
                            Pendaftaran
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- TOTAL PERBAIKAN --}}
    <div class="col-xl-4 col-md-6">
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
                                TOTAL PERBAIKAN
                            </div>
                            <div class="fs-5 fw-semibold text-dark">
                                Perlu Perbaikan
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <div class="fs-3 fw-bold text-dark">
                            {{ number_format($totalPerbaikan, 0, ',', '.') }}
                        </div>
                        <div class="text-muted small">
                            Pendaftaran
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
