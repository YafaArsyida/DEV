{{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
<div wire:ignore.self class="modal fade" id="ModalEditSiswa" tabindex="-1" aria-labelledby="ModalAddSiswa"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title">Edit Data Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    id="close-modal"></button>
            </div>
            <form wire:submit.prevent="updateSiswa">
                <div class="modal-body">
            
                    <div class="row g-3">
            
                        <!-- LEFT: SYSTEM INFO -->
                        <div class="col-xl-4 col-md-4">
            
                            <div class="card border-0 shadow-sm h-100">
            
                                <div class="card-header bg-primary border-0">
                                    <h6 class="mb-0 text-white">Personalisasi Aplikasi</h6>
                                    <small class="text-white">Informasi sistem siswa</small>
                                </div>
            
                                <div class="card-body">
            
                                    <!-- SYSTEM INFO -->
                                    <div class="mb-3">
                                        <div class="text-uppercase text-muted small mb-2">
                                            Pengaturan Cepat
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label small text-muted">Kelas</label>
                                            <select wire:model.defer="form.ms_kelas_id" class="form-select">
                                                <option value="">Pilih Kelas</option>
                                        
                                                @foreach ($selectKelas as $item)
                                                <option value="{{ $item->ms_kelas_id }}">
                                                    {{ $item->nama_kelas }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('form.ms_kelas_id')
                                            <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label small text-muted">Telepon</label>
                                            <input type="text" wire:model.defer="form.telepon" class="form-control" placeholder="08xxxxxxxxxx">
                                            @error('form.telepon')
                                            <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-0">
                                            <label class="form-label small text-muted">EduCard</label>
                                            <input type="text" wire:model.defer="form.educard" class="form-control" placeholder="ID kartu">
                                            @error('form.educard')
                                            <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
            
                                    <hr>
            
                                    <!-- EDITABLE QUICK SETTINGS -->
                                    <div>
                                        <div class="text-uppercase text-muted small mb-2">
                                            Informasi Sistem
                                        </div>
                                        
                                        <div class="list-group list-group-flush">
                                        
                                            <div class="list-group-item px-0 d-flex justify-content-between align-items-start">
                                                <div>
                                                    <div class="text-muted small">Jenjang</div>
                                                    <div class="fw-semibold">
                                                        {{ $siswa->ms_jenjang->nama_jenjang ?? '-' }}
                                                    </div>
                                                </div>
                                                <i class="ri-book-2-line text-info fs-5"></i>
                                            </div>
                                        
                                            <div class="list-group-item px-0 d-flex justify-content-between align-items-start">
                                                <div>
                                                    <div class="text-muted small">Tahun Ajar</div>
                                                    <div class="fw-semibold">
                                                        {{ $siswa->ms_tahun_ajar->nama_tahun_ajar ?? '-' }}
                                                    </div>
                                                </div>
                                                <i class="ri-calendar-fill text-warning fs-5"></i>
                                            </div>
                                        
                                            <div class="list-group-item px-0 d-flex justify-content-between align-items-start">
                                                <div>
                                                    <div class="text-muted small">Petugas</div>
                                                    <div class="fw-semibold">
                                                        {{ $siswa->ms_pengguna?->nama ?? '-' }}
                                                    </div>
                                                </div>
                                                <i class="ri-user-3-fill text-secondary fs-5"></i>
                                            </div>
                                        
                                            <div class="list-group-item px-0 d-flex justify-content-between align-items-start">
                                                <div>
                                                    <div class="text-muted small">Tanggal Daftar</div>
                                                    <div class="fw-semibold">
                                                        {{ $created_at_formatted ?? '-' }}
                                                    </div>
                                                </div>
                                                <i class="ri-time-fill text-success fs-5"></i>
                                            </div>
                                        
                                        </div>
                                    </div>
            
                                </div>
                            </div>
                        </div>
            
                        <!-- RIGHT: MAIN FORM -->
                        <div class="col-xl-8 col-md-8">
            
                            <div class="card shadow-sm border-0">
            
                                <div class="card-header bg-white border-bottom">
                                    <h6 class="mb-0">Informasi Siswa</h6>
                                    <small class="text-muted">Data pribadi & akademik</small>
                                </div>
            
                                <div class="card-body">
            
                                    <div class="row g-3">
            
                                        <div class="col-lg-6">
                                            <label class="form-label">Nama Siswa</label>
                                            <input type="text" wire:model.defer="form.nama_siswa" class="form-control">
                                            @error('form.nama_siswa')
                                            <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
            
                                        <div class="col-lg-6">
                                            <label class="form-label">NISN</label>
                                            <input type="text" wire:model.defer="form.nisn" class="form-control">
                                        </div>
            
                                        <div class="col-lg-6">
                                            <label class="form-label">Tempat Lahir</label>
                                            <input type="text" wire:model.defer="form.tempat_lahir" class="form-control">
                                        </div>
            
                                        <div class="col-lg-6">
                                            <label class="form-label">Tanggal Lahir</label>
                                            <input type="date" wire:model.defer="form.tanggal_lahir" class="form-control">
                                            @error('form.tanggal_lahir')
                                            <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
            
                                        <div class="col-lg-6">
                                            <label class="form-label">Nama Ayah</label>
                                            <input type="text" wire:model.defer="form.nama_ayah" class="form-control">
                                        </div>
            
                                        <div class="col-lg-6">
                                            <label class="form-label">Nama Ibu</label>
                                            <input type="text" wire:model.defer="form.nama_ibu" class="form-control">
                                        </div>
            
                                        <div class="col-12">
                                            <label class="form-label">Alamat</label>
                                            <textarea wire:model.defer="form.alamat" class="form-control" rows="2"></textarea>
                                        </div>
            
                                        <div class="col-12">
                                            <label class="form-label">Catatan Siswa</label>
                                            <textarea wire:model.defer="form.deskripsi" class="form-control" rows="3"></textarea>
                                        </div>
            
                                    </div>
            
                                </div>
                            </div>
                        </div>
            
                    </div>
            
                </div>
            
                <!-- FOOTER -->
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>