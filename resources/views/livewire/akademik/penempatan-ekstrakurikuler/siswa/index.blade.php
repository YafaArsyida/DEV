<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">
            {{-- TITLE --}}
            <div>
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-team-line text-primary me-1"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1">
                            Data Siswa Ekstrakurikuler
                        </h5>
                        <small class="text-muted">
                            Daftar siswa yang mengikuti kegiatan ekstrakurikuler.
                        </small>
                    </div>
                </div>
            </div>

            {{-- ACTION --}}
            <div class="d-flex gap-2 flex-wrap">
                <button
                    wire:click="cetakSiswaEkstrakurikuler"
                    class="btn btn-danger rounded-pill d-inline-flex align-items-center gap-2">
                    <i class="ri-printer-line"></i>
                    <span>Cetak</span>
                </button>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="row g-3 align-items-end mb-3">
        
            <!-- Filter Kelas -->
            <div class="col-xxl-3 col-sm-6">
                <label for="filterKelas" class="form-label small text-muted text-uppercase fw-medium mb-2">Kelas</label>
                <select id="filterKelas" wire:model="selectedKelas" style="cursor: pointer" class="form-select"
                    data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Kelas">
                    <option value="">Semua Kelas</option>
                    @foreach ($select_kelas as $item)
                    <option value="{{ $item->ms_kelas_id }}">{{ $item->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
        
            <!-- Pencarian -->
            <div class="col-xxl-9 col-sm-6">
                <label for="searchData" class="form-label small text-muted text-uppercase fw-medium mb-2">Pencarian</label>
                <div class="search-box">
                    <input type="text" id="searchData" class="form-control search" wire:model.debounce.300ms="search"
                        placeholder="Cari nama, kelas, atau deskripsi...">
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
                <table id="DataIndexSiswa" class="table table-hover table-nowrap align-middle" style="width:100%">
                    <thead class="table-light">
                        <tr class="text-uppercase">
                            <th class="text-center" width="50px">no</th>
                            <th>siswa</th>
                            <th>kelas</th>
                            <th>ekstrakurikuler</th>
                            <th class="text-center">biaya</th>
                            <th class="text-center">aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @forelse ($siswas as $item) --}}
                        @forelse ($siswas as $key => $item)
                        <tr>
                            <td class="text-center">{{ $siswas->firstItem() + $key }}</td> 
                            <td>
                                <span class="fw-medium">
                                    {{ $item->ms_siswa->nama_siswa }}
                                </span>
                                {{-- <p class="text-muted mb-0">{{ $item->deskripsi }}</p> --}}
                            </td>
                            <td>{{ $item->ms_kelas->nama_kelas }}</td>
                            <td>
                                @if ($item->ms_penempatan_ekstrakurikuler)
                                    <span class="fw-semibold">
                                        <i class="ri-trophy-line me-1"></i>
                                        {{ $item->ms_penempatan_ekstrakurikuler->ms_ekstrakurikuler->nama_ekstrakurikuler ?? '-' }}
                                    </span>
                                @else
                                    <em>Belum memilih</em>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($item->ms_penempatan_ekstrakurikuler)
                                <span class="fw-medium fs-12">    
                                    Rp{{ number_format($item->ms_penempatan_ekstrakurikuler->ms_ekstrakurikuler->biaya, 0, ',', '.') }}
                                </span>
                                @else
                                    <em>Belum memilih</em>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    {{-- Detail --}}
                                    <button
                                        class="btn btn-soft-primary btn-sm rounded-pill px-3"
                                        data-bs-toggle="modal"
                                        data-bs-target="#detailSiswaEkstrakurikuler"
                                        title="Detail Siswa"
                                        wire:click.prevent="$emit('detailSiswaEkstrakurikuler', {{ $item->ms_penempatan_siswa_id }})">
                                        <i class="ri-eye-line me-1"></i>
                                        Detail
                                    </button>

                                    {{-- Kelola Ekstrakurikuler --}}
                                    <button
                                        class="btn btn-primary btn-sm rounded-pill px-3"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editSiswaEkstrakurikuler"
                                        title="Kelola Ekstrakurikuler"
                                        wire:click.prevent="$emit('editSiswaEkstrakurikuler', {{ $item->ms_penempatan_siswa_id }})">
                                        <i class="ri-trophy-line me-1"></i>
                                        Ekstrakurikuler
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
                {{-- PAGINATION --}}
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="text-muted fs-13">
                            Menampilkan
                            <span class="fw-semibold">
                                {{ $siswas->firstItem() ?? 0 }}
                            </span>
                            -
                            <span class="fw-semibold">
                                {{ $siswas->lastItem() ?? 0 }}
                            </span>
                            dari
                            <span class="fw-semibold">
                                {{ $siswas->total() }}
                            </span>
                            data siswa
                        </div>
                        <div>
                            {{ $siswas->links() }}
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        {{-- DATA --}}
    </div>
</div>