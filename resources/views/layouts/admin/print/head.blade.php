<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {{-- <title>{{$title}}</title> --}}
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Ionicons -->
    {{-- <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> --}}
    {{-- <link href="{{ public_path('https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css') }}" rel="stylesheet" type="text/css"/> --}}

    <!-- Google Font: Source Sans Pro -->
    {{-- <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet"> --}}
    {{-- <link href="{{ public_path('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet') }}" rel="stylesheet" type="text/css"/> --}}

    {{-- bootstrap css --}}
    {{-- <link media="all" rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous"> --}}
    {{-- <link href="{{ public_path('custom-css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/> --}}

    {{-- custom css --}}
    {{-- <link media="all" rel="stylesheet" type="text/css" href="{{asset('custom-css/print.css')}}"> --}}
    {{-- <link href="{{ public_path('custom-css/print.css') }}" rel="stylesheet" type="text/css" media="all"/> --}}

    <style>
        /* body {
            background: rgb(204,204,204);
        }

        page {
            background: white;
            display: block;
            margin: 0 auto;
            margin-bottom: 0.5cm;
        }

        page[size="A4"] {
            width: 21cm;
            height: 29.7cm;
        } */

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

            div.pagebreak {
                page-break-inside: avoid;
            }

            @page {
                size: A4;
                margin-left: 0px;
                margin-right: 0px;
                margin-top: 0px;
                margin-bottom: 0px;
            }
        }

        p, td, th {
            font-size: 14px;
        }

        th {
            font-weight: 700;
            text-align: center;
            padding: 3px;
        }

        header {
            margin: 0cm 0cm;
        }
        main {
            margin: 0 1.2cm;
        }
        footer {
            bottom: 0;
            position: fixed;
        }

        .kop-surat {
            display: flex;
            justify-content: space-between;
        }
        .d-block {
            display: block;
        }
        .px-3 {
            padding: 0 1.2rem;
        }
        .mt-3 {
            margin-top: 1rem;
        }
        .pb-3 {
            padding-bottom: 1rem;
        }
        .table {
            width: 100%;
            border: 1px solid #000;
            border-collapse: collapse;
        }

        .table th, .table td {
            border: 1px solid #000;
            border-collapse: collapse;
            padding: 3px;
        }

        .v-align-top {
            vertical-align: top;
        }

        .text-center {
            text-align: center;
        }

        .text-bold {
            font-weight: 700;
        }

        .d-inline {
            display: inline;
        }
    </style>
    @yield('css')
</head>