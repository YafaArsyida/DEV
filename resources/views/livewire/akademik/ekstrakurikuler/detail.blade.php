<div wire:ignore.self class="offcanvas offcanvas-top bg-light" id="detailEkstrakurikuler" tabindex="-1" aria-labelledby="detailEkstrakurikulerLabel" style="min-height:100vh;">
    <div class="offcanvas-header border-bottom px-4 py-3 shadow-sm">
        <div class="d-flex justify-content-between align-items-start w-100">
            <!-- Kiri -->
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-sm">
                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-18">
                        <i class="ri-trophy-line"></i>
                    </div>
                </div>

                <div>
                    <h5 class="fw-bold mb-1">
                        Data Ekstrakurikuler
                    </h5>
                    {{-- <small class="text-muted">
                        deskripsi ekstra
                    </small> --}}
                </div>
            </div>
            <!-- Kanan -->
            <button type="button"
                class="btn btn-light btn-icon rounded-circle shadow-none"
                data-bs-dismiss="offcanvas">
                <i class="ri-close-line fs-18"></i>
            </button>
        </div>
    </div>
    <div class="offcanvas-body">
        <div class="row g-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header">
                        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">
                            {{-- TITLE --}}
                            <div>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-success-subtle text-success rounded-circle fs-20">
                                            <i class="ri-team-line"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <h5 class="fw-bold mb-1">
                                            Detail Ekstrakurikuler Siswa
                                        </h5>
                                        <small class="text-muted">
                                            Tampilkan siswa dari daftar berdasarkan kategori dan pencarian.
                                        </small>
                                    </div>
                                </div>
                            </div>

                            {{-- ACTION --}}
                            <div class="d-flex gap-2 flex-wrap">

                                <button
                                    wire:click="cetakEkstrakurikuler"
                                    type="button"
                                    class="btn btn-danger rounded-pill px-4 d-inline-flex align-items-center gap-1"
                                    title="Cetak Laporan PDF">
                                    <i class="ri-printer-line"></i>
                                    <span>Cetak</span>
                                </button>

                                {{-- <button data-bs-toggle="modal" data-bs-target="#ModalDetailEkstrakurikuler" class="btn rounded-pill px-4 btn-success"><i class="ri-file-excel-2-line pb-0"></i> Export</button> --}}
                            </div>

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
                        <div class="live-preview">
                            <!-- Jika Jenjang atau Tahun Ajar belum dipilih -->
                            @if (!$selectedJenjang)
                            <div class="text-center py-4">
                                <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                    colors="primary:#405189,secondary:#08a88a"
                                    style="width:75px;height:75px">
                                </lord-icon>
                                <h5 class="mt-2">Silakan Pilih Jenjang</h5>
                                <p class="text-muted mb-0">Untuk melihat data, harap pilih Jenjang terlebih dahulu.</p>
                            </div>
                            @else
                            <div class="table-responsive">
                                <table class="table table-hover nowrap align-middle" style="width:100%">
                                    <thead class="table-light">
                                        <tr class="text-uppercase">
                                            <th style="width: 50px;">NO</th>
                                            <th>Siswa</th>
                                            <th>Kelas</th>
                                            <th>Ekstrakurikuler</th>
                                            <th>Biaya</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($siswa as $key => $item)
                                            <tr>
                                                <td>{{ $siswa->firstItem() + $key }}.</td>
                                                <td>
                                                    <span class="fw-medium">
                                                        {{ $item->ms_siswa->nama_siswa }}
                                                    </span>
                                                    <p class="text-muted mb-0">{{ $item->deskripsi }}</p>
                                                </td>
                                                <td>{{ $item->ms_kelas->nama_kelas ?? '-' }}</td>
                                                <td>
                                                    @if ($item->ms_penempatan_ekstrakurikuler)
                                                        <span class="">
                                                            <i class="ri-trophy-line me-1"></i>
                                                            {{ $item->ms_penempatan_ekstrakurikuler->ms_ekstrakurikuler->nama_ekstrakurikuler ?? '-' }}
                                                        </span>
                                                    @else
                                                        <em>Belum memilih</em>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($item->ms_penempatan_ekstrakurikuler)
                                                        <span class="fs-12 fw-semibold">
                                                            Rp{{ number_format($item->ms_penempatan_ekstrakurikuler->ms_ekstrakurikuler->biaya ?? 0, 0, ',', '.') }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5">
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
                                                {{ $siswa->firstItem() ?? 0 }}
                                            </span>
                                            -
                                            <span class="fw-semibold">
                                                {{ $siswa->lastItem() ?? 0 }}
                                            </span>
                                            dari
                                            <span class="fw-semibold">
                                                {{ $siswa->total() }}
                                            </span>
                                            data siswa
                                        </div>
                                        <div>
                                            {{ $siswa->links() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="d-flex flex-column flex-sm-row gap-2 justify-content-end">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="offcanvas">
                        <i class="ri-close-line me-1"></i>
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>