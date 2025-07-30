<div class="card">
    <div class="card-header border-0 pb-0">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1">Tahun Ajar</h5>
            <div class="flex-shrink-0">
                <div class="d-flex gap-2 flex-wrap">
                    <button data-bs-toggle="modal" id="create-btn" data-bs-target="#ModalAddTahunAjar" class="btn btn-primary"><i class="ri-add-line align-bottom me-1"></i> tahun ajar baru</button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3 align-items-end mb-4">
            <!-- Input Pencarian -->
            <div class="col-xxl-6 col-md-12">
                <label for="searchInput" class="form-label">Pencarian</label>
                <div class="position-relative">
                    <input type="text" id="searchInput" class="form-control ps-4" wire:model.debounce.300ms="search" placeholder="Cari nama, deskripsi atau lainnya...">
                    <i class="ri-search-line position-absolute top-50 start-0 translate-middle-y ms-2 text-muted"></i>
                </div>
            </div>

            <!-- Pilih Jenjang -->
            <div class="col-xxl-3 col-md-6">
                <label for="jenjangSelect" class="form-label">Jenjang</label>
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
                <label for="statusSelect" class="form-label">Status</label>
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
                            <th class="text-uppercase" width="50px">status</th>
                            <th class="text-uppercase">tahun ajar</th>
                            <th class="text-uppercase">mulai</th>
                            <th class="text-uppercase">selesai</th>
                            <th class="text-uppercase">status</th>
                            <th class="text-uppercase">laba/rugi</th>
                            {{-- <th class="text-uppercase">tutup buku</th> --}}
                            <th class="text-uppercase">aksi</th>
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
                                <td class="{{ $item->status == 'Aktif' ? 'text-success' : 'text-danger' }}"><i class="ri-{{ $item->status == 'Aktif' ? 'checkbox' : 'close' }}-circle-line fs-17 align-middle"></i> {{ $item->status }}</td>
                                <td>
                                    @php
                                        $labaRugi = $item->hitungLabaRugi($selectedJenjang);
                                    @endphp

                                    @if ($labaRugi > 0)
                                        <span class="text-success fs-14 fw-medium">Laba: Rp{{ number_format($labaRugi, 0, ',', '.') }}</span>
                                    @elseif ($labaRugi < 0)
                                        <span class="text-danger fs-14 fw-medium">Rugi: Rp{{ number_format(abs($labaRugi), 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-muted fs-14 fw-medium">Imbang</span>
                                    @endif
                                </td>
                                {{-- <td>
                                    @if($item->tutup_buku === 'belum')
                                        <button wire:click="tutupBuku({{ $item->ms_tahun_ajar_id }})" class="btn btn-danger btn-sm">Tutup Buku</button>
                                    @else
                                        <span class="badge bg-success">Ditutup</span>
                                    @endif
                                </td> --}}
                                <td>
                                    <ul class="list-inline hstack gap-2 mb-0">
                                        <li class="list-inline-item edit" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit Jenjang">
                                            <a href="#ModalEditTahunAjar" data-bs-toggle="modal" class="text-success d-inline-block edit-item-btn" wire:click.prevent="$emit('loadData', {{ $item->ms_tahun_ajar_id }})">
                                                <i class="ri-pencil-line fs-17 align-middle"></i> Edit
                                            </a>
                                        </li>
                                        <li class="list-inline-item delete" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Hapus Jenjang">
                                            <a href="#ModalDeleteTahunAjar" data-bs-toggle="modal" class="text-danger d-inline-block delete-item-btn" wire:click.prevent="$emit('confirmDelete', {{ $item->ms_tahun_ajar_id }})">
                                                <i class="ri-delete-bin-5-line fs-17 align-middle"></i> Hapus
                                            </a>
                                        </li>
                                    </ul>
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