
<div class="card border-0 shadow-sm rounded-4">
    {{-- HEADER --}}
    <div class="card-header bg-transparent border-bottom">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-sm flex-shrink-0">
                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                        <i class="mdi mdi-account-multiple-outline"></i>
                    </div>
                </div>

                <div>
                    <h5 class="fw-bold mb-1">Pendaftaran Terbaru</h5>
                    <small class="text-muted">
                        Daftar calon siswa yang terakhir mendaftar.
                    </small>
                </div>
            </div>

            <a href="javascript:void(0)"
                class="btn btn-outline-primary rounded-pill">
                Lihat Semua Pendaftar
                <i class="mdi mdi-arrow-right ms-1"></i>
            </a>
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

        @elseif ($pendaftarTerbaru->isEmpty())
            <div class="text-center py-4">
                <div class="avatar-md mx-auto mb-3">
                    <div class="avatar-title bg-light text-muted rounded-circle fs-3">
                        <i class="mdi mdi-calendar-remove-outline"></i>
                    </div>
                </div>

                <h6 class="fw-semibold mb-1">
                    Belum Ada Pendaftar
                </h6>

                <p class="text-muted small mb-0">
                    Belum ada pendaftar pada periode ini.
                </p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover table-nowrap align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Calon Siswa</th>
                            <th scope="col">Nomor Pendaftaran</th>
                            <th scope="col">Jalur</th>
                            <th scope="col">Sumber</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($pendaftarTerbaru as $pendaftar)
                            <tr>
                                <td>
                                    <h6 class="mb-1 fw-medium">
                                        {{ $pendaftar['nama'] }}
                                    </h6>
                                    <small class="text-muted">
                                        {{ $pendaftar['nomor'] }}
                                    </small>
                                </td>

                                <td>
                                    <span class="text-primary fw-medium">
                                        {{ $pendaftar['nomor'] }}
                                    </span>
                                </td>

                                <td>{{ $pendaftar['jalur'] }}</td>

                                <td>{{ $pendaftar['sumber'] }}</td>

                                <td>{{ $pendaftar['tanggal'] }}</td>

                                <td>
                                    <span class="badge bg-{{ $pendaftar['badge'] }}-subtle text-{{ $pendaftar['badge'] }} rounded-pill px-3 py-2">
                                        {{ $pendaftar['status'] }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-light rounded-circle"
                                        title="Lihat detail"
                                        aria-label="Lihat detail"
                                        wire:click="$emit('loadDetailPendaftar', {{ $pendaftar['id'] }})">
                                        <i class="mdi mdi-eye-outline fs-16"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
