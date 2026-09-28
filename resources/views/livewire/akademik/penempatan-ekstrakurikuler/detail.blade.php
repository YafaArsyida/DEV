<div wire:ignore.self class="modal fade" id="detailSiswaEkstrakurikuler" tabindex="-1"
    aria-labelledby="detailSiswaEkstrakurikulerLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            {{-- HEADER --}}
            <div class="modal-header border-0 pb-0 p-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-user-star-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1">
                            Detail Peserta Ekstrakurikuler
                        </h5>
                        <small class="text-muted">
                            Informasi siswa dan ekstrakurikuler yang diikuti
                        </small>
                    </div>
                </div>

                <button type="button"
                    class="btn btn-light btn-icon rounded-circle"
                    data-bs-dismiss="modal">
                    <i class="ri-close-line fs-18"></i>
                </button>
            </div>

            <div class="modal-body p-4">
                {{-- HERO --}}
                <div class="bg-light rounded-4 p-4 mb-4">
                    <div class="d-flex justify-content-between flex-wrap gap-3">
                        <div>
                            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 mb-2">
                                Peserta Ekstrakurikuler
                            </span>

                            <h2 class="fw-bold mb-2">
                                {{ $nama_siswa }}
                            </h2>

                            <div class="d-flex flex-wrap gap-3 text-muted fs-13">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ri-graduation-cap-line text-primary fs-16"></i>
                                    <div>
                                        <div class="fw-semibold text-body">
                                            {{ $nama_kelas }}
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center gap-1">
                                    <i class="ri-calendar-check-line text-primary"></i>
                                    <span class="fw-medium text-body">
                                        Terdaftar {{ $created_at }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- INFORMATION --}}
                <div class="row g-3">
                    {{-- Ekstrakurikuler --}}
                    <div class="col-lg-6">
                        <div class="card border-0 bg-light h-100 mb-0">
                            <div class="card-body text-center">
                                <div class="avatar-md mx-auto mb-3">
                                    <div class="avatar-title bg-info-subtle text-info rounded-circle fs-24">
                                        <i class="ri-trophy-line"></i>
                                    </div>
                                </div>

                                @if($ekstrakurikulerSiswa)
                                    <h5 class="fw-bold mb-1">
                                        {{ $ekstrakurikulerSiswa->ms_ekstrakurikuler->nama_ekstrakurikuler ?? '-' }}
                                    </h5>
                                    <p class="text-muted mb-0 fs-13">
                                        Ekstrakurikuler
                                    </p>
                                @else
                                    <h6 class="fw-semibold">
                                        Belum memilih
                                    </h6>

                                    <p class="text-muted mb-0 fs-13">
                                        Ekstrakurikuler
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Biaya --}}
                    <div class="col-lg-6">
                        <div class="card border-0 bg-light h-100 mb-0">
                            <div class="card-body text-center">
                                <div class="avatar-md mx-auto mb-3">
                                    <div class="avatar-title bg-success-subtle text-success rounded-circle fs-24">
                                        <i class="ri-money-dollar-circle-line"></i>
                                    </div>
                                </div>

                                @if($ekstrakurikulerSiswa)
                                    <h5 class="fw-bold mb-1">
                                        Rp {{ number_format($ekstrakurikulerSiswa->ms_ekstrakurikuler->biaya ?? 0,0,',','.') }}
                                    </h5>
                                @else
                                    <h5 class="fw-bold mb-1">
                                        -
                                    </h5>
                                @endif

                                <p class="text-muted mb-0 fs-13">
                                    Biaya Ekstrakurikuler
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FOOTER --}}
            <div class="modal-footer border-0 px-4 pb-4 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>