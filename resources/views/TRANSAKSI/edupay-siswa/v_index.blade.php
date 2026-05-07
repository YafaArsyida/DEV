@extends('template_machine.v_template')
@section('content')
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row mb-3 pb-1">
            <div class="col-12">
                <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-16 mb-1">Transaksi EduPay Siswa</h4>
                        <p class="text-muted mb-0">Transaksi > EduPay Siswa</p>
                    </div>
                    @livewire('parameter.jenjang-tahun-ajar-siswa')
                </div><!-- end card header -->
            </div>
        </div>
        <div class="row">
            <div class="col-xxl-4">
                <div class="sticky-side-div">
                    @livewire('transaksi-edu-pay-siswa.data-siswa')
                </div><!-- end card -->
            </div>
            <!--end col-->
            <div class="col-xxl-8">
                @livewire('transaksi-edu-pay-siswa.data-edu-pay')
                @livewire('transaksi-edu-pay-siswa.edit')
                @livewire('transaksi-edu-pay-siswa.delete')
            </div>
        </div>
    </div><!-- container-fluid -->
</div><!-- End Page-content -->
@endsection

