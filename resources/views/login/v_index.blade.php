<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>

    <meta charset="utf-8" />
    <title>TemanSekolah | Advance - Sistem Administrasi Terintegrasi untuk Sekolah</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Sistem Administrasi Terintegrasi untuk Sekolah" name="description" />
    <meta content="ManekaromaTeknologi" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('assets')}}/images/favicon.ico">

    <!-- Layout config Js -->
    <script src="{{asset('assets')}}/js/layout.js"></script>
    <!-- Bootstrap Css -->
    <link href="{{asset('assets')}}/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{asset('assets')}}/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{asset('assets')}}/css/app.min.css" rel="stylesheet" type="text/css" />

</head>

<body>

     <!-- auth-page wrapper -->
    <div class="auth-page-wrapper auth-bg-cover py-5 d-flex justify-content-center align-items-center min-vh-100">
        <div class="bg-overlay"></div>

        <!-- auth page content -->
        <div class="auth-page-content overflow-hidden pt-lg-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-10">

                        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

                            <div class="row g-0">

                                <!-- ========================= -->
                                <!-- LEFT PANEL -->
                                <!-- ========================= -->
                                <div class="col-lg-6">
                                    <div class="p-lg-5 p-4 bg-primary h-100">
                                        <div class="bg-overlay"></div>

                                        <div class="position-relative h-100 d-flex flex-column text-white">

                                            <div>
                                                <h2 class="fw-bold text-white mb-1">
                                                    TemanSekolah
                                                </h2>

                                                <p class="fs-12 text-white-75 mb-5">
                                                    Sistem Administrasi Terintegrasi 
                                                </p>
                                            </div>

                                            <div class="my-auto">

                                                <div class="text-center">
                                                    <div class="avatar-xl mx-auto mb-4">
                                                        <div class="avatar-title rounded-circle bg-white bg-opacity-10">
                                                            <i class="ri-school-line display-4 text-white"></i>
                                                        </div>
                                                    </div>

                                                    <h3 class="text-white fw-semibold">
                                                        SD Islam Al Hidayah Karanggede
                                                    </h3>
                                                </div>

                                            </div>

                                            <div class="mt-auto">
                                                <div class="mb-3">
                                                    <i class="ri-double-quotes-l display-4 text-success"></i>
                                                </div>

                                                <div id="qoutescarouselIndicators" class="carousel slide" data-bs-ride="carousel">
                                                    <div class="carousel-indicators">
                                                        <button type="button" data-bs-target="#qoutescarouselIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                                        <button type="button" data-bs-target="#qoutescarouselIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                                                        <button type="button" data-bs-target="#qoutescarouselIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                                                    </div>
                                                    <div class="carousel-inner text-center text-white-50 pb-5">
                                                        <div class="carousel-item active">
                                                            <p class="fs-15 fst-italic">
                                                                "Kelola data siswa, keuangan, dan administrasi sekolah secara lebih cepat, aman, dan terintegrasi dalam satu platform."
                                                            </p>
                                                        </div>

                                                        <div class="carousel-item">
                                                            <p class="fs-15 fst-italic">
                                                                "Dirancang untuk mendukung pelayanan administrasi yang lebih efisien, akurat, dan mudah digunakan oleh seluruh civitas sekolah."
                                                            </p>
                                                        </div>

                                                        <div class="carousel-item">
                                                            <p class="fs-15 fst-italic">
                                                                "TemanSekolah X membantu sekolah bekerja lebih produktif melalui sistem digital yang modern, terpercaya, dan berkelanjutan."
                                                            </p>
                                                        </div>

                                                    </div>
                                                </div>
                                                <!-- end carousel -->
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ========================= -->
                                <!-- RIGHT PANEL -->
                                <!-- ========================= -->
                                <div class="col-lg-6 my-auto">

                                    <div class="p-lg-5 p-4">

                                        <div class="mb-4">
                                            <h3 class="fw-bold mb-2">
                                                Masuk
                                            </h3>

                                            <p class="text-muted mb-0">
                                                Silakan masuk menggunakan akun Anda.
                                            </p>
                                        </div>

                                        @if (session()->has('loginError'))
                                            <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                                                <i class="ri-error-warning-line me-2"></i>
                                                {{ session('loginError') }}

                                                <button type="button"
                                                        class="btn-close"
                                                        data-bs-dismiss="alert">
                                                </button>
                                            </div>
                                        @endif

                                        <form action="{{ route('login.authenticate') }}" method="POST">
                                            @csrf

                                            <div class="mb-3">

                                                <label class="form-label">
                                                    Email / Username
                                                </label>

                                                <input
                                                    id="email"
                                                    type="text"
                                                    name="email"
                                                    value="{{ old('email') }}"
                                                    autofocus
                                                    placeholder="Masukkan email atau username"
                                                    class="form-control @error('email') is-invalid @enderror">

                                                @error('email')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror

                                            </div>

                                            <div class="mb-4">

                                                <label class="form-label">
                                                    Password
                                                </label>

                                                <div class="position-relative auth-pass-inputgroup">

                                                    <input
                                                        type="password"
                                                        name="password"
                                                        id="password-input"
                                                        placeholder="Masukkan password"
                                                        class="form-control pe-5 password-input @error('password') is-invalid @enderror">

                                                    <button
                                                        class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted shadow-none password-addon"
                                                        type="button"
                                                        id="password-addon">

                                                        <i class="ri-eye-fill align-middle"></i>

                                                    </button>

                                                    @error('password')
                                                        <div class="invalid-feedback d-block">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror

                                                </div>

                                            </div>

                                            <div class="d-grid">

                                                <button
                                                    class="btn btn-primary btn-lg"
                                                    type="submit">

                                                    <i class="ri-login-box-line me-1"></i>

                                                    Masuk

                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- end auth page content -->

        <!-- footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <p class="mb-0 text-muted">&copy;
                                <script>document.write(new Date().getFullYear())</script> TemanSekolah. Crafted with <i class="mdi mdi-heart text-danger"></i> by Manekaroma Teknologi Nusantara
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>
    <!-- end auth-page-wrapper -->

    <!-- JAVASCRIPT -->
    <script src="{{asset('assets')}}/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('assets')}}/libs/simplebar/simplebar.min.js"></script>
    <script src="{{asset('assets')}}/libs/node-waves/waves.min.js"></script>
    <script src="{{asset('assets')}}/libs/feather-icons/feather.min.js"></script>
    <script src="{{asset('assets')}}/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="{{asset('assets')}}/js/plugins.js"></script>

    <!-- particles js -->
    <script src="{{asset('assets')}}/libs/particles.js/particles.js"></script>
    <!-- particles app js -->
    <script src="{{asset('assets')}}/js/pages/particles.app.js"></script>
    <!-- password-addon init -->
    <script src="{{asset('assets')}}/js/pages/password-addon.init.js"></script>
</body>

</html>