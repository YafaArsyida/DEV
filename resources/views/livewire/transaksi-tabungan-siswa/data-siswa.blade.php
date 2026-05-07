<div class="card">
    <div class="card-body">

        <!-- INFO SISWA -->
        <div class="mb-3">
            <h4 class="fw-bold mb-1">
                {{ $nama_siswa ?? 'Siswa belum dipilih' }}
            </h4>

            <div class="d-flex flex-wrap gap-2 text-muted small">
                <div>Kelas: <span class="text-body fw-medium">{{ $nama_kelas ?? '-' }}</span></div>
                <div class="vr"></div>
                <div>Telepon: <span class="text-body fw-medium">{{ $telepon ?? '-' }}</span></div>
            </div>

            <p class="text-muted small">
                {{ $alamat ?? 'Tidak ada alamat' }}
            </p>
        </div>

        <!-- SALDO CARD -->
        <div class="p-3 rounded-3 text-white bg-primary mb-4">
            <p class="mb-1 small">Saldo Tabungan</p>
            <h2 class="fw-bold text-white mb-0">
                Rp{{ number_format($saldoTabunganSiswa, 0, ',', '.') }}
            </h2>
            <div class="mt-3">
                <i class="ri-wallet-3-fill fs-3"></i>
            </div>
        </div>

        <!-- TABS -->
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

                <div class="row g-3">

                    <div class="col-lg-12 mb-3">
                        <label class="form-label">Nominal</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" placeholder="Minimal Rp 1.000"
                                wire:model.defer="nominal_kredit">
                        </div>
                        @error('nominal_kredit')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="hstack gap-2 justify-content-end d-print-none mt-4">
                        <input type="text" class="form-control" wire:model.defer="deskripsi_kredit"
                            placeholder="Deskripsi transaksi (opsional)">
                        @error('deskripsi_kredit')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror

                        <button wire:click="simpanKredit" class="btn btn-success">
                            <i class="ri-save-line align-bottom me-1"></i> Simpan Kredit
                        </button>
                    </div>

                </div>

            </div>

            <!-- DEBIT -->
            <div class="tab-pane fade" id="tab-debit">

                <div class="row g-3">

                    <div class="col-lg-12 mb-3">
                        <label class="form-label">Nominal</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" placeholder="Nominal debit"
                                wire:model.defer="nominal_debit">
                        </div>
                        @error('nominal_debit')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="hstack gap-2 justify-content-end d-print-none mt-4">
                        <input type="text" class="form-control" wire:model.defer="deskripsi_debit"
                            placeholder="Deskripsi transaksi (opsional)">
                        @error('deskripsi_debit')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror

                        <button wire:click="simpanDebit" class="btn btn-danger">
                            <i class="ri-save-line align-bottom me-1"></i> Simpan Debit
                        </button>
                    </div>

                </div>

            </div>

        </div>

    </div>
</div>