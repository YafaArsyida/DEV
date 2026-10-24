@extends('template_ppdb.v_template')

@section('content')
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        {{-- Informasi periode PPDB --}}
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body p-3 p-lg-4">
                        <div class="d-flex flex-column flex-xl-row align-items-xl-center justify-content-between gap-4">

                            {{-- INFORMASI PERIODE --}}
                            @livewire('p-p-d-b.admin.dashboard.informasi-periode')

                            {{-- PARAMETER JENJANG & TAHUN AJAR --}}
                            <div class="flex-shrink-0">
                                @livewire('p-p-d-b.parameter.jenjang-tahun-ajar')
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Statistik --}}
        <div class="row">
            <div class="col-xxl-12 mb-3">
                @livewire('p-p-d-b.admin.pendaftar.statistik-pendaftar')   
            </div>
        </div>

        {{-- Tabel pendaftar --}}
        <div class="row">
            <div class="col-xxl-12">
                @livewire('p-p-d-b.admin.pendaftar.index') 
                @livewire('p-p-d-b.admin.pendaftar.create')   
                @livewire('p-p-d-b.admin.pendaftar.detail')  
                
                
                @livewire('keuangan.pengguna.reset-password')  
            </div>
        </div>

    </div>
</div>

@endsection