{{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
<div wire:ignore.self class="modal fade" id="editSuratTagihan" tabindex="-1" aria-labelledby="editSuratTagihanLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSuratTagihanLabel">Edit Surat Tagihan Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form wire:submit.prevent="updateSurat">
                <div class="modal-body">
                    @if ($foto_kop_baru && is_object($foto_kop_baru))
                        <div class="mb-3">
                            <p>Preview Foto Baru:</p>
                            <img src="{{ $foto_kop_baru->temporaryUrl() }}" alt="Preview Foto Kop Baru" class="img-fluid" height="100px">
                        </div>
                    @elseif ($foto_kop)
                        <div class="mb-3">
                            <p>Foto Kop Lama:</p>
                            <img src="{{ Storage::url($foto_kop) }}" alt="Foto Kop Lama" class="img-fluid" height="100px">
                        </div>
                    @else
                        <h3 class="text-muted">Belum ada foto kop yang diunggah.</h3>
                    @endif
                    
                    <div class="mb-3">
                        <label for="foto_kop_baru" class="form-label">Unggah Foto Kop Baru</label>
                        <input type="file" class="form-control" id="foto_kop_baru" wire:model="foto_kop_baru" accept="image/*">
                        @error('foto_kop_baru') 
                            <footer class="text-danger mt-0">{{ $message }}</footer> 
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
                                @if ($tanda_tangan_baru && is_object($tanda_tangan_baru))
                                    <div class="mb-3">
                                        <p>Preview Tanda Tangan Baru:</p>
                                        <img src="{{ $tanda_tangan_baru->temporaryUrl() }}" alt="Preview Tanda Tangan Baru" class="img-fluid" style="max-height: 100px;">
                                    </div>
                                @elseif ($tanda_tangan)
                                    <!-- Kondisi Foto Tanda Tangan Lama -->
                                    <div class="mb-3">
                                        <p>Foto Tanda Tangan Lama:</p>
                                        <img src="{{ Storage::url($tanda_tangan) }}" alt="Tanda Tangan Lama" class="img-fluid" style="max-height: 100px;">
                                    </div>
                                @else
                                    <!-- Pesan Jika Belum Ada Tanda Tangan -->
                                    <p class="text-muted">Belum ada tanda tangan yang diunggah.</p>
                                @endif
                                
                                <!-- Input untuk Mengunggah Tanda Tangan -->
                                <label for="tanda_tangan_baru" class="form-label">Unggah Tanda Tangan Baru</label>
                                <input type="file" wire:model="tanda_tangan_baru" id="tanda_tangan_baru" class="form-control" accept="image/*">
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
                <div class="modal-footer">
                    <a href="javascript:void(0);" class="btn btn-link link-success shadow-none fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Tutup</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

