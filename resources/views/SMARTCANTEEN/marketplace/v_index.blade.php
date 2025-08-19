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
        <section class="section" id="ekstrakurikuler">
            <div class="container-fluid">
                <div class="row g-3">
                    <!-- TIK -->
                    <div class="col-lg-8">
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
                </div>
            </div>
        </section>

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