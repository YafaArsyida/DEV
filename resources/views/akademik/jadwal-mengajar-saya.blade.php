@extends('template_akademik.v_template')

@section('content')
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row">
            <div class="col-xxl-12">
                @livewire('akademik.jadwal-mengajar.saya')
            </div>
        </div>
    </div>
</div>
@endsection
