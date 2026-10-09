
@extends('template_ppdb.v_template')

@section('content')
@php
    $title = 'Verifikasi Pendaftaran';

    $statistik = [
        [
            'label' => 'Menunggu Verifikasi',
            'jumlah' => 24,
            'icon' => 'mdi-clipboard-text-clock-outline',
            'warna' => 'warning',
        ],
        [
            'label' => 'Terverifikasi',
            'jumlah' => 88,
            'icon' => 'mdi-clipboard-check-outline',
            'warna' => 'success',
        ],
        [
            'label' => 'Perlu Perbaikan',
            'jumlah' => 8,
            'icon' => 'mdi-clipboard-alert-outline',
            'warna' => 'danger',
        ],
    ];

    $antrean = [
        [
            'nomor' => 'PPDB-2027-0120',
            'nama' => 'Ahmad Fathan Pratama',
            'jenjang' => 'SMA/SMK',
            'jalur' => 'Reguler',
            'tanggal' => '08 Okt 2026',
            'dokumen_lengkap' => 4,
            'total_dokumen' => 5,
            'status' => 'Menunggu Verifikasi',
            'warna' => 'warning',
        ],
        [
            'nomor' => 'PPDB-2027-0118',
            'nama' => 'Muhammad Rizky Ramadhan',
            'jenjang' => 'SMA/SMK',
            'jalur' => 'Reguler',
            'tanggal' => '07 Okt 2026',
            'dokumen_lengkap' => 3,
            'total_dokumen' => 5,
            'status' => 'Menunggu Verifikasi',
            'warna' => 'warning',
        ],
        [
            'nomor' => 'PPDB-2027-0115',
            'nama' => 'Nabila Zahra',
            'jenjang' => 'SMA/SMK',
            'jalur' => 'Afirmasi',
            'tanggal' => '06 Okt 2026',
            'dokumen_lengkap' => 5,
            'total_dokumen' => 5,
            'status' => 'Perlu Perbaikan',
            'warna' => 'danger',
        ],
        [
            'nomor' => 'PPDB-2027-0112',
            'nama' => 'Farhan Al-Faruq',
            'jenjang' => 'SMA/SMK',
            'jalur' => 'Reguler',
            'tanggal' => '05 Okt 2026',
            'dokumen_lengkap' => 5,
            'total_dokumen' => 5,
            'status' => 'Menunggu Verifikasi',
            'warna' => 'warning',
        ],
    ];
@endphp

<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">

        {{-- Page title --}}
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{ $title }}</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">PPDB</li>
                            <li class="breadcrumb-item active">Verifikasi</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        {{-- Summary cards --}}
        <div class="row g-3 mb-1">
            @foreach ($statistik as $item)
                <div class="col-xl-4 col-md-6">
                    <div class="card rounded-4 border shadow-none h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <div>
                                    <p class="text-muted mb-2">{{ $item['label'] }}</p>
                                    <h4 class="mb-0 fw-semibold">
                                        {{ $item['jumlah'] }}
                                        <small class="text-muted fs-13 fw-normal">pendaftar</small>
                                    </h4>
                                </div>

                                <div class="avatar-md flex-shrink-0">
                                    <div class="avatar-title bg-{{ $item['warna'] }}-subtle text-{{ $item['warna'] }} rounded-4 fs-3">
                                        <i class="mdi {{ $item['icon'] }}"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Main card --}}
        <div class="card rounded-4 border shadow-none">
            <div class="card-header bg-transparent border-bottom p-4">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div>
                        <h5 class="card-title mb-1">Antrean Verifikasi</h5>
                        <p class="text-muted mb-0">
                            Periksa data dan dokumen calon peserta didik sebelum memberikan keputusan.
                        </p>
                    </div>

                    <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2">
                        <i class="mdi mdi-clock-outline me-1"></i>
                        24 Menunggu
                    </span>
                </div>
            </div>

            {{-- Filters --}}
            <div class="card-body border-bottom p-4">
                <div class="row g-3">
                    <div class="col-xl-5 col-md-6">
                        <label class="form-label">Cari pendaftar</label>
                        <div class="position-relative">
                            <input type="search"
                                class="form-control rounded-3 ps-5"
                                placeholder="Nama atau nomor pendaftaran"
                                disabled>
                            <i class="mdi mdi-magnify position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <label class="form-label">Status verifikasi</label>
                        <select class="form-select rounded-3" disabled>
                            <option>Semua status</option>
                            <option>Menunggu Verifikasi</option>
                            <option>Perlu Perbaikan</option>
                            <option>Terverifikasi</option>
                        </select>
                    </div>

                    <div class="col-xl-2 col-md-6">
                        <label class="form-label">Kelengkapan dokumen</label>
                        <select class="form-select rounded-3" disabled>
                            <option>Semua dokumen</option>
                            <option>Lengkap</option>
                            <option>Belum lengkap</option>
                        </select>
                    </div>

                    <div class="col-xl-2 col-md-6 d-flex align-items-end">
                        <button type="button" class="btn btn-light rounded-3 w-100" disabled>
                            <i class="mdi mdi-filter-outline me-1"></i>
                            Filter
                        </button>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" style="width: 55px;">No.</th>
                                <th>Calon Siswa</th>
                                <th>Dokumen</th>
                                <th>Tanggal Dikirim</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($antrean as $index => $item)
                                <tr>
                                    <td class="ps-4 text-muted">{{ $index + 1 }}</td>

                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-sm flex-shrink-0">
                                                <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                                    <i class="mdi mdi-account-school-outline fs-5"></i>
                                                </div>
                                            </div>

                                            <div>
                                                <h6 class="mb-1">{{ $item['nama'] }}</h6>
                                                <span class="text-muted fs-13">
                                                    {{ $item['nomor'] }}
                                                </span>
                                                <div class="text-muted fs-12 mt-1">
                                                    {{ $item['jenjang'] }} · {{ $item['jalur'] }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="fw-medium mb-1">
                                            {{ $item['dokumen_lengkap'] }}/{{ $item['total_dokumen'] }} dokumen
                                        </div>
                                        <div class="progress rounded-pill" style="height: 5px; width: 100px;">
                                            <div class="progress-bar {{ $item['dokumen_lengkap'] === $item['total_dokumen'] ? 'bg-success' : 'bg-warning' }}"
                                                role="progressbar"
                                                style="width: {{ ($item['dokumen_lengkap'] / $item['total_dokumen']) * 100 }}%">
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <span>{{ $item['tanggal'] }}</span>
                                    </td>

                                    <td>
                                        <span class="badge bg-{{ $item['warna'] }}-subtle text-{{ $item['warna'] }} rounded-pill px-3 py-2">
                                            {{ $item['status'] }}
                                        </span>
                                    </td>

                                    <td class="text-end pe-4">
                                        <button type="button"
                                            class="btn btn-sm btn-soft-primary rounded-3"
                                            title="Periksa pendaftaran"
                                            disabled>
                                            <i class="mdi mdi-clipboard-search-outline me-1"></i>
                                            Periksa
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 p-4">
                    <p class="text-muted mb-0 fs-13">
                        Menampilkan 4 dari 24 pendaftar yang menunggu verifikasi.
                    </p>

                    <nav aria-label="Navigasi halaman">
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item disabled">
                                <a class="page-link rounded-start-3" href="javascript:void(0)">
                                    <i class="mdi mdi-chevron-left"></i>
                                </a>
                            </li>
                            <li class="page-item active">
                                <a class="page-link" href="javascript:void(0)">1</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0)">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0)">3</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link rounded-end-3" href="javascript:void(0)">
                                    <i class="mdi mdi-chevron-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection