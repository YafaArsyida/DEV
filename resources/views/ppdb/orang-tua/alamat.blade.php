@extends('template_portal_ppdb.v_template')

@section('title', 'Data Alamat')

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
                @elseif (!$orangTua)
                    {{-- Data orang tua belum diisi --}}
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4 text-center">
                            <div class="avatar-md mx-auto mb-3">
                                <div class="avatar-title bg-light text-muted rounded-circle fs-2">
                                    <i class="ri-parent-line"></i>
                                </div>
                            </div>

                            <h5 class="mb-2">Lengkapi Data Orang Tua</h5>
                            <p class="text-muted mb-4">
                                Data alamat dapat diisi setelah data orang tua
                                tersimpan. Silakan lengkapi data orang tua terlebih dahulu.
                            </p>

                            <a href="{{ route('ppdb.pendaftaran.orang-tua') }}"
                            class="btn btn-primary rounded-pill px-4">
                                <i class="ri-parent-line me-1"></i>
                                Lengkapi Data Orang Tua
                            </a>
                        </div>
                    </div>
                @elseif ($alamat)
                    @php
                        $modeAlamat = request()->query('mode', 'detail');
                    @endphp
                    
                    @if ($modeAlamat === 'edit-alamat')
                        @livewire(
                            'p-p-d-b.pendaftaran.edit-alamat',
                            [
                                'pendaftaranId' => $pendaftaran->ppdb_pendaftaran_id,
                            ],
                            key('edit-alamat-' . $pendaftaran->ppdb_pendaftaran_id)
                        )
                    @else
                        @livewire(
                            'p-p-d-b.pendaftaran.detail-alamat',
                            [
                                'pendaftaranId' => $pendaftaran->ppdb_pendaftaran_id,
                            ],
                            key('detail-alamat-' . $pendaftaran->ppdb_pendaftaran_id)
                        )
                    @endif
                 @else
                    {{-- Data alamat belum tersedia --}}
                    @livewire(
                        'p-p-d-b.pendaftaran.form-alamat',
                        [
                            'periodeId' => $periode->ppdb_periode_id,
                            'pendaftaranId' => $pendaftaran->ppdb_pendaftaran_id,
                        ],
                        key('form-alamat-' . $pendaftaran->ppdb_pendaftaran_id)
                    )
                @endif                        
            </div>
        </div>
    </div>
</div>
@endsection