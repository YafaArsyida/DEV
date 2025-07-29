<div>
    <div wire:ignore.self class="modal fade" id="detailEkstrakurikuler" tabindex="-1" aria-labelledby="detailEkstrakurikulerLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title">Detail Ekstrakurikuler</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
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
                                    <tr style="white-space: nowrap;">
                                        <th style="width: 50px;">NO</th>
                                        <th>Siswa</th>
                                        <th>Kelas</th>
                                        <th>Ekstrakurikuler</th>
                                        <th class="text-center">Total Biaya</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($siswa as $index => $item)
                                        <tr style="white-space: nowrap;">
                                            <td>{{ $loop->iteration }}.</td>
                                            <td>
                                                <span class="fw-medium">{{ $item->ms_siswa->nama_siswa ?? '-' }}</span>
                                                <p class="text-muted mb-0">{{ $item->ms_siswa->deskripsi ?? '' }}</p>
                                            </td>
                                            
                                            <td>{{ $item->ms_kelas->nama_kelas ?? '-' }}</td>
                                            
                                            <td>
                                                @forelse ($item->ms_siswa->ms_penempatan_ekstrakurikuler as $ekskul)
                                                    <span class="badge bg-info">
                                                        {{ $ekskul->ms_ekstrakurikuler->nama_ekstrakurikuler ?? '-' }}
                                                    </span>
                                                @empty
                                                    <span class="text-muted">-</span>
                                                @endforelse
                                            </td>
                                            
                                            <td class="text-center">
                                                <span class="fw-medium fs-14 text-success">
                                                    Rp{{ number_format($item->ms_siswa->total_biaya_ekstrakurikuler() ?? 0, 0, ',', '.') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">Belum ada siswa terdaftar.</td>
                                        </tr>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="modal-footer">
                    <a href="javascript:void(0);" class="btn btn-link link-success shadow-none fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Tutup</a>
                </div>
            </div>
        </div>
    </div>
</div>
