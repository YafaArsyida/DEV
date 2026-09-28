@extends('template_keuangan.v_template')
@section('content') 
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row mb-3 pb-1">
            <div class="col-12">
                <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-16 mb-1">Ekstrakurikuler Siswa</h4>
                        <p class="text-muted mb-0">Administrasi > Ekstrakurikuler Siswa</p>
                    </div>
                    @livewire('keuangan.parameter.jenjang-tahun-ajar')   
                </div><!-- end card header -->
            </div>
            <!--end col-->
        </div>
        <div class="row">
            {{-- Kelas --}}
            <div class="col-xxl-12">
                <div class="card mb-1">
                </div>
            </div>
            <!--end col-->
            <div class="col-xxl-5">
                <div class="sticky-side-div">
                    @livewire('keuangan.ekstrakurikuler.index')   
                </div>
                @livewire('keuangan.ekstrakurikuler.create')   
                @livewire('keuangan.ekstrakurikuler.edit')   
                @livewire('keuangan.ekstrakurikuler.delete')  
                @livewire('keuangan.ekstrakurikuler.detail')  
            </div>
            <div class="col-xxl-7">
                @livewire('keuangan.siswa-ekstrakurikuler.index')
                @livewire('keuangan.siswa-ekstrakurikuler.detail')
                @livewire('keuangan.siswa-ekstrakurikuler.edit')
                @livewire('keuangan.siswa-ekstrakurikuler.create')
            </div>
        </div>        
    </div>
</div>
@endsection

