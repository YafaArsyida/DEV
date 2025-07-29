{{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
<div wire:ignore.self class="modal fade" id="editKuitansiTransaksi" tabindex="-1" aria-labelledby="editKuitansiTransaksiLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editKuitansiTransaksiLabel">Edit Kuitansi Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form wire:submit.prevent="updateKuitansi">
                <div class="modal-body">
                    @if ($logo_baru && is_object($logo_baru))
                        <div class="mb-3 text-center">
                            <p>Preview Logo Baru:</p>
                            <img src="{{ $logo_baru->temporaryUrl() }}" alt="Preview Logo Baru" class="" height="80px">
                        </div>
                    @elseif ($logo)
                        <div class="mb-3 text-center">
                            <p>Logo Lama:</p>
                            <img src="{{ Storage::url($logo) }}" alt="Logo Lama" class="" height="80px">
                        </div>
                    @else
                        <h6 class="text-muted">Belum ada foto kop yang diunggah.</h6>
                    @endif
                    
                    <div class="mb-3">
                        <label for="logo_baru" class="form-label">Unggah Logo Baru</label>
                        <input type="file" class="form-control" id="logo_baru" wire:model="logo_baru" accept="image/*">
                        @error('logo_baru') 
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
                <div class="modal-footer">
                    <a href="javascript:void(0);" class="btn btn-link link-success shadow-none fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Tutup</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

