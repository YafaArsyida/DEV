<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header">
        <div class="d-flex align-items-center flex-wrap gap-3">
            <div class="flex-grow-1">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-calendar-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1">
                            Tahun Ajar
                        </h5>

                        <small class="text-muted">
                            Kelola periode tahun ajaran sekolah.
                        </small>
                    </div>
                </div>
            </div>

            <div class="flex-shrink-0">

                <button type="button" data-bs-toggle="modal"
                    id="create-btn" data-bs-target="#ModalAddTahunAjar"
                    class="btn btn-primary rounded-pill px-4 d-inline-flex align-items-center gap-1">
                    <i class="ri-add-line align-bottom"></i>
                    <span>
                        Tahun Ajar Baru
                    </span>
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3 align-items-end mb-4">
            <!-- Input Pencarian -->
            <div class="col-xxl-5 col-md-12">
                <label for="searchData" class="form-label small text-muted text-uppercase fw-medium mb-2">Pencarian</label>
                <div class="search-box">
                    <input type="text" id="searchData" class="form-control search" wire:model.debounce.300ms="search"
                        placeholder="Cari nama, deskripsi, atau lainnya...">
                    <i class="ri-search-line search-icon"></i>
                </div>
            </div>

            <!-- Pilih Jenjang -->
            <div class="col-xxl-4 col-md-6">
                <label for="jenjangSelect" class="form-label small text-muted text-uppercase fw-medium mb-2">Jenjang</label>
                <select id="jenjangSelect" wire:model="selectedJenjang" class="form-select" style="cursor: pointer"
                    data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Jenjang">
                    {{-- <option value="" selected disabled>Semua Jenjang</option> --}}
                    @foreach ($select_jenjang as $item)
                        <option value="{{ $item->ms_jenjang_id }}">{{ $item->nama_jenjang }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Pilih Status -->
            <div class="col-xxl-3 col-md-6">
                <label for="statusSelect" class="form-label small text-muted text-uppercase fw-medium mb-2">Status</label>
                <select id="statusSelect" wire:model="selectedStatus" class="form-select" style="cursor: pointer"
                    data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Status">
                    <option value="">Semua</option>
                    <option value="Aktif">Aktif</option>
                    <option value="Tidak Aktif">Tidak Aktif</option>
                </select>
            </div>
        </div>

        <!--end row-->
        <div class="live-preview">
            <div class="table-responsive">
                <table class="table table-hover nowrap align-middle" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th class="text-uppercase text-center" width="50px">status</th>
                            <th class="text-uppercase">tahun ajar</th>
                            <th class="text-uppercase">mulai</th>
                            <th class="text-uppercase">selesai</th>
                            {{-- <th class="text-uppercase">tutup buku</th> --}}
                            <th class="text-uppercase text-center">aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tahunajars as $item)
                            <tr>
                                <td>
                                    <div class="form-check ps-3 form-switch form-switch-md" dir="ltr" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Ubah Status">
                                        <input type="checkbox" class="form-check-input" id="customSwitchsizemd-{{ $item->ms_tahun_ajar_id }}" 
                                            {{ $item->status == 'Aktif' ? 'checked' : '' }}
                                            wire:change="toggleStatus('{{ $item->ms_tahun_ajar_id }}', $event.target.checked)">
                                    </div>
                                </td>
                                <td>{{ $item->nama_tahun_ajar }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d-m-Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d-m-Y') }}</td>
                                {{-- <td>
                                    @if($item->tutup_buku === 'belum')
                                        <button wire:click="tutupBuku({{ $item->ms_tahun_ajar_id }})" class="btn btn-danger btn-sm">Tutup Buku</button>
                                    @else
                                        <span class="badge bg-success">Ditutup</span>
                                    @endif
                                </td> --}}
                                <td>
                                    <div class="d-flex justify-content-center gap-2">

                                        {{-- EDIT --}}
                                        <a href="#ModalEditTahunAjar" data-bs-toggle="modal"
                                            class="btn btn-primary btn-sm rounded-pill px-3"
                                            title="Edit Tahun Ajar" wire:click.prevent="$emit('loadData', {{ $item->ms_tahun_ajar_id }})">
                                            <i class="ri-mark-pen-line me-1"></i> Edit
                                        </a>

                                        {{-- HAPUS --}}
                                        <a href="#ModalDeleteTahunAjar" data-bs-toggle="modal"
                                            class="btn btn-soft-danger btn-sm rounded-pill px-3"
                                            title="Hapus Tahun Ajar" wire:click.prevent="$emit('confirmDelete', {{ $item->ms_tahun_ajar_id }})">
                                            <i class="ri-delete-bin-5-line me-1"></i>
                                            Hapus
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
                                                style="width:75px;height:75px"></lord-icon>
                                        <h5 class="mt-2">Maaf, Tidak Ada Data yang Ditemukan</h5>
                                        <p class="text-muted mb-0">Kami telah mencari keseluruhan data, namun tidak ditemukan hasil yang sesuai.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $tahunajars->links() }}
            </div>
        </div>
    </div>
</div>