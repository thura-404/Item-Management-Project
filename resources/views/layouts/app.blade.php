<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>@yield('title') - IRP</title>
    <!-- add icon link -->
    <link rel="icon" href="{{ asset('placeholder-image/irp-low-resolution-logo-color-on-transparent-background.svg') }}" type="image/x-icon">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- Custom fonts for this template-->
    <link href="/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">



    <!-- Custom styles for this template-->
    <link href="/css/sb-admin-2.min.css" rel="stylesheet">


    @yield('link')

    @yield('cs-style')

</head>

<body id="@yield('body-id')" class="@yield('body-class')">
    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('items.list' ) }}">
                <div class="sidebar-brand-icon rotate-n-15">
                    <img src="{{ asset('placeholder-image/irp-high-resolution-logo-white-on-transparent-background.svg') }}" width="50" height="50" style="rotate:calc(15deg);" alt="">
                </div>
            </a>


            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('items.list' ) }}">
                    <i class="fas fa-layer-group"></i>
                    <span>@lang('public.itemList')</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider  my-0">

            <!-- Nav Item - Logout -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('employees.logout' ) }}">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>@lang('public.logout')</span>
                </a>
            </li>



            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <h1 class="h3 mb-2 text-gray-800">@yield('nav-title')</h1>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">

                            <a class="nav-link dropdown-toggle  text-dark" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                @lang('public.language')
                            </a>
                            <div class="dropdown-menu dropdown-menu-right animated--grow-in" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item mb-1" href="{{ route('locale', ['lang' => 'en']) }}"><img src="{{ asset('placeholder-image/english.png') }}" alt="English" style="width: 1.5rem; margin-right: 0.5rem;">English</a>
                                <a class="dropdown-item mb-1" href="{{ route('locale', ['lang' => 'my']) }}"><img src="{{ asset('placeholder-image/myanmar.png') }}" alt="English" style="width: 1.5rem; margin-right: 0.5rem;">Myanmar</a>
                            </div>
                        </li>

                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">

                            @yield('download')
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">

                            <a href="@yield('nav-item-link')" class="btn btn-secondary m-2 btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-arrow-right"></i>
                                </span>
                                <span class="text">@yield('nav-item-text')</span>
                            </a>
                        </li>
                    </ul>

                </nav>
                <!-- End of Topbar -->

                @yield('body-container')

                <!-- Footer -->
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright &copy; IRP 2020</span>
                        </div>
                    </div>
                </footer>
                <!-- End of Footer -->

            </div>
            <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->

        <!-- Bootstrap 5 -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

        <!-- Bootstrap core JavaScript-->
        <script src="/jquery/jquery.min.js"></script>
        <script src="/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="/jquery-easing/jquery.easing.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="/js/sb-admin-2.min.js"></script>

        @yield('js')
</body>

</html>