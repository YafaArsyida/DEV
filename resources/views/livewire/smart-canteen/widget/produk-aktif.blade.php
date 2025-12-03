<div class="col-xl-4">
    <div class="card card-height-100">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Produk Aktif</h4>
            <!-- Select Periode -->
            <div class="flex-shrink-0">
                <select wire:model="selectKategoriProduk" class="form-select form-select-sm">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoriList as $kat)
                        <option value="{{ $kat->ms_kategori_produk_kantin_id }}">
                            {{ $kat->nama_kategori_produk_kantin }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="card-body">
            <div class="px-2 py-2 mt-2">
                 @forelse ($produkAktif as $item)
                    <div class="d-flex justify-content-between align-items-start {{ !$loop->first ? 'mt-3' : '' }}">
                        <div>
                            <div class="fw-semibold text-dark">
                                {{ $item->nama_produk_kantin }}
                            </div>

                            <div class="text-muted small">
                                {{ $item->ms_kategori_produk_kantin->nama_kategori_produk_kantin ?? 'Tanpa Kategori' }}
                            </div>
                        </div>

                        <div class="text-end">
                            <span class="fw-medium fs-14 text-primary">
                                Rp{{ number_format($item->harga, 0, ',', '.') }}
                            </span>
                        </div>

                    </div>
                @empty
                    <p class="text-muted">Belum ada produk aktif.</p>
                @endforelse
                <div class="mt-3">
                    {{ $produkAktif->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
