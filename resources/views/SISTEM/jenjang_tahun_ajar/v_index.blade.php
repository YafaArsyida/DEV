@extends('template_machine.v_template')
@section('content') 
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">jenjang & tahun ajar</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Manajemen Sistem</a></li>
                            <li class="breadcrumb-item active">Jenjang & Tahun Ajar</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            {{-- JENJANG --}}
            <div class="col-xxl-5">
                @livewire('jenjang.create')
                @livewire('jenjang.index')   
                @livewire('jenjang.edit')  
                @livewire('jenjang.delete')  
            </div>
            <!--end col-->
            {{-- TAHUN AJAR --}}
            <div class="col-xxl-7">
                @livewire('tahun-ajar.create')   
                @livewire('tahun-ajar.index')   
                @livewire('tahun-ajar.edit')   
                @livewire('tahun-ajar.delete')  

            </div>
            <!--end col-->
        </div>        
    </div>
</div>
@endsection

