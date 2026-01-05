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

    <!-- auth-page wrapper -->
    <div class="auth-page-wrapper auth-bg-cover py-5 d-flex justify-content-center align-items-center min-vh-100">
        <div class="bg-overlay"></div>
        <!-- auth-page content -->
        <div class="auth-page-content overflow-hidden pt-lg-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card overflow-hidden m-0">
                            @livewire('smart-pass.presensi-pegawai.index')
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->

                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->

        <!-- footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <p class="mb-0">&copy;
                                <script>
                                    document.write(new Date().getFullYear())
                                </script> SmartPass. Crafted with <i class="mdi mdi-heart text-danger"></i> by TemanSekolah
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
    
    </script>
</body>

</html>