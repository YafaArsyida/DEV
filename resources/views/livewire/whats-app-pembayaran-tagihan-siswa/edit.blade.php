<div wire:ignore.self class="modal fade" id="ModalEdit" tabindex="-1" aria-labelledby="ModalEditLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalEditLabel">Edit Pesan Transaksi Tagihan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form wire:submit.prevent="updatePesan">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul</label>
                        <input type="text" class="form-control" id="judul" wire:model.defer="judul">
                        @error('judul') 
                            <footer class="text-danger mt-0">{{ $message }}</footer> 
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="salam_pembuka" class="form-label">Salam Pembuka</label>
                        <input type="text" class="form-control" id="salam_pembuka" wire:model.defer="salam_pembuka">
                        @error('salam_pembuka') 
                            <footer class="text-danger mt-0">{{ $message }}</footer> 
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="kalimat_pembuka" class="form-label">Kalimat Pembuka</label>
                        <textarea class="form-control" id="kalimat_pembuka" rows="2" wire:model.defer="kalimat_pembuka"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="detail_transaksi" class="form-label">Detail Transaksi</label>
                        <p>Kami informasikan bahwa pembayaran melalui <b>Teller Tunai</b> atas nama siswa <b>Yafa Arsyida</b> telah berhasil diproses. Berikut adalah rincian transaksinya :  </p>
                        <p><b> - SPP OKTOBER 2024 - Rp50.XXX,</b></p>
                        <p><b> - SPP NOVEMBER 2024 - Rp100.XXX,</b></p>
                        <p><b> - SPP DESEMBER 2024 - Rp100.XXX,</b></p>
                        <p><b>Infaq RpXXXX</b></p>
                    </div>

                    <div class="mb-3">
                        <label for="kalimat_penutup" class="form-label">Kalimat Penutup</label>
                        <textarea class="form-control" id="kalimat_penutup" rows="2" wire:model.defer="kalimat_penutup"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="salam_penutup" class="form-label">Salam Penutup</label>
                        <input type="text" class="form-control" id="salam_penutup" wire:model.defer="salam_penutup">
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="javascript:void(0);" class="btn btn-link link-success shadow-none fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Tutup</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
