@extends('template_machine_smartpass.v_template')
@section('content')

<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row">
            <div class="col-xxl-5">
                <div class="d-flex flex-column h-100">
                    <div class="row h-100">
                        <div class="col-12">
                            <div class="card shadow-sm border-0">
                                <div class="card-body p-0">
                        
                                    <!-- ALERT CTA -->
                                    <div class="alert alert-success border-0 rounded-0 m-0 d-flex align-items-center">
                                        <i class="ri-user-follow-line fs-4 me-2"></i>
                                        <div class="flex-grow-1 text-truncate">
                                            Mulai proses <strong>Presensi Pegawai</strong> untuk hari ini.
                                        </div>
                                        <div class="flex-shrink-0">
                                            <a href="{{ route('smartPass.presensi.pegawai') }}"
                                                class="fw-semibold text-decoration-underline text-success">
                                                Buka Presensi
                                            </a>
                                        </div>
                                    </div>
                        
                                    <!-- BODY -->
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <div class="p-4">
                                                <h5 class="fw-bold mb-2">
                                                    Presensi Pegawai Berbasis Kartu
                                                </h5>
                                                <p class="mb-3 text-muted">
                                                    Lakukan presensi masuk dan pulang pegawai dengan cepat
                                                    menggunakan kartu RFID. Status hadir, terlambat,
                                                    dan pulang tercatat otomatis.
                                                </p>
                                                <a href="{{ route('smartPass.presensi.pegawai') }}" class="btn btn-success btn-lg">
                                                    <i class="ri-qrcode-line me-1"></i>
                                                    Mulai Presensi Pegawai
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <img src="{{ asset('assets/images/attendance-illustration.png') }}" class="img-fluid" alt="">
                                        </div>
                                    </div>
                        
                                </div>
                            </div>
                        </div>
                    </div> <!-- end row-->

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar-sm bg-primary bg-soft rounded-circle me-3">
                                            <i class="ri-group-line text-primary fs-4"></i>
                                        </div>
                                        <h6 class="fw-bold mb-0">
                                            Presensi Siswa
                                        </h6>
                                    </div>
                    
                                    <p class="text-muted mb-3">
                                        Catat kehadiran siswa secara cepat dan akurat
                                        menggunakan kartu pelajar.
                                    </p>
                    
                                    <a href="" class="btn btn-outline-primary w-100">
                                        <i class="ri-log-in-circle-line me-1"></i>
                                        Masuk Presensi Siswa
                                    </a>
                                </div>
                            </div>
                        </div>
                    
                        <!-- OPTIONAL : CTA Laporan -->
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar-sm bg-info bg-soft rounded-circle me-3">
                                            <i class="ri-file-chart-line text-info fs-4"></i>
                                        </div>
                                        <h6 class="fw-bold mb-0">
                                            Laporan Presensi
                                        </h6>
                                    </div>
                    
                                    <p class="text-muted mb-3">
                                        Lihat rekap presensi harian dan bulanan
                                        pegawai maupun siswa.
                                    </p>
                    
                                    <a href="" class="btn btn-outline-info w-100">
                                        <i class="ri-bar-chart-2-line me-1"></i>
                                        Lihat Laporan
                                    </a>
                                </div>
                            </div>
                        </div>
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
                      <div class="col-12">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-header bg-white border-0">
                                <h6 class="fw-bold mb-0">
                                    <i class="ri-time-line me-1"></i>
                                    Aktivitas Presensi Terakhir
                                </h6>
                            </div>
                    
                            <div class="card-body p-0">
                                <div data-simplebar style="max-height: 300px;">
                                    <table class="table table-hover align-middle mb-0">
                                        <tbody>
                                            <tr>
                                                <td class="ps-3">07:02</td>
                                                <td>Ahmad Fauzi</td>
                                                <td><span class="badge bg-success">Masuk</span></td>
                                            </tr>
                                            <tr>
                                                <td class="ps-3">07:15</td>
                                                <td>Siti Aminah</td>
                                                <td><span class="badge bg-warning">Terlambat</span></td>
                                            </tr>
                                            <tr>
                                                <td class="ps-3">15:03</td>
                                                <td>Budi Santoso</td>
                                                <td><span class="badge bg-info">Pulang</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
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

