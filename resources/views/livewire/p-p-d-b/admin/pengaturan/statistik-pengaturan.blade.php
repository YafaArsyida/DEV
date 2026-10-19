
<div class="row g-3">

    {{-- TOTAL PERIODE --}}
    <div class="col-xl-4 col-md-6">
        <div class="card border rounded-4 mb-0 h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between gap-3">

                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-md flex-shrink-0">
                            <div class="avatar-title bg-light text-primary rounded-circle fs-4">
                                <i class="ri-calendar-event-line"></i>
                            </div>
                        </div>

                        <div>
                            <div class="text-muted small mb-1">
                                TOTAL PERIODE
                            </div>
                            <div class="fs-5 fw-semibold text-dark">
                                Periode Pendaftaran
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <div class="fs-3 fw-bold text-dark">
                            {{ number_format($totalPeriode, 0, ',', '.') }}
                        </div>
                        <div class="text-muted small">
                            Periode
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- TOTAL GELOMBANG --}}
    <div class="col-xl-4 col-md-6">
        <div class="card border rounded-4 mb-0 h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between gap-3">

                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-md flex-shrink-0">
                            <div class="avatar-title bg-light text-info rounded-circle fs-4">
                                <i class="ri-stack-line"></i>
                            </div>
                        </div>

                        <div>
                            <div class="text-muted small mb-1">
                                TOTAL GELOMBANG
                            </div>
                            <div class="fs-5 fw-semibold text-dark">
                                Gelombang Pendaftaran
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <div class="fs-3 fw-bold text-dark">
                            {{ number_format($totalGelombang, 0, ',', '.') }}
                        </div>
                        <div class="text-muted small">
                            Gelombang
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- TOTAL JALUR --}}
    <div class="col-xl-4 col-md-6">
        <div class="card border rounded-4 mb-0 h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between gap-3">

                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-md flex-shrink-0">
                            <div class="avatar-title bg-light text-success rounded-circle fs-4">
                                <i class="ri-git-branch-line"></i>
                            </div>
                        </div>

                        <div>
                            <div class="text-muted small mb-1">
                                TOTAL JALUR
                            </div>
                            <div class="fs-5 fw-semibold text-dark">
                                Jalur Penerimaan
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <div class="fs-3 fw-bold text-dark">
                            {{ number_format($totalJalur, 0, ',', '.') }}
                        </div>
                        <div class="text-muted small">
                            Jalur
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
