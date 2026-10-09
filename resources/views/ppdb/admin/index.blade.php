@extends('template_ppdb.v_template')

@section('content')

@php
    $title = 'Dashboard PPDB';

    // Data statis untuk prototipe UI.
    // Nantinya ganti dengan data dari database.
    $statistik = [
        [
            'label' => 'Total Pendaftar',
            'value' => '120',
            'icon' => 'mdi-account-group-outline',
            'description' => 'Seluruh calon siswa',
            'color' => 'primary',
        ],
        [
            'label' => 'Menunggu Verifikasi',
            'value' => '24',
            'icon' => 'mdi-file-clock-outline',
            'description' => 'Perlu diperiksa',
            'color' => 'warning',
        ],
        [
            'label' => 'Terverifikasi',
            'value' => '88',
            'icon' => 'mdi-check-decagram-outline',
            'description' => 'Data telah diperiksa',
            'color' => 'success',
        ],
        [
            'label' => 'Diterima',
            'value' => '0',
            'icon' => 'mdi-account-check-outline',
            'description' => 'Belum ada hasil seleksi',
            'color' => 'info',
        ],
    ];

    $jalurPendaftaran = [
        ['nama' => 'Reguler', 'jumlah' => 82, 'persentase' => 68, 'color' => 'primary'],
        ['nama' => 'Prestasi', 'jumlah' => 23, 'persentase' => 19, 'color' => 'warning'],
        ['nama' => 'Afirmasi', 'jumlah' => 15, 'persentase' => 13, 'color' => 'success'],
    ];

    $pendaftarTerbaru = [
        [
            'nomor' => 'PPDB-2027-0120',
            'nama' => 'Ahmad Fathan Pratama',
            'jalur' => 'Reguler',
            'tanggal' => '08 Okt 2026',
            'sumber' => 'Online',
            'status' => 'Menunggu Verifikasi',
            'badge' => 'warning',
        ],
        [
            'nomor' => 'PPDB-2027-0119',
            'nama' => 'Siti Aisyah Putri',
            'jalur' => 'Prestasi',
            'tanggal' => '08 Okt 2026',
            'sumber' => 'Dibantu Panitia',
            'status' => 'Terverifikasi',
            'badge' => 'success',
        ],
        [
            'nomor' => 'PPDB-2027-0118',
            'nama' => 'Muhammad Rizky Ramadhan',
            'jalur' => 'Reguler',
            'tanggal' => '07 Okt 2026',
            'sumber' => 'Online',
            'status' => 'Perlu Perbaikan',
            'badge' => 'danger',
        ],
        [
            'nomor' => 'PPDB-2027-0117',
            'nama' => 'Nabila Zahra',
            'jalur' => 'Afirmasi',
            'tanggal' => '07 Okt 2026',
            'sumber' => 'Online',
            'status' => 'Terverifikasi',
            'badge' => 'success',
        ],
        [
            'nomor' => 'PPDB-2027-0116',
            'nama' => 'Farhan Al-Faruq',
            'jalur' => 'Reguler',
            'tanggal' => '06 Okt 2026',
            'sumber' => 'Dibantu Panitia',
            'status' => 'Menunggu Verifikasi',
            'badge' => 'warning',
        ],
    ];
@endphp

@push('info-page')
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
                <a href="{{ route('portal') }}">Portal</a>
            </li>
            <li class="breadcrumb-item active">{{ $title }}</li>
        </ol>
    </div>
@endpush

<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">

        {{-- Header halaman --}}
        <div class="row mb-3 pb-1">
            <div class="col-12">
                <div class="d-flex align-items-lg-center flex-lg-row flex-column gap-3">
                    <div class="flex-grow-1">
                        <h4 class="fs-16 mb-1">{{ $title }}</h4>
                        <p class="text-muted mb-0">
                            Ringkasan dan pemantauan Penerimaan Peserta Didik Baru.
                        </p>
                    </div>

                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                            <i class="mdi mdi-calendar-outline me-1"></i>
                            Tahun Ajaran 2027/2028
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Informasi periode PPDB --}}
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body p-3 p-lg-4">
                        <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-3">
                            <div class="avatar-md flex-shrink-0">
                                <div class="avatar-title bg-primary-subtle text-primary rounded-3 fs-3">
                                    <i class="mdi mdi-calendar-check-outline"></i>
                                </div>
                            </div>

                            <div class="flex-grow-1">
                                <h5 class="fw-bold mb-1">PPDB Tahun Ajaran 2027/2028</h5>
                                <p class="text-muted mb-0">
                                    Periode pendaftaran 1 Oktober – 30 November 2026.
                                    Pantau pendaftaran dan selesaikan verifikasi secara berkala.
                                </p>
                            </div>

                            <div class="text-lg-end">
                                <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                                    Periode Berlangsung
                                </span>
                                <div class="small text-muted mt-2">
                                    Kuota: 120 calon siswa
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistik utama --}}
        <div class="row">
            @foreach ($statistik as $item)
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <div>
                                    <p class="text-muted mb-2">
                                        {{ $item['label'] }}
                                    </p>
                                    <h4 class="fw-bold mb-1">
                                        {{ $item['value'] }}
                                    </h4>
                                    <small class="text-muted">
                                        {{ $item['description'] }}
                                    </small>
                                </div>

                                <div class="avatar-md flex-shrink-0">
                                    <div class="avatar-title bg-{{ $item['color'] }}-subtle text-{{ $item['color'] }} rounded-3 fs-3">
                                        <i class="mdi {{ $item['icon'] }}"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row">

            {{-- Antrean pekerjaan --}}
            <div class="col-xl-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-transparent border-bottom">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-sm">
                                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                    <i class="mdi mdi-clipboard-list-outline"></i>
                                </div>
                            </div>
                            <div>
                                <h5 class="card-title mb-1">Perlu Ditindaklanjuti</h5>
                                <p class="text-muted small mb-0">
                                    Pekerjaan yang membutuhkan perhatian panitia.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="d-flex align-items-start gap-3 mb-4">
                            <div class="avatar-sm flex-shrink-0">
                                <div class="avatar-title bg-warning-subtle text-warning rounded-circle">
                                    <i class="mdi mdi-file-clock-outline fs-20"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1">24 pendaftaran</h5>
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

                        <div class="d-flex align-items-start gap-3 mb-4">
                            <div class="avatar-sm flex-shrink-0">
                                <div class="avatar-title bg-danger-subtle text-danger rounded-circle">
                                    <i class="mdi mdi-file-alert-outline fs-20"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1">8 pendaftaran</h5>
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
                    </div>
                </div>
            </div>

            {{-- Distribusi jalur --}}
            <div class="col-xl-8">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-transparent border-bottom">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-sm">
                                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                    <i class="mdi mdi-chart-donut"></i>
                                </div>
                            </div>
                            <div>
                                <h5 class="card-title mb-1">Distribusi Jalur Pendaftaran</h5>
                                <p class="text-muted small mb-0">
                                    Perbandingan jumlah calon siswa berdasarkan jalur.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="d-flex flex-column gap-4">
                            @foreach ($jalurPendaftaran as $jalur)
                                <div>
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="fw-medium">{{ $jalur['nama'] }}</span>
                                        <span class="text-muted small">
                                            {{ $jalur['jumlah'] }} pendaftar
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
                                    Total 120 pendaftar dari seluruh jalur.
                                    Pastikan kuota dan persyaratan setiap jalur sesuai
                                    dengan ketentuan sekolah.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pendaftaran terbaru --}}
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-transparent border-bottom">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-sm">
                                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                        <i class="mdi mdi-account-multiple-outline"></i>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="card-title mb-1">Pendaftaran Terbaru</h5>
                                    <p class="text-muted small mb-0">
                                        Daftar calon siswa yang terakhir mendaftar.
                                    </p>
                                </div>
                            </div>

                            <a href="javascript:void(0)"
                                class="btn btn-outline-primary rounded-pill">
                                Lihat Semua Pendaftar
                                <i class="mdi mdi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
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
                                            <td>
                                                <span class="text-muted small">
                                                    {{ $pendaftar['sumber'] }}
                                                </span>
                                            </td>
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
                                                    aria-label="Lihat detail">
                                                    <i class="mdi mdi-eye-outline fs-16"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection