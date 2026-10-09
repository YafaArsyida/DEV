@extends('template_portal_ppdb.v_template')

@section('title', 'Bukti Pendaftaran')

@section('content')

<div class="page-content">

    <div class="container-fluid" style="max-width: 100%">

        {{-- Page Title --}}
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Bukti Pendaftaran</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">
                                <a href="javascript: void(0);">PPDB</a>
                            </li>
                            <li class="breadcrumb-item active">
                                Bukti Pendaftaran
                            </li>
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
                                            <i class="ri-file-text-line"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <h5 class="fw-bold mb-1">
                                            Bukti Pendaftaran PPDB
                                        </h5>

                                        <small class="text-muted">
                                            Dokumen bukti bahwa pendaftaran PPDB telah berhasil dikirim.
                                        </small>
                                    </div>

                                </div>
                            </div>

                            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                PPDB-2027-0001
                            </span>

                        </div>

                    </div>

                    <div class="card-body">

                        <div class="row g-4">

                            {{-- Detail Pendaftaran --}}
                            <div class="col-lg-12">

                                {{-- Nomor Pendaftaran --}}
                                <div class="card border shadow-none rounded-4 mb-4">

                                    <div class="card-body">

                                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">

                                            <div>
                                                <small class="text-muted d-block mb-1">
                                                    Nomor Pendaftaran
                                                </small>

                                                <h4 class="fw-bold text-primary mb-0">
                                                    PPDB-2027-0001
                                                </h4>
                                            </div>

                                            <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2">
                                                Menunggu Verifikasi
                                            </span>

                                        </div>

                                    </div>

                                </div>

                                {{-- Data Calon Siswa --}}
                                <div class="card border shadow-none rounded-4 mb-3">

                                    <div class="card-header bg-transparent">

                                        <div class="d-flex align-items-center gap-3">

                                            <div class="avatar-sm">
                                                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                                    <i class="ri-user-line"></i>
                                                </div>
                                            </div>

                                            <div>
                                                <h6 class="fw-bold mb-1">
                                                    Data Calon Siswa
                                                </h6>

                                                <small class="text-muted">
                                                    Identitas calon siswa
                                                </small>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="card-body">

                                        <div class="row g-3">

                                            <div class="col-md-6">
                                                <small class="text-muted d-block mb-1">
                                                    Nama Lengkap
                                                </small>
                                                <span class="fw-semibold">
                                                    Ahmad Fathan Pratama
                                                </span>
                                            </div>

                                            <div class="col-md-6">
                                                <small class="text-muted d-block mb-1">
                                                    NIK
                                                </small>
                                                <span class="fw-semibold">
                                                    3301234567890001
                                                </span>
                                            </div>

                                            <div class="col-md-6">
                                                <small class="text-muted d-block mb-1">
                                                    Tempat, Tanggal Lahir
                                                </small>
                                                <span class="fw-semibold">
                                                    Semarang, 12 Januari 2020
                                                </span>
                                            </div>

                                            <div class="col-md-6">
                                                <small class="text-muted d-block mb-1">
                                                    Jalur Pendaftaran
                                                </small>
                                                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                                    Reguler
                                                </span>
                                            </div>

                                        </div>

                                    </div>

                                </div>

                                {{-- Data Orang Tua --}}
                                <div class="card border shadow-none rounded-4 mb-3">

                                    <div class="card-header bg-transparent">

                                        <div class="d-flex align-items-center gap-3">

                                            <div class="avatar-sm">
                                                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                                    <i class="ri-parent-line"></i>
                                                </div>
                                            </div>

                                            <div>
                                                <h6 class="fw-bold mb-1">
                                                    Data Orang Tua/Wali
                                                </h6>

                                                <small class="text-muted">
                                                    Informasi orang tua calon siswa
                                                </small>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="card-body">

                                        <div class="row g-3">

                                            <div class="col-md-4">
                                                <small class="text-muted d-block mb-1">
                                                    Ayah
                                                </small>
                                                <span class="fw-semibold">
                                                    Budi Santoso
                                                </span>
                                            </div>

                                            <div class="col-md-4">
                                                <small class="text-muted d-block mb-1">
                                                    Ibu
                                                </small>
                                                <span class="fw-semibold">
                                                    Siti Rahmawati
                                                </span>
                                            </div>

                                            <div class="col-md-4">
                                                <small class="text-muted d-block mb-1">
                                                    Nomor WhatsApp
                                                </small>
                                                <span class="fw-semibold">
                                                    0812-3456-7890
                                                </span>
                                            </div>

                                        </div>

                                    </div>

                                </div>

                                {{-- Informasi Pendaftaran --}}
                                <div class="card border shadow-none rounded-4">

                                    <div class="card-header bg-transparent">

                                        <div class="d-flex align-items-center gap-3">

                                            <div class="avatar-sm">
                                                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                                    <i class="ri-information-line"></i>
                                                </div>
                                            </div>

                                            <div>
                                                <h6 class="fw-bold mb-1">
                                                    Informasi Pendaftaran
                                                </h6>

                                                <small class="text-muted">
                                                    Informasi penerbitan bukti pendaftaran
                                                </small>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="card-body">

                                        <div class="row g-3">

                                            <div class="col-md-4">
                                                <small class="text-muted d-block mb-1">
                                                    Tanggal Pendaftaran
                                                </small>
                                                <span class="fw-semibold">
                                                    01 Oktober 2026
                                                </span>
                                            </div>

                                            <div class="col-md-4">
                                                <small class="text-muted d-block mb-1">
                                                    Tanggal Bukti
                                                </small>
                                                <span class="fw-semibold">
                                                    08 Oktober 2026
                                                </span>
                                            </div>

                                            <div class="col-md-4">
                                                <small class="text-muted d-block mb-1">
                                                    Diterbitkan Oleh
                                                </small>
                                                <span class="fw-semibold">
                                                    Panitia PPDB
                                                </span>
                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        {{-- Action --}}
                        <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 mt-4">

                            <a
                                href="{{ route('ppdb.status') }}"
                                class="btn btn-outline-secondary rounded-pill px-4">
                                <i class="ri-arrow-left-line me-1"></i>
                                Kembali ke Status
                            </a>

                            <button
                                type="button"
                                class="btn btn-primary rounded-pill px-4">
                                <i class="ri-printer-line me-1"></i>
                                Cetak Bukti Pendaftaran
                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection