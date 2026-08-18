<div wire:ignore.self class="modal fade" id="ModalDetailPengguna" tabindex="-1"
    aria-labelledby="ModalDetailPenggunaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-user-line"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">
                            Detail Data Petugas
                        </h5>
                    </div>
                </div>

                <button type="button"
                    class="btn btn-light btn-icon rounded-circle ms-auto"
                    data-bs-dismiss="modal" aria-label="Close" id="close-modal">
                    <i class="ri-close-line fs-18"></i>
                </button>
            </div>

            {{-- BODY --}}
            <div class="modal-body px-4 px-lg-5 py-4">
                {{-- PROFILE --}}
                <div class="text-center mb-4">
                    <div class="avatar-lg mx-auto mb-3">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                            <i class="ri-user-3-line fs-14"></i>
                        </div>
                    </div>

                    <h5 class="fw-bold mb-1">
                        {{ $nama ?: '-' }}
                    </h5>

                    <span class="badge bg-secondary-subtle text-secondary text-uppercase px-3 py-2 rounded-pill">
                        {{ $peran ?: '-' }}
                    </span>
                </div>

                {{-- INFORMATION --}}
                <div class="row g-3">
                    {{-- NAMA --}}
                    <div class="col-lg-6">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-sm flex-shrink-0">
                                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                        <i class="ri-user-line"></i>
                                    </div>
                                </div>

                                <div class="min-w-0">
                                    <div class="text-muted fs-13 mb-1">
                                        Nama Petugas
                                    </div>

                                    <div class="fw-semibold text-dark">
                                        {{ $nama ?: '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- USERNAME --}}
                    <div class="col-lg-6">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-sm flex-shrink-0">
                                    <div class="avatar-title bg-info-subtle text-info rounded-circle">
                                        <i class="ri-user-settings-line"></i>
                                    </div>
                                </div>

                                <div class="min-w-0">
                                    <div class="text-muted fs-13 mb-1">
                                        Username
                                    </div>

                                    <div class="fw-semibold text-dark text-break">
                                        {{ $email ?: '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- PERAN --}}
                    <div class="col-lg-6">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-sm flex-shrink-0">
                                    <div class="avatar-title bg-warning-subtle text-warning rounded-circle">
                                        <i class="ri-shield-user-line"></i>
                                    </div>
                                </div>

                                <div class="min-w-0">
                                    <div class="text-muted fs-13 mb-1">
                                        Peran
                                    </div>

                                    <div>
                                        <span class="badge bg-warning-subtle text-warning text-uppercase">
                                            {{ $peran ?: '-' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- TANGGAL PENDAFTARAN --}}
                    <div class="col-lg-6">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-sm flex-shrink-0">
                                    <div class="avatar-title bg-success-subtle text-success rounded-circle">
                                        <i class="ri-calendar-line"></i>
                                    </div>
                                </div>

                                <div class="min-w-0">

                                    <div class="text-muted fs-13 mb-1">
                                        Tanggal Pendaftaran
                                    </div>

                                    <div class="fw-semibold text-dark">
                                        {{ $created_at ?: '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- AKSES JENJANG --}}
                    <div class="col-12">
                        <div class="border rounded-4 p-3">
                            <div class="d-flex align-items-start gap-3">
                                <div class="avatar-sm flex-shrink-0">
                                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                        <i class="ri-building-line"></i>
                                    </div>
                                </div>

                                <div class="flex-grow-1">
                                    <div class="text-muted fs-13 mb-2">
                                        Akses Jenjang
                                    </div>

                                    @if (!empty($aksesJenjang))
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach ($aksesJenjang as $jenjang)
                                                <span class="badge bg-primary-subtle text-primary px-3 py-2">
                                                    {{ $jenjang }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted">
                                            Tidak ada akses jenjang.
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FOOTER --}}
            <div class="modal-footer border-0 pt-0 px-4 px-lg-5 pb-4">
                <button type="button" class="btn btn-light rounded-pill px-4 ms-auto" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>