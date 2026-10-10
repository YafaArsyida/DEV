<!doctype html>
<html data-layout="vertical" data-topbar="light" data-sidebar="light" data-bs-theme="light" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">
    <head>
        @include('template.v_head')
    </head>
    <body>
        <div id="layout-wrapper">
            <header id="page-topbar">
                <div class="layout-width" style="max-width: 100%">
                    <div class="navbar-header">
                        <div class="d-flex align-items-center">
                            <div class="navbar-brand-box horizontal-logo">
                                <a href="{{ route('ppdb.dashboard') }}" class="logo logo-dark">
                                    <span class="logo-sm"><img src="{{ asset('assets/logo/logo.jpg') }}" alt="{{ config('app.name') }}" height="30"></span>
                                    <span class="logo-lg"><img src="{{ asset('assets/logo/logo.jpg') }}" alt="{{ config('app.name') }}" height="40"></span>
                                </a>
                                <a href="{{ route('ppdb.dashboard') }}" class="logo logo-light">
                                    <span class="logo-sm"><img src="{{ asset('assets/logo/logo.jpg') }}" alt="{{ config('app.name') }}" height="30"></span>
                                    <span class="logo-lg"><img src="{{ asset('assets/logo/logo.jpg') }}" alt="{{ config('app.name') }}" height="40"></span>
                                </a>
                            </div>

                            <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger shadow-none" id="topnav-hamburger-icon">
                                <span class="hamburger-icon"><span></span><span></span><span></span></span>
                            </button>

                            <div class="app-search d-none d-md-flex header-item">
                                <div class="position-relative">
                                    <h3 class="mb-0">PPDB {{ config('app.name') }}</h3>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center">
                            <div class="ms-1 header-item d-none d-sm-flex">
                                <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle shadow-none" data-toggle="fullscreen">
                                    <i class='bx bx-fullscreen fs-22'></i>
                                </button>
                            </div>

                            <div class="ms-1 header-item d-none d-sm-flex">
                                <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle light-dark-mode shadow-none">
                                    <i class='bx bx-moon fs-22'></i>
                                </button>
                            </div>

                            <div class="dropdown ms-sm-3 header-item topbar-user">
                                <button type="button" class="btn shadow-none" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="d-flex align-items-center">
                                        <span class="rounded-circle header-profile-user bg-primary text-white d-flex align-items-center justify-content-center fw-bold">
                                            {{ strtoupper(substr(trim(Auth::user()->nama ?? 'Orang Tua'), 0, 1)) }}
                                        </span>
                                        <span class="text-start ms-xl-2">
                                            <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">{{ Auth::user()->nama ?? 'Orang Tua' }}</span>
                                            <span class="d-none d-xl-block ms-1 fs-12 user-name-sub-text">Orang Tua/Wali</span>
                                        </span>
                                    </span>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    {{-- Informasi Pengguna --}}
                                    <h6 class="dropdown-header">
                                        Selamat datang, {{ Auth::user()->nama ?? 'Orang Tua' }}!
                                    </h6>

                                    {{-- Akun --}}
                                    <a class="dropdown-item" href="#">
                                        <i class="ri-user-settings-line text-muted fs-16 align-middle me-1"></i>
                                        <span class="align-middle">Akun</span>
                                    </a>

                                    {{-- Bantuan --}}
                                    <a class="dropdown-item" href="#">
                                        <i class="ri-question-line text-muted fs-16 align-middle me-1"></i>
                                        <span class="align-middle">Bantuan</span>
                                    </a>

                                    <div class="dropdown-divider"></div>

                                    {{-- Logout --}}
                                    <a class="dropdown-item"
                                        href="{{ route('ppdb.logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form-ppdb').submit();">
                                        <i class="ri-logout-box-line text-muted fs-16 align-middle me-1"></i>
                                        <span class="align-middle">Keluar</span>
                                    </a>

                                    <form id="logout-form-ppdb" action="{{ route('ppdb.logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            @include('template_portal_ppdb.v_sidebar')
            <div class="vertical-overlay"></div>

            <div class="main-content">
                @yield('content')
                @include('template.v_footer')
            </div>
        </div>

        <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
            <i class="ri-arrow-up-line"></i>
        </button>
        <div id="preloader">
            <div id="status">
                <div class="spinner-border text-primary avatar-sm" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
        @include('template.v_script')
    </body>
</html>
