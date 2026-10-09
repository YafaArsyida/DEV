<!doctype html>
<html lang="id" data-layout="vertical" data-topbar="light" data-sidebar="light" data-bs-theme="light"
      data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>
    <meta charset="utf-8" />
    <title>PPDB Online 2027/2028 | {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Informasi Penerimaan Peserta Didik Baru Tahun Ajaran 2027/2028 di {{ config('app.name') }}.">
    <link rel="shortcut icon" href="{{ asset('assets') }}/images/favicon.ico">
    <script src="{{ asset('assets') }}/js/layout.js"></script>
    <link href="{{ asset('assets') }}/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets') }}/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets') }}/css/app.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets') }}/css/custom.min.css" rel="stylesheet" type="text/css" />
    <style>
        .ppdb-page .ppdb-brand img {
            width: 38px;
            height: 38px;
            object-fit: contain;
        }

        .ppdb-page .ppdb-brand small {
            font-size: 0.7rem;
            letter-spacing: 0.14em;
        }

        .ppdb-page .ppdb-secondary-cta {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.30);
            color: #fff;
        }

        .ppdb-page .ppdb-hero {
            position: relative;
            overflow: hidden;
            background: linear-gradient(120deg, rgba(15, 23, 42, 0.72), rgba(15, 23, 42, 0.42));
            min-height: 640px;
            display: flex;
            align-items: center;
        }

        .ppdb-page .ppdb-hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: url('{{ asset('assets/logo/sekolah.jpg') }}');
            background-size: cover;
            background-position: center;
            transform: scale(1.05);
            z-index: 0;
        }

        .ppdb-page .ppdb-hero-content {
            position: relative;
            z-index: 1;
            padding-top: 5rem;
            padding-bottom: 5rem;
        }

        .ppdb-page .ppdb-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border-radius: 999px;
            background: rgba(255,255,255,0.14);
            color: #fff;
            border: 1px solid rgba(255,255,255,0.18);
            padding: 0.55rem 0.9rem;
            font-size: 0.8rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            font-weight: 700;
        }

        .ppdb-page .ppdb-hero h1 {
            font-size: clamp(2.6rem, 4vw, 4.5rem);
            line-height: 1.1;
            letter-spacing: -0.05em;
            color: #fff;
            margin-bottom: 1rem;
        }

        .ppdb-page .ppdb-hero p {
            color: rgba(255,255,255,0.84);
            font-size: 1.08rem;
            max-width: 660px;
            margin-bottom: 1.4rem;
        }

        .ppdb-page .ppdb-status-pill {
            background: rgba(16, 185, 129, 0.16);
            border: 1px solid rgba(52, 211, 153, 0.4);
            color: #d1fae5;
        }

        .ppdb-page .ppdb-status-pill .dot {
            display: block;
            flex: 0 0 10px;
            width: 10px;
            height: 10px;
            background: #34d399;
            border-radius: 50%;
            box-shadow: 0 0 0 8px rgba(52, 211, 153, 0.12);
        }

        .ppdb-page .ppdb-hero-card img {
            width: 100%;
            min-height: 440px;
            object-fit: cover;
        }

        .ppdb-page .ppdb-hero-card {
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.18);
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .ppdb-page .ppdb-schedule {
            position: relative;
            margin-top: 1rem;
        }

        .ppdb-page .ppdb-schedule::before {
            content: "";
            position: absolute;
            left: 22px;
            top: 12px;
            bottom: 12px;
            width: 2px;
            background: var(--vz-border-color);
        }

        .ppdb-page .ppdb-timeline-item {
            position: relative;
            padding-left: 4.5rem;
            margin-bottom: 1.4rem;
        }

        .ppdb-page .ppdb-timeline-item:last-child {
            margin-bottom: 0;
        }

        .ppdb-page .ppdb-timeline-item .date {
            position: absolute;
            left: 0;
            width: 46px;
            height: 46px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            font-weight: 700;
        }

        .ppdb-page .ppdb-timeline-item .content strong {
            display: block;
            font-size: 1rem;
            margin-bottom: 0.25rem;
            letter-spacing: -0.02em;
        }

        .ppdb-page .ppdb-status-panel {
            position: relative;
            overflow: hidden;
            background: linear-gradient(120deg, var(--vz-primary) 0%, color-mix(in srgb, var(--vz-primary), #0f172a 28%) 100%);
            color: #fff;
        }

        .ppdb-page .ppdb-status-panel::after {
            content: "";
            position: absolute;
            width: 380px;
            height: 380px;
            right: -150px;
            top: -220px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 0 0 36px rgba(255, 255, 255, 0.025), 0 0 0 72px rgba(255, 255, 255, 0.02);
            pointer-events: none;
        }

        .ppdb-page .ppdb-status-copy,
        .ppdb-page .ppdb-status-lookup {
            position: relative;
            z-index: 1;
        }

        .ppdb-page .ppdb-status-kicker {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: rgba(255, 255, 255, 0.82);
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }

        .ppdb-page .ppdb-status-kicker i {
            font-size: 1.1rem;
        }

        .ppdb-page .ppdb-status-copy > p {
            max-width: 430px;
            line-height: 1.7;
        }

        .ppdb-page .ppdb-cta {
            position: relative;
            overflow: hidden;
            background: linear-gradient(120deg, rgba(15, 23, 42, 0.72), rgba(15, 23, 42, 0.55));
            min-height: 280px;
            display: flex;
            align-items: center;
        }

        .ppdb-page .ppdb-cta::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: url('{{ asset('assets/logo/sekolah.jpg') }}');
            background-size: cover;
            background-position: center;
            transform: scale(1.08);
            z-index: 0;
        }

        .ppdb-page .ppdb-cta-content {
            position: relative;
            z-index: 1;
            max-width: 700px;
            color: #fff;
        }

        .ppdb-page .ppdb-cta-content h2 {
            font-size: clamp(2rem, 3vw, 3rem);
            letter-spacing: -0.04em;
            margin-bottom: 0.8rem;
        }

        .ppdb-page .ppdb-cta-content p {
            color: rgba(255,255,255,0.86);
            font-size: 1.04rem;
            margin-bottom: 1.3rem;
            max-width: 560px;
        }

        .ppdb-page .ppdb-whatsapp {
            z-index: 1030;
            right: 1rem;
            bottom: 1rem;
            right: max(1rem, env(safe-area-inset-right));
            bottom: max(1rem, env(safe-area-inset-bottom));
        }

        .ppdb-page .ppdb-whatsapp i {
            font-size: 1.5rem;
        }

        .ppdb-page .ppdb-step-number {
            display: block;
            margin-bottom: 1.25rem;
            color: var(--vz-primary);
            font-size: 2.5rem;
            font-weight: 800;
            letter-spacing: -0.08em;
            line-height: 1;
        }

        @media (max-width: 991.98px) {
            .ppdb-page .ppdb-hero {
                min-height: auto;
            }

            .ppdb-page .ppdb-hero-content {
                padding-top: 4rem;
                padding-bottom: 4rem;
            }
        }

        @media (max-width: 575.98px) {
            .ppdb-page .ppdb-hero h1 {
                font-size: 2.5rem;
            }

        }
    </style>
</head>

<body>
<div class="ppdb-page">
    <nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="ppdb-brand d-flex align-items-center gap-2 fw-bold text-body text-decoration-none" href="{{ route('ppdb.landing') }}">
                <img class="avatar-sm rounded-3 object-fit-cover" src="{{ asset('assets/logo/logo.jpg') }}" alt="Logo sekolah">
                <div class="lh-sm">
                    <span>{{ config('app.name') }}</span>
                    <small class="d-block text-muted fw-semibold text-uppercase">PPDB Online</small>
                </div>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#ppdbNavbar"
                    aria-controls="ppdbNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="ppdbNavbar">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">

                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="#beranda">Beranda</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="#informasi">Informasi</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="#jalur">Jalur</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="#jadwal">Jadwal</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="#persyaratan">Persyaratan</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="#faq">FAQ</a>
                    </li>

                    {{-- Action --}}
                    <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                        <a href="{{ route('ppdb.login') }}" class="btn btn-outline-dark rounded-pill px-3">
                            Login
                        </a>
                    </li>

                    <li class="nav-item mt-2 mt-lg-0">
                        <a href="{{ route('ppdb.daftar') }}" class="btn btn-primary rounded-pill px-3">
                            Daftar Sekarang
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    <main id="beranda">
        <section class="ppdb-hero">
            <div class="container ppdb-hero-content">
                <div class="row align-items-center g-4">
                    <div class="col-lg-7">
                        <span class="ppdb-badge"><i class="ri-calendar-check-line"></i> PPDB Online 2027/2028</span>
                        <h1>Mulai Langkah Baru Bersama Sekolah Kami</h1>
                        <p>Penerimaan Peserta Didik Baru Tahun Ajaran 2027/2028. Daftarkan putra-putri Anda dengan mudah melalui proses pendaftaran online yang praktis, transparan, dan terintegrasi.</p>

                        <div class="ppdb-hero-cta d-grid d-sm-flex gap-3 mt-4">
                            <a href="{{ route('ppdb.daftar') }}" class="btn btn-primary rounded-pill">Daftar Sekarang</a>
                            <a href="{{ route('ppdb.status') }}" class="btn ppdb-secondary-cta rounded-pill">Cek Status Pendaftaran</a>
                        </div>

                        <div class="ppdb-status d-flex flex-column flex-sm-row flex-wrap gap-3 align-items-sm-center mt-4 text-white">
                            <div class="ppdb-status-pill d-inline-flex align-items-center gap-2 rounded-pill px-3 py-2 fw-semibold"><span class="dot"></span> Pendaftaran Sedang Dibuka</div>
                            <div class="text-white-50">1 Oktober – 30 November 2026</div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="ppdb-hero-card rounded-4 shadow-sm">
                            <img src="{{ asset('assets/images/about.jpg') }}" alt="Kegiatan sekolah">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5 pt-0 mt-n4">
            <div class="container">

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body p-0">

                        <div class="row g-0">

                            @php
                                $quickInfo = [
                                    [
                                        'icon' => 'ri-calendar-event-line',
                                        'label' => 'Tahun Ajaran',
                                        'value' => '2027/2028',
                                    ],
                                    [
                                        'icon' => 'ri-calendar-check-line',
                                        'label' => 'Pendaftaran',
                                        'value' => '1 Okt – 30 Nov 2026',
                                    ],
                                    [
                                        'icon' => 'ri-route-line',
                                        'label' => 'Jalur Pendaftaran',
                                        'value' => '3 Jalur',
                                    ],
                                    [
                                        'icon' => 'ri-group-line',
                                        'label' => 'Kuota Penerimaan',
                                        'value' => '120 Siswa',
                                    ],
                                ];
                            @endphp

                            @foreach ($quickInfo as $index => $info)
                                <div class="col-lg-3 col-md-6 col-12">
                                    <div class="p-4 h-100
                                        {{ $index < 3 ? 'border-end border-bottom border-lg-bottom-0' : 'border-bottom' }}
                                        position-relative">

                                        <div class="d-flex align-items-start gap-3">

                                            <div class="avatar-sm flex-shrink-0">
                                                <div class="avatar-title bg-primary-subtle text-primary rounded-3 fs-5">
                                                    <i class="{{ $info['icon'] }}"></i>
                                                </div>
                                            </div>

                                            <div class="min-w-0">
                                                <div class="text-muted text-uppercase fw-medium fs-12 mb-1">
                                                    {{ $info['label'] }}
                                                </div>

                                                <div class="fw-semibold text-body fs-15">
                                                    {{ $info['value'] }}
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            @endforeach

                        </div>

                    </div>
                </div>

            </div>
        </section>

        <section class="py-5" id="informasi">
            <div class="container">

                <div class="text-center mx-auto mb-5" style="max-width: 760px;">
                    <span class="d-inline-block text-primary text-uppercase fw-semibold small mb-2">
                        Mengapa Memilih Kami
                    </span>

                    <h2 class="display-6 fw-bold mb-3">
                        Pendidikan yang Menumbuhkan Ilmu, Karakter, dan Akhlak
                    </h2>

                    <p class="text-muted mx-auto mb-0 lh-lg">
                        Kami menghadirkan lingkungan pendidikan yang mendukung tumbuh kembang
                        anak melalui pembelajaran yang berkualitas, pembiasaan nilai-nilai Islam,
                        serta pengembangan potensi setiap siswa.
                    </p>
                </div>

                @php
                    $features = [
                        [
                            'icon' => 'ri-book-open-line',
                            'title' => 'Pembelajaran Berkualitas',
                            'text' => 'Pembelajaran terarah dan menyenangkan yang membantu siswa membangun pengetahuan, keterampilan, dan rasa ingin tahu.',
                        ],
                        [
                            'icon' => 'ri-heart-3-line',
                            'title' => 'Karakter dan Akhlak',
                            'text' => 'Nilai-nilai Islam ditanamkan melalui pembiasaan sehari-hari untuk membentuk pribadi yang santun, disiplin, dan bertanggung jawab.',
                        ],
                        [
                            'icon' => 'ri-seedling-line',
                            'title' => 'Tumbuh Sesuai Potensi',
                            'text' => 'Setiap anak memiliki keunikan. Kami mendukung perkembangan akademik, kreativitas, minat, dan bakat siswa.',
                        ],
                        [
                            'icon' => 'ri-community-line',
                            'title' => 'Lingkungan yang Positif',
                            'text' => 'Lingkungan belajar yang aman, nyaman, dan kolaboratif agar anak dapat tumbuh dengan percaya diri.',
                        ],
                    ];
                @endphp

                <div class="row g-4 mt-2">

                    @foreach ($features as $index => $feature)
                        <div class="col-lg-3 col-md-6 col-12">

                            <div class="card h-100 border rounded-4 shadow-none">
                                <div class="card-body p-4">

                                    <div class="avatar-md mb-4">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-3 fs-4">
                                            <i class="{{ $feature['icon'] }}"></i>
                                        </div>
                                    </div>

                                    <h3 class="fs-5 fw-semibold mb-3">
                                        {{ $feature['title'] }}
                                    </h3>

                                    <p class="text-muted lh-lg mb-0">
                                        {{ $feature['text'] }}
                                    </p>

                                </div>
                            </div>

                        </div>
                    @endforeach

                </div>

            </div>
        </section>

        <section class="py-5 bg-white" id="jalur">
            <div class="container">

                <div class="text-center mx-auto mb-5" style="max-width: 720px;">
                    <span class="d-inline-block text-primary text-uppercase fw-semibold small mb-2">
                        Pilihan Jalur
                    </span>

                    <h2 class="display-6 fw-bold mb-3">
                        Pilih Jalur Pendaftaran
                    </h2>

                    <p class="text-muted mb-0 lh-lg">
                        Tersedia beberapa jalur penerimaan yang dapat disesuaikan
                        dengan kondisi dan persyaratan calon peserta didik.
                    </p>
                </div>

                @php
                    $routes = [
                        [
                            'icon' => 'ri-user-add-line',
                            'title' => 'Jalur Reguler',
                            'description' => 'Jalur penerimaan umum bagi calon peserta didik sesuai persyaratan yang ditetapkan sekolah.',
                            'quota' => '60 siswa',
                        ],
                        [
                            'icon' => 'ri-trophy-line',
                            'title' => 'Jalur Prestasi',
                            'description' => 'Jalur bagi calon peserta didik yang memiliki prestasi akademik maupun non-akademik.',
                            'quota' => '35 siswa',
                        ],
                        [
                            'icon' => 'ri-heart-3-line',
                            'title' => 'Jalur Afirmasi',
                            'description' => 'Jalur khusus bagi calon peserta didik sesuai kriteria dan ketentuan yang berlaku.',
                            'quota' => '25 siswa',
                        ],
                    ];
                @endphp

                <div class="row g-4">

                    @foreach ($routes as $route)
                        <div class="col-lg-4 col-md-6 col-12">

                            <div class="card h-100 border rounded-4 shadow-none">
                                <div class="card-body p-4">

                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <div class="avatar-md">
                                            <div class="avatar-title bg-primary-subtle text-primary rounded-3 fs-4">
                                                <i class="{{ $route['icon'] }}"></i>
                                            </div>
                                        </div>

                                        <span class="badge bg-light text-body fw-medium px-3 py-2">
                                            {{ $route['quota'] }}
                                        </span>
                                    </div>

                                    <h3 class="fs-5 fw-semibold mb-2">
                                        {{ $route['title'] }}
                                    </h3>

                                    <p class="text-muted lh-lg mb-4">
                                        {{ $route['description'] }}
                                    </p>

                                    <a href="#persyaratan"
                                    class="btn btn-primary fw-medium rounded-3 w-100">
                                        Lihat Persyaratan
                                        <i class="ri-arrow-right-line align-middle ms-1"></i>
                                    </a>

                                </div>
                            </div>

                        </div>
                    @endforeach

                </div>

            </div>
        </section>

        <section class="py-5" id="jadwal">
            <div class="container">
                <div class="text-center mx-auto mb-5">
                    <span class="d-inline-block text-primary text-uppercase fw-semibold small mb-2">Jadwal PPDB</span>
                    <h2 class="display-6 fw-bold mb-0">Jadwal PPDB 2027/2028</h2>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="ppdb-schedule">
                            @php
                                $schedule = [
                                    ['date' => '01', 'month' => 'Okt', 'title' => 'Pendaftaran Dibuka', 'desc' => 'Pendaftaran dimulai secara online'],
                                    ['date' => '30', 'month' => 'Nov', 'title' => 'Pendaftaran Ditutup', 'desc' => 'Batas waktu pengiriman formulir'],
                                    ['date' => '05', 'month' => 'Des', 'title' => 'Verifikasi Berkas', 'desc' => 'Penilaian dan validasi dokumen'],
                                    ['date' => '10', 'month' => 'Des', 'title' => 'Pengumuman', 'desc' => 'Hasil seleksi diumumkan'],
                                    ['date' => '11 – 15', 'month' => 'Des', 'title' => 'Daftar Ulang', 'desc' => 'Konfirmasi pendaftaran bagi calon siswa'],
                                ];
                            @endphp

                            @foreach ($schedule as $item)
                                <div class="ppdb-timeline-item">
                                    <div class="date avatar-title bg-primary-subtle text-primary">{{ $item['date'] }}</div>
                                    <div class="content card border-0 shadow-sm rounded-4">
                                        <div class="card-body p-3">
                                            <strong>{{ $item['title'] }}</strong>
                                            <span class="text-muted">{{ $item['month'] }} 2026 · {{ $item['desc'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5 bg-white" id="persyaratan">
            <div class="container">

                <div class="text-center mx-auto mb-5">
                    <span class="d-inline-block text-primary text-uppercase fw-semibold small mb-2">
                        Persyaratan
                    </span>
                    <h2 class="display-6 fw-bold mb-0">
                        Persyaratan Pendaftaran
                    </h2>
                </div>

                <div class="row g-4">

                    {{-- Persyaratan Umum --}}
                    <div class="col-lg-6">
                        <div class="card h-100 border shadow-sm rounded-4">
                            <div class="card-body p-4 p-lg-5">

                                <div class="d-flex align-items-center gap-3 mb-4">
                                    <div class="avatar-md bg-primary-subtle text-primary rounded-3
                                                d-flex align-items-center justify-content-center fs-4">
                                        <i class="ri-user-settings-line"></i>
                                    </div>

                                    <div>
                                        <h3 class="fs-5 fw-semibold mb-1">
                                            Persyaratan Umum
                                        </h3>
                                        <p class="text-muted mb-0 small">
                                            Ketentuan yang perlu dipenuhi calon peserta didik
                                        </p>
                                    </div>
                                </div>

                                <ul class="list-unstyled mb-0">
                                    <li class="d-flex align-items-start gap-3 mb-3">
                                        <i class="ri-checkbox-circle-line text-primary fs-5"></i>
                                        <span>Memenuhi ketentuan usia</span>
                                    </li>

                                    <li class="d-flex align-items-start gap-3 mb-3">
                                        <i class="ri-checkbox-circle-line text-primary fs-5"></i>
                                        <span>Mengisi formulir pendaftaran</span>
                                    </li>

                                    <li class="d-flex align-items-start gap-3 mb-3">
                                        <i class="ri-checkbox-circle-line text-primary fs-5"></i>
                                        <span>Melengkapi data calon peserta didik</span>
                                    </li>

                                    <li class="d-flex align-items-start gap-3">
                                        <i class="ri-checkbox-circle-line text-primary fs-5"></i>
                                        <span>Menyetujui ketentuan PPDB</span>
                                    </li>
                                </ul>

                            </div>
                        </div>
                    </div>

                    {{-- Dokumen --}}
                    <div class="col-lg-6">
                        <div class="card h-100 border shadow-sm rounded-4">
                            <div class="card-body p-4 p-lg-5">

                                <div class="d-flex align-items-center gap-3 mb-4">
                                    <div class="avatar-md bg-primary-subtle text-primary rounded-3
                                                d-flex align-items-center justify-content-center fs-4">
                                        <i class="ri-file-list-3-line"></i>
                                    </div>

                                    <div>
                                        <h3 class="fs-5 fw-semibold mb-1">
                                            Dokumen yang Perlu Disiapkan
                                        </h3>
                                        <p class="text-muted mb-0 small">
                                            Dokumen untuk proses pendaftaran
                                        </p>
                                    </div>
                                </div>

                                <ul class="list-unstyled mb-0">
                                    <li class="d-flex align-items-start gap-3 mb-3">
                                        <i class="ri-checkbox-circle-line text-primary fs-5"></i>
                                        <span>Kartu Keluarga</span>
                                    </li>

                                    <li class="d-flex align-items-start gap-3 mb-3">
                                        <i class="ri-checkbox-circle-line text-primary fs-5"></i>
                                        <span>Akta Kelahiran</span>
                                    </li>

                                    <li class="d-flex align-items-start gap-3 mb-3">
                                        <i class="ri-checkbox-circle-line text-primary fs-5"></i>
                                        <span>KTP Orang Tua/Wali</span>
                                    </li>

                                    <li class="d-flex align-items-start gap-3 mb-3">
                                        <i class="ri-checkbox-circle-line text-primary fs-5"></i>
                                        <span>Pas Foto</span>
                                    </li>

                                    <li class="d-flex align-items-start gap-3">
                                        <i class="ri-checkbox-circle-line text-primary fs-5"></i>
                                        <span>Dokumen pendukung sesuai jalur</span>
                                    </li>
                                </ul>

                            </div>
                        </div>
                    </div>

                </div>

                <div class="mt-4 p-3 p-lg-4 bg-light border rounded-3 text-muted">
                    <div class="d-flex align-items-start gap-2">
                        <i class="ri-information-line fs-5"></i>
                        <span>
                            Persyaratan dan dokumen dapat berbeda sesuai jalur pendaftaran
                            yang dipilih.
                        </span>
                    </div>
                </div>

            </div>
        </section>

        <section class="py-5" id="cara-daftar">
            <div class="container">
                <div class="text-center mx-auto mb-5">
                    <span class="d-inline-block text-primary text-uppercase fw-semibold small mb-2">Cara daftar</span>
                    <h2 class="display-6 fw-bold mb-0">Bagaimana Cara Mendaftar?</h2>
                </div>

                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-5 g-4">
                    @php
                        $steps = [
                            ['number' => '01', 'title' => 'Buat Akun', 'text' => 'Masuk ke portal PPDB dan buat akun pendaftar baru.'],
                            ['number' => '02', 'title' => 'Lengkapi Data', 'text' => 'Isi biodata calon siswa dan orang tua secara lengkap.'],
                            ['number' => '03', 'title' => 'Upload Dokumen', 'text' => 'Unggah berkas persyaratan sesuai jalur yang dipilih.'],
                            ['number' => '04', 'title' => 'Kirim Pendaftaran', 'text' => 'Periksa data dan kirim formulir setelah semua dokumen lengkap.'],
                            ['number' => '05', 'title' => 'Pantau Hasil', 'text' => 'Pantau status pendaftaran dan hasil seleksi secara online.'],
                        ];
                    @endphp
                    @foreach ($steps as $step)
                        <div class="col">
                            <div class="card h-100 border shadow-sm rounded-4">
                                <div class="card-body p-4">
                                    <span class="ppdb-step-number">{{ $step['number'] }}</span>
                                    <h3 class="fs-5 fw-semibold mb-2">{{ $step['title'] }}</h3>
                                    <p class="text-muted lh-lg mb-0">{{ $step['text'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="py-5" id="ppdb-status">
            <div class="container">
                <div class="ppdb-status-panel rounded-4 p-4 p-lg-5 shadow">
                    <div class="row align-items-center g-4">
                        <div class="col-lg-6 ppdb-status-copy">
                            <span class="ppdb-status-kicker"><i class="ri-search-eye-line"></i> Layanan pendaftar</span>
                            <h3 class="fs-2 fw-bold text-white">Sudah mendaftar? Cek perkembangan pendaftaran Anda.</h3>
                            <p class="text-white-50">Masukkan nomor pendaftaran untuk melihat informasi proses seleksi dan pengumuman. Nomor pendaftaran tercantum pada bukti pendaftaran Anda.</p>

                            <div class="ppdb-status-points d-flex flex-wrap gap-3 text-white-50">
                                <span class="d-inline-flex align-items-center gap-2"><i class="ri-checkbox-circle-line text-success"></i> Informasi proses pendaftaran</span>
                                <span class="d-inline-flex align-items-center gap-2"><i class="ri-checkbox-circle-line text-success"></i> Pengumuman hasil seleksi</span>
                            </div>
                        </div>

                        <div class="col-lg-6 card border-0 shadow rounded-4 p-4 p-lg-5 bg-white text-body ppdb-status-lookup">
                            <h4 class="fs-5 fw-semibold mb-2">Cek status pendaftaran</h4>
                            <p class="text-muted mb-4">Siapkan nomor pendaftaran yang Anda terima setelah mengirim formulir.</p>

                            <label for="ppdb-registration-number" class="form-label fw-semibold">Nomor pendaftaran</label>
                            <input
                                type="text"
                                class="form-control"
                                id="ppdb-registration-number"
                                name="registration_number"
                                placeholder="Contoh: PPDB-2027-0001"
                                autocomplete="off"
                                aria-describedby="ppdb-registration-hint">
                            <small class="form-text" id="ppdb-registration-hint">Masukkan nomor sesuai bukti pendaftaran.</small>

                            <button type="button" class="btn btn-primary rounded-pill w-100 mt-3">
                                Cek Status <i class="ri-arrow-right-line ms-1"></i>
                            </button>

                            <div class="ppdb-status-divider d-flex align-items-center gap-3 my-4 small text-muted"><span class="border-top flex-grow-1"></span>atau<span class="border-top flex-grow-1"></span></div>

                            <div class="ppdb-status-login d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center justify-content-between gap-3">
                                <div>
                                    <strong class="d-block mb-1">Sudah memiliki akun?</strong>
                                    <span class="small text-muted">Masuk ke Portal Pendaftar untuk melihat detail.</span>
                                </div>
                                <a href="#" class="btn btn-outline-primary rounded-pill flex-shrink-0">Login <i class="ri-login-box-line ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5" id="faq">
            <div class="container">
                <div class="text-center mx-auto mb-5">
                    <span class="d-inline-block text-primary text-uppercase fw-semibold small mb-2">FAQ</span>
                    <h2 class="display-6 fw-bold mb-0">Pertanyaan yang Sering Diajukan</h2>
                </div>

                <div class="accordion ppdb-faq" id="ppdbFaq">
                    <div class="accordion-item border-0 shadow-sm rounded-4 overflow-hidden mb-3">
                        <h2 class="accordion-header" id="faqOne">
                            <button class="accordion-button fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Siapa yang dapat mendaftar?
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="faqOne" data-bs-parent="#ppdbFaq">
                            <div class="accordion-body text-muted lh-lg">
                                Calon peserta didik baru yang memenuhi ketentuan usia, persyaratan administrasi, dan syarat jalur pendaftaran yang berlaku dapat mendaftar.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 shadow-sm rounded-4 overflow-hidden mb-3">
                        <h2 class="accordion-header" id="faqTwo">
                            <button class="accordion-button fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Apakah pendaftaran dilakukan secara online?
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="faqTwo" data-bs-parent="#ppdbFaq">
                            <div class="accordion-body text-muted lh-lg">
                                Ya, seluruh proses pendaftaran dilakukan secara online mulai dari pengisian formulir, upload dokumen, hingga pemantauan status pendaftaran.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 shadow-sm rounded-4 overflow-hidden mb-3">
                        <h2 class="accordion-header" id="faqThree">
                            <button class="accordion-button fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Dokumen apa saja yang harus disiapkan?
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="faqThree" data-bs-parent="#ppdbFaq">
                            <div class="accordion-body text-muted lh-lg">
                                Umumnya dokumen seperti Kartu Keluarga, Akta Kelahiran, KTP Orang Tua/Wali, pas foto, dan dokumen pendukung sesuai jalur pendaftaran yang dipilih.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 shadow-sm rounded-4 overflow-hidden mb-3">
                        <h2 class="accordion-header" id="faqFour">
                            <button class="accordion-button fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                Apakah data dapat diperbaiki setelah dikirim?
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="faqFour" data-bs-parent="#ppdbFaq">
                            <div class="accordion-body text-muted lh-lg">
                                Data yang sudah dikirim dapat diperbaiki selama masa pengisian masih dibuka, sebelum proses verifikasi berkas atau pengumuman dilakukan.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 shadow-sm rounded-4 overflow-hidden mb-3">
                        <h2 class="accordion-header" id="faqFive">
                            <button class="accordion-button fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                Bagaimana mengetahui hasil seleksi?
                            </button>
                        </h2>
                        <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="faqFive" data-bs-parent="#ppdbFaq">
                            <div class="accordion-body text-muted lh-lg">
                                Hasil seleksi akan diumumkan melalui portal PPDB dan dapat dicek menggunakan nomor pendaftaran yang telah dibuat pada saat pendaftaran.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 shadow-sm rounded-4 overflow-hidden mb-3">
                        <h2 class="accordion-header" id="faqSix">
                            <button class="accordion-button fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                Bagaimana jika mengalami kendala saat pendaftaran?
                            </button>
                        </h2>
                        <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="faqSix" data-bs-parent="#ppdbFaq">
                            <div class="accordion-body text-muted lh-lg">
                                Jika mengalami kendala, Anda dapat menghubungi petugas PPDB melalui nomor telepon atau email yang tercantum pada halaman informasi ini.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5" id="daftar">
            <div class="container">
                <div class="ppdb-cta rounded-4 p-4 p-lg-5">
                    <div class="ppdb-cta-content">
                        <span class="ppdb-badge d-inline-flex mb-3">Siap untuk memulai?</span>
                        <h2 class="text-white">Siap Bergabung Bersama Kami?</h2>
                        <p>Daftarkan putra-putri Anda melalui PPDB Online dan mulai langkah baru bersama sekolah kami.</p>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('ppdb.daftar') }}" class="btn btn-primary rounded-pill">Daftar Sekarang</a>
                            <a href="#informasi" class="btn ppdb-secondary-cta rounded-pill">Lihat Informasi PPDB</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-dark text-white-50 py-5 text-center text-md-start">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="brand-box d-flex align-items-center justify-content-center justify-content-md-start gap-3 mb-3">
                        <img class="avatar-sm rounded-3 object-fit-cover" src="{{ asset('assets/logo/logo.jpg') }}" alt="Logo sekolah">
                        <div>
                            <h4 class="text-white mb-0">{{ config('app.name') }}</h4>
                        </div>
                    </div>
                    <p>Tempat belajar yang menumbuhkan semangat, karakter, dan prestasi bagi generasi masa depan.</p>
                    <div class="socials d-flex justify-content-center justify-content-md-start gap-2 mt-3">
                        <a class="btn btn-sm btn-outline-light rounded-3" href="#"><i class="ri-facebook-fill"></i></a>
                        <a class="btn btn-sm btn-outline-light rounded-3" href="#"><i class="ri-instagram-line"></i></a>
                        <a class="btn btn-sm btn-outline-light rounded-3" href="#"><i class="ri-youtube-line"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6">
                    <h4 class="text-white">Informasi</h4>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a class="link-light text-decoration-none" href="#informasi">Profil</a></li>
                        <li class="mb-2"><a class="link-light text-decoration-none" href="#jalur">Jalur</a></li>
                        <li class="mb-2"><a class="link-light text-decoration-none" href="#jadwal">Jadwal</a></li>
                        <li><a class="link-light text-decoration-none" href="#faq">FAQ</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white">PPDB</h4>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a class="link-light text-decoration-none" href="#daftar">Daftar</a></li>
                        <li class="mb-2"><a class="link-light text-decoration-none" href="#ppdb-status">Cek Status</a></li>
                        <li class="mb-2"><a class="link-light text-decoration-none" href="#persyaratan">Persyaratan</a></li>
                        <li><a class="link-light text-decoration-none" href="#cara-daftar">Panduan</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white">Kontak</h4>
                    <ul class="list-unstyled">
                        <li class="mb-2">Jl. Pendidikan No. 45</li>
                        <li class="mb-2">+62 812-3456-7890</li>
                        <li>ppdb@temansekolah.sch.id</li>
                    </ul>
                </div>
            </div>

            <div class="text-center border-top mt-4 pt-3">
                © 2027 TemanSekolah. All rights reserved.
            </div>
        </div>
    </footer>

    <a
        class="btn btn-success rounded-pill shadow-lg d-inline-flex align-items-center gap-2 px-4 py-3 fw-semibold position-fixed ppdb-whatsapp"
        href="https://wa.me/6281234567890?text={{ urlencode('Halo, saya ingin bertanya tentang PPDB Tahun Ajaran 2027/2028.') }}"
        target="_blank"
        rel="noopener noreferrer"
        aria-label="Hubungi sekolah melalui WhatsApp tentang PPDB"
        title="Hubungi sekolah melalui WhatsApp">
        <i class="ri-whatsapp-fill" aria-hidden="true"></i>
        <span>Hubungi kami</span>
    </a>
</div>

    <script src="{{ asset('assets') }}/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
