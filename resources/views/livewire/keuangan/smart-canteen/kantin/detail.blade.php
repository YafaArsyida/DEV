<div wire:ignore.self class="modal fade"
    id="ModalDetailKantin" tabindex="-1"
    aria-labelledby="detailKantinLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            {{-- HEADER --}}
            <div class="modal-header border-0 px-4 pt-4 pb-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-store-2-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1" id="detailKantinLabel">
                            Detail Kantin
                        </h5>

                        <small class="text-muted">
                            Informasi dan petugas yang terkait dengan kantin.
                        </small>
                    </div>
                </div>

                <button type="button"
                    class="btn btn-light btn-icon rounded-circle ms-auto"
                    data-bs-dismiss="modal" aria-label="Close">

                    <i class="ri-close-line fs-18"></i>
                </button>
            </div>

            {{-- BODY --}}
            <div class="modal-body px-4 pb-4">
                {{-- IDENTITAS KANTIN --}}
                <div class="bg-light rounded-4 p-4 mb-4">
                    <div class="d-flex align-items-start gap-3">
                        <div class="avatar-md flex-shrink-0">
                            <div class="avatar-title bg-white text-primary rounded-3 fs-22 shadow-sm">
                                <i class="ri-store-2-line"></i>
                            </div>
                        </div>

                        <div class="flex-grow-1">
                            <h4 class="fw-bold mb-1">
                                {{ $nama_kantin ?: '-' }}
                            </h4>

                            <div class="text-muted fs-13">
                                <i class="ri-calendar-line me-1"></i>
                                Dibuat pada {{ $created_at ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- DETAIL --}}
                <div class="row g-3">
                    {{-- DESKRIPSI --}}
                    <div class="col-12">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div class="avatar-xs">
                                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                        <i class="ri-file-text-line"></i>
                                    </div>
                                </div>

                                <h6 class="fw-semibold mb-0">
                                    Deskripsi
                                </h6>
                            </div>

                            <p class="text-muted mb-0 lh-lg">
                                {{ $deskripsi ?: 'Tidak ada deskripsi kantin.' }}
                            </p>
                        </div>
                    </div>

                    {{-- PETUGAS --}}
                    <div class="col-12">
                        <div class="border rounded-4 p-3">
                            <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-xs">
                                        <div class="avatar-title bg-success-subtle text-success rounded-circle">
                                            <i class="ri-user-line"></i>
                                        </div>
                                    </div>

                                    <h6 class="fw-semibold mb-0">
                                        Petugas Kantin
                                    </h6>
                                </div>

                                @if(count($petugas))
                                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                                        {{ count($petugas) }} Petugas
                                    </span>
                                @else
                                    <span class="badge bg-light text-muted rounded-pill px-3 py-2">
                                        Belum ada
                                    </span>
                                @endif

                            </div>

                            @if(count($petugas))
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($petugas as $namaPetugas)
                                        <span class="badge bg-light text-dark border rounded-pill px-3 py-2 fw-normal">
                                            <i class="ri-user-line text-primary me-1"></i>
                                            {{ $namaPetugas }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-3">
                                    <div class="text-muted mb-1">
                                        <i class="ri-user-unfollow-line fs-24"></i>
                                    </div>

                                    <span class="text-muted fs-13">
                                        Belum ada petugas yang ditugaskan pada kantin ini.
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- FOOTER --}}
            <div class="modal-footer border-0 px-4 pb-4 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4 ms-auto"
                    data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>