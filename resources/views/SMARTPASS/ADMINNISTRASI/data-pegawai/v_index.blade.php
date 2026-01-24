@extends('template_machine_smartpass.v_template')
@section('content')
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <!-- start page title -->
        <div class="row mb-3 pb-1">
            <div class="col-12">
                <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-16 mb-1">Data Kepegawaian</h4>
                        <p class="text-muted mb-0">SmartPass > Administrasi > Data Pegawai</p>
                    </div>
                    @livewire('parameter.jenjang')
                </div><!-- end card header -->
            </div>
            <!--end col-->
        </div>
        <div class="row">
            <div class="col-xxl-12">
                @livewire('pegawai.index')
                @livewire('pegawai.create')
                @livewire('pegawai.edit')
                @livewire('pegawai.delete')
                @livewire('pegawai.import')
                @livewire('pegawai.import-kontak')
                @livewire('pegawai.import-edu-card')

                <!-- Offcanvas wrapper statis -->
                <div style="width: 500px;" class="offcanvas offcanvas-end" id="offcanvasJabatan" data-bs-scroll="true"
                    data-bs-backdrop="false" aria-labelledby="offcanvasJabatan">

                    <div class="offcanvas-header border-bottom">
                        <h5 class="offcanvas-title" id="offcanvasJabatan">Data Jabatan</h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
                    </div>

                    <div class="offcanvas-body">
                        @livewire('jabatan.index')
                    </div>
                </div>
                @livewire('jabatan.create')
                @livewire('jabatan.edit')
                @livewire('jabatan.delete')
            </div>
        </div>
    </div>
</div>
@endsection