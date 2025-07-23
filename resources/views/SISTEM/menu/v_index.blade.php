@extends('template_machine.v_template')
@section('content')

@php
    $title = "Menu Sistem"
@endphp
@push('info-page')
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Pages</a></li>
            <li class="breadcrumb-item active">{{ $title ?? "SmartGate" }}</li>
        </ol>
    </div>
@endpush
<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Menu Sistem</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Manajemen Sistem</a></li>
                            <li class="breadcrumb-item active">Menu Sistem</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-xxl-6">
                <!--end card-->
                <div class="card shadow-none">
                    <div class="card-body bg-info-subtle rounded">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i data-feather="calendar" class="text-info icon-dual-info"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="fs-15">Welcome to your Calendar!</h6>
                                <p class="text-muted mb-0">Event that applications book will appear here. Click on an event to see the details and manage applicants event.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end card-->
            </div>
            <div class="col-xxl-6">
                <!--end card-->
                <div class="card shadow-none">
                    <div class="card-body bg-info-subtle rounded">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i data-feather="calendar" class="text-info icon-dual-info"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="fs-15">Welcome to your Calendar!</h6>
                                <p class="text-muted mb-0">Event that applications book will appear here. Click on an event to see the details and manage applicants event.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end card-->
            </div>
        </div>
        <div class="row">
            <div class="col-xxl-6">
                <div class="card">
                    <div class="card-header border-0">
                        <div class="row g-4 align-items-center">
                            <div class="col-sm-6">
                                <h5 class="card-title mb-0">Data Menu</h5>
                            </div>
                            <div class="col-sm-auto ms-auto">
                                <div class="hstack gap-2">
                                    <button type="button" class="btn btn-soft-primary btn-sm shadow-none" data-bs-toggle="modal" id="create-btn" data-bs-target="#ModalAddJenjang"><i class="ri-add-line fs-16"></i></button>
                                    <button type="button" onclick="LoadData()" class="btn btn-soft-secondary btn-sm shadow-none"><i class="ri-restart-line fs-16"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        {{-- LOAD DATA --}}
                        <div>
                            <div id="LoadData"></div>
                        </div>
                        {{-- LOADDATA --}}
                    </div><!-- end card body -->
                </div>
            </div>
            <!--end col-->
            <div class="col-xxl-6">
                <div class="card sticky-side-div">
                    <div class="card-header border-0">
                        <div class="row g-4 align-items-center">
                            <div class="col-sm-6">
                                <h5 class="card-title mb-0">Data Sub-Menu</h5>
                            </div>
                            <div class="col-sm-auto ms-auto">
                                <div class="hstack gap-2">
                                    <button type="button" class="btn btn-soft-primary btn-sm shadow-none" data-bs-toggle="modal" id="create-btn" data-bs-target="#ModalAddJenjang"><i class="ri-add-line fs-16"></i></button>
                                    <button type="button" onclick="LoadDataSub()" class="btn btn-soft-secondary btn-sm shadow-none"><i class="ri-restart-line fs-16"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        {{-- LOAD DATA --}}
                        <div id="LoadDataSub"></div>
                        {{-- LOADDATA --}}
                        <div class="row">
                            <div class="col-6 col-md-4">
                                <div class="d-flex mt-4">
                                    <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                        <div class="avatar-title bg-light rounded-circle fs-16 text-primary shadow">
                                            <i class="ri-user-2-fill"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="mb-1">Designation :</p>
                                        <h6 class="text-truncate mb-0">Lead Designer / Developer</h6>
                                    </div>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-6 col-md-4">
                                <div class="d-flex mt-4">
                                    <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                        <div class="avatar-title bg-light rounded-circle fs-16 text-primary shadow">
                                            <i class="ri-global-line"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="mb-1">Website :</p>
                                        <a href="#" class="fw-semibold">www.velzon.com</a>
                                    </div>
                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </div>
                    <!--end card-body-->
                </div><!-- end card -->
            </div>
            <!--end col-->
        </div>
    </div>
</div>
@include('SISTEM.menu.v_javascript')
@endsection

