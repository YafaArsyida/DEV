@extends('template_machine.v_template')
@section('content') 
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Dokumen SIswa</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Administrasi</a></li>
                            <li class="breadcrumb-item active">Dokumen Siswa</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            {{-- Dokumen --}}
            <div class="col-xxl-12">
                <div class="card mb-1">
                    @livewire('parameter.jenjang-tahun-ajar')   
                </div>
            </div>
            <!--end col-->
            <div class="col-xxl-12">
                @livewire('dokumen-siswa.index')    
            </div>
        </div>        
    </div>
</div>
@endsection

