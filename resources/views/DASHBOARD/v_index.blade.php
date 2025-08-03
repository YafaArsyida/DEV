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
            <div class="col-xxl-5">
                <div class="d-flex flex-column h-100">
                    <div class="row h-100">
                        <div class="col-12">
                            @livewire('widget.c-t-a-menu-transaksi-tagihan-siswa')   
                        </div> <!-- end col-->
                    </div> <!-- end row-->

                    <div class="row">
                        {{-- kartu jumlah siswa --}}
                        @livewire('widget.kartu-jumlah-siswa')   
                        @livewire('widget.kartu-jumlah-tagihan-siswa')   
                        @livewire('widget.kartu-jumlah-jurnal-pendapatan')   
                        @livewire('widget.kartu-jumlah-jurnal-pengeluaran')   
                    </div> <!-- end row-->
                </div>
            </div> <!-- end col-->
            <div class="col-xxl-7">
                <div class="row h-100">
                    @livewire('widget.overview-tagihan-siswa')   
                    <div class="col-xl-6">
                        <div class="card card-height-100">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Kategori Tagihan Siswa</h4>
                                <div>
                                    <button type="button" class="btn btn-soft-secondary btn-sm shadow-none">
                                        ALL
                                    </button>
                                    <button type="button" class="btn btn-soft-primary btn-sm shadow-none">
                                        1M
                                    </button>
                                    <button type="button" class="btn btn-soft-secondary btn-sm shadow-none">
                                        6M
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">                    
                                <div class="px-2 py-2 mt-4">
                                    <p class="mb-1">Canada <span class="float-end">75%</span></p>
                                    <div class="progress mt-2" style="height: 6px;">
                                        <div class="progress-bar progress-bar-striped bg-primary" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="75"></div>
                                    </div>
                    
                                    <p class="mt-3 mb-1">Greenland <span class="float-end">47%</span>
                                    </p>
                                    <div class="progress mt-2" style="height: 6px;">
                                        <div class="progress-bar progress-bar-striped bg-primary" role="progressbar" style="width: 47%" aria-valuenow="47" aria-valuemin="0" aria-valuemax="47"></div>
                                    </div>
                                    <p class="mt-3 mb-1">Greenland <span class="float-end">47%</span>
                                    </p>
                                    <div class="progress mt-2" style="height: 6px;">
                                        <div class="progress-bar progress-bar-striped bg-primary" role="progressbar" style="width: 47%" aria-valuenow="47" aria-valuemin="0" aria-valuemax="47"></div>
                                    </div>
                                    <p class="mt-3 mb-1">Greenland <span class="float-end">47%</span>
                                    </p>
                                    <div class="progress mt-2" style="height: 6px;">
                                        <div class="progress-bar progress-bar-striped bg-primary" role="progressbar" style="width: 47%" aria-valuenow="47" aria-valuemin="0" aria-valuemax="47"></div>
                                    </div>
                                    <p class="mt-3 mb-1">Greenland <span class="float-end">47%</span>
                                    </p>
                                    <div class="progress mt-2" style="height: 6px;">
                                        <div class="progress-bar progress-bar-striped bg-primary" role="progressbar" style="width: 47%" aria-valuenow="47" aria-valuemin="0" aria-valuemax="47"></div>
                                    </div>
                                    <p class="mt-3 mb-1">Greenland <span class="float-end">47%</span>
                                    </p>
                                    <div class="progress mt-2" style="height: 6px;">
                                        <div class="progress-bar progress-bar-striped bg-primary" role="progressbar" style="width: 47%" aria-valuenow="47" aria-valuemin="0" aria-valuemax="47"></div>
                                    </div>
                    
                                    <p class="mt-3 mb-1">Russia <span class="float-end">82%</span></p>
                                    <div class="progress mt-2" style="height: 6px;">
                                        <div class="progress-bar progress-bar-striped bg-primary" role="progressbar" style="width: 82%" aria-valuenow="82" aria-valuemin="0" aria-valuemax="82"></div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end card -->
                    </div> <!-- end col-->

                </div> <!-- end row-->
            </div><!-- end col -->
        </div>
         <div class="row">
            <div class="col-xl-4">
                <div class="card card-height-100">
                    <div class="card-header align-items-center border-0 d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Transaksi</h4>
                        <div class="flex-shrink-0">
                            <ul class="nav justify-content-end nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#tab-transaksi-pendapatan" role="tab" aria-selected="false">Pendapatan</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#tab-transaksi-pengeluaran" role="tab" aria-selected="true">Pengeluaran</a>
                                </li>
                            </ul><!-- end ul -->
                        </div>
                    </div><!-- end cardheader -->
                    <div class="card-body p-0">
                        <div class="tab-content p-0">
                            <div class="tab-pane active" id="tab-transaksi-pendapatan" role="tabpanel">
                                <div class="p-3 bg-warning-subtle">
                                    <div class="float-end ms-2">
                                        <h6 class="text-warning mb-0">USD Balance : <span class="text-body">$12,426.07</span></h6>
                                    </div>
                                    <h6 class="mb-0 text-danger">Buy Coin</h6>
                                </div>
                                <div class="p-3">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label>Currency :</label>
                                                <select class="form-select">
                                                    <option>BTC</option>
                                                    <option>ETH</option>
                                                    <option>LTC</option>
                                                </select>
                                            </div>
                                        </div><!-- end col -->
                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label>Payment Method :</label>
                                                <select class="form-select">
                                                    <option>Wallet Balance</option>
                                                    <option>Credit / Debit Card</option>
                                                    <option>PayPal</option>
                                                    <option>Payoneer</option>
                                                </select>
                                            </div>
                                        </div><!-- end col -->
                                    </div><!-- end row -->
                                    <div>
                                        <div class="input-group mb-3">
                                            <label class="input-group-text">Amount</label>
                                            <input type="text" class="form-control" placeholder="0">
                                        </div>

                                        <div class="input-group mb-3">
                                            <label class="input-group-text">Price</label>
                                            <input type="text" class="form-control" placeholder="2.045585">
                                            <label class="input-group-text">$</label>
                                        </div>

                                        <div class="input-group mb-0">
                                            <label class="input-group-text">Total</label>
                                            <input type="text" class="form-control" placeholder="2700.16">
                                        </div>
                                    </div>
                                    <div class="mt-3 pt-2">
                                        <div class="d-flex mb-2">
                                            <div class="flex-grow-1">
                                                <p class="fs-13 mb-0">Transaction Fees<span class="text-muted ms-1 fs-11">(0.05%)</span></p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <h6 class="mb-0">$1.08</h6>
                                            </div>
                                        </div>
                                        <div class="d-flex mb-2">
                                            <div class="flex-grow-1">
                                                <p class="fs-13 mb-0">Minimum Received<span class="text-muted ms-1 fs-11">(2%)</span></p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <h6 class="mb-0">$7.85</h6>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="fs-13 mb-0">Estimated Rate</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <h6 class="mb-0">1 BTC ~ $34572.00</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3 pt-2">
                                        <button type="button" class="btn btn-primary w-100">Buy Coin</button>
                                    </div>
                                </div>
                            </div><!-- end tabpane -->

                            <div class="tab-pane" id="tab-transaksi-pengeluaran" role="tabpanel">
                                <div class="p-3 bg-warning-subtle">
                                    <div class="float-end ms-2">
                                        <h6 class="text-warning mb-0">USD Balance : <span class="text-body">$12,426.07</span></h6>
                                    </div>
                                    <h6 class="mb-0 text-danger">Sell Coin</h6>
                                </div>
                                <div class="p-3">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label>Currency :</label>
                                                <select class="form-select">
                                                    <option>BTC</option>
                                                    <option>ETH</option>
                                                    <option>LTC</option>
                                                </select>
                                            </div>
                                        </div><!-- end col -->
                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label>Email :</label>
                                                <input type="email" class="form-control" placeholder="example@email.com">
                                            </div>
                                        </div><!-- end col -->
                                    </div><!-- end row -->
                                    <div>
                                        <div class="input-group mb-3">
                                            <label class="input-group-text">Amount</label>
                                            <input type="text" class="form-control" placeholder="0">
                                        </div>
                                        <div class="input-group mb-3">
                                            <label class="input-group-text">Price</label>
                                            <input type="text" class="form-control" placeholder="2.045585">
                                            <label class="input-group-text">$</label>
                                        </div>
                                        <div class="input-group mb-0">
                                            <label class="input-group-text">Total</label>
                                            <input type="text" class="form-control" placeholder="2700.16">
                                        </div>
                                    </div>
                                    <div class="mt-3 pt-2">
                                        <div class="d-flex mb-2">
                                            <div class="flex-grow-1">
                                                <p class="fs-13 mb-0">Transaction Fees<span class="text-muted ms-1 fs-11">(0.05%)</span></p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <h6 class="mb-0">$1.08</h6>
                                            </div>
                                        </div>
                                        <div class="d-flex mb-2">
                                            <div class="flex-grow-1">
                                                <p class="fs-13 mb-0">Minimum Received<span class="text-muted ms-1 fs-11">(2%)</span></p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <h6 class="mb-0">$7.85</h6>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="fs-13 mb-0">Estimated Rate</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <h6 class="mb-0">1 BTC ~ $34572.00</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3 pt-2">
                                        <button type="button" class="btn btn-danger w-100">Sell Coin</button>
                                    </div>
                                </div>
                            </div><!-- end tab pane -->
                        </div><!-- end tab pane -->
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col -->

            <div class="col-xl-4 col-md-6">
                <div class="card card-height-100">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Top Referrals Pages</h4>
                        <div class="flex-shrink-0">
                            <button type="button" class="btn btn-soft-primary btn-sm shadow-none">
                                Export Report
                            </button>
                        </div>
                    </div>

                    <div class="card-body">

                        <div class="row align-items-center">
                            <div class="col-6">
                                <h6 class="text-muted text-uppercase fw-semibold text-truncate fs-12 mb-3">
                                    Total Referrals Page</h6>
                                <h4 class="fs- mb-0">725,800</h4>
                                <p class="mb-0 mt-2 text-muted"><span class="badge bg-success-subtle text-success mb-0">
                                        <i class="ri-arrow-up-line align-middle"></i> 15.72 %
                                    </span> vs. previous month</p>
                            </div><!-- end col -->
                            <div class="col-6">
                                <div class="text-center">
                                    <img src="assets/images/illustrator-1.png" class="img-fluid" alt="">
                                </div>
                            </div><!-- end col -->
                        </div><!-- end row -->
                        <div class="mt-3 pt-2">
                            <div class="progress progress-lg rounded-pill">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                <div class="progress-bar bg-info" role="progressbar" style="width: 18%" aria-valuenow="18" aria-valuemin="0" aria-valuemax="100"></div>
                                <div class="progress-bar bg-success" role="progressbar" style="width: 22%" aria-valuenow="22" aria-valuemin="0" aria-valuemax="100"></div>
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 16%" aria-valuenow="16" aria-valuemin="0" aria-valuemax="100"></div>
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 19%" aria-valuenow="19" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div><!-- end -->

                        <div class="mt-3 pt-2">
                            <div class="d-flex mb-2">
                                <div class="flex-grow-1">
                                    <p class="text-truncate text-muted fs-14 mb-0"><i class="mdi mdi-circle align-middle text-primary me-2"></i>www.google.com
                                    </p>
                                </div>
                                <div class="flex-shrink-0">
                                    <p class="mb-0">24.58%</p>
                                </div>
                            </div><!-- end -->
                            <div class="d-flex mb-2">
                                <div class="flex-grow-1">
                                    <p class="text-truncate text-muted fs-14 mb-0"><i class="mdi mdi-circle align-middle text-info me-2"></i>www.youtube.com
                                    </p>
                                </div>
                                <div class="flex-shrink-0">
                                    <p class="mb-0">17.51%</p>
                                </div>
                            </div><!-- end -->
                            <div class="d-flex mb-2">
                                <div class="flex-grow-1">
                                    <p class="text-truncate text-muted fs-14 mb-0"><i class="mdi mdi-circle align-middle text-success me-2"></i>www.meta.com
                                    </p>
                                </div>
                                <div class="flex-shrink-0">
                                    <p class="mb-0">23.05%</p>
                                </div>
                            </div><!-- end -->
                            <div class="d-flex mb-2">
                                <div class="flex-grow-1">
                                    <p class="text-truncate text-muted fs-14 mb-0"><i class="mdi mdi-circle align-middle text-warning me-2"></i>www.medium.com
                                    </p>
                                </div>
                                <div class="flex-shrink-0">
                                    <p class="mb-0">12.22%</p>
                                </div>
                            </div><!-- end -->
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-truncate text-muted fs-14 mb-0"><i class="mdi mdi-circle align-middle text-danger me-2"></i>Other
                                    </p>
                                </div>
                                <div class="flex-shrink-0">
                                    <p class="mb-0">17.58%</p>
                                </div>
                            </div><!-- end -->
                        </div><!-- end -->

                        <div class="mt-2 text-center">
                            <a href="javascript:void(0);" class="text-muted text-decoration-underline">Show
                                All</a>
                        </div>

                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col -->

            <div class="col-xl-4 col-md-6">
                <div class="card card-height-100">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Top Pages</h4>
                        <div class="flex-shrink-0">
                            <div class="dropdown card-header-dropdown">
                                <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="text-muted fs-16"><i class="mdi mdi-dots-vertical align-middle"></i></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#">Today</a>
                                    <a class="dropdown-item" href="#">Last Week</a>
                                    <a class="dropdown-item" href="#">Last Month</a>
                                    <a class="dropdown-item" href="#">Current Year</a>
                                </div>
                            </div>
                        </div>
                    </div><!-- end card header -->
                    <div class="card-body">
                        <div class="table-responsive table-card">
                            <table class="table align-middle table-borderless table-centered table-nowrap mb-0">
                                <thead class="text-muted table-light">
                                    <tr>
                                        <th scope="col" style="width: 62;">Active Page</th>
                                        <th scope="col">Active</th>
                                        <th scope="col">Users</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <a href="javascript:void(0);">/themesbrand/skote-25867</a>
                                        </td>
                                        <td>99</td>
                                        <td>25.3%</td>
                                    </tr><!-- end -->
                                    <tr>
                                        <td>
                                            <a href="javascript:void(0);">/dashonic/chat-24518</a>
                                        </td>
                                        <td>86</td>
                                        <td>22.7%</td>
                                    </tr><!-- end -->
                                    <tr>
                                        <td>
                                            <a href="javascript:void(0);">/skote/timeline-27391</a>
                                        </td>
                                        <td>64</td>
                                        <td>18.7%</td>
                                    </tr><!-- end -->
                                    <tr>
                                        <td>
                                            <a href="javascript:void(0);">/themesbrand/minia-26441</a>
                                        </td>
                                        <td>53</td>
                                        <td>14.2%</td>
                                    </tr><!-- end -->
                                    <tr>
                                        <td>
                                            <a href="javascript:void(0);">/dashon/dashboard-29873</a>
                                        </td>
                                        <td>33</td>
                                        <td>12.6%</td>
                                    </tr><!-- end -->
                                    <tr>
                                        <td>
                                            <a href="javascript:void(0);">/doot/chats-29964</a>
                                        </td>
                                        <td>20</td>
                                        <td>10.9%</td>
                                    </tr><!-- end -->
                                    <tr>
                                        <td>
                                            <a href="javascript:void(0);">/minton/pages-29739</a>
                                        </td>
                                        <td>10</td>
                                        <td>07.3%</td>
                                    </tr><!-- end -->
                                </tbody><!-- end tbody -->
                            </table><!-- end table -->
                        </div><!-- end -->
                    </div><!-- end cardbody -->
                </div><!-- end card -->
            </div><!-- end col -->
        </div><!-- end row -->
        <!--end row-->
         <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row g-4 align-items-center">
                            <div class="col-sm-auto">
                                <div>
                                    <h4 class="card-title mb-0 flex-grow-1">Jurnal Keuangan Hari Ini</h4>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <div class="search-box ms-2">
                                        <input type="text" class="form-control" id="searchResultList" placeholder="Search for jobs...">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="recomended-jobs" class="table-card"></div>
                    </div>
                </div>
            </div><!--end col-->
        </div><!--end row-->
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

