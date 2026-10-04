@extends('template_keuangan.v_template')
@section('content')
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row mb-3 pb-1">
            <div class="col-12">
                <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-16 mb-1">Transaksi Pendapatan Lainnya</h4>
                        <p class="text-muted mb-0">Transaksi > Transaksi Pendapatan Lainnya</p>
                    </div>
                    @livewire('keuangan.parameter.jenjang')   
                </div><!-- end card header -->
            </div>
        </div>
        <div class="row">
            <div class="col-xxl-4">
                <div class="sticky-side-div">
                    @livewire('keuangan.transaksi-pendapatan-lainnya.input-transaksi')
                </div><!-- end card -->
            </div>
            <!--end col-->
            <div class="col-xxl-8">
                @livewire('keuangan.transaksi-pendapatan-lainnya.data-transaksi')
                @livewire('keuangan.transaksi-pendapatan-lainnya.detail')
                @livewire('keuangan.transaksi-pendapatan-lainnya.delete')
                @livewire('keuangan.transaksi-pendapatan-lainnya.edit')
            </div>
        </div>
    </div>
</div><!-- End Page-content -->
@endsection

