<div class="table-responsive">
    <table class="table table-hover nowrap align-middle" style="width:100%">
        <thead class="table-light">
            <tr>
                <th width="50px">#</th>
                <th class="text-uppercase">Kode</th>
                <th class="text-uppercase">Nama Produk</th>
                <th class="text-uppercase">Kategori</th>
                <th class="text-uppercase">Stok</th>
                <th class="text-uppercase">Harga Beli</th>
                <th class="text-uppercase">Harga Jual</th>
                <th class="text-uppercase">Laba</th>
                <th class="text-uppercase">Status</th>
                <th class="text-uppercase" width="80px">Aksi</th>
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

                    <td class="fs-14 text-warning fw-semibold">
                        Rp{{ number_format($item->harga_jual, 0, ',', '.') }}
                    </td>
                    <td class="fs-14 text-success fw-semibold">
                        Rp{{ number_format($item->keuntungan(), 0, ',', '.') }}
                    </td>

                    <td>
                        @if ($item->status_produk_koperasi === 'aktif')
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-danger">Nonaktif</span>
                        @endif
                    </td>

                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light" 
                                data-bs-toggle="dropdown">
                                <i class="mdi mdi-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="javascript:void(0);" 
                                    class="dropdown-item"
                                    data-bs-toggle="modal"
                                    data-bs-target="#ModalDetailProdukKoperasi"
                                    wire:click="$emit('showDetailProdukKoperasi', {{ $item->ms_produk_koperasi_id }})">
                                    Detail
                                </a>

                                <a href="javascript:void(0);"
                                    class="dropdown-item"
                                    data-bs-toggle="modal"
                                    data-bs-target="#ModalEditProdukKoperasi"
                                    wire:click="$emit('showEditProdukKoperasi', {{ $item->ms_produk_koperasi_id }})">
                                    Edit
                                </a>

                                <a href="javascript:void(0);" 
                                    class="dropdown-item text-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#ModalDeleteProdukKoperasi"
                                    wire:click="$emit('confirmDeleteProdukKoperasi', {{ $item->ms_produk_koperasi_id }})">
                                    Hapus
                                </a>
                            </div>
                        </div>
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
