<div wire:ignore.self class="modal fade" id="ModalTambahSupplierKoperasi" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-light p-3">
                <h5 class="modal-title">Tambah Supplier Koperasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form wire:submit.prevent="save">
                <div class="modal-body">
                    <div class="row">
                        <!-- Nama Supplier -->
                        <div class="col-md-8 mb-3">
                            <label class="fw-semibold">Nama Supplier</label>
                            <input type="text" class="form-control" wire:model.defer="nama_supplier_koperasi">
                            @error('nama_supplier_koperasi') 
                                <span class="text-danger">{{ $message }}</span> 
                            @enderror
                        </div>

                        <!-- Telepon -->
                        <div class="col-md-4 mb-3">
                            <label class="fw-semibold">Telepon</label>
                            <input type="text" class="form-control" wire:model.defer="telepon">
                            @error('telepon') 
                                <span class="text-danger">{{ $message }}</span> 
                            @enderror
                        </div>

                    </div>

                    <div class="row">
                        <!-- Email -->
                        <div class="col-md-6 mb-3">
                            <label class="fw-semibold">Email</label>
                            <input type="email" class="form-control" wire:model.defer="email">
                            @error('email') 
                                <span class="text-danger">{{ $message }}</span> 
                            @enderror
                        </div>

                        <!-- NPWP -->
                        <div class="col-md-6 mb-3">
                            <label class="fw-semibold">NPWP</label>
                            <input type="text" class="form-control" wire:model.defer="npwp">
                            @error('npwp') 
                                <span class="text-danger">{{ $message }}</span> 
                            @enderror
                        </div>

                    </div>

                    <!-- Alamat -->
                    <div class="mb-3">
                        <label class="fw-semibold">Alamat</label>
                        <textarea class="form-control" rows="2" wire:model.defer="alamat"></textarea>
                        @error('alamat') 
                            <span class="text-danger">{{ $message }}</span> 
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-3">
                        <label class="fw-semibold">Deskripsi</label>
                        <textarea class="form-control" rows="2" wire:model.defer="deskripsi"></textarea>
                        @error('deskripsi') 
                            <span class="text-danger">{{ $message }}</span> 
                        @enderror
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
