<div>
    {{-- Success is as dangerous as failure. --}}
    <div wire:ignore.self class="modal fade" id="ModalAddJenisTagihan" tabindex="-1" aria-labelledby="ModalAddJenisTagihan" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title">Jenis Tagihan Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-lg-12">
                                <label for="nama_jenis_tagihan_siswa" class="form-label">Nama Jenis Tagihan</label>
                                <input type="text" 
                                       wire:model.defer="nama_jenis_tagihan_siswa" 
                                       id="nama_jenis_tagihan_siswa"  
                                       class="form-control" 
                                       placeholder="SPP / UANG MAKAN / TRANSPORT ......." />
                                @error('nama_jenis_tagihan_siswa') 
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            {{-- Kategori --}}
                            <div class="col-lg-4">
                                <label for="ms_kategori_tagihan_siswa_id" class="form-label">Kategori</label>
                                <select id="ms_kategori_tagihan_siswa_id" 
                                        wire:model.defer="ms_kategori_tagihan_siswa_id" 
                                        class="form-select">
                                    <option value="">Pilih Kategori</option>
                                    @foreach ($select_kategori as $item)    
                                        <option value="{{ $item->ms_kategori_tagihan_siswa_id }}">
                                            {{ $item->nama_kategori_tagihan_siswa }} 
                                        </option>
                                    @endforeach
                                </select>
                                @error('ms_kategori_tagihan_siswa_id') 
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Tanggal Jatuh Tempo --}}
                            <div class="col-lg-8">
                                <label for="tanggal_jatuh_tempo" class="form-label">Tanggal Jatuh Tempo</label>
                                <input type="date" 
                                    id="tanggal_jatuh_tempo" 
                                    wire:model.defer="tanggal_jatuh_tempo" 
                                    class="form-control" />
                                @error('tanggal_jatuh_tempo') 
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Deskripsi --}}
                            <div class="col-lg-12">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <input type="text" 
                                    id="deskripsi" 
                                    wire:model.defer="deskripsi" 
                                    class="form-control" 
                                    placeholder="Jenis laporan khusus SPP..." />
                                @error('deskripsi') 
                                    <div class="text-danger small mt-1">{{ $message }}</div>
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
