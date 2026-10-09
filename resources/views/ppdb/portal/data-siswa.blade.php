@extends('template_portal_ppdb.v_template')

@section('title', 'Data Siswa')

@section('content')

<div class="page-content">

    <div class="container-fluid" style="max-width: 100%">

        {{-- Page Title --}}
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mb-sm-0">Data Siswa</h4>
                    </div>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('ppdb.dashboard') }}">PPDB</a>
                            </li>
                            <li class="breadcrumb-item active">
                                Data Siswa
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div class="row">
            <div class="col-xxl-12">

                {{-- Main Card --}}
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

                    {{-- Header --}}
                    <div class="card-header">
                        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">

                            <div>
                                <div class="d-flex align-items-center gap-3">

                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                            <i class="ri-user-line"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <h5 class="fw-bold mb-1">Data Calon Siswa</h5>
                                        <small class="text-muted">
                                            Lengkapi identitas calon siswa sesuai dengan dokumen resmi.
                                        </small>
                                    </div>

                                </div>
                            </div>

                            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                Langkah 1 dari 6
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
                                            Lengkapi setiap langkah untuk menyelesaikan pendaftaran.
                                        </small>
                                    </div>

                                    <span class="text-primary fw-semibold">
                                        18%
                                    </span>
                                </div>

                                <div class="progress rounded-pill" style="height: 8px;">
                                    <div
                                        class="progress-bar bg-primary"
                                        role="progressbar"
                                        style="width: 18%;"
                                        aria-valuenow="18"
                                        aria-valuemin="0"
                                        aria-valuemax="100">
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap gap-3 mt-3">

                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-xs">
                                            <div class="avatar-title bg-primary text-white rounded-circle fs-12">
                                                01
                                            </div>
                                        </div>
                                        <span class="small fw-semibold text-primary">
                                            Data Siswa
                                        </span>
                                    </div>

                                    <div class="d-flex align-items-center gap-2 text-muted">
                                        <div class="avatar-xs">
                                            <div class="avatar-title bg-light text-muted rounded-circle fs-12">
                                                02
                                            </div>
                                        </div>
                                        <span class="small">
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

                        {{-- Form Card --}}
                        <div class="card border shadow-none rounded-4">

                            <div class="card-header bg-transparent">
                                <div class="d-flex align-items-center gap-3">

                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                            <i class="ri-user-3-line"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <h5 class="fw-bold mb-1">Identitas Calon Siswa</h5>
                                        <small class="text-muted">
                                            Masukkan data calon siswa sesuai dokumen resmi.
                                        </small>
                                    </div>

                                </div>
                            </div>

                            <div class="card-body">

                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label class="form-label" for="nama-lengkap">
                                            Nama Lengkap
                                        </label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="nama-lengkap"
                                            value="Ahmad Fathan Pratama">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="nik">
                                            NIK
                                        </label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="nik"
                                            value="3301234567890001">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="nisn">
                                            NISN
                                        </label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="nisn"
                                            value="0012345678">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="nomor-kk">
                                            Nomor KK
                                        </label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="nomor-kk"
                                            value="3301234567890123">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="tempat-lahir">
                                            Tempat Lahir
                                        </label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="tempat-lahir"
                                            value="Semarang">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="tanggal-lahir">
                                            Tanggal Lahir
                                        </label>
                                        <input
                                            type="date"
                                            class="form-control"
                                            id="tanggal-lahir"
                                            value="2020-01-12">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="jenis-kelamin">
                                            Jenis Kelamin
                                        </label>
                                        <select class="form-select" id="jenis-kelamin">
                                            <option selected>Laki-laki</option>
                                            <option>Perempuan</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="agama">
                                            Agama
                                        </label>
                                        <select class="form-select" id="agama">
                                            <option selected>Islam</option>
                                            <option>Kristen</option>
                                            <option>Katolik</option>
                                            <option>Hindu</option>
                                            <option>Budha</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="anak-ke">
                                            Anak ke-
                                        </label>
                                        <input
                                            type="number"
                                            class="form-control"
                                            id="anak-ke"
                                            value="1">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="jumlah-saudara">
                                            Jumlah Saudara
                                        </label>
                                        <input
                                            type="number"
                                            class="form-control"
                                            id="jumlah-saudara"
                                            value="2">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="status-anak">
                                            Status Anak
                                        </label>
                                        <select class="form-select" id="status-anak">
                                            <option selected>Anak Kandung</option>
                                            <option>Anak Tiri</option>
                                            <option>Anak Angkat</option>
                                        </select>
                                    </div>

                                </div>

                                {{-- Information --}}
                                <div class="alert alert-primary border-0 rounded-3 mt-4 mb-0">
                                    <div class="d-flex gap-2">
                                        <i class="ri-information-line fs-18"></i>
                                        <div>
                                            <div class="fw-semibold mb-1">
                                                Perhatikan data yang diisi
                                            </div>
                                            <small>
                                                Pastikan seluruh data sesuai dengan dokumen resmi
                                                seperti Kartu Keluarga dan Akta Kelahiran.
                                            </small>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                        {{-- Action --}}
                        <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 mt-4">

                            <a
                                href="{{ route('ppdb.dashboard') }}"
                                class="btn btn-outline-secondary rounded-pill px-4">
                                <i class="ri-arrow-left-line me-1"></i>
                                Kembali
                            </a>

                            <a
                                href="{{ route('ppdb.pendaftaran.orang-tua') }}"
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