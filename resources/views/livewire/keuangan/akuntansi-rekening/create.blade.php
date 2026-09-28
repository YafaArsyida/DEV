{{-- Success is as dangerous as failure. --}}
<div wire:ignore.self class="modal fade" id="CreateAkuntansiRekening" tabindex="-1" aria-labelledby="CreateAkuntansiRekening" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title">Akuntansi Rekening Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
            </div>
            <form wire:submit.prevent="save">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="row my-3">
                            <div class="col-lg-8">
                                <label for="nama_rekening" class="form-label">Nama Rekening</label>
                                <input type="text" wire:model.defer="nama_rekening" id="nama_rekening"  class="form-control" placeholder="SPP/ UANG MAKAN/ TRANSPORT ......." />
                                @error('nama_rekening') 
                                    <footer class="text-danger mt-0">{{ $message }}</footer>
                                @enderror
                            </div>
                            <div class="col-lg-4">
                                <label for="posisi_normal" class="form-label">Posisi Normal</label>
                                <select id="posisi_normal" wire:model.defer="posisi_normal" class="form-select">
                                    <option value="debit">Debit</option>
                                    <option value="kredit">Kredit </option>
                                </select>
                                @error('posisi_normal') 
                                    <footer class="text-danger mt-0">{{ $message }}</footer>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="kode_rekening" class="form-label">Kode Rekening</label>
                                <input type="text" wire:model.defer="kode_rekening" id="kode_rekening"  class="form-control" placeholder="SPP/ UANG MAKAN/ TRANSPORT ......." />
                                @error('kode_rekening') 
                                    <footer class="text-danger mt-0">{{ $message }}</footer>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label for="akuntansi_kelompok_rekening_id" class="form-label">Kelompok Rekening</label>
                                <select id="akuntansi_kelompok_rekening_id" wire:model.defer="akuntansi_kelompok_rekening_id" class="form-select">
                                    <option value="">Pilih Kelompok</option>
                                    @foreach ($select_kelompok as $item)    
                                    <option value="{{ $item->akuntansi_kelompok_rekening_id }}">{{ $item->nama_kelompok_rekening }} </option>
                                    @endforeach
                                </select>
                                @error('akuntansi_kelompok_rekening_id') 
                                    <footer class="text-danger mt-0">{{ $message }}</footer>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <input type="text" wire:model.defer="deskripsi" class="form-control" placeholder="jenis laporan khusus SPP..." />
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