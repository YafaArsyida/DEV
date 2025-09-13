{{-- MOCKUP KERANJANG KANTIN --}}
<div class="card">
    <div class="card-body p-4">
        <div class="row g-4 align-items-center mb-2">
            <div class="col-sm-12">
                <p class="text-muted mb-2 text-uppercase fw-semibold">Keranjang Kantin</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-borderless align-middle mb-0">
                <thead class="table-light">
                    <tr class="table-active text-uppercase text-center">
                        <th style="width: 50px;">Batal</th>
                        <th class="text-start">Produk</th>
                        <th class="text-start">Jumlah</th>
                        <th class="text-end">Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($user_id)
                        @forelse($keranjang as $item)
                            <tr>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-soft-danger"
                                            wire:click="hapusKeranjang({{ $item->ms_keranjang_smartcanteen_id }})">
                                        <i class="ri-delete-bin-5-line"></i>
                                    </button>
                                </td>
                                <td class="text-start">
                                    <span class="fs-12 fw-semibold text-uppercase">{{ $item->ms_produk_kantin->nama_produk_kantin ?? '-' }}</span>
                                    <p class="text-muted mb-0">RP{{ number_format($item->ms_produk_kantin->harga, 0, ',', '.') }}</p>
                                </td>
                                <td class="text-start text-uppercase">
                                    <span class="fs-12 fw-semibold text-uppercase">{{ $item->jumlah_produk }} {{ $item->ms_produk_kantin->satuan }}</span>
                                </td>
                                <td class="text-end fw-medium text-success fs-14">
                                    Rp {{ number_format(($item->ms_produk_kantin->harga ?? 0) * $item->jumlah_produk, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Keranjang masih kosong</td>
                            </tr>
                        @endforelse
                    @else
                        <tr>
                            <td colspan="3" class="text-center">Silakan scan kartu</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="border-top border-top-dashed mt-2">
            <table class="table table-borderless table-nowrap align-middle mb-0 ms-auto" style="width:250px">
                <tbody>
                    <tr class="border-top border-top-dashed fs-15">
                        <th scope="row">TOTAL</th>
                        <th class="text-end">RP{{ number_format($totalKeranjang, 2, ',', '.') }}</th>
                    </tr>
                </tbody>
            </table>
            <!--end table-->
        </div>

        {{-- Aksi --}}
        <div class="hstack gap-2 justify-content-end mt-4">
            <a href="#" class="btn btn-success">
                <i class="ri-shopping-cart-2-line align-bottom"></i> Bayar
            </a>
        </div>
    </div>
</div>
