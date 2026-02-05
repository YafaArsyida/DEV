@extends('template_machine_smartpass.v_template')
@section('content')

<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row mb-3 pb-1">
            <div class="col-12">
                <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-16 mb-1">Dashboard SmartPass</h4>
                        <p class="text-muted mb-0">Dashboard > SmartPass</p>
                    </div>
                    @livewire('parameter.jenjang')
                </div><!-- end card header -->
            </div>
            <!--end col-->
        </div>
        <div class="row">
            <div class="col-xxl-5">
                <div class="d-flex flex-column h-100">
                    <div class="row h-100">
                        <div class="col-12">
                            @livewire('smart-pass.widget.c-t-a-menu-presensi-pegawai')
                        </div>
                    </div> <!-- end row-->

                    <div class="row">
                        @livewire('smart-pass.widget.c-t-a-laporan-presensi-pegawai')
                        @livewire('smart-pass.widget.c-t-a-laporan-presensi-siswa')
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="fw-bold mb-3">
                                        <i class="ri-dashboard-line me-1"></i>
                                        Ringkasan Presensi Hari Ini
                                    </h6>
                    
                                    <div class="row text-center">
                                        <div class="col-6 col-md-3">
                                            <h4 class="fw-bold mb-0 text-success">42</h4>
                                            <small class="text-muted">Hadir</small>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <h4 class="fw-bold mb-0 text-warning">5</h4>
                                            <small class="text-muted">Terlambat</small>
                                        </div>
                                        <div class="col-6 col-md-3 mt-3 mt-md-0">
                                            <h4 class="fw-bold mb-0 text-danger">3</h4>
                                            <small class="text-muted">Belum Absen</small>
                                        </div>
                                        <div class="col-6 col-md-3 mt-3 mt-md-0">
                                            <h4 class="fw-bold mb-0 text-primary">18</h4>
                                            <small class="text-muted">Sudah Pulang</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end col-->
            <div class="col-xxl-7">
                <div class="row h-100">
                    @livewire('smart-pass.widget.kartu-presensi-hari-ini')
                    @livewire('smart-pass.widget.kartu-jumlah-kehadiran')
                </div> <!-- end row-->
            </div><!-- end col -->
        </div>
        <div class="row">
            <div class="col-12 mt-4">
                <div class="alert alert-warning d-flex align-items-center shadow-sm">
                    <i class="ri-alert-line me-2 fs-5"></i>
                    <div class="flex-grow-1">
                        <strong>Perhatian:</strong> 3 pegawai belum melakukan presensi hari ini.
                    </div>
                    <a href="#" class="fw-semibold text-decoration-underline ms-3">
                        Lihat Detail
                    </a>
                </div>
            </div>
        </div><!-- end row -->
    </div>
</div>
@endsection

