<div class="card">
    <div class="card-header border-0 pb-0">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1">Jenis Tagihan</h5>
            @if ($selectedJenjang && $selectedTahunAjar)
            <div class="flex-shrink-0">
                <div class="d-flex gap-2 flex-wrap">
                    <button data-bs-toggle="modal" data-bs-target="#ModalImportTagihan" wire:click.prevent="$emit('showImportTagihan', {{ $selectedJenjang }}, {{ $selectedTahunAjar }})"  class="btn btn-soft-info"><i class="ri-file-text-line me-1 align-bottom"></i> Import</button>
                    <button data-bs-toggle="modal" data-bs-target="#ModalAddJenisTagihan" wire:click.prevent="$emit('showCreateJenis', {{ $selectedJenjang }}, {{ $selectedTahunAjar }})" class="btn btn-primary"><i class="ri-play-list-add-line me-1 align-bottom"></i> Jenis Baru</button>
                </div>
            </div>
            @endif
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3 mb-3">
            <div class="col-xxl-2 col-sm-6"> 
                <select wire:model="selectedKategoriTagihan" style="cursor: pointer" class="form-select" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Kategori">
                    <option value="">Semua Kategori</option>
                    @foreach ($select_kategori as $item)    
                        <option value="{{ $item->ms_kategori_tagihan_siswa_id }}">
                            {{ $item->nama_kategori_tagihan_siswa }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-xxl-10 col-sm-6">
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
                            <th class="text-uppercase" width="50px">cicilan</th>
                            <th class="text-uppercase">tagihan</th>
                            <th class="text-uppercase">kategori</th>
                            <th class="text-uppercase">cicilan</th>
                            <th class="text-uppercase">jatuh tempo</th>
                            <th class="text-uppercase">aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jenis_tagihans as $item)
                        <tr>
                            <td>{{ $loop->iteration }}.</td>
                            <td>
                                <a href="#ModalDeleteJenisTagihan" data-bs-toggle="modal" class="btn btn-sm btn-soft-danger d-inline-flex align-items-center gap-1" wire:click.prevent="$emit('confirmDelete', {{ $item->ms_jenis_tagihan_siswa_id }})" data-bs-trigger="hover" data-bs-placement="top" title="Hapus Tagihan">
                                    <i class="ri-delete-bin-5-line"></i>
                                </a>
                            </td>
                            <td>
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
                            <td>
                                <div class="hstack gap-2">
                                    {{-- Tombol Edit Kategori --}}
                                    <button class="btn btn-sm btn-primary d-inline-flex align-items-center"
                                            data-bs-toggle="modal"
                                            data-bs-target="#ModalEditJenisTagihan"
                                            title="Edit Kategori"
                                            wire:click.prevent="$emit('loadDataJenisTagihan', {{ $item->ms_jenis_tagihan_siswa_id }})">
                                        <i class="ri-quill-pen-line align-bottom me-1"></i> Edit
                                    </button>
                                </div>
                            </td>                            
                        </tr>
                        @empty
                            <tr>
                                <td colspan="7">
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
                {{-- {{ $jenis_tagihans->links() }} --}}
            </div>
            @endif
        </div>
    </div>
</div>