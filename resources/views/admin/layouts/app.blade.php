<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- <title>AdminLTE 3 | Dashboard</title>--}}
    <title>{{$setting->company_name ?? 'Tha Network'}} - @yield('title')</title>
    <link rel="icon" type="image/x-icon"
          href="{{ asset('/uploads/settings/'.$setting->logo ?? 'front/images/logo.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{URL::asset('admin/plugins/fontawesome-free/css/all.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
          href="{{URL::asset('admin/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{URL::asset('admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{URL::asset('admin/plugins/jqvmap/jqvmap.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{URL::asset('admin/dist/css/adminlte.min.css')}}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{URL::asset('admin/plugins/toastr/toastr.min.css')}}">
    @yield('page_css')
    <link rel="stylesheet" href="{{URL::asset('admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{URL::asset('admin/plugins/daterangepicker/daterangepicker.css')}}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{URL::asset('admin/plugins/summernote/summernote-bs4.min.css')}}">
{{--  table colors remove--}}
<!-- Datatables -->
    <link href="{{ asset('admin/datatables/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

    <style>
        table.dataTable thead th, table.dataTable thead td {
            border-bottom: 0px;
        }

        table.dataTable.no-footer {
            border-bottom: 1px solid #dee2e6;
        }

        #example1_filter input {
            border-color: #f2f2f2;
        }

        .bannerStatus {
            display: block;
            width: 30px;
            aspect-ratio: 1;
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake"
             src="{{isset($setting->logo) ? URL::asset('uploads/settings/'.$setting->logo) : URL::asset('admin/dist/img/AdminLTELogo.png')}}"
             alt="AdminLTELogo" height="60" width="60">
    </div>
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->
    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="{{url('admin/dashboard')}}" class="brand-link" style="text-align: center">
            <img class="animation__shake"
                 src="{{isset($setting->logo) ? URL::asset('uploads/settings/'.$setting->logo) : URL::asset('admin/dist/img/AdminLTELogo.png')}}"
                 alt="Beignet Done That" style="max-width: 155px;width: 100%;height: auto;">
            {{--            <span class="brand-text font-weight-light">Dashboard</span>--}}
        </a>
        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="{{URL::asset('admin/dist/img/admin-avatar.webp')}}" class="img-circle elevation-2"
                         alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block">{{\Illuminate\Support\Facades\Auth::user()->name}}</a>
                </div>
            </div>
            <!-- SidebarSearch Form -->
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu"
                    data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
                         with font-awesome or any other icon font library -->
                    <li class="nav-item">
                        <a href="{{url('admin/dashboard')}}"
                           class="nav-link {{ request()->IS('admin/dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard
                            </p>
                        </a>
                    </li>

                    <li class="nav-item has-treeview {{ request()->IS('admin/settings') || request()->IS('admin/payouts') ? 'menu-is-opening menu-open' : '' }}">
                        <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-tags fw"></i>
                            <p>
                                Settings
                            </p>
                        </a>
                        <ul class="nav nav-treeview"
                            style="{{ request()->IS('admin/emailsetting') || request()->IS('admin/paymentgatway') ? 'display:block;' : '' }}">
                            <li class="nav-item">
                                <a href="{{route('settings')}}"
                                   class="nav-link {{ request()->IS('admin/settings') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-cog"></i>
                                    <p>
                                        General Settings
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('payouts')}}"
                                   class="nav-link {{ request()->IS('admin/payouts') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-cog"></i>
                                    <p>
                                        Payouts
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('admin.goals')}}"
                           class="nav-link {{ request()->IS('admin/goals') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user"></i>
                            <p>Goals</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('admin.users')}}"
                           class="nav-link {{ request()->IS('admin/users') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user"></i>
                            <p>Users</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('admin.deleted-users')}}"
                           class="nav-link {{ request()->IS('admin/deleted-users') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user"></i>
                            <p>Deleted Users</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('admin.suspended-users')}}"
                           class="nav-link {{ request()->IS('admin/suspended-users') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user"></i>
                            <p>Suspended Users</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('admin.closed-users')}}"
                           class="nav-link {{ request()->IS('admin/closed-users') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user"></i>
                            <p>Closed Accounts</p>
                        </a>
                    </li>

                    <li class="nav-item has-treeview {{ request()->IS('admin/cms/about-us') || request()->IS('admin/cms/home') || request()->IS('admin/cms/benefits') || request()->IS('admin/cms/terms') || request()->IS('admin/cms/privacy') || request()->IS('admin/cms/contact') || request()->IS('admin/cms/faqs')  ? 'menu-is-opening menu-open' : '' }}">
                        <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-tags fw"></i>
                            <p>
                                CMS
                            </p>
                        </a>
                        <ul class="nav nav-treeview"
                            style="{{ request()->IS('admin/emailsetting') || request()->IS('admin/paymentgatway') ? 'display:block;' : '' }}">

                            <li class="nav-item">
                                <a href="{{route('admin.cms.home')}}"
                                   class="nav-link {{ request()->IS('admin/cms/home') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-angle-double-right"></i>
                                    <p>
                                        Home
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('admin.cms.benefits')}}"
                                   class="nav-link {{ request()->IS('admin/cms/benefits') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-angle-double-right"></i>
                                    <p>
                                        Benefits
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('admin.cms.aboutUs')}}"
                                   class="nav-link {{ request()->IS('admin/cms/about-us') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-angle-double-right"></i>
                                    <p>
                                        About Us
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('admin.cms.terms')}}"
                                   class="nav-link {{ request()->IS('admin/cms/terms') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-angle-double-right"></i>
                                    <p>
                                        Terms
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('admin.cms.privacy')}}"
                                   class="nav-link {{ request()->IS('admin/cms/privacy') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-angle-double-right"></i>
                                    <p>
                                        Privacy
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('admin.cms.contact')}}"
                                   class="nav-link {{ request()->IS('admin/cms/contact') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-angle-double-right"></i>
                                    <p>
                                        Contact
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('admin.cms.faqs')}}"
                                   class="nav-link {{ request()->IS('admin/cms/faqs') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-angle-double-right"></i>
                                    <p>
                                        FAQs
                                    </p>
                                </a>
                            </li>
                            {{--                            <li class="nav-item">--}}
                            {{--                                <a href="{{route('admin.cms.maintenance.banner')}}"--}}
                            {{--                                   class="nav-link {{ request()->IS('admin/cms/maintenance-banner') ? 'active' : '' }}">--}}
                            {{--                                    <i class="nav-icon fas fa-angle-double-right"></i>--}}
                            {{--                                    <p>--}}
                            {{--                                        Maintenance Banner--}}
                            {{--                                    </p>--}}
                            {{--                                </a>--}}
                            {{--                            </li>--}}
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="{{url('admin/changePassword')}}"
                           class="nav-link {{ request()->IS('admin/changePassword') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-comments"></i>
                            <p>Change Password</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="dropdown-item nav-link" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                            <i class="nav-icon fas fa-lock"></i>
                            <p>{{ __('Logout') }}</p>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>

                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
@yield('section')
<!-- /.content-wrapper -->
    <footer class="main-footer">
        <strong>Copyright &copy; {{date('Y')}} <a href="#">{{$setting->company_name}}</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            {{--            <b>Version</b> 3.1.0--}}
        </div>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{URL::asset('admin/plugins/jquery/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{URL::asset('admin/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{URL::asset('admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
{{--<!-- ChartJS -->--}}
{{--<script src="{{URL::asset('admin/plugins/chart.js/Chart.min.js')}}"></script>--}}
{{--<!-- Sparkline -->--}}
{{--<script src="{{URL::asset('admin/plugins/sparklines/sparkline.js')}}"></script>--}}
<!-- JQVMap -->
{{--<script src="{{URL::asset('admin/plugins/jqvmap/jquery.vmap.min.js')}}"></script>--}}
{{--<script src="{{URL::asset('admin/plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>--}}
{{--<!-- jQuery Knob Chart -->--}}
{{--<script src="{{URL::asset('admin/plugins/jquery-knob/jquery.knob.min.js')}}"></script>--}}
<!-- daterangepicker -->
<script src="{{URL::asset('admin/plugins/moment/moment.min.js')}}"></script>
<script src="{{URL::asset('admin/plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{URL::asset('admin/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<!-- Summernote -->
<script src="{{URL::asset('admin/plugins/summernote/summernote-bs4.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{URL::asset('admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{URL::asset('admin/dist/js/adminlte.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{URL::asset('admin/dist/js/demo.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{URL::asset('admin/dist/js/pages/dashboard.js')}}"></script>

<script src="{{URL:: asset('admin/sweetalert.min.js') }}"></script>
<script src="{{URL:: asset('admin/alert.js') }}"></script>
<script src="{{URL:: asset('admin/plugins/toastr/toastr.min.js')}}"></script>


@if(session()->has('success'))
    <script type="text/javascript">  toastr.success('{{ session('success')}}');</script>
@endif
@if(session()->has('error'))
    <script type="text/javascript"> toastr.error('{{ session('error')}}');</script>
@endif
<script>

</script>

{{-- datatables script --}}
<script src="{{asset('admin/datatables/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script src="{{asset('admin/datatables/datatables.net/js/jquery.dataTables.min.js')}}"></script>

@yield('script')
</body>
</html>
