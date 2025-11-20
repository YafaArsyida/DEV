<div wire:ignore.self class="modal fade" id="ModalEditProdukKoperasi" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-light p-3">
                <h5 class="modal-title">Edit Produk Koperasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form wire:submit.prevent="update">
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Kode Produk</label>
                            <input type="text" class="form-control" wire:model.defer="kode_produk_koperasi">
                            @error('kode_produk_koperasi') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-8 mb-3">
                            <label>Nama Produk</label>
                            <input type="text" class="form-control" wire:model.defer="nama_produk_koperasi">
                            @error('nama_produk_koperasi') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Kategori</label>
                            <select class="form-control" wire:model.defer="ms_kategori_produk_koperasi_id">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategoriList as $kat)
                                    <option value="{{ $kat->ms_kategori_produk_koperasi_id }}">
                                        {{ $kat->nama_kategori_produk_koperasi }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ms_kategori_produk_koperasi_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Satuan</label>
                            <select class="form-control" wire:model.defer="satuan">
                                <option value="pcs">pcs</option>
                                <option value="buah">buah</option>
                                <option value="bungkus">bungkus</option>
                                <option value="botol">botol</option>
                                <option value="pack">pack</option>
                                <option value="dus">dus</option>
                                <option value="box">box</option>
                                <option value="unit">unit</option>
                                <option value="lembar">lembar</option>
                                <option value="set">set</option>
                            </select>
                            @error('satuan') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Stok</label>
                            <input type="number" class="form-control" wire:model.defer="stok">
                            @error('stok') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Harga Beli</label>
                            <input type="number" class="form-control" wire:model.defer="harga_beli">
                            @error('harga_beli') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Harga Jual</label>
                            <input type="number" class="form-control" wire:model.defer="harga_jual">
                            @error('harga_jual') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Status</label>
                        <select class="form-control" wire:model.defer="status_produk_koperasi">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                        @error('status_produk_koperasi') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label>Deskripsi</label>
                        <textarea class="form-control" rows="3" wire:model.defer="deskripsi"></textarea>
                        @error('deskripsi') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-save-3-line me-1"></i> Update
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
