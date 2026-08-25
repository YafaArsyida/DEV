<div wire:ignore.self style="width: 500px;" class="offcanvas offcanvas-end" id="offcanvasKategori" data-bs-scroll="true" 
    data-bs-backdrop="false" aria-labelledby="offcanvasKategoriLabel">

    <div class="offcanvas-header border-bottom px-4 py-3 shadow-sm">
        <div class="d-flex justify-content-between align-items-start w-100">
            <!-- Kiri -->
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-sm">
                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-18">
                        <i class="ri-file-chart-line"></i>
                    </div>
                </div>

                <div>
                    <h5 class="fw-bold mb-1">
                        Kategori Produk SmartKantin
                    </h5>
                </div>
            </div>
            <!-- Kanan -->
            <button type="button"
                class="btn btn-light btn-icon rounded-circle shadow-none"
                data-bs-dismiss="offcanvas">
                <i class="ri-close-line fs-18"></i>
            </button>
        </div>
    </div>

    <div class="offcanvas-body">
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
                <button type="button"  class="btn btn-primary w-100"
                        data-bs-toggle="modal" data-bs-target="#ModalTambahKategori"
                        wire:click="$emit('showCreateKategori', {{ $selectedKantin ?? 'null' }})">
                    <i class="ri-add-fill me-1 align-bottom"></i> Kategori
                </button>
            </div>
        </div>
        <div class="live-preview">
            <div class="table-responsive">
                <!-- Tabel Data Kelas -->
                <table class="table table-hover table-nowrap align-middle" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th class="text-uppercase text-center" width="30px">No</th>
                            <th class="text-uppercase">Kategori</th>
                            <th class="text-uppercase text-center">Aksi</th>
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
                                            <span class="fw-medium">{{ $kat->nama_kategori_produk_kantin }}</span>
                                            <p class="text-muted mb-0">{{ $kat->deskripsi ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        {{-- Edit Kategori --}}
                                        <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 d-inline-flex align-items-center gap-1"
                                            data-bs-toggle="modal" data-bs-target="#ModalEditKategori"
                                            title="Edit Kategori" wire:click.prevent="$emit('loadDataKategori', {{ $kat->ms_kategori_produk_kantin_id }})">
                                            <i class="ri-mark-pen-line"></i>
                                            <span>Edit</span>
                                        </button>

                                        {{-- Hapus Kategori --}}
                                        <button type="button" class="btn btn-soft-danger btn-sm rounded-pill px-3 d-inline-flex align-items-center gap-1"
                                            data-bs-toggle="modal" data-bs-target="#ModalDeleteKategori"
                                            title="Hapus Kategori" wire:click.prevent="$emit('confirmDeleteKategori', {{ $kat->ms_kategori_produk_kantin_id }})">
                                            <i class="ri-delete-bin-5-line"></i>
                                            <span>Hapus</span>
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