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
    <div class="smartpass-attendance min-vh-100 bg-light d-flex flex-column">
        <header class="border-bottom bg-white">
            <div class="container-fluid px-4 px-xl-5">
                <div class="d-flex align-items-center justify-content-between" style="height: 72px;">
                    {{-- BRAND --}}
                    <div class="d-flex align-items-center gap-3">

                        <div class="avatar-sm">
                            <div class="avatar-title bg-primary-subtle text-primary rounded-3 fs-4">
                                <i class="ri-fingerprint-line"></i>
                            </div>
                        </div>

                        <div>
                            <h5 class="mb-0 fw-semibold text-dark">
                                SmartPass
                            </h5>

                            <small class="text-muted">
                                Presensi Pegawai
                            </small>
                        </div>

                    </div>

                    {{-- HEADER INFO --}}
                    <div class="d-flex align-items-center gap-4">

                        <div class="text-end d-none d-md-block">
                            <div class="fw-semibold text-dark">
                                {{ now()->locale('id')->translatedFormat('l, d F Y') }}
                            </div>
                            <small class="text-muted">
                                Operasional Presensi
                            </small>
                        </div>

                        <div class="vr d-none d-md-block"></div>

                        <div class="text-end">
                            <div id="realtime-clock" class="fw-bold text-dark fs-5">
                                --:--:--
                            </div>
                            <small class="text-muted">
                                WIB
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- =====================================================
            MAIN CONTENT
        ====================================================== --}}
        <main class="flex-grow-1">
            <div class="container-fluid px-3 px-xl-5 py-4">
                <div class="row g-4">
                    {{-- =================================================
                        LEFT : PRESENSI OPERASIONAL
                    ================================================== --}}
                    <div class="col-lg-8">
                        <div class="h-100">
                            {{-- PAGE TITLE --}}
                            <div class="mb-4">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                                        <i class="ri-checkbox-circle-line me-1"></i>
                                        Sistem Aktif
                                    </span>

                                </div>

                                <h3 class="fw-semibold mb-1">
                                    Presensi Pegawai
                                </h3>

                                <p class="text-muted mb-0">
                                    Silakan tap kartu pegawai untuk melakukan presensi.
                                </p>
                            </div>

                            {{-- =================================================
                                STATUS PRESENSI
                            ================================================== --}}
                            <div class="mb-4">

                                <div class="d-flex align-items-center justify-content-between mb-3">

                                    <h6 class="fw-semibold mb-0">
                                        Status Presensi
                                    </h6>

                                    <small class="text-muted">
                                        Status saat ini
                                    </small>

                                </div>


                                <div class="row g-3">
                                    {{-- HADIR --}}
                                    <div class="col-md-4">
                                        <button type="button" class="btn w-100 border-0 rounded-4 text-start p-4 bg-success-subtle text-success">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div>
                                                    <div class="small fw-medium mb-1">
                                                        STATUS
                                                    </div>

                                                    <div class="fs-4 fw-bold">
                                                        Hadir
                                                    </div>
                                                </div>

                                                <div class="avatar-md">
                                                    <div class="avatar-title bg-success text-white rounded-circle fs-4">
                                                        <i class="ri-checkbox-circle-line"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </button>
                                    </div>


                                    {{-- IZIN --}}
                                    <div class="col-md-4">
                                        <button type="button" class="btn w-100 border-0 rounded-4 text-start p-4 bg-warning-subtle text-warning">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div>
                                                    <div class="small fw-medium mb-1">
                                                        STATUS
                                                    </div>

                                                    <div class="fs-4 fw-bold">
                                                        Izin
                                                    </div>
                                                </div>

                                                <div class="avatar-md">
                                                    <div class="avatar-title bg-warning text-white rounded-circle fs-4">
                                                        <i class="ri-time-line"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </button>
                                    </div>


                                    {{-- ALFA --}}
                                    <div class="col-md-4">
                                        <button type="button" class="btn w-100 border-0 rounded-4 text-start p-4 bg-danger-subtle text-danger">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div>
                                                    <div class="small fw-medium mb-1">
                                                        STATUS
                                                    </div>

                                                    <div class="fs-4 fw-bold">
                                                        Alfa
                                                    </div>
                                                </div>

                                                <div class="avatar-md">
                                                    <div class="avatar-title bg-danger text-white rounded-circle fs-4">
                                                        <i class="ri-close-circle-line"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </div>


                            {{-- =================================================
                                PEGAWAI TERAKHIR TAP
                            ================================================== --}}
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

                                <div class="card-body p-4 p-xl-5">

                                    <div class="d-flex align-items-center justify-content-between mb-4">

                                        <div>

                                            <h5 class="fw-semibold mb-1">
                                                Presensi Terakhir
                                            </h5>

                                            <p class="text-muted mb-0">
                                                Pegawai yang baru saja melakukan tap
                                            </p>

                                        </div>

                                        <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                                            <i class="ri-check-line me-1"></i>
                                            Berhasil
                                        </span>

                                    </div>


                                    {{-- EMPLOYEE IDENTITY --}}
                                    <div class="p-4 rounded-4 bg-light-subtle border">

                                        <div class="d-flex align-items-center gap-4">

                                            {{-- FOTO --}}
                                            <div class="flex-shrink-0">

                                                <div
                                                    class="avatar-xl bg-primary-subtle text-primary rounded-circle">

                                                    <div class="avatar-title rounded-circle bg-primary-subtle text-primary fs-2 fw-semibold">
                                                        BS
                                                    </div>

                                                </div>

                                            </div>


                                            {{-- DATA --}}
                                            <div class="flex-grow-1">

                                                <div class="d-flex align-items-center gap-2 mb-1">

                                                    <h4 class="fw-bold mb-0">
                                                        Budi Santoso
                                                    </h4>

                                                    <span class="badge bg-success-subtle text-success rounded-pill">
                                                        Hadir
                                                    </span>

                                                </div>

                                                <div class="text-muted mb-3">
                                                    Kepala Tata Usaha
                                                </div>


                                                <div class="row g-3">

                                                    <div class="col-sm-4">

                                                        <small class="text-muted d-block">
                                                            Nomor Pegawai
                                                        </small>

                                                        <span class="fw-semibold">
                                                            PGW-001
                                                        </span>

                                                    </div>

                                                    <div class="col-sm-4">

                                                        <small class="text-muted d-block">
                                                            Nomor Kartu
                                                        </small>

                                                        <span class="fw-semibold">
                                                            0000123456
                                                        </span>

                                                    </div>

                                                    <div class="col-sm-4">

                                                        <small class="text-muted d-block">
                                                            Waktu Tap
                                                        </small>

                                                        <span class="fw-semibold">
                                                            08:02 WIB
                                                        </span>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            {{-- =================================================
                                NOMOR KARTU
                            ================================================== --}}
                            <div class="card border-0 shadow-sm rounded-4 mb-4">
                                <div class="card-body p-4 p-xl-5">
                                    <label class="form-label fw-semibold text-dark mb-2">
                                        Nomor Kartu
                                    </label>

                                    <div class="position-relative">
                                        <div class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted">
                                            <i class="ri-bank-card-line fs-4"></i>
                                        </div>

                                        <input
                                            type="text"
                                            class="form-control form-control-lg rounded-3 ps-5 py-3 fs-4 fw-semibold text-center"
                                            placeholder="Tap kartu pegawai..."
                                            wire:model.defer="nomorKartu"
                                            autofocus
                                        >
                                    </div>

                                    <div class="text-center mt-3">
                                        <small class="text-muted">
                                            <i class="ri-information-line me-1"></i>
                                            Tempelkan kartu pada reader presensi
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    {{-- =================================================
                        RIGHT : HISTORY
                    ================================================== --}}
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 h-100">
                            <div class="card-header bg-transparent border-bottom px-4 py-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h5 class="mb-1 fw-semibold">
                                            Histori Presensi
                                        </h5>

                                        <small class="text-muted">
                                            Aktivitas presensi hari ini
                                        </small>
                                    </div>

                                    <span class="badge bg-primary-subtle text-primary rounded-pill">
                                        Live
                                    </span>
                                </div>
                            </div>


                            <div class="card-body p-0">
                                <div class="attendance-history">
                                    {{-- ITEM --}}
                                    <div class="px-4 py-3 border-bottom">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-sm flex-shrink-0">
                                                <div class="avatar-title bg-success-subtle text-success rounded-circle">
                                                    <i class="ri-check-line"></i>
                                                </div>
                                            </div>

                                            <div class="flex-grow-1 min-width-0">
                                                <h6 class="mb-1 fw-semibold text-truncate">
                                                    Budi Santoso
                                                </h6>

                                                <small class="text-muted">
                                                    Kepala Tata Usaha
                                                </small>
                                            </div>

                                            <div class="text-end">
                                                <span class="badge bg-success-subtle text-success rounded-pill mb-1">
                                                    Hadir
                                                </span>

                                                <small class="text-muted d-block">
                                                    08:02
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- FOOTER --}}
                            <div class="card-footer bg-transparent border-top px-4 py-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <small class="text-muted">
                                        Total presensi hari ini
                                    </small>

                                    <span class="fw-bold">
                                        124 Pegawai
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>


        {{-- =====================================================
            FOOTER
        ====================================================== --}}
        <footer class="border-top bg-white">
            <div class="container-fluid px-4 px-xl-5">
                <div class="d-flex align-items-center justify-content-between py-3">
                    <small class="text-muted">
                        © {{ date('Y') }} SmartPass
                    </small>

                    <small class="text-muted">
                        Crafted with <i class="mdi mdi-heart text-danger"></i> by TemanSekolah
                    </small>
                </div>
            </div>
        </footer>

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