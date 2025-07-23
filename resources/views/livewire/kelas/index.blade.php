<div class="card">
    <div class="card-header border-0 pb-0">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1"> Kelas</h5>
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
                <div class="search-box">
                    <input type="text" class="form-control search" wire:model.debounce.300ms="search" placeholder="cari nama, deskripsi atau lainnya...">
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
                <div data-simplebar data-simplebar-auto-hide="false" style="max-height: 400px;" class="table-responsive">
                    <table class="table table-hover nowrap align-middle" style="width:100%">
                    {{-- <div class="text-center my-3">
                        <h4 class="mb-0">Data Kelas Jenjang {{ $namaJenjang }}</h4>
                        <div>Tahun Ajaran {{ $namaTahunAjar }}</div>
                    </div> --}}
                        <thead class="table-light">
                            <tr>
                                <th class="text-uppercase">transfer</th>
                                {{-- <th class="text-uppercase" width="50px">no</th> --}}
                                <th class="text-uppercase">kelas</th>
                                <th class="text-uppercase">siswa</th>
                                <th class="text-uppercase">aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kelass as $item)
                                <tr>
                                    <td>
                                        <div class="hstack gap-2">
                                            {{-- Tombol Pindah Kelas --}}
                                            <button class="btn btn-sm btn-primary d-inline-flex align-items-center"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#ModalChangeKelas"
                                                    data-bs-placement="top" 
                                                    title="Pindah Kelas"
                                                    wire:click.prevent="$emit('showKelas', {
                                                        jenjang: {{ $item->ms_jenjang_id }},
                                                        tahunAjar: {{ $item->ms_tahun_ajar_id }},
                                                        kelasId: {{ $item->ms_kelas_id }}
                                                    })">
                                                <i class="ri-arrow-left-right-line align-bottom me-1"></i> Pindah
                                            </button>
                                    
                                            {{-- Tombol Naik Kelas --}}
                                            <button class="btn btn-sm btn-danger d-inline-flex align-items-center"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#ModalPromoteKelas"
                                                    data-bs-placement="top"
                                                    title="Naik Kelas"
                                                    wire:click.prevent="$emit('showPromote', {
                                                        jenjang: {{ $item->ms_jenjang_id }},
                                                        tahunAjar: {{ $item->ms_tahun_ajar_id }},
                                                        kelasId: {{ $item->ms_kelas_id }}
                                                    })">
                                                <i class="ri-plane-line align-bottom me-1"></i> Naik
                                            </button>
                                        </div>
                                    </td>
                                    
                                    {{-- <td>#{{ $item->urutan }}</td> --}}
                                    <td>
                                        <span class="fw-medium" style="white-space: nowrap;">
                                            {{ $item->nama_kelas }}
                                        </span>
                                        <p class="text-muted mb-0">{{ $item->deskripsi }}</p>
                                    </td>
                                    <td>{{ $item->jumlah_siswa() ?? '0' }} siswa</td>
                                    <td>
                                        <div class="hstack gap-2">
                                            {{-- Tombol Edit Kelas --}}
                                            <button class="btn btn-sm btn-primary d-inline-flex align-items-center"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#ModalEditKelas"
                                                    title="Edit Kelas"
                                                    wire:click.prevent="$emit('loadDataKelas', {{ $item->ms_kelas_id }})">
                                                <i class="ri-quill-pen-line align-bottom me-1"></i> Edit
                                            </button>
                                    
                                            {{-- Tombol Hapus Kelas --}}
                                            <button class="btn btn-sm btn-soft-danger d-inline-flex align-items-center"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#ModalDeleteKelas"
                                                    title="Hapus Kelas"
                                                    wire:click.prevent="$emit('confirmDeleteKelas', {{ $item->ms_kelas_id }})">
                                                <i class="ri-delete-bin-5-line align-bottom me-1"></i>
                                            </button>
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
                </div>
            @endif
        </div>
        {{-- DATA --}}
    </div>
</div>