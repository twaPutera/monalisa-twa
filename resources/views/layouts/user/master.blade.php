<!doctype html>
<html lang="en">

<head>
    @include('layouts.user.head')
</head>

<body>

    <!-- loader -->
    <div id="loader">
        <img src="/assets/user/img/loading-icon.png" alt="icon" class="loading-icon">
    </div>
    <!-- * loader -->
    <div class="loadingSpiner" style="position: absolute; background: rgb(0, 0, 0, 0.3); height: 100vh; width: 100%; z-index: 99999; top: 0, left: 0; display: none;">
        <div class="spinner-border text-primary" role="status" style="position: absolute; top: 48%; left: 48%;"></div>
    </div>
    <!-- App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="#" class="headerButton" data-bs-toggle="modal" data-bs-target="#sidebarPanel">
                <ion-icon name="menu-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">@yield('page-title')</div>
        <div class="right">
            <a href="{{ route('user.notification.index') }}" class="headerButton">
                <ion-icon class="icon" name="notifications-outline"></ion-icon>
                <span class="badge badge-danger" id="countNotif">0</span>
            </a>
            <a href="{{ route('user.dashboard.profile') }}" class="headerButton">
                <img src="https://ui-avatars.com/api/?name={{ $user->name ?? 'No Name' }}&background=5174ff&color=fff" alt="image" class="imaged w32">
            </a>
        </div>
    </div>

    <div id="toastDanger" class="toast-box toast-bottom bg-danger">
        <div class="in">
            <div class="text toastText">
                Auto close in 2 seconds
            </div>
        </div>
    </div>

    <div id="toastSuccess" class="toast-box toast-bottom bg-success">
        <div class="in">
            <div class="text toastText">
                Auto close in 2 seconds
            </div>
        </div>
    </div>

    <div class="modal fade dialogbox" id="DialogIconedDanger" data-bs-backdrop="static" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-icon text-danger">
                    <ion-icon name="close-circle"></ion-icon>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title" id="alertDangerTitle">Error</h5>
                </div>
                <div class="modal-body" id="alertDangerBody">
                    There is something wrong.
                </div>
                <div class="modal-footer">
                    <div class="btn-inline">
                        <a href="#" class="btn" data-bs-dismiss="modal">CLOSE</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- * App Header -->


    <!-- App Capsule -->
    <div id="appCapsule">
        @yield('content')
        <!-- app footer -->
        {{-- <div class="appFooter">
            <div class="footer-title">
                Copyright © Finapp 2021. All Rights Reserved.
            </div>
            Bootstrap 5 based mobile template.
        </div> --}}
        <!-- * app footer -->

    </div>
    <!-- * App Capsule -->


    <!-- App Bottom Menu -->
    <div class="appBottomMenu">
        @include('layouts.user.bottom-menu')
    </div>
    <!-- * App Bottom Menu -->

    <!-- App Sidebar -->
    @include('layouts.user.sidebar')
    <!-- * App Sidebar -->

    <!-- ========= JS Files =========  -->
    @include('layouts.user.js')

</body>

</html>