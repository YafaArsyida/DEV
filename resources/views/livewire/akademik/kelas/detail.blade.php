<div wire:ignore.self class="modal fade" id="ModalDetailKelas" tabindex="-1"
    aria-labelledby="ModalDetailKelasLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            {{-- HEADER --}}
            <div class="modal-header">
                <div class="d-flex align-items-center gap-3">

                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-building-4-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1">
                            Detail Kelas
                        </h5>
                        <p class="text-muted mb-0">
                            Informasi kelas
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

                {{-- PROFILE KELAS --}}
                <div class="text-center mb-4">

                    <div class="avatar-lg mx-auto mb-3">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                            <i class="ri-building-4-line fs-24"></i>
                        </div>
                    </div>

                    <h5 class="fw-bold mb-1">
                        {{ $kelasDetail?->nama_kelas ?: '-' }}
                    </h5>

                    <span class="text-muted fs-13">
                        Informasi kelas
                    </span>

                </div>

                {{-- INFORMATION --}}
                <div class="row g-3">

                    {{-- NAMA KELAS --}}
                    <div class="col-12">
                        <div class="border rounded-4 p-3">

                            <div class="d-flex align-items-center gap-3">

                                <div class="avatar-sm flex-shrink-0">
                                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                        <i class="ri-home-4-line"></i>
                                    </div>
                                </div>

                                <div class="min-w-0">
                                    <div class="text-muted fs-13 mb-1">
                                        Nama Kelas
                                    </div>

                                    <div class="fw-semibold text-dark">
                                        {{ $kelasDetail?->nama_kelas ?: '-' }}
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>

                    {{-- JENJANG --}}
                    <div class="col-md-6">
                        <div class="border rounded-4 p-3 h-100">

                            <div class="d-flex align-items-center gap-3">

                                <div class="avatar-sm flex-shrink-0">
                                    <div class="avatar-title bg-info-subtle text-info rounded-circle">
                                        <i class="ri-school-line"></i>
                                    </div>
                                </div>

                                <div class="min-w-0">
                                    <div class="text-muted fs-13 mb-1">
                                        Jenjang
                                    </div>

                                    <div class="fw-semibold text-dark">
                                        {{ $kelasDetail?->ms_jenjang?->nama_jenjang ?: '-' }}
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>

                    {{-- TAHUN AJARAN --}}
                    <div class="col-md-6">
                        <div class="border rounded-4 p-3 h-100">

                            <div class="d-flex align-items-center gap-3">

                                <div class="avatar-sm flex-shrink-0">
                                    <div class="avatar-title bg-success-subtle text-success rounded-circle">
                                        <i class="ri-calendar-line"></i>
                                    </div>
                                </div>

                                <div class="min-w-0">
                                    <div class="text-muted fs-13 mb-1">
                                        Tahun Ajaran
                                    </div>

                                    <div class="fw-semibold text-dark">
                                        {{ $kelasDetail?->ms_tahun_ajar?->nama_tahun_ajar ?: '-' }}
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                    {{-- JUMLAH SISWA --}}
                    <div class="col-md-6">
                        <div class="border rounded-4 p-3 h-100">

                            <div class="d-flex align-items-center gap-3">

                                <div class="avatar-sm flex-shrink-0">
                                    <div class="avatar-title bg-warning-subtle text-warning rounded-circle">
                                        <i class="ri-group-line"></i>
                                    </div>
                                </div>

                                <div class="min-w-0">
                                    <div class="text-muted fs-13 mb-1">
                                        Jumlah Siswa
                                    </div>

                                    <div class="fw-semibold text-dark">
                                        {{ $jumlahSiswa }} Siswa
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>

                    {{-- WALI KELAS --}}
                    <div class="col-md-6">
                        <div class="border rounded-4 p-3 h-100">

                            <div class="d-flex align-items-center gap-3">

                                <div class="avatar-sm flex-shrink-0">
                                    <div class="avatar-title bg-secondary-subtle text-secondary rounded-circle">
                                        <i class="ri-user-star-line"></i>
                                    </div>
                                </div>

                                <div class="min-w-0">
                                    <div class="text-muted fs-13 mb-1">
                                        Wali Kelas
                                    </div>

                                    <div class="fw-semibold text-dark">
                                        Nama Wali Kelas
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