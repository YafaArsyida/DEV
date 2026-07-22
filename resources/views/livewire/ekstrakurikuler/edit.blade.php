{{-- Modal Tambah Ekstrakurikuler --}}
<div wire:ignore.self class="modal fade" id="editEkstrakurikuler" tabindex="-1" aria-labelledby="editEkstrakurikuler" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-trophy-line">
                            </i>
                        </div>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">
                            Perbarui Data Ekstrakurikuler
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
            <form wire:submit.prevent="updateEkstrakurikuler">
                <div class="modal-body">
                    <div class="row g-3">
                        {{-- Nama Ekstrakurikuler --}}
                        <div class="col-lg-12">
                            <label for="nama_ekstrakurikuler" class="form-label">Nama Ekstrakurikuler</label>
                            <input type="text" wire:model.defer="nama_ekstrakurikuler" id="nama_ekstrakurikuler" class="form-control" placeholder="Pramuka, Futsal, Tahfidz..." />
                            @error('nama_ekstrakurikuler') 
                                <footer class="text-danger mt-0">{{ $message }}</footer>
                            @enderror
                        </div>

                        {{-- Biaya --}}
                        <div class="col-lg-6">
                            <label for="biaya" class="form-label">Biaya (Rp)</label>
                            <input type="number" wire:model.defer="biaya" class="form-control" placeholder="Contoh: 50000" />
                            @error('biaya') 
                                <footer class="text-danger mt-0">{{ $message }}</footer>
                            @enderror
                        </div>

                        {{-- Kuota --}}
                        <div class="col-lg-6">
                            <label for="kuota" class="form-label">Kuota</label>
                            <input type="number" wire:model.defer="kuota" class="form-control" placeholder="Contoh: 20" />
                            @error('kuota') 
                                <footer class="text-danger mt-0">{{ $message }}</footer>
                            @enderror
                        </div>

                        {{-- Deskripsi --}}
                        <div class="col-lg-12">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea wire:model.defer="deskripsi" id="deskripsi" class="form-control" rows="2" placeholder="Keterangan singkat tentang ekstrakurikuler..."></textarea>
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