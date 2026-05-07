@extends('template_machine_smartcanteen.v_template')
@section('content')

<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Administrasi Kantin</h4>
        
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">SmartCanteen</a></li>
                            <li class="breadcrumb-item active">Administrasi Kantin</li>
                        </ol>
                    </div>
        
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xxl-6">
                <div class="sticky-side-div">
                    @livewire('smart-canteen.kantin.index')
                </div>
                @livewire('smart-canteen.kantin.create')
                @livewire('smart-canteen.kantin.edit')
                @livewire('smart-canteen.kantin.detail')
                @livewire('smart-canteen.kantin.delete')
            </div>
            <div class="col-xxl-6">
                @livewire('smart-canteen.akses-kantin.index')
                @livewire('smart-canteen.akses-kantin.create')
                @livewire('smart-canteen.akses-kantin.detail')
                @livewire('smart-canteen.akses-kantin.edit')
                @livewire('smart-canteen.akses-kantin.delete')
                @livewire('pengguna.reset-password')
            </div>
        </div>
    </div>
</div>
@endsection

