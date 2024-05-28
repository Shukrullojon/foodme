<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FOOD | ME</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset("admin_lte_3.2.0/plugins/fontawesome-free/css/all.min.css") }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{ asset("admin_lte_3.2.0/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css") }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset("admin_lte_3.2.0/plugins/icheck-bootstrap/icheck-bootstrap.min.css") }}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ asset("admin_lte_3.2.0/plugins/jqvmap/jqvmap.min.css") }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset("admin_lte_3.2.0/dist/css/adminlte.min.css") }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset("admin_lte_3.2.0/plugins/overlayScrollbars/css/OverlayScrollbars.min.css") }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset("admin_lte_3.2.0/plugins/daterangepicker/daterangepicker.css") }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset("admin_lte_3.2.0/plugins/summernote/summernote-bs4.min.css") }}">
</head>
<body class="hold-transition sidebar-mini layout-fixed">

<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle"><i class="fas fa-user"></i>
                    @if(auth()->user())
                        {{ auth()->user()->name }}
                    @endif
                </a>
                <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow" style="left: 0px; right: inherit;">
                    <li>
                        <a href="{{ route("profile") }}" class="nav-link">
                            <i class="fa fa-user"></i> Profile
                        </a>
                    </li>

                    <li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <a href="#" class="nav-link" role="button" onclick="
                                    event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i>Logout
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    @include('layouts.sidebar')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="margin-top: 10px">
        @yield('content')
    </div>
    <!-- /.content-wrapper -->


    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset("admin_lte_3.2.0/plugins/jquery/jquery.min.js")}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset("admin_lte_3.2.0/plugins/jquery-ui/jquery-ui.min.js")}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{ asset("admin_lte_3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js")}}"></script>
<!-- ChartJS -->
<script src="{{ asset("admin_lte_3.2.0/plugins/chart.js/Chart.min.js")}}"></script>
<!-- Sparkline -->
<script src="{{ asset("admin_lte_3.2.0/plugins/sparklines/sparkline.js")}}"></script>
<!-- JQVMap -->
<script src="{{ asset("admin_lte_3.2.0/plugins/jqvmap/jquery.vmap.min.js")}}"></script>
<script src="{{ asset("admin_lte_3.2.0/plugins/jqvmap/maps/jquery.vmap.usa.js")}}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset("admin_lte_3.2.0/plugins/jquery-knob/jquery.knob.min.js")}}"></script>
<!-- daterangepicker -->
<script src="{{ asset("admin_lte_3.2.0/plugins/moment/moment.min.js")}}"></script>
<script src="{{ asset("admin_lte_3.2.0/plugins/daterangepicker/daterangepicker.js")}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset("admin_lte_3.2.0/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js")}}"></script>
<!-- Summernote -->
<script src="{{ asset("admin_lte_3.2.0/plugins/summernote/summernote-bs4.min.js")}}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset("admin_lte_3.2.0/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js")}}"></script>
<!-- AdminLTE App -->
<script src="{{ asset("admin_lte_3.2.0/dist/js/adminlte.js")}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset("admin_lte_3.2.0/dist/js/demo.js")}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ asset("admin_lte_3.2.0/dist/js/pages/dashboard.js")}}"></script>
</body>
</html>
