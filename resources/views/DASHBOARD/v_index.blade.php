@extends('template_machine.v_template')
@section('content')

@php
    $title = "Dashboard"
@endphp
@push('info-page')
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Pages</a></li>
            <li class="breadcrumb-item active">{{ $title ?? "SmartGate" }}</li>
        </ol>
    </div>
@endpush
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row mb-3 pb-1">
            <div class="col-12">
                <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-16 mb-1">Dashboard Tata Usaha</h4>
                        <p class="text-muted mb-0">Dashboard > Tata Usaha</p>
                    </div>
                    @livewire('parameter.jenjang-tahun-ajar')   
                </div><!-- end card header -->
            </div>
            <!--end col-->
        </div>
        <div class="row">
            <div class="col-xxl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex mb-3">
                            <div class="flex-grow-1">
                                <lord-icon src="https://cdn.lordicon.com/fhtaantg.json" trigger="loop" colors="primary:#405189,secondary:#0ab39c" style="width:55px;height:55px">
                                </lord-icon>
                            </div>
                            <div class="flex-shrink-0">
                                <a href="javascript:void(0);" class="badge bg-warning-subtle text-warning badge-border">BTC</a>
                                <a href="javascript:void(0);" class="badge bg-info-subtle text-info badge-border">ETH</a>
                                <a href="javascript:void(0);" class="badge bg-primary-subtle text-primary badge-border">USD</a>
                                <a href="javascript:void(0);" class="badge bg-danger-subtle text-danger badge-border">EUR</a>
                            </div>
                        </div>
                        <h3 class="mb-2">$<span class="counter-value" data-target="74858">0</span><small class="text-muted fs-13">.68k</small></h3>
                        <h6 class="text-muted mb-0">Available Balance (USD)</h6>
                    </div>
                </div>
                <!--end card-->
            </div>
            <!--end col-->
            <div class="col-xxl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex mb-3">
                            <div class="flex-grow-1">
                                <lord-icon src="https://cdn.lordicon.com/qhviklyi.json" trigger="loop" colors="primary:#405189,secondary:#0ab39c" style="width:55px;height:55px"></lord-icon>
                            </div>
                            <div class="flex-shrink-0">
                                <a href="javascript:void(0);" class="badge bg-warning-subtle text-warning badge-border">BTC</a>
                                <a href="javascript:void(0);" class="badge bg-info-subtle text-info badge-border">ETH</a>
                                <a href="javascript:void(0);" class="badge bg-primary-subtle text-primary badge-border">USD</a>
                                <a href="javascript:void(0);" class="badge bg-danger-subtle text-danger badge-border">EUR</a>
                            </div>
                        </div>
                        <h3 class="mb-2">$<span class="counter-value" data-target="74361">0</span><small class="text-muted fs-13">.34k</small></h3>
                        <h6 class="text-muted mb-0">Send (Previous Month)</h6>
                    </div>
                </div>
                <!--end card-->
            </div>
            <!--end col-->
            <div class="col-xxl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex mb-3">
                            <div class="flex-grow-1">
                                <lord-icon src="https://cdn.lordicon.com/yeallgsa.json" trigger="loop" colors="primary:#405189,secondary:#0ab39c" style="width:55px;height:55px">
                                </lord-icon>
                            </div>
                            <div class="flex-shrink-0">
                                <a href="javascript:void(0);" class="badge bg-warning-subtle text-warning badge-border">BTC</a>
                                <a href="javascript:void(0);" class="badge bg-info-subtle text-info badge-border">ETH</a>
                                <a href="javascript:void(0);" class="badge bg-primary-subtle text-primary badge-border">USD</a>
                                <a href="javascript:void(0);" class="badge bg-danger-subtle text-danger badge-border">EUR</a>
                            </div>
                        </div>
                        <h3 class="mb-2">$<span class="counter-value" data-target="97685">0</span><small class="text-muted fs-13">.22k</small></h3>
                        <h6 class="text-muted mb-0">Receive (Previous Month)</h6>
                    </div>
                </div>
                <!--end card-->
            </div>
            <!--end col-->
            <div class="col-xxl-3 col-md-6">
                <div class="swiper default-swiper rounded">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="card card-animate overflow-hidden">
                                <div class="card-body bg-warning-subtle">
                                    <div class="d-flex mb-3">
                                        <div class="flex-grow-1">
                                            <lord-icon src="https://cdn.lordicon.com/vaeagfzc.json" trigger="loop" colors="primary:#405189,secondary:#0ab39c" style="width:55px;height:55px">
                                            </lord-icon>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <a href="javascript:void(0);" class="fw-medium">Bitcoin (BTC)</a>
                                        </div>
                                    </div>
                                    <h3 class="mb-2">$245<small class="text-muted fs-13">.65k</small></h3>
                                    <h6 class="text-muted mb-0">Send - Receive (Previous Month)</h6>
                                </div>
                            </div>
                            <!--end card-->
                        </div>
                        <div class="swiper-slide">
                            <div class="card card-animate">
                                <div class="card-body bg-warning-subtle">
                                    <div class="d-flex mb-3">
                                        <div class="flex-grow-1">
                                            <lord-icon src="https://cdn.lordicon.com/vaeagfzc.json" trigger="loop" colors="primary:#405189,secondary:#0ab39c" style="width:55px;height:55px">
                                            </lord-icon>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <a href="javascript:void(0);" class="fw-medium">Ethereum (ETH)</a>
                                        </div>
                                    </div>
                                    <h3 class="mb-2">$24<small class="text-muted fs-13">.74k</small></h3>
                                    <h6 class="text-muted mb-0">Send - Receive (Previous Month)</h6>
                                </div>
                            </div>
                            <!--end card-->
                        </div>
                        <div class="swiper-slide">
                            <div class="card card-animate overflow-hidden">
                                <div class="card-body bg-warning-subtle">
                                    <div class="d-flex mb-3">
                                        <div class="flex-grow-1">
                                            <lord-icon src="https://cdn.lordicon.com/vaeagfzc.json" trigger="loop" colors="primary:#405189,secondary:#0ab39c" style="width:55px;height:55px">
                                            </lord-icon>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <a href="javascript:void(0);" class="fw-medium">Monero (XMR)</a>
                                        </div>
                                    </div>
                                    <h3 class="mb-2">$124<small class="text-muted fs-13">.36k</small></h3>
                                    <h6 class="text-muted mb-0">Send - Receive (Previous Month)</h6>
                                </div>
                            </div>
                            <!--end card-->
                        </div>
                    </div>
                </div>
                <!--end swiper-->
            </div>
            <!--end col-->
        </div>
        <!--end row-->
        <div class="row">
            <div class="col-xxl-6">
                @livewire('laporan-pembayaran-tagihan-siswa.kategori')   
            </div>
            <div class="col-xxl-6">
                @livewire('laporan-pembayaran-tagihan-siswa.jenis')   
            </div>
        </div>
    </div>
</div>

@endsection

