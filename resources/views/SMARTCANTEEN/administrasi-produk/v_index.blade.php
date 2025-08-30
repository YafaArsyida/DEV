@extends('template_machine_smartcanteen.v_template')
@section('content')

<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row mb-3 pb-1">
            <div class="col-12">
                <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-16 mb-1">Administrasi Produk SmartCanteen</h4>
                        <p class="text-muted mb-0">SmartCanteen > Administrasi Produk</p>
                    </div>
                    @livewire('parameter.jenjang')   
                </div><!-- end card header -->
            </div>
            <!--end col-->
        </div>
        <div class="row g-3">
            <div class="col-xxl-12">
                @livewire('smart-canteen.produk-kantin.index')   
                @livewire('smart-canteen.produk-kantin.create')   
                @livewire('smart-canteen.produk-kantin.edit')   
                @livewire('smart-canteen.produk-kantin.delete')

                <!-- Offcanvas wrapper statis -->
                <div style="width: 500px;" 
                    class="offcanvas offcanvas-end" 
                    id="offcanvasKategori" 
                    data-bs-scroll="true" 
                    data-bs-backdrop="false" 
                    aria-labelledby="offcanvasKategoriLabel">

                    <div class="offcanvas-header border-bottom">
                        <h5 class="offcanvas-title" id="offcanvasKategoriLabel">Data Kategori Produk</h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
                    </div>

                    <div class="offcanvas-body">
                        {{-- hanya isinya Livewire --}}
                        @livewire('smart-canteen.kategori-produk-kantin.index')
                    </div>
                </div>
                @livewire('smart-canteen.kategori-produk-kantin.create')
                @livewire('smart-canteen.kategori-produk-kantin.edit')
                @livewire('smart-canteen.kategori-produk-kantin.delete')
            </div>
        </div>   
    </div>
</div>
@endsection

