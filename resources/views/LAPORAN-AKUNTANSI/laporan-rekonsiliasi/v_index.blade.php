@extends('template_machine.v_template')
@section('content') 
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row mb-3 pb-1">
            <div class="col-12">
                <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-16 mb-1">Laporan Rekonsiliasi Kas dan Bank</h4>
                        <p class="text-muted mb-0">Laporan Akuntansi > Laporan Rekonsiliasi</p>
                    </div>
                    @livewire('parameter.jenjang-tahun-ajar')   
                </div><!-- end card header -->
            </div>
            <!--end col-->
        </div>
        <div class="row">
            <div class="col-xxl-6 pe-1">
                @livewire('akuntansi-mutasi-kas.index')   
            </div>  
            <div class="col-xxl-6 ps-0">
                @livewire('akuntansi-mutasi-bank.index')   
            </div>  
        </div>
    </div>
</div>
@endsection

