<div
    wire:ignore.self class="modal fade" id="ModalDetailProduk" 
    tabindex="-1" aria-labelledby="detailProdukLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            {{-- HEADER --}}
            <div class="modal-header border-0 px-4 pt-4 pb-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-shopping-bag-3-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="modal-title fw-bold mb-1" id="detailProdukLabel">
                            Detail Produk
                        </h5>

                        <small class="text-muted">
                            Informasi produk dan ketersediaannya di kantin.
                        </small>
                    </div>
                </div>

                <button type="button" class="btn btn-light btn-icon rounded-circle ms-auto"
                    data-bs-dismiss="modal" aria-label="Close">
                    <i class="ri-close-line fs-18"></i>
                </button>
            </div>

            {{-- BODY --}}
            <div class="modal-body px-4 pb-4">
                @if($produk)
                    {{-- PRODUCT SUMMARY --}}
                    <div class="bg-light rounded-4 p-4 mb-4">
                        <div class="d-flex flex-column flex-sm-row align-items-center align-items-sm-start gap-4">
                            {{-- PRODUCT ICON --}}
                            <div class="flex-shrink-0">
                                <div
                                    class="avatar-xl rounded-4 bg-white shadow-sm d-flex align-items-center justify-content-center">
                                    <i class="{{ $produk->icon }} {{ $produk->icon_color }}" style="font-size: 52px;"></i>
                                </div>
                            </div>

                            {{-- PRODUCT INFO --}}
                            <div class="flex-grow-1 text-center text-sm-start">
                                <div class="text-muted fs-12 text-uppercase fw-semibold mb-1">
                                    Produk Kantin
                                </div>

                                <h3 class="fw-bold mb-2">
                                    {{ $produk->nama_produk_kantin }}
                                </h3>

                                <div class="d-flex flex-wrap justify-content-center justify-content-sm-start align-items-center gap-2">
                                    {{-- CATEGORY --}}
                                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                        <i class="ri-price-tag-3-line me-1"></i>
                                        {{ $produk->ms_kategori_produk_kantin?->nama_kategori_produk_kantin ?? 'Tanpa Kategori' }}
                                    </span>

                                    {{-- STATUS --}}
                                    @if($produk->status)
                                        <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                                            <i class="ri-checkbox-circle-line me-1"></i>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2">
                                            <i class="ri-close-circle-line me-1"></i>
                                            Nonaktif
                                        </span>
                                    @endif
                                </div>

                                {{-- PRICE --}}
                                <div class="mt-3">
                                    <div class="text-muted fs-12 mb-1">
                                        Harga Jual
                                    </div>

                                    <h3 class="fw-bold text-success mb-0">
                                        Rp {{ number_format($produk->harga, 0, ',', '.') }}
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- DETAIL INFORMATION --}}
                    <div class="row g-3">
                        {{-- STOK --}}
                        <div class="col-md-6">
                            <div class="border rounded-4 p-3 h-100">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-sm flex-shrink-0">
                                        <div class="avatar-title bg-warning-subtle text-warning rounded-circle">
                                            <i class="ri-stack-line fs-18"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="text-muted fs-12 mb-1">
                                            Stok Tersedia
                                        </div>

                                        <h5 class="fw-bold mb-0">
                                            {{ $produk->stok }}
                                            <span class="text-muted fs-13 fw-normal">
                                                {{ $produk->satuan }}
                                            </span>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- KATEGORI --}}
                        <div class="col-md-6">
                            <div class="border rounded-4 p-3 h-100">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-sm flex-shrink-0">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                            <i class="ri-price-tag-3-line fs-18"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="text-muted fs-12 mb-1">
                                            Kategori
                                        </div>

                                        <h6 class="fw-semibold mb-0">
                                            {{ $produk->ms_kategori_produk_kantin?->nama_kategori_produk_kantin ?? '-' }}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- DESKRIPSI --}}
                        <div class="col-12">
                            <div class="border rounded-4 p-3">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div class="avatar-xs">
                                        <div class="avatar-title bg-info-subtle text-info rounded-circle">
                                            <i class="ri-file-text-line"></i>
                                        </div>
                                    </div>

                                    <h6 class="fw-semibold mb-0">
                                        Deskripsi Produk
                                    </h6>
                                </div>

                                @if($produk->deskripsi)
                                    <p class="text-muted mb-0 lh-lg">
                                        {{ $produk->deskripsi }}
                                    </p>
                                @else
                                    <p class="text-muted mb-0">
                                        Tidak ada deskripsi untuk produk ini.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    {{-- EMPTY STATE --}}
                    <div class="text-center py-5">
                        <div class="avatar-xl mx-auto mb-3">
                            <div class="avatar-title bg-light text-muted rounded-circle">
                                <i class="ri-shopping-bag-line fs-28"></i>
                            </div>
                        </div>

                        <h5 class="fw-semibold mb-2">
                            Data Produk Belum Dimuat
                        </h5>

                        <p class="text-muted mb-0">
                            Silakan pilih produk terlebih dahulu untuk melihat detailnya.
                        </p>
                    </div>
                @endif
            </div>

            {{-- FOOTER --}}
            <div class="modal-footer border-0 px-4 pb-4 pt-0">
                <button type="button"
                    class="btn btn-light rounded-pill px-4 ms-auto" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>