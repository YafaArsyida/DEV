{{-- Nothing in the world is as soft and yielding as water. --}}
<div>
    <div wire:ignore.self class="modal fade" id="ModalEditJenjang" tabindex="-1" aria-labelledby="ModalEditJenjangLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title" id="ModalEditJenjangLabel">edit jenjang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="updateJenjang">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-lg-6">
                                <label for="nama_jenjang" class="form-label">Nama Jenjang</label>
                                <input type="text" id="nama_jenjang" wire:model="nama_jenjang" class="form-control">
                                @error('nama_jenjang') 
                                    <footer class="text-danger mt-0">{{ $message }}</footer> 
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label for="urutan" class="form-label">Urutan</label>
                                <input type="number" wire:model="urutan" class="form-control">
                                @error('urutan') 
                                    <footer class="text-danger mt-0">{{ $message }}</footer> 
                                @enderror
                            </div>
                            <div class="col-lg-12">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea id="deskripsi" wire:model="deskripsi" class="form-control"></textarea>
                            </div>
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
</div>
