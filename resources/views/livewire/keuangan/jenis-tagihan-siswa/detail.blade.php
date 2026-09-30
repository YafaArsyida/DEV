<div>
    <div wire:ignore.self class="modal fade" id="modalDetailJenisTagihan" tabindex="-1" aria-labelledby="modalDetailJenisTagihanLabel" aria-hidden="true">
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
                            <h5 class="fw-bold mb-1" id="modalDetailJenisTagihanLabel">Financial Overview Jenis Tagihan</h5>
                            <small class="text-muted">{{ $jenisTagihan->nama_jenis_tagihan_siswa ?? 'Jenis Tagihan' }} · {{ $jenisTagihan->ms_kategori_tagihan_siswa->nama_kategori_tagihan_siswa ?? '-' }}</small>
                        </div>
                    </div>
                    <button type="button" class="btn btn-light btn-icon rounded-circle" data-bs-dismiss="modal" aria-label="Tutup">
                        <i class="ri-close-line fs-18"></i>
                    </button>
                </div>

                <div class="modal-body p-4">
                    @if (!$selectedJenisTagihan)
                        <div class="text-center py-5 text-muted">Pilih jenis tagihan untuk melihat detail.</div>
                    @elseif (!$jenisTagihan)
                        <div class="text-center py-5 text-muted">Data jenis tagihan tidak ditemukan.</div>
                    @else
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="avatar-lg flex-shrink-0">
                                <div class="avatar-title bg-primary-subtle text-primary rounded-3 fs-2">
                                    <i class="ri-file-list-3-line"></i>
                                </div>
                            </div>
                            <div class="min-w-0">
                                <h5 class="fw-bold mb-1 text-truncate">{{ $jenisTagihan->nama_jenis_tagihan_siswa }}</h5>
                                <div class="text-muted small">
                                    Kategori: {{ $jenisTagihan->ms_kategori_tagihan_siswa->nama_kategori_tagihan_siswa ?? '-' }}
                                </div>
                                <div class="text-muted small">
                                    {{ $jenisTagihan->ms_jenjang->nama_jenjang ?? '-' }}
                                    <span class="mx-1">&bull;</span>
                                    {{ $jenisTagihan->ms_tahun_ajar->nama_tahun_ajar ?? '-' }}
                                </div>
                                @if ($jenisTagihan->deskripsi)
                                    <div class="text-muted small">{{ $jenisTagihan->deskripsi }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-sm-4">
                                <div class="card border-0 bg-primary-subtle h-100 mb-0">
                                    <div class="card-body">
                                        <div class="text-primary small fw-medium mb-2">Estimasi Tagihan</div>
                                        <div class="fs-5 fw-bold">Rp{{ number_format($totalTagihan, 0, ',', '.') }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="card border-0 bg-success-subtle h-100 mb-0">
                                    <div class="card-body">
                                        <div class="text-success small fw-medium mb-2">Sudah Dibayar</div>
                                        <div class="fs-5 fw-bold">Rp{{ number_format($totalDibayarkan, 0, ',', '.') }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
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
                            <div class="progress" role="progressbar" aria-label="Progress pembayaran {{ $jenisTagihan->nama_jenis_tagihan_siswa }}"
                                aria-valuenow="{{ min(max($persentaseLunas, 0), 100) }}" aria-valuemin="0" aria-valuemax="100" style="height: 10px">
                                <div class="progress-bar bg-success" style="width: {{ min(max($persentaseLunas, 0), 100) }}%"></div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <section aria-labelledby="ringkasanTagihanJenisHeading">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-semibold mb-0" id="ringkasanTagihanJenisHeading">Ringkasan Tagihan</h6>
                                <span class="badge bg-primary-subtle text-primary">{{ $jumlahItemTagihan }} item</span>
                            </div>
                            <div class="row g-3">
                                <div class="col-6 col-md-3">
                                    <div class="border rounded-3 p-3 h-100">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <i class="ri-file-list-3-line text-primary fs-18"></i>
                                            <span class="text-muted small">Total Item</span>
                                        </div>
                                        <div class="fs-4 fw-bold">{{ $jumlahItemTagihan }}</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="border rounded-3 p-3 h-100">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <i class="ri-error-warning-line text-danger fs-18"></i>
                                            <span class="text-muted small">Belum Dibayar</span>
                                        </div>
                                        <div class="fs-4 fw-bold">{{ $jumlahBelumBayar }}</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="border rounded-3 p-3 h-100">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <i class="ri-time-line text-warning fs-18"></i>
                                            <span class="text-muted small">Masih Dicicil</span>
                                        </div>
                                        <div class="fs-4 fw-bold">{{ $jumlahSebagian }}</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="border rounded-3 p-3 h-100">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <i class="ri-checkbox-circle-line text-success fs-18"></i>
                                            <span class="text-muted small">Lunas</span>
                                        </div>
                                        <div class="fs-4 fw-bold">{{ $jumlahLunas }}</div>
                                    </div>
                                </div>
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
    </div>
</div>
