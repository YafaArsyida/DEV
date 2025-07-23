@extends('template_machine.v_template')
@section('content') 
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Pengguna & Akses Jenjang</h4>

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
            {{-- PENGGUNA --}}
            <div class="col-xxl-6">
                @livewire('akses-jenjang.index')   
                {{-- @livewire('pengguna.index')    --}}
                @livewire('pengguna.create')
                @livewire('pengguna.edit')  
                @livewire('pengguna.delete')  
                @livewire('pengguna.detail')  
                @livewire('pengguna.reset-password')  
            </div>
            <!--end col-->
            {{-- TAHUN AJAR --}}
            <div class="col-xxl-7">
                {{-- AKSES MENU --}}

            </div>
            <!--end col-->
        </div>        
    </div>
</div>
@endsection

