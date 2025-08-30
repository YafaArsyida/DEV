<div class="">
    <div class="row g-3 mb-3">
    <div class="col-xxl-8 col-sm-6">
        <div class="search-box">
            <input type="text" class="form-control search" 
                   wire:model.debounce.300ms="search" 
                   placeholder="cari nama, deskripsi atau lainnya...">
            <i class="ri-search-line search-icon"></i>
        </div>
    </div>
    <div class="col-xxl-4 col-sm-6">
        <button type="button" 
                class="btn btn-primary w-100"
                data-bs-toggle="modal" 
                data-bs-target="#ModalTambahKategori"
                 wire:click="$emit('showCreateKategori', {{ $selectedJenjang ?? 'null' }})">
            <i class="ri-add-fill me-1 align-bottom"></i> Kategori
        </button>
    </div>
</div>

<div class="col-xl-12">
    <div class="mt-4">
        <div class="live-preview">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover nowrap align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-uppercase text-center">No</th>
                            <th class="text-uppercase">Kategori</th>
                            <th class="text-uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kategori as $index => $kat)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="{{ $kat->icon ?? 'ri-price-tag-3-line' }} fs-1 text-primary me-2"></i>
                                        <div>
                                            <h5 class="fs-13 mb-0">{{ $kat->nama_kategori_produk_kantin }}</h5>
                                            <p class="fs-12 mb-0 text-muted">{{ $kat->deskripsi ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="hstack gap-2">
                                        {{-- Tombol Edit Kategori --}}
                                        <button class="btn btn-sm btn-primary d-inline-flex align-items-center"
                                                data-bs-toggle="modal"
                                                data-bs-target="#ModalEditKategori"
                                                title="Edit Kategori"
                                                wire:click.prevent="$emit('loadDataKategori', {{ $kat->ms_kategori_produk_kantin_id }})">
                                            <i class="ri-quill-pen-line align-bottom me-1"></i> Edit
                                        </button>

                                        {{-- Tombol Hapus Kategori --}}
                                        <button class="btn btn-sm btn-soft-danger d-inline-flex align-items-center"
                                                data-bs-toggle="modal"
                                                data-bs-target="#ModalDeleteKategori"
                                                title="Hapus Kategori"
                                                wire:click.prevent="$emit('confirmDeleteKategori', {{ $kat->ms_kategori_produk_kantin_id }})">
                                            <i class="ri-delete-bin-5-line align-bottom me-1"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Tidak ada data kategori</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</div>