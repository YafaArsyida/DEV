@extends('template_portal_ppdb.v_template')

@section('title', 'Data Orang Tua/Wali')

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

                @elseif (!$pendaftaran)
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4 text-center">
                            <div class="avatar-md mx-auto mb-3">
                                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-2">
                                    <i class="ri-route-line"></i>
                                </div>
                            </div>

                            <h5 class="mb-2">Pilih Jalur Pendaftaran</h5>

                            <p class="text-muted mb-4">
                                Anda belum memilih jalur pendaftaran.
                                Silakan pilih jalur terlebih dahulu sebelum
                                mengisi data calon siswa.
                            </p>

                            <a href="{{ route('ppdb.pendaftaran.jalur', [
                                    'jenjang' => $jenjang->slug,
                                ]) }}"
                                class="btn btn-primary rounded-pill px-4">
                                <i class="ri-route-line me-1"></i>
                                Pilih Jalur Pendaftaran
                            </a>
                        </div>
                    </div>
                    
                @elseif (!$calonSiswa)
                    {{-- Data siswa belum tersedia --}}
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4 text-center">
                            <div class="avatar-md mx-auto mb-3">
                                <div class="avatar-title bg-light text-muted rounded-circle fs-2">
                                    <i class="ri-user-line"></i>
                                </div>
                            </div>

                            <h5 class="mb-2">Lengkapi Data Siswa</h5>
                            <p class="text-muted mb-4">
                                Silakan pilih jalur pendaftaran dan lengkapi data
                                calon siswa sebelum mengisi data orang tua.
                            </p>

                            <a href="{{ route('ppdb.pendaftaran.data-siswa', [
                                    'jenjang' => $pendaftaran->ppdb_periode->ms_jenjang->slug,
                                ]) }}"
                                class="btn btn-primary rounded-pill px-4">
                                <i class="ri-user-line me-1"></i>
                                Lengkapi Data Siswa
                            </a>
                        </div>
                    </div>
                @elseif ($orangTua)
                    @php
                        $modeOrangTua = request()->query('mode', 'detail');
                    @endphp

                    @if ($modeOrangTua === 'edit-orang-tua')
                        @livewire(
                            'p-p-d-b.pendaftaran.edit-orang-tua',
                            [
                                'pendaftaranId' => $pendaftaran->ppdb_pendaftaran_id,
                            ],
                            key('edit-orang-tua-' . $pendaftaran->ppdb_pendaftaran_id)
                        )
                    @else
                        @livewire(
                            'p-p-d-b.pendaftaran.detail-orang-tua',
                            [
                                'pendaftaranId' => $pendaftaran->ppdb_pendaftaran_id,
                            ],
                            key('detail-orang-tua-' . $pendaftaran->ppdb_pendaftaran_id)
                        )
                    @endif
                @else
                    {{-- Form pengisian data orang tua --}}
                    @livewire(
                        'p-p-d-b.pendaftaran.form-orang-tua',
                        [
                            'periodeId' => $periode->ppdb_periode_id,
                            'pendaftaranId' => $pendaftaran->ppdb_pendaftaran_id,
                        ],
                        key('form-orang-tua-' . $pendaftaran->ppdb_pendaftaran_id)
                    )
                @endif

            </div>
        </div>

    </div>
</div>
@endsection