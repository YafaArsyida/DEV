@extends('template_keuangan.v_template')
@section('content') 
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Konfigurasi Rekening</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Akuntansi</a></li>
                            <li class="breadcrumb-item active">Konfigurasi Rekening</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <div class="row">
            <!--end col-->
            <div class="col-xxl-4 pe-1">
                @livewire('keuangan.akuntansi-kelompok-rekening.index')   
                @livewire('keuangan.akuntansi-kelompok-rekening.create')   
                @livewire('keuangan.akuntansi-kelompok-rekening.edit')   
                @livewire('keuangan.akuntansi-kelompok-rekening.delete')   
            </div>
            <div class="col-xxl-8 ps-0">
                @livewire('keuangan.akuntansi-rekening.index')   
                @livewire('keuangan.akuntansi-rekening.create')   
                @livewire('keuangan.akuntansi-rekening.edit')   
                @livewire('keuangan.akuntansi-rekening.delete')   
            </div>
        </div>        
    </div>
</div>
@endsection

