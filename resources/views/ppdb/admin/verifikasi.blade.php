
@extends('template_ppdb.v_template')

@section('content')
@php
    $title = 'Verifikasi Pendaftaran';

    $statistik = [
        [
            'label' => 'Menunggu Verifikasi',
            'jumlah' => 24,
            'icon' => 'mdi-clipboard-text-clock-outline',
            'warna' => 'warning',
        ],
        [
            'label' => 'Terverifikasi',
            'jumlah' => 88,
            'icon' => 'mdi-clipboard-check-outline',
            'warna' => 'success',
        ],
        [
            'label' => 'Perlu Perbaikan',
            'jumlah' => 8,
            'icon' => 'mdi-clipboard-alert-outline',
            'warna' => 'danger',
        ],
    ];

    $antrean = [
        [
            'nomor' => 'PPDB-2027-0120',
            'nama' => 'Ahmad Fathan Pratama',
            'jenjang' => 'SMA/SMK',
            'jalur' => 'Reguler',
            'tanggal' => '08 Okt 2026',
            'dokumen_lengkap' => 4,
            'total_dokumen' => 5,
            'status' => 'Menunggu Verifikasi',
            'warna' => 'warning',
        ],
        [
            'nomor' => 'PPDB-2027-0118',
            'nama' => 'Muhammad Rizky Ramadhan',
            'jenjang' => 'SMA/SMK',
            'jalur' => 'Reguler',
            'tanggal' => '07 Okt 2026',
            'dokumen_lengkap' => 3,
            'total_dokumen' => 5,
            'status' => 'Menunggu Verifikasi',
            'warna' => 'warning',
        ],
        [
            'nomor' => 'PPDB-2027-0115',
            'nama' => 'Nabila Zahra',
            'jenjang' => 'SMA/SMK',
            'jalur' => 'Afirmasi',
            'tanggal' => '06 Okt 2026',
            'dokumen_lengkap' => 5,
            'total_dokumen' => 5,
            'status' => 'Perlu Perbaikan',
            'warna' => 'danger',
        ],
        [
            'nomor' => 'PPDB-2027-0112',
            'nama' => 'Farhan Al-Faruq',
            'jenjang' => 'SMA/SMK',
            'jalur' => 'Reguler',
            'tanggal' => '05 Okt 2026',
            'dokumen_lengkap' => 5,
            'total_dokumen' => 5,
            'status' => 'Menunggu Verifikasi',
            'warna' => 'warning',
        ],
    ];
@endphp

<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        {{-- Summary cards --}}
        <div class="row g-3 mb-3">
            @foreach ($statistik as $item)
                <div class="col-xl-4 col-md-6">
                    <div class="card rounded-4 border shadow-none h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <div>
                                    <p class="text-muted mb-2">{{ $item['label'] }}</p>
                                    <h4 class="mb-0 fw-semibold">
                                        {{ $item['jumlah'] }}
                                        <small class="text-muted fs-13 fw-normal">pendaftar</small>
                                    </h4>
                                </div>

                                <div class="avatar-md flex-shrink-0">
                                    <div class="avatar-title bg-{{ $item['warna'] }}-subtle text-{{ $item['warna'] }} rounded-4 fs-3">
                                        <i class="mdi {{ $item['icon'] }}"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- TABEL--}}
        <div class="row">
            <div class="col-xxl-12">
                @livewire('p-p-d-b.admin.verifikasi.index')   
                @livewire('p-p-d-b.admin.verifikasi.detail')   
                
                @livewire('p-p-d-b.pendaftaran.preview-berkas')
            </div>
        </div>
    </div>
</div>
@endsection