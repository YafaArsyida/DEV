@extends('template_portal_ppdb.v_template')

@section('title', 'Berkas')

@section('content')

<div class="page-content">

    <div class="container-fluid" style="max-width: 100%">

        {{-- Page Title --}}
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Berkas Persyaratan</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">
                                <a href="javascript: void(0);">PPDB</a>
                            </li>
                            <li class="breadcrumb-item active">Berkas</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xxl-12">

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

                    {{-- Header --}}
                    <div class="card-header">

                        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">

                            <div>
                                <div class="d-flex align-items-center gap-3">

                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                            <i class="ri-file-upload-line"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <h5 class="fw-bold mb-1">
                                            Berkas Persyaratan
                                        </h5>

                                        <small class="text-muted">
                                            Unggah dokumen persyaratan PPDB sesuai ketentuan yang telah ditetapkan.
                                        </small>
                                    </div>

                                </div>
                            </div>

                            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                Langkah 5 dari 6
                            </span>

                        </div>

                    </div>

                    <div class="card-body">

                        {{-- Progress --}}
                        <div class="card border shadow-none rounded-4 mb-4">

                            <div class="card-body">

                                <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-2">

                                    <div>
                                        <h6 class="fw-semibold mb-1">
                                            Progress Pendaftaran
                                        </h6>

                                        <small class="text-muted">
                                            Pastikan seluruh berkas persyaratan telah diunggah sebelum melakukan review.
                                        </small>
                                    </div>

                                    <span class="text-primary fw-semibold">
                                        83%
                                    </span>

                                </div>

                                <div class="progress rounded-pill" style="height: 8px;">
                                    <div
                                        class="progress-bar bg-primary"
                                        role="progressbar"
                                        style="width: 83%;"
                                        aria-valuenow="83"
                                        aria-valuemin="0"
                                        aria-valuemax="100">
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap gap-3 mt-3">

                                    <span class="text-success small fw-medium">
                                        <i class="ri-checkbox-circle-fill me-1"></i>
                                        01 Data Siswa
                                    </span>

                                    <span class="text-success small fw-medium">
                                        <i class="ri-checkbox-circle-fill me-1"></i>
                                        02 Orang Tua/Wali
                                    </span>

                                    <span class="text-success small fw-medium">
                                        <i class="ri-checkbox-circle-fill me-1"></i>
                                        03 Alamat
                                    </span>

                                    <span class="text-success small fw-medium">
                                        <i class="ri-checkbox-circle-fill me-1"></i>
                                        04 Pendidikan
                                    </span>

                                    <span class="text-primary small fw-semibold">
                                        <i class="ri-checkbox-blank-circle-line me-1"></i>
                                        05 Berkas
                                    </span>

                                    <span class="text-muted small">
                                        <i class="ri-checkbox-blank-circle-line me-1"></i>
                                        06 Review
                                    </span>

                                </div>

                            </div>

                        </div>

                        {{-- Dokumen Persyaratan --}}
                        <div class="card border shadow-none rounded-4">

                            <div class="card-header bg-transparent">

                                <div class="d-flex align-items-center gap-3">

                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                            <i class="ri-folder-upload-line"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <h6 class="fw-bold mb-1">
                                            Dokumen Persyaratan
                                        </h6>

                                        <small class="text-muted">
                                            Unggah dokumen sesuai persyaratan pendaftaran.
                                        </small>
                                    </div>

                                </div>

                            </div>

                            <div class="card-body">

                                {{-- Kartu Keluarga --}}
                                <div class="border rounded-4 p-3 mb-3">

                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">

                                        <div class="d-flex align-items-center gap-3">

                                            <div class="avatar-sm">
                                                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                                    <i class="ri-file-text-line"></i>
                                                </div>
                                            </div>

                                            <div>
                                                <h6 class="fw-semibold mb-1">
                                                    Kartu Keluarga
                                                </h6>

                                                <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-1">
                                                    Belum diunggah
                                                </span>
                                            </div>

                                        </div>

                                        <button type="button" class="btn btn-outline-primary rounded-pill px-4">
                                            <i class="ri-upload-2-line me-1"></i>
                                            Upload
                                        </button>

                                    </div>

                                </div>

                                {{-- Akta Kelahiran --}}
                                <div class="border rounded-4 p-3 mb-3">

                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">

                                        <div class="d-flex align-items-center gap-3">

                                            <div class="avatar-sm">
                                                <div class="avatar-title bg-success-subtle text-success rounded-circle fs-20">
                                                    <i class="ri-file-text-line"></i>
                                                </div>
                                            </div>

                                            <div>
                                                <h6 class="fw-semibold mb-1">
                                                    Akta Kelahiran
                                                </h6>

                                                <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1">
                                                    <i class="ri-check-line me-1"></i>
                                                    Berkas telah diunggah
                                                </span>
                                            </div>

                                        </div>

                                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4">
                                            <i class="ri-eye-line me-1"></i>
                                            Lihat
                                        </button>

                                    </div>

                                </div>

                                {{-- Pas Foto --}}
                                <div class="border rounded-4 p-3 mb-3">

                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">

                                        <div class="d-flex align-items-center gap-3">

                                            <div class="avatar-sm">
                                                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                                    <i class="ri-image-line"></i>
                                                </div>
                                            </div>

                                            <div>
                                                <h6 class="fw-semibold mb-1">
                                                    Pas Foto
                                                </h6>

                                                <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-1">
                                                    Belum diunggah
                                                </span>
                                            </div>

                                        </div>

                                        <button type="button" class="btn btn-outline-primary rounded-pill px-4">
                                            <i class="ri-upload-2-line me-1"></i>
                                            Upload
                                        </button>

                                    </div>

                                </div>

                                {{-- Dokumen Pendukung --}}
                                <div class="border rounded-4 p-3">

                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">

                                        <div class="d-flex align-items-center gap-3">

                                            <div class="avatar-sm">
                                                <div class="avatar-title bg-light text-muted rounded-circle fs-20">
                                                    <i class="ri-attachment-2"></i>
                                                </div>
                                            </div>

                                            <div>
                                                <h6 class="fw-semibold mb-1">
                                                    Dokumen Pendukung
                                                </h6>

                                                <span class="badge bg-light text-muted rounded-pill px-3 py-1">
                                                    Opsional
                                                </span>
                                            </div>

                                        </div>

                                        <button type="button" class="btn btn-outline-primary rounded-pill px-4">
                                            <i class="ri-upload-2-line me-1"></i>
                                            Upload
                                        </button>

                                    </div>

                                </div>

                                {{-- Informasi --}}
                                <div class="alert alert-primary border-0 rounded-3 mt-4 mb-0">

                                    <div class="d-flex gap-2">

                                        <i class="ri-information-line fs-18"></i>

                                        <div>
                                            <strong>Informasi Berkas</strong>

                                            <div class="small mt-1">
                                                Berkas dapat diunggah dalam format
                                                JPG, PNG, atau PDF dengan ukuran maksimal
                                                2 MB per file.
                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        {{-- Action --}}
                        <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 mt-4">

                            <a
                                href="{{ route('ppdb.pendaftaran.pendidikan') }}"
                                class="btn btn-outline-secondary rounded-pill px-4">
                                <i class="ri-arrow-left-line me-1"></i>
                                Kembali
                            </a>

                            <a
                                href="{{ route('ppdb.pendaftaran.review') }}"
                                class="btn btn-primary rounded-pill px-4">
                                Simpan &amp; Lanjutkan
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