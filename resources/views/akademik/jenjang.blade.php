@extends('template_akademik.v_template')
@section('content')

@php
    $title = "Jenjang"
@endphp
{{-- @push('info-page')
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Pages</a></li>
            <li class="breadcrumb-item active">{{ $title ?? "SmartGate" }}</li>
        </ol>
    </div>
@endpush --}}
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Jenjang</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Akademik</a></li>
                            <li class="breadcrumb-item active">Jenjang</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            {{-- JENJANG --}}
            <div class="col-xxl-8">
                @livewire('akademik.jenjang.create')
                @livewire('akademik.jenjang.index')   
                @livewire('akademik.jenjang.edit')  
                @livewire('akademik.jenjang.delete')  
            </div>
            <!--end col-->
        </div>        
    </div>
</div>
@endsection

