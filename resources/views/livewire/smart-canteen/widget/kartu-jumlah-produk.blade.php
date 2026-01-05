<div class="col-md-6">
    <div class="card card-animate">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div class="w-100">
                    <h4 class="card-title mb-1 flex-grow-1">
                        Jumlah Produk
                    </h4>

                    {{-- Dropdown kategori --}}
                    <div class="dropdown">
                        <a href="#" class="text-reset dropdown-btn" data-bs-toggle="dropdown">
                            <span class="fw-semibold text-uppercase fs-12">Kategori:</span>
                            <span class="text-muted">
                                @if($selectedKategori)
                                {{ $listKategori->firstWhere('ms_kategori_produk_kantin_id',
                                $selectedKategori)?->nama_kategori_produk_kantin }}
                                @else
                                Semua Kategori
                                @endif
                                <i class="mdi mdi-chevron-down ms-1"></i>
                            </span>
                        </a>
                    
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" wire:click.prevent="setKategori(null)">
                                Semua Kategori
                            </a>
                    
                            @foreach($listKategori as $kategori)
                            <a class="dropdown-item {{ $selectedKategori == $kategori->ms_kategori_produk_kantin_id ? 'active' : '' }}" wire:click.prevent="setKategori({{ $kategori->ms_kategori_produk_kantin_id }})">
                                {{ $kategori->nama_kategori_produk_kantin }}
                            </a>
                            @endforeach
                        </div>
                    </div>

                    <h2 class="mt-4 ff-secondary fw-semibold text-success">
                        {{ number_format($jumlahProduk) }}
                        <span class="fs-6 text-muted">produk</span>
                    </h2>

                    <p class="mb-0 text-muted">
                        Jenjang:
                        <strong>{{ $namaJenjang ?: '-' }}</strong>
                    </p>
                </div>

                <div class="ms-2">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-success rounded-circle fs-2">
                            <i class="bx bx-package"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>