
@extends('template_ppdb.v_template')

@section('content')
@php
    $title = 'Bantu Pendaftaran';

    $steps = [
        ['number' => 1, 'title' => 'Data Siswa', 'icon' => 'mdi-account-school-outline'],
        ['number' => 2, 'title' => 'Orang Tua/Wali', 'icon' => 'mdi-account-group-outline'],
        ['number' => 3, 'title' => 'Alamat', 'icon' => 'mdi-map-marker-outline'],
        ['number' => 4, 'title' => 'Pendidikan', 'icon' => 'mdi-school-outline'],
        ['number' => 5, 'title' => 'Berkas', 'icon' => 'mdi-file-document-outline'],
        ['number' => 6, 'title' => 'Review', 'icon' => 'mdi-clipboard-check-outline'],
    ];
@endphp

<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{ $title }}</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">PPDB</li>
                            <li class="breadcrumb-item active">{{ $title }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        {{-- Informasi halaman --}}
        <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
            <i class="mdi mdi-information-outline me-2"></i>
            Gunakan formulir ini untuk membantu orang tua/wali yang mengalami
            kendala saat mendaftar secara mandiri. Pastikan data yang dimasukkan
            sudah sesuai dengan dokumen calon siswa.
        </div>

        <div class="row g-4">

            {{-- Formulir utama --}}
            <div class="col-xl-8 col-lg-7">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h5 class="card-title mb-1">Formulir Pendaftaran</h5>
                        <p class="text-muted mb-0">
                            PPDB Tahun Ajaran 2027/2028
                        </p>
                    </div>

                    <div class="card-body">

                        {{-- Informasi sumber pendaftaran --}}
                        <div class="d-flex align-items-start gap-3 p-3 bg-light rounded-3 mb-4">
                            <div class="avatar-sm flex-shrink-0">
                                <div class="avatar-title bg-primary-subtle text-primary rounded-3">
                                    <i class="mdi mdi-account-edit-outline fs-4"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-1">Pendaftaran Dibantu Petugas</h6>
                                <p class="text-muted mb-0 fs-13">
                                    Pendaftaran akan tercatat sebagai pendaftaran
                                    yang dibantu panitia, bukan pendaftaran mandiri.
                                </p>
                            </div>
                        </div>

                        {{-- Navigasi tahap --}}
                        <div class="row g-2 mb-4">
                            @foreach ($steps as $step)
                                <div class="col-6 col-md-4 col-xl-4">
                                    <div class="border rounded-3 p-3 h-100
                                        {{ $step['number'] === 1 ? 'border-primary bg-primary-subtle' : '' }}">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar-xs flex-shrink-0">
                                                <div class="avatar-title rounded-circle
                                                    {{ $step['number'] === 1 ? 'bg-primary text-white' : 'bg-light text-muted' }}">
                                                    {{ $step['number'] }}
                                                </div>
                                            </div>
                                            <div class="min-w-0">
                                                <span class="text-muted fs-12 d-block">
                                                    Tahap {{ $step['number'] }}
                                                </span>
                                                <span class="fw-medium fs-13">
                                                    {{ $step['title'] }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Tahap 1 --}}
                        <div class="mb-4">
                            <h5 class="mb-1">Data Calon Siswa</h5>
                            <p class="text-muted mb-0">
                                Isi identitas calon peserta didik sesuai dokumen.
                            </p>
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">
                                    Nama lengkap <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control"
                                    placeholder="Masukkan nama lengkap sesuai dokumen" disabled>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">NIK <span class="text-danger">*</span></label>
                                <input type="text" class="form-control"
                                    placeholder="16 digit NIK" disabled>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">NISN</label>
                                <input type="text" class="form-control"
                                    placeholder="Masukkan NISN jika tersedia" disabled>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Jenis kelamin <span class="text-danger">*</span></label>
                                <select class="form-select" disabled>
                                    <option value="">Pilih jenis kelamin</option>
                                    <option>Laki-laki</option>
                                    <option>Perempuan</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Agama</label>
                                <select class="form-select" disabled>
                                    <option value="">Pilih agama</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Tempat lahir <span class="text-danger">*</span></label>
                                <input type="text" class="form-control"
                                    placeholder="Kota/kabupaten kelahiran" disabled>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Tanggal lahir <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" disabled>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Jenjang yang dituju <span class="text-danger">*</span></label>
                                <select class="form-select" disabled>
                                    <option value="">Pilih jenjang</option>
                                    <option>SD</option>
                                    <option>SMP</option>
                                    <option>SMA/SMK</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Jalur pendaftaran <span class="text-danger">*</span></label>
                                <select class="form-select" disabled>
                                    <option value="">Pilih jalur pendaftaran</option>
                                    <option>Reguler</option>
                                    <option>Prestasi</option>
                                    <option>Afirmasi</option>
                                </select>
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- Kontak orang tua --}}
                        <div class="mb-3">
                            <h5 class="mb-1">Kontak Orang Tua/Wali</h5>
                            <p class="text-muted mb-0">
                                Digunakan untuk komunikasi terkait proses pendaftaran.
                            </p>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">
                                    Nama orang tua/wali <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control"
                                    placeholder="Masukkan nama orang tua/wali" disabled>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Nomor HP orang tua/wali <span class="text-danger">*</span>
                                </label>
                                <input type="tel" class="form-control"
                                    placeholder="Contoh: 08xxxxxxxxxx" disabled>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Email orang tua/wali</label>
                                <input type="email" class="form-control"
                                    placeholder="nama@email.com (opsional)" disabled>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex flex-wrap justify-content-between gap-2">
                            <button type="button" class="btn btn-light" disabled>
                                <i class="mdi mdi-refresh me-1"></i>
                                Reset Formulir
                            </button>

                            <button type="button" class="btn btn-primary" disabled>
                                Lanjut ke Data Orang Tua/Wali
                                <i class="mdi mdi-arrow-right ms-1"></i>
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Panel informasi --}}
            <div class="col-xl-4 col-lg-5">

                <div class="card">
                    <div class="card-body">
                        <div class="avatar-sm mb-3">
                            <div class="avatar-title bg-primary-subtle text-primary rounded-3">
                                <i class="mdi mdi-account-supervisor-outline fs-4"></i>
                            </div>
                        </div>

                        <h5 class="mb-2">Panduan Petugas</h5>
                        <p class="text-muted mb-3">
                            Bantu orang tua/wali mengisi formulir berdasarkan
                            informasi dan dokumen yang mereka berikan.
                        </p>

                        <div class="d-flex gap-2 mb-3">
                            <i class="mdi mdi-check-circle-outline text-success fs-5"></i>
                            <div>
                                <h6 class="mb-1">Periksa identitas</h6>
                                <p class="text-muted mb-0 fs-13">
                                    Pastikan nama dan NIK sesuai dokumen.
                                </p>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mb-3">
                            <i class="mdi mdi-check-circle-outline text-success fs-5"></i>
                            <div>
                                <h6 class="mb-1">Pastikan kontak aktif</h6>
                                <p class="text-muted mb-0 fs-13">
                                    Gunakan nomor orang tua/wali yang dapat dihubungi.
                                </p>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <i class="mdi mdi-check-circle-outline text-success fs-5"></i>
                            <div>
                                <h6 class="mb-1">Tetap melalui verifikasi</h6>
                                <p class="text-muted mb-0 fs-13">
                                    Pendaftaran yang dibantu petugas tetap harus
                                    melewati proses verifikasi seperti pendaftaran lain.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-sm flex-shrink-0">
                                <div class="avatar-title bg-warning-subtle text-warning rounded-3">
                                    <i class="mdi mdi-shield-lock-outline fs-4"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-1">Jaga kerahasiaan data</h6>
                                <p class="text-muted mb-0 fs-13">
                                    Gunakan data calon siswa hanya untuk keperluan
                                    PPDB dan ikuti kebijakan sekolah.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection