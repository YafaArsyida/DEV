<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Datatables | Velzon - Admin & Dashboard Template</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesbrand" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('assets')}}/images/favicon.ico">
        <!-- Icons Css -->
        <link href="{{asset('assets')}}/css/icons.min.css" rel="stylesheet" type="text/css" />
        <!-- Feather icon-->
        <link rel="stylesheet" type="text/css" href="{{asset('assets')}}/css/feather-icon.css">
        <!-- latest jquery-->
        <script src="{{asset('assets')}}/js/jquery-3.5.1.min.js"></script>

         <!-- aos css -->
        <link rel="stylesheet" href="{{asset('assets')}}/libs/aos/aos.css" />        
    </head>
<body>
    
    @yield("table")
    
    
    <script src="{{asset('assets')}}/libs/feather-icons/feather.min.js"></script>
    <script src="{{asset('assets')}}/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <!--datatable js-->
    <script src="{{asset('assets')}}/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{asset('assets')}}/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

    <script src="{{asset('assets')}}/js/pages/datatables.init.js"></script>

    <!-- aos js -->
    <script src="{{asset('assets')}}/libs/aos/aos.js"></script>
    <!-- prismjs plugin -->
    <script src="{{asset('assets')}}/libs/prismjs/prism.js"></script>
    <!-- animation init -->
    <script src="{{asset('assets')}}/js/pages/animation-aos.init.js"></script>
</body>
</html>