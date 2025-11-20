@extends('template_machine_koperasipintar.v_template')
@section('content')

<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row g-3">
            <div class="col-xxl-12">
                @livewire('koperasi-pintar.produk-koperasi.index')   
                @livewire('koperasi-pintar.produk-koperasi.create')   
                @livewire('koperasi-pintar.produk-koperasi.detail')   
                @livewire('koperasi-pintar.produk-koperasi.edit')   
                @livewire('koperasi-pintar.produk-koperasi.delete')

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
                        @livewire('koperasi-pintar.kategori-produk-koperasi.index')
                    </div>
                </div>
                @livewire('koperasi-pintar.kategori-produk-koperasi.create')
                @livewire('koperasi-pintar.kategori-produk-koperasi.edit')
                @livewire('koperasi-pintar.kategori-produk-koperasi.delete')
            </div>
        </div>   
    </div>
</div>
@endsection

