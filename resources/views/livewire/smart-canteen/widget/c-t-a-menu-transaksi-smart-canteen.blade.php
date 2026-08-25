<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <!-- Alert CTA -->
        <div class="alert alert-warning border-0 rounded-0 m-0 d-flex align-items-center" role="alert">
            <i class="bx bx-cart-alt text-warning me-2 fs-4"></i>
            <div class="flex-grow-1 text-truncate">
                Siap melayani penjualan? Akses cepat fitur <strong>Transaksi Produk Kantin</strong> sekarang.
            </div>
            <div class="flex-shrink-0">
                <a href="{{ route('smartCanteen.transaksi.produk') }}" 
                   class="text-decoration-underline fw-semibold text-warning">
                    Buka Sekarang
                </a>
            </div>
        </div>

        <!-- Body CTA -->
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="p-4">
                    <h5 class="fw-bold mb-2">Penjualan Menu Lebih Cepat dan Terintegrasi</h5>
                    <p class="mb-3 text-muted">
                        Fitur ini membantu petugas kantin dalam mencatat transaksi penjualan menu, 
                        mengurangi antrean, serta memastikan pencatatan lebih akurat dan otomatis masuk 
                        ke laporan keuangan SmartCanteen.
                    </p>

                    <a href="{{ route('smartCanteen.transaksi.produk') }}" class="btn btn-primary btn-lg">
                        <i class="bx bx-log-in-circle align-middle me-1"></i>
                        Masuk Menu Transaksi Kantin
                    </a>
                </div>
            </div>

            <div class="col-md-4 text-end">
                <img src="{{asset('assets')}}/images/user-illustarator-2.png" class="img-fluid" alt="">
            </div>
        </div>
    </div>
</div>
