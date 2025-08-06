 <div class="col-xl-4">
    <div class="card card-height-100">
        <div class="card-header align-items-center border-0 d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Transaksi Jurnal</h4>
            <div class="flex-shrink-0">
                <ul class="nav justify-content-end nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#tab-transaksi-pendapatan" role="tab" aria-selected="false">Pendapatan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab-transaksi-pengeluaran" role="tab" aria-selected="true">Pengeluaran</a>
                    </li>
                </ul><!-- end ul -->
            </div>
        </div><!-- end cardheader -->
        <div class="card-body p-0">
            <div class="tab-content p-0">
                <div class="tab-pane active" id="tab-transaksi-pendapatan" role="tabpanel">
                    @livewire('widget.kartu-transaksi-pendapatan-lainnya')   
                </div><!-- end tabpane -->

                <div class="tab-pane" id="tab-transaksi-pengeluaran" role="tabpanel">
                    @livewire('widget.kartu-transaksi-pengeluaran')   
                </div><!-- end tab pane -->
            </div><!-- end tab pane -->
        </div><!-- end card body -->
    </div><!-- end card -->
</div><!-- end col -->