{{-- The whole world belongs to you. --}}
<div wire:ignore.self class="modal fade" id="editPendapatanLainnya" tabindex="-1" aria-labelledby="editPendapatanLainnyaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-edit-2-line"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">
                            Perbarui Data Transaksi
                        </h5>
                        <small class="text-muted">
                            Perbarui informasi transaksi sesuai kebutuhan.
                        </small>
                    </div>
                </div>
                <button type="button" class="btn btn-light btn-icon rounded-circle" data-bs-dismiss="modal">
                    <i class="ri-close-line fs-18">
                    </i>
                </button>
            </div>
            <div class="modal-body">
                @if($transaksi)
                <table class="table table-nowrap mb-0 ">
                    <tbody>
                        <tr>
                            <th scope="row">Transaksi</th>
                            <td>
                                {{ $transaksi->akuntansi_rekening->nama_rekening }}</i>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Nominal</th>
                            <td>
                                <span class="fs-12 fw-medium ">
                                    Rp{{ number_format($transaksi->nominal, 0, ',', '.') }} - {{ $transaksi->metode_pembayaran }}
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
                            <th scope="row">Perubahan Tanggal</th>
                            <td>
                                <div class="input-group input-group-sm">
                                    <input type="date" class="form-control" wire:model.defer="tanggal">
                                    {{-- <input type="datetime-local" class="form-control" wire:model.defer="tanggal" aria-label="Tanggal Transaksi"> --}}
                                </div>
                                @error('tanggal') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Perubahan Deskripsi</th>
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
            <div class="modal-footer border-0 px-4 pb-4 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>
                    Tutup
                </button>
                @if($transaksi)
                <button class="btn btn-primary rounded-pill px-4" wire:click.prevent="updateTransaksi">
                    <i class="ri-save-3-line me-1"></i>
                    Simpan
                </button>
                @endif
            </div>
        </div>
    </div>
</div>
