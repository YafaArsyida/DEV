<div class="col-md-6">
    <div class="card card-animate">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div>
                    <h4 class="card-title mb-0 flex-grow-1">
                        Total Transaksi
                    </h4>

                    <div class="dropdown card-header-dropdown">
                        <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown">
                            <span class="fw-semibold text-uppercase fs-12">Periode:</span>
                            <span class="text-muted">
                                {{ $labelPeriode }}
                                <i class="mdi mdi-chevron-down ms-1"></i>
                            </span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" wire:click.prevent="setPeriode('today')">
                                Hari Ini
                            </a>
                            <a class="dropdown-item" wire:click.prevent="setPeriode('yesterday')">
                                Kemarin
                            </a>
                            <a class="dropdown-item" wire:click.prevent="setPeriode('1_month')">
                                1 Bulan Terakhir
                            </a>
                            <a class="dropdown-item" wire:click.prevent="setPeriode('3_month')">
                                3 Bulan Terakhir
                            </a>
                        </div>
                    </div>

                    <h2 class="mt-4 ff-secondary fw-semibold text-success">
                        Rp{{ number_format($totalTransaksi, 0, ',', '.') }}
                    </h2>

                    <p class="text-muted mb-0">
                        Periode:<br>
                        <strong>
                            {{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($startDate, 'd F Y') }}
                        </strong>
                        –
                        <strong>
                            {{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($endDate, 'd F Y') }}
                        </strong>
                    </p>
                </div>

                <div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-success rounded-circle fs-2">
                            <i class="bx bx-bar-chart-alt-2"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>