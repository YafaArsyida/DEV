<div class="card">
    <div class="card-header border-0 pb-0">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1">Kategori Tagihan</h5>
            @if ($selectedJenjang && $selectedTahunAjar)
            <div class="flex-shrink-0">
                <div class="d-flex gap-2 flex-wrap">
                    <button data-bs-toggle="modal" id="create-btn" data-bs-target="#ModalAddKategoriTagihan" wire:click.prevent="$emit('showCreateKategori', {{ $selectedJenjang }}, {{ $selectedTahunAjar }})" class="btn btn-primary"><i class="ri-play-list-add-line me-1 align-bottom"></i> Kategori Baru</button>
                </div>
            </div>
            @endif
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3 mb-3">
            <div class="col-xxl-12 col-sm-12">
                <div class="search-box">
                    <input type="text" class="form-control search" wire:model.debounce.300ms="search" placeholder="cari nama, deskripsi atau lainnya...">
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
                <table class="table table-hover nowrap align-middle" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th class="text-uppercase" width="50px">NO</th>
                            <th class="text-uppercase" style="width: 50px;">Hapus</th>
                            <th class="text-uppercase">kategori</th>
                            <th class="text-uppercase">aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kategoris as $item)
                        <tr>
                            <td>{{ $item->urutan }}.</td>
                            <td>
                                <a href="#ModalDeleteKategoriTagihan" data-bs-toggle="modal" class="btn btn-sm btn-soft-danger d-inline-flex align-items-center gap-1" 
                                wire:click.prevent="$emit('confirmDelete', {{ $item->ms_kategori_tagihan_siswa_id }})" data-bs-trigger="hover" data-bs-placement="top" title="Hapus Kategori">
                                    <i class="ri-delete-bin-5-line"></i>
                                </a>
                            </td>
                            <td>
                                <span class="fw-medium">
                                    {{ $item->nama_kategori_tagihan_siswa  }}
                                </span>
                                <p class="text-muted mb-0">{{ $item->deskripsi }}</p>
                            </td>
                            <td>
                                <div class="hstack gap-2">
                                    {{-- Tombol Edit Kategori Tagihan --}}
                                    <button class="btn btn-sm btn-primary d-inline-flex align-items-center"
                                            data-bs-toggle="modal"
                                            data-bs-target="#ModalEditKategoriTagihan"
                                            title="Edit Kategori"
                                            wire:click.prevent="$emit('loadDataKategoriTagihan', {{ $item->ms_kategori_tagihan_siswa_id }})">
                                        <i class="ri-quill-pen-line align-bottom me-1"></i> Edit
                                    </button>
                                </div>
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
                {{ $kategoris->links() }}
            </div>
            @endif

        </div>
    </div>
</div>