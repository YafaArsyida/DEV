@extends('template_ppdb.v_template')

@section('content')
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body p-3 p-lg-4">
                        <div class="d-flex flex-column flex-xl-row align-items-xl-center justify-content-between gap-4">

                            {{-- INFORMASI PERIODE --}}
                            @livewire('p-p-d-b.admin.dashboard.informasi-periode')

                            {{-- PARAMETER JENJANG & TAHUN AJAR --}}
                            <div class="flex-shrink-0">
                                @livewire('p-p-d-b.parameter.jenjang-tahun-ajar')
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SUMMARY --}}
        <div class="row">
            <div class="col-xxl-12 mb-3">
                @livewire('p-p-d-b.admin.pengaturan.statistik-pengaturan')   
            </div>
        </div>

        {{-- MAIN CONFIGURATION --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            {{-- Header --}}
            <div class="card-header bg-transparent border-bottom p-3 p-lg-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-settings-3-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1">Pengaturan SPMB</h5>
                        <small class="text-muted">
                            Atur jadwal penerimaan dan persyaratan
                            yang akan ditampilkan pada portal SPMB.
                        </small>
                    </div>
                </div>
            </div>

            {{-- Body --}}
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

                    <li class="nav-item">
                        <a class="nav-link"
                            data-bs-toggle="tab"
                            href="#tab-jalur"
                            role="tab">
                            <i class="ri-route-line"></i>
                            Jalur
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link"
                            data-bs-toggle="tab"
                            href="#tab-pertanyaan"
                            role="tab">
                            <i class="ri-questionnaire-line me-1"></i>
                            Pertanyaan
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

                    {{-- TAB JALUR --}}
                    <div class="tab-pane fade" id="tab-jalur" role="tabpanel">
                        @livewire('p-p-d-b.jalur.index')
                        @livewire('p-p-d-b.jalur.create')
                        @livewire('p-p-d-b.jalur.detail')
                        @livewire('p-p-d-b.jalur.edit')
                        @livewire('p-p-d-b.jalur.delete')
                    </div>

                    {{-- TAB PERTANYAAN --}}
                    <div class="tab-pane fade" id="tab-pertanyaan" role="tabpanel">
                        @livewire('p-p-d-b.pertanyaan.index')
                        @livewire('p-p-d-b.pertanyaan.create')
                        @livewire('p-p-d-b.pertanyaan.detail')
                        @livewire('p-p-d-b.pertanyaan.edit')
                        @livewire('p-p-d-b.pertanyaan.delete')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection