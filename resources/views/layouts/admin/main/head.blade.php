<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    <link rel="shortcut icon" type="image/png" href="{{asset('assets/images/logo.png')}}"/>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo-denpasar.png') }}">
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script>
        WebFont.load({
            google: {
                "families": ["Poppins:300,400,500,600,700", "Asap+Condensed:500"]
            },
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>
    <link href="{{ asset('assets/vendors/general/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('assets/vendors/general/animate.css/animate.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.3.5/dist/sweetalert2.css">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css"> --}}
    <link href="{{ asset('assets/vendors/custom/vendors/line-awesome/css/line-awesome.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/vendors/custom/vendors/flaticon/flaticon.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/vendors/custom/vendors/flaticon2/flaticon.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/vendors/general/toastr/build/toastr.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/vendors/general/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css" />
    @yield('plugin_css')
    <link href="{{ asset('assets/css/style.bundle.min.css') }}" rel="stylesheet" type="text/css" />
    {{-- <link href="{{ asset('custom-css/bootstrap-validation-v3.css') }}" rel="stylesheet" type="text/css" /> --}}
    <link href="{{ asset('custom-css/theme-mod.css') }}" rel="stylesheet" type="text/css" />
    @yield('custom_css')

</head>

{{-- <audio id="notification_sound" preload="none" src="{{asset('sound/notification.mp3')}}" type="audio/mpeg"></audio> --}}
