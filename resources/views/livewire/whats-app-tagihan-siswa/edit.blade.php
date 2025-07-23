{{-- Care about people's approval and you will be their prisoner. --}}
<div wire:ignore.self class="modal fade" id="ModalEditTagihanSiswa" tabindex="-1" aria-labelledby="ModalEditTagihanSiswaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalEditTagihanSiswaLabel">Edit Pesan Tagihan Siswa</h5>
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
                        <label for="detail_transaksi" class="form-label">Detail Tagihan (bawaan sistem)</label>
                        <p>Kami informasikan bahwa <b>Tagihan Sekolah</b> atas nama siswa <b>Yafa Arsyida</b> kelas <b>2 A Umar</b> masih perlu diselesaikan. Berikut adalah rincian tagihannya : </p>
                        <table cellpadding="1" class="mb-3">
                            <tr>
                                <td><b>- SPP FEBRUARI : Rp100.XXX</b></td>
                            </tr>
                            <tr class="mb-3">
                                <td><b>- Transport MARET : Rp50.XXX</b></td>
                            </tr>
                        </table> 
                        <p><b>Total Tagihan Rp9XX.XXX</b></p>  
                    </div>
                    
                    <div class="mb-3">
                        <label for="detail_transaksi" class="form-label">Instruksi (sama seperti template surat)</label>
                        <table cellpadding="1" class="mb-3">
                            <tr>
                                <td>{!! $surat->panduan !!}</td>
                            </tr>
                            <tr>
                                <td>{!! $surat->instruksi_1 !!}</td>
                            </tr>
                            <tr>
                                <td>{!! $surat->instruksi_2 !!}</td>
                            </tr>
                            <tr>
                                <td>{!! $surat->instruksi_3 !!}</td>
                            </tr>
                            <tr>
                                <td>{!! $surat->instruksi_4 !!}</td>
                            </tr>
                            <tr>
                                <td>{!! $surat->instruksi_5 !!}</td>
                            </tr>
                        </table>        
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
