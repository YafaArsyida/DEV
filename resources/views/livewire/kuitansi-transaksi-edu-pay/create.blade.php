{{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
<div wire:ignore.self class="modal fade" id="createKuitansiEduPay" tabindex="-1" aria-labelledby="createKuitansiEduPayLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-bank-card-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1" id="createKuitansiEduPayLabel">
                            Buat Template Kuitansi EduPay
                        </h5>

                        <small class="text-muted">
                            Atur format dan isi kuitansi transaksi EduPay.
                        </small>
                    </div>
                </div>

                <button
                    type="button" class="btn btn-light btn-icon rounded-circle"
                    data-bs-dismiss="modal" aria-label="Close">
                    <i class="ri-close-line fs-18"></i>
                </button>
            </div>
            <form wire:submit.prevent="createKuitansi">
                <div class="modal-body">
                    @if ($logo && is_object($logo))
                        <div class="mb-3 text-center">
                            <p>Preview Logo Baru:</p>
                            <img src="{{ $logo->temporaryUrl() }}" alt="Preview Logo Baru" class="" height="80px">
                        </div>
                    @else
                        <h6 class="text-muted">Belum ada foto kop yang diunggah.</h6>
                    @endif
                    
                    <div class="mb-3">
                        <label for="logo" class="form-label">Unggah Logo Baru</label>
                        <input type="file" class="form-control" id="logo" wire:model="logo" accept="image/*">
                        @error('logo') 
                            <footer class="text-danger mt-0">{{ $message }}</footer> 
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="nama_institusi" class="form-label">Nama Institusi</label>
                        <input type="text" class="form-control fs-17" id="nama_institusi" wire:model.defer="nama_institusi">
                        @error('nama_institusi') 
                            <footer class="text-danger mt-0">{{ $message }}</footer> 
                        @enderror
                    </div>
                    <div class="row mb-3">
                        <div class="col-xxl-6">
                            <div class="">
                                <label for="alamat" class="form-label">Alamat</label>
                                <input type="text" class="form-control" id="alamat" wire:model.defer="alamat">
                                @error('alamat') 
                                    <footer class="text-danger mt-0">{{ $message }}</footer> 
                                @enderror
                            </div>
                        </div>
                        <div class="col-xxl-6">
                            <div class="">
                                <label for="kontak" class="form-label">Kontak</label>
                                <input type="text" class="form-control" id="kontak" wire:model.defer="kontak">
                                @error('kontak') 
                                    <footer class="text-danger mt-0">{{ $message }}</footer> 
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul</label>
                        <input type="text" class="form-control fs-17" id="judul" wire:model.defer="judul">
                        @error('judul') 
                            <footer class="text-danger mt-0">{{ $message }}</footer> 
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="pesan" class="form-label">Catatan</label>
                        <textarea class="form-control" id="pesan" wire:model.defer="pesan" rows="2"></textarea>
                        @error('pesan') 
                            <footer class="text-danger mt-0">{{ $message }}</footer> 
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="tempat" class="form-label">Kota / Kabupaten</label>
                        <input type="text" class="form-control" id="tempat" wire:model.defer="tempat">
                        @error('tempat') 
                            <footer class="text-danger mt-0">{{ $message }}</footer> 
                        @enderror
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="ri-save-3-line me-1"></i>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

