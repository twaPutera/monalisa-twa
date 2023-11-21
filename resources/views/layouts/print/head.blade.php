<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    {{-- <title>{{$title}}</title> --}}
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Ionicons -->
    {{-- <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> --}}
    {{-- <link href="{{ public_path('https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css') }}" rel="stylesheet" type="text/css"/> --}}

    <!-- Google Font: Source Sans Pro -->
    {{-- <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet"> --}}
    {{-- <link href="{{ public_path('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet') }}" rel="stylesheet" type="text/css"/> --}}

    {{-- <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,700;0,800;1,500;1,800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700&family=Tinos&display=swap"
        rel="stylesheet"> --}}

    {{-- bootstrap css --}}
    <link media="all" rel="stylesheet" type="text/css"
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    {{-- <link href="{{ public_path('custom-css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/> --}}

    {{-- custom css --}}
    {{-- <link media="all" rel="stylesheet" type="text/css" href="{{asset('custom-css/print.css')}}"> --}}
    {{-- <link href="{{ public_path('custom-css/print.css') }}" rel="stylesheet" type="text/css" media="all"/> --}}

    @hasSection('head-css')
        @yield('head-css')
    @else
        <style>
            @page {
                size: A4;
                margin-top: 2.5cm;
                margin-bottom: 2.5cm;
                margin-left: 0;
                margin-right: 0;
            }

            header {
                position: fixed;
                top: -70px;
                left: 0px;
                right: 0px;
                height: 70px;
                text-align: center;
                margin-bottom: 30px;
            }

            footer {
                position: fixed;
                bottom: -80px;
                left: 0px;
                right: 0px;
                width: 80%;
                height: auto;
                text-align: center;
                margin-left: 2cm;
                margin-right: 2cm;
            }

            main {
                margin: 30px 2cm;
            }
        </style>
    @endif

    <style>
        /* @font-face {
        font-family: "Times New Roman" !important;
        src: url({{ asset('fonts/times-new-roman.ttf.ttf') }}) format("truetype") !important;
    } */
        body {
            /* font-family: 'Jost', sans-serif; */
            /* font-family: 'Tinos', serif; */
            font-family: "times-roman", sans-serif, "Arial";
            font-size: 16px;
        }

        .text-center {
            text-align: center;
        }

        .font-weight-bold {
            font-weight: bold;
        }

        .w-100 {
            width: 100%;
        }

        h1 {
            font-size: 16px;
        }

        h3 {
            font-size: 14px;
        }

        div.pagebreak {
            page-break-after: always;
        }

        table {
            width: 100% !important;
        }



        @media print {
            table.report-container {
                page-break-after: always;
            }

            thead.report-header {
                display: table-header-group;
            }

            tfoot.report-footer {
                display: table-footer-group;
            }

            @page {
                size: A4;
                margin-left: 0px;
                margin-right: 0px;
                margin-top: 0px;
                margin-bottom: 0px;
            }
        }
    </style>
    @yield('css')
</head>
