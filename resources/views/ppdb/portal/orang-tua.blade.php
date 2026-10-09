@extends('template_portal_ppdb.v_template')

@section('title', 'Data Orang Tua/Wali')

@section('content')

<div class="page-content">

    <div class="container-fluid" style="max-width: 100%">

        {{-- Page Title --}}
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mb-sm-0">Data Orang Tua/Wali</h4>
                    </div>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('ppdb.dashboard') }}">PPDB</a>
                            </li>
                            <li class="breadcrumb-item active">
                                Orang Tua/Wali
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
                                            <i class="ri-parent-line"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <h5 class="fw-bold mb-1">Data Orang Tua/Wali</h5>
                                        <small class="text-muted">
                                            Lengkapi informasi orang tua atau wali calon siswa.
                                        </small>
                                    </div>

                                </div>
                            </div>

                            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                Langkah 2 dari 6
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
                                            Anda sedang mengisi data orang tua/wali.
                                        </small>
                                    </div>

                                    <span class="text-primary fw-semibold">
                                        33%
                                    </span>
                                </div>

                                <div class="progress rounded-pill" style="height: 8px;">
                                    <div
                                        class="progress-bar bg-primary"
                                        role="progressbar"
                                        style="width: 33%;"
                                        aria-valuenow="33"
                                        aria-valuemin="0"
                                        aria-valuemax="100">
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap gap-3 mt-3">

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

                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-xs">
                                            <div class="avatar-title bg-primary text-white rounded-circle fs-12">
                                                02
                                            </div>
                                        </div>
                                        <span class="small fw-semibold text-primary">
                                            Orang Tua/Wali
                                        </span>
                                    </div>

                                    <div class="d-flex align-items-center gap-2 text-muted">
                                        <div class="avatar-xs">
                                            <div class="avatar-title bg-light text-muted rounded-circle fs-12">
                                                03
                                            </div>
                                        </div>
                                        <span class="small">
                                            Alamat
                                        </span>
                                    </div>

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

                        {{-- Data Ayah --}}
                        <div class="card border shadow-none rounded-4 mb-4">

                            <div class="card-header bg-transparent">
                                <div class="d-flex align-items-center gap-3">

                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                            <i class="ri-user-line"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <h5 class="fw-bold mb-1">Data Ayah</h5>
                                        <small class="text-muted">
                                            Informasi ayah kandung calon siswa.
                                        </small>
                                    </div>

                                </div>
                            </div>

                            <div class="card-body">

                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label class="form-label" for="ayah-nama">
                                            Nama Lengkap
                                        </label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="ayah-nama"
                                            value="Budi Santoso">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="ayah-nik">
                                            NIK
                                        </label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="ayah-nik"
                                            value="3301234567890002">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="ayah-pendidikan">
                                            Pendidikan Terakhir
                                        </label>
                                        <select class="form-select" id="ayah-pendidikan">
                                            <option>SMP</option>
                                            <option selected>S1</option>
                                            <option>D3</option>
                                            <option>SMA/SMK</option>
                                            <option>S2</option>
                                            <option>S3</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="ayah-pekerjaan">
                                            Pekerjaan
                                        </label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="ayah-pekerjaan"
                                            value="Karyawan Swasta">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="ayah-penghasilan">
                                            Penghasilan per Bulan
                                        </label>
                                        <select class="form-select" id="ayah-penghasilan">
                                            <option>&lt; Rp1.000.000</option>
                                            <option>Rp1.000.000 - Rp3.000.000</option>
                                            <option selected>Rp3.000.000 - Rp5.000.000</option>
                                            <option>Rp5.000.000 - Rp7.000.000</option>
                                            <option>&gt; Rp7.000.000</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="ayah-whatsapp">
                                            Nomor WhatsApp
                                        </label>
                                        <input
                                            type="tel"
                                            class="form-control"
                                            id="ayah-whatsapp"
                                            value="081234567890">
                                    </div>

                                </div>

                            </div>

                        </div>

                        {{-- Data Ibu --}}
                        <div class="card border shadow-none rounded-4 mb-4">

                            <div class="card-header bg-transparent">
                                <div class="d-flex align-items-center gap-3">

                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                            <i class="ri-user-heart-line"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <h5 class="fw-bold mb-1">Data Ibu</h5>
                                        <small class="text-muted">
                                            Informasi ibu kandung calon siswa.
                                        </small>
                                    </div>

                                </div>
                            </div>

                            <div class="card-body">

                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label class="form-label" for="ibu-nama">
                                            Nama Lengkap
                                        </label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="ibu-nama"
                                            value="Siti Rahmawati">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="ibu-nik">
                                            NIK
                                        </label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="ibu-nik"
                                            value="3301234567890003">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="ibu-pendidikan">
                                            Pendidikan Terakhir
                                        </label>
                                        <select class="form-select" id="ibu-pendidikan">
                                            <option>SMP</option>
                                            <option selected>SMA/SMK</option>
                                            <option>D3</option>
                                            <option>S1</option>
                                            <option>S2</option>
                                            <option>S3</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="ibu-pekerjaan">
                                            Pekerjaan
                                        </label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="ibu-pekerjaan"
                                            value="Ibu Rumah Tangga">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="ibu-penghasilan">
                                            Penghasilan per Bulan
                                        </label>
                                        <select class="form-select" id="ibu-penghasilan">
                                            <option selected>Tidak Berpenghasilan</option>
                                            <option>&lt; Rp1.000.000</option>
                                            <option>Rp1.000.000 - Rp3.000.000</option>
                                            <option>Rp3.000.000 - Rp5.000.000</option>
                                            <option>Rp5.000.000 - Rp7.000.000</option>
                                            <option>&gt; Rp7.000.000</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="ibu-whatsapp">
                                            Nomor WhatsApp
                                        </label>
                                        <input
                                            type="tel"
                                            class="form-control"
                                            id="ibu-whatsapp"
                                            value="081234567891">
                                    </div>

                                </div>

                            </div>

                        </div>

                        {{-- Data Wali --}}
                        <div class="card border shadow-none rounded-4 mb-4">

                            <div class="card-header bg-transparent">
                                <div class="d-flex align-items-center gap-3">

                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                            <i class="ri-user-settings-line"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <h5 class="fw-bold mb-1">Data Wali</h5>
                                        <small class="text-muted">
                                            Isi apabila calon siswa menggunakan wali.
                                        </small>
                                    </div>

                                </div>
                            </div>

                            <div class="card-body">

                                <div class="form-check form-switch mb-3">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        role="switch"
                                        id="use-wali">

                                    <label
                                        class="form-check-label fw-semibold"
                                        for="use-wali">
                                        Menggunakan wali
                                    </label>
                                </div>

                                <div class="alert alert-light border rounded-3 mb-0">
                                    <div class="d-flex align-items-center gap-2 text-muted">
                                        <i class="ri-information-line fs-18"></i>
                                        <span class="small">
                                            Tidak menggunakan wali. Aktifkan pilihan di atas jika
                                            calon siswa memiliki wali yang perlu dicantumkan.
                                        </span>
                                    </div>
                                </div>

                            </div>

                        </div>

                        {{-- Action --}}
                        <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 mt-4">

                            <a
                                href="{{ route('ppdb.pendaftaran.data-siswa') }}"
                                class="btn btn-outline-secondary rounded-pill px-4">
                                <i class="ri-arrow-left-line me-1"></i>
                                Kembali
                            </a>

                            <a
                                href="{{ route('ppdb.pendaftaran.alamat') }}"
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