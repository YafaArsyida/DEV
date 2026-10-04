@extends('template_keuangan.v_template')
@section('content')
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row">
            <div class="col-xxl-12">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-3 p-lg-4">
                        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                            {{-- LABEL --}}
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-3">
                                            <i class="ri-equalizer-line fs-5"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <h6 class="mb-0 fw-semibold">
                                            Parameter Akademik
                                        </h6>

                                        <small class="text-muted">
                                            Pilih jenjang dan tahun ajaran
                                        </small>
                                    </div>
                                </div>
                            </div>

                            {{-- PARAMETER --}}
                            <div>
                                @livewire('keuangan.parameter.jenjang-tahun-ajar-siswa')   
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xxl-4">
                <div class="sticky-side-div">
                    @livewire('keuangan.transaksi-tabungan-siswa.data-siswa')
                </div>
            </div>
            <!--end col-->
            <div class="col-xxl-8">
                @livewire('keuangan.transaksi-tabungan-siswa.data-tabungan')
                @livewire('keuangan.transaksi-tabungan-siswa.delete')
                @livewire('keuangan.transaksi-tabungan-siswa.detail')
                @livewire('keuangan.transaksi-tabungan-siswa.edit')
            </div>
        </div>
    </div>
</div><!-- End Page-content -->
@endsection

