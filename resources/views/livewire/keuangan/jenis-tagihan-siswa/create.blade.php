{{-- Success is as dangerous as failure. --}}
<div wire:ignore.self class="modal fade" id="ModalAddJenisTagihan" tabindex="-1" aria-labelledby="ModalAddJenisTagihan" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-file-list-3-line"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">
                            Jenis Tagihan Baru
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
                        <div class="col-lg-12">
                            <label for="nama_jenis_tagihan_siswa" class="form-label">Nama Jenis Tagihan</label>
                            <input type="text" 
                                    wire:model.defer="nama_jenis_tagihan_siswa" 
                                    id="nama_jenis_tagihan_siswa"  
                                    class="form-control @error('nama_jenis_tagihan_siswa') is-invalid @enderror" 
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
                                    class="form-select @error('ms_kategori_tagihan_siswa_id') is-invalid @enderror">
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
                                class="form-control @error('tanggal_jatuh_tempo') is-invalid @enderror" />
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