<div class="card">
    <div class="card-header border-0 pb-0">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1">Akuntansi Kelompok Rekening</h5>
            <div class="flex-shrink-0">
                <div class="d-flex gap-2 flex-wrap">
                    {{-- <button data-bs-toggle="modal" id="create-btn" data-bs-target="#CreateKelompokRekening" wire:click.prevent="$emit('CreateKelompokRekening')" class="btn btn-primary"><i class="ri-play-list-add-line me-1 align-bottom"></i> Kelompok Rekening Baru</button> --}}
                </div>
            </div>
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
            <div class="table-responsive">
                <table class="table table-hover nowrap align-middle" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th class="text-uppercase" width="50px">NO</th>
                            {{-- <th class="text-uppercase" style="width: 50px;">Hapus</th> --}}
                            <th class="text-uppercase">kelompok rekening</th>
                            {{-- <th class="text-uppercase">aksi</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kelompok as $item)
                        <tr>
                            <td>{{ $loop->iteration }}.</td>
                            {{-- <td>
                                <a href="#DeleteKelompokRekening" data-bs-toggle="modal" class="btn btn-soft-danger d-inline-flex align-items-center gap-1" 
                                wire:click.prevent="$emit('confirmDelete', {{ $item->akuntansi_kelompok_rekening_id }})" data-bs-trigger="hover" data-bs-placement="top" title="Hapus Kelompok Rekening">
                                    <i class="ri-delete-bin-5-line"></i>
                                </a>
                            </td> --}}
                            <td>
                                <div>
                                    <h5 class="fs-13 mb-0">{{ $item->nama_kelompok_rekening }}</h5>
                                    <p class="fs-12 mb-0 text-muted">{{ $item->deskripsi }}</p>
                                </div>
                            </td>
                            {{-- <td>
                                <ul class="list-inline hstack gap-2 mb-0">
                                    <li class="list-inline-item detail" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit Kelompok Rekening">
                                        <a href="#EditKelompokRekening" data-bs-toggle="modal" class="btn btn-primary d-inline-flex align-items-center gap-1" wire:click.prevent="$emit('loadDataKelompok Rekening', {{ $item->akuntansi_kelompok_rekening_id }})">
                                            <i class="ri-quill-pen-line align-bottom"></i> Edit
                                        </a>
                                    </li>
                                </ul>
                            </td> --}}
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
            </div>
        </div>
    </div>
</div>