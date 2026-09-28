<div class="col-md-6">
    <div class="card card-animate">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div>
                    @php
                    $textClass = match($jenisSaldo) {
                    'estimasi' => 'text-info',
                    'belum' => 'text-danger',
                    'sudah' => 'text-success',
                    };

                    $bgClass = match($jenisSaldo) {
                    'estimasi' => 'bg-info',
                    'belum' => 'bg-danger',
                    'sudah' => 'bg-success',
                    };
                    @endphp

                    <h4 class="card-title mb-0 flex-grow-1">
                        Settlement Kantin
                    </h4>

                    <div class="dropdown">
                        <a href="#" class="text-reset dropdown-btn" data-bs-toggle="dropdown">
                            <span class="fw-semibold text-uppercase fs-12">by:</span>
                            <span class="text-muted">
                                @if($jenisSaldo === 'estimasi') Estimasi
                                @elseif($jenisSaldo === 'belum') Belum Disettlement
                                @elseif($jenisSaldo === 'sudah') Sudah Disettlement
                                @endif
                                <i class="mdi mdi-chevron-down ms-1"></i>
                            </span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" wire:click.prevent="setJenisSaldo('estimasi')">
                                Estimasi Saldo
                            </a>
                            <a class="dropdown-item" wire:click.prevent="setJenisSaldo('belum')">
                                Belum Disettlement
                            </a>
                            <a class="dropdown-item" wire:click.prevent="setJenisSaldo('sudah')">
                                Sudah Disettlement
                            </a>
                        </div>
                    </div>

                    <h2 class="mt-4 ff-secondary fw-semibold {{ $textClass }}">
                        Rp{{ number_format($totalSaldo, 0, ',', '.') }}
                    </h2>

                    <p class="mb-0 text-muted">
                        @if($jenisSaldo === 'estimasi') Total seluruh transaksi
                        @elseif($jenisSaldo === 'belum') Dana belum disetor
                        @elseif($jenisSaldo === 'sudah') Dana sudah disetor
                        @endif
                    </p>
                </div>

                <div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title {{ $bgClass }} rounded-circle fs-2">
                            <i class="bx bx-wallet"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>