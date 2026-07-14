@extends('template_machine.v_template')
@section('content')
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row mb-3 pb-1">
            <div class="col-12">
                <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-16 mb-1">Transaksi Tagihan Siswa</h4>
                        <p class="text-muted mb-0">Transaksi > Tagihan Siswa</p>
                    </div>
                    @livewire('parameter.jenjang-tahun-ajar-siswa')   
                </div><!-- end card header -->
            </div>
        </div>
        <div class="row">
            <div class="col-xxl-8">
                @livewire('siswa.edit')
                @livewire('tagihan-siswa.detail')
                @livewire('tagihan-siswa.edit')
                @livewire('tagihan-siswa.delete')
    
                @livewire('transaksi-tagihan-siswa.aksi-tambah')
    
                @livewire('transaksi-tagihan-siswa.index')
    
                @livewire('transaksi-tagihan-siswa.cicilan')
    
                @livewire('transaksi-tagihan-siswa.edit')
                @livewire('transaksi-tagihan-siswa.delete')
                @livewire('transaksi-tagihan-siswa.histori')
    
                @livewire('transaksi-tabungan-siswa.index')
                @livewire('transaksi-tabungan-siswa.delete')
                @livewire('transaksi-tabungan-siswa.edit')
    
                @livewire('transaksi-edu-pay-siswa.index')
                @livewire('transaksi-edu-pay-siswa.delete')
                @livewire('transaksi-edu-pay-siswa.edit')
            </div>
            <!--end col-->
            <div class="col-xxl-4">
                <div class="sticky-side-div">
                    @livewire('transaksi-tagihan-siswa.data-keranjang')
                </div>
            </div>
        </div>
    </div>
</div><!-- End Page-content -->
@endsection

