{{-- The whole world belongs to you. --}}
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header border-0 pb-1">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-sm">
                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                        <i class="ri-calendar-event-line"></i>
                    </div>
                </div>

                <div>
                    <h5 class="fw-bold mb-0">
                        Keranjang Transaksi
                    </h5>

                    <small class="text-muted">
                        Kelola item dalam keranjang transaksi
                    </small>
                </div>

            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-borderless table-hover table-nowrap align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-uppercase text-center" scope="col" style="width: 50px;">hapus</th>
                        <th class="text-uppercase" scope="col" style="width: 350px;">tagihan</th>
                        <th class="text-uppercase text-end" scope="col">bayar</th>
                    </tr>
                </thead>
                <tbody id="products-list">
                    @if($siswaSelected)
                        @forelse($keranjangs as $item)
                            <tr>
                                <th class="text-center" scope="row">
                                    <a class="text-danger d-inline-block remove-item-btn cursor-pointer" 
                                        wire:click.prevent="hapusKeranjang({{ $item->ms_keranjang_tagihan_siswa_id }})"
                                        wire:loading.attr="disabled"
                                        data-bs-trigger="hover"
                                        data-bs-placement="top"
                                        title="Hapus Item">
                                        <i class="ri-delete-bin-5-fill fs-14"></i>
                                    </a>
                                </th>
                                <td>
                                    <span class="fw-medium">{{ $item->ms_tagihan_siswa->ms_jenis_tagihan_siswa->nama_jenis_tagihan_siswa ?? '-'  }}</span>
                                    <p class="text-muted mb-0">
                                        RP{{ number_format($item->ms_tagihan_siswa->jumlah_tagihan_siswa ?? 0, 0, ',', '.') }}
                                    </p>
                                </td>
                        
                                {{-- Dibayar Sekarang (di keranjang) --}}
                                <td class="text-end">
                                    <span class="fw-medium fs-12">
                                        RP{{ number_format($item->jumlah_bayar, 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data keranjang untuk siswa ini.</td>
                            </tr>
                        @endforelse
                    @else
                        <tr>
                            <td colspan="5" class="text-center">Silakan pilih siswa terlebih dahulu.</td>
                        </tr>
                    @endif
                </tbody>
            </table><!--end table-->
        </div>
        <div class="border-top border-top-dashed mt-2">
            <table class="table table-borderless table-nowrap align-middle mb-0 ms-auto" style="width:250px">
                <tbody>
                    <tr class="border-top border-top-dashed fs-14">
                        <th scope="row">TOTAL</th>
                        <th class="text-end">RP{{ number_format($totalKeranjang, 0, ',', '.') }}</th>
                    </tr>
                </tbody>
            </table>
            <!--end table-->
        </div>

        <div class="hstack gap-2 justify-content-end d-print-none mt-4">
            <input type="text" 
            class="form-control" 
            wire:model.defer="deskripsi" 
            placeholder="deskripsi transaksi (bila perlu)" 
            aria-label="Deskripsi">
            <select class="form-select w-auto" 
                    wire:model.defer="metode_pembayaran" 
                    aria-label="Pilih metode pembayaran">
                <option value="Teller Tunai">Teller Tunai</option>
                <option value="Transfer ke Rekening Sekolah">Transfer ke Rekening Sekolah</option>
                <option value="EduPay">EduPay</option>
            </select>
            <a href="" 
                wire:click.prevent="simpanTransaksi" 
                class="btn btn-success">
                <i class="ri-shopping-cart-2-line align-bottom"></i> Bayar
            </a>
            @if($currentTransaksiId)
                <a
                wire:click.prevent="cetakTransaksi({{ $currentTransaksiId }})" 
                class="btn btn-info">
                    <i class="ri-printer-line align-bottom"></i> Cetak
                </a>
            @endif
        </div>
    </div>
</div>