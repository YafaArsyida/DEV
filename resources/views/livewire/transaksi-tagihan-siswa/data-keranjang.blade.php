{{-- The whole world belongs to you. --}}
<div class="card">
    <div class="card-body p-4">
        <div class="row g-4 align-items-center mb-2">
            <div class="col-sm-4">
                <p class="text-muted mb-2 text-uppercase fw-semibold">KERANJANG</p>
            </div>
            {{-- <div class="col-sm-auto ms-auto">
                <div class="hstack gap-2">
                    <button type="button" class="btn btn-primary btn-label waves-effect waves-light"><i class="ri-user-smile-line label-icon align-middle align-bottom me-2"></i> Simpan</button>
                    <button type="button" class="btn btn-primary btn-label waves-effect waves-light"><i class="ri-user-smile-line label-icon align-middle align-bottom me-2"></i> Cetak</button>
                </div>
            </div> --}}
        </div>
        <div class="table-responsive">
            <table class="table table-borderless table-hover text-center table-nowrap align-middle mb-0">
                <thead class="table-light">
                    <tr class="table-active">
                        <th class="text-uppercase" scope="col" style="width: 50px;">batal</th>
                        <th class="text-uppercase" scope="col" style="width: 350px;">tagihan</th>
                        <th class="text-uppercase" scope="col">riwayat</th>
                        <th class="text-uppercase" scope="col">bayar</th>
                        <th class="text-uppercase text-end" scope="col">kekurangan</th>
                    </tr>
                </thead>
                <tbody id="products-list">
                    @if($siswaSelected)
                        @forelse($keranjangs as $item)
                            <tr>
                                <th scope="row">
                                    <a href="" class="btn btn-sm btn-soft-danger d-inline-flex align-items-center gap-1" wire:click.prevent="hapusKeranjang({{ $item->ms_keranjang_tagihan_siswa_id }})">
                                        <i class="ri-delete-bin-5-line align-bottom"></i>
                                    </a>
                                </th>
                                <td class="text-start">
                                    <span class="fw-medium">
                                        {{ $item->nama_jenis_tagihan_siswa() }}</span>
                                    <p class="text-muted mb-0">RP{{ number_format($item->jumlah_tagihan_siswa(), 0, ',', '.') }}</p>
                                </td>
                                <td>
                                    <span class="fw-medium">
                                        RP{{ number_format($item->jumlah_sudah_dibayar(), 0, ',', '.') }}
                                    </span>
                                </td>
                                <td><span class="fw-medium text-success">
                                        RP{{ number_format($item->jumlah_bayar, 0, ',', '.') }}
                                    </span>
                                </td>
                               <td class="text-end">
                                    <span class="fw-medium text-danger">
                                        RP{{ number_format($item->jumlah_tagihan_siswa() - ($item->jumlah_sudah_dibayar() + $item->jumlah_bayar), 0, ',', '.') }}
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
                    <tr class="border-top border-top-dashed fs-15">
                        <th scope="row">TOTAL</th>
                        <th class="text-end">RP{{ number_format($totalKeranjang, 2, ',', '.') }}</th>
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
            aria-label="Infaq">
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
                <a href="#" 
                wire:click.prevent="cetakTransaksi({{ $currentTransaksiId }})" 
                class="btn btn-info">
                    <i class="ri-printer-line align-bottom"></i> Cetak
                </a>
            @endif
            {{-- <a href="javascript:void(0);" class="btn btn-primary"><i class="ri-download-2-line align-bottom me-1"></i> Download</a> --}}
        </div>

    </div>
</div>