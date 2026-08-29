@extends('template_machine_smartcanteen.v_template_pos')
@section('content')

<div class="page-content" style="margin-top: 20px">
    <div class="container-fluid px-3" style="max-width: 100%;" >

        <div class="row g-3">

            {{-- Produk --}}
            <div class="col-xxl-8 col-xl-8 col-lg-8">
                @livewire('smart-canteen.transaksi-produk.index')
                @livewire('smart-canteen.transaksi-produk.histori')
                @livewire('smart-canteen.transaksi-produk.edit')
            </div>

            {{-- Keranjang --}}
            <div class="col-xxl-4 col-xl-4 col-lg-4">
                @livewire('smart-canteen.transaksi-produk.keranjang-produk')
            </div>

        </div>

    </div>
</div>

@endsection

