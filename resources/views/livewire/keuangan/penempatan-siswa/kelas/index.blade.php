<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    {{-- HEADER --}}
    <div class="card-header">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">
            {{-- TITLE --}}
            <div>
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-community-line"></i>
                        </div>
                    </div>
    
                    <div>
                        <h5 class="fw-bold mb-1">
                            Data Kelas
                        </h5>
                        <small>
                            Kelola informasi tagihan siswa pada kelas
                        </small>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="card-body">
        <div class="row g-3 mb-3">
            <div class="col-xxl-12 col-sm-12">
                <label for="searchData" class="form-label small text-muted text-uppercase fw-medium mb-2">Pencarian</label>
                <div class="search-box">
                    <input type="text" id="searchData" class="form-control search" wire:model.debounce.300ms="search"
                        placeholder="Cari nama, deskripsi, atau lainnya...">
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
                    <!-- Tabel Data Kelas -->
                    <table class="table table-hover table-nowrap align-middle" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th class="text-uppercase" width="30px">no</th>
                                <th class="text-uppercase">kelas</th>
                                <th class="text-uppercase">siswa</th>
                                <th class="text-uppercase text-center">Lunas</th>
                                {{-- <th class="text-uppercase text-end">Lunas</th> --}}
                                <th class="text-uppercase text-center">aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kelass as $item)
                            @php
                                $totalTagihan = $item->ms_tagihan_siswa_sum_jumlah_tagihan_siswa ?? 0;
                                $totalDibayarkan = $item->total_dibayarkan ?? 0;

                                $persentaseLunas = $totalTagihan > 0
                                    ? round(($totalDibayarkan / $totalTagihan) * 100, 1)
                                    : 0;
                                $progressLunas = min(max($persentaseLunas, 0), 100);
                            @endphp
                                <tr>

                                    {{-- NO --}}
                                    <td>
                                        {{ ($kelass->firstItem() ?? 0) + $loop->index }}.
                                    </td>

                                    {{-- KELAS --}}
                                    <td>
                                        <span class="fw-medium">
                                            {{ $item->nama_kelas }}
                                        </span>

                                        @if ($item->deskripsi)
                                            <p class="text-muted mb-0">
                                                {{ $item->deskripsi }}
                                            </p>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $item->ms_penempatan_siswa_count }} siswa
                                    </td>

                                    <td>
                                        <div class="d-flex flex-column align-items-center gap-1 mx-4" style="min-width: 60px">
                                            <span class="fw-semibold">{{ $persentaseLunas }}%</span>
                                            <div class="progress w-100" role="progressbar"
                                                aria-label="Persentase pelunasan {{ $item->nama_kelas }}"
                                                aria-valuenow="{{ $progressLunas }}" aria-valuemin="0" aria-valuemax="100"
                                                style="height: 6px">
                                                <div class="progress-bar bg-success" style="width: {{ $progressLunas }}%"></div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- AKSI --}}
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            {{-- DETAIL --}}
                                            <a href="#modalDetailKelas"
                                                data-bs-toggle="modal"
                                                class="btn btn-soft-primary btn-sm rounded-pill px-3"
                                                title="Detail Kelas"
                                                wire:click.prevent="$emit('loadDetailKelas',{{ $item->ms_kelas_id }})">
                                                <i class="ri-eye-line me-1"></i>
                                                Detail
                                            </a>
                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <!-- Jika Tidak Ada Data Kelas -->
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
                    {{-- PAGINATION --}}
                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="text-muted fs-13">
                                Menampilkan
                                <span class="fw-semibold">
                                    {{ $kelass->firstItem() ?? 0 }}
                                </span>
                                -
                                <span class="fw-semibold">
                                    {{ $kelass->lastItem() ?? 0 }}
                                </span>
                                dari
                                <span class="fw-semibold">
                                    {{ $kelass->total() }}
                                </span>
                                data kelas
                            </div>
                            <div>
                                {{ $kelass->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        {{-- DATA --}}
    </div>
</div>