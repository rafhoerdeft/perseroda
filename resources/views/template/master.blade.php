<!doctype html>
<html lang="{{ app()->getLocale() }}" class="color-sidebar {{ auth()->user()->role->color }}">

<head>
    @include('template.meta')

    <!--plugins-->
    <link href="{{ asset('assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
    <!-- loader-->
    {{-- <link href="{{ asset('assets/css/pace.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('assets/js/pace.min.js') }}"></script> --}}
    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/css/app-new.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">

    <!-- Theme Style CSS -->
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/dark-theme.css') }}" /> --}}
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/semi-dark.css') }}" /> --}}
    <link rel="stylesheet" href="{{ asset('assets/css/header-colors.css') }}" />

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

    <!-- Plugin CSS tambahan -->
    @stack('css_plugin')

    <!-- Style CSS tambahan -->
    @stack('css_style')

    <style>
        .loading-process {
            text-align: center;
            z-index: 10000;
            top: 0px;
            width: 100%;
            height: 100%;
            position: fixed;
            background: #32004c5e;
            display: none;
        }
    </style>

    <style>
        .dt-buttons .btn {
            padding: 0.15rem 0.75rem;
        }

        .font-sm {
            font-size: 9pt !important;
        }

        .row_check {
            background-color: #ffe175 !important;
        }

        .row_empty {
            background-color: #ffabab !important;
        }

        .text-sm-b {
            font-size: 0.75em;
            font-weight: 700;
        }
    </style>
</head>

<body>

    @stack('loading')

    <!--wrapper-->
    <div class="wrapper">
        <!--sidebar wrapper -->
        <div class="sidebar-wrapper" data-simplebar="true">
            <div class="sidebar-header">
                <div>
                    <img src="{{ show_image(session('configs')['logo']) }}" class="logo-icon" alt="logo icon">
                </div>
                <div>
                    <h6 class="logo-text">{{ session('configs')['app_name'] }}</h6>
                </div>
                <div class="toggle-icon ms-auto"><i class='bx bx-first-page'></i>
                </div>
            </div>
            <!--navigation-->
            @include('template.navigation')
            <!--end navigation-->
        </div>
        <!--end sidebar wrapper -->

        <!--start header -->
        @include('template.header_dark')
        <!--end header -->

        <!--start page wrapper -->
        <div class="page-wrapper">
            <div class="page-content">
                @if (isset($breadcrumb))
                    <!--breadcrumb-->
                    @include('template.breadcrumb')
                    <!--end breadcrumb-->
                @endif

                <!-- Content -->
                @yield('content')
                <!-- end Content -->
            </div>
        </div>
        <!--end page wrapper -->

        <!--start overlay-->
        {{-- <div class="search-overlay"></div> --}}
        <div class="overlay toggle-icon"></div>
        <!--end overlay-->

        <!--Start Back To Top Button-->
        <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->
        <footer class="page-footer">
            <p class="mb-0">Copyright © {{ date('Y') }}. Ver. {{ config('app.version') }}</p>
        </footer>

        <!-- Modal -->
        @stack('modal')

    </div>
    <!--end wrapper-->

    <!-- Bootstrap JS -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <!--plugins-->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
    {{-- <script src="{{ asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script> --}}
    <!--app JS-->
    <script src="{{ asset('assets/js/app.js') }}"></script>

    <!-- Plugin JS tambahan -->
    @stack('js_plugin')

    <!-- Script JS tambahan -->
    @stack('js_script')

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#alts').fadeTo(3000, 500).slideUp(500);
        });
    </script>
</body>

</html>
