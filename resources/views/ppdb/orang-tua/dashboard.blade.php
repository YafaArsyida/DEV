@extends('template_portal_ppdb.v_template')
@section('content')
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Dashboard</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">PPDB</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xxl-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

                {{-- HEADER --}}
                <div class="card-header">
                    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">

                        <div>
                            <div class="d-flex align-items-center gap-3">

                                <div class="avatar-sm">
                                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                        <i class="ri-user-star-line"></i>
                                    </div>
                                </div>

                                <div>
                                    <h5 class="fw-bold mb-1">
                                        Selamat Datang, Bapak/Ibu
                                    </h5>

                                    <small class="text-muted">
                                        Kelola pendaftaran putra-putri Anda melalui portal PPDB.
                                    </small>
                                </div>

                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2">
                                <i class="ri-time-line me-1 align-middle"></i>
                                Belum Lengkap
                            </span>
                        </div>

                    </div>
                </div>


                {{-- BODY --}}
                <div class="card-body">

                    {{-- STATUS PENDAFTARAN --}}
                    <div class="card border shadow-none rounded-4 mb-4">

                        <div class="card-body p-4">

                            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">

                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <i class="ri-file-list-3-line text-primary"></i>

                                        <span class="text-muted small text-uppercase fw-medium">
                                            Status Pendaftaran
                                        </span>
                                    </div>

                                    <h5 class="fw-bold mb-1">
                                        Lengkapi Data Pendaftaran
                                    </h5>

                                    <p class="text-muted mb-0">
                                        Lengkapi data putra-putri Anda untuk melanjutkan proses PPDB.
                                    </p>
                                </div>

                                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                    Progress 40%
                                </span>

                            </div>


                            {{-- PROGRESS --}}
                            <div class="mb-4">

                                <div class="d-flex justify-content-between align-items-center mb-2">

                                    <span class="text-muted small">
                                        Progress pendaftaran
                                    </span>

                                    <span class="fw-semibold text-primary">
                                        40%
                                    </span>

                                </div>

                                <div class="progress rounded-pill" style="height: 8px;">
                                    <div
                                        class="progress-bar rounded-pill"
                                        role="progressbar"
                                        style="width: 40%;"
                                        aria-valuenow="40"
                                        aria-valuemin="0"
                                        aria-valuemax="100">
                                    </div>
                                </div>

                            </div>


                            {{-- CHECKLIST --}}
                            <div class="row g-2">

                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-2 py-2">
                                        <span class="avatar-xs">
                                            <span class="avatar-title bg-success-subtle text-success rounded-circle">
                                                <i class="ri-check-line"></i>
                                            </span>
                                        </span>

                                        <span class="text-body">
                                            Akun Pendaftar
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-2 py-2">
                                        <span class="avatar-xs">
                                            <span class="avatar-title bg-success-subtle text-success rounded-circle">
                                                <i class="ri-check-line"></i>
                                            </span>
                                        </span>

                                        <span class="text-body">
                                            Data Calon Siswa
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-2 py-2">
                                        <span class="avatar-xs">
                                            <span class="avatar-title bg-light text-muted rounded-circle">
                                                <i class="ri-arrow-right-line"></i>
                                            </span>
                                        </span>

                                        <span class="text-muted">
                                            Data Orang Tua/Wali
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-2 py-2">
                                        <span class="avatar-xs">
                                            <span class="avatar-title bg-light text-muted rounded-circle">
                                                <i class="ri-arrow-right-line"></i>
                                            </span>
                                        </span>

                                        <span class="text-muted">
                                            Data Alamat
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-2 py-2">
                                        <span class="avatar-xs">
                                            <span class="avatar-title bg-light text-muted rounded-circle">
                                                <i class="ri-arrow-right-line"></i>
                                            </span>
                                        </span>

                                        <span class="text-muted">
                                            Berkas Persyaratan
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-2 py-2">
                                        <span class="avatar-xs">
                                            <span class="avatar-title bg-light text-muted rounded-circle">
                                                <i class="ri-arrow-right-line"></i>
                                            </span>
                                        </span>

                                        <span class="text-muted">
                                            Review & Pengiriman
                                        </span>
                                    </div>
                                </div>

                            </div>


                            {{-- ACTION --}}
                            <div class="border-top mt-3 pt-3">

                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">

                                    <p class="text-muted mb-0 small">
                                        Pastikan data yang Anda isi sesuai dengan dokumen resmi.
                                    </p>

                                    <a href="{{ route('ppdb.pendaftaran.data-siswa') }}"
                                    class="btn btn-primary rounded-pill px-4">
                                        <i class="ri-arrow-right-line me-1"></i>
                                        Lanjutkan Pendaftaran
                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- RINGKASAN PENDAFTAR --}}
                    <div class="row g-3">

                        {{-- AKUN --}}
                        <div class="col-lg-4 col-md-6">

                            <div class="card border shadow-none rounded-4 h-100">

                                <div class="card-body p-4">

                                    <div class="d-flex align-items-center justify-content-between mb-3">

                                        <div class="avatar-sm">
                                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-18">
                                                <i class="ri-shield-check-line"></i>
                                            </div>
                                        </div>

                                        <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                                            Aktif
                                        </span>

                                    </div>

                                    <h5 class="fw-bold mb-1">
                                        Akun Pendaftar
                                    </h5>

                                    <p class="text-muted mb-0">
                                        Akun orang tua/wali telah berhasil dibuat dan dapat digunakan untuk melanjutkan pendaftaran.
                                    </p>

                                </div>

                            </div>

                        </div>


                        {{-- CALON SISWA --}}
                        <div class="col-lg-4 col-md-6">

                            <div class="card border shadow-none rounded-4 h-100">

                                <div class="card-body p-4">

                                    <div class="d-flex align-items-center justify-content-between mb-3">

                                        <div class="avatar-sm">
                                            <div class="avatar-title bg-success-subtle text-success rounded-circle fs-18">
                                                <i class="ri-user-star-line"></i>
                                            </div>
                                        </div>

                                        <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                                            Terisi
                                        </span>

                                    </div>

                                    <h5 class="fw-bold mb-1">
                                        Ahmad Fathan Pratama
                                    </h5>

                                    <p class="text-muted mb-0">
                                        Data calon siswa telah diisi dan dapat dilanjutkan ke tahap berikutnya.
                                    </p>

                                </div>

                            </div>

                        </div>


                        {{-- JALUR --}}
                        <div class="col-lg-4 col-md-6">

                            <div class="card border shadow-none rounded-4 h-100">

                                <div class="card-body p-4">

                                    <div class="d-flex align-items-center justify-content-between mb-3">

                                        <div class="avatar-sm">
                                            <div class="avatar-title bg-warning-subtle text-warning rounded-circle fs-18">
                                                <i class="ri-route-line"></i>
                                            </div>
                                        </div>

                                        <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2">
                                            Pilihan
                                        </span>

                                    </div>

                                    <h5 class="fw-bold mb-1">
                                        Jalur Reguler
                                    </h5>

                                    <p class="text-muted mb-0">
                                        Jalur pendaftaran yang dipilih untuk Tahun Ajaran 2027/2028.
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
@endsection
