<div class="card">
    <div class="card-header border-0 pb-0">
        <div class="d-flex align-items-center">
            <div class="flex-grow-1">
                <h5 class="card-title mb-0">Laporan Presensi Pegawai</h5>
            </div>
            <div class="flex-shrink-0">
                <div class="d-flex gap-2 flex-wrap">
                    <button wire:click="cetakLaporan" class="btn btn-danger d-inline-flex align-items-center gap-1">
                        <i class="ri-printer-line align-bottom"></i>
                        <span>Cetak Laporan</span>
                    </button>
                    <button data-bs-toggle="modal" data-bs-target="#ExportLaporanExcel" class="btn btn-soft-success"><i
                            class="ri-file-excel-2-line pb-0"></i> Export</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="row g-3 align-items-end mb-3">
            <!-- Dropdown Kelas -->
            <div class="col-xxl-2 col-sm-6">
                <label for="selectJabatan" class="form-label">Jabatan</label>
                <select id="selectJabatan" wire:model="selectedJabatan" class="form-select">
                    <option value="">Semua Jabatan</option>
                    @foreach($jabatan as $j)
                    <option value="{{ $j->ms_jabatan_id }}">{{ $j->nama_jabatan }}</option>
                    @endforeach
                </select>
            </div>
            <!-- Input Pencarian -->
            <div class="col-xxl-6 col-sm-6">
                <label for="searchInput" class="form-label">Pencarian</label>
                <div class="position-relative">
                    <input type="text" id="searchInput" class="form-control ps-4" wire:model.debounce.300ms="search"
                        placeholder="Cari nama, deskripsi, atau lainnya...">
                    <i class="ri-search-line position-absolute top-50 start-0 translate-middle-y ms-2 text-muted"></i>
                </div>
            </div>
            <!-- Filter Periode -->
            <div class="col-xxl-4">
                <label class="form-label fw-semibold">Periode</label>
                <div class="d-flex align-items-center gap-2">
                    <input type="date" id="startDate" class="form-control" wire:model="startDate" value="">
                    <span class="text-muted">–</span>
                    <input type="date" id="endDate" class="form-control" wire:model="endDate" value="">
                    <div class="col-auto">
                        <button type="button" class="btn btn-soft-secondary btn-icon rounded-circle" wire:click="resetTanggal"
                            title="Reset Tanggal">
                            <i class="ri-refresh-line fs-16"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="live-preview">
            <div class="table-responsive">
                <table class="table table-bordered align-middle table-striped">
                    <thead class="table-light">
                        <tr>
                            <th width="50px">No</th>
                            <th>Tanggal</th>
                            <th>Pegawai</th>
                            <th>Jabatan</th>
                            <th>Jam Masuk</th>
                            <th>Status Masuk</th>
                            <th>Jam Pulang</th>
                            <th>Status Pulang</th>
                            <th>Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($presensi as $index => $row)
                        <tr>
                            <td>{{ ($presensi->firstItem() + $index) }}</td>
                            <td class="text-uppercase text-start">
                                {{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($row->tanggal, 'd F Y') }}
                            </td>
                            <td class="text-start" style="white-space: nowrap;">
                                {{ $row->ms_pegawai->nama_pegawai ?? '-' }}
                                <p class="fs-12 mb-0 text-muted">{{ $row->kode_kartu ?? ''}}</p>
                            </td>
                            <td>{{ $row->ms_pegawai->ms_jabatan->nama_jabatan }}</td>
                
                            <td>{{ $row->jam_masuk ?? '-' }}</td>
                            <td>
                                <span class="badge bg-success">{{ $row->status_masuk }}</span>
                            </td>
                    
                            <td>{{ $row->jam_pulang ?? '-' }}</td>
                            <td>
                                <span class="badge bg-success">{{ $row->status_pulang }}</span>
                            </td>
                    
                            <td>{{ $row->deskripsi ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9">
                                <div class="noresult text-center py-3">
                                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#405189,secondary:#08a88a"
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
                {{ $presensi->links() }}
            </div>
        </div>

    </div>
</div>