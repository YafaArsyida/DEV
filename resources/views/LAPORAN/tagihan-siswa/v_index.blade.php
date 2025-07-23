@extends('template_machine.v_template')
@section('content') 
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row mb-3 pb-1">
            <div class="col-12">
                <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-16 mb-1">Laporan Tagihan Siswa</h4>
                        <p class="text-muted mb-0">Laporan > Tagihan Siswa</p>
                    </div>
                    @livewire('parameter.jenjang-tahun-ajar')   
                </div><!-- end card header -->
            </div>
            <!--end col-->
        </div>
        <div class="row">
            {{-- <div class="col-xxl-12">
                @livewire('laporan-pembayaran-tagihan-siswa.kategori')   
                @livewire('laporan-pembayaran-tagihan-siswa.jenis')   
                @livewire('laporan-pembayaran-tagihan-siswa.export-kategori')   
                @livewire('laporan-pembayaran-tagihan-siswa.export-jenis')   
            </div>   --}}
            <div class="col-xxl-12">
                @livewire('parameter.filter-tagihan')   
                @livewire('laporan-tagihan-siswa.index')   
                @livewire('laporan-tagihan-siswa.export')   
                
                @livewire('surat-tagihan-siswa.index')   
                @livewire('surat-tagihan-siswa.create')   
                @livewire('surat-tagihan-siswa.edit')   
            </div>  
        </div>
    </div>
</div>
@endsection

