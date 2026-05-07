<div wire:ignore.self class="modal fade" id="ModalDetailSiswa" tabindex="-1" aria-labelledby="ModalDetailSiswaLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title">Detail Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
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
                    
                                <!-- QUICK SETTINGS (READONLY MODE) -->
                                <div class="mb-3">
                                    <div class="text-uppercase text-muted small mb-2">
                                        Informasi Cepat
                                    </div>
                    
                                    <div class="mb-3">
                                    
                                        <div class="text-uppercase text-muted small mb-2">
                                            Saldo Siswa
                                        </div>
                                    
                                        <div class="row g-2">
                                    
                                            <!-- TABUNGAN -->
                                            <div class="col-6">
                                                <div class="border rounded p-2 text-center bg-light">
                                                    <div class="small text-muted">Tabungan</div>
                                                    <div class="fw-bold text-success">
                                                        Rp {{ number_format($saldo_tabungan, 0, ',', '.') }}
                                                    </div>
                                                </div>
                                            </div>
                                    
                                            <!-- EDUPAY -->
                                            <div class="col-6">
                                                <div class="border rounded p-2 text-center bg-light">
                                                    <div class="small text-muted">EduPay</div>
                                                    <div class="fw-bold text-primary">
                                                        Rp {{ number_format($saldo_edupay, 0, ',', '.') }}
                                                    </div>
                                                </div>
                                            </div>
                                    
                                        </div>
                                    
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small text-muted">Kelas</label>
                                        <div class="form-control bg-light">
                                            {{ $siswaDetail->ms_kelas->nama_kelas ?? '-' }}
                                        </div>
                                    </div>
                    
                                    <div class="mb-3">
                                        <label class="form-label small text-muted">Telepon</label>
                                        <div class="form-control bg-light">
                                            {{ $siswaDetail->ms_siswa->telepon ?? '-' }}
                                        </div>
                                    </div>
                    
                                    <div class="mb-0">
                                        <label class="form-label small text-muted">EduCard</label>
                                        <div class="form-control bg-light">
                                            {{ $siswaDetail->ms_siswa->ms_educard->kode_kartu ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                    
                                <hr>
                    
                                <!-- SYSTEM INFO (IDENTIK EDIT) -->
                                <div>
                                    <div class="text-uppercase text-muted small mb-2">
                                        Informasi Sistem
                                    </div>
                    
                                    <div class="list-group list-group-flush">
                    
                                        <div class="list-group-item px-0 d-flex justify-content-between">
                                            <div>
                                                <div class="text-muted small">Jenjang</div>
                                                <div class="fw-semibold">
                                                    {{ $siswaDetail->ms_jenjang->nama_jenjang ?? '-' }}
                                                </div>
                                            </div>
                                            <i class="ri-book-2-line text-info fs-5"></i>
                                        </div>
                    
                                        <div class="list-group-item px-0 d-flex justify-content-between">
                                            <div>
                                                <div class="text-muted small">Tahun Ajar</div>
                                                <div class="fw-semibold">
                                                    {{ $siswaDetail->ms_tahun_ajar->nama_tahun_ajar ?? '-' }}
                                                </div>
                                            </div>
                                            <i class="ri-calendar-fill text-warning fs-5"></i>
                                        </div>
                    
                                        <div class="list-group-item px-0 d-flex justify-content-between">
                                            <div>
                                                <div class="text-muted small">Petugas</div>
                                                <div class="fw-semibold">
                                                    {{ $siswaDetail->ms_pengguna->nama ?? '-' }}
                                                </div>
                                            </div>
                                            <i class="ri-user-3-fill text-secondary fs-5"></i>
                                        </div>
                    
                                        <div class="list-group-item px-0 d-flex justify-content-between">
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
                                        <div class="form-control bg-light">
                                            {{ $siswaDetail->ms_siswa->nama_siswa ?? '-' }}
                                        </div>
                                    </div>
                    
                                    <div class="col-lg-6">
                                        <label class="form-label">NISN</label>
                                        <div class="form-control bg-light">
                                            {{ $siswaDetail->ms_siswa->nisn ?? '-' }}
                                        </div>
                                    </div>
                    
                                    <div class="col-lg-6">
                                        <label class="form-label">Tempat Lahir</label>
                                        <div class="form-control bg-light">
                                            {{ $siswaDetail->ms_siswa->tempat_lahir ?? '-' }}
                                        </div>
                                    </div>
                    
                                    <div class="col-lg-6">
                                        <label class="form-label">Tanggal Lahir</label>
                                        <div class="form-control bg-light">
                                            {{ $siswaDetail->ms_siswa->tanggal_lahir ?? '-' }}
                                        </div>
                                    </div>
                    
                                    <div class="col-lg-6">
                                        <label class="form-label">Nama Ayah</label>
                                        <div class="form-control bg-light">
                                            {{ $siswaDetail->ms_siswa->nama_ayah ?? '-' }}
                                        </div>
                                    </div>
                    
                                    <div class="col-lg-6">
                                        <label class="form-label">Nama Ibu</label>
                                        <div class="form-control bg-light">
                                            {{ $siswaDetail->ms_siswa->nama_ibu ?? '-' }}
                                        </div>
                                    </div>
                    
                                    <div class="col-12">
                                        <label class="form-label">Alamat</label>
                                        <div class="form-control bg-light">
                                            {{ $siswaDetail->ms_siswa->alamat ?? '-' }}
                                        </div>
                                    </div>
                    
                                    <div class="col-12">
                                        <label class="form-label">Catatan Siswa</label>
                                        <div class="form-control bg-light">
                                            {{ $siswaDetail->ms_siswa->deskripsi ?? '-' }}
                                        </div>
                                    </div>
                    
                                </div>
                    
                            </div>
                        </div>
                    </div>
                
                </div>
            </div>


            <div class="modal-footer">
                <a href="javascript:void(0);" class="btn btn-link link-success shadow-none fw-medium"
                    data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Tutup</a>
            </div>
        </div>
    </div>
</div>