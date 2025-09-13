@forelse ($listProduk as $item)
    <div class="col-xxl-2 col-md-3 col-lg-3">
        <div class="card card-animate text-center shadow-sm rounded-3 h-100 border">
            <div class="card-body p-3 d-flex flex-column justify-content-between">

                <!-- Ikon Produk -->
                <div class="position-relative d-inline-block mx-auto">
                    <i wire:click="$emit('tambahKeranjang', {{ $item->ms_produk_kantin_id }})" class="{{ $item->icon ?? 'mdi mdi-cube-outline' }} {{ $item->icon_color ?? 'text-primary' }} mb-2"
                       style="font-size: 35px;"></i>
                </div>

                <!-- Nama Produk -->
                <h5 wire:click="$emit('tambahKeranjang', {{ $item->ms_produk_kantin_id }})" class="mb-0 fs-12 fw-semibold text-uppercase text-truncate" 
                    title="{{ $item->nama_produk_kantin }}">
                    {{ $item->nama_produk_kantin }}
                </h5>

                <!-- Harga -->
                <h4 class="mb-2 fs-20 fw-bold ff-secondary text-success">
                    RP{{ number_format($item->harga, 0, ',', '.') }}
                </h4>

                <!-- Tombol Tambah Keranjang -->
                <button type="button" 
                        class="btn btn-sm btn-primary rounded-pill shadow-sm"
                        wire:click="$emit('tambahKeranjang', {{ $item->ms_produk_kantin_id }})">
                    <i class="ri-shopping-cart-2-line me-1"></i> Pilih
                </button>
            </div>
        </div>
    </div>
@empty
    <div class="col-12">
        <div class="alert alert-info text-center mb-0">Tidak ada produk ditemukan.</div>
    </div>
@endforelse
