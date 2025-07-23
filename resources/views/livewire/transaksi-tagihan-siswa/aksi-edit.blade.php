{{-- The whole world belongs to you. --}}
<div wire:ignore.self class="modal fade" id="ModalAksiEdit" tabindex="-1" aria-labelledby="ModalAksiEditLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalAksiEditLabel">Edit Tagihan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table mb-0">
                    <tbody>
                        @if($tagihan)
                        <tr>
                            <th scope="row" style="width: 150px;">Tagihan</th>
                            <td>
                                <span class="fw-medium">{{ $tagihan->ms_jenis_tagihan_siswa->nama_jenis_tagihan_siswa }} - <i>{{ $tagihan->nama_kategori_tagihan_siswa() }}</i></span>
                                <p class="text-mute mb-0">RP{{ number_format($tagihan->jumlah_tagihan_siswa, 0, ',', '.') }}</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Dibayarkan</th>
                            <td>
                                <span class="fw-medium text-success">
                                    RP{{ number_format($tagihan->jumlah_sudah_dibayar(), 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Kekurangan</th>
                            <td>
                                <span class="fw-medium text-danger">
                                    RP{{ number_format($tagihan->jumlah_tagihan_siswa - $tagihan->jumlah_sudah_dibayar(), 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Perubahan Tagihan</th>
                            <td>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">RP</span>
                                    <input type="number" class="form-control" wire:model.defer="jumlah_perubahan_tagihan" min="0" step="0.01" aria-label="Amount">
                                </div>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <a href="javascript:void(0);" class="btn btn-link link-success shadow-none fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Tutup</a>
                @if($tagihan)
                <button type="button" class="btn btn-primary" 
                    wire:click="aksiEdit({{ $tagihan->ms_tagihan_id }})">
                    <i class="ri-printer-line align-bottom me-1"></i> Edit
                </button>
                @endif

            </div>
        </div>
    </div>
</div>
