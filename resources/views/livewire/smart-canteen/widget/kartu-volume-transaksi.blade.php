<div class="col-md-6">
    <div class="card card-animate">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div>
                    <h4 class="card-title mb-0 flex-grow-1">
                        Volume Transaksi
                    </h4>

                    {{-- Dropdown Periode --}}
                    <div class="dropdown card-header-dropdown mb-1">
                        <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown">
                            <span class="fw-semibold text-uppercase fs-12">Periode:</span>
                            <span class="text-muted">
                                {{ $labelPeriode }}
                                <i class="mdi mdi-chevron-down ms-1"></i>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" wire:click.prevent="setPeriode('today')">Hari Ini</a>
                            <a class="dropdown-item" wire:click.prevent="setPeriode('yesterday')">Kemarin</a>
                            <a class="dropdown-item" wire:click.prevent="setPeriode('1_month')">1 Bulan Terakhir</a>
                            <a class="dropdown-item" wire:click.prevent="setPeriode('2_month')">2 Bulan Terakhir</a>
                        </div>
                    </div>

                    {{-- Dropdown Petugas (hanya non-kantin) --}}
                    @if(auth()->user()->peran !== 'kantin')
                    <div class="mb-2">
                        <select class="form-select form-select-sm" wire:model="selectedPetugas">
                            <option value="">Semua Petugas Kantin</option>
                            @foreach($select_petugas as $petugas)
                            <option value="{{ $petugas->ms_pengguna_id }}">
                                {{ $petugas->nama ?? $petugas->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <h2 class="mt-3 ff-secondary fw-semibold text-info">
                        {{ number_format($jumlahTransaksi) }}
                        <span class="fs-6 text-muted">transaksi</span>
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
                        <span class="avatar-title bg-info rounded-circle fs-2">
                            <i class="bx bx-receipt"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>