<div class="card">
    <div class="card-header border-0 pb-0">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1"> Siswa</h5>
            @if ($selectedJenjang && $selectedTahunAjar)
            <div class="flex-shrink-0">
                <div class="d-flex gap-2 flex-wrap">
                    @if ($siswaSelected)
                        <button href="#ModalDeleteSiswa" data-bs-toggle="modal" class="btn btn-soft-danger d-inline-flex align-items-center gap-1" wire:click.prevent="$emit('confirmBulkDelete', {{ json_encode($siswaSelected) }})">
                            <i class="ri-delete-bin-2-line me-1 align-bottom"></i> Hapus {{ count($siswaSelected) }}
                        </button>
                    @endif
                    @if ($selectedKelas)
                        <button data-bs-toggle="modal" data-bs-target="#ModalImportTelepon" wire:click.prevent="$emit('showImportTelepon', {{ $selectedKelas }}, {{ $selectedJenjang }}, {{ $selectedTahunAjar }})" class="btn btn-success"><i class="ri-whatsapp-line me-1 align-bottom"></i> Import Telepon</button>                        
                        <button data-bs-toggle="modal" data-bs-target="#ModalImportEduCard" wire:click.prevent="$emit('showImportEduCard', {{ $selectedKelas }}, {{ $selectedJenjang }}, {{ $selectedTahunAjar }})"  class="btn btn-warning"><i class="ri-bank-card-line me-1 align-bottom"></i> Import EduCard</button>                        
                    @endif

                    <button data-bs-toggle="modal" data-bs-target="#ModalImportSiswa" wire:click.prevent="$emit('showImportSiswa', {{ $selectedJenjang }}, {{ $selectedTahunAjar }})"  class="btn btn-secondary"><i class="ri-contacts-line me-1 align-bottom"></i> Import Siswa</button>
                    <button data-bs-toggle="modal" data-bs-target="#ModalAddSiswa" wire:click.prevent="$emit('showCreateSiswa', {{ $selectedJenjang }}, {{ $selectedTahunAjar }})" class="btn btn-primary"><i class="ri-play-list-add-line me-1 align-bottom"></i> Siswa Baru</button>
                    <button data-bs-toggle="modal" data-bs-target="#ModalExportSiswa" wire:click.prevent="showExportSiswa"  class="btn btn-soft-success"><i class="ri-file-excel-2-line me-1 align-bottom"></i> Export</button>
                </div>
            </div>
            @endif
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3 mb-3">
            <div class="col-xxl-2 col-sm-6"> 
                <select wire:model="selectedKelas" style="cursor: pointer" class="form-select" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Kelas">
                    <option value="">Semua Kelas</option>
                    @foreach ($select_kelas as $item)    
                    <option value="{{ $item->ms_kelas_id }}">{{ $item->nama_kelas }}</option>
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
            <div class="table-responsive">
            {{-- <div data-simplebar data-simplebar-auto-hide="false" style="max-height: 100vh" class="table-responsive"> --}}
                <table class="table table-hover nowrap align-middle" style="width:100%">
                    {{-- <div class="text-center my-3">
                        <h4 class="mb-0">Data Siswa Jenjang {{ $namaJenjang }}</h4>
                        <div>{{ $namaKelas }} Tahun Ajaran {{ $namaTahunAjar }}</div>
                    </div> --}}
                    <thead class="table-light">
                        <tr>
                            <th class="text-uppercase" width="50px">no</th>
                            <th scope="col" style="width: 50px;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="checkAll" wire:model="selectAll">
                                </div>
                            </th>
                            <th class="text-uppercase">siswa</th>
                            {{-- <th class="text-uppercase">L/P</th> --}}
                            <th class="text-uppercase">kelas</th>
                            {{-- <th class="text-uppercase">ekstrakurikuler</th> --}}
                            <th class="text-uppercase">whatsapp</th>
                            <th class="text-uppercase">EduCard</th>
                            <th class="text-uppercase">EduPay</th>
                            <th class="text-uppercase">aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @forelse ($siswas as $item) --}}
                        @forelse ($siswas as $key => $item)
                        <tr>
                            <td>{{ $siswas->firstItem() + $key }}.</td> 
                            {{-- <td>{{ $loop->iteration }}</td> --}}
                            <td scope="row">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:key="{{ $item->ms_penempatan_siswa_id }}" wire:model.live="siswaSelected" value="{{ $item->ms_penempatan_siswa_id }}">
                                </div>
                            </td>
                            <td>
                                <span class="fw-medium">
                                    {{ $item->ms_siswa->nama_siswa }}
                                </span>
                                <p class="text-muted mb-0">{{ $item->deskripsi }}</p>
                            </td>
                            <td>{{ $item->ms_kelas->nama_kelas }}</td>
                            {{-- <td>
                                @foreach ($item->ms_siswa->ms_penempatan_ekstrakurikuler as $ekskul)
                                    <span class="badge bg-info">
                                        {{ $ekskul->ms_ekstrakurikuler->nama_ekstrakurikuler ?? '-' }}
                                    </span>
                                @endforeach
                            </td> --}}
                            <td>
                                <span class="fw-medium text-success">    
                                    {{ $item->ms_siswa->telepon }}
                                </span>
                            </td>
                            <td>
                                @if($item->ms_siswa->ms_educard)
                                <span class="fw-medium text-warning">    
                                    {{ $item->ms_siswa->ms_educard->kode_kartu }}
                                </span>
                                @else
                                    <em>Belum memiliki kartu</em>
                                @endif
                            </td>
                            <td> <!-- Menampilkan saldo tabungan -->
                                <span class="fw-medium text-info">
                                    RP{{ number_format($item->ms_siswa->saldo_edupay_siswa(), 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                <div class="hstack gap-2">
                                    {{-- Tombol Detail Siswa --}}
                                    <button class="btn btn-sm btn-primary d-inline-flex align-items-center"
                                            data-bs-toggle="modal"
                                            data-bs-target="#ModalDetailSiswa"
                                            title="Detail Siswa"
                                            wire:click.prevent="$emit('showDetailSiswa', {{ $item->ms_penempatan_siswa_id }})">
                                        <i class="ri-eye-line align-bottom me-1"></i> Detail
                                    </button>
                            
                                    {{-- Tombol Edit Siswa --}}
                                    <button class="btn btn-sm btn-info d-inline-flex align-items-center"
                                            data-bs-toggle="modal"
                                            data-bs-target="#ModalEditSiswa"
                                            title="Edit Siswa"
                                            wire:click.prevent="$emit('loadDataSiswa', {{ $item->ms_penempatan_siswa_id }})">
                                        <i class="ri-quill-pen-line align-bottom me-1"></i> Edit
                                    </button>
                            
                                    {{-- Tombol Hapus Siswa --}}
                                    <button class="btn btn-sm btn-soft-danger d-inline-flex align-items-center"
                                            data-bs-toggle="modal"
                                            data-bs-target="#ModalDeleteSiswa"
                                            title="Hapus Siswa"
                                            wire:click.prevent="$emit('confirmDeleteSiswa', {{ $item->ms_penempatan_siswa_id }})">
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
                {{ $siswas->links() }}
            </div>
            @endif
        </div>
        {{-- DATA --}}
    </div>
</div>