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
                                    <h3 class='mb-0'>SD Islam Al Hidayah Karanggede</h3>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center">
                            <div class="dropdown d-md-none topbar-head-dropdown header-item">
                                <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle shadow-none" id="page-header-search-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-search fs-22"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-search-dropdown">
                                    <form class="p-3">
                                        <div class="form-group m-0">
                                            <div class="input-group">
                                                <input type="text" class="form-control" placeholder="Search ..." aria-label="Recipient's username">
                                                <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

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

            <!-- removeNotificationModal -->
            <div id="removeNotificationModal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="NotificationModalbtn-close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mt-2 text-center">
                                <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
                                <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                                    <h4>Are you sure ?</h4>
                                    <p class="text-muted mx-4 mb-0">Are you sure you want to remove this Notification ?</p>
                                </div>
                            </div>
                            <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn w-sm btn-danger" id="delete-notification">Yes, Delete It!</button>
                            </div>
                        </div>

                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- ========== Left Sidebar Start ========== -->
            @include('template_machine.v_sidebar')
            <!-- Left Sidebar End -->

            <!-- Vertical Overlay-->
            <div class="vertical-overlay"></div>

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">
                @yield('content') {{-- section --}}

                <!-- End Page-content -->
                @include('template_machine.v_footer') 
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
                alertify.set('notifier', 'position', 'bottom-right');
                alertify.success(event.detail.message);
            });

            window.addEventListener('alertify-error', event => {
                alertify.set('notifier', 'position', 'bottom-right');
                alertify.error(event.detail.message);
            });
            // end notif

            // modal
            window.addEventListener('hide-create-modal', (event) => {
                let modalId = event.detail.modalId;
                let modal = document.getElementById(modalId);
                if (modal) {
                    let bootstrapModal = bootstrap.Modal.getInstance(modal);
                    if (bootstrapModal) {
                        bootstrapModal.hide();
                    }
                }
            });
            window.addEventListener('hide-edit-modal', (event) => {
                let modalId = event.detail.modalId;
                let modal = document.getElementById(modalId);
                if (modal) {
                    let bootstrapModal = bootstrap.Modal.getInstance(modal);
                    if (bootstrapModal) {
                        bootstrapModal.hide();
                    }
                }
            });
            window.addEventListener('hide-delete-modal', (event) => {
                let modalId = event.detail.modalId;
                let modal = document.getElementById(modalId);
                if (modal) {
                    let bootstrapModal = bootstrap.Modal.getInstance(modal);
                    if (bootstrapModal) {
                        bootstrapModal.hide();
                    }
                }
            });
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