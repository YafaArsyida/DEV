@extends('template_machine.v_template')
@section('content')
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row mb-3 pb-1">
            <div class="col-12">
                <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-16 mb-1">Transaksi Tabungan Siswa</h4>
                        <p class="text-muted mb-0">Transaksi > Tabungan Siswa</p>
                    </div>
                    @livewire('parameter.jenjang-tahun-ajar-siswa')   
                </div><!-- end card header -->
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <!-- Tab panes -->
                <div class="tab-content text-muted">
                    <div class="tab-pane active" id="tabSiswaKelas" role="tabpanel">
                        <div class="row">
                            <div class="col-xxl-4 pe-1">
                                <div class="card">
                                    @livewire('transaksi-tabungan-siswa.delete')   
                                    @livewire('transaksi-tabungan-siswa.data-siswa')   
                                </div><!-- end card -->
                            </div>
                            <!--end col-->
                            <div class="col-xxl-8 ps-0">
                                <div class="sticky-side-div">
                                    @livewire('transaksi-tabungan-siswa.data-tabungan')   
                                </div>
                            </div>
                        </div>
                        <!--end row-->
                    </div>
                </div>
                <!--end tab-content-->
            </div>
            <!--end row-->
        </div>
    </div>
</div><!-- End Page-content -->
@endsection

