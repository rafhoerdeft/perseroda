<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/header-colors.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

    <!-- Plugin CSS tambahan -->
    @stack('css_plugin')

    <!-- Style CSS tambahan -->
    @stack('css_style')

    @livewireStyles
</head>

<body>

    @stack('content')

    @livewireScripts

    <!-- Bootstrap JS -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <!--plugins-->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/app.js') }}"></script> --}}

    <!-- Plugin JS tambahan -->
    @stack('js_plugin')

    <!-- Script JS tambahan -->
    @stack('js_script')

    <script type="text/javascript">
        $(document).ready(function() {
            $('#alts').fadeTo(3000, 500).slideUp(500);
        });
    </script>
</body>

</html>
