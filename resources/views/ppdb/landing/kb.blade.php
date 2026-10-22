<!doctype html>
<html lang="id" data-layout="vertical" data-topbar="light" data-sidebar="light" data-bs-theme="light" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">
<head>
    <meta charset="utf-8" />
    <title>PPDB {{ $jenjang->nama_jenjang }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Informasi Penerimaan Peserta Didik Baru Tahun Ajaran 2027/2028 di {{ $jenjang->nama_jenjang }}.">
    {{-- <link rel="shortcut icon" href="{{ asset('assets') }}/images/favicon.ico"> --}}
    <link rel="shortcut icon" href="{{asset('assets')}}/logo/hero.png">
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

        .ppdb-page .ppdb-help {
            z-index: 1030;
            right: 1rem;
            bottom: 1rem;
            right: max(1rem, env(safe-area-inset-right));
            bottom: max(1rem, env(safe-area-inset-bottom));
        }

        .ppdb-page .ppdb-help i {
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
            <a class="ppdb-brand d-flex align-items-center gap-2 fw-bold text-body text-decoration-none"
            href="{{ route('ppdb.kb') }}">
                <img class="avatar-sm rounded-3 object-fit-cover"
                    src="{{ asset('assets/logo/logo.jpg') }}"
                    alt="Logo {{ $jenjang->nama_jenjang }}">

                <div class="lh-sm">
                    <span>{{ $jenjang->nama_jenjang }}</span>
                    <small class="d-block text-muted fw-semibold text-uppercase">
                        PPDB Online
                    </small>
                </div>
            </a>

            <button class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#ppdbNavbar"
                    aria-controls="ppdbNavbar"
                    aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="ppdbNavbar">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="#beranda">
                            Beranda
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="#informasi">
                            Informasi
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="#program">
                            Program
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="#jadwal">
                            Jadwal
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="#persyaratan">
                            Persyaratan
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="#faq">
                            FAQ
                        </a>
                    </li>

                    @auth
                        @if (Auth::user()->peran == 'ADMIN' ||
                            Auth::user()->peran == 'SUPERADMIN')
                            <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                                <a href="{{ route('ppdb.admin.dashboard') }}"
                                class="btn btn-primary rounded-pill px-3">
                                    <i class="ri-home-line me-1"></i>
                                    Dashboard Admin
                                </a>
                            </li>
                        @else
                            <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                                <a href="{{ route('ppdb.dashboard' , [
                                    'jenjang' => $jenjang->slug,
                                ]) }}"
                                    class="btn btn-primary rounded-pill px-3">
                                    <i class="ri-home-line me-1"></i>
                                    Dashboard
                                </a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                            <a href="{{ route('ppdb.login' , [
                                'jenjang' => $jenjang->slug,
                            ]) }}"
                                class="btn btn-outline-dark rounded-pill px-3">
                                Login
                            </a>
                        </li>

                        <li class="nav-item mt-2 mt-lg-0">
                            <a href="{{ route('ppdb.daftar', [
                                'jenjang' => $jenjang->slug,
                            ]) }}"
                            class="btn btn-primary rounded-pill px-3">
                                Daftar Sekarang
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>


    <main id="beranda">
        <section class="ppdb-hero" id="beranda">
            <div class="container ppdb-hero-content">
                <div class="row align-items-center g-4">

                    {{-- Konten Utama --}}
                    <div class="col-lg-7">
                        <span class="ppdb-badge">
                            <i class="ri-heart-3-line"></i>
                            PPDB KB Tahun Ajaran 2027/2028
                        </span>

                        <h1>
                            Langkah Kecil, Tumbuh Bahagia
                        </h1>

                        <p>
                            Temani si kecil mengenal dunia melalui bermain,
                            mengembangkan kemampuan sesuai tahap usianya,
                            dan membiasakan doa serta akhlak baik dalam
                            lingkungan yang hangat dan menyenangkan bersama
                            {{ $jenjang->nama_jenjang }}.
                        </p>

                        {{-- CTA --}}
                        <div class="ppdb-hero-cta d-grid d-sm-flex gap-3 mt-4">
                            <a href="{{ route('ppdb.daftar', [
                                'jenjang' => $jenjang->slug,
                            ]) }}"
                                class="btn btn-primary rounded-pill">
                                Daftar Sekarang
                            </a>

                            <a href="#informasi"
                                class="btn ppdb-secondary-cta rounded-pill">
                                Kenali KB Kami
                            </a>
                        </div>

                        {{-- Status Pendaftaran --}}
                        <div
                            class="ppdb-status d-flex flex-column flex-sm-row flex-wrap gap-3 align-items-sm-center mt-4 text-white">

                            <div
                                class="ppdb-status-pill d-inline-flex align-items-center gap-2 rounded-pill px-3 py-2 fw-semibold">
                                <span class="dot"></span>
                                Pendaftaran Tahun Ajaran 2027/2028
                            </div>

                            <div class="text-white-50">
                                1 Oktober – 30 November 2026
                            </div>
                        </div>
                    </div>

                    {{-- Visual Utama --}}
                    <div class="col-lg-5">
                        <div class="ppdb-hero-card rounded-4 shadow-sm">
                            <img
                                src="{{ asset('assets/images/about.jpg') }}"
                                alt="Kegiatan bermain dan stimulasi tumbuh kembang anak {{ $jenjang->nama_jenjang }}">
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
                                        'label' => 'Periode Pendaftaran',
                                        'value' => '1 Okt – 30 Nov 2026',
                                    ],
                                    [
                                        'icon' => 'ri-user-heart-line',
                                        'label' => 'Sasaran Usia',
                                        'value' => 'Sekitar 2–4 Tahun',
                                    ],
                                    [
                                        'icon' => 'ri-seedling-line',
                                        'label' => 'Fokus Pembelajaran',
                                        'value' => 'Tumbuh Kembang Anak',
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

                {{-- Section Heading --}}
                <div class="text-center mx-auto mb-5" style="max-width: 680px;">
                    <span class="d-inline-block text-primary text-uppercase fw-semibold small mb-2">
                        Mengenal KB Kami
                    </span>

                    <h2 class="display-6 fw-bold mb-3">
                        Awal Kecil untuk Masa Depan yang Hebat
                    </h2>

                    <p class="text-muted lh-lg mb-0">
                        Setiap anak tumbuh dengan caranya sendiri.
                        Kami menemani si kecil belajar, bermain, dan mengenal
                        kebiasaan baik dalam suasana yang penuh kasih.
                    </p>
                </div>

                {{-- Keunggulan KB --}}
                @php
                    $features = [
                        [
                            'icon' => 'ri-gamepad-line',
                            'title' => 'Serunya Belajar Sambil Bermain',
                            'text' => 'Bernyanyi, bergerak, bereksplorasi, dan mencoba hal baru setiap hari.',
                        ],
                        [
                            'icon' => 'ri-heart-3-line',
                            'title' => 'Mengenal Islam Sejak Dini',
                            'text' => 'Belajar berdoa, mengucapkan salam, dan membiasakan perilaku baik.',
                        ],
                        [
                            'icon' => 'ri-seedling-line',
                            'title' => 'Tumbuh Sesuai Usianya',
                            'text' => 'Melatih gerak, bahasa, rasa ingin tahu, dan keberanian secara bertahap.',
                        ],
                        [
                            'icon' => 'ri-parent-line',
                            'title' => 'Didampingi dengan Kasih Sayang',
                            'text' => 'Membantu si kecil merasa nyaman, mulai mandiri, dan berteman.',
                        ],
                    ];
                @endphp

                <div class="row g-4 mt-2">
                    @foreach ($features as $feature)
                        <div class="col-lg-3 col-md-6 col-12">
                            <div class="card h-100 border rounded-4 shadow-none mb-0">
                                <div class="card-body p-4">

                                    <div class="avatar-md mb-3">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-3 fs-4">
                                            <i class="{{ $feature['icon'] }}"></i>
                                        </div>
                                    </div>

                                    <h3 class="fs-5 fw-semibold mb-2">
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

                {{-- Penegasan --}}
                <div class="text-center mt-4">
                    <p class="text-muted mb-0">
                        <i class="ri-sparkling-2-line text-primary me-1"></i>
                        Langkah kecil hari ini, bekal berharga untuk esok hari.
                    </p>
                </div>

            </div>
        </section>

        <section class="py-5 bg-white" id="program">
            <div class="container">

                {{-- Section Heading --}}
                <div class="text-center mx-auto mb-5" style="max-width: 720px;">
                    <span class="d-inline-block text-primary text-uppercase fw-semibold small mb-2">
                        Kegiatan Si Kecil
                    </span>

                    <h2 class="display-6 fw-bold mb-3">
                        Banyak Hal Seru untuk Dipelajari
                    </h2>

                    <p class="text-muted mb-0 lh-lg">
                        Dari bermain hingga mengenal kebiasaan baik, setiap kegiatan
                        menjadi kesempatan bagi si kecil untuk tumbuh dan mencoba hal baru.
                    </p>
                </div>

                {{-- Program KB --}}
                @php
                    $programs = [
                        [
                            'icon' => 'ri-gamepad-line',
                            'title' => 'Bermain dan Bereksplorasi',
                            'description' => 'Bernyanyi, bermain warna, mengenal benda, dan melakukan aktivitas seru yang membangkitkan rasa ingin tahu.',
                            'label' => 'Belajar Sambil Bermain',
                            'target' => '#informasi',
                            'button' => 'Kenali KB Kami',
                        ],
                        [
                            'icon' => 'ri-seedling-line',
                            'title' => 'Belajar Mandiri dan Berani',
                            'description' => 'Berlatih bergerak, berbicara, mencoba sendiri, dan berinteraksi dengan teman secara bertahap.',
                            'label' => 'Tumbuh Kembang',
                            'target' => '#informasi',
                            'button' => 'Lihat Manfaatnya',
                        ],
                        [
                            'icon' => 'ri-heart-3-line',
                            'title' => 'Membiasakan Akhlak Baik',
                            'description' => 'Mulai mengenal doa sederhana, mengucapkan salam, berbagi, dan membiasakan perilaku baik sehari-hari.',
                            'label' => 'Nilai-Nilai Islami',
                            'target' => '#informasi',
                            'button' => 'Kenali Pembiasaan',
                        ],
                    ];
                @endphp

                <div class="row g-4">
                    @foreach ($programs as $program)
                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="card h-100 border rounded-4 shadow-none mb-0">
                                <div class="card-body p-4 d-flex flex-column">

                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <div class="avatar-md">
                                            <div class="avatar-title bg-primary-subtle text-primary rounded-3 fs-4">
                                                <i class="{{ $program['icon'] }}"></i>
                                            </div>
                                        </div>

                                        <span class="badge bg-light text-body fw-medium px-3 py-2">
                                            {{ $program['label'] }}
                                        </span>
                                    </div>

                                    <h3 class="fs-5 fw-semibold mb-2">
                                        {{ $program['title'] }}
                                    </h3>

                                    <p class="text-muted lh-lg mb-4">
                                        {{ $program['description'] }}
                                    </p>

                                    <div class="mt-auto">
                                        <a href="{{ $program['target'] }}"
                                            class="btn btn-primary fw-medium rounded-3 w-100">
                                            {{ $program['button'] }}
                                            <i class="ri-arrow-right-line align-middle ms-1"></i>
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </section>

        <section class="py-5" id="jadwal">
            <div class="container">

                {{-- Section Heading --}}
                <div class="text-center mx-auto mb-5" style="max-width: 720px;">
                    <span class="d-inline-block text-primary text-uppercase fw-semibold small mb-2">
                        Jadwal PPDB
                    </span>

                    <h2 class="display-6 fw-bold mb-3">
                        Jadwal PPDB KB 2027/2028
                    </h2>

                    <p class="text-muted mb-0 lh-lg">
                        Ayah dan Bunda, yuk catat tahapan pendaftaran KB
                        agar persiapan si kecil dapat dilakukan dengan nyaman
                        dan tanpa terburu-buru.
                    </p>
                </div>

                {{-- Timeline Jadwal --}}
                <div class="row justify-content-center">
                    <div class="col-lg-8">

                        <div class="ppdb-schedule">

                            @php
                                $schedule = [
                                    [
                                        'date' => '01',
                                        'month' => 'Okt',
                                        'title' => 'Pendaftaran Dibuka',
                                        'desc' => 'Ayah dan Bunda dapat mulai mendaftarkan si kecil melalui formulir online.',
                                    ],
                                    [
                                        'date' => '30',
                                        'month' => 'Nov',
                                        'title' => 'Batas Akhir Pendaftaran',
                                        'desc' => 'Lengkapi data dan kirim formulir sebelum masa pendaftaran berakhir.',
                                    ],
                                    [
                                        'date' => '05',
                                        'month' => 'Des',
                                        'title' => 'Pemeriksaan Berkas',
                                        'desc' => 'Tim PPDB memeriksa data dan dokumen pendaftaran calon peserta didik.',
                                    ],
                                    [
                                        'date' => '10',
                                        'month' => 'Des',
                                        'title' => 'Pengumuman Hasil',
                                        'desc' => 'Ayah dan Bunda dapat memeriksa informasi hasil penerimaan melalui sistem PPDB.',
                                    ],
                                    [
                                        'date' => '11–15',
                                        'month' => 'Des',
                                        'title' => 'Daftar Ulang',
                                        'desc' => 'Orang tua calon peserta didik yang diterima menyelesaikan proses daftar ulang.',
                                    ],
                                ];
                            @endphp

                            @foreach ($schedule as $item)
                                <div class="ppdb-timeline-item">

                                    <div class="date avatar-title bg-primary-subtle text-primary">
                                        {{ $item['date'] }}
                                    </div>

                                    <div class="content card border-0 shadow-sm rounded-4">
                                        <div class="card-body p-3">
                                            <strong class="d-block mb-1">
                                                {{ $item['title'] }}
                                            </strong>

                                            <span class="text-muted">
                                                {{ $item['month'] }} 2026 · {{ $item['desc'] }}
                                            </span>
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

                {{-- Heading --}}
                <div class="text-center mx-auto mb-5" style="max-width: 760px;">
                    <span class="d-inline-block text-primary text-uppercase fw-semibold small mb-2">
                        Persiapan Pendaftaran
                    </span>

                    <h2 class="display-6 fw-bold mb-3">
                        Yuk, Siapkan Kebutuhan Si Kecil
                    </h2>

                    <p class="text-muted mb-0 lh-lg">
                        Sebelum mendaftar, Ayah dan Bunda dapat menyiapkan
                        informasi serta dokumen berikut agar proses pendaftaran
                        berjalan lebih mudah.
                    </p>
                </div>

                <div class="row g-4">

                    {{-- Persyaratan Umum --}}
                    <div class="col-lg-6">
                        <div class="card h-100 border shadow-sm rounded-4 mb-0">
                            <div class="card-body p-4 p-lg-5">

                                <div class="d-flex align-items-center gap-3 mb-4">
                                    <div class="avatar-md bg-primary-subtle text-primary rounded-3
                                                d-flex align-items-center justify-content-center fs-4">
                                        <i class="ri-user-heart-line"></i>
                                    </div>

                                    <div>
                                        <h3 class="fs-5 fw-semibold mb-1">
                                            Sebelum Mendaftar
                                        </h3>
                                        <p class="text-muted mb-0 small">
                                            Hal yang perlu diperhatikan
                                        </p>
                                    </div>
                                </div>

                                <ul class="list-unstyled mb-0">
                                    <li class="d-flex align-items-start gap-3 mb-3">
                                        <i class="ri-checkbox-circle-line text-primary fs-5"></i>
                                        <span>
                                            Pastikan usia si kecil sesuai ketentuan penerimaan KB.
                                        </span>
                                    </li>

                                    <li class="d-flex align-items-start gap-3 mb-3">
                                        <i class="ri-checkbox-circle-line text-primary fs-5"></i>
                                        <span>
                                            Isi formulir pendaftaran dengan data yang benar.
                                        </span>
                                    </li>

                                    <li class="d-flex align-items-start gap-3 mb-3">
                                        <i class="ri-checkbox-circle-line text-primary fs-5"></i>
                                        <span>
                                            Siapkan informasi anak, orang tua atau wali, dan alamat tempat tinggal.
                                        </span>
                                    </li>

                                    <li class="d-flex align-items-start gap-3">
                                        <i class="ri-checkbox-circle-line text-primary fs-5"></i>
                                        <span>
                                            Periksa kembali data sebelum mengirim formulir.
                                        </span>
                                    </li>
                                </ul>

                            </div>
                        </div>
                    </div>

                    {{-- Dokumen --}}
                    <div class="col-lg-6">
                        <div class="card h-100 border shadow-sm rounded-4 mb-0">
                            <div class="card-body p-4 p-lg-5">

                                <div class="d-flex align-items-center gap-3 mb-4">
                                    <div class="avatar-md bg-primary-subtle text-primary rounded-3
                                                d-flex align-items-center justify-content-center fs-4">
                                        <i class="ri-folder-user-line"></i>
                                    </div>

                                    <div>
                                        <h3 class="fs-5 fw-semibold mb-1">
                                            Dokumen Pendaftaran
                                        </h3>
                                        <p class="text-muted mb-0 small">
                                            Berkas yang perlu disiapkan
                                        </p>
                                    </div>
                                </div>

                                <ul class="list-unstyled mb-0">
                                    <li class="d-flex align-items-start gap-3 mb-3">
                                        <i class="ri-checkbox-circle-line text-primary fs-5"></i>
                                        <span>
                                            Kartu Keluarga (KK).
                                        </span>
                                    </li>

                                    <li class="d-flex align-items-start gap-3 mb-3">
                                        <i class="ri-checkbox-circle-line text-primary fs-5"></i>
                                        <span>
                                            Akta Kelahiran anak.
                                        </span>
                                    </li>

                                    <li class="d-flex align-items-start gap-3 mb-3">
                                        <i class="ri-checkbox-circle-line text-primary fs-5"></i>
                                        <span>
                                            Pas foto calon peserta didik.
                                        </span>
                                    </li>

                                    <li class="d-flex align-items-start gap-3 mb-3">
                                        <i class="ri-checkbox-circle-line text-primary fs-5"></i>
                                        <span>
                                            Informasi identitas dan kontak orang tua atau wali.
                                        </span>
                                    </li>

                                    <li class="d-flex align-items-start gap-3">
                                        <i class="ri-checkbox-circle-line text-primary fs-5"></i>
                                        <span>
                                            Dokumen tambahan jika diminta oleh pihak sekolah.
                                        </span>
                                    </li>
                                </ul>

                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </section>


        <section class="py-5" id="cara-daftar">
            <div class="container">

                <div class="text-center mx-auto mb-5" style="max-width: 760px;">
                    <span class="d-inline-block text-primary text-uppercase fw-semibold small mb-2">
                        Cara Pendaftaran
                    </span>
                    <h2 class="display-6 fw-bold mb-3">
                        Cara Mendaftar
                    </h2>
                    <p class="text-muted mb-0 lh-lg">
                        Ikuti langkah berikut untuk mendaftarkan putra-putri Anda.
                    </p>
                </div>

                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-5 g-4">
                    @php
                        $steps = [
                            [
                                'number' => '01',
                                'title' => 'Buat Akun',
                                'text' => 'Daftar akun orang tua melalui portal PPDB.',
                            ],
                            [
                                'number' => '02',
                                'title' => 'Isi Data',
                                'text' => 'Lengkapi data anak dan orang tua.',
                            ],
                            [
                                'number' => '03',
                                'title' => 'Upload Berkas',
                                'text' => 'Unggah dokumen yang diperlukan.',
                            ],
                            [
                                'number' => '04',
                                'title' => 'Kirim Formulir',
                                'text' => 'Periksa kembali data, lalu kirim pendaftaran.',
                            ],
                            [
                                'number' => '05',
                                'title' => 'Cek Status',
                                'text' => 'Pantau status pendaftaran melalui akun Anda.',
                            ],
                        ];
                    @endphp

                    @foreach ($steps as $step)
                        <div class="col">
                            <div class="card h-100 border shadow-sm rounded-4">
                                <div class="card-body p-4">
                                    <span class="ppdb-step-number">
                                        {{ $step['number'] }}
                                    </span>

                                    <h3 class="fs-5 fw-semibold mb-2">
                                        {{ $step['title'] }}
                                    </h3>

                                    <p class="text-muted lh-lg mb-0">
                                        {{ $step['text'] }}
                                    </p>
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

                        {{-- Informasi --}}
                        <div class="col-lg-6 ppdb-status-copy">
                            <span class="ppdb-status-kicker">
                                <i class="ri-search-eye-line"></i>
                                Cek Pendaftaran
                            </span>

                            <h3 class="fs-2 fw-bold text-white">
                                Cek Status Pendaftaran Buah Hati
                            </h3>

                            <p class="text-white-50">
                                Masukkan nomor pendaftaran untuk melihat status
                                pendaftaran dan informasi hasil penerimaan.
                            </p>

                            <div class="ppdb-status-points d-flex flex-wrap gap-3 text-white-50">
                                <span class="d-inline-flex align-items-center gap-2">
                                    <i class="ri-checkbox-circle-line text-success"></i>
                                    Status pendaftaran
                                </span>

                                <span class="d-inline-flex align-items-center gap-2">
                                    <i class="ri-checkbox-circle-line text-success"></i>
                                    Informasi hasil
                                </span>
                            </div>
                        </div>

                        {{-- Form Cek Status --}}
                        <div class="col-lg-6">
                            <div class="card border-0 shadow rounded-4 p-4 p-lg-5 bg-white text-body ppdb-status-lookup">

                                <h4 class="fs-5 fw-semibold mb-4">
                                    Cek Status PPDB
                                </h4>
                                <form>
                                    <label for="ppdb-registration-number"
                                        class="form-label fw-semibold">
                                        Nomor Pendaftaran
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        id="ppdb-registration-number"
                                        name="registration_number"
                                        placeholder="Masukkan nomor pendaftaran"
                                        autocomplete="off"
                                        aria-describedby="ppdb-registration-hint">

                                    <small class="form-text" id="ppdb-registration-hint">
                                        Contoh: PPDB-2027-0001
                                    </small>

                                    <button type="button"
                                            class="btn btn-primary rounded-pill w-100 mt-3">
                                        Cek Status
                                        <i class="ri-arrow-right-line ms-1"></i>
                                    </button>
                                </form>

                                <div class="ppdb-status-divider d-flex align-items-center gap-3 my-4 small text-muted">
                                    <span class="border-top flex-grow-1"></span>
                                    atau
                                    <span class="border-top flex-grow-1"></span>
                                </div>

                                <div class="ppdb-status-login d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
                                    <div>
                                        <strong class="d-block mb-1">
                                            Sudah punya akun?
                                        </strong>
                                        <span class="small text-muted">
                                            Kelola pendaftaran melalui akun Anda.
                                        </span>
                                    </div>

                                    <a href="{{ route('ppdb.login', [
                                        'jenjang' => $jenjang->slug,
                                    ]) }}"
                                        class="btn btn-outline-primary rounded-pill flex-shrink-0">
                                        Login
                                        <i class="ri-login-box-line ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        
        <section class="py-5" id="faq">
            <div class="container">

                {{-- Heading --}}
                <div class="text-center mx-auto mb-5" style="max-width: 760px;">
                    <span class="d-inline-block text-primary text-uppercase fw-semibold small mb-2">
                        FAQ
                    </span>

                    <h2 class="display-6 fw-bold mb-3">
                        Ada yang Ingin Ditanyakan?
                    </h2>

                    <p class="text-muted mb-0 lh-lg">
                        Temukan jawaban seputar pendaftaran KB untuk membantu
                        Ayah dan Bunda mempersiapkan langkah pertama si kecil.
                    </p>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-9">

                        <div class="accordion ppdb-faq" id="ppdbFaq">

                            {{-- FAQ 1 --}}
                            <div class="accordion-item border-0 shadow-sm rounded-4 overflow-hidden mb-3">
                                <h2 class="accordion-header" id="faqOne">
                                    <button class="accordion-button fw-semibold"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapseOne"
                                        aria-expanded="true"
                                        aria-controls="collapseOne">
                                        Berapa usia anak yang bisa mendaftar KB?
                                    </button>
                                </h2>

                                <div id="collapseOne"
                                    class="accordion-collapse collapse show"
                                    aria-labelledby="faqOne"
                                    data-bs-parent="#ppdbFaq">
                                    <div class="accordion-body text-muted lh-lg">
                                        Pendaftaran ditujukan bagi anak usia dini yang memenuhi
                                        ketentuan usia dari sekolah. Silakan konfirmasi kepada
                                        pihak {{ $jenjang->nama_jenjang }} untuk memastikan
                                        kesesuaian usia si kecil.
                                    </div>
                                </div>
                            </div>

                            {{-- FAQ 2 --}}
                            <div class="accordion-item border-0 shadow-sm rounded-4 overflow-hidden mb-3">
                                <h2 class="accordion-header" id="faqTwo">
                                    <button class="accordion-button fw-semibold collapsed"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapseTwo"
                                        aria-expanded="false"
                                        aria-controls="collapseTwo">
                                        Bagaimana cara mendaftarkan anak?
                                    </button>
                                </h2>

                                <div id="collapseTwo"
                                    class="accordion-collapse collapse"
                                    aria-labelledby="faqTwo"
                                    data-bs-parent="#ppdbFaq">
                                    <div class="accordion-body text-muted lh-lg">
                                        Ayah dan Bunda dapat membuat akun, mengisi data anak
                                        dan orang tua, mengunggah dokumen yang diperlukan,
                                        lalu mengirim formulir melalui halaman PPDB.
                                    </div>
                                </div>
                            </div>

                            {{-- FAQ 3 --}}
                            <div class="accordion-item border-0 shadow-sm rounded-4 overflow-hidden mb-3">
                                <h2 class="accordion-header" id="faqThree">
                                    <button class="accordion-button fw-semibold collapsed"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapseThree"
                                        aria-expanded="false"
                                        aria-controls="collapseThree">
                                        Dokumen apa saja yang perlu disiapkan?
                                    </button>
                                </h2>

                                <div id="collapseThree"
                                    class="accordion-collapse collapse"
                                    aria-labelledby="faqThree"
                                    data-bs-parent="#ppdbFaq">
                                    <div class="accordion-body text-muted lh-lg">
                                        Siapkan Kartu Keluarga, akta kelahiran, dan pas foto
                                        anak. Informasi orang tua atau wali juga perlu dilengkapi.
                                        Dokumen tambahan dapat diminta jika diperlukan.
                                    </div>
                                </div>
                            </div>

                            {{-- FAQ 4 --}}
                            <div class="accordion-item border-0 shadow-sm rounded-4 overflow-hidden mb-3">
                                <h2 class="accordion-header" id="faqFour">
                                    <button class="accordion-button fw-semibold collapsed"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapseFour"
                                        aria-expanded="false"
                                        aria-controls="collapseFour">
                                        Bagaimana jika ada data yang perlu diperbaiki?
                                    </button>
                                </h2>

                                <div id="collapseFour"
                                    class="accordion-collapse collapse"
                                    aria-labelledby="faqFour"
                                    data-bs-parent="#ppdbFaq">
                                    <div class="accordion-body text-muted lh-lg">
                                        Jika petugas meminta perbaikan, Ayah dan Bunda dapat
                                        melihat catatan pada sistem PPDB, memperbaiki data
                                        yang diminta, lalu mengirimkan kembali pendaftaran.
                                    </div>
                                </div>
                            </div>

                            {{-- FAQ 5 --}}
                            <div class="accordion-item border-0 shadow-sm rounded-4 overflow-hidden mb-3">
                                <h2 class="accordion-header" id="faqFive">
                                    <button class="accordion-button fw-semibold collapsed"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapseFive"
                                        aria-expanded="false"
                                        aria-controls="collapseFive">
                                        Bagaimana cara mengetahui status pendaftaran?
                                    </button>
                                </h2>

                                <div id="collapseFive"
                                    class="accordion-collapse collapse"
                                    aria-labelledby="faqFive"
                                    data-bs-parent="#ppdbFaq">
                                    <div class="accordion-body text-muted lh-lg">
                                        Gunakan nomor pendaftaran pada bagian Cek Status
                                        di halaman ini untuk mengetahui perkembangan
                                        pendaftaran si kecil.
                                    </div>
                                </div>
                            </div>

                            {{-- FAQ 6 --}}
                            <div class="accordion-item border-0 shadow-sm rounded-4 overflow-hidden mb-3">
                                <h2 class="accordion-header" id="faqSix">
                                    <button class="accordion-button fw-semibold collapsed"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapseSix"
                                        aria-expanded="false"
                                        aria-controls="collapseSix">
                                        Bagaimana jika saya kesulitan saat mendaftar?
                                    </button>
                                </h2>

                                <div id="collapseSix"
                                    class="accordion-collapse collapse"
                                    aria-labelledby="faqSix"
                                    data-bs-parent="#ppdbFaq">
                                    <div class="accordion-body text-muted lh-lg">
                                        Ayah dan Bunda dapat menghubungi petugas PPDB
                                        {{ $jenjang->nama_jenjang }} untuk meminta bantuan
                                        selama proses pendaftaran.
                                    </div>
                                </div>
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

                        <span class="ppdb-badge d-inline-flex mb-3">
                            PPDB KB Tahun Ajaran 2027/2028
                        </span>

                        <h2 class="text-white">
                            Awali Langkah Kecilnya Bersama Kami
                        </h2>

                        <p>
                            Yuk, temani si kecil mengenal dunia, belajar mandiri,
                            dan membiasakan akhlak baik melalui kegiatan bermain
                            yang menyenangkan bersama {{ $jenjang->nama_jenjang }}.
                        </p>

                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('ppdb.daftar', [
                                    'jenjang' => $jenjang->slug,
                                ]) }}"
                                class="btn btn-primary rounded-pill">
                                Daftarkan Si Kecil
                                <i class="ri-arrow-right-line ms-1"></i>
                            </a>

                            <a href="#persyaratan"
                                class="btn ppdb-secondary-cta rounded-pill">
                                Lihat Persyaratan
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </main>

    
    
    <footer class="custom-footer bg-dark position-relative">
        <div class="container">
            {{-- Main Footer --}}
            <div class="row py-5 gy-4">

                {{-- Informasi Sekolah --}}
                <div class="col-lg-5 col-md-6">
                    <div>
                        <div class="mb-4">
                            <img src="{{ asset('assets/logo/hero.png') }}"
                                alt="Logo {{ $jenjang->nama_jenjang }}"
                                height="50"
                                class="rounded-2">
                        </div>

                        <p class="text-white fs-15 fw-medium mb-2">
                            {{ $jenjang->nama_jenjang }}
                        </p>

                        <p class="text-white-50 ff-secondary mb-4"
                            style="max-width: 420px;">
                            Informasi Penerimaan Peserta Didik Baru (PPDB)
                            KB tahun ajaran 2027/2028. Kenali kegiatan,
                            persyaratan, jadwal, dan langkah pendaftaran
                            untuk menemani awal tumbuh kembang si kecil.
                        </p>

                        {{-- Alamat Sekolah --}}
                        <div class="d-flex align-items-start gap-2 text-white-50">
                            <i class="ri-map-pin-line text-white fs-18 mt-1"></i>
                            <span class="ff-secondary fs-13">
                                {{ $jenjang->alamat }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Navigasi Informasi --}}
                <div class="col-lg-3 col-md-3 col-6">
                    <div>
                        <h5 class="text-white mb-4">
                            Informasi
                        </h5>

                        <ul class="list-unstyled ff-secondary footer-list fs-14 mb-0">
                            <li class="mb-2">
                                <a href="#beranda">Beranda</a>
                            </li>
                            <li class="mb-2">
                                <a href="#informasi">Tentang KB Kami</a>
                            </li>
                            <li class="mb-2">
                                <a href="#program">Kegiatan Si Kecil</a>
                            </li>
                            <li class="mb-2">
                                <a href="#jadwal">Jadwal PPDB</a>
                            </li>
                            <li class="mb-2">
                                <a href="#persyaratan">Persyaratan</a>
                            </li>
                            <li>
                                <a href="#faq">Pertanyaan Umum</a>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- CTA PPDB --}}
                <div class="col-lg-4 col-md-3">
                    <div>
                        <h5 class="text-white mb-3">
                            Pendaftaran KB
                        </h5>

                        <p class="text-white-50 ff-secondary fs-14 mb-4">
                            Yuk, persiapkan data dan dokumen si kecil.
                            Lengkapi pendaftaran secara online agar
                            langkah awalnya bersama kami semakin mudah.
                        </p>

                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('ppdb.daftar', [
                                    'jenjang' => $jenjang->slug,
                                ]) }}"
                                class="btn btn-primary rounded-pill px-4">
                                <i class="ri-pencil-line align-middle me-1"></i>
                                Daftarkan Si Kecil
                            </a>

                            <a href="#persyaratan"
                                class="btn btn-outline-light rounded-pill px-4">
                                <i class="ri-file-list-3-line align-middle me-1"></i>
                                Persyaratan
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Divider --}}
            <div class="border-top border-secondary opacity-25"></div>

            {{-- Bottom Footer --}}
            <div class="row align-items-center py-4 gy-3">

                <div class="col-sm-6">
                    <p class="copy-rights mb-0 text-white-50 ff-secondary fs-13">
                        &copy; {{ date('Y') }}
                        {{ $jenjang->nama_jenjang }}. Hak cipta dilindungi.
                    </p>
                </div>

                <div class="col-sm-6">
                    <div class="text-sm-end">
                        <a href="{{ route('ppdb.daftar', [
                                'jenjang' => $jenjang->slug,
                            ]) }}"
                            class="link-light">
                            Daftar PPDB
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </footer>



    <a
        class="btn btn-primary rounded-pill shadow-lg d-inline-flex align-items-center gap-2 px-4 py-3 fw-semibold position-fixed ppdb-help"
        href="#faq"
        aria-label="Lihat bantuan dan pertanyaan umum tentang PPDB"
        title="Lihat bantuan PPDB">
        <i class="ri-question-line" aria-hidden="true"></i>
        <span>Bantuan PPDB</span>
    </a>
</div>

    <script src="{{ asset('assets') }}/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
