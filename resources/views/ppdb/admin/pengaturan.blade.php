@extends('template_ppdb.v_template')

@section('content')
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">

        {{-- PAGE TITLE --}}
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Pengaturan PPDB</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Akademik</a></li>
                            <li class="breadcrumb-item active">Pengaturan PPDB</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>

        {{-- PARAMETER AKADEMIK --}}
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-3 p-lg-4">
                        <div class="d-flex flex-column flex-md-row
                            align-items-md-center justify-content-between gap-3">

                            <div>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-sm">
                                        <div class="avatar-title
                                            bg-primary-subtle text-primary rounded-3">
                                            <i class="ri-equalizer-line fs-5"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-semibold">
                                            Parameter Akademik
                                        </h6>
                                        <p class="text-muted mb-0">
                                            Pilih jenjang dan tahun ajaran
                                            untuk mengelola konfigurasi PPDB.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                @livewire('keuangan.parameter.jenjang-tahun-ajar')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SUMMARY --}}
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-xl-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 mb-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-md">
                                <div class="avatar-title bg-primary-subtle
                                    text-primary rounded-3 fs-4">
                                    <i class="ri-calendar-event-line"></i>
                                </div>
                            </div>
                            <div>
                                <p class="text-muted mb-1">
                                    Periode Pendaftaran
                                </p>
                                <h4 class="mb-0 fw-bold">
                                    {{ number_format($totalPeriode, 0, ',', '.') }}
                                </h4>
                                <small class="text-muted">
                                    Periode terkonfigurasi
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 mb-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-md">
                                <div class="avatar-title bg-info-subtle
                                    text-info rounded-3 fs-4">
                                    <i class="ri-stack-line"></i>
                                </div>
                            </div>
                            <div>
                                <p class="text-muted mb-1">
                                    Gelombang
                                </p>
                                <h4 class="mb-0 fw-bold">
                                    {{ number_format($totalGelombang, 0, ',', '.') }}
                                </h4>
                                <small class="text-muted">
                                    Gelombang terkonfigurasi
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 mb-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-md">
                                <div class="avatar-title bg-success-subtle
                                    text-success rounded-3 fs-4">
                                    <i class="ri-file-list-3-line"></i>
                                </div>
                            </div>
                            <div>
                                <p class="text-muted mb-1">
                                    Persyaratan
                                </p>
                                <h4 class="mb-0 fw-bold">5</h4>
                                <small class="text-muted">
                                    Persyaratan terkonfigurasi
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MAIN CONFIGURATION --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">

            <div class="card-header bg-transparent border-bottom
                p-3 p-lg-4">
                <h5 class="card-title mb-1 fw-semibold">
                    Konfigurasi Penerimaan
                </h5>
                <p class="text-muted mb-0">
                    Atur jadwal penerimaan dan persyaratan
                    yang akan ditampilkan pada portal PPDB.
                </p>
            </div>

            <div class="card-body p-0">

                {{-- TABS --}}
                <ul class="nav nav-tabs nav-tabs-custom nav-primary
                    px-3 px-lg-4 pt-2"
                    role="tablist">

                    <li class="nav-item">
                        <a class="nav-link active"
                            data-bs-toggle="tab"
                            href="#tab-periode"
                            role="tab">
                            <i class="ri-calendar-event-line me-1"></i>
                            Periode
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link"
                            data-bs-toggle="tab"
                            href="#tab-gelombang"
                            role="tab">
                            <i class="ri-stack-line me-1"></i>
                            Gelombang
                        </a>
                    </li>
                </ul>

                <div class="tab-content p-3 p-lg-4">

                    {{-- TAB PERIODE --}}
                    <div class="tab-pane fade show active" id="tab-periode" role="tabpanel">
                        @livewire('p-p-d-b.periode.index')
                        @livewire('p-p-d-b.periode.create')
                        @livewire('p-p-d-b.periode.detail')
                        @livewire('p-p-d-b.periode.edit')
                        @livewire('p-p-d-b.periode.delete')
                    </div>

                    {{-- TAB GELOMBANG --}}
                    <div class="tab-pane fade" id="tab-gelombang" role="tabpanel">
                        @livewire('p-p-d-b.gelombang.index')
                        @livewire('p-p-d-b.gelombang.create')
                        @livewire('p-p-d-b.gelombang.detail')
                        @livewire('p-p-d-b.gelombang.edit')
                        @livewire('p-p-d-b.gelombang.delete')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection