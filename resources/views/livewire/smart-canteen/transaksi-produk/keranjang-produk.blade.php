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
                        {{-- <th style="width: 50px;">Batal</th> --}}
                        <th class="text-start">Produk</th>
                        <th class="text-center">Jumlah</th>
                        <th class="text-end">Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($user_id)
                        @forelse($keranjang as $index => $item)
                        <tr>
                            <td class="text-start">
                                <span class="fs-12 fw-semibold text-uppercase">
                                    {{ $item['nama'] }}
                                </span>
                                <p class="text-muted mb-0">
                                    RP{{ number_format($item['harga'], 0, ',', '.') }}
                                </p>
                            </td>
                        
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <button class="btn btn-sm btn-light" wire:click="decrementQty({{ $index }})">–</button>
                        
                                    <input class="form-control form-control-sm text-center" style="width:50px" value="{{ $item['jumlah'] }}"
                                        readonly>
                        
                                    <button class="btn btn-sm btn-light" wire:click="incrementQty({{ $index }})">+</button>
                                </div>
                            </td>
                        
                            <td class="text-end fw-medium fs-14 text-success">
                                RP{{ number_format($item['subtotal'], 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">
                                Keranjang masih kosong
                            </td>
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
                        <th class="text-end">RP{{ number_format($this->totalKeranjang, 0, ',', '.') }}</th>
                    </tr>
                </tbody>
            </table>
            <!--end table-->
        </div>

        {{-- Aksi --}}
        <div class="hstack gap-2 justify-content-end mt-4">
            <select class="form-select w-auto" 
                    wire:model.defer="metode_pembayaran" 
                    aria-label="Pilih metode pembayaran">
                {{-- <option value="Tunai">Tunai</option> --}}
                <option value="EduPay">EduPay</option>
            </select>
            {{-- <a href="#ModalScanRFID" data-bs-toggle="modal" 
                wire:click.prevent="simpanTransaksiKantin"
                x-on:click="$wire.emit('openScanModal')"
                    class="btn btn-success">
                <i class="ri-shopping-cart-2-line align-bottom"></i> Bayar
            </a> --}}
            <a href="" wire:click.prevent="simpanTransaksiKantin" class="btn btn-success">
                <i class="ri-shopping-cart-2-line align-bottom"></i> Bayar
            </a>
        </div>
    </div>
</div>
