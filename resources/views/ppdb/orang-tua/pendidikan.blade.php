@extends('template_portal_ppdb.v_template')

@section('title', 'Data Pendidikan')

@section('content')
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">

        {{-- Content --}}
        <div class="row">
            <div class="col-xxl-12">

                {{-- PROGRESS PENDAFTARAN --}}
                @include('ppdb.orang-tua.layouts.progress-pendaftaran', [
                    'stepAktif' => $stepAktif
                ])

                {{-- MAIN CARD --}}
                @if (!$periode)
                    {{-- Periode PPDB belum aktif --}}
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
                @elseif (!$alamat)
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4 text-center">
                            <div class="avatar-md mx-auto mb-3">
                                <div class="avatar-title bg-light text-muted rounded-circle fs-2">
                                    <i class="ri-map-pin-line"></i>
                                </div>
                            </div>

                            <h5 class="mb-2">Lengkapi Data Alamat</h5>

                            <p class="text-muted mb-4">
                                Silakan lengkapi data alamat calon siswa agar proses
                                pendaftaran dapat dilanjutkan.
                            </p>

                            <a href="{{ route('ppdb.pendaftaran.alamat') }}"
                                class="btn btn-primary rounded-pill px-4">
                                <i class="ri-map-pin-line me-1"></i>
                                Lengkapi Data Alamat
                            </a>
                        </div>
                    </div>
                @elseif ($pendidikan)
                    @php
                        $modePendidikan = request()->query('mode', 'detail');
                    @endphp
                    
                    @if ($modePendidikan === 'edit-pendidikan')
                        @livewire(
                            'p-p-d-b.pendaftaran.edit-pendidikan',
                            [
                                'pendaftaranId' => $pendaftaran->ppdb_pendaftaran_id,
                            ],
                            key('edit-pendidikan-' . $pendaftaran->ppdb_pendaftaran_id)
                        )
                    @else
                        @livewire(
                            'p-p-d-b.pendaftaran.detail-pendidikan',
                            [
                                'pendaftaranId' => $pendaftaran->ppdb_pendaftaran_id,
                            ],
                            key('detail-pendidikan-' . $pendaftaran->ppdb_pendaftaran_id)
                        )
                    @endif
                 @else
                    {{-- Data pendidikan belum tersedia --}}
                    @livewire(
                        'p-p-d-b.pendaftaran.form-pendidikan',
                        [
                            'periodeId' => $periode->ppdb_periode_id,
                            'pendaftaranId' => $pendaftaran->ppdb_pendaftaran_id,
                        ],
                        key('form-pendidikan-' . $pendaftaran->ppdb_pendaftaran_id)
                    )
                @endif   

            </div>
        </div>

    </div>
</div>
@endsection