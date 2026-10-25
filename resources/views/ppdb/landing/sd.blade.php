<!doctype html>
<html lang="id" data-layout="vertical" data-topbar="light" data-sidebar="light" data-bs-theme="light" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">
    <head>
        <meta charset="utf-8" />
        <title>PPDB {{ $jenjang->nama_jenjang }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Informasi Penerimaan Peserta Didik Baru Tahun Ajaran 2027/2028 di {{ $jenjang->nama_jenjang }}.">
        <link rel="shortcut icon" href="{{asset('assets')}}/logo/yayasan.webp">
        <script src="{{ asset('assets') }}/js/layout.js"></script>
        <link href="{{ asset('assets') }}/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets') }}/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets') }}/css/app.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets') }}/css/custom.min.css" rel="stylesheet" type="text/css" />
        
        @livewireStyles
        
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
                background: #0f172a;
                min-height: 640px;
                display: flex;
                align-items: center;
                isolation: isolate;
            }

            .ppdb-page .ppdb-hero::before {
                content: "";
                position: absolute;
                inset: 0;
                background-image: url('{{ asset('assets/logo/sekolah.jpg') }}');
                background-size: cover;
                background-position: center;
                transform: scale(1.05);
                z-index: -2;
            }

            /* Overlay untuk meningkatkan keterbacaan teks */
            .ppdb-page .ppdb-hero::after {
                content: "";
                position: absolute;
                inset: 0;
                background: linear-gradient(
                    90deg,
                    rgba(15, 23, 42, 0.92) 0%,
                    rgba(15, 23, 42, 0.78) 45%,
                    rgba(15, 23, 42, 0.42) 100%
                );
                z-index: -1;
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
                isolation: isolate;
                background: #0f172a;
                min-height: 280px;
                display: flex;
                align-items: center;
            }

            /* Foto sekolah + overlay gradien */
            .ppdb-page .ppdb-cta::before {
                content: "";
                position: absolute;
                inset: 0;
                background:
                    linear-gradient(
                        120deg,
                        rgba(15, 23, 42, 0.92) 0%,
                        rgba(15, 23, 42, 0.78) 48%,
                        rgba(15, 23, 42, 0.48) 100%
                    ),
                    url('{{ asset('assets/logo/sekolah.jpg') }}');

                background-size: cover;
                background-position: center;
                transform: scale(1.04);
                z-index: -1;
            }

            /* Konten CTA */
            .ppdb-page .ppdb-cta-content {
                position: relative;
                z-index: 1;
                max-width: 700px;
                color: #fff;
            }

            .ppdb-page .ppdb-cta-content p {
                color: rgba(255, 255, 255, 0.78);
                line-height: 1.8;
                max-width: 620px;
            }

            /* Tampilan mobile */
            @media (max-width: 575.98px) {
                .ppdb-page .ppdb-cta {
                    min-height: 300px;
                    padding: 1.5rem !important;
                }

                .ppdb-page .ppdb-cta::before {
                    background:
                        linear-gradient(
                            120deg,
                            rgba(15, 23, 42, 0.93),
                            rgba(15, 23, 42, 0.76)
                        ),
                        url('{{ asset('assets/logo/sekolah.jpg') }}');

                    background-size: cover;
                    background-position: center;
                }

                .ppdb-page .ppdb-cta-content h2 {
                    font-size: 1.65rem;
                    line-height: 1.35;
                }
            }
            ```

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
                        href="{{ route('ppdb.sd') }}">
                        <img class="avatar-sm rounded-3 object-fit-cover"
                            src="{{ asset('assets/logo/logo.jpg') }}"
                            alt="Logo sekolah">

                        <div class="lh-sm">
                            <span>{{ $jenjang->nama_jenjang }}</span>
                            <small class="d-block text-muted fw-semibold text-uppercase">
                                SPMB 2027/2028
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
                                <a class="nav-link fw-semibold" href="#profil">
                                    Profil Sekolah
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link fw-semibold" href="#program">
                                    Program Unggulan
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link fw-semibold" href="#biaya">
                                    Biaya SPMB
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link fw-semibold" href="#jadwal">
                                    Jadwal & Alur
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link fw-semibold" href="#persyaratan">
                                    Persyaratan
                                </a>
                            </li>

                            {{-- Action --}}
                            @auth
                                @if (Auth::user()->peran == 'ADMIN' || Auth::user()->peran == 'SUPERADMIN')
                                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                                        <a href="{{ route('ppdb.admin.dashboard') }}"
                                            class="btn btn-primary rounded-pill px-3">
                                            <i class="ri-home-line me-1"></i>
                                            Dashboard Admin
                                        </a>
                                    </li>
                                @else
                                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                                        <a href="{{ route('ppdb.dashboard', [
                                            'jenjang' => $jenjang->slug,
                                        ]) }}"
                                            class="btn btn-primary rounded-pill px-3">
                                            <i class="ri-home-line me-1"></i>
                                            Dashboard
                                        </a>
                                    </li>
                                @endif
                            @else
                                <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                                    <a href="{{ route('ppdb.login', [
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
                            <div class="col-lg-7">
                                <span class="ppdb-badge">
                                    <i class="ri-calendar-check-line"></i>
                                    SPMB Tahun Ajaran 2027/2028
                                </span>

                                <h1>
                                    Mewujudkan Generasi Islami dan Berwawasan Global
                                </h1>

                                <p>
                                    SD Islam Al Hidayah mendampingi anak belajar, mengenal nilai-nilai
                                    Islam, dan mengembangkan potensinya. Bersama keluarga, kami
                                    menumbuhkan karakter baik sebagai bekal masa depan.
                                </p>

                                <div class="ppdb-hero-cta d-grid d-sm-flex gap-3 mt-4">
                                    @auth
                                        @if (Auth::user()->peran == 'ADMIN' || Auth::user()->peran == 'SUPERADMIN')
                                            <a href="{{ route('ppdb.admin.dashboard') }}"
                                                class="btn btn-primary rounded-pill">
                                                <i class="ri-home-line me-1"></i>
                                                Dashboard Admin
                                            </a>
                                        @else
                                            <a href="{{ route('ppdb.dashboard', [
                                                'jenjang' => $jenjang->slug,
                                            ]) }}"
                                                class="btn btn-primary rounded-pill">
                                                <i class="ri-home-line me-1"></i>
                                                Dashboard Pendaftaran
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('ppdb.daftar', [
                                            'jenjang' => $jenjang->slug,
                                        ]) }}"
                                            class="btn btn-primary rounded-pill">
                                            <i class="ri-user-add-line me-1"></i>
                                            Daftar Sekarang
                                        </a>
                                    @endauth

                                    <a href="#jadwal"
                                        class="btn ppdb-secondary-cta rounded-pill">
                                        <i class="ri-calendar-line me-1"></i>
                                        Lihat Alur Pendaftaran
                                    </a>
                                </div>

                                <div class="ppdb-status d-flex flex-column flex-sm-row flex-wrap gap-3 align-items-sm-center mt-4 text-white">
                                    <div class="ppdb-status-pill d-inline-flex align-items-center gap-2 rounded-pill px-3 py-2 fw-semibold">
                                        <span class="dot"></span>
                                        Pendaftaran Mulai November 2026
                                    </div>

                                    <div class="text-white-50">
                                        Tahun Ajaran 2027/2028
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-5">
                                <div class="ppdb-hero-card rounded-4 shadow-sm">
                                    <img src="{{ asset('assets/logo/hero.jpeg') }}"
                                        alt="Kegiatan peserta didik SD Islam Al Hidayah">
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
                                                'label' => 'Pendaftaran Mulai',
                                                'value' => 'November 2026',
                                            ],
                                            [
                                                'icon' => 'ri-psychotherapy-line',
                                                'label' => 'Skrining Psikologi',
                                                'value' => 'April 2027',
                                            ],
                                            [
                                                'icon' => 'ri-user-follow-line',
                                                'label' => 'Penutupan',
                                                'value' => 'Saat kuota terpenuhi',
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

                <section class="py-5" id="profil">
                    <div class="container">

                        {{-- Header Profil --}}
                        <div class="text-center mx-auto mb-5" style="max-width: 760px;">
                            <span class="d-inline-block text-primary text-uppercase fw-semibold small mb-2">
                                Profil Sekolah
                            </span>

                            <h2 class="display-6 fw-bold mb-3">
                                Mengenal SD Islam Al Hidayah
                            </h2>

                            <p class="text-muted lh-lg mb-0">
                                SD Islam Al Hidayah mendampingi anak untuk mengenal nilai-nilai Islam, membangun sikap santun, dan mengembangkan potensinya. Melalui kerja sama antara sekolah dan keluarga, kami berupaya menghadirkan pengalaman belajar yang bermakna bagi setiap anak.
                            </p>
                        </div>

                        {{-- Visi Sekolah --}}
                        <div class="card border-0 rounded-4 shadow-sm mb-5 overflow-hidden">
                            <div class="card-body p-4 p-lg-5">
                                <div class="row align-items-center g-4">
                                    <div class="col-md-auto text-center">
                                        <div class="avatar-lg mx-auto">
                                            <div class="avatar-title bg-primary-subtle text-primary rounded-4 fs-2">
                                                <i class="ri-eye-line"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col">
                                        <span class="text-primary text-uppercase fw-semibold small">
                                            Visi Sekolah
                                        </span>

                                        <h3 class="fs-4 fw-bold mt-2 mb-0 lh-base">
                                            <i class="ri-double-quotes-l text-primary fs-3 me-1" aria-hidden="true"></i>
                                            Mewujudkan generasi Islami dan berwawasan global,
                                            melalui proses pendidikan yang menjunjung nilai-nilai lokal.
                                            <i class="ri-double-quotes-r text-primary fs-3 ms-1" aria-hidden="true"></i>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Misi Sekolah --}}
                        <div class="mb-4">
                            <div class="text-center mx-auto" style="max-width: 680px;">
                                <span class="d-inline-block text-primary text-uppercase fw-semibold small mb-2">
                                    Misi Sekolah
                                </span>

                                <h3 class="fw-bold mb-2">
                                    Nilai yang Kami Tanamkan
                                </h3>

                                <p class="text-muted mb-0">
                                    Empat komitmen dalam mendampingi pendidikan dan perkembangan peserta didik.
                                </p>
                            </div>
                        </div>

                        @php
                            $missions = [
                                [
                                    'icon' => 'ri-heart-3-line',
                                    'title' => 'Karakter Islami',
                                    'text' => 'Menanamkan karakter Islami pada setiap peserta didik.',
                                ],
                                [
                                    'icon' => 'ri-hand-heart-line',
                                    'title' => 'Sikap Santun',
                                    'text' => 'Menanamkan sikap santun dalam kehidupan sehari-hari.',
                                ],
                                [
                                    'icon' => 'ri-computer-line',
                                    'title' => 'Pembelajaran Adaptif',
                                    'text' => 'Melaksanakan pembelajaran yang bersinergi dengan perkembangan teknologi.',
                                ],
                                [
                                    'icon' => 'ri-team-line',
                                    'title' => 'Kemitraan Keluarga',
                                    'text' => 'Membersamai keluarga dalam mendidik anak.',
                                ],
                            ];
                        @endphp

                        <div class="row g-3 g-lg-4">
                            @foreach ($missions as $mission)
                                <div class="col-lg-3 col-md-6 col-12">
                                    <div class="card h-100 border rounded-4 shadow-none mb-0">
                                        <div class="card-body p-4">
                                            <div class="avatar-md mb-3">
                                                <div class="avatar-title bg-primary-subtle text-primary rounded-3 fs-4">
                                                    <i class="{{ $mission['icon'] }}"></i>
                                                </div>
                                            </div>

                                            <h4 class="fs-5 fw-semibold mb-2">
                                                {{ $mission['title'] }}
                                            </h4>

                                            <p class="text-muted lh-lg mb-0">
                                                {{ $mission['text'] }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </section>

                <section class="py-5 bg-white" id="program">
                    <div class="container">

                        {{-- Header Program Unggulan --}}
                        <div class="text-center mx-auto mb-5" style="max-width: 720px;">
                            <span class="d-inline-block text-primary text-uppercase fw-semibold small mb-2">
                                Program Unggulan
                            </span>

                            <h2 class="display-6 fw-bold mb-3">
                                Mendampingi Anak Tumbuh dan Berkembang
                            </h2>

                            <p class="text-muted mb-0 lh-lg">
                                Pembelajaran yang memadukan nilai-nilai Islami,
                                pengembangan pengetahuan, dan keterampilan untuk
                                mendampingi perjalanan belajar setiap peserta didik.
                            </p>
                        </div>

                        @php
                            $programs = [
                                [
                                    'icon' => 'ri-book-open-line',
                                    'title' => 'Metode Iqra',
                                    'description' => 'Mendampingi peserta didik dalam belajar membaca Al-Qur’an melalui metode Iqra.',
                                ],
                                [
                                    'icon' => 'ri-book-line',
                                    'title' => 'Tahfidz Al-Qur’an',
                                    'description' => 'Program pembelajaran untuk mengenal dan menghafal ayat-ayat Al-Qur’an.',
                                ],
                                [
                                    'icon' => 'ri-computer-line',
                                    'title' => 'Teknologi Informasi',
                                    'description' => 'Mengenalkan pengetahuan dan keterampilan teknologi informasi dan komunikasi.',
                                ],
                                [
                                    'icon' => 'ri-global-line',
                                    'title' => 'Bahasa Inggris',
                                    'description' => 'Mendukung pengenalan dan pengembangan kemampuan berbahasa Inggris.',
                                ],
                                [
                                    'icon' => 'ri-heart-3-line',
                                    'title' => 'Pembiasaan Islami',
                                    'description' => 'Menanamkan nilai-nilai Islam melalui pembiasaan dalam kehidupan sehari-hari.',
                                ],
                            ];
                        @endphp

                        {{-- Daftar Program --}}
                        <div class="row g-4">
                            @foreach ($programs as $program)
                                <div class="col-lg-4 col-md-6 col-12">
                                    <div class="card h-100 border rounded-4 shadow-none mb-0">
                                        <div class="card-body p-4">

                                            <div class="avatar-md mb-4">
                                                <div class="avatar-title bg-primary-subtle text-primary rounded-3 fs-4">
                                                    <i class="{{ $program['icon'] }}"></i>
                                                </div>
                                            </div>

                                            <h3 class="fs-5 fw-semibold mb-2">
                                                {{ $program['title'] }}
                                            </h3>

                                            <p class="text-muted lh-lg mb-0">
                                                {{ $program['description'] }}
                                            </p>

                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Penutup --}}
                        <div class="text-center mt-4">
                            <p class="text-muted small mb-0">
                                Pendidikan yang berlandaskan nilai Islami dan kesiapan
                                menghadapi perkembangan zaman.
                            </p>
                        </div>

                    </div>
                </section>

                <section class="py-5" id="biaya">
                    <div class="container">

                        {{-- Header --}}
                        <div class="text-center mx-auto mb-5" style="max-width: 720px;">
                            <span class="d-inline-block text-primary text-uppercase fw-semibold small mb-2">
                                Informasi Biaya
                            </span>

                            <h2 class="display-6 fw-bold mb-3">
                                Biaya SPMB 2027/2028
                            </h2>

                            <p class="text-muted lh-lg mb-0">
                                Berikut rincian biaya penerimaan peserta didik baru
                                SD Islam Al Hidayah, mulai dari tahap pendataan
                                hingga biaya pengembangan pendidikan.
                            </p>
                        </div>

                        {{-- Ringkasan Total --}}
                        <div class="row g-4 mb-5">
                            <div class="col-md-6">
                                <div class="card h-100 border rounded-4 shadow-none mb-0">
                                    <div class="card-body p-4 p-lg-5">
                                        <div class="d-flex align-items-center gap-3 mb-4">
                                            <div class="avatar-md flex-shrink-0">
                                                <div class="avatar-title bg-primary-subtle text-primary rounded-3 fs-4">
                                                    <i class="ri-user-line"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <h3 class="fs-5 fw-semibold mb-1">
                                                    Calon Siswa Laki-laki
                                                </h3>
                                                <p class="text-muted small mb-0">
                                                    Total biaya pendidikan
                                                </p>
                                            </div>
                                        </div>

                                        <h4 class="display-6 fw-bold text-primary mb-2">
                                            Rp4.295.000
                                        </h4>

                                        <p class="text-muted small mb-0">
                                            Rincian pembayaran tersedia berdasarkan tahap.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card h-100 border rounded-4 shadow-none mb-0">
                                    <div class="card-body p-4 p-lg-5">
                                        <div class="d-flex align-items-center gap-3 mb-4">
                                            <div class="avatar-md flex-shrink-0">
                                                <div class="avatar-title bg-success-subtle text-success rounded-3 fs-4">
                                                    <i class="ri-user-heart-line"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <h3 class="fs-5 fw-semibold mb-1">
                                                    Calon Siswi Perempuan
                                                </h3>
                                                <p class="text-muted small mb-0">
                                                    Termasuk hijab
                                                </p>
                                            </div>
                                        </div>

                                        <h4 class="display-6 fw-bold text-success mb-2">
                                            Rp4.445.000
                                        </h4>

                                        <p class="text-muted small mb-0">
                                            Rincian pembayaran tersedia berdasarkan tahap.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Rincian Pembayaran --}}
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="avatar-md flex-shrink-0">
                                <div class="avatar-title bg-primary-subtle text-primary rounded-3 fs-4">
                                    <i class="ri-file-list-3-line"></i>
                                </div>
                            </div>

                            <div>
                                <h3 class="fs-4 fw-bold mb-1">
                                    Rincian Pembayaran
                                </h3>
                                <p class="text-muted mb-0">
                                    Biaya dibagi menjadi beberapa tahap pembayaran.
                                </p>
                            </div>
                        </div>

                        @php
                            $tahapanBiaya = [
                                [
                                    'tahap' => 'Tahap Pendataan',
                                    'periode' => 'Saat pendaftaran',
                                    'icon' => 'ri-clipboard-line',
                                    'items' => [
                                        ['nama' => 'Pendataan', 'nominal' => 150000],
                                        ['nama' => 'Skrining psikologi', 'nominal' => 150000],
                                        ['nama' => '3 seragam siap pakai dan 2 kaos olahraga', 'nominal' => 740000],
                                    ],
                                ],
                                [
                                    'tahap' => 'Tahap 1',
                                    'periode' => 'Januari–Februari 2027',
                                    'icon' => 'ri-book-2-line',
                                    'items' => [
                                        ['nama' => 'Buku selama satu tahun', 'nominal' => 640000],
                                    ],
                                ],
                                [
                                    'tahap' => 'Tahap 2',
                                    'periode' => 'Maret–April 2027',
                                    'icon' => 'ri-calendar-check-line',
                                    'items' => [
                                        ['nama' => 'Kegiatan tahunan', 'nominal' => 450000],
                                        ['nama' => 'SPP bulan Juli', 'nominal' => 370000],
                                        ['nama' => 'Perlengkapan tahunan', 'nominal' => 375000],
                                    ],
                                ],
                                [
                                    'tahap' => 'Tahap 3',
                                    'periode' => 'Mei–Juni 2027',
                                    'icon' => 'ri-graduation-cap-line',
                                    'items' => [
                                        ['nama' => 'Alat penunjang pendidikan', 'nominal' => 620000],
                                        ['nama' => 'Pengembangan', 'nominal' => 800000],
                                    ],
                                ],
                            ];
                        @endphp

                        <div class="row g-4">
                            @foreach ($tahapanBiaya as $tahap)
                                <div class="col-lg-6">
                                    <div class="card h-100 border rounded-4 shadow-none mb-0">
                                        <div class="card-header bg-transparent border-bottom p-4">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="avatar-md flex-shrink-0">
                                                    <div class="avatar-title bg-primary-subtle text-primary rounded-3 fs-4">
                                                        <i class="{{ $tahap['icon'] }}"></i>
                                                    </div>
                                                </div>

                                                <div>
                                                    <h4 class="fs-5 fw-semibold mb-1">
                                                        {{ $tahap['tahap'] }}
                                                    </h4>
                                                    <p class="text-muted small mb-0">
                                                        {{ $tahap['periode'] }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card-body p-4">
                                            @foreach ($tahap['items'] as $item)
                                                <div class="d-flex justify-content-between align-items-start gap-3 {{ !$loop->last ? 'mb-3 pb-3 border-bottom' : '' }}">
                                                    <span class="text-muted">
                                                        {{ $item['nama'] }}
                                                    </span>

                                                    <span class="fw-semibold text-nowrap">
                                                        Rp{{ number_format($item['nominal'], 0, ',', '.') }}
                                                    </span>
                                                </div>
                                            @endforeach

                                            <div class="d-flex justify-content-between align-items-center gap-3 mt-4 pt-3 border-top">
                                                <span class="fw-semibold">
                                                    Total tahap
                                                </span>

                                                <span class="fw-bold text-primary text-nowrap">
                                                    Rp{{ number_format(collect($tahap['items'])->sum('nominal'), 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Catatan --}}
                        <div class="alert alert-light border rounded-4 mt-4 mb-0" role="note">
                            <div class="d-flex align-items-start gap-3">
                                <i class="ri-information-line fs-4 text-primary"></i>
                                <div>
                                    <h4 class="fs-6 fw-semibold mb-1">
                                        Informasi Biaya
                                    </h4>
                                    <p class="text-muted small mb-0 lh-lg">
                                        Total biaya calon siswa laki-laki sebesar Rp4.295.000
                                        dan calon siswi perempuan sebesar Rp4.445.000 termasuk hijab.
                                        Silakan menghubungi panitia SPMB untuk konfirmasi
                                        teknis pembayaran.
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>
                </section>

                <section class="py-5" id="jadwal">
                    <div class="container">

                        {{-- Header --}}
                        <div class="text-center mx-auto mb-5" style="max-width: 720px;">
                            <span class="d-inline-block text-primary text-uppercase fw-semibold small mb-2">
                                Jadwal dan Alur
                            </span>

                            <h2 class="display-6 fw-bold mb-3">
                                Alur Pendaftaran SPMB 2027/2028
                            </h2>

                            <p class="text-muted lh-lg mb-0">
                                Ikuti tahapan pendaftaran SD Islam Al Hidayah berikut
                                untuk mempersiapkan kebutuhan calon peserta didik.
                            </p>
                        </div>

                        {{-- Fase Pendaftaran --}}
                        <div class="row g-4 mb-5">

                            <div class="col-lg-6">
                                <div class="card h-100 border rounded-4 shadow-none mb-0">
                                    <div class="card-body p-4 p-lg-5">

                                        <div class="d-flex align-items-start gap-3 mb-4">
                                            <div class="avatar-md flex-shrink-0">
                                                <div class="avatar-title bg-primary-subtle text-primary rounded-3 fs-4">
                                                    <i class="ri-calendar-event-line"></i>
                                                </div>
                                            </div>

                                            <div>
                                                <span class="badge bg-primary-subtle text-primary mb-2">
                                                    November 2026
                                                </span>

                                                <h3 class="fs-4 fw-bold mb-1">
                                                    Tahap Pendaftaran
                                                </h3>

                                                <p class="text-muted small mb-0">
                                                    Pendaftaran dibuka hingga kuota terpenuhi.
                                                </p>
                                            </div>
                                        </div>

                                        <div class="d-flex flex-column gap-3">
                                            @php
                                                $registrationSteps = [
                                                    'Datang ke sekolah',
                                                    'Mengikuti wawancara orang tua',
                                                    'Mengisi formulir pendaftaran',
                                                    'Melengkapi persyaratan dokumen',
                                                    'Membayar biaya pendataan, skrining psikologi, dan seragam',
                                                    'Bergabung dengan grup WhatsApp calon peserta didik SDIA 2027/2028',
                                                ];
                                            @endphp

                                            @foreach ($registrationSteps as $step)
                                                <div class="d-flex align-items-start gap-3">
                                                    <div class="avatar-xs flex-shrink-0">
                                                        <div class="avatar-title bg-light text-primary rounded-circle fw-semibold">
                                                            {{ $loop->iteration }}
                                                        </div>
                                                    </div>

                                                    <p class="text-muted mb-0 lh-lg">
                                                        {{ $step }}
                                                    </p>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="alert alert-light border rounded-3 mt-4 mb-0">
                                            <div class="d-flex align-items-start gap-2">
                                                <i class="ri-information-line text-primary fs-5"></i>
                                                <p class="small text-muted mb-0 lh-lg">
                                                    Pendaftaran ditutup apabila kuota telah terpenuhi.
                                                </p>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            {{-- Fase Skrining dan Pengumuman --}}
                            <div class="col-lg-6">
                                <div class="card h-100 border rounded-4 shadow-none mb-0">
                                    <div class="card-body p-4 p-lg-5">

                                        <div class="d-flex align-items-start gap-3 mb-4">
                                            <div class="avatar-md flex-shrink-0">
                                                <div class="avatar-title bg-success-subtle text-success rounded-3 fs-4">
                                                    <i class="ri-calendar-check-line"></i>
                                                </div>
                                            </div>

                                            <div>
                                                <span class="badge bg-success-subtle text-success mb-2">
                                                    April 2027
                                                </span>

                                                <h3 class="fs-4 fw-bold mb-1">
                                                    Tahap Seleksi
                                                </h3>

                                                <p class="text-muted small mb-0">
                                                    Pelaksanaan skrining dan penyampaian hasil.
                                                </p>
                                            </div>
                                        </div>

                                        <div class="d-flex flex-column gap-4 mt-4">

                                            <div class="d-flex align-items-start gap-3">
                                                <div class="avatar-md flex-shrink-0">
                                                    <div class="avatar-title bg-success-subtle text-success rounded-3 fs-4">
                                                        <i class="ri-psychotherapy-line"></i>
                                                    </div>
                                                </div>

                                                <div>
                                                    <h4 class="fs-5 fw-semibold mb-2">
                                                        Skrining Psikologi
                                                    </h4>

                                                    <p class="text-muted lh-lg mb-0">
                                                        Calon peserta didik mengikuti skrining psikologi
                                                        sesuai jadwal yang diinformasikan oleh sekolah.
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="d-flex align-items-start gap-3">
                                                <div class="avatar-md flex-shrink-0">
                                                    <div class="avatar-title bg-success-subtle text-success rounded-3 fs-4">
                                                        <i class="ri-megaphone-line"></i>
                                                    </div>
                                                </div>

                                                <div>
                                                    <h4 class="fs-5 fw-semibold mb-2">
                                                        Pengumuman Hasil
                                                    </h4>

                                                    <p class="text-muted lh-lg mb-0">
                                                        Informasi hasil penerimaan disampaikan
                                                        pada April 2027.
                                                    </p>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- CTA --}}
                        <div class="card border-0 rounded-4 bg-primary-subtle mb-0">
                            <div class="card-body p-4 p-lg-5">
                                <div class="row align-items-center g-3">
                                    <div class="col-lg">
                                        <h3 class="fs-4 fw-bold mb-2">
                                            Siapkan Persyaratan Pendaftaran
                                        </h3>

                                        <p class="text-muted mb-0 lh-lg">
                                            Pastikan dokumen calon peserta didik sudah dipersiapkan
                                            sebelum melakukan pendaftaran.
                                        </p>
                                    </div>

                                    <div class="col-lg-auto">
                                        <a href="#persyaratan"
                                        class="btn btn-primary rounded-pill px-4 py-2 fw-medium">
                                            Lihat Persyaratan
                                            <i class="ri-arrow-right-line align-middle ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </section>

                <section class="py-5 bg-white" id="persyaratan">
                    <div class="container">

                        {{-- Header --}}
                        <div class="text-center mx-auto mb-5" style="max-width: 720px;">
                            <span class="d-inline-block text-primary text-uppercase fw-semibold small mb-2">
                                Persyaratan SPMB
                            </span>

                            <h2 class="display-6 fw-bold mb-3">
                                Siapkan Dokumen Pendaftaran
                            </h2>

                            <p class="text-muted lh-lg mb-0">
                                Pastikan dokumen berikut sudah disiapkan agar proses
                                pendaftaran calon peserta didik SD Islam Al Hidayah
                                berjalan lebih lancar.
                            </p>
                        </div>

                        <div class="row g-4">

                            {{-- Dokumen Fisik --}}
                            <div class="col-lg-7">
                                <div class="card h-100 border rounded-4 shadow-none mb-0">
                                    <div class="card-body p-4 p-lg-5">

                                        <div class="d-flex align-items-center gap-3 mb-4">
                                            <div class="avatar-md flex-shrink-0">
                                                <div class="avatar-title bg-primary-subtle text-primary rounded-3 fs-4">
                                                    <i class="ri-file-list-3-line"></i>
                                                </div>
                                            </div>

                                            <div>
                                                <h3 class="fs-5 fw-semibold mb-1">
                                                    Dokumen yang Dibutuhkan
                                                </h3>
                                                <p class="text-muted small mb-0">
                                                    Siapkan berkas berikut
                                                </p>
                                            </div>
                                        </div>

                                        @php
                                            $documents = [
                                                [
                                                    'title' => 'Akta kelahiran',
                                                    'detail' => 'Fotokopi 1 lembar',
                                                ],
                                                [
                                                    'title' => 'Kartu Keluarga (KK)',
                                                    'detail' => 'Fotokopi 1 lembar',
                                                ],
                                                [
                                                    'title' => 'KTP ayah dan ibu',
                                                    'detail' => 'Fotokopi 1 lembar',
                                                ],
                                                [
                                                    'title' => 'Pas foto calon siswa',
                                                    'detail' => 'Ukuran 3 × 4, latar belakang merah, 2 lembar',
                                                ],
                                                [
                                                    'title' => 'Formulir pendaftaran',
                                                    'detail' => 'Diisi oleh orang tua calon peserta didik',
                                                ],
                                                [
                                                    'title' => 'Materai Rp10.000',
                                                    'detail' => '1 lembar',
                                                ],
                                            ];
                                        @endphp

                                        <div class="d-flex flex-column gap-3">
                                            @foreach ($documents as $document)
                                                <div class="d-flex align-items-start gap-3">
                                                    <i class="ri-checkbox-circle-line text-primary fs-5 mt-1"></i>

                                                    <div>
                                                        <h4 class="fs-6 fw-semibold mb-1">
                                                            {{ $document['title'] }}
                                                        </h4>

                                                        <p class="text-muted small mb-0 lh-lg">
                                                            {{ $document['detail'] }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                    </div>
                                </div>
                            </div>

                            {{-- Berkas Digital dan Biaya Awal --}}
                            <div class="col-lg-5">
                                <div class="d-flex flex-column gap-4 h-100">

                                    {{-- Berkas Digital --}}
                                    <div class="card border rounded-4 shadow-none mb-0">
                                        <div class="card-body p-4">

                                            <div class="d-flex align-items-center gap-3 mb-3">
                                                <div class="avatar-md flex-shrink-0">
                                                    <div class="avatar-title bg-info-subtle text-info rounded-3 fs-4">
                                                        <i class="ri-folder-upload-line"></i>
                                                    </div>
                                                </div>

                                                <div>
                                                    <h3 class="fs-5 fw-semibold mb-1">
                                                        Berkas Digital
                                                    </h3>
                                                    <p class="text-muted small mb-0">
                                                        Untuk pendaftaran online
                                                    </p>
                                                </div>
                                            </div>

                                            <p class="text-muted lh-lg mb-3">
                                                Siapkan salinan digital dokumen berikut:
                                            </p>

                                            <ul class="list-unstyled mb-0">
                                                <li class="d-flex align-items-start gap-2 mb-2">
                                                    <i class="ri-check-line text-info fs-5"></i>
                                                    <span>Kartu Keluarga (KK)</span>
                                                </li>
                                                <li class="d-flex align-items-start gap-2">
                                                    <i class="ri-check-line text-info fs-5"></i>
                                                    <span>Akta kelahiran</span>
                                                </li>
                                            </ul>

                                        </div>
                                    </div>

                                    {{-- Biaya Tahap Pendataan --}}
                                    <div class="card border rounded-4 shadow-none mb-0">
                                        <div class="card-body p-4">

                                            <div class="d-flex align-items-center gap-3 mb-3">
                                                <div class="avatar-md flex-shrink-0">
                                                    <div class="avatar-title bg-success-subtle text-success rounded-3 fs-4">
                                                        <i class="ri-wallet-3-line"></i>
                                                    </div>
                                                </div>

                                                <div>
                                                    <h3 class="fs-5 fw-semibold mb-1">
                                                        Biaya Tahap Pendataan
                                                    </h3>
                                                    <p class="text-muted small mb-0">
                                                        Sesuai informasi SPMB
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center gap-3 py-2 border-bottom">
                                                <span class="text-muted">Pendataan</span>
                                                <span class="fw-semibold text-nowrap">Rp150.000</span>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center gap-3 py-2 border-bottom">
                                                <span class="text-muted">Skrining psikologi</span>
                                                <span class="fw-semibold text-nowrap">Rp150.000</span>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-start gap-3 py-2">
                                                <span class="text-muted">
                                                    Seragam dan kaos olahraga
                                                </span>
                                                <span class="fw-semibold text-nowrap">Rp740.000</span>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center gap-3 bg-light rounded-3 p-3 mt-2">
                                                <span class="fw-semibold">Total</span>
                                                <span class="fw-bold text-primary text-nowrap">
                                                    Rp1.040.000
                                                </span>
                                            </div>

                                            <p class="text-muted small lh-lg mt-3 mb-0">
                                                Total tahap pendataan untuk calon siswa laki-laki.
                                                Untuk calon siswi perempuan, total termasuk hijab
                                                adalah Rp1.190.000.
                                            </p>

                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                        {{-- Catatan --}}
                        <div class="alert alert-light border rounded-4 mt-4 mb-0">
                            <div class="d-flex align-items-start gap-3">
                                <i class="ri-information-line fs-4 text-primary"></i>

                                <div>
                                    <h4 class="fs-6 fw-semibold mb-1">
                                        Sebelum Mendaftar
                                    </h4>

                                    <p class="text-muted small lh-lg mb-0">
                                        Pendaftaran dimulai November 2026 dan ditutup
                                        apabila kuota telah terpenuhi. Untuk informasi lebih
                                        lanjut mengenai dokumen atau proses pendaftaran,
                                        silakan hubungi panitia SPMB.
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>
                </section>

                <section class="py-5" id="ppdb-status">
                    <div class="container">
                        <div class="ppdb-status-panel rounded-4 p-4 p-lg-5 shadow">
                            <div class="row align-items-center g-4">
                                <div class="col-lg-6 ppdb-status-copy">
                                    <span class="ppdb-status-kicker">
                                        <i class="ri-search-eye-line"></i>
                                        Layanan Pendaftaran
                                    </span>

                                    <h3 class="fs-2 fw-bold text-white">
                                        Pantau Informasi Pendaftaran Calon Peserta Didik
                                    </h3>

                                    <p class="text-white-50">
                                        Gunakan fitur ini untuk memeriksa informasi pendaftaran
                                        calon peserta didik melalui portal SPMB SD Islam Al Hidayah
                                        Tahun Ajaran 2027/2028.
                                    </p>

                                    <div class="ppdb-status-points d-flex flex-wrap gap-3 text-white-50">
                                        <span class="d-inline-flex align-items-center gap-2">
                                            <i class="ri-checkbox-circle-line text-success"></i>
                                            Informasi status pendaftaran
                                        </span>

                                        <span class="d-inline-flex align-items-center gap-2">
                                            <i class="ri-checkbox-circle-line text-success"></i>
                                            Informasi proses seleksi
                                        </span>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    @livewire('p-p-d-b.landing.status-pendaftaran', [
                                        'jenjangSlug' => $jenjang->slug,
                                    ])
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                @php
                    $faqs = [
                        [
                            'question' => 'Kapan pendaftaran SPMB dibuka?',
                            'answer' => 'Pendaftaran SPMB SD Islam Al Hidayah Tahun Ajaran 2027/2028 mulai November 2026. Pendaftaran ditutup apabila kuota telah terpenuhi.',
                        ],
                        [
                            'question' => 'Bagaimana cara mendaftar?',
                            'answer' => 'Berdasarkan informasi sekolah, orang tua datang ke sekolah, mengikuti wawancara, mengisi formulir pendaftaran, melengkapi persyaratan, dan menyelesaikan pembayaran tahap pendataan. Silakan perhatikan informasi pada bagian Alur Pendaftaran.',
                        ],
                        [
                            'question' => 'Dokumen apa saja yang perlu disiapkan?',
                            'answer' => 'Dokumen yang perlu disiapkan meliputi fotokopi akta kelahiran, Kartu Keluarga, KTP ayah dan ibu, pas foto calon siswa ukuran 3×4 berlatar belakang merah sebanyak dua lembar, formulir pendaftaran, dan satu lembar materai Rp10.000. Untuk pendaftaran online, siapkan juga berkas digital KK dan akta kelahiran.',
                        ],
                        [
                            'question' => 'Berapa biaya SPMB?',
                            'answer' => 'Total biaya SPMB yang tercantum pada informasi sekolah adalah Rp4.295.000 untuk siswa laki-laki dan Rp4.445.000 untuk siswi perempuan termasuk hijab. Rincian pembayaran dibagi menjadi tahap pendataan dan tiga tahap pembayaran berikutnya. Silakan lihat bagian Biaya SPMB.',
                        ],
                        [
                            'question' => 'Kapan skrining psikologi dan pengumuman hasil dilaksanakan?',
                            'answer' => 'Sesuai informasi sekolah, skrining psikologi dan pengumuman hasil dijadwalkan pada April 2027.',
                        ],
                        [
                            'question' => 'Bagaimana jika saya membutuhkan informasi lebih lanjut?',
                            'answer' => 'Orang tua dapat menghubungi pihak SD Islam Al Hidayah melalui nomor 0822-2626-6861 untuk memperoleh informasi lebih lanjut mengenai pendaftaran.',
                        ],
                    ];
                @endphp

                <section class="py-5" id="faq">
                    <div class="container">
                        <div class="text-center mx-auto mb-5" style="max-width: 650px;">
                            <span class="d-inline-block text-primary text-uppercase fw-semibold small mb-2">
                                FAQ
                            </span>

                            <h2 class="display-6 fw-bold mb-3">
                                Pertanyaan yang Sering Diajukan
                            </h2>

                            <p class="text-muted mb-0">
                                Informasi penting untuk membantu orang tua mempersiapkan
                                pendaftaran calon peserta didik baru.
                            </p>
                        </div>

                        <div class="accordion ppdb-faq" id="ppdbFaq">
                            @foreach ($faqs as $index => $faq)
                                <div class="accordion-item border-0 shadow-sm rounded-4 overflow-hidden mb-3">
                                    <h2 class="accordion-header" id="faqHeading{{ $index }}">
                                        <button
                                            class="accordion-button fw-semibold {{ $index !== 0 ? 'collapsed' : '' }}"
                                            type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#faqCollapse{{ $index }}"
                                            aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                            aria-controls="faqCollapse{{ $index }}">
                                            {{ $faq['question'] }}
                                        </button>
                                    </h2>

                                    <div
                                        id="faqCollapse{{ $index }}"
                                        class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                                        aria-labelledby="faqHeading{{ $index }}"
                                        data-bs-parent="#ppdbFaq">
                                        <div class="accordion-body text-muted lh-lg">
                                            {{ $faq['answer'] }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>

                <section class="py-5" id="daftar">
                    <div class="container">
                        <div class="ppdb-cta rounded-4 p-4 p-lg-5">
                            <div class="ppdb-cta-content">
                                <span class="ppdb-badge d-inline-flex mb-3">
                                    SPMB Tahun Ajaran 2027/2028
                                </span>

                                <h2 class="text-white">
                                    Bersiap Menjadi Bagian dari SD Islam Al Hidayah
                                </h2>

                                <p>
                                    Awali perjalanan pendidikan putra-putri Anda bersama
                                    SD Islam Al Hidayah. Pendaftaran dimulai November 2026
                                    dan ditutup apabila kuota telah terpenuhi.
                                    Siapkan persyaratan dan ikuti alur pendaftaran yang tersedia.
                                </p>

                                <div class="d-flex flex-wrap gap-2">
                                    @auth
                                        @if (in_array(auth()->user()->peran, ['SUPERADMIN', 'ADMIN']))
                                            <a href="{{ route('ppdb.admin.dashboard') }}"
                                                class="btn btn-primary rounded-pill">
                                                <i class="ri-dashboard-line me-1"></i>
                                                Dashboard Admin
                                            </a>
                                        @else
                                            <a href="{{ route('ppdb.dashboard', [
                                                'jenjang' => $jenjang->slug,
                                            ]) }}"
                                                class="btn btn-primary rounded-pill">
                                                <i class="ri-home-line me-1"></i>
                                                Dashboard PPDB
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('ppdb.daftar', [
                                            'jenjang' => $jenjang->slug,
                                        ]) }}"
                                            class="btn btn-primary rounded-pill">
                                            <i class="ri-user-add-line me-1"></i>
                                            Mulai Pendaftaran
                                        </a>
                                    @endauth

                                    <a href="#jadwal"
                                        class="btn ppdb-secondary-cta rounded-pill">
                                        <i class="ri-calendar-check-line me-1"></i>
                                        Lihat Alur Pendaftaran
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
                                    <img
                                        src="{{ asset('assets/logo/logo.png') }}"
                                        alt="Logo {{ $jenjang->nama_jenjang }}"
                                        height="50"
                                        class="rounded-2">
                                </div>

                                <p class="text-white fs-15 fw-medium mb-2">
                                    {{ $jenjang->nama_jenjang }}
                                </p>

                                <p class="text-white-50 ff-secondary mb-4"
                                    style="max-width: 420px;">
                                    Informasi SPMB Tahun Ajaran 2027/2028.
                                    Kenali profil sekolah, program unggulan, biaya,
                                    jadwal, dan persyaratan pendaftaran calon peserta didik.
                                </p>

                                <div class="d-flex align-items-start gap-2 text-white-50 mb-3">
                                    <i class="ri-map-pin-line text-white fs-18 mt-1"></i>
                                    <span class="ff-secondary fs-13">
                                        {{ $jenjang->alamat }}
                                    </span>
                                </div>

                                <div class="d-flex align-items-center gap-2 text-white-50">
                                    <i class="ri-whatsapp-line text-white fs-18"></i>
                                    <a
                                        href="https://wa.me/6282226266861"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="link-light ff-secondary fs-13">
                                        0822-2626-6861
                                    </a>
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
                                        <a href="#profil">Profil Sekolah</a>
                                    </li>
                                    <li class="mb-2">
                                        <a href="#program">Program Unggulan</a>
                                    </li>
                                    <li class="mb-2">
                                        <a href="#biaya">Biaya SPMB</a>
                                    </li>
                                    <li class="mb-2">
                                        <a href="#jadwal">Jadwal & Alur Pendaftaran</a>
                                    </li>
                                    <li class="mb-2">
                                        <a href="#persyaratan">Persyaratan</a>
                                    </li>
                                    <li class="mb-2">
                                        <a href="#faq">Pertanyaan Umum</a>
                                    </li>
                                    <li>
                                        <a href="#ppdb-status">Cek Status Pendaftaran</a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        {{-- CTA SPMB --}}
                        <div class="col-lg-4 col-md-3">
                            <div>
                                <h5 class="text-white mb-3">
                                    Bergabung Bersama Kami
                                </h5>

                                <p class="text-white-50 ff-secondary fs-14 mb-4">
                                    Kenali proses SPMB SD Islam Al Hidayah,
                                    persiapkan dokumen yang diperlukan, dan
                                    dapatkan informasi pendaftaran Tahun Ajaran 2027/2028.
                                </p>

                                <div class="d-flex flex-wrap gap-2">
                                    @auth
                                        @if (in_array(auth()->user()->peran, ['SUPERADMIN', 'ADMIN']))
                                            <a
                                                href="{{ route('ppdb.admin.dashboard') }}"
                                                class="btn btn-primary rounded-pill px-4">
                                                <i class="ri-dashboard-line align-middle me-1"></i>
                                                Dashboard Admin
                                            </a>
                                        @else
                                            <a
                                                href="{{ route('ppdb.dashboard', [
                                                    'jenjang' => $jenjang->slug,
                                                ]) }}"
                                                class="btn btn-primary rounded-pill px-4">
                                                <i class="ri-home-line align-middle me-1"></i>
                                                Dashboard PPDB
                                            </a>
                                        @endif
                                    @else
                                        <a
                                            href="{{ route('ppdb.daftar', [
                                                'jenjang' => $jenjang->slug,
                                            ]) }}"
                                            class="btn btn-primary rounded-pill px-4">
                                            <i class="ri-pencil-line align-middle me-1"></i>
                                            Mulai Pendaftaran
                                            <i class="ri-arrow-right-line align-middle ms-1"></i>
                                        </a>
                                    @endauth

                                    <a
                                        href="#ppdb-status"
                                        class="btn btn-outline-light rounded-pill px-4">
                                        <i class="ri-search-line align-middle me-1"></i>
                                        Cek Status
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Divider --}}
                    <div class="border-top border-secondary opacity-25"></div>

                    {{-- Bottom Footer --}}
                    <div class="row align-items-center py-4 gy-3">

                        {{-- Copyright --}}
                        <div class="col-sm-6">
                            <p class="copy-rights mb-0 text-white-50 ff-secondary fs-13">
                                &copy; {{ date('Y') }}
                                {{ $jenjang->nama_jenjang }}.
                                Hak cipta dilindungi.
                            </p>
                        </div>

                        {{-- Footer Links --}}
                        <div class="col-sm-6">
                            <div class="text-sm-end">
                                <a href="#beranda" class="link-light me-3">
                                    Beranda
                                </a>

                                @auth
                                    @if (in_array(auth()->user()->peran, ['SUPERADMIN', 'ADMIN']))
                                        <a
                                            href="{{ route('ppdb.admin.dashboard') }}"
                                            class="link-light me-3">
                                            Dashboard Admin
                                        </a>
                                    @else
                                        <a
                                            href="{{ route('ppdb.dashboard', [
                                                'jenjang' => $jenjang->slug,
                                            ]) }}"
                                            class="link-light me-3">
                                            Dashboard PPDB
                                        </a>
                                    @endif
                                @else
                                    <a
                                        href="{{ route('ppdb.daftar', [
                                            'jenjang' => $jenjang->slug,
                                        ]) }}"
                                        class="link-light me-3">
                                        Daftar SPMB
                                    </a>
                                @endauth

                                <a href="#ppdb-status" class="link-light">
                                    Cek Status
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </footer>

            <a
                class="btn btn-success rounded-pill shadow-lg d-inline-flex align-items-center gap-2 px-4 py-3 fw-semibold position-fixed ppdb-help"
                href="https://wa.me/6282226266861?text={{ rawurlencode('Assalamu’alaikum, saya ingin bertanya mengenai SPMB SD Islam Al Hidayah Tahun Ajaran 2027/2028.') }}"
                target="_blank"
                rel="noopener noreferrer"
                aria-label="Hubungi panitia SPMB melalui WhatsApp"
                title="Hubungi panitia SPMB melalui WhatsApp">

                <i class="ri-whatsapp-line" aria-hidden="true"></i>
                <span>Bantuan PPDB</span>
            </a>
        </div>
        <script src="{{ asset('assets') }}/libs/bootstrap/js/bootstrap.bundle.min.js"></script>

        @livewireScripts

        <script>
            window.addEventListener('show-modal', (event) => {
                const modalElement = document.getElementById(event.detail.modalId);

                if (!modalElement) {
                    console.error('Elemen modal tidak ditemukan:', event.detail.modalId);
                    return;
                }

                if (!window.bootstrap) {
                    console.error('Bootstrap JS belum tersedia.');
                    return;
                }

                window.bootstrap.Modal
                    .getOrCreateInstance(modalElement)
                    .show();
            });
        </script>
    </body>

</html>
