<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header border-0 ">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div class="flex-grow-1">
                <h4 class="mb-1">
                    {{ $nama_siswa ?? 'Siswa belum dipilih' }}
                </h4>

                <div class="hstack gap-3 flex-wrap small">
                    <div class="text-muted">
                        Kelas :
                        <span class="text-body fw-semibold">
                            {{ $nama_kelas ?? 'Belum ada' }}
                        </span>
                    </div>

                    <div class="vr"></div>

                    <div class="text-muted">
                        Telepon :
                        <span class="text-body fw-semibold">
                            {{ $telepon ?? 'Tidak tersedia' }}
                        </span>
                    </div>
                </div>

                <p class="text-muted mt-2 mb-0">
                    {{ $alamat ?? 'Tidak ada alamat' }}
                </p>
            </div>

        </div>
    </div>
    <div class="card-body">
        <!-- SALDO CARD -->
        <div class="p-3 rounded-3 text-white bg-warning mb-4">
            <p class="mb-1 small">Saldo EduPay</p>
            <h2 class="fw-bold text-white mb-0">
                Rp{{ number_format($saldoEduPaySiswa, 0, ',', '.') }}
            </h2>
            <div class="mt-3">
                <i class="ri-wallet-3-fill fs-3"></i>
            </div>
        </div>

        <!-- TABS -->
        <ul class="nav nav-tabs nav-tabs-custom nav-success mb-4" role="tablist" wire:ignore>
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#tab-topup">
                    <i class="ri-arrow-up-circle-fill me-1 text-success"></i> Top-Up
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#tab-penarikan">
                    <i class="ri-arrow-down-circle-fill me-1 text-danger"></i> Penarikan
                </a>
            </li>
        </ul>

        <div class="tab-content" wire:ignore>

            <!-- TOPUP -->
            <div class="tab-pane fade show active" id="tab-topup">

                <div class="row g-3">

                    <div class="col-lg-6 mb-3">
                        <label class="form-label">Nominal</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" class="form-control" placeholder="Minimal Rp 1.000"
                                wire:model.defer="nominal_topup"
                                onkeyup="formatTagihan(this)">
                        </div>
                        @error('nominal_topup')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label class="form-label">Metode</label>
                        <select class="form-select" wire:model.defer="jenis_transaksi_topup">
                            <option value="topup tunai">Teller Tunai</option>
                            <option value="topup online">Transfer ke Sekolah</option>
                        </select>
                    </div>
                    <!-- Tombol Simpan -->
                    <div class="hstack gap-2 justify-content-end d-print-none mt-4">
                        <input type="text" id="deskripsi" class="form-control" wire:model.defer="deskripsi_topup"
                            placeholder="Deskripsi transaksi (opsional)">
                        @error('deskripsi_topup') <span class="text-danger">{{ $message }}</span> @enderror
                        
                        <button wire:click="simpanTopUp" class="btn btn-success">
                            <i class="ri-save-line align-bottom me-1"></i> Simpan Top-Up
                        </button>
                    </div>

                </div>

            </div>

            <!-- PENARIKAN -->
            <div class="tab-pane fade" id="tab-penarikan">

                <div class="row g-3">

                    <div class="col-lg-12 mb-3">
                        <label class="form-label">Nominal</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" class="form-control" placeholder="Nominal penarikan"
                                wire:model.defer="nominal_penarikan"
                                onkeyup="formatTagihan(this)">
                        </div>
                        @error('nominal_penarikan')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="hstack gap-2 justify-content-end d-print-none mt-4">
                        <input type="text" id="deskripsi" class="form-control" wire:model.defer="deskripsi_penarikan"
                            placeholder="Deskripsi transaksi (opsional)">
                        @error('deskripsi_penarikan') <span class="text-danger">{{ $message }}</span> @enderror
                        <button wire:click="simpanPenarikan" class="btn btn-danger">
                            <i class="ri-save-line align-bottom me-1"></i> Simpan Penarikan
                        </button>
                    </div>

                </div>

            </div>

        </div>

    </div>
</div>
<script>
    function formatTagihan(el) {
        let angka = el.value.replace(/\D/g, '');
        el.value = new Intl.NumberFormat('id-ID').format(angka);
    }
</script>