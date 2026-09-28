<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    {{-- HEADER --}}
    <div class="card-header">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">
            {{-- TITLE --}}
            <div>
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-store-2-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1">
                            Data Kantin
                        </h5>

                        <small class="text-muted">
                            Kelola data kantin yang terhubung dengan transaksi sekolah.
                        </small>
                    </div>
                </div>
            </div>

            {{-- ACTION --}}
            <div class="d-flex gap-2 flex-wrap">
                <button type="button" id="create-btn"
                    class="btn btn-primary rounded-pill px-4 d-inline-flex align-items-center gap-1"
                    data-bs-toggle="modal" data-bs-target="#ModalAddKantin"
                    wire:click.prevent="$emit('showCreateKantin')">

                    <i class="ri-add-line"></i>
                    <span>Kantin Baru</span>
                </button>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="row g-3 mb-3">
            <!-- Kotak Pencarian -->
            <div class="col-xxl-12 col-sm-12">
                <label for="searchData" class="form-label small text-muted text-uppercase fw-medium mb-2">Pencarian</label>
                <div class="search-box">
                    <input type="text" id="searchData" class="form-control search" wire:model.debounce.300ms="search"
                        placeholder="Cari kantin, deskripsi, atau lainnya...">
                    <i class="ri-search-line search-icon"></i>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-nowrap align-middle" style="width:100%">
                <thead class="table-light">
                    <tr class="text-uppercase">
                        <th class="text-center">No</th>
                        <th class="text-center">Hapus</th>
                        <th>Kantin</th>
                        <th>Petugas</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kantin as $index => $item)
                    <tr>
                        <td class="text-center">
                            {{ ($kantin->firstItem() ?? 0) + $loop->index }}.
                        </td>

                        <td class="text-center">
                            <!-- Hapus Kantin -->
                            <a href="#ModalDeleteKantin" data-bs-toggle="modal" class="text-danger d-inline-block remove-item-btn"
                                wire:click.prevent="$emit('confirmDeleteKantin', {{ $item->ms_kantin_id }})" data-bs-trigger="hover"
                                data-bs-placement="top" title="Hapus Kantin">
                                <i class="ri-delete-bin-5-fill fs-16"></i>
                            </a>
                        </td>
                        <td>
                            <span class="fw-medium">
                                {{ $item->nama_kantin }}
                            </span>
                            <p class="text-muted mb-0">{{ $item->deskripsi }}</p>
                        </td>
                        <td>
                            @if($item->ms_pengguna->isNotEmpty())
                            {{ $item->ms_pengguna->pluck('nama')->implode(', ') }}
                            @else
                            <span class="text-muted">Belum ada petugas</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">

                                {{-- Detail Kantin --}}
                                <a href="#ModalDetailKantin" data-bs-toggle="modal"
                                    class="btn btn-soft-primary btn-sm rounded-pill px-3"
                                    title="Detail Kantin"
                                    wire:click.prevent="$emit('detailKantin', {{ $item->ms_kantin_id }})">

                                    <i class="ri-eye-line me-1"></i>
                                    Detail

                                </a>

                                {{-- Edit Kantin --}}
                                <a href="#ModalEditKantin" data-bs-toggle="modal"
                                    class="btn btn-primary btn-sm rounded-pill px-3"
                                    title="Edit Kantin"
                                    wire:click.prevent="$emit('editKantin', {{ $item->ms_kantin_id }})">

                                    <i class="ri-mark-pen-line me-1"></i>
                                    Edit
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="noresult text-center py-3">
                                <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                    colors="primary:#405189,secondary:#08a88a"
                                    style="width:75px;height:75px">
                                </lord-icon>
                                <h5 class="mt-2">Maaf, Tidak Ada Data yang Ditemukan</h5>
                                <p class="text-muted mb-0">Kami telah mencari keseluruhan data, namun tidak ditemukan hasil yang sesuai.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="text-muted fs-13">
                        Menampilkan
                        <span class="fw-semibold">
                            {{ $kantin->firstItem() ?? 0 }}
                        </span>
                        -
                        <span class="fw-semibold">
                            {{ $kantin->lastItem() ?? 0 }}
                        </span>
                        dari
                        <span class="fw-semibold">
                            {{ $kantin->total() }}
                        </span>
                        data
                    </div>
                    <div>
                        {{ $kantin->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>