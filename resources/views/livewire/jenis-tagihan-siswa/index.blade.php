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
                            Data Jenis Tagihan
                        </h5>
                    </div>
                </div>
            </div>
    
            {{-- ACTION --}}
            @if ($selectedJenjang && $selectedTahunAjar)
            <div class="d-flex gap-2 flex-wrap">
                {{-- TAMBAH --}}
                <button type="button" class="btn btn-info rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#ModalImportTagihan" wire:click.prevent="$emit('showImportTagihan', {{ $selectedJenjang }}, {{ $selectedTahunAjar }})">
                    <i class="ri-add-line me-1"></i>Import Jenis Tagihan
                </button>
                <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#ModalAddJenisTagihan" wire:click.prevent="$emit('showCreateJenis', {{ $selectedJenjang }}, {{ $selectedTahunAjar }})">
                    <i class="ri-add-line me-1"></i>Jenis Tagihan Baru
                </button>
            </div>
            @endif
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3 mb-3">
            <!-- Dropdown Kategori -->
            <div class="col-xxl-2 col-sm-6">
                <label for="filterKategoriTagihan" class="form-label small text-muted text-uppercase fw-medium mb-2">Kategori</label>
                <select id="filterKategoriTagihan" wire:model="selectedKategoriTagihan" style="cursor: pointer" class="form-select"
                    data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Kategori">
                    <option value="">Semua Kategori</option>
                    @foreach ($select_kategori as $item)
                    <option value="{{ $item->ms_kategori_tagihan_siswa_id }}">
                        {{ $item->nama_kategori_tagihan_siswa }}
                    </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Input Pencarian -->
            <div class="col-xxl-10 col-sm-6">
                <label for="searchTagihan" class="form-label small text-muted text-uppercase fw-medium mb-2">Pencarian</label>
                <div class="search-box">
                    <input type="text" id="searchTagihan" class="form-control search" wire:model.debounce.300ms="search"
                        placeholder="Cari nama, kategori, atau deskripsi...">
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
                            <th class="text-uppercase" width="30px">NO</th>
                            <th class="text-uppercase" style="width: 50px;">Hapus</th>
                            <th class="text-uppercase text-center" width="50px">cicilan</th>
                            <th class="text-uppercase">tagihan</th>
                            <th class="text-uppercase">kategori</th>
                            <th class="text-uppercase">cicilan</th>
                            <th class="text-uppercase">jatuh tempo</th>
                            <th class="text-uppercase text-center">aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jenis_tagihans as $key => $item)
                        <tr>
                            <td>{{ $jenis_tagihans->firstItem() + $key }}.</td>
                            <td class="text-center">
                                <a href="#ModalDeleteJenisTagihan" data-bs-toggle="modal" class="text-danger d-inline-block remove-item-btn"
                                    wire:click.prevent="$emit('confirmDeleteJenis', {{ $item->ms_jenis_tagihan_siswa_id }})"
                                    data-bs-trigger="hover" data-bs-placement="top" title="Hapus Jenis Tagihan">
                                    <i class="ri-delete-bin-5-fill fs-14"></i>
                                </a>
                            </td>
                            <td class="text-center">
                                <div class="form-check ps-3 form-switch form-switch-md" dir="ltr" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Ubah Status Cicilan">
                                    <input type="checkbox" class="form-check-input" id="customSwitchsizemd-{{ $item->ms_jenis_tagihan_siswa_id }}" 
                                        {{ $item->cicilan_status == 'Aktif' ? 'checked' : '' }}
                                        wire:change="toggleStatus('{{ $item->ms_jenis_tagihan_siswa_id }}', $event.target.checked)">
                                </div>
                            </td>
                            <td>
                                <span class="fw-medium">
                                    {{ $item->nama_jenis_tagihan_siswa }}
                                </span>
                                <p class="text-muted mb-0">{{ $item->deskripsi }}</p>
                            <td>{{ $item->ms_kategori_tagihan_siswa->nama_kategori_tagihan_siswa }}</td>
                            <td class="{{ $item->cicilan_status == 'Aktif' ? 'text-success' : 'text-danger' }}"><i class="ri-{{ $item->cicilan_status == 'Aktif' ? 'checkbox' : 'close' }}-circle-line fs-17 align-middle"></i> {{ $item->cicilan_status }}</td>
                            <td>{{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($item->tanggal_jatuh_tempo, 'd F Y') }}</td>
                            <td class="text-center">
                                {{-- edit --}}
                                <a href="#ModalEditJenisTagihan" data-bs-toggle="modal" class="btn btn-primary btn-sm rounded-pill px-3" title="Edit Jenis Tagihan" 
                                    wire:click="$emit('loadDataJenisTagihan', {{ $item->ms_jenis_tagihan_siswa_id }})">
                                    <i class="ri-mark-pen-line me-1"></i> Edit
                                </a>
                            </td>                                   
                        </tr>
                        @empty
                            <tr>
                                <td colspan="8">
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
                                {{ $jenis_tagihans->firstItem() ?? 0 }}
                            </span>
                            -
                            <span class="fw-semibold">
                                {{ $jenis_tagihans->lastItem() ?? 0 }}
                            </span>
                            dari
                            <span class="fw-semibold">
                                {{ $jenis_tagihans->total() }}
                            </span>
                            data jenis tagihan
                        </div>
                        <div>
                            {{ $jenis_tagihans->links() }}
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>