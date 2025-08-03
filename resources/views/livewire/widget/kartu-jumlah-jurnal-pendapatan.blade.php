<div class="col-md-6">
    <div class="card card-animate">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div>
                    <h4 class="card-title mb-0 flex-grow-1">Total Pendapatan</h4>
                    <div class="flex-shrink-0">
                        <div class="dropdown card-header-dropdown">
                            <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="fw-semibold text-uppercase fs-12">Periode:</span>
                                <span class="text-muted">{{ $labelPeriode ?? 'Bulan Ini' }} <i class="mdi mdi-chevron-down ms-1"></i></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" wire:click.prevent="setPeriode('this_month')">Bulan Ini</a>
                                <a class="dropdown-item" wire:click.prevent="setPeriode('last_3_months')">3 Bulan Terakhir</a>
                                <a class="dropdown-item" wire:click.prevent="setPeriode('last_6_months')">6 Bulan Terakhir</a>
                                <a class="dropdown-item" wire:click.prevent="setPeriode('all')">Semua</a>
                            </div>
                        </div>
                    </div>

                    <h2 class="mt-4 ff-secondary fw-semibold">
                        Rp{{ number_format($totalPendapatan, 0, ',', '.') }}
                    </h2>
                    <p class="mb-0 text-muted">
                        Jenjang: <strong>{{ $namaJenjang }}</strong>,
                        Tahun Ajar: <strong>{{ $namaTahunAjar }}</strong>
                    </p>
                    <p class="text-muted mb-0">Periode: <strong>{{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($startDate, 'd F Y') }}</strong> - <strong>{{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($endDate, 'd F Y') }}</strong></p>
                </div>
                <div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-success rounded-circle fs-2">
                            <i class="bx bx-bar-chart-alt-2"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div><!-- end card body -->
    </div> <!-- end card-->
</div> <!-- end col-->
