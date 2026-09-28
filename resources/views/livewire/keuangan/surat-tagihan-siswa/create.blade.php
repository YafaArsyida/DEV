{{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
<div wire:ignore.self class="modal fade" id="createSuratTagihan" tabindex="-1" aria-labelledby="createSuratTagihanLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-mail-send-line"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">
                            Buat Template Surat Tagihan Siswa
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
            <form wire:submit.prevent="createSurat">
                <div class="modal-body">
                    @if ($foto_kop && is_object($foto_kop))
                        <div class="text-center mb-4">
                            <p class="text-muted mb-2">Preview Foto Kop Baru</p>

                            <img src="{{ $foto_kop->temporaryUrl() }}"
                                alt="Preview Foto Kop"
                                class="img-fluid rounded border shadow-sm"
                                style="max-height:180px;">
                        </div>
                    @else
                        <div class="border border-2 border-dashed rounded-3 p-5 text-center bg-light-subtle mb-4">

                            <div class="avatar-lg mx-auto mb-3">
                                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-1">
                                    <i class="ri-image-add-line"></i>
                                </div>
                            </div>

                            <h5 class="fw-semibold mb-2">
                                Belum Ada Foto Kop
                            </h5>

                            <p class="text-muted mb-0">
                                Unggah gambar kop surat untuk digunakan pada
                                surat tagihan yang dicetak maupun dikirim.
                            </p>

                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="foto_kop" class="form-label fw-medium">
                            Upload Foto Kop
                        </label>

                        <input type="file"
                            class="form-control"
                            id="foto_kop"
                            wire:model="foto_kop"
                            accept="image/*">

                        <small class="text-muted">
                            Format yang didukung: JPG, JPEG, PNG. Disarankan rasio landscape dengan resolusi tinggi.
                        </small>

                        @error('foto_kop')
                            <div class="text-danger mt-1 small">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-xxl-4"></div>
                        <div class="col-xxl-4"></div>
                        <div class="col-xxl-4">
                            <div>
                                <label for="tempat_tanggal" class="form-label">Tempat, Tanggal</label>
                                <input type="text" class="form-control" id="tempat_tanggal" wire:model.defer="tempat_tanggal">
                                @error('tempat_tanggal') 
                                    <footer class="text-danger mt-0">{{ $message }}</footer> 
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-xxl-4">
                            <div class="">
                                <label for="nomor_surat" class="form-label">Nomor Surat</label>
                                <input type="text" class="form-control" id="nomor_surat" wire:model.defer="nomor_surat">
                                @error('nomor_surat') 
                                    <footer class="text-danger mt-0">{{ $message }}</footer> 
                                @enderror
                            </div>
                        </div>
                        <div class="col-xxl-4">
                            <div class="">
                                <label for="lampiran" class="form-label">Lampiran</label>
                                <input type="text" class="form-control" id="lampiran" wire:model.defer="lampiran">
                                @error('lampiran') 
                                    <footer class="text-danger mt-0">{{ $message }}</footer> 
                                @enderror
                            </div>
                        </div>
                        <div class="col-xxl-4">
                            <div class="">
                                <label for="hal" class="form-label">Hal</label>
                                <input type="text" class="form-control" id="hal" wire:model.defer="hal">
                                @error('hal') 
                                    <footer class="text-danger mt-0">{{ $message }}</footer> 
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="salam_pembuka" class="form-label">Salam Pembuka</label>
                        <input type="text" class="form-control" id="salam_pembuka" wire:model.defer="salam_pembuka">
                        @error('salam_pembuka') 
                            <footer class="text-danger mt-0">{{ $message }}</footer> 
                        @enderror
                    </div>
                
                    <div class="mb-3">
                        <label for="pembuka" class="form-label">Paragraf Pembuka</label>
                        <textarea class="form-control" id="pembuka" wire:model.defer="pembuka" rows="3"></textarea>
                        @error('pembuka') 
                            <footer class="text-danger mt-0">{{ $message }}</footer> 
                        @enderror
                    </div>
                
                    <div class="mb-3">
                        <label for="isi" class="form-label">Isi Surat</label>
                        <textarea class="form-control" id="isi" wire:model.defer="isi" rows="4"></textarea>
                        @error('isi') 
                            <footer class="text-danger mt-0">{{ $message }}</footer> 
                        @enderror
                    </div>
                
                    <div class="mb-3">
                        <label for="rincian" class="form-label">Rincian Tagihan</label>
                        <div class="row">
                            <div class="col-xxl-8">
                                <textarea class="form-control" id="rincian" wire:model.defer="rincian" rows="1"></textarea>
                                @error('rincian') 
                                    <footer class="text-danger mt-0">{{ $message }}</footer> 
                                @enderror
                            </div>
                            <div class="col-xxl-4 d-flex align-items-center">
                                <span><b>Rp9XX.XXX</b> dengan rincian terlampir.</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="panduan" class="form-label">Panduan Pembayaran</label>
                        <textarea class="form-control" id="panduan" wire:model.defer="panduan" rows="1"></textarea>
                        @error('panduan') 
                            <footer class="text-danger mt-0">{{ $message }}</footer> 
                        @enderror
                    </div>
                
                    @for ($i = 1; $i <= 5; $i++)
                        <div class="mb-3">
                            <label for="instruksi_{{ $i }}" class="form-label">Instruksi {{ $i }}</label>
                            <textarea class="form-control" id="instruksi_{{ $i }}" wire:model.defer="instruksi_{{ $i }}" rows="1"></textarea>
                            @error('instruksi_{{ $i }}') 
                                <footer class="text-danger mt-0">{{ $message }}</footer> 
                            @enderror
                        </div>
                    @endfor
                
                    <div class="mb-3">
                        <label for="penutup" class="form-label">Penutup</label>
                        <textarea class="form-control" id="penutup" wire:model.defer="penutup" rows="2"></textarea>
                        @error('penutup') 
                            <footer class="text-danger mt-0">{{ $message }}</footer> 
                        @enderror
                    </div>
                
                    <div class="mb-3">
                        <label for="salam_penutup" class="form-label">Salam Penutup</label>
                        <input type="text" class="form-control" id="salam_penutup" wire:model.defer="salam_penutup">
                        @error('salam_penutup') 
                            <footer class="text-danger mt-0">{{ $message }}</footer> 
                        @enderror
                    </div>
                    <div class="row mb-3">
                        <div class="col-xxl-6">
                            <div class="mb-3">
                                <label for="jabatan" class="form-label">Jabatan Penandatangan</label>
                                <input type="text" class="form-control" id="jabatan" wire:model.defer="jabatan">
                                @error('jabatan') 
                                    <footer class="text-danger mt-0">{{ $message }}</footer> 
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="nama_petugas" class="form-label">Nama Petugas</label>
                                <input type="text" class="form-control" id="nama_petugas" wire:model.defer="nama_petugas">
                                @error('nama_petugas') 
                                    <footer class="text-danger mt-0">{{ $message }}</footer> 
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="nomor_petugas" class="form-label">Nomor Petugas</label>
                                <input type="text" class="form-control" id="nomor_petugas" wire:model.defer="nomor_petugas">
                                @error('nomor_petugas') 
                                    <footer class="text-danger mt-0">{{ $message }}</footer> 
                                @enderror
                            </div>
                        </div>
                        <div class="col-xxl-6">
                            <div class="mb-3">
                                <!-- Kondisi Preview Foto Baru -->
                                @if ($tanda_tangan && is_object($tanda_tangan))
                                    <div class="mb-3">
                                        <p>Preview Tanda Tangan Baru:</p>
                                        <img src="{{ $tanda_tangan->temporaryUrl() }}" alt="Preview Tanda Tangan Baru" class="img-fluid" style="max-height: 100px;">
                                    </div>
                                @else
                                    <!-- Pesan Jika Belum Ada Tanda Tangan -->
                                    <p class="text-muted">Belum ada tanda tangan yang diunggah.</p>
                                @endif
                                
                                <!-- Input untuk Mengunggah Tanda Tangan -->
                                <label for="tanda_tangan" class="form-label">Unggah Tanda Tangan Baru</label>
                                <input type="file" wire:model="tanda_tangan" id="tanda_tangan" class="form-control" accept="image/*">
                                <!-- Menampilkan Error jika Ada Masalah pada File -->
                                @error('tanda_tangan')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>                                                      
                        </div>
                    </div>
                
                    @for ($i = 1; $i <= 3; $i++)
                        <div class="mb-3">
                            <label for="catatan_{{ $i }}" class="form-label">Catatan Tambahan {{ $i }}</label>
                            <textarea class="form-control" id="catatan_{{ $i }}" wire:model.defer="catatan_{{ $i }}" rows="2"></textarea>
                            @error('catatan_{{ $i }}') 
                                <footer class="text-danger mt-0">{{ $message }}</footer> 
                            @enderror
                        </div>
                    @endfor
                
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

