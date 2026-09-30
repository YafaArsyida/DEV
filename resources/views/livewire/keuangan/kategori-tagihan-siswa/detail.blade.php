<div wire:ignore.self class="modal fade" id="modalDetailKategoriTagihan" tabindex="-1" aria-labelledby="modalDetailKategoriTagihanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-bottom px-4 py-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-18">
                            <i class="ri-funds-line"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1" id="modalDetailKategoriTagihanLabel">Financial Overview Kategori</h5>
                        <small class="text-muted">{{ $kategori->nama_kategori_tagihan_siswa ?? 'Kategori Tagihan' }} · {{ $kategori->ms_tahun_ajar->nama_tahun_ajar ?? '-' }}</small>
                    </div>
                </div>
                <button type="button" class="btn btn-light btn-icon rounded-circle" data-bs-dismiss="modal" aria-label="Tutup">
                    <i class="ri-close-line fs-18"></i>
                </button>
            </div>

            <div class="modal-body p-4">
                @if (!$selectedKategori)
                    <div class="text-center py-5 text-muted">Pilih kategori untuk melihat ringkasan tagihan.</div>
                @elseif (!$kategori)
                    <div class="text-center py-5 text-muted">Data kategori tidak ditemukan.</div>
                @else
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="avatar-lg flex-shrink-0">
                            <div class="avatar-title bg-primary-subtle text-primary rounded-3 fs-2">
                                <i class="ri-price-tag-3-line"></i>
                            </div>
                        </div>
                        <div class="min-w-0">
                            <h5 class="fw-bold mb-1 text-truncate">{{ $kategori->nama_kategori_tagihan_siswa }}</h5>
                            <div class="text-muted small">
                                {{ $kategori->ms_jenjang->nama_jenjang ?? '-' }}
                                <span class="mx-1">&bull;</span>
                                {{ $kategori->ms_tahun_ajar->nama_tahun_ajar ?? '-' }}
                            </div>
                            @if ($kategori->deskripsi)
                                <div class="text-muted small">{{ $kategori->deskripsi }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="card border-0 bg-primary-subtle h-100 mb-0">
                                <div class="card-body">
                                    <div class="text-primary small fw-medium mb-2">Total Tagihan</div>
                                    <div class="fs-5 fw-bold">Rp{{ number_format($totalTagihan, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 bg-success-subtle h-100 mb-0">
                                <div class="card-body">
                                    <div class="text-success small fw-medium mb-2">Sudah Dibayar</div>
                                    <div class="fs-5 fw-bold">Rp{{ number_format($totalDibayarkan, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 bg-danger-subtle h-100 mb-0">
                                <div class="card-body">
                                    <div class="text-danger small fw-medium mb-2">Sisa Tagihan</div>
                                    <div class="fs-5 fw-bold">Rp{{ number_format(max(0, $totalKekurangan), 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-medium">Progress Pembayaran</span>
                            <span class="fw-semibold">{{ $persentaseLunas }}%</span>
                        </div>
                        <div class="progress" role="progressbar" aria-label="Progress pembayaran kategori {{ $kategori->nama_kategori_tagihan_siswa }}"
                            aria-valuenow="{{ min(max($persentaseLunas, 0), 100) }}" aria-valuemin="0" aria-valuemax="100" style="height: 10px">
                            <div class="progress-bar bg-success" style="width: {{ min(max($persentaseLunas, 0), 100) }}%"></div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <section aria-labelledby="ringkasanTagihanKategoriHeading">
                        <h6 id="ringkasanTagihanKategoriHeading" class="fw-semibold mb-3">Ringkasan Tagihan</h6>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Jenis Tagihan</th>
                                        <th class="text-end">Tagihan</th>
                                        <th class="text-end">Dibayarkan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($jenisTagihan as $jenis)
                                        <tr>
                                            <td class="fw-medium">{{ $jenis->nama_jenis_tagihan_siswa }}</td>
                                            <td class="text-end text-nowrap">Rp{{ number_format($jenis->total_tagihan, 0, ',', '.') }}</td>
                                            <td class="text-end text-success text-nowrap">Rp{{ number_format($jenis->total_dibayarkan, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-4">Belum ada tagihan pada kategori ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </section>
                @endif
            </div>

            <div class="modal-footer border-0 px-4 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div><div>
    {{-- Close your eyes. Count to one. That is how long forever feels. --}}
</div>
