
<div class="card border-0 shadow-sm rounded-4 h-100">
    {{-- HEADER --}}
    <div class="card-header bg-transparent border-bottom">
        <div class="d-flex align-items-center gap-3">
            <div class="avatar-sm">
                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                    <i class="mdi mdi-clipboard-list-outline"></i>
                </div>
            </div>

            <div>
                <h5 class="fw-bold mb-1">Perlu Ditindaklanjuti</h5>
                <small class="text-muted">
                    Pekerjaan yang membutuhkan perhatian panitia.
                </small>
            </div>
        </div>
    </div>

    {{-- BODY --}}
    <div class="card-body">

        @if (!$selectedJenjang || !$selectedTahunAjar)
            <div class="text-center py-4">
                <div class="avatar-md mx-auto mb-3">
                    <div class="avatar-title bg-light text-muted rounded-circle fs-3">
                        <i class="mdi mdi-filter-outline"></i>
                    </div>
                </div>

                <h6 class="fw-semibold mb-1">
                    Pilih Parameter
                </h6>

                <p class="text-muted small mb-0">
                    Pilih jenjang dan tahun ajar untuk melihat antrean pekerjaan.
                </p>
            </div>

        @elseif (!$periode)
            <div class="text-center py-4">
                <div class="avatar-md mx-auto mb-3">
                    <div class="avatar-title bg-light text-muted rounded-circle fs-3">
                        <i class="mdi mdi-calendar-remove-outline"></i>
                    </div>
                </div>

                <h6 class="fw-semibold mb-1">
                    Belum Ada Periode PPDB
                </h6>

                <p class="text-muted small mb-0">
                    Belum ada periode aktif untuk parameter yang dipilih.
                </p>
            </div>

        @else
            {{-- MENUNGGU VERIFIKASI --}}
            <div class="d-flex align-items-start gap-3 mb-4">
                <div class="avatar-sm flex-shrink-0">
                    <div class="avatar-title bg-warning-subtle text-warning rounded-circle">
                        <i class="mdi mdi-file-clock-outline fs-20"></i>
                    </div>
                </div>

                <div class="flex-grow-1">
                    <h5 class="mb-1">
                        {{ number_format($menungguVerifikasi, 0, ',', '.') }}
                        pendaftaran
                    </h5>

                    <p class="text-muted small mb-2">
                        Menunggu pemeriksaan data dan dokumen.
                    </p>

                    <a href="javascript:void(0)"
                       class="link-primary fw-medium small">
                        Buka antrean
                        <i class="mdi mdi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>

            {{-- PERLU PERBAIKAN --}}
            <div class="d-flex align-items-start gap-3 mb-4">
                <div class="avatar-sm flex-shrink-0">
                    <div class="avatar-title bg-danger-subtle text-danger rounded-circle">
                        <i class="mdi mdi-file-alert-outline fs-20"></i>
                    </div>
                </div>

                <div class="flex-grow-1">
                    <h5 class="mb-1">
                        {{ number_format($perluPerbaikan, 0, ',', '.') }}
                        pendaftaran
                    </h5>

                    <p class="text-muted small mb-2">
                        Memerlukan perbaikan data atau kelengkapan berkas.
                    </p>

                    <a href="javascript:void(0)"
                       class="link-primary fw-medium small">
                        Lihat pendaftar
                        <i class="mdi mdi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>

            {{-- BANTU PENDAFTARAN --}}
            <div class="d-flex align-items-start gap-3">
                <div class="avatar-sm flex-shrink-0">
                    <div class="avatar-title bg-info-subtle text-info rounded-circle">
                        <i class="mdi mdi-account-plus-outline fs-20"></i>
                    </div>
                </div>

                <div class="flex-grow-1">
                    <h5 class="mb-1">Bantu pendaftaran</h5>

                    <p class="text-muted small mb-2">
                        Layani orang tua yang membutuhkan bantuan mendaftar.
                    </p>

                    <a href="javascript:void(0)"
                       class="link-primary fw-medium small">
                        Mulai pendaftaran
                        <i class="mdi mdi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        @endif

    </div>
</div>
