@extends('template_ppdb.v_template')

@section('content')

<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        {{-- Informasi periode PPDB --}}
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body p-3 p-lg-4">
                        <div class="d-flex flex-column flex-xl-row align-items-xl-center justify-content-between gap-4">

                            {{-- INFORMASI PERIODE --}}
                            @livewire('p-p-d-b.admin.dashboard.informasi-periode')

                            {{-- PARAMETER JENJANG & TAHUN AJAR --}}
                            <div class="flex-shrink-0">
                                @livewire('p-p-d-b.parameter.jenjang-tahun-ajar')
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistik --}}
        <div class="row">
            <div class="col-xxl-12 mb-3">
                @livewire('p-p-d-b.admin.pendaftar.statistik-pendaftar')   
            </div>
        </div>

        {{-- Rekapitulasi per jenjang --}}
        <div class="row">
            <div class="col-xxl-12">
                @livewire('p-p-d-b.admin.laporan.index') 
            </div>

            {{-- Unduh laporan --}}
            {{-- <div class="col-xxl-4">
                <div class="card rounded-4 border shadow-none">
                    <div class="card-header bg-transparent border-bottom p-4">
                        <h5 class="card-title mb-1">Unduh Laporan</h5>
                        <p class="text-muted mb-0 small">
                            Simpan rekap untuk dokumentasi dan evaluasi sekolah.
                        </p>
                    </div>

                    <div class="card-body p-4">
                        <div class="d-flex align-items-start gap-3 mb-4">
                            <div class="avatar-sm flex-shrink-0">
                                <div class="avatar-title bg-success-subtle text-success rounded-3 fs-20">
                                    <i class="ri-file-excel-2-line"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Excel (.xlsx)</h6>
                                <p class="text-muted small mb-2">
                                    Untuk pengolahan data dan rekap lanjutan.
                                </p>
                                <button type="button" class="btn btn-soft-success btn-sm rounded-3" disabled>
                                    <i class="ri-download-2-line me-1"></i> Unduh Excel
                                </button>
                            </div>
                        </div>

                        <div class="d-flex align-items-start gap-3">
                            <div class="avatar-sm flex-shrink-0">
                                <div class="avatar-title bg-danger-subtle text-danger rounded-3 fs-20">
                                    <i class="ri-file-pdf-line"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">PDF</h6>
                                <p class="text-muted small mb-2">
                                    Untuk arsip dan laporan resmi kepada kepala sekolah.
                                </p>
                                <button type="button" class="btn btn-soft-danger btn-sm rounded-3" disabled>
                                    <i class="ri-download-2-line me-1"></i> Unduh PDF
                                </button>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex align-items-start gap-2">
                            <i class="ri-information-line text-muted fs-18"></i>
                            <p class="text-muted small mb-0">
                                Laporan akan mengikuti periode dan tanggal yang dipilih setelah fitur tersedia.
                            </p>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
</div>
@endsection