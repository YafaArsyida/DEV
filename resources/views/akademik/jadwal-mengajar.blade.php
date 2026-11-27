@extends('template_akademik.v_template')

@section('content')
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row">
            <div class="col-xxl-12">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-3 p-lg-4">
                        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                            @livewire('keuangan.parameter.informasi-tahun-ajar', ['heading' => 'Jadwal Mengajar'])
                            <div>
                                @livewire('keuangan.parameter.jenjang-tahun-ajar-periode')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xxl-12">
                @livewire('akademik.jadwal-mengajar.index')
                @livewire('akademik.jadwal-mengajar.create')
                @livewire('akademik.jadwal-mengajar.edit')
                @livewire('akademik.jadwal-mengajar.delete')
            </div>
        </div>
    </div>
</div>
@endsection
