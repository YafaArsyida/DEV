@extends('template_machine_smartcanteen.v_template')
@section('content')

<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row mb-3 pb-1">
            <div class="col-12">
                <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-16 mb-1">Dashboard SmartPss</h4>
                        <p class="text-muted mb-0">Dashboard > SmartPss</p>
                    </div>
                    @livewire('parameter.jenjang-tahun-ajar')   
                </div><!-- end card header -->
            </div>
            <!--end col-->
        </div>
        <div class="row">
            <div class="col-xxl-5">
                <div class="d-flex flex-column h-100">
                    <div class="row h-100">
                        <div class="col-12">
                            @livewire('widget.c-t-a-menu-transaksi-tagihan-siswa')   
                        </div> <!-- end col-->
                    </div> <!-- end row-->

                    <div class="row">
                        {{-- kartu jumlah siswa --}}
                        @livewire('widget.kartu-jumlah-siswa')   
                        @livewire('widget.kartu-jumlah-tagihan-siswa')   
                        @livewire('widget.kartu-jumlah-jurnal-pendapatan')   
                        @livewire('widget.kartu-jumlah-jurnal-pengeluaran')   
                    </div> <!-- end row-->
                </div>
            </div> <!-- end col-->
            <div class="col-xxl-7">
                <div class="row h-100">
                    @livewire('widget.overview-tagihan-siswa')   
                    @livewire('widget.progres-tagihan-siswa')   
                </div> <!-- end row-->
            </div><!-- end col -->
        </div>
         <div class="row">
            @livewire('widget.kartu-transaksi-jurnal')   
            @livewire('widget.kartu-jurnal-detail')   
        </div><!-- end row -->
    </div>
</div>
@endsection

