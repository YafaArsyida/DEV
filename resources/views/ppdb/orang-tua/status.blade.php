@extends('template_portal_ppdb.v_template')

@section('title', 'Status Pendaftaran')

@section('content')

<div class="page-content">

    <div class="container-fluid" style="max-width: 100%">

        {{-- Page Title --}}
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Status Pendaftaran</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">
                                <a href="javascript: void(0);">PPDB</a>
                            </li>
                            <li class="breadcrumb-item active">
                                Status Pendaftaran
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-xxl-12">

                {{-- Status Utama --}}
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

                    <div class="card-header">

                        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">

                            <div>
                                <div class="d-flex align-items-center gap-3">

                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-success-subtle text-success rounded-circle fs-20">
                                            <i class="ri-checkbox-circle-line"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <h5 class="fw-bold mb-1">
                                            Pendaftaran Berhasil
                                        </h5>

                                        <small class="text-muted">
                                            Pendaftaran telah berhasil dikirim dan sedang menunggu proses verifikasi.
                                        </small>
                                    </div>

                                </div>
                            </div>

                            <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2">
                                Menunggu Verifikasi
                            </span>

                        </div>

                    </div>

                    <div class="card-body">

                        <div class="row align-items-center g-4">

                            {{-- Nomor Pendaftaran --}}
                            <div class="col-lg-7">

                                <div class="mb-3">

                                    <small class="text-muted d-block mb-1">
                                        Nomor Pendaftaran
                                    </small>

                                    <h4 class="fw-bold text-primary mb-0">
                                        PPDB-2027-0001
                                    </h4>

                                </div>

                                <p class="text-muted mb-4">
                                    Simpan nomor pendaftaran ini untuk memantau proses
                                    PPDB dan melihat informasi pendaftaran Anda.
                                </p>

                                <div class="d-flex flex-column flex-sm-row gap-2">

                                    <a
                                        href="{{ route('ppdb.bukti-pendaftaran') }}"
                                        class="btn btn-primary rounded-pill px-4">
                                        <i class="ri-file-text-line me-1"></i>
                                        Lihat Detail
                                    </a>

                                    <a
                                        href="{{ route('ppdb.bukti-pendaftaran') }}"
                                        class="btn btn-outline-primary rounded-pill px-4">
                                        <i class="ri-printer-line me-1"></i>
                                        Cetak Bukti
                                    </a>

                                </div>

                            </div>

                            {{-- Ringkasan --}}
                            <div class="col-lg-5">

                                <div class="card border shadow-none rounded-4 mb-0">

                                    <div class="card-body">

                                        <div class="d-flex justify-content-between gap-3 mb-3">
                                            <span class="text-muted">
                                                Tanggal Pendaftaran
                                            </span>
                                            <strong class="text-end">
                                                01 Oktober 2026
                                            </strong>
                                        </div>

                                        <div class="d-flex justify-content-between gap-3 mb-3">
                                            <span class="text-muted">
                                                Nama Calon Siswa
                                            </span>
                                            <strong class="text-end">
                                                Ahmad Fathan Pratama
                                            </strong>
                                        </div>

                                        <div class="d-flex justify-content-between gap-3 mb-3">
                                            <span class="text-muted">
                                                Jalur
                                            </span>
                                            <strong class="text-end">
                                                Reguler
                                            </strong>
                                        </div>

                                        <div class="d-flex justify-content-between gap-3">
                                            <span class="text-muted">
                                                Status
                                            </span>
                                            <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-1">
                                                Menunggu Verifikasi
                                            </span>
                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- Timeline --}}
                <div class="card border-0 shadow-sm rounded-4">

                    <div class="card-header">

                        <div class="d-flex align-items-center gap-3">

                            <div class="avatar-sm">
                                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                    <i class="ri-route-line"></i>
                                </div>
                            </div>

                            <div>
                                <h5 class="fw-bold mb-1">
                                    Timeline Proses PPDB
                                </h5>

                                <small class="text-muted">
                                    Pantau tahapan proses pendaftaran putra-putri Anda.
                                </small>
                            </div>

                        </div>

                    </div>

                    <div class="card-body">

                        {{-- Timeline Item --}}
                        <div class="d-flex gap-3">

                            <div class="avatar-sm flex-shrink-0">
                                <div class="avatar-title bg-success-subtle text-success rounded-circle fs-20">
                                    <i class="ri-check-line"></i>
                                </div>
                            </div>

                            <div class="pb-4 border-bottom w-100">
                                <h6 class="fw-semibold mb-1">
                                    Pendaftaran Dibuat
                                </h6>

                                <small class="text-muted">
                                    Pendaftaran berhasil dibuat pada 01 Oktober 2026.
                                </small>
                            </div>

                        </div>

                        {{-- Timeline Item --}}
                        <div class="d-flex gap-3 pt-4">

                            <div class="avatar-sm flex-shrink-0">
                                <div class="avatar-title bg-success-subtle text-success rounded-circle fs-20">
                                    <i class="ri-check-line"></i>
                                </div>
                            </div>

                            <div class="pb-4 border-bottom w-100">
                                <h6 class="fw-semibold mb-1">
                                    Data Dikirim
                                </h6>

                                <small class="text-muted">
                                    Data dan dokumen pendaftaran telah berhasil dikirim.
                                </small>
                            </div>

                        </div>

                        {{-- Timeline Item --}}
                        <div class="d-flex gap-3 pt-4">

                            <div class="avatar-sm flex-shrink-0">
                                <div class="avatar-title bg-warning-subtle text-warning rounded-circle fs-20">
                                    <i class="ri-time-line"></i>
                                </div>
                            </div>

                            <div class="pb-4 border-bottom w-100">

                                <div class="d-flex flex-column flex-sm-row justify-content-between gap-2">

                                    <div>
                                        <h6 class="fw-semibold mb-1">
                                            Verifikasi Berkas
                                        </h6>

                                        <small class="text-muted">
                                            Berkas sedang menunggu pemeriksaan oleh panitia PPDB.
                                        </small>
                                    </div>

                                    <span class="badge bg-warning-subtle text-warning rounded-pill align-self-start px-3 py-1">
                                        Sedang Menunggu
                                    </span>

                                </div>

                            </div>

                        </div>

                        {{-- Timeline Item --}}
                        <div class="d-flex gap-3 pt-4">

                            <div class="avatar-sm flex-shrink-0">
                                <div class="avatar-title bg-light text-muted rounded-circle fs-20">
                                    <i class="ri-time-line"></i>
                                </div>
                            </div>

                            <div class="pb-4 border-bottom w-100">

                                <h6 class="fw-semibold mb-1 text-muted">
                                    Seleksi
                                </h6>

                                <small class="text-muted">
                                    Tahap seleksi akan dilakukan setelah proses verifikasi selesai.
                                </small>

                            </div>

                        </div>

                        {{-- Timeline Item --}}
                        <div class="d-flex gap-3 pt-4">

                            <div class="avatar-sm flex-shrink-0">
                                <div class="avatar-title bg-light text-muted rounded-circle fs-20">
                                    <i class="ri-time-line"></i>
                                </div>
                            </div>

                            <div class="pb-4 border-bottom w-100">

                                <h6 class="fw-semibold mb-1 text-muted">
                                    Pengumuman
                                </h6>

                                <small class="text-muted">
                                    Hasil seleksi akan diumumkan sesuai jadwal PPDB.
                                </small>

                            </div>

                        </div>

                        {{-- Timeline Item --}}
                        <div class="d-flex gap-3 pt-4">

                            <div class="avatar-sm flex-shrink-0">
                                <div class="avatar-title bg-light text-muted rounded-circle fs-20">
                                    <i class="ri-time-line"></i>
                                </div>
                            </div>

                            <div class="w-100">

                                <h6 class="fw-semibold mb-1 text-muted">
                                    Daftar Ulang
                                </h6>

                                <small class="text-muted">
                                    Calon siswa yang dinyatakan diterima dapat mengikuti proses daftar ulang.
                                </small>

                            </div>

                        </div>

                        {{-- Info --}}
                        <div class="alert alert-light border rounded-3 mt-4 mb-0">

                            <div class="d-flex gap-2">

                                <i class="ri-information-line fs-18 text-primary"></i>

                                <div>
                                    <strong>Informasi</strong>

                                    <div class="small text-muted mt-1">
                                        Silakan pantau status pendaftaran secara berkala.
                                        Informasi mengenai hasil verifikasi dan tahapan berikutnya
                                        akan ditampilkan pada halaman ini.
                                    </div>
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