<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Управление подписками</title>

    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/font-awesome-4.7.0/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
          href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ asset('plugins/jqvmap/jqvmap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
    <!-- JQuery Confirm-->
    <link rel="stylesheet" href="{{ asset('plugins/jquery-confirm-v3.3.4/css/jquery-confirm.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/DataTables-1-12-1/datatables.min.css') }}">
    <!-- BS Stepper -->
    <link rel="stylesheet" href="{{ asset('plugins/bs-stepper/css/bs-stepper.min.css') }}">
    <!-- Checkbox iOS -->
    <link rel="stylesheet" href="{{ asset('plugins/checkbox-ios/checkbox-ios.css') }}">
    <!-- JQuery FloatingScroll -->
    <link rel="stylesheet" href="{{ asset('plugins/floatingscroll/jquery.floatingscroll.css') }}">
    <!-- Our custom css -->
    <link rel="stylesheet" href="{{ assetVersioned('dist/css/custom.css') }}">
    <!-- Custom CSS for current page -->
    @yield('pageStyle')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="{{ asset('dist/img/BBLogo.png') }}" alt="BBLogo" height="60"
             width="60">
    </div>

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <div class="col-12 d-flex justify-content-between">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link pl-0" data-widget="pushmenu" href="#" role="button"><i class="fa fa-bars" aria-hidden="true"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('admin.subscribes.index') }}" class="nav-link">Подписки</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('admin.subscribers.index') }}" class="nav-link">Подписчики</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('admin.subRequests.index', 'allOpened') }}" class="nav-link">Заявки</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="post">
                        @csrf
                        <button class="btn btn-outline-primary exit_button" type="submit"><i class="fa fa-sign-out mr-1" aria-hidden="true"></i>Выйти ({{ auth()->user()->name }})</button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>
    <!-- /.navbar -->

    @yield('submenu')

    @include('admin.includes.sidebar')
    @yield('content')

    <!-- Notifications -->
    @if (!empty(session('notification')['message']))
        <div class="toasts-top-right fixed">
            <div class="toast bg-{{ !empty(session('notification')['class']) ? session('notification')['class'] : 'success'}} fade show mw-100 w-auto" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-body d-flex align-items-center">
                    <div>
                        @if(!empty(session('notification')['icon']))
                            <i class="fa fa-{{ session('notification')['icon'] }} fa-lg" aria-hidden="true"></i>
                        @elseif (!empty(session('notification')['class']) && session('notification')['class'] == 'success')
                            <i class="fa fa-check fa-lg" aria-hidden="true"></i>
                        @elseif (!empty(session('notification')['class']) && session('notification')['class'] == 'danger')
                            <i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i>
                        @else
                            <i class="fa fa-envelope fa-lg" aria-hidden="true"></i>
                        @endif
                    </div>
                    <div class="ml-2 mr-2">
                        {!! session('notification')['message'] !!}
                    </div>
                    <div class="align-self-start">
                        <button data-dismiss="toast" type="button" class="close" aria-label="Close" onclick="$(this).closest('.toast').toast('hide');">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if ($errors->any())
        <div class="toasts-top-right fixed" id="validation_errors_notification">
            <div class="toast bg-danger fade show mw-100 w-auto" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-body d-flex align-items-center">
                    <div>
                        <i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i>
                    </div>
                    <div class="ml-2 mr-2">
                        Данные не были сохранены.<br/>Проверьте правильность заполнения полей формы.<br/>Ошибки указаны рядом с невено заполненными полями.
                    </div>
                    <div class="align-self-start">
                        <button data-dismiss="toast" type="button" class="close" aria-label="Close" onclick="$(this).closest('.toast').toast('hide');">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- /Notifications -->

    <footer class="main-footer">
        <strong>BB Управление подписками</strong>
    </footer>

    @include('admin.includes.form_actions')

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- bs-custom-file-input -->
<script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{ asset('plugins/sparklines/sparkline.js') }}"></script>
<!-- JQVMap -->
<script src="{{ asset('plugins/jqvmap/jquery.vmap.min.js') }}"></script>
<script src="{{ asset('plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('plugins/jquery-knob/jquery.knob.min.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ asset('plugins/moment/moment-with-locales.min.js') }}"></script>
<script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Summernote -->
<script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<!-- JQuery Confirm-->
<script src="{{ asset('plugins/jquery-confirm-v3.3.4/js/jquery-confirm.js') }}"></script>
<!-- Checkbox iOS -->
<script src="{{ asset('plugins/checkbox-ios/checkbox-ios.js') }}"></script>
<!-- DataTables  & Plugins -->
<script src="{{ asset('plugins/DataTables-1-12-1/datatables.min.js') }}"></script>
<!-- jQuery UI Touch Punch -->
<script src="{{ asset('plugins/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js') }}"></script>
<!-- JQuery FloatingScroll -->
<script src="{{ asset('plugins/floatingscroll/jquery.floatingscroll.min.js') }}"></script>
<!-- InputMask -->
<script src="{{ asset('plugins/inputmask/jquery.inputmask.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.js') }}"></script>
<!-- Custom JS -->
<script src="{{ assetVersioned('dist/js/custom.js') }}"></script>
<!-- Custom JS for current page -->
@yield('pageScript')

</body>
</html>
