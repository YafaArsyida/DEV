@extends('template_machine.v_template')
@section('content') 
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Kepegawaian</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Administrasi</a></li>
                            <li class="breadcrumb-item active">Kepegawaian</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xxl-4 pe-1">
                @livewire('jabatan.index')   
                @livewire('jabatan.create')   
                @livewire('jabatan.edit')   
                @livewire('jabatan.delete')   
            </div>
            <div class="col-xxl-8 ps-0">
                @livewire('pegawai.index')    
                @livewire('pegawai.create')    
                @livewire('pegawai.edit')    
                @livewire('pegawai.delete')    
                @livewire('pegawai.import')    
                @livewire('pegawai.import-kontak')    
                @livewire('pegawai.import-edu-card')    
            </div>
        </div>        
    </div>
</div>
@endsection

