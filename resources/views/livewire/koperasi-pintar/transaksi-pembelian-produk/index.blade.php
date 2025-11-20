 <div class="" id="produkList">
    <div class="card-body pb-2">
        <div class="row align-items-end gy-3">

            <!-- Kiri: Judul & Deskripsi -->
            <div class="col-md-5">
                <h4 class="fw-bold text-dark mb-1">Transaksi Pembelian Produk</h4>
                <small class="text-muted">Pengelolaan stok masuk dari supplier</small>
            </div>

            <!-- Tengah: Tanggal Transaksi -->
            <div class="col-md-3">
                <label class="fw-semibold mb-1">Tanggal Transaksi</label>
                <input type="date"
                    class="form-control shadow-sm"
                    wire:model="tanggal_pembelian"
                    style="cursor: pointer">
            </div>

            <!-- Kanan: Select Supplier + Tombol Tambah Supplier -->
            <div class="col-md-4">
                <label class="fw-semibold mb-1">Supplier</label>
                <div class="d-flex gap-2">

                    <!-- Dropdown Supplier -->
                    <select wire:model="ms_supplier_koperasi_id"
                            class="form-select shadow-sm"
                            style="cursor: pointer"
                            data-bs-toggle="tooltip"
                            data-bs-trigger="hover"
                            data-bs-placement="top"
                            title="Pilih Supplier">
                        <option value="">-- Pilih Supplier --</option>

                        @foreach ($this->select_supplier as $sup)
                            <option value="{{ $sup->ms_supplier_koperasi_id }}">
                                {{ $sup->nama_supplier_koperasi }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Tombol Tambah Supplier -->
                    <button type="button"
                        class="btn btn-success shadow-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#ModalTambahSupplierKoperasi"
                        wire:click="$emit('showCreateSupplierKoperasi')">
                        <i class="ri-add-line"></i>
                    </button>

                </div>
            </div>

        </div>


    </div>

    <div class="card-body">
        <div class="row g-4">
            {{-- barcode --}}
            <div class="col-md-7">

                <!-- Input Barcode -->
                <label class="fw-semibold">Scan / Input Barcode Produk</label>

                <div class="input-group input-group-lg shadow-sm">
                    <span class="input-group-text bg-primary text-white border-primary">
                        <i class="ri-barcode-line fs-4"></i>
                    </span>

                    <input type="text"
                        wire:model.lazy="barcodeInput"
                        wire:keydown.enter="tambahDariBarcode"
                        class="form-control border-primary"
                        placeholder="Arahkan scanner ke barcode atau ketik kode produk..."
                        autofocus>
                </div>

                <small class="text-muted d-block mt-1">
                    Gunakan scanner barcode atau tekan <strong>Enter</strong> untuk menambahkan ke keranjang.
                </small>
            </div>  
            {{-- Supplier --}}
            <div class="col-md-5">
                <label class="fw-semibold">Supplier</label>
                @if ($this->supplierNama)
                    {{-- Jika supplier sudah dipilih --}}
                    <div class="alert alert-info d-flex align-items-center gap-2 mb-0">
                        <i class="ri-truck-line fs-5"></i>
                        <span>Supplier: <strong>{{ $this->supplierNama }}</strong></span>
                    </div>
                @else
                    {{-- Default jika belum pilih supplier --}}
                    <div class="alert alert-warning d-flex align-items-center gap-2 mb-0">
                        <i class="ri-alert-line fs-5"></i>
                        <span><strong>Belum memilih supplier</strong></span>
                    </div>
                @endif
            </div>

        </div>
        
        <!-- Tabel Keranjang -->
        <div class="table-responsive mt-3">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 5%">No</th>
                        <th style="width: 35%">Produk</th>
                        <th style="width: 15%">Qty</th>
                        <th style="width: 20%">Harga Beli</th>
                        <th style="width: 20%">Subtotal</th>
                        <th style="width: 5%">Hapus</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($keranjang as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}. </td>
                            <!-- Nama Produk -->
                            <td class="fw-semibold text-uppercase">{{ $item['nama'] }}</td>

                            <!-- Qty -->
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="number"
                                        class="form-control form-control-sm fs-14"
                                        style="width: 70px"
                                        min="1"
                                        wire:model.lazy="keranjang.{{ $index }}.jumlah"
                                        wire:change="updateJumlah({{ $index }})">

                                    <span class="text-primary fs-14">{{ $item['satuan'] }}</span>
                                </div>
                            </td>


                            <!-- Harga Beli -->
                            <td class="fs-14 text-info fw-semibold">
                                <input type="number"
                                    class="form-control form-control-sm  fs-14"
                                    wire:model.lazy="keranjang.{{ $index }}.harga_beli"
                                    wire:change="updateHarga({{ $index }})">
                            </td>

                            <!-- Subtotal -->
                            <td class="fs-14 text-primary fw-semibold">
                                Rp{{ number_format($item['subtotal'], 0, ',', '.') }}
                            </td>

                            <!-- Hapus -->
                            <td class="text-center">
                                <button class="btn btn-sm btn-danger"
                                        wire:click="hapusItem({{ $index }})">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">
                                <i class="ri-shopping-cart-2-line fs-4"></i><br>
                                Keranjang kosong
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        <!-- Total Pembelian -->
        <div class="d-flex justify-content-between align-items-center mt-3 p-3 bg-light rounded">
            <h6 class="mb-0 fw-bold">TOTAL :</h6>
            <h4 class="fw-bold text-primary mb-0">
                Rp {{ number_format(collect($keranjang)->sum('subtotal'), 0, ',', '.') }}
            </h4>
        </div>

        <!-- Metode Pembayaran -->
        <div class="mt-4">
            <label class="fw-semibold">Metode Pembayaran</label>
            <select class="form-select">
                <option value="">-- Pilih Metode Pembayaran --</option>
                <option value="cash">Cash</option>
                <option value="transfer">Transfer Bank</option>
            </select>
        </div>

        <!-- Catatan -->
        <div class="mt-3">
            <label class="fw-semibold">Catatan Transaksi</label>
            <textarea class="form-control" rows="2" placeholder="Opsional..."></textarea>
        </div>

        <!-- Tombol -->
        <div class="mt-4 d-flex gap-2">
            <button class="btn btn-success w-100">
                <i class="ri-check-double-line me-1"></i> Simpan Transaksi
            </button>
            <button class="btn btn-secondary w-50">
                <i class="ri-refresh-line me-1"></i> Reset
            </button>
        </div>
    </div>
    <script>
        document.addEventListener('livewire:load', function () {
            Livewire.on('focusBarcode', () => {
                let input = document.querySelector('input[wire\\:model.lazy="barcodeInput"]');
                if (input) input.focus();
            });
        });
    </script>
</div>