@extends('template_machine.v_template')
@section('content') 
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row mb-3 pb-1">
            <div class="col-12">
                <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-16 mb-1">Dokumen Administrasi</h4>
                        <p class="text-muted mb-0">Sistem > Dokumen Administrasi</p>
                    </div>
                    @livewire('parameter.jenjang')   
                </div><!-- end card header -->
            </div>
            <!--end col-->
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-xxl-6 pe-1">
                @livewire('whats-app-pembayaran-tagihan-siswa.index')
                @livewire('whats-app-pembayaran-tagihan-siswa.create')
                @livewire('whats-app-pembayaran-tagihan-siswa.edit')

                @livewire('kuitansi-pembayaran-tagihan-siswa.index')
                @livewire('kuitansi-pembayaran-tagihan-siswa.create')
                @livewire('kuitansi-pembayaran-tagihan-siswa.edit')
            </div>
             <div class="col-xxl-6 ps-0">
                @livewire('whats-app-tagihan-siswa.index')
                @livewire('whats-app-tagihan-siswa.create')
                @livewire('whats-app-tagihan-siswa.edit')

                @livewire('surat-tagihan-siswa.index')
                @livewire('surat-tagihan-siswa.create')
                @livewire('surat-tagihan-siswa.edit')
            </div>
            <div class="col-xxl-6 pe-1">
                @livewire('whats-app-histori-tabungan-siswa.index')
                @livewire('whats-app-histori-tabungan-siswa.create')
                @livewire('whats-app-histori-tabungan-siswa.edit')
            </div>
            <div class="col-xxl-6 ps-0">
                @livewire('whats-app-edu-pay-siswa.index')
                @livewire('whats-app-edu-pay-siswa.create')
                @livewire('whats-app-edu-pay-siswa.edit')
            </div>
           
            <!--end col-->
        </div>        
    </div>
</div>
@endsection

