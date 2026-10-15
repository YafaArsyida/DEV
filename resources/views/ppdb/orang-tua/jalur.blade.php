@extends('template_portal_ppdb.v_template')

@section('title', 'Pilih Jalur Pendaftaran')

@section('content')
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row">
            <div class="col-xxl-12">

                {{-- PROGRESS PENDAFTARAN --}}
                @include('ppdb.orang-tua.layouts.progress-pendaftaran', [
                    'stepAktif' => $stepAktif
                ])

                {{-- MAIN CARD --}}
                @if (!$periode)
                    {{-- Informasi periode belum dibuka --}}
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4 text-center">
                            <div class="avatar-md mx-auto mb-3">
                                <div class="avatar-title bg-light text-muted rounded-circle fs-2">
                                    <i class="ri-calendar-close-line"></i>
                                </div>
                            </div>

                            <h5 class="mb-2">Pendaftaran Belum Dibuka</h5>
                            <p class="text-muted mb-0">
                                Periode PPDB belum tersedia. Silakan kembali
                                ketika pendaftaran sudah dibuka.
                            </p>
                        </div>
                    </div>
                @elseif ($pendaftaran)
                    @livewire('p-p-d-b.pendaftaran.detail-jalur',
                        ['pendaftaranId' => $pendaftaran->ppdb_pendaftaran_id],
                        key('detail-pendaftaran-' . $pendaftaran->ppdb_pendaftaran_id)
                    )
                @else
                    @livewire('p-p-d-b.pendaftaran.pilih-jalur',
                        ['periodeId' => $periode->ppdb_periode_id],
                        key('pilih-jalur-' . $periode->ppdb_periode_id)
                    )
                @endif
            </div>
        </div>
    </div>
</div>
@endsection