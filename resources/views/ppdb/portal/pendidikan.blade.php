@extends('template_portal_ppdb.v_template')

@section('title', 'Pendidikan')

@section('content')

<div class="page-content">

    <div class="container-fluid" style="max-width: 100%">

        {{-- Page Title --}}
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mb-sm-0">Data Pendidikan</h4>
                    </div>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('ppdb.dashboard') }}">PPDB</a>
                            </li>
                            <li class="breadcrumb-item active">
                                Pendidikan
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div class="row">
            <div class="col-xxl-12">

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

                    {{-- Main Header --}}
                    <div class="card-header">
                        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">

                            <div>
                                <div class="d-flex align-items-center gap-3">

                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                            <i class="ri-school-line"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <h5 class="fw-bold mb-1">Data Pendidikan</h5>
                                        <small class="text-muted">
                                            Lengkapi informasi pendidikan dan pilih jalur pendaftaran.
                                        </small>
                                    </div>

                                </div>
                            </div>

                            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                Langkah 4 dari 6
                            </span>

                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="card-body">

                        {{-- Progress --}}
                        <div class="card border shadow-none rounded-4 mb-4">
                            <div class="card-body">

                                <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-2">
                                    <div>
                                        <h6 class="fw-semibold mb-1">Progress Pendaftaran</h6>
                                        <small class="text-muted">
                                            Lengkapi asal pendidikan dan pilih jalur PPDB.
                                        </small>
                                    </div>

                                    <span class="text-primary fw-semibold">
                                        66%
                                    </span>
                                </div>

                                <div class="progress rounded-pill" style="height: 8px;">
                                    <div
                                        class="progress-bar bg-primary"
                                        role="progressbar"
                                        style="width: 66%;"
                                        aria-valuenow="66"
                                        aria-valuemin="0"
                                        aria-valuemax="100">
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap gap-3 mt-3">

                                    {{-- Step 01 --}}
                                    <div class="d-flex align-items-center gap-2 text-success">
                                        <div class="avatar-xs">
                                            <div class="avatar-title bg-success-subtle text-success rounded-circle fs-12">
                                                <i class="ri-check-line"></i>
                                            </div>
                                        </div>
                                        <span class="small">Data Siswa</span>
                                    </div>

                                    {{-- Step 02 --}}
                                    <div class="d-flex align-items-center gap-2 text-success">
                                        <div class="avatar-xs">
                                            <div class="avatar-title bg-success-subtle text-success rounded-circle fs-12">
                                                <i class="ri-check-line"></i>
                                            </div>
                                        </div>
                                        <span class="small">Orang Tua/Wali</span>
                                    </div>

                                    {{-- Step 03 --}}
                                    <div class="d-flex align-items-center gap-2 text-success">
                                        <div class="avatar-xs">
                                            <div class="avatar-title bg-success-subtle text-success rounded-circle fs-12">
                                                <i class="ri-check-line"></i>
                                            </div>
                                        </div>
                                        <span class="small">Alamat</span>
                                    </div>

                                    {{-- Step 04 --}}
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-xs">
                                            <div class="avatar-title bg-primary text-white rounded-circle fs-12">
                                                04
                                            </div>
                                        </div>
                                        <span class="small fw-semibold text-primary">
                                            Pendidikan
                                        </span>
                                    </div>

                                    {{-- Step 05 --}}
                                    <div class="d-flex align-items-center gap-2 text-muted">
                                        <div class="avatar-xs">
                                            <div class="avatar-title bg-light text-muted rounded-circle fs-12">
                                                05
                                            </div>
                                        </div>
                                        <span class="small">Berkas</span>
                                    </div>

                                    {{-- Step 06 --}}
                                    <div class="d-flex align-items-center gap-2 text-muted">
                                        <div class="avatar-xs">
                                            <div class="avatar-title bg-light text-muted rounded-circle fs-12">
                                                06
                                            </div>
                                        </div>
                                        <span class="small">Review</span>
                                    </div>

                                </div>

                            </div>
                        </div>

                        {{-- Asal Pendidikan --}}
                        <div class="card border shadow-none rounded-4 mb-4">

                            <div class="card-header bg-transparent">
                                <div class="d-flex align-items-center gap-3">

                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                            <i class="ri-school-line"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <h5 class="fw-bold mb-1">Asal Pendidikan</h5>
                                        <small class="text-muted">
                                            Informasi sekolah atau lembaga pendidikan sebelumnya.
                                        </small>
                                    </div>

                                </div>
                            </div>

                            <div class="card-body">

                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label class="form-label" for="asal-tk">
                                            Asal TK/RA
                                        </label>

                                        <input
                                            type="text"
                                            class="form-control"
                                            id="asal-tk"
                                            value="TK Islam Al-Hikmah">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="npsn">
                                            NPSN
                                        </label>

                                        <input
                                            type="text"
                                            class="form-control"
                                            id="npsn"
                                            value="20345678">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="tahun-lulus">
                                            Tahun Lulus
                                        </label>

                                        <select class="form-select" id="tahun-lulus">
                                            <option>2025</option>
                                            <option selected>2026</option>
                                            <option>2027</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="nomor-peserta">
                                            Nomor Peserta
                                        </label>

                                        <input
                                            type="text"
                                            class="form-control"
                                            id="nomor-peserta"
                                            value="TK-AH-2026-001">
                                    </div>

                                </div>

                            </div>

                        </div>

                        {{-- Pilihan Jalur --}}
                        <div class="card border shadow-none rounded-4">

                            <div class="card-header bg-transparent">
                                <div class="d-flex align-items-center gap-3">

                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                            <i class="ri-route-line"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <h5 class="fw-bold mb-1">Pilihan Jalur Pendaftaran</h5>
                                        <small class="text-muted">
                                            Pilih satu jalur yang sesuai dengan kondisi calon siswa.
                                        </small>
                                    </div>

                                </div>
                            </div>

                            <div class="card-body">

                                <div class="row g-3">

                                    {{-- Reguler --}}
                                    <div class="col-lg-4">
                                        <label
                                            for="jalur-reguler"
                                            class="card border shadow-none rounded-4 h-100 mb-0">

                                            <div class="card-body">

                                                <div class="d-flex align-items-start justify-content-between gap-3 mb-3">

                                                    <div class="avatar-sm">
                                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                                            <i class="ri-user-line"></i>
                                                        </div>
                                                    </div>

                                                    <div class="form-check">
                                                        <input
                                                            class="form-check-input"
                                                            type="radio"
                                                            name="jalur"
                                                            id="jalur-reguler"
                                                            value="reguler"
                                                            checked>
                                                    </div>

                                                </div>

                                                <h6 class="fw-bold mb-1">
                                                    Jalur Reguler
                                                </h6>

                                                <small class="text-muted d-block mb-3">
                                                    Untuk calon siswa umum
                                                </small>

                                                <p class="text-muted small mb-0">
                                                    Jalur utama dengan proses seleksi sesuai
                                                    ketentuan sekolah.
                                                </p>

                                            </div>

                                        </label>
                                    </div>

                                    {{-- Prestasi --}}
                                    <div class="col-lg-4">
                                        <label
                                            for="jalur-prestasi"
                                            class="card border shadow-none rounded-4 h-100 mb-0">

                                            <div class="card-body">

                                                <div class="d-flex align-items-start justify-content-between gap-3 mb-3">

                                                    <div class="avatar-sm">
                                                        <div class="avatar-title bg-warning-subtle text-warning rounded-circle fs-20">
                                                            <i class="ri-award-line"></i>
                                                        </div>
                                                    </div>

                                                    <div class="form-check">
                                                        <input
                                                            class="form-check-input"
                                                            type="radio"
                                                            name="jalur"
                                                            id="jalur-prestasi"
                                                            value="prestasi">
                                                    </div>

                                                </div>

                                                <h6 class="fw-bold mb-1">
                                                    Jalur Prestasi
                                                </h6>

                                                <small class="text-muted d-block mb-3">
                                                    Berdasarkan prestasi
                                                </small>

                                                <p class="text-muted small mb-0">
                                                    Diperuntukkan bagi calon siswa yang memiliki
                                                    prestasi unggulan sesuai ketentuan sekolah.
                                                </p>

                                            </div>

                                        </label>
                                    </div>

                                    {{-- Afirmasi --}}
                                    <div class="col-lg-4">
                                        <label
                                            for="jalur-afirmasi"
                                            class="card border shadow-none rounded-4 h-100 mb-0">

                                            <div class="card-body">

                                                <div class="d-flex align-items-start justify-content-between gap-3 mb-3">

                                                    <div class="avatar-sm">
                                                        <div class="avatar-title bg-success-subtle text-success rounded-circle fs-20">
                                                            <i class="ri-heart-line"></i>
                                                        </div>
                                                    </div>

                                                    <div class="form-check">
                                                        <input
                                                            class="form-check-input"
                                                            type="radio"
                                                            name="jalur"
                                                            id="jalur-afirmasi"
                                                            value="afirmasi">
                                                    </div>

                                                </div>

                                                <h6 class="fw-bold mb-1">
                                                    Jalur Afirmasi
                                                </h6>

                                                <small class="text-muted d-block mb-3">
                                                    Untuk kebutuhan khusus
                                                </small>

                                                <p class="text-muted small mb-0">
                                                    Berdasarkan kebutuhan dukungan sosial dan
                                                    pendidikan inklusif.
                                                </p>

                                            </div>

                                        </label>
                                    </div>

                                </div>

                                <div class="alert alert-primary border-0 rounded-3 mt-4 mb-0">
                                    <div class="d-flex gap-2">
                                        <i class="ri-information-line fs-18"></i>

                                        <div>
                                            <div class="fw-semibold mb-1">
                                                Perhatikan pilihan jalur
                                            </div>

                                            <small>
                                                Pastikan jalur yang dipilih sesuai dengan
                                                persyaratan yang ditetapkan oleh sekolah.
                                                Dokumen pendukung dapat diminta pada tahap
                                                pengumpulan berkas.
                                            </small>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                        {{-- Action --}}
                        <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 mt-4">

                            <a
                                href="{{ route('ppdb.pendaftaran.alamat') }}"
                                class="btn btn-outline-secondary rounded-pill px-4">
                                <i class="ri-arrow-left-line me-1"></i>
                                Kembali
                            </a>

                            <a
                                href="{{ route('ppdb.pendaftaran.berkas') }}"
                                class="btn btn-primary rounded-pill px-4">
                                Simpan & Lanjutkan
                                <i class="ri-arrow-right-line ms-1"></i>
                            </a>

                        </div>

                    </div>

                </div>

            </div>
        </div>

    </div>

</div>

@endsection