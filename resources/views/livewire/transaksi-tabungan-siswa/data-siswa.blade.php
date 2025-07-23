{{-- Do your work, then step back. --}}

<div class="card-body p-4">
    <div class="d-flex">
        <div class="flex-grow-1">
            <h4>{{ $nama_siswa ?? 'Siswa belum dipilih' }}</h4>
            <div class="hstack gap-3 flex-wrap">
                <div><a href="#" class="text-primary d-block">{{ $ms_penempatan_siswa_id }}-TemanSekolah</a></div>
                <div class="vr"></div>
                <div class="text-muted">EduCard : <span class="text-warning fw-medium">{{ $educard ?? 'Belum ada' }}</span></div>
                <div class="vr"></div>
                <div class="text-muted">Kelas : <span class="text-body fw-medium">{{ $nama_kelas ?? 'Belum ada' }}</span></div>
                <div class="vr"></div>
                <div class="text-muted">Telepon : <span class="text-body fw-medium">{{ $telepon ?? 'Tidak tersedia' }}</span></div>
                {{-- <div class="vr"></div> --}}
                {{-- <div class="text-muted">Virtual Akun : <span class="text-body fw-medium">9888838383838</span></div> --}}
            </div>
        </div>
    </div>
    {{-- <div class="mt-4 text-muted">
        <p>{{ $alamat ?? 'Tidak ada alamat' }}</p>
    </div> --}}
    <div class="row mt-4">
        <div class="col-lg-4 col-sm-6">
            <div class="p-2 border border-dashed rounded">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm me-2">
                        <div class="avatar-title rounded bg-transparent text-info fs-24">
                            <i class="ri-inbox-archive-fill"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1">Saldo :</p>
                        <h5 class="mb-0">RP{{ number_format($saldo_tabungan_siswa, 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6">
            <div class="p-2 border border-dashed rounded">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm me-2">
                        <div class="avatar-title rounded bg-transparent text-success fs-24">
                            <i class="ri-money-dollar-circle-fill"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1">Total Kredit:</p>
                        <h5 class="mb-0">RP{{ number_format($total_kredit_tabungan, 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <!-- end col -->
        <div class="col-lg-4 col-sm-6">
            <div class="p-2 border border-dashed rounded">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm me-2">
                        <div class="avatar-title rounded bg-transparent text-danger fs-24">
                            <i class="ri-file-copy-2-fill"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1">Total Debit:</p>
                        <h5 class="mb-0">RP{{ number_format($total_debit_tabungan, 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <!-- end col -->
    </div>
    <div class="mt-4">        
        <!-- Base Example -->
        <div class="accordion" id="default-accordion-example">
            <div class="accordion-item shadow">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                        Kredit
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse show" aria-labelledby="headingTwo">
                    <div class="accordion-body">
                        <h5 class="fs-15">Kredit</h5>
                        <p class="text-muted">Kredit adalah Pencatatan <b>penambahan</b> nominal saldo tabungan</p>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text" id="addon-wrapping">RP</span>
                            <input type="number" class="form-control" placeholder="123" wire:model.defer="nominal_kredit" aria-label="123" aria-describedby="addon-wrapping">
                        </div>
                        @error('nominal_kredit') <span class="text-danger">{{ $message }}</span> @enderror

                        <!-- Input deskripsi kredit -->
                        <div class="hstack gap-2 justify-content-end d-print-none mt-4">
                            <input type="text" 
                                class="form-control" 
                                wire:model.defer="deskripsi_kredit" 
                                placeholder="deskripsi transaksi (bila perlu)" 
                                aria-label="deskripsi">
                            @error('deskripsi_kredit') <span class="text-danger">{{ $message }}</span> @enderror

                            <!-- Tombol simpan -->
                            <button wire:click="simpanKredit" class="btn btn-success">
                                <i class="ri-printer-line align-bottom me-1"></i> Kredit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="accordion-item shadow">
                <h2 class="accordion-header" id="headingThree">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Debit
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree">
                    <div class="accordion-body">
                        <h5 class="fs-15">Debit</h5>
                        <p class="text-muted">Debit adalah pencatatan <b>pengurangan</b> nominal saldo tabungan</p>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text" id="addon-wrapping">RP</span>
                            <input type="number" 
                                class="form-control" 
                                placeholder="123" 
                                wire:model.defer="nominal_debit" 
                                aria-label="123" 
                                aria-describedby="addon-wrapping">
                        </div>
                        @error('nominal_debit') <span class="text-danger">{{ $message }}</span> @enderror

                        <!-- Input deskripsi debit -->
                        <div class="hstack gap-2 justify-content-end d-print-none mt-4">
                            <input type="text" 
                                class="form-control" 
                                wire:model.defer="deskripsi_debit" 
                                placeholder="deskripsi transaksi (bila perlu)" 
                                aria-label="deskripsi">
                            @error('deskripsi_debit') <span class="text-danger">{{ $message }}</span> @enderror

                            <!-- Tombol simpan -->
                            <button wire:click="simpanDebit" class="btn btn-danger">
                                <i class="ri-printer-line align-bottom me-1"></i> Debit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

