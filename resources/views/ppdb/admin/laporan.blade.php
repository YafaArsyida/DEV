@extends('template_ppdb.v_template')

@section('content')
@php
    $title = 'Laporan PPDB';

    // Data ilustrasi untuk rancangan UI.
    // Nantinya ganti dengan data hasil query database.
    $statistik = [
        [
            'label' => 'Total Pendaftar',
            'nilai' => '120',
            'keterangan' => 'Seluruh pendaftar',
            'icon' => 'ri-user-add-line',
            'warna' => 'primary',
        ],
        [
            'label' => 'Terverifikasi',
            'nilai' => '88',
            'keterangan' => '73,3% dari pendaftar',
            'icon' => 'ri-checkbox-circle-line',
            'warna' => 'success',
        ],
        [
            'label' => 'Diterima',
            'nilai' => '85',
            'keterangan' => 'Hasil seleksi',
            'icon' => 'ri-user-follow-line',
            'warna' => 'info',
        ],
        [
            'label' => 'Daftar Ulang',
            'nilai' => '0',
            'keterangan' => 'Menunggu proses daftar ulang',
            'icon' => 'ri-file-check-line',
            'warna' => 'warning',
        ],
    ];

    $rekap = [
        [
            'jenjang' => 'Semua Jenjang',
            'pendaftar' => 120,
            'verifikasi' => 88,
            'diterima' => 85,
            'daftar_ulang' => 0,
        ],
        [
            'jenjang' => 'Jenjang A',
            'pendaftar' => 70,
            'verifikasi' => 52,
            'diterima' => 50,
            'daftar_ulang' => 0,
        ],
        [
            'jenjang' => 'Jenjang B',
            'pendaftar' => 50,
            'verifikasi' => 36,
            'diterima' => 35,
            'daftar_ulang' => 0,
        ],
    ];

    $statusPendaftaran = [
        ['label' => 'Menunggu Verifikasi', 'jumlah' => 24, 'warna' => 'warning'],
        ['label' => 'Terverifikasi', 'jumlah' => 88, 'warna' => 'success'],
        ['label' => 'Perlu Perbaikan', 'jumlah' => 8, 'warna' => 'danger'],
    ];

    $hasilSeleksi = [
        ['label' => 'Diterima', 'jumlah' => 85, 'warna' => 'success'],
        ['label' => 'Cadangan', 'jumlah' => 10, 'warna' => 'warning'],
        ['label' => 'Tidak Diterima', 'jumlah' => 25, 'warna' => 'danger'],
    ];
@endphp

<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">

        {{-- Page title --}}
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mb-sm-1">{{ $title }}</h4>
                        <p class="text-muted mb-0">
                            Ringkasan dan rekapitulasi pelaksanaan penerimaan peserta didik baru.
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

        {{-- Filter laporan --}}
        <div class="card rounded-4 border shadow-none mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-3">
                            <i class="ri-filter-3-line fs-18"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="mb-1">Filter Laporan</h5>
                        <p class="text-muted mb-0 small">
                            Tentukan periode dan rentang tanggal laporan.
                        </p>
                    </div>
                </div>

                <div class="row align-items-end g-3">
                    <div class="col-lg-4 col-md-6">
                        <label for="periode_ppdb" class="form-label">
                            Periode PPDB
                        </label>
                        <select id="periode_ppdb" class="form-select rounded-3" disabled>
                            <option selected>PPDB Tahun Ajaran 2027/2028</option>
                        </select>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label for="tanggal_mulai" class="form-label">
                            Tanggal Mulai
                        </label>
                        <input type="date" id="tanggal_mulai"
                            class="form-control rounded-3" value="2026-10-01" disabled>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label for="tanggal_akhir" class="form-label">
                            Tanggal Akhir
                        </label>
                        <input type="date" id="tanggal_akhir"
                            class="form-control rounded-3" value="2026-11-30" disabled>
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <button type="button" class="btn btn-primary rounded-3 w-100" disabled>
                            <i class="ri-search-line me-1"></i> Tampilkan
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistik utama --}}
        <div class="row">
            @foreach ($statistik as $item)
                <div class="col-xl-3 col-md-6">
                    <div class="card rounded-4 border shadow-none">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start justify-content-between gap-3">
                                <div>
                                    <p class="text-muted mb-2">{{ $item['label'] }}</p>
                                    <h3 class="mb-2 fw-semibold">{{ $item['nilai'] }}</h3>
                                    <p class="text-muted mb-0 small">
                                        {{ $item['keterangan'] }}
                                    </p>
                                </div>

                                <div class="avatar-sm flex-shrink-0">
                                    <div class="avatar-title bg-{{ $item['warna'] }}-subtle
                                        text-{{ $item['warna'] }} rounded-3 fs-20">
                                        <i class="{{ $item['icon'] }}"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Rekapitulasi per jenjang --}}
        <div class="row">
            <div class="col-xl-8">
                <div class="card rounded-4 border shadow-none">
                    <div class="card-header bg-transparent border-bottom p-4">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <h5 class="card-title mb-1">Rekapitulasi Pendaftar</h5>
                                <p class="text-muted mb-0 small">
                                    Perbandingan pendaftaran, verifikasi, dan penerimaan per jenjang.
                                </p>
                            </div>
                            <span class="badge bg-light text-muted rounded-pill">
                                Tahun Ajaran 2027/2028
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Jenjang</th>
                                        <th class="text-center">Pendaftar</th>
                                        <th class="text-center">Terverifikasi</th>
                                        <th class="text-center">Diterima</th>
                                        <th class="text-center pe-4">Daftar Ulang</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rekap as $item)
                                        <tr>
                                            <td class="ps-4">
                                                <span class="fw-medium">{{ $item['jenjang'] }}</span>
                                            </td>
                                            <td class="text-center">{{ $item['pendaftar'] }}</td>
                                            <td class="text-center">{{ $item['verifikasi'] }}</td>
                                            <td class="text-center">{{ $item['diterima'] }}</td>
                                            <td class="text-center pe-4">{{ $item['daftar_ulang'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer bg-transparent border-top p-3">
                        <p class="text-muted small mb-0">
                            <i class="ri-information-line me-1"></i>
                            Angka pada halaman ini merupakan data ilustrasi.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Unduh laporan --}}
            <div class="col-xl-4">
                <div class="card rounded-4 border shadow-none">
                    <div class="card-header bg-transparent border-bottom p-4">
                        <h5 class="card-title mb-1">Unduh Laporan</h5>
                        <p class="text-muted mb-0 small">
                            Simpan rekap untuk dokumentasi dan evaluasi sekolah.
                        </p>
                    </div>

                    <div class="card-body p-4">
                        <div class="d-flex align-items-start gap-3 mb-4">
                            <div class="avatar-sm flex-shrink-0">
                                <div class="avatar-title bg-success-subtle text-success rounded-3 fs-20">
                                    <i class="ri-file-excel-2-line"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Excel (.xlsx)</h6>
                                <p class="text-muted small mb-2">
                                    Untuk pengolahan data dan rekap lanjutan.
                                </p>
                                <button type="button" class="btn btn-soft-success btn-sm rounded-3" disabled>
                                    <i class="ri-download-2-line me-1"></i> Unduh Excel
                                </button>
                            </div>
                        </div>

                        <div class="d-flex align-items-start gap-3">
                            <div class="avatar-sm flex-shrink-0">
                                <div class="avatar-title bg-danger-subtle text-danger rounded-3 fs-20">
                                    <i class="ri-file-pdf-line"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">PDF</h6>
                                <p class="text-muted small mb-2">
                                    Untuk arsip dan laporan resmi kepada kepala sekolah.
                                </p>
                                <button type="button" class="btn btn-soft-danger btn-sm rounded-3" disabled>
                                    <i class="ri-download-2-line me-1"></i> Unduh PDF
                                </button>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex align-items-start gap-2">
                            <i class="ri-information-line text-muted fs-18"></i>
                            <p class="text-muted small mb-0">
                                Laporan akan mengikuti periode dan tanggal yang dipilih setelah fitur tersedia.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistik status --}}
        <div class="row">
            <div class="col-xl-6">
                <div class="card rounded-4 border shadow-none">
                    <div class="card-header bg-transparent border-bottom p-4">
                        <h5 class="card-title mb-1">Status Pendaftaran</h5>
                        <p class="text-muted small mb-0">
                            Ringkasan proses pemeriksaan berkas calon siswa.
                        </p>
                    </div>

                    <div class="card-body p-4">
                        @foreach ($statusPendaftaran as $item)
                            <div class="mb-4 {{ $loop->last ? 'mb-0' : '' }}">
                                <div class="d-flex align-items-center justify-content-between gap-3 mb-2">
                                    <span class="text-muted">{{ $item['label'] }}</span>
                                    <span class="fw-medium">{{ $item['jumlah'] }}</span>
                                </div>
                                <div class="progress rounded-pill" style="height: 8px;">
                                    <div class="progress-bar bg-{{ $item['warna'] }}"
                                        role="progressbar"
                                        style="width: {{ ($item['jumlah'] / 120) * 100 }}%"
                                        aria-valuenow="{{ $item['jumlah'] }}"
                                        aria-valuemin="0"
                                        aria-valuemax="120">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-xl-6">
                <div class="card rounded-4 border shadow-none">
                    <div class="card-header bg-transparent border-bottom p-4">
                        <h5 class="card-title mb-1">Hasil Seleksi</h5>
                        <p class="text-muted small mb-0">
                            Ringkasan keputusan seleksi calon siswa.
                        </p>
                    </div>

                    <div class="card-body p-4">
                        @foreach ($hasilSeleksi as $item)
                            <div class="mb-4 {{ $loop->last ? 'mb-0' : '' }}">
                                <div class="d-flex align-items-center justify-content-between gap-3 mb-2">
                                    <span class="text-muted">{{ $item['label'] }}</span>
                                    <span class="fw-medium">{{ $item['jumlah'] }}</span>
                                </div>
                                <div class="progress rounded-pill" style="height: 8px;">
                                    <div class="progress-bar bg-{{ $item['warna'] }}"
                                        role="progressbar"
                                        style="width: {{ ($item['jumlah'] / 120) * 100 }}%"
                                        aria-valuenow="{{ $item['jumlah'] }}"
                                        aria-valuemin="0"
                                        aria-valuemax="120">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection