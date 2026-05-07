<div class="card">
    <div class="card-header border-0 pb-0">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1">Data Kantin</h5>
            <div class="flex-shrink-0">
                <div class="d-flex gap-2 flex-wrap">
                    <button data-bs-toggle="modal" id="create-btn" data-bs-target="#ModalAddKantin"
                        wire:click.prevent="$emit('showCreateKantin')"
                        class="btn btn-primary"><i class="ri-store-2-line"></i> Kantin Baru</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="row g-3 mb-3">
            <!-- Kotak Pencarian -->
            <div class="col-xxl-12 col-sm-12">
                <label for="searchKategori" class="form-label">Pencarian</label>
                <div class="search-box">
                    <input type="text" class="form-control" wire:model.debounce.300ms="search"
                        placeholder="Cari nama kantin atau deskripsi...">
                    <i class="ri-search-line search-icon"></i>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr class="text-uppercase">
                        <th width="50px">No</th>
                        <th width="50px">Hapus</th>
                        <th>Kantin</th>
                        <th>Petugas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kantin as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}.</td>

                        <td class="text-center">
                            <!-- Hapus Kantin -->
                            <a href="#ModalDeleteKantin" data-bs-toggle="modal" class="text-danger d-inline-block remove-item-btn"
                                wire:click.prevent="$emit('confirmDeleteKantin', {{ $item->ms_kantin_id }})" data-bs-trigger="hover"
                                data-bs-placement="top" title="Hapus Kantin">
                                <i class="ri-delete-bin-5-fill fs-16"></i>
                            </a>
                        </td>
                        <td>
                            <span class="fw-medium" style="white-space: nowrap;">
                                {{ $item->nama_kantin }}
                            </span>
                            <p class="text-muted mb-0">{{ $item->deskripsi }}</p>
                        </td>
                        <td style="white-space: nowrap;">
                            @if($item->ms_pengguna->isNotEmpty())
                            {{ $item->ms_pengguna->pluck('nama')->implode(', ') }}
                            @else
                            <span class="text-muted">Belum ada petugas</span>
                            @endif
                        </td>
                        <td style="white-space: nowrap;">
                            <!-- Detail Kantin -->
                            <a href="#ModalDetailKantin" data-bs-toggle="modal" class="text-primary d-inline-block detail-item-btn"
                                wire:click.prevent="$emit('detailKantin',  {{ $item->ms_kantin_id }})">
                                <i class="ri-eye-line fs-17 align-middle"></i> Detail
                            </a>
                        
                            <!-- Edit Kantin -->
                            <a href="#ModalEditKantin" data-bs-toggle="modal" class="text-warning d-inline-block detail-item-btn ms-1"
                                wire:click.prevent="$emit('editKantin',  {{ $item->ms_kantin_id }})">
                                <i class="ri-mark-pen-line fs-17 align-middle"></i> Edit
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            Data kantin tidak ditemukan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>