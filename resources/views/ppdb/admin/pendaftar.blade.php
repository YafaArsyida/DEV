@extends('template_ppdb.v_template')

@section('content')

@php
    $title = 'Semua Pendaftar';

    // Data dummy untuk prototipe UI.
    $statistik = [
        [
            'label' => 'Total Pendaftar',
            'jumlah' => 120,
            'icon' => 'mdi-account-group-outline',
            'color' => 'primary',
        ],
        [
            'label' => 'Menunggu Verifikasi',
            'jumlah' => 24,
            'icon' => 'mdi-file-clock-outline',
            'color' => 'warning',
        ],
        [
            'label' => 'Terverifikasi',
            'jumlah' => 88,
            'icon' => 'mdi-check-decagram-outline',
            'color' => 'success',
        ],
        [
            'label' => 'Perlu Perbaikan',
            'jumlah' => 8,
            'icon' => 'mdi-file-alert-outline',
            'color' => 'danger',
        ],
    ];

    $pendaftar = [
        [
            'nomor' => 'PPDB-2027-0120',
            'nama' => 'Ahmad Fathan Pratama',
            'orang_tua' => 'Budi Santoso',
            'jalur' => 'Reguler',
            'sumber' => 'Online',
            'tanggal' => '08 Okt 2026',
            'status' => 'Menunggu Verifikasi',
            'badge' => 'warning',
        ],
        [
            'nomor' => 'PPDB-2027-0119',
            'nama' => 'Siti Aisyah Putri',
            'orang_tua' => 'Siti Rahmawati',
            'jalur' => 'Prestasi',
            'sumber' => 'Dibantu Panitia',
            'tanggal' => '08 Okt 2026',
            'status' => 'Terverifikasi',
            'badge' => 'success',
        ],
        [
            'nomor' => 'PPDB-2027-0118',
            'nama' => 'Muhammad Rizky Ramadhan',
            'orang_tua' => 'Ahmad Fauzi',
            'jalur' => 'Reguler',
            'sumber' => 'Online',
            'tanggal' => '07 Okt 2026',
            'status' => 'Perlu Perbaikan',
            'badge' => 'danger',
        ],
        [
            'nomor' => 'PPDB-2027-0117',
            'nama' => 'Nabila Zahra',
            'orang_tua' => 'Dewi Anggraini',
            'jalur' => 'Afirmasi',
            'sumber' => 'Online',
            'tanggal' => '07 Okt 2026',
            'status' => 'Terverifikasi',
            'badge' => 'success',
        ],
        [
            'nomor' => 'PPDB-2027-0116',
            'nama' => 'Farhan Al-Faruq',
            'orang_tua' => 'Hendra Setiawan',
            'jalur' => 'Reguler',
            'sumber' => 'Dibantu Panitia',
            'tanggal' => '06 Okt 2026',
            'status' => 'Menunggu Verifikasi',
            'badge' => 'warning',
        ],
    ];
@endphp

<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">

        {{-- Header halaman --}}
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mb-sm-0">{{ $title }}</h4>
                        <p class="text-muted mb-0 mt-1">
                            Kelola dan pantau seluruh pendaftaran calon peserta didik.
                        </p>
                    </div>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">PPDB</li>
                            <li class="breadcrumb-item active">{{ $title }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistik --}}
        <div class="row">
            @foreach ($statistik as $item)
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <div>
                                    <p class="text-muted mb-2">{{ $item['label'] }}</p>
                                    <h4 class="fw-bold mb-0">{{ $item['jumlah'] }}</h4>
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

        {{-- Tabel pendaftar --}}
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

                    {{-- Header kartu --}}
                    <div class="card-header bg-transparent border-bottom">
                        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-sm">
                                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                        <i class="mdi mdi-account-multiple-outline"></i>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="card-title mb-1">Data Pendaftar</h5>
                                    <p class="text-muted small mb-0">
                                        Tahun Ajaran 2027/2028
                                    </p>
                                </div>
                            </div>

                            <div class="d-flex flex-wrap gap-2">
                                <button type="button"
                                    class="btn btn-outline-primary rounded-pill"
                                    disabled>
                                    <i class="mdi mdi-download-outline me-1"></i>
                                    Ekspor Data
                                </button>

                                <button type="button"
                                    class="btn btn-primary rounded-pill"
                                    disabled>
                                    <i class="mdi mdi-account-plus-outline me-1"></i>
                                    Bantu Pendaftaran
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Pencarian dan filter --}}
                    <div class="card-body border-bottom">
                        <div class="row g-3">
                            <div class="col-xl-4 col-md-6">
                                <label class="form-label">Cari Pendaftar</label>
                                <div class="search-box">
                                    <input
                                        type="search"
                                        class="form-control"
                                        placeholder="Nama atau nomor pendaftaran"
                                        disabled>
                                    <i class="mdi mdi-magnify search-icon"></i>
                                </div>
                            </div>

                            <div class="col-xl-2 col-md-6">
                                <label class="form-label">Status</label>
                                <select class="form-select" disabled>
                                    <option selected>Semua Status</option>
                                    <option>Menunggu Verifikasi</option>
                                    <option>Terverifikasi</option>
                                    <option>Perlu Perbaikan</option>
                                </select>
                            </div>

                            <div class="col-xl-2 col-md-6">
                                <label class="form-label">Jalur</label>
                                <select class="form-select" disabled>
                                    <option selected>Semua Jalur</option>
                                    <option>Reguler</option>
                                    <option>Prestasi</option>
                                    <option>Afirmasi</option>
                                </select>
                            </div>

                            <div class="col-xl-2 col-md-6">
                                <label class="form-label">Sumber</label>
                                <select class="form-select" disabled>
                                    <option selected>Semua Sumber</option>
                                    <option>Online</option>
                                    <option>Dibantu Panitia</option>
                                </select>
                            </div>

                            <div class="col-xl-2 col-md-12 d-flex align-items-end">
                                <button type="button"
                                    class="btn btn-light w-100"
                                    disabled>
                                    <i class="mdi mdi-filter-outline me-1"></i>
                                    Filter
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Tabel --}}
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 50px;">No.</th>
                                        <th>Calon Siswa</th>
                                        <th>Jalur</th>
                                        <th>Sumber Pendaftaran</th>
                                        <th>Tanggal Daftar</th>
                                        <th>Status</th>
                                        <th class="text-center" style="width: 80px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pendaftar as $index => $item)
                                        <tr>
                                            <td class="text-center text-muted">
                                                {{ $index + 1 }}
                                            </td>

                                            <td>
                                                <h6 class="fw-semibold mb-1">
                                                    {{ $item['nama'] }}
                                                </h6>
                                                <span class="text-muted small">
                                                    {{ $item['nomor'] }}
                                                </span>
                                            </td>

                                            <td>{{ $item['jalur'] }}</td>

                                            <td>
                                                @if ($item['sumber'] === 'Online')
                                                    <span class="text-muted">
                                                        <i class="mdi mdi-web me-1"></i>
                                                        Online
                                                    </span>
                                                @else
                                                    <span class="text-muted">
                                                        <i class="mdi mdi-account-edit-outline me-1"></i>
                                                        Dibantu Panitia
                                                    </span>
                                                @endif
                                            </td>

                                            <td>{{ $item['tanggal'] }}</td>

                                            <td>
                                                <span class="badge bg-{{ $item['badge'] }}-subtle text-{{ $item['badge'] }} rounded-pill px-3 py-2">
                                                    {{ $item['status'] }}
                                                </span>
                                            </td>

                                            <td class="text-center">
                                                <button
                                                    type="button"
                                                    class="btn btn-sm btn-light rounded-circle"
                                                    title="Lihat detail"
                                                    aria-label="Lihat detail"
                                                    disabled>
                                                    <i class="mdi mdi-eye-outline fs-16"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination statis --}}
                        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-4">
                            <div class="text-muted small">
                                Menampilkan 1–5 dari 120 pendaftar
                            </div>

                            <nav aria-label="Navigasi halaman pendaftar">
                                <ul class="pagination pagination-sm mb-0">
                                    <li class="page-item disabled">
                                        <span class="page-link">
                                            <i class="mdi mdi-chevron-left"></i>
                                        </span>
                                    </li>
                                    <li class="page-item active">
                                        <span class="page-link">1</span>
                                    </li>
                                    <li class="page-item">
                                        <span class="page-link">2</span>
                                    </li>
                                    <li class="page-item">
                                        <span class="page-link">3</span>
                                    </li>
                                    <li class="page-item">
                                        <span class="page-link">
                                            <i class="mdi mdi-chevron-right"></i>
                                        </span>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

@endsection