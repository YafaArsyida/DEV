<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>

    <meta charset="utf-8" />
    <title>SD Islam Al Hidayah Karanggede</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    {{-- <link rel="shortcut icon" href="{{asset('assets')}}/images/favicon.ico"> --}}
    <link rel="shortcut icon" href="{{asset('assets')}}/logo/hero.png">

    <!--Swiper slider css-->
    <link href="{{asset('assets')}}/libs/swiper/swiper-bundle.min.css" rel="stylesheet" type="text/css" />

    <!-- Layout config Js -->
    <script src="{{asset('assets')}}/js/layout.js"></script>
    <!-- Bootstrap Css -->
    <link href="{{asset('assets')}}/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{asset('assets')}}/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{asset('assets')}}/css/app.min.css" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{asset('assets')}}/css/custom.min.css" rel="stylesheet" type="text/css" />
    <!-- alertifyjs Css -->
    <link href="{{asset('assets')}}/libs/alertifyjs/build/css/alertify.min.css" rel="stylesheet" type="text/css" />

    <!-- alertifyjs default themes  Css -->
    <link href="{{asset('assets')}}/libs/alertifyjs/build/css/themes/default.min.css" rel="stylesheet" type="text/css" />

    @livewireStyles

</head>

<body data-bs-spy="scroll" data-bs-target="#navbar-example">

    <!-- Begin page -->
    <div class="layout-wrapper landing">
        <nav class="navbar navbar-expand-lg navbar-landing fixed-top" id="navbar">
            <div class="container">

                {{-- Brand --}}
                <a class="navbar-brand d-flex align-items-center" href="#hero">
                    <img src="{{ asset('assets') }}/logo/atas.jpg"
                        class="card-logo card-logo-dark"
                        alt="SD Islam Al Hidayah Karanggede"
                        height="36">

                    <img src="{{ asset('assets') }}/logo/atas.jpg"
                        class="card-logo card-logo-light"
                        alt="SD Islam Al Hidayah Karanggede"
                        height="36">
                </a>

                {{-- Mobile Toggle --}}
                <button class="navbar-toggler py-0 fs-20 text-body"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent"
                    aria-expanded="false"
                    aria-label="Toggle navigation">
                    <i class="mdi mdi-menu"></i>
                </button>

                {{-- Navigation --}}
                <div class="collapse navbar-collapse" id="navbarSupportedContent">

                    {{-- Main Navigation --}}
                    <ul class="navbar-nav mx-auto mt-3 mt-lg-0 gap-lg-2"
                        id="navbar-example">

                        {{-- Beranda --}}
                        <li class="nav-item">
                            <a class="nav-link fs-14 active"
                                href="#hero">
                                Beranda
                            </a>
                        </li>

                        {{-- Kuota --}}
                        <li class="nav-item">
                            <a class="nav-link fs-14"
                                href="#kuota">
                                Informasi Kuota
                            </a>
                        </li>

                        {{-- Pilihan Kegiatan --}}
                        <li class="nav-item">
                            <a class="nav-link fs-14"
                                href="#ekstrakurikuler">
                                Pilihan Kegiatan
                            </a>
                        </li>

                        {{-- Cara Mendaftar --}}
                        <li class="nav-item">
                            <a class="nav-link fs-14"
                                href="#alur">
                                Cara Mendaftar
                            </a>
                        </li>

                    </ul>


                    {{-- CTA --}}
                    <div class="d-flex align-items-center mt-3 mt-lg-0">

                        <a href="#formulir"
                            class="btn btn-danger rounded-pill px-4">

                            <i class="ri-pencil-line align-middle me-1"></i>

                            Daftar Sekarang

                            <i class="ri-arrow-right-line align-middle ms-1"></i>

                        </a>

                    </div>

                </div>

            </div>
        </nav>
        <!-- end navbar -->
        <div class="vertical-overlay" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent.show"></div>

        <!-- start hero section -->
        <section class="section hero-section position-relative overflow-hidden" id="hero">

            {{-- Background Pattern --}}
            <div class="bg-overlay bg-overlay-pattern"></div>

            {{-- Decorative Shape --}}
            <div class="position-absolute top-0 end-0 opacity-25">
                <div class="bg-danger rounded-circle"
                    style="width: 350px; height: 350px; filter: blur(80px);">
                </div>
            </div>

            <div class="container position-relative">
                <div class="row justify-content-center">
                    <div class="col-lg-9 col-xl-8 col-sm-11">

                        <div class="text-center mt-lg-5 pt-5">

                            {{-- Badge --}}
                            <div class="mb-4">
                                <span class="badge bg-white text-danger rounded-pill px-3 py-2 fw-medium shadow-sm">
                                    <i class="ri-sparkling-2-line align-middle me-1"></i>
                                    Ekstrakurikuler 2026/2027
                                </span>
                            </div>

                            {{-- Heading --}}
                            <h1 class="display-5 fw-semibold mb-3 lh-base text-dark">
                                Kembangkan Bakat,
                                <span class="text-danger">
                                    Temukan Potensimu.
                                </span>
                            </h1>

                            {{-- Description --}}
                            <p class="lead text-muted lh-base mb-0">
                                Temukan berbagai kegiatan ekstrakurikuler yang seru dan inspiratif
                                untuk mengembangkan minat, bakat, kreativitas, dan karakter siswa
                                SD Islam Al Hidayah Karanggede.
                            </p>

                            {{-- CTA --}}
                            <div class="d-flex flex-wrap gap-2 justify-content-center mt-4">

                                <a href="#ekstrakurikuler"
                                    class="btn btn-danger btn-lg rounded-pill px-4 shadow-sm">
                                    Jelajahi Kegiatan
                                    <i class="ri-arrow-right-line align-middle ms-1"></i>
                                </a>

                                <a href="#formulir"
                                    class="btn btn-soft-danger btn-lg rounded-pill px-4">
                                    Daftar Sekarang
                                    <i class="ri-pencil-line align-middle ms-1"></i>
                                </a>

                            </div>

                            {{-- Trust / Highlight --}}
                            <div class="d-flex flex-wrap justify-content-center gap-4 mt-5 pt-2">

                                <div class="d-flex align-items-center gap-2 text-muted">
                                    <i class="ri-checkbox-circle-fill text-danger fs-18"></i>
                                    <span class="fs-13">Beragam Pilihan Kegiatan</span>
                                </div>

                                <div class="d-flex align-items-center gap-2 text-muted">
                                    <i class="ri-checkbox-circle-fill text-danger fs-18"></i>
                                    <span class="fs-13">Sesuai Minat & Bakat</span>
                                </div>

                                <div class="d-flex align-items-center gap-2 text-muted">
                                    <i class="ri-checkbox-circle-fill text-danger fs-18"></i>
                                    <span class="fs-13">Tumbuh & Berprestasi</span>
                                </div>

                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </section>
        <!-- end hero section -->

        <!-- end client section -->

        <!-- Informasi Kuota -->
        <section class="py-5 position-relative bg-light" id="kuota">
            @livewire('ekstrakurikuler.informasi-kuota')
        </section>

        <!-- end counter -->
        <section class="section" id="ekstrakurikuler">

            <div class="container">

                {{-- Section Header --}}
                <div class="row justify-content-center">
                    <div class="col-lg-8">

                        <div class="text-center mb-5">

                            <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2 mb-3">
                                <i class="ri-compass-3-line align-middle me-1"></i>
                                Pilihan Kegiatan
                            </span>

                            <h2 class="mb-3 fw-semibold lh-base">
                                Temukan Kegiatan yang
                                <span class="text-danger">Membuatmu Berkembang</span>
                            </h2>

                            <p class="text-muted mb-0">
                                Setiap anak memiliki keunikan dan potensi yang berbeda.
                                Jelajahi berbagai kegiatan ekstrakurikuler untuk belajar,
                                berkarya, dan berprestasi bersama.
                            </p>

                        </div>

                    </div>
                </div>


                {{-- Extracurricular Cards --}}
                <div class="row g-4">

                    {{-- Teater --}}
                    <div class="col-lg-4 col-md-6">
                        <div class="card border-0 shadow-sm rounded-4 h-100">

                            <div class="card-body p-4">

                                <div class="d-flex align-items-start justify-content-between mb-4">

                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-danger-subtle text-danger rounded-3 fs-20">
                                            <i class="ri-emotion-happy-line"></i>
                                        </div>
                                    </div>

                                    <span class="badge bg-light text-muted rounded-pill">
                                        Semua Kelas
                                    </span>

                                </div>

                                <h5 class="fw-semibold mb-2">
                                    Teater
                                </h5>

                                <p class="text-muted mb-4">
                                    Mengembangkan keberanian, kreativitas, dan kepercayaan diri
                                    melalui seni peran dan ekspresi.
                                </p>

                                <div class="d-flex align-items-center justify-content-between pt-3 border-top">

                                    <div>
                                        <small class="text-muted d-block">
                                            Biaya
                                        </small>
                                        <span class="fw-semibold">
                                            Rp40.000
                                        </span>
                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>


                    {{-- Tilawah --}}
                    <div class="col-lg-4 col-md-6">
                        <div class="card border-0 shadow-sm rounded-4 h-100">

                            <div class="card-body p-4">

                                <div class="d-flex align-items-start justify-content-between mb-4">

                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-danger-subtle text-danger rounded-3 fs-20">
                                            <i class="ri-book-open-line"></i>
                                        </div>
                                    </div>

                                    <span class="badge bg-light text-muted rounded-pill">
                                        Semua Kelas
                                    </span>

                                </div>

                                <h5 class="fw-semibold mb-2">
                                    Tilawah
                                </h5>

                                <p class="text-muted mb-4">
                                    Membimbing siswa meningkatkan kemampuan membaca Al-Qur'an
                                    dengan tajwid yang baik dan lantunan yang indah.
                                </p>

                                <div class="d-flex align-items-center justify-content-between pt-3 border-top">

                                    <div>
                                        <small class="text-muted d-block">
                                            Biaya
                                        </small>
                                        <span class="fw-semibold">
                                            Rp40.000
                                        </span>
                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>


                    {{-- Kriya Anyam --}}
                    <div class="col-lg-4 col-md-6">
                        <div class="card border-0 shadow-sm rounded-4 h-100">

                            <div class="card-body p-4">

                                <div class="d-flex align-items-start justify-content-between mb-4">

                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-danger-subtle text-danger rounded-3 fs-20">
                                            <i class="ri-brush-line"></i>
                                        </div>
                                    </div>

                                    <span class="badge bg-light text-muted rounded-pill">
                                        Semua Kelas
                                    </span>

                                </div>

                                <h5 class="fw-semibold mb-2">
                                    Kriya Anyam
                                </h5>

                                <p class="text-muted mb-4">
                                    Mengasah kreativitas, ketelitian, dan keterampilan tangan
                                    melalui seni kerajinan anyaman.
                                </p>

                                <div class="d-flex align-items-center justify-content-between pt-3 border-top">

                                    <div>
                                        <small class="text-muted d-block">
                                            Biaya
                                        </small>
                                        <span class="fw-semibold">
                                            Rp40.000
                                        </span>
                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>


                    {{-- Musik --}}
                    <div class="col-lg-4 col-md-6">
                        <div class="card border-0 shadow-sm rounded-4 h-100">

                            <div class="card-body p-4">

                                <div class="d-flex align-items-start justify-content-between mb-4">

                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-danger-subtle text-danger rounded-3 fs-20">
                                            <i class="ri-headphone-line"></i>
                                        </div>
                                    </div>

                                    <span class="badge bg-light text-muted rounded-pill">
                                        Semua Kelas
                                    </span>

                                </div>

                                <h5 class="fw-semibold mb-2">
                                    Musik
                                </h5>

                                <p class="text-muted mb-4">
                                    Mengenal alat musik, irama, dan teknik dasar bermain musik
                                    dengan cara yang menyenangkan dan kreatif.
                                </p>

                                <div class="d-flex align-items-center justify-content-between pt-3 border-top">

                                    <div>
                                        <small class="text-muted d-block">
                                            Biaya
                                        </small>
                                        <span class="fw-semibold">
                                            Rp40.000
                                        </span>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>


                    {{-- Kaligrafi --}}
                    <div class="col-lg-4 col-md-6">
                        <div class="card border-0 shadow-sm rounded-4 h-100">

                            <div class="card-body p-4">

                                <div class="d-flex align-items-start justify-content-between mb-4">

                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-danger-subtle text-danger rounded-3 fs-20">
                                            <i class="ri-pen-nib-line"></i>
                                        </div>
                                    </div>

                                    <span class="badge bg-light text-muted rounded-pill">
                                        Semua Kelas
                                    </span>

                                </div>

                                <h5 class="fw-semibold mb-2">
                                    Kaligrafi
                                </h5>

                                <p class="text-muted mb-4">
                                    Mengembangkan kreativitas dan kecintaan terhadap seni Islami
                                    melalui keindahan tulisan Arab.
                                </p>

                                <div class="d-flex align-items-center justify-content-between pt-3 border-top">

                                    <div>
                                        <small class="text-muted d-block">
                                            Biaya
                                        </small>
                                        <span class="fw-semibold">
                                            Rp40.000
                                        </span>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>


                    {{-- Silat --}}
                    <div class="col-lg-4 col-md-6">
                        <div class="card border-0 shadow-sm rounded-4 h-100">

                            <div class="card-body p-4">

                                <div class="d-flex align-items-start justify-content-between mb-4">

                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-danger-subtle text-danger rounded-3 fs-20">
                                            <i class="ri-sword-line"></i>
                                        </div>
                                    </div>

                                    <span class="badge bg-light text-muted rounded-pill">
                                        Semua Kelas
                                    </span>

                                </div>

                                <h5 class="fw-semibold mb-2">
                                    Silat
                                </h5>

                                <p class="text-muted mb-4">
                                    Melatih kedisiplinan, keberanian, ketangkasan, dan karakter
                                    melalui seni bela diri tradisional Indonesia.
                                </p>

                                <div class="d-flex align-items-center justify-content-between pt-3 border-top">

                                    <div>
                                        <small class="text-muted d-block">
                                            Biaya
                                        </small>
                                        <span class="fw-semibold">
                                            Rp40.000
                                        </span>
                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>


                    {{-- Taekwondo --}}
                    <div class="col-lg-4 col-md-6">
                        <div class="card border-0 shadow-sm rounded-4 h-100">

                            <div class="card-body p-4">

                                <div class="d-flex align-items-start justify-content-between mb-4">

                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-danger-subtle text-danger rounded-3 fs-20">
                                            <i class="ri-user-follow-line"></i>
                                        </div>
                                    </div>

                                    <span class="badge bg-light text-muted rounded-pill">
                                        Semua Kelas
                                    </span>

                                </div>

                                <h5 class="fw-semibold mb-2">
                                    Taekwondo
                                </h5>

                                <p class="text-muted mb-4">
                                    Mengembangkan kekuatan fisik, disiplin, fokus, dan kepercayaan
                                    diri melalui seni bela diri Korea.
                                </p>

                                <div class="d-flex align-items-center justify-content-between pt-3 border-top">

                                    <div>
                                        <small class="text-muted d-block">
                                            Biaya
                                        </small>
                                        <span class="fw-semibold">
                                            Rp40.000
                                        </span>
                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>


                    {{-- Menari --}}
                    <div class="col-lg-4 col-md-6">
                        <div class="card border-0 shadow-sm rounded-4 h-100">

                            <div class="card-body p-4">

                                <div class="d-flex align-items-start justify-content-between mb-4">

                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-danger-subtle text-danger rounded-3 fs-20">
                                            <i class="ri-music-line"></i>
                                        </div>
                                    </div>

                                    <span class="badge bg-light text-muted rounded-pill">
                                        Semua Kelas
                                    </span>

                                </div>

                                <h5 class="fw-semibold mb-2">
                                    Menari
                                </h5>

                                <p class="text-muted mb-4">
                                    Mengekspresikan kreativitas dan melestarikan seni budaya
                                    melalui gerakan yang indah dan penuh ekspresi.
                                </p>

                                <div class="d-flex align-items-center justify-content-between pt-3 border-top">

                                    <div>
                                        <small class="text-muted d-block">
                                            Biaya
                                        </small>
                                        <span class="fw-semibold">
                                            Rp40.000
                                        </span>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>


                    {{-- Futsal --}}
                    <div class="col-lg-4 col-md-6">
                        <div class="card border-0 shadow-sm rounded-4 h-100">

                            <div class="card-body p-4">

                                <div class="d-flex align-items-start justify-content-between mb-4">

                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-danger-subtle text-danger rounded-3 fs-20">
                                            <i class="ri-football-line"></i>
                                        </div>
                                    </div>

                                    <span class="badge bg-light text-muted rounded-pill">
                                        Semua Kelas
                                    </span>

                                </div>

                                <h5 class="fw-semibold mb-2">
                                    Futsal
                                </h5>

                                <p class="text-muted mb-4">
                                    Menumbuhkan semangat kebersamaan, sportivitas, kerja sama tim,
                                    dan kebugaran jasmani siswa.
                                </p>

                                <div class="d-flex align-items-center justify-content-between pt-3 border-top">

                                    <div>
                                        <small class="text-muted d-block">
                                            Biaya
                                        </small>
                                        <span class="fw-semibold">
                                            Rp40.000
                                        </span>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>


                    {{-- Banjari --}}
                    <div class="col-lg-4 col-md-6">
                        <div class="card border-0 shadow-sm rounded-4 h-100">

                            <div class="card-body p-4">

                                <div class="d-flex align-items-start justify-content-between mb-4">

                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-danger-subtle text-danger rounded-3 fs-20">
                                            <i class="ri-mic-line"></i>
                                        </div>
                                    </div>

                                    <span class="badge bg-light text-muted rounded-pill">
                                        Semua Kelas
                                    </span>

                                </div>

                                <h5 class="fw-semibold mb-2">
                                    Banjari
                                </h5>

                                <p class="text-muted mb-4">
                                    Menumbuhkan kecintaan terhadap seni musik Islami melalui
                                    lantunan shalawat dan permainan rebana.
                                </p>

                                <div class="d-flex align-items-center justify-content-between pt-3 border-top">

                                    <div>
                                        <small class="text-muted d-block">
                                            Biaya
                                        </small>
                                        <span class="fw-semibold">
                                            Rp40.000
                                        </span>
                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>

                </div>

            </div>

        </section>


        <!-- start cta -->
        <section class="py-5 bg-danger position-relative overflow-hidden">

            {{-- Background Pattern --}}
            <div class="bg-overlay bg-overlay-pattern opacity-25"></div>

            
            <div class="container position-relative">
                <div class="row align-items-center gy-4">

                    {{-- Content --}}
                    <div class="col-lg">

                        <div>

                            <span class="badge bg-white bg-opacity-10 text-white rounded-pill px-3 py-2 mb-3">
                                <i class="ri-sparkling-line align-middle me-1"></i>
                                Saatnya Menemukan Potensi Terbaik
                            </span>

                            <h3 class="text-white mb-2 fw-semibold">
                                Setiap Anak Punya Potensi.
                                <br>
                                Mari Bantu Mereka Menemukannya.
                            </h3>

                            <p class="text-white-50 mb-0">
                                Pilih kegiatan yang sesuai dengan minat dan bakat,
                                lalu tumbuh bersama dalam pengalaman yang positif dan bermakna.
                            </p>

                        </div>

                    </div>

                    {{-- CTA --}}
                    <div class="col-lg-auto">

                        <div>

                            <a href="#formulir"
                                class="btn btn-light btn-lg rounded-pill px-4 shadow-sm">

                                <i class="ri-pencil-line align-middle me-1"></i>
                                Daftar Sekarang

                                <i class="ri-arrow-right-line align-middle ms-1"></i>

                            </a>

                        </div>

                    </div>

                </div>
            </div>

        </section>
        <!-- end cta -->

        <!-- Start Registration Process -->
        <section class="section bg-light" id="alur">
            <div class="container">

                {{-- Section Header --}}
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="text-center mb-5">

                            <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2 mb-3">
                                <i class="ri-route-line align-middle me-1"></i>
                                Cara Mendaftar
                            </span>

                            <h3 class="mb-3 fw-semibold">
                                Mulai Perjalananmu di Sini
                            </h3>

                            <p class="text-muted mb-0 ff-secondary">
                                Pilih kegiatan yang kamu sukai, lengkapi pendaftaran,
                                dan bersiap untuk belajar, berkarya, dan tumbuh bersama.
                            </p>

                        </div>
                    </div>
                </div>

                {{-- Process --}}
                <div class="row text-center">

                    {{-- Step 1 --}}
                    <div class="col-lg-4">
                        <div class="process-card mt-4">

                            {{-- Arrow --}}
                            <div class="process-arrow-img d-none d-lg-block">
                                <img src="{{ asset('assets') }}/images/landing/process-arrow-img.png"
                                    alt=""
                                    class="img-fluid">
                            </div>

                            {{-- Icon --}}
                            <div class="avatar-sm mx-auto mb-4">
                                <div class="avatar-title bg-transparent text-danger rounded-circle h1">
                                    <i class="ri-compass-3-line"></i>
                                </div>
                            </div>

                            <h4 class="fw-semibold">
                                Pilih Kegiatan
                            </h4>

                            <p class="text-muted ff-secondary mb-0">
                                Jelajahi berbagai pilihan ekstrakurikuler
                                dan temukan kegiatan yang sesuai dengan
                                minat dan bakatmu.
                            </p>

                        </div>
                    </div>


                    {{-- Step 2 --}}
                    <div class="col-lg-4">
                        <div class="process-card mt-4">

                            {{-- Arrow --}}
                            <div class="process-arrow-img d-none d-lg-block">
                                <img src="{{ asset('assets') }}/images/landing/process-arrow-img.png"
                                    alt=""
                                    class="img-fluid">
                            </div>

                            {{-- Icon --}}
                            <div class="avatar-sm mx-auto mb-4">
                                <div class="avatar-title bg-transparent text-danger rounded-circle h1">
                                    <i class="ri-edit-box-line"></i>
                                </div>
                            </div>

                            <h4 class="fw-semibold">
                                Isi Pendaftaran
                            </h4>

                            <p class="text-muted ff-secondary mb-0">
                                Lengkapi data siswa dan pilih
                                ekstrakurikuler yang ingin diikuti
                                melalui formulir pendaftaran.
                            </p>

                        </div>
                    </div>


                    {{-- Step 3 --}}
                    <div class="col-lg-4">
                        <div class="process-card mt-4">

                            {{-- Icon --}}
                            <div class="avatar-sm mx-auto mb-4">
                                <div class="avatar-title bg-transparent text-danger rounded-circle h1">
                                    <i class="ri-checkbox-circle-line"></i>
                                </div>
                            </div>

                            <h4 class="fw-semibold">
                                Pendaftaran Berhasil
                            </h4>

                            <p class="text-muted ff-secondary mb-0">
                                Pendaftaran selesai. Selanjutnya,
                                ikuti kegiatan ekstrakurikuler sesuai
                                jadwal yang telah ditentukan.
                            </p>

                        </div>
                    </div>

                </div>

            </div>
        </section>
        <!-- End Registration Process -->

       <!-- Start Pendaftaran Ekskul -->
        <section class="section" id="formulir">

            @livewire('formulir-ekstrakurikuler.index')

        </section>
        <!-- End Pendaftaran Ekskul -->
        <!-- Start Footer -->
        <footer class="custom-footer bg-dark position-relative">

            <div class="container">

                {{-- Main Footer --}}
                <div class="row py-5 gy-4">

                    {{-- School Info --}}
                    <div class="col-lg-5 col-md-6">

                        <div>

                            {{-- Logo --}}
                            <div class="mb-4">
                                <img src="{{ asset('assets') }}/logo/hero.png"
                                    alt="SD Islam Al Hidayah Karanggede"
                                    height="50">
                            </div>

                            {{-- Description --}}
                            <p class="text-white fs-15 fw-medium mb-2">
                                SD Islam Al Hidayah Karanggede
                            </p>

                            <p class="text-white-50 ff-secondary mb-4"
                                style="max-width: 420px;">
                                Tempat bagi siswa untuk menemukan potensi,
                                mengembangkan bakat, dan tumbuh menjadi pribadi
                                yang kreatif, mandiri, dan berkarakter.
                            </p>

                            {{-- Address --}}
                            <div class="d-flex align-items-start gap-2 text-white-50">

                                <i class="ri-map-pin-line text-danger fs-18 mt-1"></i>

                                <span class="ff-secondary fs-13">
                                    Dusun No.2 RT.04/RW.01, Dusun 2,
                                    Kebonan, Karanggede,<br>
                                    Boyolali, Jawa Tengah 57381
                                </span>

                            </div>

                        </div>

                    </div>


                    {{-- Navigation --}}
                    <div class="col-lg-3 col-md-3 col-6">

                        <div>

                            <h5 class="text-white mb-4">
                                Jelajahi
                            </h5>

                            <ul class="list-unstyled ff-secondary footer-list fs-14 mb-0">

                                <li>
                                    <a href="#hero">
                                        Beranda
                                    </a>
                                </li>

                                <li>
                                    <a href="#ekstrakurikuler">
                                        Ekstrakurikuler
                                    </a>
                                </li>

                                <li>
                                    <a href="#kuota">
                                        Informasi Kuota
                                    </a>
                                </li>

                                <li>
                                    <a href="#alur">
                                        Alur Pendaftaran
                                    </a>
                                </li>

                                <li>
                                    <a href="#formulir">
                                        Formulir Pendaftaran
                                    </a>
                                </li>

                            </ul>

                        </div>

                    </div>


                    {{-- CTA --}}
                    <div class="col-lg-4 col-md-3">

                        <div>

                            <h5 class="text-white mb-3">
                                Siap Menemukan Potensimu?
                            </h5>

                            <p class="text-white-50 ff-secondary fs-14 mb-4">
                                Pilih kegiatan yang sesuai dengan minat dan bakat,
                                lalu mulai perjalananmu bersama ekstrakurikuler
                                SD Islam Al Hidayah Karanggede.
                            </p>

                            <a href="#formulir"
                                class="btn btn-danger rounded-pill px-4">

                                <i class="ri-pencil-line align-middle me-1"></i>

                                Mulai Pendaftaran

                                <i class="ri-arrow-right-line align-middle ms-1"></i>

                            </a>

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

                            ©
                            <script>
                                document.write(new Date().getFullYear())
                            </script>

                            SD Islam Al Hidayah Karanggede.
                            All Rights Reserved.

                        </p>

                    </div>


                    {{-- Social Media --}}
                    <div class="col-sm-6">

                        <div class="text-sm-end">

                            <ul class="list-inline mb-0 footer-social-link">

                                {{-- Instagram --}}
                                <li class="list-inline-item">

                                    <a href="#"
                                        class="avatar-xs d-block"
                                        title="Instagram">

                                        <div class="avatar-title rounded-circle bg-light text-dark">
                                            <i class="ri-instagram-line"></i>
                                        </div>

                                    </a>

                                </li>


                                {{-- YouTube --}}
                                <li class="list-inline-item">

                                    <a href="#"
                                        class="avatar-xs d-block"
                                        title="YouTube">

                                        <div class="avatar-title rounded-circle bg-light text-dark">
                                            <i class="ri-youtube-fill"></i>
                                        </div>

                                    </a>

                                </li>


                                {{-- Facebook --}}
                                <li class="list-inline-item">

                                    <a href="#"
                                        class="avatar-xs d-block"
                                        title="Facebook">

                                        <div class="avatar-title rounded-circle bg-light text-dark">
                                            <i class="ri-facebook-fill"></i>
                                        </div>

                                    </a>

                                </li>

                            </ul>

                        </div>

                    </div>

                </div>

            </div>

        </footer>
        <!-- End Footer -->


        <!--start back-to-top-->
        <button onclick="topFunction()" class="btn btn-danger btn-icon landing-back-top" id="back-to-top">
            <i class="ri-arrow-up-line"></i>
        </button>
        <!--end back-to-top-->

    </div>
    <!-- end layout wrapper -->


    @livewireScripts
    <!-- JAVASCRIPT -->
    <script src="{{asset('assets')}}/libs/alertifyjs/build/alertify.min.js"></script>
    <script src="{{asset('assets')}}/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('assets')}}/libs/simplebar/simplebar.min.js"></script>
    <script src="{{asset('assets')}}/libs/node-waves/waves.min.js"></script>
    <script src="{{asset('assets')}}/libs/feather-icons/feather.min.js"></script>
    <script src="{{asset('assets')}}/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="{{asset('assets')}}/js/plugins.js"></script>

    <!--Swiper slider js-->
    <script src="{{asset('assets')}}/libs/swiper/swiper-bundle.min.js"></script>

    <!-- landing init -->
    <script src="{{asset('assets')}}/js/pages/landing.init.js"></script>

    <script>
        // notif
        window.addEventListener('alertify-success', event => {
            alertify.set('notifier', 'position', 'bottom-right');
            alertify.success(event.detail.message);
        });

        window.addEventListener('alertify-error', event => {
            alertify.set('notifier', 'position', 'bottom-right');
            alertify.error(event.detail.message);
        });
    </script> 
</body>

</html>