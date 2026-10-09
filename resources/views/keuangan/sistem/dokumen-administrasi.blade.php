@extends('template_keuangan.v_template')
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
                    @livewire('keuangan.parameter.jenjang')   
                </div><!-- end card header -->
            </div>
            <!--end col-->
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-xxl-6">
                @livewire('keuangan.whats-app-pembayaran-tagihan-siswa.index')
                @livewire('keuangan.whats-app-pembayaran-tagihan-siswa.create')
                @livewire('keuangan.whats-app-pembayaran-tagihan-siswa.edit')

                @livewire('keuangan.kuitansi-pembayaran-tagihan-siswa.index')
                @livewire('keuangan.kuitansi-pembayaran-tagihan-siswa.create')
                @livewire('keuangan.kuitansi-pembayaran-tagihan-siswa.edit')
            </div>
            <div class="col-xxl-6">
                @livewire('keuangan.whats-app-tagihan-siswa.index')
                @livewire('keuangan.whats-app-tagihan-siswa.create')
                @livewire('keuangan.whats-app-tagihan-siswa.edit')

                @livewire('keuangan.surat-tagihan-siswa.index')
                @livewire('keuangan.surat-tagihan-siswa.create')
                @livewire('keuangan.surat-tagihan-siswa.edit')
            </div>
            <div class="col-xxl-6">
                @livewire('keuangan.whats-app-transaksi-tabungan.index')
                @livewire('keuangan.whats-app-transaksi-tabungan.create')
                @livewire('keuangan.whats-app-transaksi-tabungan.edit')

                @livewire('keuangan.kuitansi-transaksi-tabungan.index')
                @livewire('keuangan.kuitansi-transaksi-tabungan.create')
                @livewire('keuangan.kuitansi-transaksi-tabungan.edit')
            </div>
            <div class="col-xxl-6">
                @livewire('keuangan.whats-app-transaksi-edu-pay.index')
                @livewire('keuangan.whats-app-transaksi-edu-pay.create')
                @livewire('keuangan.whats-app-transaksi-edu-pay.edit')

                
                @livewire('keuangan.kuitansi-transaksi-edu-pay.index')
                @livewire('keuangan.kuitansi-transaksi-edu-pay.create')
                @livewire('keuangan.kuitansi-transaksi-edu-pay.edit')
            </div>
           
            <!--end col-->
        </div>        
    </div>
</div>
@endsection

