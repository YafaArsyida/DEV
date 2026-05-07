@extends('template_machine.v_template')
@section('content')
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row mb-3 pb-1">
            <div class="col-12">
                <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-16 mb-1">Transaksi Tabungan Pegawai</h4>
                        <p class="text-muted mb-0">Transaksi Pegawai > Tabungan Pegawai</p>
                    </div>
                    @livewire('parameter.jenjang-tahun-pegawai')   
                </div><!-- end card header -->
            </div>
        </div>
        <div class="row">
            <div class="col-xxl-4">
                <div class="sticky-side-div">
                    @livewire('transaksi-tabungan-pegawai.data-pegawai')
                </div>
            </div>
            <!--end col-->
            <div class="col-xxl-8">
                @livewire('transaksi-tabungan-pegawai.data-tabungan')
                @livewire('transaksi-tabungan-siswa.delete')
                @livewire('transaksi-tabungan-siswa.edit')
            </div>
        </div>
    </div>
</div><!-- End Page-content -->
@endsection

