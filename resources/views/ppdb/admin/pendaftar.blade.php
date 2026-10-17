@extends('template_ppdb.v_template')

@section('content')
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        {{-- Statistik --}}
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between gap-3">
                            <div>
                                <p class="text-muted mb-2">Total Pendaftar</p>
                                <h4 class="fw-bold mb-0">
                                    {{ number_format($totalPendaftar, 0, ',', '.') }}
                                </h4>
                            </div>

                            <div class="avatar-md flex-shrink-0">
                                <div class="avatar-title bg-primary-subtle text-primary rounded-3 fs-3">
                                    <i class="mdi mdi-account-group-outline"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between gap-3">
                            <div>
                                <p class="text-muted mb-2">Menunggu Verifikasi</p>
                                <h4 class="fw-bold mb-0">
                                    {{ number_format($menungguVerifikasi, 0, ',', '.') }}
                                </h4>
                            </div>

                            <div class="avatar-md flex-shrink-0">
                                <div class="avatar-title bg-warning-subtle text-warning rounded-3 fs-3">
                                    <i class="mdi mdi-file-clock-outline"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between gap-3">
                            <div>
                                <p class="text-muted mb-2">Terverifikasi</p>
                                <h4 class="fw-bold mb-0">
                                    {{ number_format($terverifikasi, 0, ',', '.') }}
                                </h4>
                            </div>

                            <div class="avatar-md flex-shrink-0">
                                <div class="avatar-title bg-success-subtle text-success rounded-3 fs-3">
                                    <i class="mdi mdi-check-decagram-outline"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                        <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between gap-3">
                            <div>
                                <p class="text-muted mb-2">Perlu Perbaikan</p>
                                <h4 class="fw-bold mb-0">
                                    {{ number_format($perluPerbaikan, 0, ',', '.') }}
                                </h4>
                            </div>

                            <div class="avatar-md flex-shrink-0">
                                <div class="avatar-title bg-danger-subtle text-danger rounded-3 fs-3">
                                    <i class="mdi mdi-file-alert-outline"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel pendaftar --}}
        <div class="row">
            <div class="col-xxl-12">
                @livewire('p-p-d-b.admin.pendaftar.index')   
                @livewire('p-p-d-b.admin.pendaftar.detail')  
                
                
                @livewire('keuangan.pengguna.reset-password')  
            </div>
        </div>

    </div>
</div>

@endsection