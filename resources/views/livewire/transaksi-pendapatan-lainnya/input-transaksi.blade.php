<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header border-0 ">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div class="flex-grow-1">
                <h4 class="mb-1">
                    Transaksi Pendapatan Lainnya
                </h4>
                <p class="text-muted mt-2 mb-0">
                    Silakan mencatat transaksi pendapatan berdasarkan akun yang tersedia.
                </p>
            </div>

        </div>
    </div>
    <div class="card-body">
        <!-- SUMMARY CARD -->
        <div class="p-3 rounded-3 text-white bg-primary mb-4">
            <p class="mb-1 small">Pendapatan Lainnya</p>
            <h2 class="fw-bold text-white mb-0">
                Rp{{ number_format($totalPendapatanLainnya, 0, ',', '.') }}
            </h2>
            <div class="mt-3">
                <i class="ri-inbox-archive-fill fs-3"></i>
            </div>
        </div>

        <!-- FORM -->
        <div class="row g-3">

            <!-- Jenis Transaksi -->
            <div class="col-lg-6">
                <label class="form-label">Jenis Transaksi</label>
                <select wire:model="kode_rekening" class="form-select" style="cursor:pointer">
                    <option value="">Pilih Transaksi</option>
                    @foreach ($select_transaksi as $item)
                    <option value="{{ $item->kode_rekening }}">
                        {{ $item->nama_rekening }}
                    </option>
                    @endforeach
                </select>
                @error('kode_rekening')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Metode -->
            <div class="col-lg-6">
                <label class="form-label">Metode Penerimaan</label>
                <select wire:model.defer="metode_pembayaran" class="form-select">
                    <option value="tunai">Kas Tunai</option>
                    <option value="bank">Transfer ke Rekening Sekolah</option>
                </select>
                @error('metode_pembayaran')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Nominal -->
            <div class="col-lg-12 mb-3">
                <label class="form-label">Nominal</label>
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

            <!-- ACTION -->
            <div class="hstack gap-2 justify-content-end d-print-none mt-4">
                <input type="text" class="form-control" wire:model.defer="deskripsi"
                    placeholder="Deskripsi transaksi (opsional)">
                @error('deskripsi')
                <span class="text-danger">{{ $message }}</span>
                @enderror

                <button wire:click="simpanTransaksi" class="btn btn-success">
                    <i class="ri-save-line align-bottom me-1"></i> Simpan
                </button>
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