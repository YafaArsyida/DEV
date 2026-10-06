@extends('template_keuangan.v_template')
@section('content') 
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row mb-3 pb-1">
            <div class="col-12">
                <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-16 mb-1">Laporan Pengeluaran</h4>
                        <p class="text-muted mb-0">Laporan Akuntansi > Laporan Pengeluaran</p>
                    </div>
                    @livewire('keuangan.parameter.jenjang')   
                </div><!-- end card header -->
            </div>
            <!--end col-->
        </div>
          <div class="row justify-content-center">
            <div class="col-xxl-12">
                @livewire('keuangan.akuntansi-laporan-pengeluaran.index')   
            </div>
        </div>
    </div>
</div>
@endsection

