

        <!DOCTYPE html>
        <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
            <head>
                <meta charset="utf-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <meta name="description" content="">
                <meta name="author" content="">
                <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
                <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet"> 
                
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
                <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
                <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

                @yield('library')
            
             
                <title> {{ config('app.name') }}</title>

                <!-- Custom styles for this template-->
                 <!-- Fonts -->
                <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

                <!-- Styles -->
                <link rel="stylesheet" href="{{ asset('css/app.css') }}">

                <!-- Scripts -->
                <script src="{{ asset('js/app.js') }}" defer></script>
                <link href="{{ asset('css/sb-admin-2.css') }}" rel='stylesheet'/>
                <script src="{{ asset('js/Event.js') }}"> </script>

            </head>

            <body id="page-top">
  
                <!-- Page Wrapper -->
                <div id="wrapper">

                    <!-- Sidebar -->
                    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

                        <!-- Sidebar - Brand -->
                        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
                            <div class="sidebar-brand-icon">
                                <i class="fa fa-fw fa-home"></i>
                            </div>
                            <div class="sidebar-brand-text mx-3"> Admin </div>
                        </a>
                            
                        <!-- Divider -->
                        <hr class="sidebar-divider">

                        <!-- Nav Item - Dashboard -->
                        <li class="nav-item active">
                            <a class="nav-link" href="#">
                                <i class="fas fa-fw fa-tachometer-alt"></i>
                                <span>Dashboard</span></a>
                        </li>

                        <hr class="sidebar-divider">
                        <!-- Nav Item - Tables -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{URL::to('/customers')}}">
                                <i class="fas fa-fw fa-table"></i>
                                <span class="font_weight">Quản lý KH</span></a>
                        </li>
                        <!-- Divider -->
                        <hr class="sidebar-divider">

                        <!-- Nav Item - Tables -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{URL::to('/estimates')}}">
                                <i class="fas fa-fw fa-table"></i>
                                <span class="font_weight">Quản lý estimate</span></a>
                        </li>
                        <!-- Divider -->
                        <hr class="sidebar-divider">

                        <!-- Nav Item - Tables -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{URL::to('/projects')}}">
                                <i class="fas fa-fw fa-table"></i>
                                <span class="font_weight">Quản lý project</span></a>
                        </li>
                        <!-- Divider -->
                        <hr class="sidebar-divider">

                        <!-- Nav Item - Tables -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{URL::to('/items')}}">
                                <i class="fas fa-fw fa-table"></i>
                                <span class="font_weight">Quản lý item</span></a>
                        </li>
                        <!-- Divider -->
                        <hr class="sidebar-divider">

                        <!-- Nav Item - Tables -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{URL::to('/invoices')}}">
                                <i class="fas fa-fw fa-table"></i>
                                <span class="font_weight">Quản lý invoice</span></a>
                        </li>
                        <!-- Divider -->
                        <hr class="sidebar-divider d-none d-md-block">
                        <!-- Sidebar Toggler (Sidebar) -->
      
                    </ul>
                    <!-- End of Sidebar -->

                    <!-- Content Wrapper -->
                    <div id="content-wrapper" class="d-flex flex-column">

                        <!-- Main Content -->
                        <div id="content">

                            <!-- Topbar -->
                            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                                <!-- Topbar Navbar -->
                                <ul class="navbar-nav ml-auto">
                                    <!-- Nav Item - User Information -->
                                    <li class="nav-item dropdown no-arrow">
                                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                                            <img class="img-profile rounded-circle" src="{{URL::to('img/undraw_profile.svg')}}">
                                        </a>
                                        <!-- Dropdown - User Information -->
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"aria-labelledby="userDropdown">
                                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                                
                                                <form method="POST" action="{{ route('logout') }}">
                                                        @csrf
                                                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                                            {{ __('Log Out') }}
                                                        </x-dropdown-link>
                                                </form>
                                            </a>
                                                
                                            
                                        </div>
                                    </li>
                                </ul>
                            </nav>
                            <!-- Begin Page Content -->
                            <div class="container-fluid">

                                <!-- Page Heading -->
                                <div style="margin-top:-15px;">
                                    <h6> @yield('title-detail')</h6>                                   
                                </div>

                            </div>
                            <!-- End of Topbar -->
                            
                            <!-- show extend content -->
                            @yield('content')
                
                        </div>
                        
                        <!-- End of Main Content -->

                        <!-- Footer -->
                        <footer class="sticky-footer bg-white">
                            <div class="container my-auto">
                                <div class="copyright text-center my-auto">
                                    <span>Copyright &copy;  Website 2021</span>
                                </div>
                            </div>
                        </footer>
                        <!-- End of Footer -->

                    </div>
                    <!-- End of Content Wrapper -->

                </div>

            </body>
        </html>
