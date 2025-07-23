<div>
    {{-- Success is as dangerous as failure. --}}
    <div wire:ignore.self class="modal fade" id="ModalAddKelas" tabindex="-1" aria-labelledby="ModalAddKelas" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title">kelas baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-lg-8">
                                <label for="nama_kelas" class="form-label">Nama Kelas</label>
                                <input type="text" wire:model.defer="nama_kelas" id="nama_kelas"  class="form-control" placeholder="7 A/ VII D Umar Bin Khatab..." />
                                @error('nama_kelas') 
                                    <footer class="text-danger mt-0">{{ $message }}</footer>
                                @enderror
                            </div>
                            <div class="col-lg-4">
                                <label for="urutan" class="form-label">Urutan</label>
                                <input type="number" wire:model.defer="urutan" class="form-control" placeholder="1, 2, 3, ..." />
                                @error('urutan') 
                                    <footer class="text-danger mt-0">{{ $message }}</footer>
                                @enderror
                            </div>
                            <div class="col-lg-12">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <input type="text" wire:model.defer="deskripsi" class="form-control" placeholder="Kelas khusus/unggulan santri..." />
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
