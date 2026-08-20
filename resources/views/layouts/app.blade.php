<!DOCTYPE html>
<html dir="ltr" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/extra-assets/images/favicon.png') }}">

    <!-- Custom CSS -->
    <link href="{{ asset('assets/extra-assets/extra-libs/c3/c3.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/extra-assets/libs/chartist/dist/chartist.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/extra-assets/extra-libs/jvector/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/dist/css/style.min.css') }}" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>
    <!-- Preloader -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>

    <!-- Main wrapper -->
    <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">

        <!-- Topbar Header -->
        @include('layouts.header')

        <!-- Left Sidebar -->
        @include('layouts.sidebar')

        <!-- Page Wrapper -->
        <div class="page-wrapper">

            <!-- Page Content -->
            @if(isset($slot) && $slot->isNotEmpty())
                {{ $slot }}
            @else
                @yield('content')
            @endif

            <!-- Footer -->
            @include('layouts.footer')
        </div>
    </div>

    <!-- All Required js -->
    <script src="{{ asset('assets/extra-assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/extra-assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/dist/js/app-style-switcher.js') }}"></script>
    <script src="{{ asset('assets/dist/js/feather.min.js') }}"></script>
    <script src="{{ asset('assets/extra-assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/dist/js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('assets/dist/js/custom.min.js') }}"></script>

    <!-- Dashboard charts & maps plugin js -->
    <script src="{{ asset('assets/extra-assets/extra-libs/c3/d3.min.js') }}"></script>
    <script src="{{ asset('assets/extra-assets/extra-libs/c3/c3.min.js') }}"></script>
    <script src="{{ asset('assets/extra-assets/libs/chartist/dist/chartist.min.js') }}"></script>
    <script src="{{ asset('assets/extra-assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js') }}"></script>
    <script src="{{ asset('assets/extra-assets/extra-libs/jvector/jquery-jvectormap-2.0.2.min.js') }}"></script>
    <script src="{{ asset('assets/extra-assets/extra-libs/jvector/jquery-jvectormap-world-mill-en.js') }}"></script>
    <script src="{{ asset('assets/dist/js/pages/dashboards/dashboard1.min.js') }}"></script>
</body>

</html>
