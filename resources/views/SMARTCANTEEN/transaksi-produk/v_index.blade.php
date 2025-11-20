@extends('template_machine_smartcanteen.v_template')
@section('content')

<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row">
            <div class="col-lg-12">
                <!-- Tab panes -->
                <div class="tab-content text-muted">
                    <div class="tab-pane active" id="tabSiswaKelas" role="tabpanel">
                        <div class="row">
                            <div class="col-xxl-8 col-md-8 pe-1">
                                <div class="card">
                                    @livewire('smart-canteen.transaksi-produk.index')   
                                </div><!-- end card -->
                            </div>
                            <!--end col-->
                            <div class="col-xxl-4 col-md-4 ps-0">
                                <div class="sticky-side-div">
                                    @livewire('smart-canteen.transaksi-produk.keranjang-produk')   
                                </div>
                            </div>
                        </div>
                        <!--end row-->
                    </div>
                </div>
                <!--end tab-content-->
            </div>
        </div>
    </div>
</div>
@endsection

