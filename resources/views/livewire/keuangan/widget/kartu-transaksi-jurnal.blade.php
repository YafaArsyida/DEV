<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    {{-- HEADER --}}
    <div class="card-header">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
            {{-- TITLE --}}
            <div>
                <div class="d-flex align-items-center gap-3">

                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-booklet-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1">
                            Transaksi Jurnal
                        </h5>
                    </div>
                </div>
            </div>

            {{-- TABS --}}
            <div class="flex-shrink-0">

                <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#tab-transaksi-pendapatan" role="tab" aria-selected="true">
                            Pendapatan
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab-transaksi-pengeluaran" role="tab" aria-selected="false">
                            Pengeluaran
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- BODY --}}
    <div class="card-body p-0">
        <div class="tab-content p-0">
            {{-- PENDAPATAN --}}
            <div class="tab-pane active" id="tab-transaksi-pendapatan" role="tabpanel">
                @livewire('keuangan.widget.kartu-transaksi-pendapatan-lainnya')
            </div>

            {{-- PENGELUARAN --}}
            <div class="tab-pane" id="tab-transaksi-pengeluaran" role="tabpanel">
                @livewire('keuangan.widget.kartu-transaksi-pengeluaran')
            </div>
        </div>
    </div>
</div>