@extends('template_keuangan.v_template')
@section('content') 
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row mb-3 pb-1">
            <div class="col-12">
                <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-16 mb-1">Kelas Siswa</h4>
                        <p class="text-muted mb-0">Kesiswaan > Kelas Siswa</p>
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
            <div class="col-xxl-4">
                <div class="sticky-side-div">
                    @livewire('keuangan.kelas.index')   
                </div>
                @livewire('keuangan.kelas.create')   
                @livewire('keuangan.kelas.edit')   
                @livewire('keuangan.kelas.delete')   
                @livewire('keuangan.kelas.change')   
                @livewire('keuangan.kelas.promote')   
                @livewire('keuangan.kelas.detail')   
            </div>
            <div class="col-xxl-8">
                @livewire('keuangan.siswa.index')    
                @livewire('keuangan.siswa.detail')    
                @livewire('keuangan.siswa.create')    
                @livewire('keuangan.siswa.edit')    
                @livewire('keuangan.siswa.delete')    
                @livewire('keuangan.siswa.bulk-delete')    
                @livewire('keuangan.siswa.import')    
                {{-- @livewire('keuangan.siswa.export')     --}}
                @livewire('keuangan.siswa.import-telepon')    
                @livewire('keuangan.siswa.import-edu-card')    
            </div>
        </div>        
    </div>
</div>
@endsection

