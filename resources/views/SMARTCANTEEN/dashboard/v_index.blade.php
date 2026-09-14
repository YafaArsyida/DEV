@extends('template_machine_smartcanteen.v_template')
@section('content')

<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row">
            <div class="col-xxl-5">
                <div class="d-flex flex-column h-100">
                    <div class="row h-100">
                        <div class="col-12">
                            @livewire('smart-canteen.widget.c-t-a-menu-transaksi-smart-canteen')   
                        </div> <!-- end col-->
                    </div> <!-- end row-->

                    <div class="row">
                        @livewire('smart-canteen.widget.kartu-jumlah-produk')
                        @livewire('smart-canteen.widget.kartu-settlement')
                        @livewire('smart-canteen.widget.kartu-pendapatan')
                        @livewire('smart-canteen.widget.kartu-volume-transaksi')
                    </div> <!-- end row-->
                </div>
            </div> <!-- end col-->
            <div class="col-xxl-7">
                <div class="row h-100">
                    <div class="col-xl-8 col-md-6">
                        @livewire('smart-canteen.widget.produk-terlaris')   
                    </div>
                    <div class="col-xl-4 col-md-6">
                        @livewire('smart-canteen.widget.produk-aktif')   
                    </div>
                </div> <!-- end row-->
            </div><!-- end col -->
        </div>
         <div class="row">
            <div class="col-xxl-4">
                @livewire('smart-canteen.widget.kartu-top-jajan')
            </div>
            <div class="col-xxl-8">
                @livewire('smart-canteen.widget.kartu-jurnal-kantin')
            </div>
        </div><!-- end row -->
    </div>
</div>
@endsection

