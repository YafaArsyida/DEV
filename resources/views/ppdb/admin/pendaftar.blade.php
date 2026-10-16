@extends('template_ppdb.v_template')

@section('content')

@php
    $title = 'Semua Pendaftar';

    // Data dummy untuk prototipe UI.
    $statistik = [
        [
            'label' => 'Total Pendaftar',
            'jumlah' => 120,
            'icon' => 'mdi-account-group-outline',
            'color' => 'primary',
        ],
        [
            'label' => 'Menunggu Verifikasi',
            'jumlah' => 24,
            'icon' => 'mdi-file-clock-outline',
            'color' => 'warning',
        ],
        [
            'label' => 'Terverifikasi',
            'jumlah' => 88,
            'icon' => 'mdi-check-decagram-outline',
            'color' => 'success',
        ],
        [
            'label' => 'Perlu Perbaikan',
            'jumlah' => 8,
            'icon' => 'mdi-file-alert-outline',
            'color' => 'danger',
        ],
    ];

    $pendaftar = [
        [
            'nomor' => 'PPDB-2027-0120',
            'nama' => 'Ahmad Fathan Pratama',
            'orang_tua' => 'Budi Santoso',
            'jalur' => 'Reguler',
            'sumber' => 'Online',
            'tanggal' => '08 Okt 2026',
            'status' => 'Menunggu Verifikasi',
            'badge' => 'warning',
        ],
        [
            'nomor' => 'PPDB-2027-0119',
            'nama' => 'Siti Aisyah Putri',
            'orang_tua' => 'Siti Rahmawati',
            'jalur' => 'Prestasi',
            'sumber' => 'Dibantu Panitia',
            'tanggal' => '08 Okt 2026',
            'status' => 'Terverifikasi',
            'badge' => 'success',
        ],
        [
            'nomor' => 'PPDB-2027-0118',
            'nama' => 'Muhammad Rizky Ramadhan',
            'orang_tua' => 'Ahmad Fauzi',
            'jalur' => 'Reguler',
            'sumber' => 'Online',
            'tanggal' => '07 Okt 2026',
            'status' => 'Perlu Perbaikan',
            'badge' => 'danger',
        ],
        [
            'nomor' => 'PPDB-2027-0117',
            'nama' => 'Nabila Zahra',
            'orang_tua' => 'Dewi Anggraini',
            'jalur' => 'Afirmasi',
            'sumber' => 'Online',
            'tanggal' => '07 Okt 2026',
            'status' => 'Terverifikasi',
            'badge' => 'success',
        ],
        [
            'nomor' => 'PPDB-2027-0116',
            'nama' => 'Farhan Al-Faruq',
            'orang_tua' => 'Hendra Setiawan',
            'jalur' => 'Reguler',
            'sumber' => 'Dibantu Panitia',
            'tanggal' => '06 Okt 2026',
            'status' => 'Menunggu Verifikasi',
            'badge' => 'warning',
        ],
    ];
@endphp

<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        {{-- Statistik --}}
        <div class="row">
            @foreach ($statistik as $item)
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <div>
                                    <p class="text-muted mb-2">{{ $item['label'] }}</p>
                                    <h4 class="fw-bold mb-0">{{ $item['jumlah'] }}</h4>
                                </div>

                                <div class="avatar-md flex-shrink-0">
                                    <div class="avatar-title bg-{{ $item['color'] }}-subtle text-{{ $item['color'] }} rounded-3 fs-3">
                                        <i class="mdi {{ $item['icon'] }}"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Tabel pendaftar --}}
        <div class="row">
            <div class="col-xxl-12">
                @livewire('p-p-d-b.admin.pendaftar.index')   
                @livewire('p-p-d-b.admin.pendaftar.detail')    
            </div>
        </div>

    </div>
</div>

@endsection