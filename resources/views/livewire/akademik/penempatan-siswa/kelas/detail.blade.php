 <div wire:ignore.self class="modal fade" id="ModalDetailKelas" tabindex="-1" aria-labelledby="ModalDetailKelasLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-calendar-event-line">
                            </i>
                        </div>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">
                            Daftar Siswa
                        </h5>
                        <small>
                            Kelas {{ $namaKelasCurrent ?? 'Kelas' }}
                        </small>
                    </div>
                </div>
                <button type="button" class="btn btn-light btn-icon rounded-circle" data-bs-dismiss="modal">
                    <i class="ri-close-line fs-18">
                    </i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <!-- Pencarian dan Aksi -->
                        <div class="row g-3 mb-3">
                            <div class="col-lg-12">
                                <label class="form-label">Cari Siswa</label>
                                <div class="search-box">
                                    <input type="text" id="searchSiswaDetail" class="form-control" 
                                        wire:model.debounce.300ms="searchSiswaDetail"
                                        placeholder="Cari nama siswa...">
                                    <i class="ri-search-line search-icon"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Tabel Data Siswa -->
                        <div class="live-preview">
                            @if (!$selectedKelasDetail)
                                <div class="text-center py-4">
                                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                        colors="primary:#405189,secondary:#08a88a"
                                        style="width:75px;height:75px">
                                    </lord-icon>
                                    <h5 class="mt-2">Silakan Pilih Kelas</h5>
                                    <p class="text-muted mb-0">Untuk melihat data siswa, harap pilih kelas terlebih dahulu.</p>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover nowrap align-middle" style="width:100%">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="text-uppercase" width="30px">no</th>
                                                <th class="text-uppercase">Siswa</th>
                                                <th class="text-uppercase">Kelas</th>
                                                <th class="text-uppercase">Jenjang</th>
                                                <th class="text-uppercase">Tahun Ajar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($siswaDetailList as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        <span class="fw-medium">{{ $item->ms_siswa->nama_siswa }}</span>
                                                    </td>
                                                    <td>
                                                        {{ $item->ms_kelas->nama_kelas ?? 'N/A' }}
                                                    </td>
                                                    <td>
                                                        {{ $item->ms_jenjang->nama_jenjang ?? 'N/A' }}
                                                    </td>
                                                    <td>
                                                        {{ $item->ms_tahun_ajar->nama_tahun_ajar ?? 'N/A' }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3">
                                                        <div class="text-center py-4">
                                                            <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                                                colors="primary:#405189,secondary:#08a88a"
                                                                style="width:75px;height:75px">
                                                            </lord-icon>
                                                            <h6 class="mt-2 fw-semibold">Tidak Ada Data</h6>
                                                            <p class="text-muted small mb-0">Belum ada siswa dalam kelas ini.</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-0 px-4 pb-4 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>