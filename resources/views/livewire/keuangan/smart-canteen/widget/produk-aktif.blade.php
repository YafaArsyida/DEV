<div class="card border-0 shadow-sm rounded-4 overflow-hidden">

    {{-- HEADER --}}
    <div class="card-header">

        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">

            {{-- TITLE --}}
            <div>
                <div class="d-flex align-items-center gap-3">

                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-store-2-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1">
                            Produk
                        </h5>
                    </div>

                </div>
            </div>

            {{-- FILTER --}}
            <div class="flex-shrink-0">
                <select
                    wire:model="selectKategoriProduk"
                    class="form-select form-select-sm rounded-pill px-3"
                >
                    <option value="">Semua Kategori</option>

                    @foreach($kategoriList as $kat)
                        <option value="{{ $kat->ms_kategori_produk_kantin_id }}">
                            {{ $kat->nama_kategori_produk_kantin }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>

    </div>

    {{-- BODY --}}
    <div class="card-body">

        <div class="px-2 py-2">

            @forelse ($produkAktif as $item)

                <div class="d-flex justify-content-between align-items-center {{ !$loop->first ? 'mt-3 pt-3 border-top' : '' }}">

                    {{-- PRODUCT --}}
                    <div class="d-flex align-items-center gap-3">

                        <div class="avatar-xs flex-shrink-0">
                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                <i class="ri-shopping-bag-3-line"></i>
                            </div>
                        </div>

                        <div>
                            <div class="fw-semibold text-dark">
                                {{ $item->nama_produk_kantin }}
                            </div>

                            <div class="text-muted small">
                                {{ $item->ms_kategori_produk_kantin->nama_kategori_produk_kantin ?? 'Tanpa Kategori' }}
                            </div>
                        </div>

                    </div>

                    {{-- PRICE --}}
                    <div class="text-end flex-shrink-0">
                        <span class="fw-medium fs-14 text-primary">
                            Rp{{ number_format($item->harga, 0, ',', '.') }}
                        </span>
                    </div>

                </div>

            @empty

                <div class="text-center py-4">
                    <div class="text-muted mb-2">
                        <i class="ri-inbox-line fs-24"></i>
                    </div>

                    <span class="text-muted">
                        Belum ada produk aktif.
                    </span>
                </div>

            @endforelse

            {{-- PAGINATION --}}
            @if($produkAktif->hasPages())
                <div class="mt-3">
                    {{ $produkAktif->links() }}
                </div>
            @endif

        </div>

    </div>

</div>
