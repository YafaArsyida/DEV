{{-- The whole world belongs to you. --}}
<div wire:ignore.self class="modal fade" id="loadTransaksiTabungan" tabindex="-1" aria-labelledby="loadTransaksiTabunganLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loadTransaksiTabunganLabel">Edit Tanggal Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if($transaksi)
                <table class="table mb-0">
                    <tbody>
                        <tr>
                            <th scope="row">Nominal</th>
                            <td>
                                <span class="text-success fs-14 fw-semibold ">
                                    Rp{{ number_format($transaksi->nominal, 0, ',', '.') }} - {{ $transaksi->jenis_transaksi }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Petugas</th>
                            <td>
                                {{ $transaksi->ms_pengguna->nama }} 
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Tanggal Transaksi</th>
                            <td>
                                {{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($transaksi->tanggal, 'd F Y H:i:s') }}
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Keterangan</th>
                            <td>
                                {{ $transaksi->deskripsi }}
                            </td>
                        </tr>

                        <tr>
                            <th scope="row" class="text-warning">Perubahan Tanggal</th>
                            <td>
                                <div class="input-group input-group-sm">
                                    <input type="date" class="form-control" wire:model.defer="tanggal">
                                    {{-- <input type="datetime-local" class="form-control" wire:model.defer="tanggal" aria-label="Tanggal Transaksi"> --}}
                                </div>
                                @error('tanggal') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                            </td>
                        </tr>
                        <tr>
                            <th scope="row" class="text-warning">Perubahan Deskripsi</th>
                            <td>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" wire:model.defer="deskripsi" placeholder="Ubah keterangan (opsional)">
                                </div>
                                @error('tanggal') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                            </td>
                        </tr>
                    </tbody>
                </table>
                @endif
            </div>
            <div class="modal-footer">
                <a href="javascript:void(0);" class="btn btn-link link-success shadow-none fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Tutup</a>
                @if($transaksi)
                <button type="button" class="btn btn-primary" 
                    wire:click.prevent="updateTransaksi">
                    <i class="ri-printer-line align-bottom me-1"></i> Edit
                </button>
                @endif

            </div>
        </div>
    </div>
</div>
