@extends('template_machine_smartpass.v_template')
@section('content') 
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <!-- start page title -->
        <<div class="row mb-3 pb-1">
            <div class="col-12">
                <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-16 mb-1">Laporan Presensi Pegawai</h4>
                        <p class="text-muted mb-0">SmartPass > Laporan Presensi Pegawai</p>
                    </div>
                    @livewire('parameter.jenjang')   
                </div><!-- end card header -->
            </div>
            <!--end col-->
        </div>
        <div class="row">
            <div class="col-xxl-12">
                @livewire('smart-pass.laporan-presensi-pegawai.index')
            </div>
        </div>        
    </div>
</div>
@endsection

