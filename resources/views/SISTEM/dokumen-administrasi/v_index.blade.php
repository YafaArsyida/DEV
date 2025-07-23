@extends('template_machine.v_template')
@section('content') 
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row mb-3 pb-1">
            <div class="col-12">
                <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-16 mb-1">Selamat Datang di Halaman Pesan!</h4>
                        <p class="text-muted mb-0">Kelola Pesan notifikasi dengan mudah di sini.</p>
                    </div>
                    @livewire('parameter.jenjang')   
                </div><!-- end card header -->
            </div>
            <!--end col-->
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-xxl-6 pe-1">
                @livewire('whats-app-histori-tagihan.index')
                @livewire('whats-app-histori-tagihan.create')
                @livewire('whats-app-histori-tagihan.edit')

                @livewire('kuitansi-pembayaran.index')
                @livewire('kuitansi-pembayaran.create')
                @livewire('kuitansi-pembayaran.edit')
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
                @livewire('whats-app-edu-pay.index')
                @livewire('whats-app-edu-pay.create')
                @livewire('whats-app-edu-pay.edit')
            </div>
           
            <!--end col-->
        </div>        
    </div>
</div>
@endsection

