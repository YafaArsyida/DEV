<div class="card-body">
    {{-- Do your work, then step back. --}}
    <div class="row">
    
        <div class="col-lg-12">
    
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body">
    
                    <!-- Nama -->
                    <h4 class="fw-bold mb-1">
                        {{ $nama_siswa ?? 'Siswa belum dipilih' }}
                    </h4>
    
                    <!-- Meta -->
                    <div class="d-flex flex-wrap gap-2 text-muted small mb-3">
                        <div><a href="#" class="text-primary d-block">{{ $ms_penempatan_siswa_id }}-TemanSekolah</a></div>
                        <div class="vr"></div>
                        <div class="text-muted">Kelas : <span class="text-body fw-medium">{{ $nama_kelas ?? 'Belum ada' }}</span></div>
                        <div class="vr"></div>
                        <div class="text-muted">Telepon : <span class="text-body fw-medium">{{ $telepon ?? 'Tidak tersedia' }}</span></div>
                    </div>
    
                    <!-- Alamat -->
                    <p class="text-muted small mb-4">
                        {{ $alamat ?? 'Tidak ada alamat' }}
                    </p>
    
                    <!-- SALDO CARD -->
                    <div class="p-3 rounded-3 text-white bg-primary">
    
                        <p class="mb-1 small">Saldo Tabungan</p>
    
                        <h2 class="fw-bold text-white mb-0">
                            Rp{{ number_format($saldoTabunganSiswa, 0, ',', '.') }}
                        </h2>
    
                        <div class="mt-3">
                            <i class="ri-wallet-3-fill fs-3"></i>
                        </div>
                    </div>
    
                </div>
            </div>
    
        </div>
    
        <div class="col-lg-12">
    
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body">
    
                    <!-- Tabs -->
                    <ul class="nav nav-tabs nav-tabs-custom nav-success mb-4" role="tablist" wire:ignore>
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#tab-kredit">
                                <i class="ri-arrow-up-circle-fill me-1 text-success"></i> Kredit
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#tab-debit">
                                <i class="ri-arrow-down-circle-fill me-1 text-danger"></i> Debit
                            </a>
                        </li>
                    </ul>
    
                    <div class="tab-content" wire:ignore>
    
                        <!-- KREDIT -->
                        <div class="tab-pane fade show active" id="tab-kredit">
    
                            <p class="text-muted mb-3">
                                Tambahkan saldo tabungan siswa
                            </p>
    
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-success-subtle text-success">Rp</span>
                                <input type="number" class="form-control form-control-lg" placeholder="Nominal kredit"
                                    wire:model.defer ="nominal_kredit">
                            </div>
    
                            @error('nominal_kredit')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
    
                            <div class="mb-3">
                                <input type="text" class="form-control" placeholder="Deskripsi (opsional)"
                                    wire:model.defer="deskripsi_kredit">
                            </div>
    
                            <div class="d-flex justify-content-end">
                                <button wire:click="simpanKredit" class="btn btn-success px-4">
                                    <i class="ri-check-line me-1"></i> Simpan
                                </button>
                            </div>
    
                        </div>
    
                        <!-- DEBIT -->
                        <div class="tab-pane fade" id="tab-debit">
    
                            <p class="text-muted mb-3">
                                Kurangi saldo tabungan siswa
                            </p>
    
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-danger-subtle text-danger">Rp</span>
                                <input type="number" class="form-control form-control-lg" placeholder="Nominal debit"
                                    wire:model.defer="nominal_debit">
                            </div>
    
                            @error('nominal_debit')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
    
                            <div class="mb-3">
                                <input type="text" class="form-control" placeholder="Deskripsi (opsional)"
                                    wire:model.defer="deskripsi_debit">
                            </div>
    
                            <div class="d-flex justify-content-end">
                                <button wire:click="simpanDebit" class="btn btn-danger px-4">
                                    <i class="ri-check-line me-1"></i> Simpan
                                </button>
                            </div>
    
                        </div>
    
                    </div>
    
                </div>
            </div>
    
        </div>
    </div>
</div>