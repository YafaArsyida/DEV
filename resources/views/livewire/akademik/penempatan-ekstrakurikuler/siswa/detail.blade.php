<div wire:ignore.self class="modal fade" id="detailSiswaEkstrakurikuler" tabindex="-1"
    aria-labelledby="detailSiswaEkstrakurikulerLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            {{-- HEADER --}}
            <div class="modal-header">
                <div class="d-flex align-items-center gap-3">

                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-user-star-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1">
                            Detail Peserta
                        </h5>
                        <p class="text-muted mb-0">
                            Informasi siswa dan ekstrakurikuler
                        </p>
                    </div>

                </div>

                <button type="button"
                    class="btn btn-light btn-icon rounded-circle ms-auto"
                    data-bs-dismiss="modal"
                    aria-label="Close">

                    <i class="ri-close-line fs-18"></i>
                </button>
            </div>

            {{-- BODY --}}
            <div class="modal-body px-4 px-lg-5 py-4">

                {{-- PROFILE SISWA --}}
                <div class="text-center mb-4">

                    <div class="avatar-lg mx-auto mb-3">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                            <i class="ri-user-3-line fs-24"></i>
                        </div>
                    </div>

                    <h5 class="fw-bold mb-1">
                        {{ $nama_siswa ?: '-' }}
                    </h5>

                    <div class="text-muted fs-13">
                        Peserta Ekstrakurikuler
                    </div>

                </div>

                {{-- INFORMATION --}}
                <div class="row g-3">

                    {{-- KELAS --}}
                    <div class="col-12">
                        <div class="border rounded-4 p-3">

                            <div class="d-flex align-items-center gap-3">

                                <div class="avatar-sm flex-shrink-0">
                                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                        <i class="ri-graduation-cap-line"></i>
                                    </div>
                                </div>

                                <div class="min-w-0">
                                    <div class="text-muted fs-13 mb-1">
                                        Kelas
                                    </div>

                                    <div class="fw-semibold text-dark">
                                        {{ $nama_kelas ?: '-' }}
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>

                    {{-- EKSTRAKURIKULER --}}
                    <div class="col-md-6">
                        <div class="border rounded-4 p-3 h-100">

                            <div class="d-flex align-items-center gap-3">

                                <div class="avatar-sm flex-shrink-0">
                                    <div class="avatar-title bg-info-subtle text-info rounded-circle">
                                        <i class="ri-trophy-line"></i>
                                    </div>
                                </div>

                                <div class="min-w-0">

                                    <div class="text-muted fs-13 mb-1">
                                        Ekstrakurikuler
                                    </div>

                                    <div class="fw-semibold text-dark text-break">
                                        @if ($ekstrakurikulerSiswa)
                                            {{ $ekstrakurikulerSiswa->ms_ekstrakurikuler->nama_ekstrakurikuler ?? '-' }}
                                        @else
                                            Belum memilih
                                        @endif
                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>

                    {{-- BIAYA --}}
                    <div class="col-md-6">
                        <div class="border rounded-4 p-3 h-100">

                            <div class="d-flex align-items-center gap-3">

                                <div class="avatar-sm flex-shrink-0">
                                    <div class="avatar-title bg-success-subtle text-success rounded-circle">
                                        <i class="ri-money-dollar-circle-line"></i>
                                    </div>
                                </div>

                                <div class="min-w-0">

                                    <div class="text-muted fs-13 mb-1">
                                        Biaya
                                    </div>

                                    <div class="fw-semibold text-dark">
                                        @if ($ekstrakurikulerSiswa)
                                            Rp {{ number_format($ekstrakurikulerSiswa->ms_ekstrakurikuler->biaya ?? 0, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>

                    {{-- TANGGAL TERDAFTAR --}}
                    <div class="col-12">
                        <div class="border rounded-4 p-3">

                            <div class="d-flex align-items-center gap-3">

                                <div class="avatar-sm flex-shrink-0">
                                    <div class="avatar-title bg-warning-subtle text-warning rounded-circle">
                                        <i class="ri-calendar-check-line"></i>
                                    </div>
                                </div>

                                <div class="min-w-0">

                                    <div class="text-muted fs-13 mb-1">
                                        Terdaftar
                                    </div>

                                    <div class="fw-semibold text-dark">
                                        {{ $created_at ?: '-' }}
                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>

                </div>

            </div>

            {{-- FOOTER --}}
            <div class="modal-footer border-0 pt-0 px-4 px-lg-5 pb-4">

                <button type="button"
                    class="btn btn-light rounded-pill px-4 ms-auto"
                    data-bs-dismiss="modal">

                    <i class="ri-close-line me-1"></i>
                    Tutup

                </button>

            </div>

        </div>
    </div>

</div>