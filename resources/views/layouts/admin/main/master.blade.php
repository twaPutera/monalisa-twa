<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('layouts.admin.main.head')

<body style="background: #FFF" class="@yield('class-body', 'kt-page--fixed kt-page-content-white kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-subheader--enabled kt-subheader--transparent kt-page--loading')">

    <!-- begin:: Header Mobile -->
    <div id="kt_header_mobile" class="kt-header-mobile mb-lg-0 mb-2 kt-header-mobile--fixed ">
        <div class="kt-header-mobile__logo">
            <a href="#">
                <img alt="Logo" src="{{ asset('assets/images/logo-Press-103x75.png') }}" width="60px" />
            </a>
        </div>
        <div class="kt-header-mobile__toolbar">
            <button class="kt-header-mobile__toolbar-toggler" id="kt_header_mobile_toggler"><span></span></button>
            <button class="kt-header-mobile__toolbar-topbar-toggler" id="kt_header_mobile_topbar_toggler"><i
                    class="flaticon-more-1 text-primary"></i></button>
        </div>
    </div>

    <div class="kt-grid kt-grid--hor kt-grid--root">
        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">
            <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper " id="kt_wrapper">
                @include('layouts.admin.main.header-menu')
                <div class="kt-grid__item kt-grid__item--fluid shadow-custom kt-grid kt-grid--ver kt-grid--stretch"
                    style="border-bottom-left-radius: 6px !important; border-bottom-right-radius: 6px !important;">
                    <div class="kt-container kt-body  kt-grid kt-grid--ver"
                        style="padding: 30px; border-bottom-left-radius: 6px !important; border-bottom-right-radius: 6px !important;"
                        id="kt_body">
                        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor"
                            style="border-bottom-left-radius: 6px !important; border-bottom-right-radius: 6px !important;">
                            @yield('subheader')
                            @yield('main-content')
                        </div>
                    </div>
                </div>
                @include('layouts.admin.main.footer')

            </div>
        </div>
    </div>

    <div id="kt_scrolltop" class="kt-scrolltop">
        <i class="fa fa-arrow-up"></i>
    </div>
    @include('layouts.admin.main.js')

    <div class="backdrop" style="display: none;">
        <div class="loader"></div>
    </div>
</body>

</html>
