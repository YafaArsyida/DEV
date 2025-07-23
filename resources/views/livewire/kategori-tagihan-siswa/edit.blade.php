<div>
    {{-- Success is as dangerous as failure. --}}
    <div wire:ignore.self class="modal fade" id="ModalEditKategoriTagihan" tabindex="-1" aria-labelledby="ModalEditKategoriTagihan" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title">Edit Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
                </div>
                <form wire:submit.prevent="updateKategori">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-lg-8">
                                <label for="nama_kategori_tagihan_siswa" class="form-label">Nama Kategori Tagihan</label>
                                <input type="text" wire:model.defer="nama_kategori_tagihan_siswa" id="nama_kategori_tagihan_siswa"  class="form-control" placeholder="SPP/ UANG MAKAN/ TRANSPORT ......." />
                                @error('nama_kategori_tagihan_siswa') 
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
                                <input type="text" wire:model.defer="deskripsi" class="form-control" placeholder="Kategori laporan khusus SPP..." />
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
