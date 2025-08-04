 <div class="col-xl-4">
    <div class="card card-height-100">
        <div class="card-header align-items-center border-0 d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Transaksi Jurnal</h4>
            <div class="flex-shrink-0">
                <ul class="nav justify-content-end nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#tab-transaksi-pendapatan" role="tab" aria-selected="false">Pendapatan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab-transaksi-pengeluaran" role="tab" aria-selected="true">Pengeluaran</a>
                    </li>
                </ul><!-- end ul -->
            </div>
        </div><!-- end cardheader -->
        <div class="card-body p-0">
            <div class="tab-content p-0">
                <div class="tab-pane active" id="tab-transaksi-pendapatan" role="tabpanel">
                    <div class="p-3">
                        <div class="row">
                            <div class="col-lg-12 col-sm-12">
                                <div class="p-2 border border-dashed rounded">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2">
                                            <div class="avatar-title rounded bg-transparent text-success fs-24">
                                                <i class="ri-inbox-archive-fill"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="text-muted mb-1">Total Pendapatan :</p>
                                            <h5 class="mb-0">RP{{ number_format($totalPendapatan, 0, ',', '.') }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end col -->
                        </div>
                        <div class="row mt-4">        
                            <!-- Pilihan Akun -->
                            <div class="col-lg-12 mb-3">
                                <label for="kode_rekening" class="form-label">Jenis Transaksi Pendapatan</label>
                                <select wire:model="kode_rekening" style="cursor: pointer" class="form-select" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Transaksi">
                                    <option value="">Pilih Transaksi</option>
                                    @foreach ($select_pendapatan as $item)    
                                    <option value="{{ $item->kode_rekening }}">{{ $item->nama_rekening }}</option>
                                    @endforeach
                                </select>
                                @error('kode_rekening') 
                                    <footer class="text-danger mt-0">{{ $message }}</footer>
                                @enderror
                            </div>
                    
                            <!-- Input Nominal -->
                            <div class="col-lg-6 mb-3">
                                <label for="nominal" class="form-label">Nominal</label>
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text" id="addon-wrapping">RP</span>
                                    <input type="number" id="nominal" class="form-control" placeholder="Masukkan nominal, minimal RP 1.000" wire:model.defer="nominal" aria-describedby="addon-wrapping">
                                    @error('nominal') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                    
                            <!-- Pilihan Akun Tujuan -->
                            <div class="col-lg-6 mb-3">
                                <label for="metode_pembayaran" class="form-label">Metode Penerimaan</label>
                                <select id="metode_pembayaran" wire:model.defer="metode_pembayaran" class="form-select">
                                    <option value="tunai">Kas Tunai</option>
                                    <option value="bank">Transfer ke Rekening Sekolah</option>
                                </select>
                                @error('metode_pembayaran') 
                                    <footer class="text-danger mt-0">{{ $message }}</footer>
                                @enderror
                            </div>
                    
                            <!-- Tombol Simpan -->
                            <div class="hstack gap-2 justify-content-end d-print-none mt-4">
                                <input type="text" id="deskripsi" class="form-control" wire:model.defer="deskripsi" placeholder="Deskripsi transaksi (opsional)">
                                @error('deskripsi') <span class="text-danger">{{ $message }}</span> @enderror
                                <button wire:click="simpanTransaksi" class="btn btn-success">
                                    <i class="ri-save-line align-bottom me-1"></i> Simpan
                                </button>
                            </div>
                        </div>  
                    </div>
                </div><!-- end tabpane -->

                <div class="tab-pane" id="tab-transaksi-pengeluaran" role="tabpanel">
                    <div class="p-3">
                        <div class="row">
                            <div class="col-lg-12 col-sm-12">
                                <div class="p-2 border border-dashed rounded">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2">
                                            <div class="avatar-title rounded bg-transparent text-danger fs-24">
                                                <i class="ri-inbox-archive-fill"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="text-muted mb-1">Total Pengeluaran :</p>
                                            <h5 class="mb-0">RP{{ number_format($totalPengeluaran, 0, ',', '.') }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end col -->
                        </div>
                        <div class="row mt-4">        
                            <!-- Pilihan Akun -->
                            <div class="col-lg-12 mb-3">
                                <label for="kode_rekening" class="form-label">Jenis Pengeluaran Operasional</label>
                                <select wire:model="kode_rekening" style="cursor: pointer" class="form-select" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Transaksi">
                                    <option value="">Pilih Transaksi</option>
                                    @foreach ($select_pengeluaran as $item)    
                                    <option value="{{ $item->kode_rekening }}">{{ $item->nama_rekening }}</option>
                                    @endforeach
                                </select>
                                @error('kode_rekening') 
                                    <footer class="text-danger mt-0">{{ $message }}</footer>
                                @enderror
                            </div>
                    
                            <!-- Input Nominal -->
                            <div class="col-lg-6 mb-3">
                                <label for="nominal" class="form-label">Nominal</label>
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text" id="addon-wrapping">RP</span>
                                    <input type="number" id="nominal" class="form-control" placeholder="Masukkan nominal, minimal RP 1.000" wire:model.defer="nominal" aria-describedby="addon-wrapping">
                                    @error('nominal') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                    
                            <!-- Pilihan Akun Tujuan -->
                            <div class="col-lg-6 mb-3">
                                <label for="metode_pembayaran" class="form-label">Sumber Dana</label>
                                <select id="metode_pembayaran" wire:model.defer="metode_pembayaran" class="form-select">
                                    <option value="tunai">Kas Tunai</option>
                                    <option value="bank">Saldo Bank Sekolah</option>
                                </select>
                                @error('metode_pembayaran') 
                                    <footer class="text-danger mt-0">{{ $message }}</footer>
                                @enderror
                            </div>
                    
                            <!-- Tombol Simpan -->
                            <div class="hstack gap-2 justify-content-end d-print-none mt-4">
                                <input type="text" id="deskripsi" class="form-control" wire:model.defer="deskripsi" placeholder="Deskripsi transaksi (opsional)">
                                @error('deskripsi') <span class="text-danger">{{ $message }}</span> @enderror
                                <button wire:click="simpanPengeluaran" class="btn btn-danger">
                                    <i class="ri-save-line align-bottom me-1"></i> Simpan
                                </button>
                            </div>
                        </div>     
                    </div>
                </div><!-- end tab pane -->
            </div><!-- end tab pane -->
        </div><!-- end card body -->
    </div><!-- end card -->
</div><!-- end col -->