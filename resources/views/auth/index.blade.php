<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    @include('template.meta')

    <!--plugins-->
    <link href="{{ asset('assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
    {{-- <link href="{{ asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" /> --}}
    <link href="{{ asset('assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
    <!-- loader-->
    <link href="{{ asset('assets/css/pace.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('assets/js/pace.min.js') }}"></script>
    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/plugins/notifications/css/lobibox.min.css') }}" />
</head>

<body>
    <!--wrapper-->
    <div class="wrapper">
        <div class="authentication-header"></div>
        <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
            <div class="container-fluid">
                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
                    <div class="col mx-auto">
                        <div class="mb-4 text-center">
                            <img src="{{ asset('assets/images/logo-img.png') }}" width="180" alt="" />
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="p-4 rounded">
                                    <div class="text-center">
                                        <h3 class="">Login Panel</h3>
                                        {{-- <p>
                                            Don't have an account yet?
                                            <a href="authentication-signup.html">
                                                Sign up here
                                            </a>
                                        </p> --}}
                                    </div>

                                    {{-- <div class="d-grid">
                                        <a class="btn my-4 shadow-sm btn-white" href="javascript:;"> <span
                                                class="d-flex justify-content-center align-items-center">
                                                <img class="me-2"
                                                    src="{{ asset('assets/images/icons/search.svg') }}" width="16"
                                                    alt="Image Description">
                                                <span>Sign in with Google</span>
                                            </span>
                                        </a> <a href="javascript:;" class="btn btn-facebook"><i
                                                class="bx bxl-facebook"></i>Sign in with Facebook</a>
                                    </div>
                                    <div class="login-separater text-center mb-4"> <span>OR SIGN IN WITH EMAIL</span>
                                        <hr />
                                    </div> --}}

                                    <div class="form-body">
                                        <form class="row g-3" action="{{ route('auth.process') }}"
                                            method="POST">
                                            @csrf
                                            <div class="col-12">
                                                <label for="username" class="form-label">Username</label>
                                                <input type="text" name="username"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    id="username" placeholder="Enter Username"
                                                    value="{{ old('username') }}">
                                                @error('username')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="col-12">
                                                <label for="password" class="form-label">
                                                    Password
                                                </label>
                                                <div class="input-group has-validation" id="show_hide_password">
                                                    <input type="password"
                                                        class="form-control border-end-0 @error('password') is-invalid @enderror"
                                                        id="password" name="password" placeholder="Enter Password">
                                                    <a href="javascript:;" class="input-group-text bg-transparent">
                                                        <i class='bx bx-hide'></i>
                                                    </a>
                                                    @error('password')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>

                                            </div>
                                            {{-- <div class="col-md-6">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="remember"
                                                        name="remember" checked>
                                                    <label class="form-check-label" for="remember">Remember Me</label>
                                                </div>
                                            </div> --}}

                                            {{-- <div class="col-md-6 text-end"> <a
                                                    href="authentication-forgot-password.html">Forgot Password ?</a>
                                            </div> --}}

                                            <div class="col-12">
                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-primary"><i
                                                            class="bx bxs-lock-open"></i>Login</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end row-->
            </div>
        </div>
    </div>
    <!--end wrapper-->
    <!-- Bootstrap JS -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <!--plugins-->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
    <!--notification js -->
    <script src="{{ asset('assets/plugins/notifications/js/lobibox.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/notifications/js/notifications.min.js') }}"></script>
    {{-- <script src="{{ asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script> --}}
    <!--Password show & hide js -->
    <script>
        $(document).ready(function() {
            $("#show_hide_password a").on('click', function(event) {
                event.preventDefault();
                if ($('#show_hide_password input').attr("type") == "text") {
                    $('#show_hide_password input').attr('type', 'password');
                    $('#show_hide_password i').addClass("bx-hide");
                    $('#show_hide_password i').removeClass("bx-show");
                } else if ($('#show_hide_password input').attr("type") == "password") {
                    $('#show_hide_password input').attr('type', 'text');
                    $('#show_hide_password i').removeClass("bx-hide");
                    $('#show_hide_password i').addClass("bx-show");
                }
            });
        });
    </script>
    <!--app JS-->
    {{-- <script src="{{ asset('assets/js/app.js') }}"></script> --}}

    @if (Session::has('alert'))
        <script>
            alert();

            function alert(params) {
                Lobibox.notify('error', {
                    pauseDelayOnHover: true,
                    rounded: true,
                    continueDelayOnInactiveTab: false,
                    position: 'top right',
                    icon: 'bx bx-x-circle',
                    msg: "{{ session('alert') }}"
                });
            }
        </script>
    @endif
</body>

</html>
