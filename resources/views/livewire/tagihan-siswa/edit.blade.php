{{-- The whole world belongs to you. --}}
<div wire:ignore.self class="modal fade" id="editHistoriTagihan" tabindex="-1" aria-labelledby="editHistoriTagihanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editHistoriTagihanLabel">Edit Tanggal Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if($transaksi)
                <table class="table">
                    <tbody>
                        <thead>
                            {{-- looping detail transaksi dan nominal --}}
                            @foreach ($transaksi->dt_transaksi_tagihan_siswa as $detail)
                            <tr>
                                <th style="width: 50px;">{{ $loop->iteration }}</th>
                                <th scope="row">{{ $detail->ms_tagihan_siswa->ms_jenis_tagihan_siswa->nama_jenis_tagihan_siswa }}</th>
                                <td>
                                    <span class="fs-14 fw-semibold text-success">
                                        Rp{{ number_format($detail->jumlah_bayar, 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </thead>
                    </tbody>
                </table>
                <table class="table mb-0">
                    <tbody>
                        <tr>
                            <th scope="row">Petugas</th>
                            <td>
                                {{ $transaksi->ms_pengguna->nama }} - {{ $transaksi->metode_pembayaran }}
                                {{-- <span class="fs-14 fw-semibold text-warning">
                                </span> --}}
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Tanggal Transaksi</th>
                            <td>
                                {{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($transaksi->tanggal_transaksi, 'd F Y H:i:s') }}
                                {{-- <span class="fs-14 fw-semibold text-info">
                                </span> --}}
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
                                    <input type="datetime-local" class="form-control" wire:model.defer="tanggalTransaksi" aria-label="Tanggal Transaksi">
                                </div>
                                @error('tanggalTransaksi') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                            </td>
                        </tr>
                        <tr>
                            <th scope="row" class="text-warning">Perubahan Deskripsi</th>
                            <td>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" wire:model.defer="deskripsi" placeholder="Ubah keterangan (opsional)">
                                </div>
                                @error('tanggalTransaksi') <span class="text-danger text-sm">{{ $message }}</span> @enderror
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
                    wire:click.prevent="updateTanggalTransaksi">
                    <i class="ri-printer-line align-bottom me-1"></i> Edit
                </button>
                @endif

            </div>
        </div>
    </div>
</div>
