
<div class="card border-0 shadow-sm rounded-4 h-100">
    {{-- HEADER --}}
    <div class="card-header bg-transparent border-bottom">
        <div class="d-flex align-items-center gap-3">
            <div class="avatar-sm flex-shrink-0">
                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                    <i class="mdi mdi-chart-donut"></i>
                </div>
            </div>

            <div>
                <h5 class="fw-bold mb-1">Distribusi Jalur Pendaftaran</h5>
                <small class="text-muted">
                    Perbandingan jumlah calon siswa berdasarkan jalur.
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

        @elseif ($jalurPendaftaran->isEmpty())
            <div class="text-center py-4">
                <i class="mdi mdi-chart-donut fs-1 text-muted"></i>
                <p class="text-muted mb-0 mt-2">
                    Belum ada jalur pendaftaran aktif.
                </p>
            </div>

        @else
            <div class="d-flex flex-column gap-4">
                @foreach ($jalurPendaftaran as $jalur)
                    <div>
                        <div class="d-flex align-items-center justify-content-between gap-3 mb-2">
                            <span class="fw-medium">
                                {{ $jalur['nama'] }}
                            </span>

                            <span class="text-muted small text-end">
                                {{ number_format($jalur['jumlah'], 0, ',', '.') }} pendaftar
                                <span class="fw-semibold text-body ms-1">
                                    ({{ $jalur['persentase'] }}%)
                                </span>
                            </span>
                        </div>

                        <div class="progress" style="height: 8px;">
                            <div
                                class="progress-bar bg-{{ $jalur['color'] }}"
                                role="progressbar"
                                style="width: {{ $jalur['persentase'] }}%"
                                aria-valuenow="{{ $jalur['persentase'] }}"
                                aria-valuemin="0"
                                aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="alert alert-light border rounded-3 mt-4 mb-0">
                <div class="d-flex align-items-start gap-2">
                    <i class="mdi mdi-information-outline text-primary fs-18"></i>

                    <div class="small text-muted">
                        Total
                        <strong class="text-body">
                            {{ number_format($totalPendaftar, 0, ',', '.') }} pendaftar
                        </strong>
                        dari seluruh jalur aktif.
                        Pastikan kuota dan persyaratan setiap jalur sesuai
                        dengan ketentuan sekolah.
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
