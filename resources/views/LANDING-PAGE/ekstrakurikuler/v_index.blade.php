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
                <a class="navbar-brand" href="index.html">
                    <img src="{{ asset('assets') }}/logo/atas.jpg" class="card-logo card-logo-dark" alt="logo dark" height="30">
                    <img src="{{ asset('assets') }}/logo/atas.jpg" class="card-logo card-logo-light" alt="logo light" height="30">
                </a>
                <button class="navbar-toggler py-0 fs-20 text-body" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="mdi mdi-menu"></i>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mx-auto mt-2 mt-lg-0" id="navbar-example">
                        <li class="nav-item">
                            <a class="nav-link fs-14 active" href="#hero">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fs-14 active" href="#ekstrakurikuler">Ekstrakurikuler</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fs-14" href="#alur">Alur Pendaftaran</a>
                        </li>
                         {{-- <li class="nav-item">
                            <a class="nav-link fs-14" href="#cerita">Cerita Mereka</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fs-14" href="#pembimbing">Pembimbing</a>
                        </li> --}}
                        <li class="nav-item">
                            <a class="nav-link fs-14" href="#formulir">Formulir</a>
                        </li>
                    </ul>

                    <div class="">
                        {{-- <a href="{{ route('login.index') }}" class="btn btn-link fw-medium text-decoration-none text-body">Login Admin</a> --}}
                        {{-- <a href="auth-signup-basic.html" class="btn btn-primary">Sign Up</a> --}}
                    </div>
                </div>

            </div>
        </nav>
        <!-- end navbar -->
        <div class="vertical-overlay" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent.show"></div>

        <!-- start hero section -->
        <section class="section hero-section" id="hero">
            <div class="bg-overlay bg-overlay-pattern"></div>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-sm-10">
                        <div class="text-center mt-lg-5 pt-5">
                            <h1 class="display-6 fw-semibold mb-3 lh-base">
                                Temukan Bakat & Potensi Anak di <span class="text-danger">Ekstrakurikuler</span> SD Islam Al Hidayah Karanggede
                            </h1>
                            <p class="lead text-muted lh-base">
                                Kami menghadirkan beragam kegiatan ekstrakurikuler yang inspiratif untuk mendukung tumbuh kembang siswa secara holistik — dari seni, olahraga, hingga keterampilan hidup.
                            </p>

                            <div class="d-flex gap-2 justify-content-center mt-4">
                                <a href="#ekstrakurikuler" class="btn btn-primary">
                                    Lihat Ekskul <i class="ri-arrow-right-line align-middle ms-1"></i>
                                </a>
                                <a href="#formulir" class="btn btn-danger">
                                    Daftar Sekarang <i class="ri-pencil-line align-middle ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- end row -->
            </div>
            <!-- end container -->
        </section>
        <!-- end hero section -->

        {{-- <!-- start client section -->
        <div class="pt-5 mt-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">

                        <div class="text-center mt-5">
                            <h5 class="fs-20">Didukung oleh</h5>

                            <!-- Swiper -->
                            <div class="swiper trusted-client-slider mt-sm-5 mt-4 mb-sm-5 mb-4" dir="ltr">
                                <div class="swiper-wrapper">
                                    <!-- Muhammadiyah -->
                                    <div class="swiper-slide">
                                        <div class="client-images">
                                            <img src="{{asset('assets')}}/images/clients/amazon.svg" alt="client-img" class="mx-auto img-fluid d-block">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="client-images">
                                            <img src="{{asset('assets')}}/images/clients/walmart.svg" alt="client-img" class="mx-auto img-fluid d-block">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="client-images">
                                            <img src="{{asset('assets')}}/images/clients/lenovo.svg" alt="client-img" class="mx-auto img-fluid d-block">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="client-images">
                                            <img src="{{asset('assets')}}/images/clients/paypal.svg" alt="client-img" class="mx-auto img-fluid d-block">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="client-images">
                                            <img src="{{asset('assets')}}/images/clients/shopify.svg" alt="client-img" class="mx-auto img-fluid d-block">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="client-images">
                                            <img src="{{asset('assets')}}/images/clients/verizon.svg" alt="client-img" class="mx-auto img-fluid d-block">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div> --}}

        <!-- end client section -->

          <!-- start counter -->
        <section class="py-5 position-relative bg-light">
            @livewire('ekstrakurikuler.informasi-kuota')   
        </section>

        <!-- end counter -->

        <section class="section" id="ekstrakurikuler">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="text-center mb-5">
                            <h1 class="mb-3 ff-secondary fw-semibold lh-base">Eksplorasi Minat & Bakat Siswa</h1>
                            <p class="text-muted">SD Islam Al Hidayah Karanggede menyediakan berbagai kegiatan ekstrakurikuler untuk menumbuhkan kemandirian, kedisiplinan, dan kreativitas sejak dini.</p>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <!-- TIK -->
                    <div class="col-lg-4">
                        <div class="d-flex p-3">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-sm icon-effect">
                                    <div class="avatar-title bg-transparent text-success rounded-circle">
                                        <i class="ri-computer-line fs-36"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fs-18">TIK (Teknologi Informasi)</h5>
                                <p class="text-muted my-3 ff-secondary">Untuk kelas 3-6. Mengenal komputer, mengetik, dan pengenalan coding sederhana.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Silat -->
                    <div class="col-lg-4">
                        <div class="d-flex p-3">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-sm icon-effect">
                                    <div class="avatar-title bg-transparent text-success rounded-circle">
                                        <i class="ri-sword-line fs-36"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fs-18">Pencak Silat</h5>
                                <p class="text-muted my-3 ff-secondary">Melatih kedisiplinan dan ketangkasan dengan seni bela diri tradisional Indonesia.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Taekwondo -->
                    <div class="col-lg-4">
                        <div class="d-flex p-3">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-sm icon-effect">
                                    <div class="avatar-title bg-transparent text-success rounded-circle">
                                        <i class="ri-user-follow-line fs-36"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fs-18">Taekwondo</h5>
                                <p class="text-muted my-3 ff-secondary">Seni bela diri Korea yang melatih kekuatan fisik dan fokus mental siswa.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Futsal -->
                    <div class="col-lg-4">
                        <div class="d-flex p-3">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-sm icon-effect">
                                    <div class="avatar-title bg-transparent text-success rounded-circle">
                                        <i class="ri-football-line fs-36"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fs-18">Futsal</h5>
                                <p class="text-muted my-3 ff-secondary">Menumbuhkan semangat tim, sportivitas, dan kebugaran jasmani siswa.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Menari -->
                    <div class="col-lg-4">
                        <div class="d-flex p-3">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-sm icon-effect">
                                    <div class="avatar-title bg-transparent text-success rounded-circle">
                                        <i class="ri-music-line fs-36"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fs-18">Menari</h5>
                                <p class="text-muted my-3 ff-secondary">Melestarikan seni budaya lewat gerakan yang indah dan ekspresif.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Musik -->
                    <div class="col-lg-4">
                        <div class="d-flex p-3">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-sm icon-effect">
                                    <div class="avatar-title bg-transparent text-success rounded-circle">
                                        <i class="ri-headphone-line fs-36"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fs-18">Musik</h5>
                                <p class="text-muted my-3 ff-secondary">Mengenal alat musik, irama, dan teknik dasar bermain musik dengan fun.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Kaligrafi -->
                    <div class="col-lg-4">
                        <div class="d-flex p-3">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-sm icon-effect">
                                    <div class="avatar-title bg-transparent text-success rounded-circle">
                                        <i class="ri-brush-line fs-36"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fs-18">Kaligrafi</h5>
                                <p class="text-muted my-3 ff-secondary">Mengasah keindahan tulisan Arab dan seni visual Islami.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Teater -->
                    <div class="col-lg-4">
                        <div class="d-flex p-3">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-sm icon-effect">
                                    <div class="avatar-title bg-transparent text-success rounded-circle">
                                        <i class="ri-emotion-happy-line fs-36"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fs-18">Teater</h5>
                                <p class="text-muted my-3 ff-secondary">Menumbuhkan kepercayaan diri dan ekspresi diri melalui seni peran.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Public Speaking -->
                    <div class="col-lg-4">
                        <div class="d-flex p-3">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-sm icon-effect">
                                    <div class="avatar-title bg-transparent text-success rounded-circle">
                                        <i class="ri-mic-line fs-36"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fs-18">Public Speaking</h5>
                                <p class="text-muted my-3 ff-secondary">Melatih siswa berbicara di depan umum dengan percaya diri dan lugas.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tilawah -->
                    <div class="col-lg-4">
                        <div class="d-flex p-3">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-sm icon-effect">
                                    <div class="avatar-title bg-transparent text-success rounded-circle">
                                        <i class="ri-book-read-line fs-36"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fs-18">Tilawah</h5>
                                <p class="text-muted my-3 ff-secondary">Meningkatkan kemampuan membaca Al-Qur’an dengan tajwid yang benar dan suara yang merdu.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


         <!-- start cta -->
        <section class="py-5 bg-primary position-relative">
            <div class="bg-overlay bg-overlay-pattern opacity-50"></div>
            <div class="container">
                <div class="row align-items-center gy-4">
                    <div class="col-sm">
                        <div>
                            <h4 class="text-white mb-0 fw-semibold">
                                Dukung Potensi Anak Anda Bersinar Lewat Kegiatan Ekstrakurikuler!
                            </h4>
                            <p class="text-white-50 mb-0 mt-1">
                                Daftarkan sekarang dan jadilah bagian dari generasi emas yang cerdas, kreatif, dan berakhlak.
                            </p>
                        </div>
                    </div>
                    <!-- end col -->
                    <div class="col-sm-auto">
                        <div>
                            <a href="#formulir" class="btn bg-gradient btn-light text-primary">
                                <i class="ri-edit-box-line align-middle me-1"></i> Daftar Sekarang
                            </a>
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </section>
        <!-- end cta -->

        <!-- start Work Process -->
        <section class="section" id="alur">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="text-center mb-5">
                            <h3 class="mb-3 fw-semibold">Alur Pendaftaran Ekstrakurikuler</h3>
                            <p class="text-muted mb-4 ff-secondary">
                                Pendaftaran ekstrakurikuler kini semakin mudah! Ikuti langkah-langkah sederhana berikut untuk menjadi bagian dari kegiatan yang kamu minati dan kembangkan potensimu di luar kelas.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- end row -->

                <div class="row text-center">
                    <div class="col-lg-4">
                        <div class="process-card mt-4">
                            <div class="process-arrow-img d-none d-lg-block">
                                <img src="{{asset('assets')}}/images/landing/process-arrow-img.png" alt="" class="img-fluid">
                            </div>
                            <div class="avatar-sm icon-effect mx-auto mb-4">
                                <div class="avatar-title bg-transparent text-success rounded-circle h1">
                                    <i class="ri-global-line"></i>
                                </div>
                            </div>

                            <h5>Daftar via Website</h5>
                            <p class="text-muted ff-secondary">Buka halaman pendaftaran di website resmi sekolah dan pilih ekskul yang ingin diikuti.</p>
                        </div>
                    </div>
                    <!-- end col -->

                    <div class="col-lg-4">
                        <div class="process-card mt-4">
                            <div class="process-arrow-img d-none d-lg-block">
                                <img src="{{asset('assets')}}/images/landing/process-arrow-img.png" alt="" class="img-fluid">
                            </div>
                            <div class="avatar-sm icon-effect mx-auto mb-4">
                                <div class="avatar-title bg-transparent text-success rounded-circle h1">
                                    <i class="ri-notification-2-line"></i>
                                </div>
                            </div>

                            <h5>Pengumuman Peserta Terpilih</h5>
                            <p class="text-muted ff-secondary">Pantau pengumuman dari administrasi untuk mengetahui apakah kamu terpilih sebagai peserta ekskul.</p>
                        </div>
                    </div>
                    <!-- end col -->

                    <div class="col-lg-4">
                        <div class="process-card mt-4">
                            <div class="avatar-sm icon-effect mx-auto mb-4">
                                <div class="avatar-title bg-transparent text-success rounded-circle h1">
                                    <i class="ri-calendar-check-line"></i>
                                </div>
                            </div>

                            <h5>Ikuti Kegiatan Ekskul</h5>
                            <p class="text-muted ff-secondary">Datang dan ikuti kegiatan ekskul sesuai jadwal yang telah ditentukan. Semangat berkarya!</p>
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </section>
        <!-- end Work Process -->


        <!-- start review -->
        {{-- <section class="section bg-primary" id="cerita">
            <div class="bg-overlay bg-overlay-pattern"></div>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="text-center">
                            <div>
                                <i class="ri-double-quotes-l text-success display-3"></i>
                            </div>
                            <h4 class="text-white mb-5">Cerita Mereka</h4>

                            <!-- Swiper -->
                            <div class="swiper client-review-swiper rounded" dir="ltr">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <div class="row justify-content-center">
                                            <div class="col-10">
                                                <div class="text-white-50">
                                                    <p class="fs-20 ff-secondary mb-4">"Ekskul Pramuka di sekolah ini seru banget! Kegiatan latihannya selalu menyenangkan dan bikin kami jadi lebih disiplin dan mandiri."</p>
                                                    <div>
                                                        <h5 class="text-white">Alya Putri</h5>
                                                        <p>- Siswa Kelas 8</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end slide -->
                                    <div class="swiper-slide">
                                        <div class="row justify-content-center">
                                            <div class="col-10">
                                                <div class="text-white-50">
                                                    <p class="fs-20 ff-secondary mb-4">"Saya ikut ekskul futsal sejak kelas 7, dan sekarang jadi kapten tim. Pelatihnya asik, latihannya rutin, dan kami sering ikut turnamen!"</p>
                                                    <div>
                                                        <h5 class="text-white">Dio Pratama</h5>
                                                        <p>- Siswa Kelas 9</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end slide -->
                                    <div class="swiper-slide">
                                        <div class="row justify-content-center">
                                            <div class="col-10">
                                                <div class="text-white-50">
                                                    <p class="fs-20 ff-secondary mb-4">"Ekskul tari tradisional bikin aku lebih cinta budaya Indonesia. Latihannya seru dan kami sering tampil di acara sekolah."</p>
                                                    <div>
                                                        <h5 class="text-white">Nabila Rahma</h5>
                                                        <p>- Siswi Kelas 7</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end slide -->
                                    <div class="swiper-slide">
                                        <div class="row justify-content-center">
                                            <div class="col-10">
                                                <div class="text-white-50">
                                                    <p class="fs-20 ff-secondary mb-4">"Di ekskul robotik, aku belajar bikin robot sendiri dari nol. Seru banget bisa ngoding dan lihat robotnya bisa jalan!"</p>
                                                    <div>
                                                        <h5 class="text-white">Kevin Alvaro</h5>
                                                        <p>- Siswa Kelas 8</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end slide -->
                                    <div class="swiper-slide">
                                        <div class="row justify-content-center">
                                            <div class="col-10">
                                                <div class="text-white-50">
                                                    <p class="fs-20 ff-secondary mb-4">"Ekskul jurnalistik ngajarin aku nulis berita dan bikin majalah sekolah. Sekarang aku jadi lebih percaya diri saat wawancara!"</p>
                                                    <div>
                                                        <h5 class="text-white">Rizky Ananda</h5>
                                                        <p>- Siswa Kelas 9</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end slide -->
                                </div>
                                <div class="swiper-button-next bg-white rounded-circle"></div>
                                <div class="swiper-button-prev bg-white rounded-circle"></div>
                                <div class="swiper-pagination position-relative mt-2"></div>
                            </div>
                            <!-- end slider -->
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </section> --}}
        <!-- end review -->

        <!-- start team -->
        {{-- <section class="section bg-light" id="pembimbing">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="text-center mb-5">
                            <h3 class="mb-3 fw-semibold">Pembimbing <span class="text-danger">Ekstrakurikuler</span></h3>
                            <p class="text-muted mb-4 ff-secondary">Para pembimbing kegiatan ekstrakurikuler adalah guru-guru berdedikasi yang siap membantu siswa mengembangkan minat dan bakat di luar kelas.</p>
                        </div>
                    </div>
                </div>

                <!-- Team Grid -->
                <div class="row">
                    <!-- 1 -->
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="card-body text-center p-4">
                                <div class="avatar-xl mx-auto mb-4 position-relative">
                                    <img src="{{asset('assets')}}/images/users/avatar-2.jpg" alt="" class="img-fluid rounded-circle">
                                </div>
                                <h5 class="mb-1">Bu Siti Rahma</h5>
                                <p class="text-muted mb-0 ff-secondary">Pengampu Pramuka</p>
                            </div>
                        </div>
                    </div>
                    <!-- 2 -->
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="card-body text-center p-4">
                                <div class="avatar-xl mx-auto mb-4 position-relative">
                                    <img src="{{asset('assets')}}/images/users/avatar-10.jpg" alt="" class="img-fluid rounded-circle">
                                </div>
                                <h5 class="mb-1">Pak Andi Wijaya</h5>
                                <p class="text-muted mb-0 ff-secondary">Pengampu Futsal</p>
                            </div>
                        </div>
                    </div>
                    <!-- 3 -->
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="card-body text-center p-4">
                                <div class="avatar-xl mx-auto mb-4 position-relative">
                                    <img src="{{asset('assets')}}/images/users/avatar-3.jpg" alt="" class="img-fluid rounded-circle">
                                </div>
                                <h5 class="mb-1">Bu Lina Kartika</h5>
                                <p class="text-muted mb-0 ff-secondary">Pengampu Tari</p>
                            </div>
                        </div>
                    </div>
                    <!-- 4 -->
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="card-body text-center p-4">
                                <div class="avatar-xl mx-auto mb-4 position-relative">
                                    <img src="{{asset('assets')}}/images/users/avatar-8.jpg" alt="" class="img-fluid rounded-circle">
                                </div>
                                <h5 class="mb-1">Pak Budi Santoso</h5>
                                <p class="text-muted mb-0 ff-secondary">Pengampu Robotik</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section> --}}
        <!-- end team -->


        <!-- start pendaftaran ekskul -->
        <section class="section" id="formulir">
            @livewire('formulir-ekstrakurikuler.index')   

        </section>
        <!-- end pendaftaran ekskul -->

        <!-- Start footer -->
        <footer class="custom-footer bg-dark py-5 position-relative">
            <div class="container">
                <div class="row">
                    <!-- Info Sekolah -->
                    <div class="col-lg-4 mt-4">
                        <div>
                            <div>
                                <img src="{{asset('assets')}}/logo/hero.png" alt="logo light" height="50">
                            </div>
                            <div class="mt-4 fs-13 text-white">
                                <p>SD Al Hidayah Karanggede</p>
                                <p class="ff-secondary text-muted">
                                    Tempat untuk mengembangkan potensi siswa melalui kegiatan ekstrakurikuler yang mendukung karakter, keterampilan, dan kreativitas.
                                </p>
                                <p class="text-muted mb-0">
                                    <strong>Alamat:</strong><br>
                                    Dusun No.2 RT.04/RW.01, Dusun 2, Kebonan, Karanggede,<br>
                                    Boyolali, Jawa Tengah 57381
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Navigasi -->
                    <div class="col-lg-7 ms-lg-auto">
                        <div class="row">
                            <div class="col-sm-4 mt-4">
                                <h5 class="text-white mb-0">Profil</h5>
                                <div class="text-muted mt-3">
                                    <ul class="list-unstyled ff-secondary footer-list fs-14">
                                        <li><a href="#">Tentang Sekolah</a></li>
                                        <li><a href="#">Visi & Misi</a></li>
                                        <li><a href="#">Ekstrakurikuler</a></li>
                                        <li><a href="#">Kontak Kami</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-sm-4 mt-4">
                                <h5 class="text-white mb-0">Informasi</h5>
                                <div class="text-muted mt-3">
                                    <ul class="list-unstyled ff-secondary footer-list fs-14">
                                        <li><a href="#">Jadwal Ekstrakurikuler</a></li>
                                        <li><a href="#">Alur Pendaftaran</a></li>
                                        <li><a href="#">Galeri Kegiatan</a></li>
                                        <li><a href="#">Berita Terbaru</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-sm-4 mt-4">
                                <h5 class="text-white mb-0">Layanan</h5>
                                <div class="text-muted mt-3">
                                    <ul class="list-unstyled ff-secondary footer-list fs-14">
                                        <li><a href="#">FAQ</a></li>
                                        <li><a href="#">Hubungi Kami</a></li>
                                        <li><a href="#">Form Pendaftaran</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Copyright & Sosmed -->
                <div class="row text-center text-sm-start align-items-center mt-5">
                    <div class="col-sm-6">
                        <div>
                            <p class="copy-rights mb-0 text-muted">
                                <script> document.write(new Date().getFullYear()) </script> © SD Al Hidayah Karanggede - All Rights Reserved
                            </p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-sm-end mt-3 mt-sm-0">
                            <ul class="list-inline mb-0 footer-social-link">
                                <li class="list-inline-item">
                                    <a href="#" class="avatar-xs d-block" title="Facebook">
                                        <div class="avatar-title rounded-circle bg-light text-dark">
                                            <i class="ri-facebook-fill"></i>
                                        </div>
                                    </a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="#" class="avatar-xs d-block" title="Instagram">
                                        <div class="avatar-title rounded-circle bg-light text-dark">
                                            <i class="ri-instagram-line"></i>
                                        </div>
                                    </a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="#" class="avatar-xs d-block" title="YouTube">
                                        <div class="avatar-title rounded-circle bg-light text-dark">
                                            <i class="ri-youtube-fill"></i>
                                        </div>
                                    </a>
                                </li>
                                <!-- Tambahkan jika punya link sosial lainnya -->
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end footer -->


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