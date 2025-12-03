@extends('template_machine_smartcanteen.v_template')
@section('content') 
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row g-3">
            <div class="col-xxl-4 pe-1">
                @livewire('smart-canteen.laporan-transaksi.overview')   
                @livewire('smart-canteen.laporan-transaksi.top-jajan')   
            </div>  
            <div class="col-xxl-8 ps-0">
                @livewire('smart-canteen.laporan-transaksi.index')   
                @livewire('smart-canteen.settlement-transaksi.detail-settlement')
            </div>  
        </div>
    </div>
     <!-- Offcanvas wrapper statis -->
    <div style="width: 700px;" 
        class="offcanvas offcanvas-start" 
        id="offcanvasSettlement" 
        data-bs-scroll="true" 
        data-bs-backdrop="false" 
        aria-labelledby="offcanvasSettlementLabel">

        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title" id="offcanvasSettlementLabel">Riwayat Settlement</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
        </div>

        <div class="offcanvas-body">
            {{-- hanya isinya Livewire --}}
            @livewire('smart-canteen.settlement-transaksi.riwayat-settlement')
        </div>
    </div>
</div>
@endsection

