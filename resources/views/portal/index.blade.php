<!doctype html>
<html lang="en" data-layout="semibox" data-sidebar-visibility="show" data-topbar="light" data-sidebar="light"
    data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>

    <meta charset="utf-8" />
    <title>TemanSekolah | SmartPass</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
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
    <!-- custom Css-->
    <link href="{{asset('assets')}}/css/custom.min.css" rel="stylesheet" type="text/css" />

    <!-- alertifyjs Css -->
    <link href="{{asset('assets')}}/libs/alertifyjs/build/css/alertify.min.css" rel="stylesheet" type="text/css" />
    
    <!-- alertifyjs default themes  Css -->
    <link href="{{asset('assets')}}/libs/alertifyjs/build/css/themes/default.min.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <div class="container-fluid">

        {{-- HEADER --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body px-4 py-3">
                <div class="d-flex align-items-center justify-content-between">

                    {{-- LOGO / NAMA APLIKASI --}}
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar-sm">
                            <div class="avatar-title bg-primary-subtle text-primary rounded-3 fs-4">
                                <i class="ri-school-line"></i>
                            </div>
                        </div>

                        <div>
                            <h5 class="mb-0 fw-bold text-dark">
                                TemanSekolah
                            </h5>
                            <small class="text-muted">
                                Portal Administrasi Sekolah
                            </small>
                        </div>
                    </div>

                    {{-- USER --}}
                    <div class="d-flex align-items-center gap-2">
                        <div class="text-end d-none d-sm-block">
                            <div class="fw-semibold text-dark">
                                {{ auth()->user()->nama ?? 'Admin' }}
                            </div>
                            <small class="text-muted">
                                {{ auth()->user()->peran ?? 'Administrator' }}
                            </small>
                        </div>

                        <div class="avatar-sm">
                            <div class="avatar-title bg-light text-primary rounded-circle">
                                <i class="ri-user-line fs-5"></i>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        {{-- WELCOME --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4 p-lg-5">

                <div class="row align-items-center">

                    <div class="col-lg-8">
                        <div class="mb-3">
                            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                Portal Sekolah
                            </span>
                        </div>

                        <h1 class="fw-bold text-dark mb-2">
                            Selamat Datang di
                            <span class="text-primary">TemanSekolah</span>
                        </h1>

                        <p class="text-muted fs-16 mb-0">
                            Portal Digital Terpadu Sekolah
                        </p>

                        <p class="text-muted mt-2 mb-0">
                            Satu tempat untuk mengelola seluruh administrasi
                            sekolah secara terintegrasi.
                        </p>
                    </div>

                    <div class="col-lg-4 d-none d-lg-block text-end">
                        <i class="ri-building-4-line text-primary opacity-25"
                            style="font-size: 120px;"></i>
                    </div>

                </div>

            </div>
        </div>


        {{-- APPLICATION --}}
        <div class="mb-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h5 class="fw-bold mb-1">
                        Aplikasi Sekolah
                    </h5>

                    <p class="text-muted mb-0">
                        Pilih modul yang ingin Anda gunakan.
                    </p>
                </div>
            </div>


            <div class="row g-4">
                {{-- AKADEMIK --}}
                <div class="col-xl-4 col-md-6">
                    <a href="{{ route('akademik.dashboard') }}"
                        class="text-decoration-none">

                        <div class="card border-0 shadow-sm rounded-4 h-100 application-card">

                            <div class="card-body p-4">

                                <div class="d-flex justify-content-between align-items-start mb-4">

                                    <div class="avatar-md">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-3 fs-4">
                                            <i class="ri-graduation-cap-line"></i>
                                        </div>
                                    </div>

                                    <i class="ri-arrow-right-line text-muted fs-5"></i>

                                </div>

                                <h5 class="fw-bold text-dark mb-2">
                                    Akademik
                                </h5>

                                <p class="text-muted mb-0">
                                    Kelola data siswa, kelas, tahun ajaran,
                                    dan administrasi akademik sekolah.
                                </p>

                            </div>

                        </div>

                    </a>

                </div>

                {{-- KEUANGAN --}}
                <div class="col-xl-4 col-md-6">
                    <a href="{{ route('keuangan.dashboard') }}"
                        class="text-decoration-none">
                        <div class="card border-0 shadow-sm rounded-4 h-100 application-card">
                            <div class="card-body p-4">

                                <div class="d-flex justify-content-between align-items-start mb-4">
                                    <div class="avatar-md">
                                        <div class="avatar-title bg-success-subtle text-success rounded-3 fs-4">
                                            <i class="ri-wallet-3-line"></i>
                                        </div>
                                    </div>

                                    <i class="ri-arrow-right-line text-muted fs-5"></i>
                                </div>

                                <h5 class="fw-bold text-dark mb-2">
                                    Keuangan
                                </h5>

                                <p class="text-muted mb-0">
                                    Kelola tagihan, transaksi, laporan,
                                    dan administrasi keuangan sekolah.
                                </p>

                            </div>
                        </div>
                    </a>
                </div>


                {{-- JURNAL --}}
                <div class="col-xl-4 col-md-6">
                    <a href="{{ route('keuangan.dashboard') }}"
                        class="text-decoration-none">
                        <div class="card border-0 shadow-sm rounded-4 h-100 application-card">
                            <div class="card-body p-4">

                                <div class="d-flex justify-content-between align-items-start mb-4">
                                    <div class="avatar-md">
                                        <div class="avatar-title bg-warning-subtle text-warning rounded-3 fs-4">
                                            <i class="ri-booklet-line"></i>
                                        </div>
                                    </div>

                                    <i class="ri-arrow-right-line text-muted fs-5"></i>
                                </div>

                                <h5 class="fw-bold text-dark mb-2">
                                    Jurnal
                                </h5>

                                <p class="text-muted mb-0">
                                    Kelola jurnal kegiatan dan pencatatan
                                    administrasi sekolah.
                                </p>

                            </div>
                        </div>
                    </a>
                </div>


                {{-- PERPUSTAKAAN --}}
                <div class="col-xl-4 col-md-6">
                    <a href="{{ route('keuangan.dashboard') }}"
                        class="text-decoration-none">
                        <div class="card border-0 shadow-sm rounded-4 h-100 application-card">
                            <div class="card-body p-4">

                                <div class="d-flex justify-content-between align-items-start mb-4">
                                    <div class="avatar-md">
                                        <div class="avatar-title bg-info-subtle text-info rounded-3 fs-4">
                                            <i class="ri-book-2-line"></i>
                                        </div>
                                    </div>

                                    <i class="ri-arrow-right-line text-muted fs-5"></i>
                                </div>

                                <h5 class="fw-bold text-dark mb-2">
                                    Perpustakaan
                                </h5>

                                <p class="text-muted mb-0">
                                    Kelola koleksi buku, anggota,
                                    peminjaman, dan pengembalian.
                                </p>

                            </div>
                        </div>
                    </a>
                </div>


                {{-- KOPERASI --}}
                <div class="col-xl-4 col-md-6">
                    <a href="{{ route('keuangan.dashboard') }}"
                        class="text-decoration-none">
                        <div class="card border-0 shadow-sm rounded-4 h-100 application-card">
                            <div class="card-body p-4">

                                <div class="d-flex justify-content-between align-items-start mb-4">
                                    <div class="avatar-md">
                                        <div class="avatar-title bg-danger-subtle text-danger rounded-3 fs-4">
                                            <i class="ri-shopping-cart-2-line"></i>
                                        </div>
                                    </div>

                                    <i class="ri-arrow-right-line text-muted fs-5"></i>
                                </div>

                                <h5 class="fw-bold text-dark mb-2">
                                    Koperasi
                                </h5>

                                <p class="text-muted mb-0">
                                    Kelola produk, pembelian,
                                    penjualan, dan aktivitas koperasi.
                                </p>

                            </div>
                        </div>
                    </a>
                </div>


                {{-- PRESENSI --}}
                <div class="col-xl-4 col-md-6">
                    <a href="{{ route('keuangan.dashboard') }}"
                        class="text-decoration-none">
                        <div class="card border-0 shadow-sm rounded-4 h-100 application-card">
                            <div class="card-body p-4">

                                <div class="d-flex justify-content-between align-items-start mb-4">
                                    <div class="avatar-md">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-3 fs-4">
                                            <i class="ri-time-line"></i>
                                        </div>
                                    </div>

                                    <i class="ri-arrow-right-line text-muted fs-5"></i>
                                </div>

                                <h5 class="fw-bold text-dark mb-2">
                                    Presensi
                                </h5>

                                <p class="text-muted mb-0">
                                    Kelola presensi siswa dan pegawai
                                    secara terintegrasi.
                                </p>

                            </div>
                        </div>
                    </a>
                </div>


                {{-- KEPEGAWAIAN --}}
                <div class="col-xl-4 col-md-6">
                    <a href="{{ route('keuangan.dashboard') }}"
                        class="text-decoration-none">
                        <div class="card border-0 shadow-sm rounded-4 h-100 application-card">
                            <div class="card-body p-4">

                                <div class="d-flex justify-content-between align-items-start mb-4">
                                    <div class="avatar-md">
                                        <div class="avatar-title bg-secondary-subtle text-secondary rounded-3 fs-4">
                                            <i class="ri-team-line"></i>
                                        </div>
                                    </div>

                                    <i class="ri-arrow-right-line text-muted fs-5"></i>
                                </div>

                                <h5 class="fw-bold text-dark mb-2">
                                    Kepegawaian
                                </h5>

                                <p class="text-muted mb-0">
                                    Kelola data pegawai, administrasi,
                                    dan informasi kepegawaian.
                                </p>

                            </div>
                        </div>
                    </a>
                </div>

            </div>
        </div>

    </div>
    
    <!-- JAVASCRIPT -->
    <script src="{{asset('assets')}}/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('assets')}}/libs/simplebar/simplebar.min.js"></script>
    <script src="{{asset('assets')}}/libs/node-waves/waves.min.js"></script>
    <script src="{{asset('assets')}}/libs/feather-icons/feather.min.js"></script>
    <script src="{{asset('assets')}}/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="{{asset('assets')}}/js/plugins.js"></script>
    <!-- alertifyjs js -->
    <script src="{{asset('assets')}}/libs/alertifyjs/build/alertify.min.js"></script>
    <!-- validation init -->
    <script src="{{asset('assets')}}/js/pages/form-validation.init.js"></script>
    <!-- password create init -->
    <script src="{{asset('assets')}}/js/pages/passowrd-create.init.js"></script>
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
        // modal
        Livewire.on('openNewTab', (url) => {
            setTimeout(function() {
                window.open(url, '_blank');
            }, 1000);
        });

        function updateClock() {
            const now = new Date();

            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');

            document.getElementById('realtime-clock').textContent =
                `${hours}:${minutes}:${seconds}`;
        }

        updateClock();
        setInterval(updateClock, 1000);
    
    </script>
</body>

</html>
