<div class="card">
    <div class="card-header border-0 pb-0">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1"> Ekstrakurikuler</h5>
            @if ($selectedJenjang)
            <div class="flex-shrink-0">
                <div class="d-flex gap-2 flex-wrap">
                    <button data-bs-toggle="modal" id="create-btn" data-bs-target="#ModalAddEkstrakurikuler" wire:click.prevent="$emit('createEkstrakurikuler', {{ $selectedJenjang }})" class="btn btn-primary"><i class="ri-play-list-add-line me-1 align-bottom"></i> Ekstrakurikuler Baru</button>
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
            @if (!$selectedJenjang)
                <div class="text-center py-4">
                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                        colors="primary:#405189,secondary:#08a88a"
                        style="width:75px;height:75px">
                    </lord-icon>
                    <h5 class="mt-2">Silakan Pilih Jenjang</h5>
                    <p class="text-muted mb-0">Untuk melihat data ekstrakurikuler, harap pilih Jenjang terlebih dahulu.</p>
                </div>
            @else
                <!-- Tabel Data Ekstrakurikuler -->
                <div class="table-responsive">
                    <table class="table table-hover nowrap align-middle" style="width:100%">
                    {{-- <div class="text-center my-3">
                        <h4 class="mb-0">Data Ekstrakurikuler Jenjang {{ $namaJenjang }}</h4>
                        <div>Tahun Ajaran {{ $namaTahunAjar }}</div>
                    </div> --}}
                        <thead class="table-light">
                            <tr>
                                <th class="text-uppercase">hapus</th>
                                <th class="text-uppercase" width="50px">no</th>
                                <th class="text-uppercase">ekstrakurikuler</th>
                                <th class="text-uppercase">biaya</th>
                                <th class="text-uppercase">kuota</th>
                                <th class="text-uppercase">terisi</th>
                                <th class="text-uppercase">tersedia</th>
                                <th class="text-uppercase">aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $item)
                                <tr>
                                    <td>
                                        <a href="#deleteEkstrakurikuler" data-bs-toggle="modal" class="btn btn-sm btn-soft-danger d-inline-flex align-items-center gap-1" wire:click.prevent="$emit('confirmDelete', {{ $item->ms_ekstrakurikuler_id }})" data-bs-trigger="hover" data-bs-placement="top" title="Hapus">
                                            <i class="ri-delete-bin-5-line"></i>
                                        </a>
                                    </td>
                                    <td>{{ $loop->iteration }}.</td>
                                    <td>
                                        <span class="fw-medium" style="white-space: nowrap;">
                                            {{ $item->nama_ekstrakurikuler }}
                                        </span>
                                        <p class="text-muted mb-0">{{ $item->deskripsi }}</p>
                                    </td>
                                    <td class="" style="white-space: nowrap;">
                                        <span class="fw-medium fs-14 text-success">
                                            RP{{ number_format($item->biaya, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td style="white-space: nowrap;">{{ $item->kuota ?? '0' }} siswa</td>
                                    <td style="white-space: nowrap;">{{ $item->total_penempatan_siswa() ?? '0' }} siswa</td>
                                    <td style="white-space: nowrap;">{{ $item->kuota_tersedia() ?? '0' }} siswa</td>
                                    <td style="white-space: nowrap;">
                                        <div class="hstack gap-2">
                                            {{-- Tombol Edit Ekstrakurikuler --}}
                                            <button class="btn btn-sm btn-primary d-inline-flex align-items-center"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editEkstrakurikuler"
                                                    title="Edit Ekstrakurikuler"
                                                    wire:click.prevent="$emit('loadEkstrakurikuler', {{ $item->ms_ekstrakurikuler_id }})">
                                                <i class="ri-quill-pen-line align-bottom me-1"></i> Edit
                                            </button>
                                            {{-- Tombol Detail Tagihan --}}
                                            <button class="btn btn-sm btn-secondary d-inline-flex align-items-center"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#detailEkstrakurikuler"
                                                    title="Detail Ekstrakurikuler"
                                                    wire:click.prevent="$emit('detailEkstrakurikuler', {{ $item->ms_ekstrakurikuler_id }})">
                                                <i class="ri-eye-line align-bottom me-1"></i> Detail
                                            </button>
                                           {{-- cetak PDF --}}
                                            <button class="btn btn-sm btn-danger d-inline-flex align-items-center"
                                                    title="Cetak Ekstrakurikuler"
                                                    wire:click.prevent="$emit('cetakEkstrakurikuler', {{ $item->ms_ekstrakurikuler_id }})">
                                                <i class="ri-printer-line align-bottom me-1"></i> Cetak PDF
                                            </button>
                                        </div>
                                    </td>                                    
                                </tr>
                            @empty
                                <!-- Jika Tidak Ada Data Ekstrakurikuler -->
                                <tr>
                                    <td colspan="8">
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
                </div>
            @endif
        </div>
        {{-- DATA --}}
    </div>
</div>