@extends('template_portal_ppdb.v_template')
@section('content')
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row">
            <div class="col-xxl-12">

                {{-- HEADER --}}
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-transparent border-bottom-0 pb-0">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-sm">
                                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                    <i class="ri-user-star-line"></i>
                                </div>
                            </div>

                            <div>
                                <h5 class="fw-bold mb-1">
                                    Selamat Datang, Bapak/Ibu
                                </h5>

                                <small class="text-muted">
                                    Pantau perkembangan pendaftaran putra-putri Anda melalui portal PPDB.
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="card-body pt-3">
                        @if ($pendaftaran)
                            @php
                                $statusInfo = [
                                    'draft' => [
                                        'label' => 'Belum Dikirim',
                                        'class' => 'bg-secondary-subtle text-secondary',
                                        'icon' => 'ri-draft-line',
                                        'deskripsi' => 'Lengkapi data dan kirim pendaftaran setelah semuanya sesuai.',
                                    ],
                                    'pengecekan' => [
                                        'label' => 'Sedang Diperiksa',
                                        'class' => 'bg-warning-subtle text-warning',
                                        'icon' => 'ri-time-line',
                                        'deskripsi' => 'Pendaftaran sudah dikirim dan sedang diperiksa oleh panitia.',
                                    ],
                                    'perbaikan' => [
                                        'label' => 'Perlu Perbaikan',
                                        'class' => 'bg-danger-subtle text-danger',
                                        'icon' => 'ri-edit-line',
                                        'deskripsi' => 'Ada data yang perlu diperbaiki sesuai catatan panitia.',
                                    ],
                                    'diterima' => [
                                        'label' => 'Diterima',
                                        'class' => 'bg-success-subtle text-success',
                                        'icon' => 'ri-checkbox-circle-line',
                                        'deskripsi' => 'Selamat, calon siswa dinyatakan diterima.',
                                    ],
                                    'ditolak' => [
                                        'label' => 'Tidak Diterima',
                                        'class' => 'bg-danger-subtle text-danger',
                                        'icon' => 'ri-close-circle-line',
                                        'deskripsi' => 'Silakan lihat status pendaftaran untuk informasi selengkapnya.',
                                    ],
                                ];

                                $info = $statusInfo[$pendaftaran->status] ?? [
                                    'label' => 'Dalam Proses',
                                    'class' => 'bg-secondary-subtle text-secondary',
                                    'icon' => 'ri-information-line',
                                    'deskripsi' => 'Silakan periksa status pendaftaran secara berkala.',
                                ];

                                $bolehEdit = in_array($pendaftaran->status, [
                                    'draft',
                                    'perbaikan',
                                ]);
                            @endphp

                            <div class="border rounded-4 p-3 p-md-4">
                                <div class="d-flex flex-column flex-lg-row justify-content-between gap-3">
                                    <div class="flex-grow-1">
                                        <div class="text-muted small mb-1">
                                            Nomor Pendaftaran
                                        </div>

                                        <h4 class="text-primary fw-bold mb-3">
                                            {{ $pendaftaran->nomor_pendaftaran ?? '-' }}
                                        </h4>

                                        <div class="d-flex align-items-center gap-2 mb-3">
                                            <div class="avatar-xs flex-shrink-0">
                                                <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                                    <i class="ri-user-3-line"></i>
                                                </div>
                                            </div>

                                            <div class="fw-semibold">
                                                {{ $pendaftaran->ppdb_calon_siswa->nama_siswa ?? 'Data siswa belum diisi' }}
                                            </div>
                                        </div>

                                        @if ($pendaftaran->ppdb_jalur)
                                            <div class="d-flex align-items-center gap-2 mb-3">
                                                <div class="avatar-xs flex-shrink-0">
                                                    <div class="avatar-title bg-secondary-subtle text-secondary rounded-circle">
                                                        <i class="ri-route-line"></i>
                                                    </div>
                                                </div>

                                                <div class="fw-semibold">
                                                    {{ $pendaftaran->ppdb_jalur->nama_jalur }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="border-top d-lg-none"></div>

                                    <div class="flex-shrink-0" style="min-width: 240px;">
                                        <div class="mb-2">
                                            <span class="badge {{ $info['class'] }} rounded-pill px-3 py-2">
                                                <i class="{{ $info['icon'] }} me-1"></i>
                                                {{ $info['label'] }}
                                            </span>
                                        </div>

                                        <p class="text-muted small mb-3">
                                            {{ $info['deskripsi'] }}
                                        </p>

                                        <div class="d-flex flex-column gap-2">
                                            <a href="{{ route('ppdb.pendaftaran.status', [
                                                'jenjang' => $jenjang->slug,
                                            ]) }}"
                                                class="btn btn-outline-primary rounded-pill">
                                                <i class="ri-file-list-3-line me-1"></i>
                                                Lihat Status
                                            </a>

                                            <a href="{{ route('ppdb.pendaftaran.review', [
                                                'jenjang' => $jenjang->slug,
                                            ]) }}"
                                                class="btn btn-light rounded-pill">
                                                <i class="ri-eye-line me-1"></i>
                                                Preview Data
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-4 py-md-5">
                                <div class="avatar-lg mx-auto mb-3">
                                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                        <i class="ri-file-add-line fs-1"></i>
                                    </div>
                                </div>

                                <h5 class="fw-semibold mb-2">
                                    Belum Ada Pendaftaran
                                </h5>

                                <p class="text-muted mb-4 mx-auto" style="max-width: 380px;">
                                    Mulai proses PPDB dengan memilih jalur pendaftaran yang tersedia.
                                </p>

                                @if ($periode)
                                    <a href="{{ route('ppdb.pendaftaran.jalur', [
                                        'jenjang' => $jenjang->slug,
                                    ]) }}"
                                        class="btn btn-primary rounded-pill px-4">
                                        <i class="ri-arrow-right-line me-1"></i>
                                        Mulai Pendaftaran
                                    </a>
                                @else
                                    <div class="d-inline-flex align-items-center gap-2 text-muted small">
                                        <i class="ri-information-line"></i>
                                        <span>Belum ada periode PPDB yang dibuka saat ini.</span>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                </div>

                @if ($pendaftaran)

                    {{-- PERINGATAN PERBAIKAN --}}
                    @if ($perluPerbaikan)
                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="avatar-sm flex-shrink-0">
                                        <div class="avatar-title bg-warning-subtle text-warning rounded-circle fs-20">
                                            <i class="ri-error-warning-line"></i>
                                        </div>
                                    </div>

                                    <div class="flex-grow-1">
                                        <h5 class="fw-bold mb-2">
                                            Perbaikan Data Diperlukan
                                        </h5>

                                        <p class="text-muted mb-3">
                                            Panitia meminta Anda memperbaiki data pendaftaran.
                                            Silakan baca catatan berikut dan lakukan perbaikan
                                            sebelum mengirim ulang.
                                        </p>

                                        @if (filled($pendaftaran->catatan_perbaikan))
                                            <div class="bg-warning-subtle rounded-3 p-3 mb-3">
                                                <small class="fw-semibold d-block mb-1">
                                                    Catatan dari Panitia
                                                </small>

                                                <p class="mb-0 text-break">
                                                    {{ $pendaftaran->catatan_perbaikan }}
                                                </p>
                                            </div>
                                        @endif

                                        <a href="{{ route('ppdb.pendaftaran.status') }}"
                                            class="btn btn-warning rounded-pill px-4">
                                            <i class="ri-edit-line me-1"></i>
                                            Lihat Instruksi Perbaikan
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- PROGRES PENDAFTARAN --}}
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-transparent">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-sm">
                                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                        <i class="ri-list-check-2"></i>
                                    </div>
                                </div>

                                <div>
                                    <h5 class="fw-bold mb-1">
                                        Progres Pendaftaran
                                    </h5>

                                    <small class="text-muted">
                                        Pantau tahapan pengisian data calon siswa.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            @php
                                $steps = [
                                    1 => [
                                        'label' => 'Jalur Pendaftaran',
                                        'route' => 'ppdb.pendaftaran.jalur',
                                        'icon' => 'ri-route-line',
                                    ],
                                    2 => [
                                        'label' => 'Data Calon Siswa',
                                        'route' => 'ppdb.pendaftaran.data-siswa',
                                        'icon' => 'ri-user-3-line',
                                    ],
                                    3 => [
                                        'label' => 'Orang Tua/Wali',
                                        'route' => 'ppdb.pendaftaran.orang-tua',
                                        'icon' => 'ri-parent-line',
                                    ],
                                    4 => [
                                        'label' => 'Alamat',
                                        'route' => 'ppdb.pendaftaran.alamat',
                                        'icon' => 'ri-map-pin-line',
                                    ],
                                    5 => [
                                        'label' => 'Pendidikan',
                                        'route' => 'ppdb.pendaftaran.pendidikan',
                                        'icon' => 'ri-school-line',
                                    ],
                                    6 => [
                                        'label' => 'Berkas',
                                        'route' => 'ppdb.pendaftaran.berkas',
                                        'icon' => 'ri-file-paper-2-line',
                                    ],
                                    7 => [
                                        'label' => 'Review Data',
                                        'route' => 'ppdb.pendaftaran.review',
                                        'icon' => 'ri-checkbox-circle-line',
                                    ],
                                    8 => [
                                        'label' => 'Menunggu Hasil',
                                        'route' => 'ppdb.pendaftaran.status',
                                        'icon' => 'ri-time-line',
                                    ],
                                ];

                            @endphp

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">
                                    Tahap {{ $currentStep }} dari {{ $totalStep }}
                                </span>

                                <span class="fw-semibold text-primary">
                                    {{ $progressPersen }}%
                                </span>
                            </div>

                            <div class="progress rounded-pill mb-4" style="height: 8px;">
                                <div class="progress-bar bg-primary rounded-pill"
                                    role="progressbar"
                                    style="width: {{ $progressPersen }}%"
                                    aria-valuenow="{{ $progressPersen }}"
                                    aria-valuemin="0"
                                    aria-valuemax="100">
                                </div>
                            </div>

                            <div class="row g-3">
                                @foreach ($steps as $step => $item)
                                    @php
                                        $label = $item['label'];

                                        $sudahDikirim = in_array($status, [
                                            'pengecekan',
                                            'diterima',
                                        ]);

                                        $stepSelesaiSampai = $sudahDikirim
                                            ? max($currentStep, 7)
                                            : $currentStep - 1;

                                        $selesai = $step <= $stepSelesaiSampai;
                                        $aktif = ! $selesai && $step === $currentStep;

                                        $iconClass = $selesai
                                            ? 'bg-success-subtle text-success'
                                            : ($aktif
                                                ? 'bg-primary-subtle text-primary'
                                                : 'bg-light text-muted');

                                        $icon = $selesai
                                            ? 'ri-check-line'
                                            : ($aktif
                                                ? 'ri-time-line'
                                                : 'ri-checkbox-blank-circle-line');

                                        // Tahap yang boleh dibuka.
                                        $bolehDibuka = $step <= $currentStep;

                                        // Setelah dikirim, form tidak boleh dibuka kembali.
                                        if ($sudahDikirim && $step <= 7) {
                                            $bolehDibuka = false;
                                        }

                                        // Status perbaikan boleh mengakses form untuk memperbaiki data.
                                        if ($status === 'perbaikan' && $step >= 2 && $step <= 6) {
                                            $bolehDibuka = true;
                                        }

                                        $slugJenjang = $pendaftaran->ppdb_periode->ms_jenjang->slug ?? null;

                                        $url = $bolehDibuka
                                            && $slugJenjang
                                            && \Illuminate\Support\Facades\Route::has($item['route'])
                                                ? route($item['route'], [
                                                    'jenjang' => $slugJenjang,
                                                ])
                                                : null;
                                    @endphp

                                    <div class="col-12 col-sm-6 col-lg-3">
                                        @if ($url)
                                            <a href="{{ $url }}"
                                                class="text-reset text-decoration-none d-block h-100">
                                        @endif

                                        <div class="border rounded-4 p-3 h-100 {{ $url ? 'cursor-pointer' : '' }}">
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <div class="avatar-xs">
                                                    <div class="avatar-title {{ $iconClass }} rounded-circle">
                                                        <i class="{{ $icon }}"></i>
                                                    </div>
                                                </div>

                                                <small class="text-muted">
                                                    Tahap {{ $step }}
                                                </small>
                                            </div>

                                            <div class="fw-semibold mb-1">
                                                {{ $label }}
                                            </div>

                                            <small class="{{ $selesai ? 'text-success' : ($aktif ? 'text-primary' : 'text-muted') }}">
                                                {{ $selesai ? 'Selesai' : ($aktif ? 'Tahap saat ini' : 'Belum dicapai') }}
                                            </small>

                                            {{-- @if ($url)
                                                <div class="text-primary small mt-2">
                                                    Buka tahap <i class="ri-arrow-right-line align-middle"></i>
                                                </div>
                                            @endif --}}
                                        </div>

                                        @if ($url)
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>


                            @if ($status === 'draft' || $status === 'perbaikan')
                                <div class="mt-4">
                                    <a href="{{ route('ppdb.pendaftaran.data-siswa', [
                                            'jenjang' => $jenjang->slug,
                                        ]) }}"
                                        class="btn btn-primary rounded-pill px-4">
                                        <i class="ri-edit-line me-1"></i>
                                        {{ $status === 'perbaikan' ? 'Perbaiki Data' : 'Lanjutkan Pendaftaran' }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- INFORMASI JADWAL PPDB --}}
                @if ($periode)
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-transparent">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-sm">
                                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                        <i class="ri-calendar-event-line"></i>
                                    </div>
                                </div>

                                <div>
                                    <h5 class="fw-bold mb-1">
                                        Informasi PPDB
                                    </h5>

                                    <small class="text-muted">
                                        Periode penerimaan peserta didik baru.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <h6 class="fw-bold mb-3">
                                {{ $periode->nama_periode }}
                            </h6>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="border rounded-3 p-3 h-100">
                                        <small class="text-muted d-block mb-2">
                                            <i class="ri-calendar-check-line me-1"></i>
                                            Pendaftaran Dibuka
                                        </small>

                                        <div class="fw-semibold">
                                            {{ $periode->tanggal_mulai
                                                ? \Carbon\Carbon::parse($periode->tanggal_mulai)->translatedFormat('d F Y')
                                                : '-' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="border rounded-3 p-3 h-100">
                                        <small class="text-muted d-block mb-2">
                                            <i class="ri-calendar-close-line me-1"></i>
                                            Pendaftaran Ditutup
                                        </small>

                                        <div class="fw-semibold">
                                            {{ $periode->tanggal_selesai
                                                ? \Carbon\Carbon::parse($periode->tanggal_selesai)->translatedFormat('d F Y')
                                                : '-' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if (filled($periode->deskripsi))
                                <p class="text-muted mb-0 mt-3">
                                    {{ $periode->deskripsi }}
                                </p>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- BANTUAN PANITIA --}}
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex flex-column flex-md-row align-items-md-center gap-3">
                            <div class="avatar-sm flex-shrink-0">
                                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                    <i class="ri-customer-service-2-line"></i>
                                </div>
                            </div>

                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1">
                                    Butuh Bantuan?
                                </h6>

                                <p class="text-muted mb-0">
                                    Hubungi panitia PPDB jika Anda membutuhkan informasi
                                    atau bantuan terkait pendaftaran.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
