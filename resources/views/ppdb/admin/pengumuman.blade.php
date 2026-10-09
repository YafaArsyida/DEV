
@extends('template_ppdb.v_template')

@section('content')
@php
    $title = 'Pengumuman Hasil Seleksi';

    $statistik = [
        [
            'label' => 'Diterima',
            'jumlah' => 85,
            'icon' => 'mdi-account-check-outline',
            'warna' => 'success',
        ],
        [
            'label' => 'Cadangan',
            'jumlah' => 10,
            'icon' => 'mdi-account-clock-outline',
            'warna' => 'warning',
        ],
        [
            'label' => 'Tidak Diterima',
            'jumlah' => 25,
            'icon' => 'mdi-account-remove-outline',
            'warna' => 'danger',
        ],
    ];

    $penerima = [
        [
            'nomor' => 'PPDB-2027-0120',
            'nama' => 'Ahmad Fathan Pratama',
            'jalur' => 'Reguler',
            'tanggal' => '08 Okt 2026',
            'status' => 'Diterima',
            'warna' => 'success',
        ],
        [
            'nomor' => 'PPDB-2027-0119',
            'nama' => 'Siti Aisyah Putri',
            'jalur' => 'Prestasi',
            'tanggal' => '08 Okt 2026',
            'status' => 'Diterima',
            'warna' => 'success',
        ],
        [
            'nomor' => 'PPDB-2027-0117',
            'nama' => 'Nabila Zahra',
            'jalur' => 'Afirmasi',
            'tanggal' => '07 Okt 2026',
            'status' => 'Cadangan',
            'warna' => 'warning',
        ],
        [
            'nomor' => 'PPDB-2027-0114',
            'nama' => 'Farhan Al-Faruq',
            'jalur' => 'Reguler',
            'tanggal' => '06 Okt 2026',
            'status' => 'Diterima',
            'warna' => 'success',
        ],
        [
            'nomor' => 'PPDB-2027-0110',
            'nama' => 'Muhammad Rizky Ramadhan',
            'jalur' => 'Reguler',
            'tanggal' => '05 Okt 2026',
            'status' => 'Tidak Diterima',
            'warna' => 'danger',
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
                            <li class="breadcrumb-item active">Pengumuman</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        {{-- Status publikasi --}}
        <div class="card rounded-4 border shadow-none mb-4">
            <div class="card-body p-4">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-md flex-shrink-0">
                            <div class="avatar-title bg-warning-subtle text-warning rounded-4 fs-3">
                                <i class="mdi mdi-bullhorn-outline"></i>
                            </div>
                        </div>

                        <div>
                            <h5 class="mb-1">Pengumuman PPDB Tahun Ajaran 2027/2028</h5>
                            <p class="text-muted mb-0">
                                Kelola dan publikasikan hasil seleksi kepada calon siswa.
                            </p>
                        </div>
                    </div>

                    <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2">
                        <i class="mdi mdi-clock-outline me-1"></i>
                        Belum Dipublikasikan
                    </span>
                </div>
            </div>
        </div>

        {{-- Statistik hasil --}}
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

        {{-- Daftar hasil seleksi --}}
        <div class="card rounded-4 border shadow-none">
            <div class="card-header bg-transparent border-bottom p-4">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div>
                        <h5 class="card-title mb-1">Daftar Hasil Seleksi</h5>
                        <p class="text-muted mb-0">
                            Daftar calon siswa berdasarkan hasil seleksi PPDB.
                        </p>
                    </div>

                    <button type="button" class="btn btn-light rounded-3" disabled>
                        <i class="mdi mdi-download-outline me-1"></i>
                        Ekspor Hasil
                    </button>
                </div>
            </div>

            {{-- Filter --}}
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
                        <label class="form-label">Status hasil</label>
                        <select class="form-select rounded-3" disabled>
                            <option>Semua status</option>
                            <option>Diterima</option>
                            <option>Cadangan</option>
                            <option>Tidak Diterima</option>
                        </select>
                    </div>

                    <div class="col-xl-2 col-md-6">
                        <label class="form-label">Jalur pendaftaran</label>
                        <select class="form-select rounded-3" disabled>
                            <option>Semua jalur</option>
                            <option>Reguler</option>
                            <option>Prestasi</option>
                            <option>Afirmasi</option>
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
                                <th>Jalur</th>
                                <th>Tanggal Pendaftaran</th>
                                <th>Status Hasil</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($penerima as $index => $item)
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
                                            </div>
                                        </div>
                                    </td>

                                    <td>{{ $item['jalur'] }}</td>

                                    <td>{{ $item['tanggal'] }}</td>

                                    <td>
                                        <span class="badge bg-{{ $item['warna'] }}-subtle text-{{ $item['warna'] }} rounded-pill px-3 py-2">
                                            {{ $item['status'] }}
                                        </span>
                                    </td>

                                    <td class="text-end pe-4">
                                        <button type="button"
                                            class="btn btn-sm btn-soft-primary rounded-3"
                                            title="Lihat detail hasil"
                                            disabled>
                                            <i class="mdi mdi-eye-outline me-1"></i>
                                            Detail
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination statis --}}
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 p-4">
                    <p class="text-muted mb-0 fs-13">
                        Menampilkan 5 dari 120 pendaftar.
                    </p>

                    <nav aria-label="Navigasi halaman">
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item disabled">
                                <a class="page-link" href="javascript:void(0)">
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
                                <a class="page-link" href="javascript:void(0)">
                                    <i class="mdi mdi-chevron-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        {{-- Pengaturan publikasi --}}
        <div class="card rounded-4 border shadow-none">
            <div class="card-header bg-transparent border-bottom p-4">
                <h5 class="card-title mb-1">Pengaturan Publikasi</h5>
                <p class="text-muted mb-0">
                    Atur jadwal dan informasi yang ditampilkan saat hasil diumumkan.
                </p>
            </div>

            <div class="card-body p-4">
                <div class="alert alert-info rounded-3">
                    <i class="mdi mdi-information-outline me-2"></i>
                    Pastikan hasil seleksi telah ditinjau dan disetujui pihak sekolah
                    sebelum dipublikasikan.
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Periode PPDB</label>
                        <select class="form-select rounded-3" disabled>
                            <option>PPDB Tahun Ajaran 2027/2028</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Tanggal pengumuman</label>
                        <input type="date" class="form-control rounded-3" disabled>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Pesan untuk pendaftar</label>
                        <textarea class="form-control rounded-3" rows="4"
                            placeholder="Tuliskan informasi hasil seleksi, jadwal daftar ulang, dan petunjuk berikutnya..."
                            disabled></textarea>
                        <div class="form-text">
                            Pesan ini nantinya dapat ditampilkan pada portal pendaftar.
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-wrap justify-content-end gap-2 mt-4">
                    <button type="button" class="btn btn-light rounded-3" disabled>
                        <i class="mdi mdi-content-save-outline me-1"></i>
                        Simpan Draf
                    </button>

                    <button type="button" class="btn btn-primary rounded-3" disabled>
                        <i class="mdi mdi-bullhorn-outline me-1"></i>
                        Publikasikan Hasil
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection