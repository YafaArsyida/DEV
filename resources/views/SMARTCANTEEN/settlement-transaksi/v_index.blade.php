@extends('template_machine_smartcanteen.v_template')
@section('content') 
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row">
            <div class="col-xxl-12">
                @livewire('smart-canteen.settlement-transaksi.index')
                @livewire('smart-canteen.settlement-transaksi.detail-settlement')
            </div>
        </div>
    </div>
    @livewire('smart-canteen.settlement-transaksi.riwayat-settlement')
</div>
@endsection

