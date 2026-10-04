<div wire:ignore.self class="modal fade" id="detailEkstrakurikuler" tabindex="-1"
    aria-labelledby="detailEkstrakurikulerLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            {{-- Header --}}
            <div class="modal-header border-bottom px-4 py-3">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm me-3">
                        <span class="avatar-title bg-primary-subtle text-primary rounded-3 fs-4">
                            <i class="ri-trophy-line"></i>
                        </span>
                    </div>

                    <div>
                        <h5 class="modal-title mb-1" id="detailEkstrakurikulerLabel">
                            Detail Ekstrakurikuler
                        </h5>

                        <small class="text-muted d-block">
                            Informasi ekstrakurikuler
                        </small>
                    </div>
                </div>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Body --}}
            <div class="modal-body p-4">

                @if ($ekstrakurikuler)

                    {{-- Profile --}}
                    <div class="text-center mb-4">
                        <div class="avatar-lg mx-auto mb-3">
                            <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-2">
                                <i class="ri-trophy-line"></i>
                            </span>
                        </div>

                        <h5 class="mb-1">
                            {{ $ekstrakurikuler->nama_ekstrakurikuler }}
                        </h5>

                        <p class="text-muted mb-0">
                            {{ $ekstrakurikuler->deskripsi ?: 'Tidak ada deskripsi.' }}
                        </p>
                    </div>

                    {{-- Informasi --}}
                    <div class="row g-3">

                        {{-- Jenjang --}}
                        <div class="col-md-6">
                            <div class="border rounded-4 p-3 h-100">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3">
                                        <span class="avatar-title bg-info-subtle text-info rounded-3">
                                            <i class="ri-school-line"></i>
                                        </span>
                                    </div>

                                    <div>
                                        <div class="text-muted fs-12 mb-1">
                                            Jenjang
                                        </div>

                                        <div class="fw-medium">
                                            {{ $ekstrakurikuler->ms_jenjang?->nama_jenjang ?: '-' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tahun Ajaran --}}
                        <div class="col-md-6">
                            <div class="border rounded-4 p-3 h-100">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3">
                                        <span class="avatar-title bg-warning-subtle text-warning rounded-3">
                                            <i class="ri-calendar-line"></i>
                                        </span>
                                    </div>

                                    <div>
                                        <div class="text-muted fs-12 mb-1">
                                            Tahun Ajaran
                                        </div>

                                        <div class="fw-medium">
                                            {{ $ekstrakurikuler->ms_tahun_ajar?->nama_tahun_ajar ?: '-' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Biaya --}}
                        <div class="col-md-4">
                            <div class="border rounded-4 p-3 h-100">
                                <div class="text-muted fs-12 mb-2">
                                    Biaya
                                </div>

                                <div class="fw-semibold">
                                    Rp{{ number_format($ekstrakurikuler->biaya ?? 0, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>

                        {{-- Kuota --}}
                        <div class="col-md-4">
                            <div class="border rounded-4 p-3 h-100">
                                <div class="text-muted fs-12 mb-2">
                                    Kuota
                                </div>

                                <div class="fw-semibold">
                                    {{ $ekstrakurikuler->kuota ?? 0 }} siswa
                                </div>
                            </div>
                        </div>

                        {{-- Terdaftar --}}
                        <div class="col-md-4">
                            <div class="border rounded-4 p-3 h-100">
                                <div class="text-muted fs-12 mb-2">
                                    Terdaftar
                                </div>

                                <div class="fw-semibold text-primary">
                                    {{ $ekstrakurikuler->total_peserta ?? 0 }} siswa
                                </div>
                            </div>
                        </div>

                        {{-- Tersedia --}}
                        <div class="col-12">
                            <div class="border rounded-4 p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-muted fs-12 mb-1">
                                            Sisa Kuota
                                        </div>

                                        <div class="fw-semibold">
                                            {{ max(0, ($ekstrakurikuler->kuota ?? 0) - ($ekstrakurikuler->total_peserta ?? 0)) }}
                                            siswa
                                        </div>
                                    </div>

                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-success-subtle text-success rounded-3">
                                            <i class="ri-user-add-line"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                @else
                    <div class="text-center py-5">
                        <div class="avatar-lg mx-auto mb-3">
                            <span class="avatar-title bg-light text-muted rounded-circle fs-2">
                                <i class="ri-information-line"></i>
                            </span>
                        </div>

                        <h5>Data Tidak Ditemukan</h5>

                        <p class="text-muted mb-0">
                            Data ekstrakurikuler tidak tersedia.
                        </p>
                    </div>
                @endif
            </div>

            {{-- Footer --}}
            <div class="modal-footer border-top px-4 py-3">
                <button
                    type="button"
                    class="btn btn-light rounded-pill px-4"
                    data-bs-dismiss="modal"
                >
                    Tutup
                </button>

                @if ($ekstrakurikuler)
                    <button
                        type="button"
                        class="btn btn-primary rounded-pill px-4"
                        wire:click="cetakEkstrakurikuler"
                    >
                        <i class="ri-printer-line me-1"></i>
                        Cetak
                    </button>
                @endif
            </div>

        </div>
    </div>
</div>