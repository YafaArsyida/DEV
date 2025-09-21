@extends('template_machine.v_template')
@section('content') 
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row g-3">
            <div class="col-xxl-4 pe-1">
                @livewire('laporan-edu-pay-siswa.overview')   
                @livewire('laporan-edu-pay-siswa.saldo')   
                @livewire('laporan-edu-pay-siswa.export-saldo')   
                @livewire('laporan-edu-pay-siswa.withdraw')   
            </div>  
            <div class="col-xxl-8 ps-0">
                @livewire('parameter.filter-laporan-edu-pay')   
                @livewire('laporan-edu-pay-siswa.index')   
                @livewire('laporan-edu-pay-siswa.export')   
            </div>  
        </div>
    </div>
</div>
@endsection

