<div class="table-responsive">
    <table class="table table-hover nowrap align-middle" style="width:100%">
        <thead class="table-light">
            <tr>
                <th width="50px">#</th>
                <th class="text-uppercase">Kode</th>
                <th class="text-uppercase">Produk</th>
                <th class="text-uppercase">Kategori</th>
                <th class="text-uppercase">Stok</th>
                <th class="text-uppercase">Harga Beli</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($listProduk as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>

                    <td class="fs-14 fw-semibold">{{ $item->kode_produk_koperasi }}</td>

                    <td class="fw-semibold text-uppercase">
                        {{ $item->nama_produk_koperasi }}
                    </td>
                    <td>
                        {{ $item->ms_kategori_produk_koperasi?->nama_kategori_produk_koperasi ?? '-' }}
                    </td>
                    <td class="fs-14 {{ $item->stok > 0 ? 'text-primary' : 'text-danger' }}">
                            {{ $item->stok }} {{ $item->satuan ?? '-' }}
                    </td>
                    <td class="fs-14 text-info fw-semibold">
                        Rp{{ number_format($item->harga_beli, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11">
                        <div class="alert alert-info text-center mb-0">
                            Tidak ada produk koperasi ditemukan.
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
