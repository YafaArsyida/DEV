@extends('template_machine.v_template')
@section('content')

@php
    $title = "Selamat Datang di SinaukuApp"
@endphp
@push('info-page')
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Pages</a></li>
            <li class="breadcrumb-item active">{{ $title ?? "SmartGate" }}</li>
        </ol>
    </div>
@endpush

<h1>HELLO</h1>
@endsection

