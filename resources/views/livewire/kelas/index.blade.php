<div class="card">
    <div class="card-header border-0 pb-0">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1">Data Kelas</h5>
            @if ($selectedJenjang && $selectedTahunAjar)
            <div class="flex-shrink-0">
                <div class="d-flex gap-2 flex-wrap">
                    <button data-bs-toggle="modal" id="create-btn" data-bs-target="#ModalAddKelas" wire:click.prevent="$emit('showCreateKelas', {{ $selectedJenjang }}, {{ $selectedTahunAjar }})" class="btn btn-primary"><i class="ri-play-list-add-line me-1 align-bottom"></i> Kelas Baru</button>
                </div>
            </div>
            @endif
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3 mb-3">
            <div class="col-xxl-12 col-sm-12">
                <label for="searchData" class="form-label">Pencarian</label>
                <div class="search-box">
                    <input type="text" id="searchData" class="form-control search" wire:model.debounce.300ms="search"
                        placeholder="Cari nama, deskripsi, atau lainnya...">
                    <i class="ri-search-line search-icon"></i>
                </div>
            </div>
        </div>
        <!--end row-->
        {{-- DATA --}}
        <div class="live-preview">
            <!-- Jika Jenjang atau Tahun Ajar belum dipilih -->
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
                <!-- Tabel Data Kelas -->
                <table class="table table-hover nowrap align-middle" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th class="text-uppercase" width="50px">no</th>
                            <th class="text-uppercase" style="width: 50px;">Hapus</th>
                            <th class="text-uppercase">kelas</th>
                            <th class="text-uppercase">siswa</th>
                            <th class="text-uppercase">aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kelass as $item)
                            <tr>
                                <td>
                                    {{ ($kelass->firstItem() ?? 0) + $loop->index }}.
                                </td>
                                <th class="text-center">
                                    <a href="#ModalAksiDelete" data-bs-toggle="modal" class="btn btn-sm btn-soft-danger d-inline-flex align-items-center gap-1"
                                        data-bs-target="#ModalDeleteKelas"
                                        title="Hapus Kelas"
                                        wire:click.prevent="$emit('confirmDeleteKelas', {{ $item->ms_kelas_id }})">
                                        <i class="ri-delete-bin-5-line"></i>
                                    </a>
                                </th>
                                <td>
                                    <span class="fw-medium" style="white-space: nowrap;">
                                        {{ $item->nama_kelas }}
                                    </span>
                                    <p class="text-muted mb-0">{{ $item->deskripsi }}</p>
                                </td>
                                <td>{{ $item->ms_penempatan_siswa_count }} siswa</td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                
                                        {{-- Pindah Kelas --}}
                                        <a href="#ModalChangeKelas" data-bs-toggle="modal" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1"
                                            title="Pindah Kelas" wire:click.prevent="$emit('showKelas', {
                                                        jenjang: {{ $item->ms_jenjang_id }},
                                                        tahunAjar: {{ $item->ms_tahun_ajar_id }},
                                                        kelasId: {{ $item->ms_kelas_id }}
                                                   })">
                                            <i class="ri-arrow-left-right-line align-middle"></i>
                                            <span>Pindah</span>
                                        </a>
                                        <div class="vr mx-1"></div>
                                        {{-- Naik Kelas --}}
                                        <a href="#ModalPromoteKelas" data-bs-toggle="modal" class="btn btn-sm btn-soft-danger d-inline-flex align-items-center gap-1"
                                            title="Naik Kelas" wire:click.prevent="$emit('showPromote', {
                                                        jenjang: {{ $item->ms_jenjang_id }},
                                                        tahunAjar: {{ $item->ms_tahun_ajar_id }},
                                                        kelasId: {{ $item->ms_kelas_id }}
                                                   })">
                                            <i class="ri-plane-line align-middle"></i>
                                            <span>Naik</span>
                                        </a>
                                
                                        {{-- DIVIDER --}}
                                        <div class="vr mx-1"></div>
                                
                                        {{-- EDIT --}}
                                        
                                        <a href="#ModalEditKelas" data-bs-toggle="modal" class="text-primary d-inline-block" title="Edit Kelas" 
                                            wire:click="$emit('loadDataKelas', {{ $item->ms_kelas_id }})">
                                            <i class="ri-quill-pen-line fs-17 align-middle"></i> Edit
                                        </a>
                                
                                    </div>
                                </td>                                 
                            </tr>
                        @empty
                            <!-- Jika Tidak Ada Data Kelas -->
                            <tr>
                                <td colspan="7">
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
                <!-- Pagination -->
                {{ $kelass->links() }}
            @endif
        </div>
        {{-- DATA --}}
    </div>
</div>