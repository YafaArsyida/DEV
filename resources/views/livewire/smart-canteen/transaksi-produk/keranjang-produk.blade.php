{{-- MOCKUP KERANJANG KANTIN --}}
<div class="card border-0 shadow-sm rounded-4 overflow-hidden sticky-side-div">
    <div class="card-header">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">
            {{-- TITLE --}}
            <div>
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-store-2-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1">
                            Keranjang Produk
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-nowrap align-middle" style="width:100%">
                <thead class="table-light">
                    <tr class="table-active text-uppercase text-center">
                        {{-- <th style="width: 50px;">Batal</th> --}}
                        <th class="text-start">Produk</th>
                        <th class="text-center">Jumlah</th>
                        <th class="text-end">Harga</th>
                    </tr>
                </thead>
                <tbody>
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
                <option value="Tunai">Tunai</option>
                <option value="EduPay" {{ !$user_id ? 'disabled' : '' }}>
                    EduPay
                </option>
                <option value="QRIS">QRIS</option>
                <option value="Transfer">Transfer</option>
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
