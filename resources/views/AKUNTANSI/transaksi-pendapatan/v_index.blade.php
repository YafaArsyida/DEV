@extends('template_machine.v_template')
@section('content')
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="container-fluid" style="max-width: 100%">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                        <div class="flex-grow-1">
                            <h4 class="fs-16 mb-1">Transaksi Donasi</h4>
                            <p class="text-muted mb-0">Akuntansi > Transaksi Keuangan > Donasi</p>
                        </div>
                        @livewire('parameter.jenjang-tahun-ajar')   
                    </div><!-- end card header -->
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="tab-content text-muted">
                    @livewire('akuntansi-transaksi-pendapatan.index')   
                </div>
            </div>
        </div>
    </div><!-- container-fluid -->
</div><!-- End Page-content -->
@endsection

