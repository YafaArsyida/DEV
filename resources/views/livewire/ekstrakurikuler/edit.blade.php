<div>
    {{-- Modal Tambah Ekstrakurikuler --}}
    <div wire:ignore.self class="modal fade" id="editEkstrakurikuler" tabindex="-1" aria-labelledby="editEkstrakurikuler" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title">Tambah Ekstrakurikuler</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
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

                    <div class="modal-footer">
                        <a href="javascript:void(0);" class="btn btn-link link-secondary shadow-none fw-medium" data-bs-dismiss="modal">
                            <i class="ri-close-line me-1 align-middle"></i> Tutup
                        </a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
