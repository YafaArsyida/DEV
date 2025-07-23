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

        <!-- latest jquery-->
        <script src="{{asset('assets')}}/js/jquery-3.5.1.min.js"></script>

         <!-- aos css -->
        <link rel="stylesheet" href="{{asset('assets')}}/libs/aos/aos.css" />

        <!-- DataTables -->
        <link href="{{asset('assets')}}/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets')}}/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    </head>
<body>
    
    @yield("table")
    
    <!--datatable js-->
    <script src="{{asset('assets')}}/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{asset('assets')}}/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

    <script src="{{asset('assets')}}/js/pages/datatables.init.js"></script>

    <!-- crm leads init -->
    <script src="{{asset('assets')}}/js/pages/crm-leads.init.js"></script>

    <!-- aos js -->
    <script src="{{asset('assets')}}/libs/aos/aos.js"></script>
    <!-- prismjs plugin -->
    <script src="{{asset('assets')}}/libs/prismjs/prism.js"></script>
    <!-- animation init -->
    <script src="{{asset('assets')}}/js/pages/animation-aos.init.js"></script>
</body>
</html>