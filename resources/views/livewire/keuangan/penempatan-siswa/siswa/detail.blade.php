<div>
    <div wire:ignore.self class="modal fade" id="ModalDetailSiswa" tabindex="-1" aria-labelledby="ModalDetailSiswaLabel" aria-hidden="true">
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
                            <h5 class="fw-bold mb-1" id="modalDetailKelasLabel">Financial Overview Siswa</h5>
                            <small class="text-muted">{{ $penempatanSiswa->ms_siswa->nama_siswa ?? 'Siswa' }} · {{ $penempatanSiswa->ms_kelas->nama_kelas ?? '-' }}</small>
                        </div>
                    </div>
                    <button type="button" class="btn btn-light btn-icon rounded-circle" data-bs-dismiss="modal" aria-label="Tutup">
                        <i class="ri-close-line fs-18"></i>
                    </button>
                </div>

                <div class="modal-body p-4">
                    @if (!$selectedPenempatanSiswaId)
                        <div class="text-center py-5 text-muted">Pilih siswa untuk melihat ringkasan keuangan.</div>
                    @elseif (!$penempatanSiswa)
                        <div class="text-center py-5 text-muted">Data siswa tidak ditemukan.</div>
                    @else
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="avatar-lg flex-shrink-0">
                                <div class="avatar-title bg-primary-subtle text-primary rounded-3 fs-2">
                                    <i class="ri-user-3-line"></i>
                                </div>
                            </div>
                            <div class="min-w-0">
                                <h5 class="fw-bold mb-1 text-truncate">{{ $penempatanSiswa->ms_siswa->nama_siswa ?? 'Siswa' }}</h5>
                                <div class="text-muted small">NISN: {{ $penempatanSiswa->ms_siswa->nisn ?? '-' }}</div>
                                <div class="text-muted small">
                                    {{ $penempatanSiswa->ms_kelas->nama_kelas ?? '-' }}
                                    <span class="mx-1">&bull;</span>
                                    {{ $penempatanSiswa->ms_tahun_ajar->nama_tahun_ajar ?? '-' }}
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-sm-4">
                                <div class="card border-0 bg-primary-subtle h-100 mb-0">
                                    <div class="card-body">
                                        <div class="text-primary small fw-medium mb-2">Total Tagihan</div>
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
                                        <div class="fs-5 fw-bold">Rp{{ number_format($sisaTagihan, 0, ',', '.') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-medium">Progress Pembayaran</span>
                                <span class="fw-semibold">{{ $persentaseLunas }}%</span>
                            </div>
                            <div class="progress" role="progressbar"
                                aria-label="Progress pembayaran {{ $penempatanSiswa->ms_siswa->nama_siswa ?? 'siswa' }}"
                                aria-valuenow="{{ $progressLunas }}" aria-valuemin="0" aria-valuemax="100" style="height: 10px">
                                <div class="progress-bar bg-success" style="width: {{ $progressLunas }}%"></div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <section aria-labelledby="ringkasanTagihanSiswaLabel">
                            <h6 class="fw-semibold mb-3" id="ringkasanTagihanSiswaLabel">Ringkasan Tagihan</h6>
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
                                        @forelse ($ringkasanJenisTagihan as $tagihan)
                                            <tr>
                                                <td class="fw-medium">{{ $tagihan['nama_tagihan'] }}</td>
                                                <td class="text-end">Rp{{ number_format($tagihan['total_tagihan'], 0, ',', '.') }}</td>
                                                <td class="text-end text-success">Rp{{ number_format($tagihan['total_bayar'], 0, ',', '.') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted py-4">Belum ada tagihan untuk siswa ini.</td>
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
    </div>
</div>
