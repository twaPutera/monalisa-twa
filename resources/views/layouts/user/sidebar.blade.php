<div class="modal fade panelbox panelbox-left" id="sidebarPanel" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body p-0" style="background: #ECECF4;">
                <!-- profile box -->
                <div class="profileBox pt-2 pb-2">
                    <div class="image-wrapper">
                        <img src="https://ui-avatars.com/api/?name={{ $user->name ?? 'No Name' }}&background=5174ff&color=fff"
                            alt="image" class="imaged  w36">
                    </div>
                    <div class="in">
                        <strong>{{ $user->name }}</strong>
                        <div class="text-primary"><strong>{!! App\Helpers\CutText::cutUnderscore($user->role) !!}</strong></div>
                    </div>
                    <a href="#" class="btn btn-link btn-icon sidebar-close" data-bs-dismiss="modal">
                        <ion-icon name="close-outline"></ion-icon>
                    </a>
                </div>
                <!-- * profile box -->
                <!-- balance -->
                <div class="sidebar-balance">
                    <div class="listview-title">Layanan Aset</div>
                </div>
                <!-- * balance -->

                <!-- action group -->
                <div class="action-group justify-content-start">
                    <a href="{{ route('user.pengaduan.create') }}" class="action-button mx-2">
                        <div class="in">
                            <div class="iconbox">
                                <ion-icon name="document-outline"></ion-icon>
                            </div>
                            Adukan
                        </div>
                    </a>
                    <a href="{{ route('user.asset-data.peminjaman.create') }}" class="action-button mx-2">
                        <div class="in">
                            <div class="iconbox">
                                <ion-icon name="add-circle-outline"></ion-icon>
                            </div>
                            Pinjam
                        </div>
                    </a>
                    <a href="{{ route('user.asset-data.bahan-habis-pakai.create') }}" class="action-button mx-2">
                        <div class="in">
                            <div class="iconbox">
                                <ion-icon name="cube-outline"></ion-icon>
                            </div>
                            Permintaan
                        </div>
                    </a>
                    <a href="{{ route('user.scan-qr.index') }}" class="action-button mx-2">
                        <div class="in">
                            <div class="iconbox">
                                <ion-icon name="grid-outline"></ion-icon>
                            </div>
                            Scan
                        </div>
                    </a>
                </div>
                <!-- * action group -->

                <!-- menu -->
                <div class="listview-title mt-1">Menu</div>
                <ul class="listview flush transparent no-line image-listview">
                    <li>
                        <a href="{{ route('user.pengaduan.index') }}" class="item">
                            <div class="icon-box bg-primary">
                                <ion-icon name="pencil-outline"></ion-icon>
                            </div>
                            <div class="in">
                                Daftar Aduan
                                {{-- <span class="badge badge-primary">10</span> --}}
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.asset-data.bahan-habis-pakai.index') }}" class="item">
                            <div class="icon-box bg-primary">
                                <ion-icon name="cube-outline"></ion-icon>
                            </div>
                            <div class="in">
                                Daftar Permintaan
                                {{-- <span class="badge badge-primary">10</span> --}}
                            </div>
                        </a>
                    </li>
                </ul>
                <!-- * menu -->

                <!-- others -->
                <div class="listview-title mt-1">Lainnya</div>
                <ul class="listview flush transparent no-line image-listview">
                    <li>
                        <a href="{{ route('user.dashboard.about') }}" class="item">
                            <div class="icon-box bg-primary">
                                <ion-icon name="information-circle-outline"></ion-icon>
                            </div>
                            <div class="in">
                                Tentang Aplikasi
                            </div>
                        </a>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('sso.logout') }}">
                            @csrf
                            <a class="item" href="#"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="log-out-outline"></ion-icon>
                                </div>
                                <div class="in">
                                    Log out
                                </div>
                            </a>
                        </form>

                    </li>


                </ul>
                <!-- * others -->

            </div>
        </div>
    </div>
</div>
