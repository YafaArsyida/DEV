@extends('template_portal_ppdb.v_template')

@section('title', 'Alamat')

@section('content')

<div class="page-content">

    <div class="container-fluid" style="max-width: 100%">

        {{-- Page Title --}}
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mb-sm-0">Data Alamat</h4>
                    </div>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('ppdb.dashboard') }}">PPDB</a>
                            </li>
                            <li class="breadcrumb-item active">
                                Alamat
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
                                            <i class="ri-map-pin-user-line"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <h5 class="fw-bold mb-1">Data Alamat</h5>
                                        <small class="text-muted">
                                            Lengkapi alamat tempat tinggal calon siswa.
                                        </small>
                                    </div>

                                </div>
                            </div>

                            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                Langkah 3 dari 6
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
                                            Anda sedang mengisi alamat tempat tinggal calon siswa.
                                        </small>
                                    </div>

                                    <span class="text-primary fw-semibold">
                                        50%
                                    </span>
                                </div>

                                <div class="progress rounded-pill" style="height: 8px;">
                                    <div
                                        class="progress-bar bg-primary"
                                        role="progressbar"
                                        style="width: 50%;"
                                        aria-valuenow="50"
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
                                        <span class="small">
                                            Data Siswa
                                        </span>
                                    </div>

                                    {{-- Step 02 --}}
                                    <div class="d-flex align-items-center gap-2 text-success">
                                        <div class="avatar-xs">
                                            <div class="avatar-title bg-success-subtle text-success rounded-circle fs-12">
                                                <i class="ri-check-line"></i>
                                            </div>
                                        </div>
                                        <span class="small">
                                            Orang Tua/Wali
                                        </span>
                                    </div>

                                    {{-- Step 03 --}}
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-xs">
                                            <div class="avatar-title bg-primary text-white rounded-circle fs-12">
                                                03
                                            </div>
                                        </div>
                                        <span class="small fw-semibold text-primary">
                                            Alamat
                                        </span>
                                    </div>

                                    {{-- Step 04 --}}
                                    <div class="d-flex align-items-center gap-2 text-muted">
                                        <div class="avatar-xs">
                                            <div class="avatar-title bg-light text-muted rounded-circle fs-12">
                                                04
                                            </div>
                                        </div>
                                        <span class="small">
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
                                        <span class="small">
                                            Berkas
                                        </span>
                                    </div>

                                    {{-- Step 06 --}}
                                    <div class="d-flex align-items-center gap-2 text-muted">
                                        <div class="avatar-xs">
                                            <div class="avatar-title bg-light text-muted rounded-circle fs-12">
                                                06
                                            </div>
                                        </div>
                                        <span class="small">
                                            Review
                                        </span>
                                    </div>

                                </div>

                            </div>
                        </div>

                        {{-- Address Form --}}
                        <div class="card border shadow-none rounded-4">

                            <div class="card-header bg-transparent">
                                <div class="d-flex align-items-center gap-3">

                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                            <i class="ri-home-4-line"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <h5 class="fw-bold mb-1">Alamat Tempat Tinggal</h5>
                                        <small class="text-muted">
                                            Masukkan alamat sesuai dengan tempat tinggal saat ini.
                                        </small>
                                    </div>

                                </div>
                            </div>

                            <div class="card-body">

                                <div class="row g-3">

                                    {{-- Alamat Lengkap --}}
                                    <div class="col-12">
                                        <label class="form-label" for="alamat-lengkap">
                                            Alamat Lengkap
                                        </label>

                                        <textarea
                                            class="form-control"
                                            id="alamat-lengkap"
                                            rows="3">Jl. Menoreh No. 18</textarea>

                                        <small class="text-muted">
                                            Cantumkan nama jalan, nomor rumah, atau keterangan alamat lainnya.
                                        </small>
                                    </div>

                                    {{-- Provinsi --}}
                                    <div class="col-md-6">
                                        <label class="form-label" for="provinsi">
                                            Provinsi
                                        </label>

                                        <select class="form-select" id="provinsi">
                                            <option selected>Jawa Tengah</option>
                                            <option>Jawa Barat</option>
                                            <option>Jawa Timur</option>
                                        </select>
                                    </div>

                                    {{-- Kabupaten/Kota --}}
                                    <div class="col-md-6">
                                        <label class="form-label" for="kabupaten">
                                            Kabupaten/Kota
                                        </label>

                                        <select class="form-select" id="kabupaten">
                                            <option selected>Kota Semarang</option>
                                            <option>Kabupaten Semarang</option>
                                            <option>Demak</option>
                                            <option>Salatiga</option>
                                        </select>
                                    </div>

                                    {{-- Kecamatan --}}
                                    <div class="col-md-6">
                                        <label class="form-label" for="kecamatan">
                                            Kecamatan
                                        </label>

                                        <input
                                            type="text"
                                            class="form-control"
                                            id="kecamatan"
                                            value="Candisari">
                                    </div>

                                    {{-- Desa/Kelurahan --}}
                                    <div class="col-md-6">
                                        <label class="form-label" for="desa">
                                            Desa/Kelurahan
                                        </label>

                                        <input
                                            type="text"
                                            class="form-control"
                                            id="desa"
                                            value="Tlogosari">
                                    </div>

                                    {{-- RT --}}
                                    <div class="col-md-4">
                                        <label class="form-label" for="rt">
                                            RT
                                        </label>

                                        <input
                                            type="text"
                                            class="form-control"
                                            id="rt"
                                            value="03">
                                    </div>

                                    {{-- RW --}}
                                    <div class="col-md-4">
                                        <label class="form-label" for="rw">
                                            RW
                                        </label>

                                        <input
                                            type="text"
                                            class="form-control"
                                            id="rw"
                                            value="05">
                                    </div>

                                    {{-- Kode Pos --}}
                                    <div class="col-md-4">
                                        <label class="form-label" for="kode-pos">
                                            Kode Pos
                                        </label>

                                        <input
                                            type="text"
                                            class="form-control"
                                            id="kode-pos"
                                            value="50211">
                                    </div>

                                </div>

                                {{-- Information --}}
                                <div class="alert alert-primary border-0 rounded-3 mt-4 mb-0">
                                    <div class="d-flex gap-2">
                                        <i class="ri-map-pin-user-line fs-18"></i>

                                        <div>
                                            <div class="fw-semibold mb-1">
                                                Informasi Alamat
                                            </div>

                                            <small>
                                                Pastikan alamat yang dicantumkan sesuai dengan
                                                tempat tinggal calon siswa dan dapat digunakan
                                                untuk keperluan administrasi sekolah.
                                            </small>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                        {{-- Action --}}
                        <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 mt-4">

                            <a
                                href="{{ route('ppdb.pendaftaran.orang-tua') }}"
                                class="btn btn-outline-secondary rounded-pill px-4">
                                <i class="ri-arrow-left-line me-1"></i>
                                Kembali
                            </a>

                            <a
                                href="{{ route('ppdb.pendaftaran.pendidikan') }}"
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