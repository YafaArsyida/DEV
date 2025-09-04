<div wire:ignore.self class="modal fade" id="ModalTambahProduk" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title">Tambah Produk Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit.prevent="save">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                             <label>Nama Produk</label>
                            <input type="text" class="form-control" wire:model.defer="nama_produk_kantin">
                            @error('nama_produk_kantin') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Kategori</label>
                            <select class="form-control" wire:model.defer="ms_kategori_produk_kantin_id">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategoriList as $kat)
                                    <option value="{{ $kat->ms_kategori_produk_kantin_id }}">{{ $kat->nama_kategori_produk_kantin }}</option>
                                @endforeach
                            </select>
                            @error('ms_kategori_produk_kantin_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Harga</label>
                            <input type="number" class="form-control" wire:model.defer="harga">
                            @error('harga') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Stok</label>
                            <input type="number" class="form-control" wire:model.defer="stok">
                            @error('stok') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Satuan</label>
                            <input type="text" class="form-control" wire:model.defer="satuan">
                            @error('satuan') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                    </div>
                    <div class="row">
                    <!-- Pilih Icon -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Icon Produk</label>
                        <select class="form-select" wire:model.defer="icon">
                            <option value="">-- Pilih Icon --</option>
                            <option value="mdi mdi-pizza">🍕 Pizza</option>
                            <option value="mdi mdi-cup-water">🥤 Minuman</option>
                            <option value="mdi mdi-candy">🍬 Permen</option>
                            <option value="mdi mdi-ice-cream">🍦 Es Krim</option>
                            <option value="mdi mdi-hamburger">🍔 Burger</option>
                            <option value="mdi mdi-food-apple">🍎 Buah</option>
                            <option value="mdi mdi-noodles">🍜 Mie</option>
                            <option value="mdi mdi-fish">🐟 Ikan</option>
                        </select>
                        @error('icon') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <!-- Pilih Warna -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Warna Icon</label>
                        <select class="form-select" wire:model.defer="icon_color">
                            <option value="">-- Pilih Warna --</option>
                            <option value="text-primary">Biru Tua</option>
                            <option value="text-info">Biru Muda</option>
                            <option value="text-success">Hijau</option>
                            <option value="text-danger">Merah</option>
                            <option value="text-warning">Kuning</option>
                            <option value="text-secondary">Abu-abu</option>
                            <option value="text-dark">Hitam</option>
                        </select>
                        @error('icon_color') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                    
                    <div class="mb-3">
                        <label>Deskripsi</label>
                        <textarea class="form-control" wire:model.defer="deskripsi"></textarea>
                        @error('deskripsi') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <a href="javascript:void(0);" class="btn btn-link link-success shadow-none fw-medium"
                       data-bs-dismiss="modal">
                        <i class="ri-close-line me-1 align-middle"></i> Tutup
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-save-3-line me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
