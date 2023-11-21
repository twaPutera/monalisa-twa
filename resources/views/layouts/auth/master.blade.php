<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,300;0,400;0,700;0,800;1,500;1,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('custom-css/login.css') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo-Press-103x75.png') }}">

</head>
<body>
    <div style="height: 10vh">
        <div class="row">
            <div class="col-md-6">
                <div class="left">
                    <div class="header d-flex align-items-center" style="height: 40px;">
                        <img src="{{ asset('images/logo-Press-103x75.png') }}" width="40px" alt="">
                        <h5 class="text-primary ml-3 mb-0">Portal Login</h5>
                    </div>
                    <div class="body-form d-flex justify-content-center align-items-center">
                        @yield('content')
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="slide-custom">
                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                            {{-- <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li> --}}
                        </ol>
                        <div class="carousel-inner">
                            {{-- <div class="carousel-item active">
                                <img class="d-block w-100" src="{{ url('images/thumb-1.png') }}" alt="First slide">
                            </div> --}}
                            {{-- <div class="carousel-item">
                                <img class="d-block w-100" src="https://via.placeholder.com/400x300.png?text=2" alt="Second slide">
                            </div>
                            <div class="carousel-item">
                                <img class="d-block w-100" src="https://via.placeholder.com/400x300.png?text=3" alt="Third slide">
                            </div> --}}
                        </div>
                        {{-- <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script>
        $('#carouselExampleIndicators').carousel({
            interval: 2000
        });
    </script>
</body>
</html>