@extends('template_machine.v_template')
@section('content') 
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row mb-3 pb-1">
            <div class="col-12">
                <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-16 mb-1">Tagihan Siswa</h4>
                        <p class="text-muted mb-0">Keuangan > Tagihan Siswa</p>
                    </div>
                    @livewire('parameter.jenjang-tahun-ajar')   
                </div><!-- end card header -->
            </div>
            <!--end col-->
        </div>
        <div class="row">
            <div class="col-xxl-12">
                @livewire('tagihan-siswa.index')   
                @livewire('tagihan-siswa.create')   
                @livewire('tagihan-siswa.detail')   
                @livewire('tagihan-siswa.edit')   
                @livewire('tagihan-siswa.histori')   
                @livewire('tagihan-siswa.manage')   
                @livewire('transaksi-tagihan-siswa.delete-transaksi')   
            </div>  
        </div>
    </div>
</div>
@endsection

