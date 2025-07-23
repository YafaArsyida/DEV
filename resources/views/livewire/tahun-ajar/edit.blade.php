<div>
    {{-- In work, do what you enjoy. --}}
    <div wire:ignore.self class="modal fade" id="ModalEditTahunAjar" tabindex="-1" aria-labelledby="ModalEditTahunAjar" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title">edit tahun ajar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
                </div>
                <form wire:submit.prevent="updateTahunAjar">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-lg-8">
                                <label class="form-label">Nama Tahun Ajar</label>
                                <input type="text" wire:model="nama_tahun_ajar" id="nama_tahun_ajar" class="form-control" placeholder="2024 - 2025..." />
                                @error('nama_tahun_ajar') 
                                    <footer class="text-danger mt-0">{{ $message }}</footer>
                                @enderror
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label">Urutan</label>
                                <input type="number" wire:model="urutan" class="form-control" placeholder="1,2,3...." />
                                @error('urutan') 
                                    <footer class="text-danger mt-0">{{ $message }}</footer>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" wire:model="tanggal_mulai" class="form-control" />
                                @error('tanggal_mulai') 
                                    <footer class="text-danger mt-0">{{ $message }}</footer>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">Tanggal Selesai</label>
                                <input type="date" wire:model="tanggal_selesai" class="form-control" />
                                @error('tanggal_selesai') 
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
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
