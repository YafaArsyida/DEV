@extends('template_keuangan.v_template')
@section('content')
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row mb-3 pb-1">
            <div class="col-12">
                <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-16 mb-1">Transaksi Pengeluaran</h4>
                        <p class="text-muted mb-0">Transaksi > Transaksi Pengeluaran</p>
                    </div>
                    @livewire('keuangan.parameter.jenjang')   
                </div><!-- end card header -->
            </div>
        </div>
        <div class="row">
            <div class="col-xxl-4">
                <div class="sticky-side-div">
                    @livewire('keuangan.transaksi-pengeluaran.input-transaksi')
                </div><!-- end card -->
            </div>
            <!--end col-->
            <div class="col-xxl-8">
                @livewire('keuangan.transaksi-pengeluaran.data-transaksi')
                @livewire('keuangan.transaksi-pengeluaran.detail')
                @livewire('keuangan.transaksi-pengeluaran.edit')
                @livewire('keuangan.transaksi-pengeluaran.delete')
            </div>
        </div>
    </div><!-- container-fluid -->
</div><!-- End Page-content -->
@endsection
