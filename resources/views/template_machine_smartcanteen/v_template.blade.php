<!doctype html>
<html data-layout="vertical" data-topbar="light" data-sidebar="light" data-bs-theme="light" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

    <head>
        @include('template_machine.v_head')
    </head>

    <body>
        <!-- Begin page -->
        <div id="layout-wrapper">

            <header id="page-topbar">
                <div class="layout-width" style="max-width: 100%">
                    <div class="navbar-header">
                        <div class="d-flex">
                            <!-- LOGO -->
                            <div class="navbar-brand-box horizontal-logo">
                                <a href="index.html" class="logo logo-dark">
                                    <span class="logo-sm">
                                        <img src="{{asset('assets')}}/logo/atas.jpg" alt="" height="22">
                                    </span>
                                    <span class="logo-lg">
                                        <img src="{{asset('assets')}}/logo/atas.jpg" alt="" height="17">
                                    </span>
                                </a>

                                <a href="index.html" class="logo logo-light">
                                    <span class="logo-sm">
                                        <img src="{{asset('assets')}}/logo/atas.jpg" alt="" height="22">
                                    </span>
                                    <span class="logo-lg">
                                        <img src="{{asset('assets')}}/logo/atas.jpg" alt="" height="17">
                                    </span>
                                </a>
                            </div>

                            <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger shadow-none" id="topnav-hamburger-icon">
                                <span class="hamburger-icon">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </span>
                            </button>

                            <!-- App Search-->
                            <div class="app-search d-none d-md-flex header-item">
                                <div class="position-relative">
                                     {{-- <div class="row">
                                        <div class="col-lg-12">
                                            <div class="card overflow-hidden">
                                                <div class="card-body bg-success-subtle text-success fw-semibold d-flex">
                                                    <marquee class="fs-14">
                                                        NFT art is a digital asset that is collectable, unique, and non-transferrable, Cortes explained. Every NFT is unique in it's creative design and cannot be duplicated, making them limited and rare. NFTs get their value because the transaction proves ownership of the art.
                                                    </marquee>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end col-->
                                    </div> --}}
                                    <h3 class='mb-0'>SmartCanteen {{ config('app.name') }}</h3>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center">

                            @livewire('parameter.smart-canteen')   

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
                                        <div class="rounded-circle header-profile-user bg-primary text-white d-flex align-items-center justify-content-center fw-bold">
                                            {{ strtoupper(substr(Auth::user()->nama ?? 'G', 0, 1)) }}
                                        </div>
                                        <span class="text-start ms-xl-2">
                                            <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">{{ Auth::user()->nama ?? 'Guest' }}</span>
                                            <span class="d-none d-xl-block ms-1 fs-12 user-name-sub-text">{{ Auth::user()->peran ?? 'Unknown Role' }}</span>
                                        </span>
                                    </span>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <!-- item-->
                                    <h6 class="dropdown-header">Welcome {{ Auth::user()->nama ?? 'Guest' }}!</h6>
                                    <a class="dropdown-item" href="pages-profile.html"><i class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Profile</span></a>
                                    <a class="dropdown-item" href="{{ route('logout') }}" 
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i>
                                        <span class="align-middle" data-key="t-logout">Logout</span>
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- ========== Left Sidebar Start ========== -->
            @include('template_machine_smartcanteen.v_sidebar')
            <!-- Left Sidebar End -->

            <!-- Vertical Overlay-->
            <div class="vertical-overlay"></div>

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">
                @yield('content') {{-- section --}}

                <!-- End Page-content -->
                {{-- @include('template_machine.v_footer')  --}}
                {{-- sxtends --}}
            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->



        <!--start back-to-top-->
        <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
            <i class="ri-arrow-up-line"></i>
        </button>
        <!--end back-to-top-->

        <!--preloader-->
        <div id="preloader">
            <div id="status">
                <div class="spinner-border text-primary avatar-sm" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>

        <!-- JAVASCRIPT -->
        @include('template_machine.v_script')
        @livewireScripts 
    
        <script>
            // notif
            window.addEventListener('alertify-success', event => {
                alertify.set('notifier', 'position', 'top-right');
                alertify.success(event.detail.message);
            });

            window.addEventListener('alertify-error', event => {
                alertify.set('notifier', 'position', 'top-right');
                alertify.error(event.detail.message);
            });
            // end notif

            window.addEventListener('hide-modal', (event) => {
                let modalId = event.detail.modalId;
                let modal = document.getElementById(modalId);
                if (modal) {
                    let bootstrapModal = bootstrap.Modal.getInstance(modal);
                    if (bootstrapModal) {
                        bootstrapModal.hide();
                    }
                }
            });
            window.addEventListener('show-modal', (event) => {
                let modalId = event.detail.modalId;
                let modal = document.getElementById(modalId);
                if (modal) {
                    let bootstrapModal = new bootstrap.Modal(modal);
                    bootstrapModal.show();
                }
            });
            // modal
            Livewire.on('openNewTab', (url) => {
                setTimeout(function() {
                    window.open(url, '_blank');
                }, 1000);
            });

        </script>   
    </body>
</html>