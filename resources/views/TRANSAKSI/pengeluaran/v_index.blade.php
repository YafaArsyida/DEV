@extends('template_machine.v_template')
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
                    @livewire('parameter.jenjang-tahun-ajar')   
                </div><!-- end card header -->
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="tab-content text-muted">
                    @livewire('transaksi-pengeluaran.index')   
                    @livewire('transaksi-pengeluaran.edit')   
                    @livewire('transaksi-pengeluaran.delete')   
                </div>
            </div>
        </div>
    </div><!-- container-fluid -->
</div><!-- End Page-content -->
@endsection

