{{-- The best athlete wants his opponent at his best. --}}
<div>
    <div wire:ignore.self class="modal fade" id="ModalAddJabatan" tabindex="-1" aria-labelledby="ModalAddJabatan" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title">Jabatan Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
                </div>
                <form id="formAddJenjang" wire:submit.prevent="save">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-lg-12">
                                <label for="nama_jabatan" class="form-label">Nama Jabatan</label>
                                <input type="text" wire:model="nama_jabatan" id="nama_jabatan"  class="form-control" placeholder="SD/SMP/SMA" />
                                @error('nama_jabatan') 
                                    <footer class="text-danger mt-0">{{ $message }}</footer>
                                @enderror
                            </div>
                            <div class="col-lg-12">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <input type="text" wire:model="deskripsi" class="form-control" placeholder="SD N Alamanah/SMP Al..." />
                                @error('deskripsi') 
                                    <footer class="text-danger mt-0">{{ $message }}</footer>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="javascript:void(0);" class="btn btn-link link-success shadow-none fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Tutup</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
