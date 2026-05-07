@extends('template_machine.v_template')
@section('content') 
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row mb-3 pb-1">
            <div class="col-12">
                <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-16 mb-1">Laporan EduPay Pegawai</h4>
                        <p class="text-muted mb-0">Laporan > EduPay Pegawai</p>
                    </div>
                    @livewire('parameter.jenjang-tahun-ajar')   
                </div><!-- end card header -->
            </div>
            <!--end col-->
        </div>
        <div class="row">
            <div class="col-xxl-4">
                @livewire('laporan-edu-pay-pegawai.overview')   
                <div class="sticky-side-div">
                    @livewire('laporan-edu-pay-pegawai.saldo')   
                </div>
                @livewire('laporan-edu-pay-pegawai.withdraw')   
            </div>  
            <div class="col-xxl-8">
                @livewire('parameter.filter-laporan-edu-pay')   
                @livewire('laporan-edu-pay-pegawai.index')   
            </div>  
        </div>
    </div>
</div>
@endsection

