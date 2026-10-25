
<div>
    {{-- PROFIL AKUN --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        {{-- HEADER --}}
        <div class="card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-sm flex-shrink-0">
                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                        <i class="ri-user-settings-line"></i>
                    </div>
                </div>

                <div class="flex-grow-1">
                    <h5 class="fw-bold mb-1">Informasi Akun</h5>
                    <small class="text-muted">
                        Informasi akun orang tua yang digunakan untuk pendaftaran.
                    </small>
                </div>
            </div>
        </div>

        <div class="card-body">
            {{-- IDENTITAS --}}
            <div class="text-center py-3 mb-3">
                <div class="avatar-lg mx-auto mb-3">
                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-1">
                        <i class="ri-user-line"></i>
                    </div>
                </div>

                <h5 class="fw-bold mb-2">
                    {{ $pengguna->nama ?: 'Nama belum diisi' }}
                </h5>

                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                    {{ $pengguna->peran }}
                </span>
            </div>

            <hr class="border-dashed">

            {{-- DETAIL AKUN --}}
            <div class="d-flex align-items-start gap-3 mb-4">
                <div class="avatar-sm flex-shrink-0">
                    <div class="avatar-title bg-light text-muted rounded-circle fs-18">
                        <i class="ri-user-3-line"></i>
                    </div>
                </div>

                <div class="flex-grow-1 min-w-0">
                    <small class="text-muted d-block mb-1">Nama Lengkap</small>
                    <h6 class="fw-semibold mb-0">
                        {{ $pengguna->nama ?: '-' }}
                    </h6>
                </div>
            </div>

            <div class="d-flex align-items-start gap-3 mb-4">
                <div class="avatar-sm flex-shrink-0">
                    <div class="avatar-title bg-light text-muted rounded-circle fs-18">
                        <i class="ri-mail-line"></i>
                    </div>
                </div>

                <div class="flex-grow-1 min-w-0">
                    <small class="text-muted d-block mb-1">Email</small>
                    <h6 class="fw-semibold mb-0 text-break">
                        {{ $pengguna->email ?: '-' }}
                    </h6>
                </div>
            </div>

            <div class="d-flex align-items-start gap-3">
                <div class="avatar-sm flex-shrink-0">
                    <div class="avatar-title bg-light text-muted rounded-circle fs-18">
                        <i class="ri-phone-line"></i>
                    </div>
                </div>

                <div class="flex-grow-1 min-w-0">
                    <small class="text-muted d-block mb-1">Nomor Telepon</small>
                    <h6 class="fw-semibold mb-0">
                        {{ $pengguna->telepon ?: '-' }}
                    </h6>
                </div>
            </div>
        </div>
    </div>

    {{-- INFORMASI PENDAFTARAN --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        {{-- HEADER --}}
        <div class="card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-sm flex-shrink-0">
                    <div class="avatar-title bg-success-subtle text-success rounded-circle fs-20">
                        <i class="ri-file-list-3-line"></i>
                    </div>
                </div>

                <div class="flex-grow-1">
                    <h5 class="fw-bold mb-1">Informasi Pendaftaran</h5>
                    <small class="text-muted">
                        Ringkasan pendaftaran pada jenjang yang dipilih.
                    </small>
                </div>
            </div>
        </div>

        <div class="card-body">
            @if ($pendaftaran)
                {{-- JENJANG --}}
                <div class="d-flex align-items-start gap-3 mb-4">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-light text-muted rounded-circle fs-18">
                            <i class="ri-building-4-line"></i>
                        </div>
                    </div>

                    <div class="flex-grow-1 min-w-0">
                        <small class="text-muted d-block mb-1">Jenjang</small>
                        <h6 class="fw-semibold mb-0">
                            {{ $jenjang->nama_jenjang }}
                        </h6>
                    </div>
                </div>

                {{-- NOMOR PENDAFTARAN --}}
                <div class="d-flex align-items-start gap-3 mb-4">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-light text-muted rounded-circle fs-18">
                            <i class="ri-hashtag"></i>
                        </div>
                    </div>

                    <div class="flex-grow-1 min-w-0">
                        <small class="text-muted d-block mb-1">
                            Nomor Pendaftaran
                        </small>
                        <h6 class="fw-semibold mb-0 text-break">
                            {{ $pendaftaran->nomor_pendaftaran ?: '-' }}
                        </h6>
                    </div>
                </div>

                {{-- PERIODE --}}
                <div class="d-flex align-items-start gap-3 mb-4">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-light text-muted rounded-circle fs-18">
                            <i class="ri-calendar-event-line"></i>
                        </div>
                    </div>

                    <div class="flex-grow-1 min-w-0">
                        <small class="text-muted d-block mb-1">
                            Periode PPDB
                        </small>
                        <h6 class="fw-semibold mb-0">
                            {{ optional($pendaftaran->ppdb_periode)->nama_periode ?? '-' }}
                        </h6>
                    </div>
                </div>

                {{-- STATUS --}}
                @php
                    $statusBadge = [
                        'draft' => 'bg-secondary-subtle text-secondary',
                        'pengecekan' => 'bg-warning-subtle text-warning',
                        'perbaikan' => 'bg-danger-subtle text-danger',
                        'diterima' => 'bg-success-subtle text-success',
                    ];

                    $status = $pendaftaran->status;
                @endphp

                <div class="d-flex align-items-start gap-3">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-light text-muted rounded-circle fs-18">
                            <i class="ri-checkbox-circle-line"></i>
                        </div>
                    </div>

                    <div class="flex-grow-1">
                        <small class="text-muted d-block mb-2">
                            Status Pendaftaran
                        </small>

                        <span class="badge rounded-pill px-3 py-2 {{ $statusBadge[$status] ?? 'bg-light text-dark' }}">
                            {{ ucfirst($status ?: 'Belum tersedia') }}
                        </span>
                    </div>
                </div>
            @else
                {{-- BELUM ADA PENDAFTARAN --}}
                <div class="text-center py-4">
                    <div class="avatar-lg mx-auto mb-3">
                        <div class="avatar-title bg-light text-muted rounded-circle fs-1">
                            <i class="ri-file-search-line"></i>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-2">Belum Ada Pendaftaran</h6>

                    <p class="text-muted mb-0">
                        Belum ditemukan data pendaftaran untuk jenjang
                        <strong>{{ $jenjang->nama_jenjang }}</strong>.
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
