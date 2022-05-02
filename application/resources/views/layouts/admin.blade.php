<!doctype html>
<!-- 
* Workday - A time clock application for employees
* URL: https://codecanyon.net/item/workday-a-time-clock-application-for-employees/23076255
* Support: official.codefactor@gmail.com
* Version: 5.0
* Author: Brian Luna
* Copyright 2022 Codefactor
-->
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('meta')
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/assets/images/img/favicon-16x16.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/assets/images/img/favicon-32x32.png') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('/assets/images/img/favicon.ico') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/vendor/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/vendor/fontawesome/css/solid.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/vendor/flag-icon-css/css/flag-icon.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/vendor/datatables/datatables-theme.css') }}">
    @yield('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/css/master.css') }}">
</head>
<body>
    <div class="wrapper">
        <!-- sidebar navigation component -->
        <nav id="sidebar" class="active">
            <div class="sidebar-header text-center">
                <a class="navbar-brand m-0" href="{{ url('/admin/dashboard') }}">
                    <img src="{{ asset('/assets/images/img/logo.png') }}" id="logo" alt="">
                </a> 
            </div>
            <ul class="list-unstyled components mt-2">
                <li>
                    <a class="nav-link" href="{{ url('/admin/dashboard') }}">
                        <i class="text-secondary fas fa-poll"></i><span class="text-with-icon text-uppercase">{{ __("Dashboard") }}</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link" href="{{ url('/admin/employee') }}">
                        <i class="text-secondary fas fa-users"></i><span class="text-with-icon text-uppercase">{{ __("Employees") }}</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link" href="{{ url('/admin/attendance') }}">
                        <i class="text-secondary fas fa-list"></i><span class="text-with-icon text-uppercase">{{ __("Attendances") }}</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link" href="{{ url('/admin/schedule') }}">
                        <i class="text-secondary fas fa-calendar-alt"></i><span class="text-with-icon text-uppercase">{{ __("Schedules") }}</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link" href="{{ url('/admin/leave') }}">
                        <i class="text-secondary fas fa-calendar-plus"></i><span class="text-with-icon text-uppercase">{{ __("Leave") }}</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link" href="{{ url('/admin/reports') }}">
                        <i class="text-secondary fas fa-chart-bar"></i><span class="text-with-icon text-uppercase">{{ __("Reports") }}</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link" href="{{ url('/admin/users') }}">
                        <i class="text-secondary fas fa-user-circle"></i><span class="text-with-icon text-uppercase">{{ __("Users") }}</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link" href="{{ url('/admin/settings') }}">
                        <i class="text-secondary fas fa-cog"></i><span class="text-with-icon text-uppercase">{{ __("Settings") }}</span>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- end of sidebar component -->

        <div id="body" class="active">
            <!-- navbar navigation component -->
            <nav class="navbar navbar-expand-lg navbar-white bg-white">
              <div class="container-fluid">
                <button type="button" id="sidebarCollapse" class="btn btn-light">
                    <i class="text-secondary fas fa-bars"></i>
                </button>

                <div class="collapse ml-auto navbar-collapse d-flex justify-content-end" id="topnavmenu">
                    <ul class="navbar-nav  main-nav-top navmenu">

                        <li class="nav-item dropdown">
                            <div class="nav-dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="nav2" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="text-with-icon text-responsive text-uppercase">{{ __("Manage") }}</span>
                                    <i class="text-secondary fas fa-layer-group"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end nav-link-menu" aria-labelledby="nav2">
                                    <ul class="nav-list">
                                        <li>
                                            <a href="{{ url('admin/company') }}" class="dropdown-item">
                                                <i class="text-secondary fas fa-university"></i>
                                                <span class="text-with-icon">{{ __("Company") }}</span>
                                            </a>
                                        </li>

                                        <li>
                                            <a href="{{ url('admin/department') }}" class="dropdown-item">
                                                <i class="text-secondary fas fa-cubes"></i><span class="text-with-icon">{{ __("Department") }}</span>
                                            </a>
                                        </li>

                                        <li>
                                            <a href="{{ url('admin/jobtitle') }}" class="dropdown-item">
                                                <i class="text-secondary fas fa-pencil-alt"></i><span class="text-with-icon">{{ __("Job Title") }}</span>
                                            </a>
                                        </li>

                                        <li>
                                            <a href="{{ url('admin/leavetype') }}" class="dropdown-item">
                                                <i class="text-secondary fas fa-calendar-alt"></i><span class="text-with-icon">{{ __("Leave Type") }}</span>
                                            </a>
                                        </li>

                                        <li>
                                            <a href="{{ url('admin/leavegroups') }}" class="dropdown-item">
                                                <i class="text-secondary fas fa-calendar-check"></i><span class="text-with-icon">{{ __("Leave Groups") }}</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </li>

                        <li class="nav-item dropdown small-dropdown">
                            <div class="nav-dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="nav3" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="text-secondary fas fa-user-circle"></i><span class="text-with-icon text-responsive">{{ Auth::user()->name }}</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end nav-link-menu" aria-labelledby="nav3">
                                    <ul class="nav-list">
                                        <li>
                                            <a class="dropdown-item" href="{{ url('/admin/account') }}">
                                                <i class="text-secondary fas fa-user-tie"></i><span class="text-with-icon">{{ __("My Account") }}</span>
                                            </a>
                                        </li>

                                        <li>
                                            <a class="dropdown-item" href="{{ url('/personal/dashboard') }}" target="_blank">
                                                <i class="text-secondary fas fa-user-alt"></i><span class="text-with-icon">{{ __("Personal Portal") }}</span>
                                            </a>
                                        </li>

                                        <div class="line"></div>

                                        <li>
                                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                <i class="text-secondary fas fa-sign-out-alt"></i><span class="text-with-icon">{{ __("Log out") }}</span>
                                            </a>
                                        </li>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </ul>
                                </div>
                            </div>
                        </li>

                    </ul>
                </div>
              </div>
            </nav>
            <!-- end of navbar navigation -->

            <main class="py-4">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- message alert -->
    <div class="position-fixed bottom-0 right-0 p-3" style="z-index: 5; right: 0; bottom: 0;">
        @if ($success = Session::get('success'))
        <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="5000">
          <div class="toast-header">
            <svg class="bd-placeholder-img rounded me-2" width="20" height="20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false">
                <rect width="100%" height="100%" fill="#007aff"></rect>
            </svg>
            <strong class="me-auto">{{ __("Success") }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
          <div class="toast-body">
            {{ $success }}
          </div>
        </div>
        @endif
        
        @if ($error = Session::get('error'))
        <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="5000">
          <div class="toast-header">
            <svg class="bd-placeholder-img rounded me-2" width="20" height="20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false">
                <rect width="100%" height="100%" fill="#f44336"></rect>
            </svg>
            <strong class="me-auto">{{ __("Error") }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
          <div class="toast-body">
            {{ $error }}
          </div>
        </div>
        @endif
    </div>

    <script src="{{ asset('/assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('/assets/js/sidebar.js') }}"></script>
    <script src="{{ asset('/assets/vendor/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('/assets/js/initiate-toast.js') }}"></script>
    @yield('scripts')
</body>
</html>
