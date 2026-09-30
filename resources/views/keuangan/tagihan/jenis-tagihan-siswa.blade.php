@extends('template_keuangan.v_template')
@section('content') 
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row mb-3 pb-1">
            <div class="col-12">
                <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-16 mb-1">Kelola Jenis Tagihan</h4>
                        <p class="text-muted mb-0">Keuangan > Kelola Jenis Tagihan</p>
                    </div>
                    @livewire('keuangan.parameter.jenjang-tahun-ajar')   
                </div><!-- end card header -->
            </div>
            <!--end col-->
        </div>
        <div class="row">
            <div class="col-xxl-12">
                @livewire('keuangan.tagihan-siswa.edit')   
                @livewire('keuangan.tagihan-siswa.create')  

                @livewire('keuangan.tagihan-jenis.index')   
                @livewire('keuangan.tagihan-jenis.detail')   
                @livewire('keuangan.tagihan-jenis.manage')  
                
                @livewire('keuangan.tagihan-siswa.delete')   
            </div>  
        </div>
    </div>
</div>
@endsection

