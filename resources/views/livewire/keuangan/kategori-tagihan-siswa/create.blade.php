{{-- Success is as dangerous as failure. --}}
<div wire:ignore.self class="modal fade" id="ModalAddKategoriTagihan" tabindex="-1" aria-labelledby="ModalAddKategoriTagihan" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-price-tag-3-line"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">
                            Kategori Baru
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
            <form wire:submit.prevent="save">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-lg-8">
                            <label for="nama_kategori_tagihan_siswa" class="form-label">Nama Kategori Tagihan</label>
                            <input type="text" wire:model.defer="nama_kategori_tagihan_siswa" id="nama_kategori_tagihan_siswa"  class="form-control @error('nama_kategori_tagihan_siswa') is-invalid @enderror" placeholder="SPP/ UANG MAKAN/ TRANSPORT ......." />
                            @error('nama_kategori_tagihan_siswa') 
                                <footer class="text-danger mt-0">{{ $message }}</footer>
                            @enderror
                        </div>
                        <div class="col-lg-4">
                            <label for="urutan" class="form-label">Urutan</label>
                            <input type="number" wire:model.defer="urutan" class="form-control @error('urutan') is-invalid @enderror" placeholder="1, 2, 3, ..." />
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