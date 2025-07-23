@extends('template_machine.v_template')
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
                    @livewire('parameter.jenjang-tahun-ajar')   
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
            <div class="col-xxl-4 pe-1">
                @livewire('ekstrakurikuler.index')   
                @livewire('ekstrakurikuler.create')   
                @livewire('ekstrakurikuler.edit')   
                @livewire('ekstrakurikuler.delete')  
            </div>
            <div class="col-xxl-8 ps-0">
                @livewire('siswa-ekstrakurikuler.index')
                @livewire('siswa-ekstrakurikuler.detail')
                @livewire('siswa-ekstrakurikuler.edit')
                @livewire('siswa-ekstrakurikuler.create')
            </div>
        </div>        
    </div>
</div>
@endsection

