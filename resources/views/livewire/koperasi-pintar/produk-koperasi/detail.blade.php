<div wire:ignore.self class="modal fade" id="ModalDetailProdukKoperasi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title">Detail Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                @if($produk)
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <i class="mdi mdi-candy text-primary" style="font-size:60px"></i>
                            <h5 class="mt-2 fs-12 fw-semibold text-uppercase">{{ $produk->nama_produk_koperasi }}</h5>
                            <h4 class="mb-0 fs-12 fw-bold ff-secondary">
                                {{ number_format($produk->kode_produk_koperasi, 0, ',', '.') }}
                            </h4>
                        </div>
                        <div class="col-md-9">
                            <table class="table table-sm">
                                <tr>
                                    <th>Kategori</th>
                                    <td>{{ $produk->ms_kategori_produk_koperasi?->nama_kategori_produk_koperasi }}</td>
                                </tr>
                                <tr>
                                    <th>Harga Beli</th>
                                    <td>
                                        <span class="fs-14 fw-medium text-info">
                                            RP{{ number_format($produk->harga_beli, 0, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Harga Jual</th>
                                    <td>
                                        <span class="fs-14 fw-medium text-success">
                                            RP{{ number_format($produk->harga_jual, 0, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Stok</th>
                                    <td>{{ $produk->stok }} {{ $produk->satuan }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>{!! $produk->status_produk_koperasi ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Nonaktif</span>' !!}</td>
                                </tr>
                                <tr>
                                    <th>Deskripsi</th>
                                    <td>{{ $produk->deskripsi }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info">Data produk belum dimuat.</div>
                @endif
            </div>

            <div class="modal-footer">
                <a href="javascript:void(0);" class="btn btn-link link-success shadow-none fw-medium"
                    data-bs-dismiss="modal">
                    <i class="ri-close-line me-1 align-middle"></i> Tutup
                </a>
            </div>
        </div>
    </div>
</div>
