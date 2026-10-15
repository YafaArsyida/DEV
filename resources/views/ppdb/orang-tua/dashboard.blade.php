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
        <div class="row">
            <div class="col-xxl-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    {{-- HEADER --}}
                    <div class="card-header">
                        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">

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
                        </div>
                    </div>
                    <div class="card-body">
                        {{-- HEADER PENDAFTARAN --}}
                        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-start gap-3 mb-4">
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="ri-file-list-3-line text-primary fs-18"></i>

                                    <span class="text-muted small text-uppercase fw-medium">
                                        Pendaftaran Anak
                                    </span>
                                </div>

                                <h5 class="fw-bold mb-2">
                                    Mulai Pendaftaran PPDB
                                </h5>

                                <p class="text-muted mb-2">
                                    Lengkapi formulir pendaftaran putra-putri Anda
                                    dan pantau prosesnya melalui halaman ini.
                                </p>
                            </div>
                        </div>

                        {{-- CHECKLIST TAHAPAN --}}
                        <div class="row g-3 mb-4">

                            {{-- TAHAP 1: AKUN --}}
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-3 p-3 border rounded-3 h-100">
                                    <div class="avatar-sm flex-shrink-0">
                                        <div class="avatar-title bg-success-subtle text-success rounded-circle">
                                            <i class="ri-check-line fs-18"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <h6 class="mb-1 fw-semibold">Akun Pendaftar</h6>
                                        <p class="text-muted small mb-0">
                                            Akun berhasil dibuat
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- TAHAP 2: PILIH JALUR --}}
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-3 p-3 border border-primary rounded-3 h-100 bg-primary-subtle">
                                    <div class="avatar-sm flex-shrink-0">
                                        <div class="avatar-title bg-primary text-white rounded-circle">
                                            <i class="ri-route-line fs-18"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <h6 class="mb-1 fw-semibold text-primary">
                                            Pilih Jalur Pendaftaran
                                        </h6>
                                        <p class="text-primary small mb-0">
                                            Tentukan jalur yang sesuai
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- TAHAP 3: IDENTITAS SISWA --}}
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-3 p-3 border rounded-3 h-100">
                                    <div class="avatar-sm flex-shrink-0">
                                        <div class="avatar-title bg-light text-muted rounded-circle">
                                            <i class="ri-user-line fs-18"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <h6 class="mb-1 fw-semibold">Identitas Calon Siswa</h6>
                                        <p class="text-muted small mb-0">
                                            Belum diisi
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- TAHAP 4: ORANG TUA --}}
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-3 p-3 border rounded-3 h-100">
                                    <div class="avatar-sm flex-shrink-0">
                                        <div class="avatar-title bg-light text-muted rounded-circle">
                                            <i class="ri-parent-line fs-18"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <h6 class="mb-1 fw-semibold">Data Orang Tua/Wali</h6>
                                        <p class="text-muted small mb-0">
                                            Belum diisi
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- TAHAP 5: ALAMAT --}}
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-3 p-3 border rounded-3 h-100">
                                    <div class="avatar-sm flex-shrink-0">
                                        <div class="avatar-title bg-light text-muted rounded-circle">
                                            <i class="ri-map-pin-line fs-18"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <h6 class="mb-1 fw-semibold">Alamat</h6>
                                        <p class="text-muted small mb-0">
                                            Belum diisi
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- TAHAP 6: PENDIDIKAN --}}
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-3 p-3 border rounded-3 h-100">
                                    <div class="avatar-sm flex-shrink-0">
                                        <div class="avatar-title bg-light text-muted rounded-circle">
                                            <i class="ri-graduation-cap-line fs-18"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <h6 class="mb-1 fw-semibold">Riwayat Pendidikan</h6>
                                        <p class="text-muted small mb-0">
                                            Belum diisi
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- TAHAP 7: BERKAS --}}
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-3 p-3 border rounded-3 h-100">
                                    <div class="avatar-sm flex-shrink-0">
                                        <div class="avatar-title bg-light text-muted rounded-circle">
                                            <i class="ri-file-upload-line fs-18"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <h6 class="mb-1 fw-semibold">Berkas Persyaratan</h6>
                                        <p class="text-muted small mb-0">
                                            Belum diunggah
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- TAHAP 8: REVIEW --}}
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-3 p-3 border rounded-3 h-100">
                                    <div class="avatar-sm flex-shrink-0">
                                        <div class="avatar-title bg-light text-muted rounded-circle">
                                            <i class="ri-checkbox-circle-line fs-18"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <h6 class="mb-1 fw-semibold">Review & Pengiriman</h6>
                                        <p class="text-muted small mb-0">
                                            Menunggu tahap sebelumnya
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- ACTION --}}
                        <div class="border-top pt-4">
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">

                                <div>
                                    <h6 class="fw-semibold mb-1">
                                        Siap mendaftarkan putra-putri Anda?
                                    </h6>

                                    <p class="text-muted small mb-0">
                                        Anda akan memilih jalur pendaftaran
                                        sebelum mengisi formulir.
                                    </p>
                                </div>

                                <a
                                    href="{{ route('ppdb.pendaftaran.jalur') }}"
                                    class="btn btn-primary rounded-pill px-4 flex-shrink-0">
                                    Pilih Jalur Pendaftaran
                                    <i class="ri-arrow-right-line ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header">
                        <div class="d-flex align-items-center gap-3">

                            <div class="avatar-sm">
                                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                    <i class="ri-information-line"></i>
                                </div>
                            </div>

                            <div>
                                <h5 class="fw-bold mb-1">
                                    Informasi PPDB
                                </h5>

                                <small class="text-muted">
                                    Informasi yang membantu Anda mempersiapkan pendaftaran.
                                </small>
                            </div>

                        </div>
                    </div>
                    {{-- BODY --}}
                    <div class="card-body">
                        <div class="row g-3">
                            {{-- JADWAL --}}
                            <div class="col-lg-4 col-md-6">
                                <div class="card border shadow-none rounded-4 h-100">
                                    <div class="card-body p-4">
                                        <div class="avatar-sm mb-3">
                                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-18">
                                                <i class="ri-calendar-event-line"></i>
                                            </div>
                                        </div>

                                        <h6 class="fw-bold mb-2">
                                            Jadwal Pendaftaran
                                        </h6>

                                        <p class="text-muted small mb-0">
                                            Periksa periode dan batas waktu pendaftaran
                                            agar tidak melewatkan kesempatan mendaftar.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- PERSYARATAN --}}
                            <div class="col-lg-4 col-md-6">
                                <div class="card border shadow-none rounded-4 h-100">
                                    <div class="card-body p-4">
                                        <div class="avatar-sm mb-3">
                                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-18">
                                                <i class="ri-file-list-2-line"></i>
                                            </div>
                                        </div>

                                        <h6 class="fw-bold mb-2">
                                            Persyaratan Pendaftaran
                                        </h6>

                                        <p class="text-muted small mb-0">
                                            Pelajari persyaratan umum dan dokumen
                                            tambahan sesuai jalur yang dipilih.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- BANTUAN --}}
                            <div class="col-lg-4 col-md-6">
                                <div class="card border shadow-none rounded-4 h-100">
                                    <div class="card-body p-4">
                                        <div class="avatar-sm mb-3">
                                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-18">
                                                <i class="ri-customer-service-2-line"></i>
                                            </div>
                                        </div>

                                        <h6 class="fw-bold mb-2">
                                            Bantuan PPDB
                                        </h6>

                                        <p class="text-muted small mb-0">
                                            Hubungi panitia PPDB sekolah apabila
                                            mengalami kendala selama pendaftaran.
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
</div>
@endsection
