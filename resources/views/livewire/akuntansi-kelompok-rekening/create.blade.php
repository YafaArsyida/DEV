<div wire:ignore.self class="modal fade" id="CreateKelompokRekening" tabindex="-1" aria-labelledby="CreateKelompokRekening" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title">Kelompok Rekening Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
            </div>
            <form wire:submit.prevent="save">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-lg-12">
                            <label for="nama_kelompok_rekening" class="form-label">Nama Kelompok Rekening</label>
                            <input type="text" wire:model="nama_kelompok_rekening" id="nama_kelompok_rekening"  class="form-control" placeholder=".." />
                            @error('nama_kelompok_rekening') 
                                <footer class="text-danger mt-0">{{ $message }}</footer>
                            @enderror
                        </div>
                        <div class="col-lg-12">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <input type="text" wire:model="deskripsi" class="form-control" placeholder="Kategori laporan khusus SPP..." />
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