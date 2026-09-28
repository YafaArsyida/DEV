@extends('template_akademik.v_template')
@section('content') 
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Siswa</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Akademik</a></li>
                            <li class="breadcrumb-item active">Siswa</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xxl-12">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-3 p-lg-4">
                        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                            {{-- LABEL --}}
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-3">
                                            <i class="ri-equalizer-line fs-5"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <h6 class="mb-0 fw-semibold">
                                            Parameter Akademik
                                        </h6>

                                        <small class="text-muted">
                                            Pilih jenjang dan tahun ajaran
                                        </small>
                                    </div>
                                </div>
                            </div>

                            {{-- PARAMETER --}}
                            <div>
                                @livewire('keuangan.parameter.jenjang-tahun-ajar')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xxl-12">
                @livewire('akademik.siswa.index')    
                @livewire('akademik.siswa.detail')    
                @livewire('akademik.siswa.create')    
                @livewire('akademik.siswa.edit')    
                @livewire('akademik.siswa.delete')    
                @livewire('akademik.siswa.bulk-delete')    
                @livewire('akademik.siswa.import')    
                @livewire('akademik.siswa.import-telepon')    
                @livewire('akademik.siswa.import-edu-card')    
            </div>
        </div>        
    </div>
</div>
@endsection

