@extends('template_machine_smartcanteen.v_template')
@section('content') 
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row g-3">
            <div class="col-xxl-4">
                @livewire('smart-canteen.laporan-transaksi.overview')   
                @livewire('smart-canteen.laporan-transaksi.top-jajan')   
            </div>  
            <div class="col-xxl-8">
                @livewire('smart-canteen.laporan-transaksi.index')   
            </div>  
        </div>
    </div>
    @livewire('smart-canteen.settlement-transaksi.riwayat-settlement')
    @livewire('smart-canteen.settlement-transaksi.detail-settlement')
</div>
@endsection

