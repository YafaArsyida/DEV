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