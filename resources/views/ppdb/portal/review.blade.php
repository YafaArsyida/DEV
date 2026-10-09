@extends('template_portal_ppdb.v_template')

@section('title', 'Review Pendaftaran')

@section('content')

<div class="page-content">

    <div class="container-fluid" style="max-width: 100%">

        {{-- Page Title --}}
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Review Pendaftaran</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">
                                <a href="javascript: void(0);">PPDB</a>
                            </li>
                            <li class="breadcrumb-item active">
                                Review Pendaftaran
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
                                            <i class="ri-file-check-line"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <h5 class="fw-bold mb-1">
                                            Review Pendaftaran
                                        </h5>

                                        <small class="text-muted">
                                            Periksa kembali seluruh data sebelum pendaftaran dikirim.
                                        </small>
                                    </div>

                                </div>
                            </div>

                            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                Langkah 6 dari 6
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
                                            Seluruh langkah pendaftaran telah dilengkapi.
                                        </small>
                                    </div>

                                    <span class="text-success fw-semibold">
                                        100%
                                    </span>

                                </div>

                                <div class="progress rounded-pill" style="height: 8px;">
                                    <div
                                        class="progress-bar bg-success"
                                        role="progressbar"
                                        style="width: 100%;"
                                        aria-valuenow="100"
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

                                    <span class="text-success small fw-medium">
                                        <i class="ri-checkbox-circle-fill me-1"></i>
                                        05 Berkas
                                    </span>

                                    <span class="text-primary small fw-semibold">
                                        <i class="ri-checkbox-blank-circle-line me-1"></i>
                                        06 Review
                                    </span>

                                </div>

                            </div>

                        </div>

                        {{-- Informasi --}}
                        <div class="alert alert-primary border-0 rounded-3 mb-4">

                            <div class="d-flex gap-2">

                                <i class="ri-information-line fs-18"></i>

                                <div>
                                    <strong>Periksa sebelum dikirim</strong>

                                    <div class="small mt-1">
                                        Pastikan seluruh data dan dokumen sudah benar.
                                        Setelah pendaftaran dikirim, beberapa data mungkin
                                        tidak dapat diubah kembali.
                                    </div>
                                </div>

                            </div>

                        </div>

                        {{-- Data Calon Siswa --}}
                        <div class="card border shadow-none rounded-4 mb-3">

                            <div class="card-header bg-transparent">

                                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">

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

                                    <a
                                        href="{{ route('ppdb.pendaftaran.data-siswa') }}"
                                        class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        <i class="ri-edit-line me-1"></i>
                                        Ubah Data
                                    </a>

                                </div>

                            </div>

                            <div class="card-body">

                                <div class="row g-3">

                                    <div class="col-md-4">
                                        <small class="text-muted d-block mb-1">
                                            Nama Lengkap
                                        </small>
                                        <span class="fw-semibold">
                                            Ahmad Fathan Pratama
                                        </span>
                                    </div>

                                    <div class="col-md-4">
                                        <small class="text-muted d-block mb-1">
                                            NIK
                                        </small>
                                        <span class="fw-semibold">
                                            330xxxxxxxxxxx
                                        </span>
                                    </div>

                                    <div class="col-md-4">
                                        <small class="text-muted d-block mb-1">
                                            Tanggal Lahir
                                        </small>
                                        <span class="fw-semibold">
                                            12 Januari 2020
                                        </span>
                                    </div>

                                </div>

                            </div>

                        </div>

                        {{-- Orang Tua --}}
                        <div class="card border shadow-none rounded-4 mb-3">

                            <div class="card-header bg-transparent">

                                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">

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
                                                Informasi orang tua atau wali calon siswa
                                            </small>
                                        </div>

                                    </div>

                                    <a
                                        href="{{ route('ppdb.pendaftaran.orang-tua') }}"
                                        class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        <i class="ri-edit-line me-1"></i>
                                        Ubah Data
                                    </a>

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

                        {{-- Alamat --}}
                        <div class="card border shadow-none rounded-4 mb-3">

                            <div class="card-header bg-transparent">

                                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">

                                    <div class="d-flex align-items-center gap-3">

                                        <div class="avatar-sm">
                                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                                <i class="ri-map-pin-user-line"></i>
                                            </div>
                                        </div>

                                        <div>
                                            <h6 class="fw-bold mb-1">
                                                Data Alamat
                                            </h6>

                                            <small class="text-muted">
                                                Alamat tempat tinggal calon siswa
                                            </small>
                                        </div>

                                    </div>

                                    <a
                                        href="{{ route('ppdb.pendaftaran.alamat') }}"
                                        class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        <i class="ri-edit-line me-1"></i>
                                        Ubah Data
                                    </a>

                                </div>

                            </div>

                            <div class="card-body">

                                <div class="row g-3">

                                    <div class="col-lg-6">
                                        <small class="text-muted d-block mb-1">
                                            Alamat Lengkap
                                        </small>
                                        <span class="fw-semibold">
                                            Jl. Menoreh No. 18
                                        </span>
                                    </div>

                                    <div class="col-md-6 col-lg-3">
                                        <small class="text-muted d-block mb-1">
                                            Kecamatan
                                        </small>
                                        <span class="fw-semibold">
                                            Banyumanik
                                        </span>
                                    </div>

                                    <div class="col-md-6 col-lg-3">
                                        <small class="text-muted d-block mb-1">
                                            Kode Pos
                                        </small>
                                        <span class="fw-semibold">
                                            50264
                                        </span>
                                    </div>

                                </div>

                            </div>

                        </div>

                        {{-- Pendidikan & Jalur --}}
                        <div class="card border shadow-none rounded-4 mb-3">

                            <div class="card-header bg-transparent">

                                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">

                                    <div class="d-flex align-items-center gap-3">

                                        <div class="avatar-sm">
                                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                                <i class="ri-school-line"></i>
                                            </div>
                                        </div>

                                        <div>
                                            <h6 class="fw-bold mb-1">
                                                Pendidikan & Jalur Pendaftaran
                                            </h6>

                                            <small class="text-muted">
                                                Asal pendidikan dan jalur yang dipilih
                                            </small>
                                        </div>

                                    </div>

                                    <a
                                        href="{{ route('ppdb.pendaftaran.pendidikan') }}"
                                        class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        <i class="ri-edit-line me-1"></i>
                                        Ubah Data
                                    </a>

                                </div>

                            </div>

                            <div class="card-body">

                                <div class="row g-3">

                                    <div class="col-md-4">
                                        <small class="text-muted d-block mb-1">
                                            Asal TK/RA
                                        </small>
                                        <span class="fw-semibold">
                                            TK Islam Al-Hikmah
                                        </span>
                                    </div>

                                    <div class="col-md-4">
                                        <small class="text-muted d-block mb-1">
                                            Tahun Lulus
                                        </small>
                                        <span class="fw-semibold">
                                            2026
                                        </span>
                                    </div>

                                    <div class="col-md-4">
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

                        {{-- Berkas --}}
                        <div class="card border shadow-none rounded-4">

                            <div class="card-header bg-transparent">

                                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">

                                    <div class="d-flex align-items-center gap-3">

                                        <div class="avatar-sm">
                                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                                <i class="ri-folder-upload-line"></i>
                                            </div>
                                        </div>

                                        <div>
                                            <h6 class="fw-bold mb-1">
                                                Berkas Persyaratan
                                            </h6>

                                            <small class="text-muted">
                                                Dokumen yang telah diunggah
                                            </small>
                                        </div>

                                    </div>

                                    <a
                                        href="{{ route('ppdb.pendaftaran.berkas') }}"
                                        class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        <i class="ri-edit-line me-1"></i>
                                        Kelola Berkas
                                    </a>

                                </div>

                            </div>

                            <div class="card-body">

                                <div class="row g-3">

                                    <div class="col-md-4">

                                        <div class="d-flex align-items-center gap-2">
                                            <i class="ri-checkbox-circle-fill text-success fs-18"></i>
                                            <span class="fw-medium">
                                                Kartu Keluarga
                                            </span>
                                        </div>

                                    </div>

                                    <div class="col-md-4">

                                        <div class="d-flex align-items-center gap-2">
                                            <i class="ri-checkbox-circle-fill text-success fs-18"></i>
                                            <span class="fw-medium">
                                                Akta Kelahiran
                                            </span>
                                        </div>

                                    </div>

                                    <div class="col-md-4">

                                        <div class="d-flex align-items-center gap-2">
                                            <i class="ri-checkbox-circle-fill text-success fs-18"></i>
                                            <span class="fw-medium">
                                                Pas Foto
                                            </span>
                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        {{-- Pernyataan --}}
                        <div class="card border shadow-none rounded-4 mt-4">

                            <div class="card-body">

                                <div class="form-check">

                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        id="agree-review"
                                        checked>

                                    <label
                                        class="form-check-label"
                                        for="agree-review">

                                        Saya menyatakan bahwa data yang saya isi adalah benar
                                        dan dapat dipertanggungjawabkan.

                                    </label>

                                </div>

                            </div>

                        </div>

                        {{-- Action --}}
                        <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 mt-4">

                            <a
                                href="{{ route('ppdb.pendaftaran.berkas') }}"
                                class="btn btn-outline-secondary rounded-pill px-4">
                                <i class="ri-arrow-left-line me-1"></i>
                                Kembali
                            </a>

                            <button
                                type="button"
                                class="btn btn-primary rounded-pill px-4"
                                data-bs-toggle="modal"
                                data-bs-target="#confirmSubmitModal">

                                <i class="ri-send-plane-line me-1"></i>
                                Kirim Pendaftaran

                            </button>

                        </div>

                    </div>

                </div>

            </div>
        </div>

    </div>

</div>

{{-- Confirmation Modal --}}
<div
    class="modal fade"
    id="confirmSubmitModal"
    tabindex="-1"
    aria-labelledby="confirmSubmitLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 rounded-4 shadow">

            <div class="modal-header border-0 pb-0">

                <div class="d-flex align-items-center gap-3">

                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-send-plane-line"></i>
                        </div>
                    </div>

                    <h5
                        class="modal-title fw-bold"
                        id="confirmSubmitLabel">
                        Kirim Pendaftaran?
                    </h5>

                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close">
                </button>

            </div>

            <div class="modal-body">

                <p class="text-muted mb-0">
                    Pastikan seluruh data dan dokumen yang Anda masukkan sudah benar.
                    Setelah pendaftaran dikirim, beberapa data mungkin tidak dapat
                    diubah kembali.
                </p>

            </div>

            <div class="modal-footer border-0 pt-0">

                <button
                    type="button"
                    class="btn btn-light rounded-pill px-4"
                    data-bs-dismiss="modal">
                    Batal
                </button>

                <a
                    href="{{ route('ppdb.status') }}"
                    class="btn btn-primary rounded-pill px-4">
                    Ya, Kirim Pendaftaran
                </a>

            </div>

        </div>

    </div>

</div>

@endsection