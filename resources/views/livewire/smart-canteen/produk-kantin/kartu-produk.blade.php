@forelse ($listProduk as $item)
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card card-animate text-center shadow-sm rounded-3 h-100 border">
            <div class="card-body p-3 d-flex flex-column justify-content-between">
                <!-- Ikon Produk + Dropdown -->
                <div class="position-relative d-inline-block mx-auto">
                    <i class="{{ $item->icon ?? 'mdi mdi-cube-outline' }} {{ $item->icon_color ?? 'text-primary' }} mb-2"
                    style="font-size: 35px;" data-bs-toggle="dropdown"></i>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="#">Edit</a>
                        <a class="dropdown-item" href="#">Remove</a>
                        <a class="dropdown-item" href="#">Detail</a>
                    </div>
                </div>

                <!-- Nama Produk -->
                <h5 class="mb-0 fs-12 fw-semibold text-uppercase text-truncate" 
                    title="{{ $item->nama_produk_kantin }}">
                    {{ $item->nama_produk_kantin }}
                </h5>

                <!-- Harga -->
                <h4 class="mb-0 fs-20 fw-bold ff-secondary text-success">
                    Rp {{ number_format($item->harga, 0, ',', '.') }}
                </h4>
            </div>
        </div>
    </div>
@empty
    <div class="col-12">
        <div class="alert alert-info text-center mb-0">Tidak ada produk ditemukan.</div>
    </div>
@endforelse
