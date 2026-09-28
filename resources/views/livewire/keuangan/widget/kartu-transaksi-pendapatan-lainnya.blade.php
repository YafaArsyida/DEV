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
                        <p class="text-muted mb-1">Total Pendapatan Lainnya :</p>
                        <h5 class="mb-0">Rp{{ number_format($totalPendapatanLainnya, 0, ',', '.') }}</h5>
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
                @foreach ($select_transaksi as $item)    
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
            <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="text" class="form-control" placeholder="Minimal Rp 1.000" 
                    wire:model.defer="nominal"
                    onkeyup="formatTagihan(this)">
            </div>
            @error('nominal')
            <small class="text-danger">{{ $message }}</small>
            @enderror
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
<script>
    function formatTagihan(el) {
        let angka = el.value.replace(/\D/g, '');
        el.value = new Intl.NumberFormat('id-ID').format(angka);
    }
</script>