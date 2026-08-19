{{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
<div wire:ignore.self class="modal fade" id="loadPesanEduPay" tabindex="-1" aria-labelledby="loadPesanEduPayLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-calendar-event-line">
                            </i>
                        </div>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">
                            Perbarui Pesan Transaksi EduPay
                        </h5>
                        {{-- <small>
                            Tambahkan agenda kegiatan generasi penerus dengan pengaturan tingkat, jadwal, dan lokasi.
                        </small> --}}
                    </div>
                </div>
                <button type="button" class="btn btn-light btn-icon rounded-circle" data-bs-dismiss="modal">
                    <i class="ri-close-line fs-18">
                    </i>
                </button>
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
                        <p>Kami informasikan bahwa <b>Transaksi EduPay</b> atas nama siswa <b>Yafa Arsyida</b> telah berhasil. Berikut adalah rincian transaksinya :  </p>
                        <p><b>TopUp/Tarik Tunai - Rp. 50.XXX,</b></p>
                        <p><b>Saldo EduPay - Rp. 500.XXX,</b></p>
                        <p><b>Top Up untuk pembayaran SPP Sepxxxx, SPP Oktxxx</b></p>
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
                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="ri-save-3-line me-1"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

