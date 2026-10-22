@extends('template_portal_ppdb.v_template')

@section('title', 'Berkas Persyaratan')

@section('content')
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row">
            <div class="col-xxl-12">

                {{-- PROGRESS PENDAFTARAN --}}
                @include('ppdb.orang-tua.layouts.progress-pendaftaran', [
                    'stepAktif' => $stepAktif
                ])

                {{-- Main Card --}}
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
                @elseif (!$pendidikan)
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4 text-center">
                            <div class="avatar-md mx-auto mb-3">
                                <div class="avatar-title bg-light text-muted rounded-circle fs-2">
                                    <i class="ri-graduation-cap-line"></i>
                                </div>
                            </div>

                            <h5 class="mb-2">Data Pendidikan Belum Diisi</h5>

                            <p class="text-muted mb-4">
                                Silakan lengkapi data pendidikan calon siswa, seperti
                                asal sekolah dan informasi pendidikan sebelumnya,
                                agar proses pendaftaran dapat dilanjutkan.
                            </p>

                            <a href="{{ route('ppdb.pendaftaran.pendidikan', [
                                    'jenjang' => $pendaftaran->ppdb_periode->ms_jenjang->slug,
                                ]) }}"
                                class="btn btn-primary rounded-pill px-4">
                                <i class="ri-graduation-cap-line me-1"></i>
                                Lengkapi Data Pendidikan
                            </a>
                        </div>
                    </div>
                @elseif ($berkas->isNotEmpty())
                    @php
                        $modeBerkas = request()->query('mode', 'detail');
                    @endphp

                    @if ($modeBerkas === 'edit-berkas')
                        @livewire(
                            'p-p-d-b.pendaftaran.edit-berkas',
                            [
                                'pendaftaranId' => $pendaftaran->ppdb_pendaftaran_id,
                                'periodeId' => $periode->ppdb_periode_id,
                            ],
                            key('edit-berkas-' . $pendaftaran->ppdb_pendaftaran_id)
                        )
                    @else
                        {{-- <h1>masuk mode detail</h1> --}}
                        @livewire(
                            'p-p-d-b.pendaftaran.detail-berkas',
                            [
                                'pendaftaranId' => $pendaftaran->ppdb_pendaftaran_id,
                            ],
                            key('detail-berkas-' . $pendaftaran->ppdb_pendaftaran_id)
                        )
                    @endif
                @else
                    {{-- Belum ada berkas --}}
                    @livewire(
                        'p-p-d-b.pendaftaran.form-berkas',
                        [
                            'periodeId' => $periode->ppdb_periode_id,
                            'pendaftaranId' => $pendaftaran->ppdb_pendaftaran_id,
                        ],
                        key('form-berkas-' . $pendaftaran->ppdb_pendaftaran_id)
                    )
                @endif  

                @livewire('p-p-d-b.pendaftaran.preview-berkas')

            </div>
        </div>
    </div>
</div>

@endsection