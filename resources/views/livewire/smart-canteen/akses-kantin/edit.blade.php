<div wire:ignore.self class="modal fade" id="editPengguna">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-light">
                <h5 class="modal-title">Edit Petugas Kantin</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form wire:submit.prevent="update">
                <div class="modal-body">
                    <div class="row g-3">

                        <div class="col-lg-6">
                            <label class="form-label">Nama</label>
                            <input type="text" wire:model.defer="nama" class="form-control">
                            @error('nama') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="col-lg-6">
                            <label class="form-label">Email</label>
                            <input type="text" wire:model.defer="email" class="form-control">
                            @error('email') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label">Pilih Kantin</label>
                            <select wire:model="ms_kantin_id" class="form-select">
                                <option value="">-- Pilih Kantin --</option>
                                @foreach($kantinList as $kantin)
                                <option value="{{ $kantin->ms_kantin_id }}">
                                    {{ $kantin->nama_kantin }}
                                </option>
                                @endforeach
                            </select>
                            @error('ms_kantin_id')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-lg-12">
                            <label class="form-label">Akses Jenjang</label>
                        
                            @foreach($jenjangList as $jenjang)
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" wire:model.defer="ms_jenjang_id"
                                    value="{{ $jenjang->ms_jenjang_id }}" id="jenjang_{{ $jenjang->ms_jenjang_id }}">
                                <label class="form-check-label">
                                    {{ $jenjang->nama_jenjang }}
                                </label>
                            </div>
                            @endforeach
                        
                            @error('ms_jenjang_id')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <a href="javascript:void(0);" class="btn btn-link link-success shadow-none fw-medium" data-bs-dismiss="modal"><i
                            class="ri-close-line me-1 align-middle"></i> Tutup</a>
                    <button class="btn btn-primary">Update</button>
                </div>
            </form>

        </div>
    </div>
</div>