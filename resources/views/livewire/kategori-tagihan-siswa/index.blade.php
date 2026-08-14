<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">
            {{-- TITLE --}}
            <div>
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-calendar-event-line">
                            </i>
                        </div>
                    </div>
    
                    <div>
                        <h5 class="fw-bold mb-1">
                            Data Kategori Tagihan
                        </h5>
                        {{-- <small>
                            Kelola laporan kegiatan generus 
                        </small> --}}
                    </div>
                </div>
            </div>
    
            {{-- ACTION --}}
            @if ($selectedJenjang && $selectedTahunAjar)
            <div class="d-flex gap-2 flex-wrap">
                {{-- TAMBAH --}}
                <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#ModalAddKategoriTagihan" wire:click.prevent="$emit('showCreateKategori', {{ $selectedJenjang }}, {{ $selectedTahunAjar }})">
                    <i class="ri-add-line me-1"></i>Tambah Kategori
                </button>
            </div>
            @endif
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3 mb-3">
            <!-- Kotak Pencarian -->
            <div class="col-xxl-12 col-sm-12">
                <label for="searchKategori" class="form-label small text-muted text-uppercase fw-medium mb-2">Pencarian</label>
                <div class="search-box">
                    <input type="text" id="searchKategori" class="form-control search" wire:model.debounce.300ms="search"
                        placeholder="Cari kategori...">
                    <i class="ri-search-line search-icon"></i>
                </div>
            </div>
        </div>
        <!--end row-->
        <div class="live-preview">
            @if (!$selectedJenjang || !$selectedTahunAjar)
                <div class="text-center py-4">
                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                        colors="primary:#405189,secondary:#08a88a"
                        style="width:75px;height:75px">
                    </lord-icon>
                    <h5 class="mt-2">Silakan Pilih Jenjang dan Tahun Ajar</h5>
                    <p class="text-muted mb-0">Untuk melihat data kelas, harap pilih Jenjang dan Tahun Ajar terlebih dahulu.</p>
                </div>
            @else
            <div class="table-responsive">
                <table class="table table-hover table-nowrap align-middle" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th class="text-uppercase" width="30px">NO</th>
                            <th class="text-uppercase" style="width: 50px;">Hapus</th>
                            <th class="text-uppercase">kategori</th>
                            <th class="text-uppercase text-center">aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kategoris as $key => $item)
                        <tr>
                            <td>{{ $kategoris->firstItem() + $key }}.</td>
                            <td class="text-center">
                                <a href="#ModalDeleteKategoriTagihan" data-bs-toggle="modal" class="text-danger d-inline-block remove-item-btn"
                                    wire:click.prevent="$emit('confirmDeleteKategori', {{ $item->ms_kategori_tagihan_siswa_id }})"
                                    data-bs-trigger="hover" data-bs-placement="top" title="Hapus Kategori">
                                    <i class="ri-delete-bin-5-fill fs-14"></i>
                                </a>
                            </td>
                            <td>
                                <span class="fw-medium">
                                    {{ $item->nama_kategori_tagihan_siswa  }}
                                </span>
                                <p class="text-muted mb-0">{{ $item->deskripsi }}</p>
                            </td>
                            <td class="text-center">
                                {{-- edit --}}
                                <a href="#ModalEditKategoriTagihan" data-bs-toggle="modal" class="btn btn-primary btn-sm rounded-pill px-3" title="Edit Kategori" 
                                    wire:click="$emit('loadDataKategoriTagihan', {{ $item->ms_kategori_tagihan_siswa_id }})">
                                    <i class="ri-mark-pen-line me-1"></i> Edit
                                </a>
                            </td>                
                        </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="noresult text-center py-3">
                                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" 
                                                colors="primary:#405189,secondary:#08a88a" 
                                                style="width:75px;height:75px"></lord-icon>
                                        <h5 class="mt-2">Maaf, Tidak Ada Data yang Ditemukan</h5>
                                        <p class="text-muted mb-0">Kami telah mencari keseluruhan data, namun tidak ditemukan hasil yang sesuai.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{-- PAGINATION --}}
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="text-muted fs-13">
                            Menampilkan
                            <span class="fw-semibold">
                                {{ $kategoris->firstItem() ?? 0 }}
                            </span>
                            -
                            <span class="fw-semibold">
                                {{ $kategoris->lastItem() ?? 0 }}
                            </span>
                            dari
                            <span class="fw-semibold">
                                {{ $kategoris->total() }}
                            </span>
                            data kategori
                        </div>
                        <div>
                            {{ $kategoris->links() }}
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>