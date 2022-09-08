<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="{{ asset('assets/images/favicon-32x32.png') }}" type="image/png" />
    <!-- loader-->
    <link href="{{ asset('assets/css/pace.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('assets/js/pace.min.js') }}"></script>
    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
    <title>@yield('code') @yield('title')</title>
</head>

<body>
    <!-- wrapper -->
    <div class="wrapper">
        {{-- <nav class="navbar navbar-expand-lg navbar-light bg-white rounded fixed-top rounded-0 shadow-sm">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">
                    <img src="{{ asset('assets/images/logo-img.png') }}" width="140" alt="" />
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent1" aria-controls="navbarSupportedContent1"
                    aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent1">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"> <a class="nav-link active" aria-current="page" href="#"><i
                                    class='bx bx-home-alt me-1'></i>Home</a>
                        </li>
                        <li class="nav-item"> <a class="nav-link" href="#"><i
                                    class='bx bx-user me-1'></i>About</a>
                        </li>
                        <li class="nav-item"> <a class="nav-link" href="#"><i
                                    class='bx bx-category-alt me-1'></i>Features</a>
                        </li>
                        <li class="nav-item"> <a class="nav-link" href="#"><i
                                    class='bx bx-microphone me-1'></i>Contact</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav> --}}
        <div class="error-404 d-flex align-items-center justify-content-center">
            <div class="container">
                <div class="card py-5">
                    <div class="row g-0">
                        <div class="col col-xl-5">
                            <div class="card-body p-4">
                                @yield('content')
                                <div class="mt-5">
                                    {{-- <a href="javascript:;" class="btn btn-primary btn-lg px-md-5 radius-30">Go Home</a> --}}
                                    <a href="{{ url()->previous() }}"
                                        class="btn btn-outline-dark btn-lg ms-3 px-md-5 radius-30">Back</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-7">
                            <img src="{{ asset('assets/images/errors-images') }}/@yield('img')" class="img-fluid"
                                alt="">
                        </div>
                    </div>
                    <!--end row-->
                </div>
            </div>
        </div>
        <div class="bg-white p-3 fixed-bottom border-top shadow">
            <div class="d-flex align-items-center justify-content-between flex-wrap">
                <ul class="list-inline mb-0">
                    {{-- <li class="list-inline-item">Follow Us :</li>
                    <li class="list-inline-item"><a href="javascript:;"><i class='bx bxl-facebook me-1'></i>Facebook</a>
                    </li>
                    <li class="list-inline-item"><a href="javascript:;"><i class='bx bxl-twitter me-1'></i>Twitter</a>
                    </li>
                    <li class="list-inline-item"><a href="javascript:;"><i class='bx bxl-google me-1'></i>Google</a>
                    </li> --}}
                </ul>
                <p class="mb-0">Copyright © {{ date('Y') }}. All right reserved.</p>
            </div>
        </div>
    </div>
    <!-- end wrapper -->
    <!-- Bootstrap JS -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>