@extends('template_machine_smartcanteen.v_template')
@section('content')

<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row g-3">
            <div class="col-xxl-12">
                @livewire('smart-canteen.kategori-produk-kantin.index')
                @livewire('smart-canteen.kategori-produk-kantin.create')
                @livewire('smart-canteen.kategori-produk-kantin.edit')
                @livewire('smart-canteen.kategori-produk-kantin.delete')

                @livewire('smart-canteen.produk-kantin.index')   
                @livewire('smart-canteen.produk-kantin.create')   
                @livewire('smart-canteen.produk-kantin.detail')   
                @livewire('smart-canteen.produk-kantin.edit')   
                @livewire('smart-canteen.produk-kantin.delete')
            </div>
        </div>   
    </div>
</div>
@endsection

