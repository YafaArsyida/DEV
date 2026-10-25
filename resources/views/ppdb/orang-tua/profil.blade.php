@extends('template_portal_ppdb.v_template')

@section('content')
    <div class="page-content">
        <div class="container-fluid" style="max-width: 100%">
            <div class="row">
                <div class="col-xxl-12">
                    {{-- Informasi Akun --}}
                    <div class="row">
                        <div class="col-xl-4 col-lg-5">
                            @livewire('p-p-d-b.profil.index', [
                                'jenjang' => $jenjang
                            ])
                        </div>

                        {{-- Form Edit Akun --}}
                        <div class="col-xl-8 col-lg-7">
                            @livewire('p-p-d-b.profil.edit', [
                                'jenjang' => $jenjang
                            ])
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection