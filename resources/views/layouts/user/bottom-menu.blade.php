<div class="appBottomMenu" style="position: relative; z-index: 0; padding-bottom: 0;">
    <a href="{{ route('user.dashboard.index') }}" class="item @if(\Request::segment(3) == 'dashboard') active @endif">
        <div class="col">
            <ion-icon name="home-outline" role="img" class="md hydrated" aria-label="card outline"></ion-icon>
            <strong>Dashboard</strong>
        </div>
    </a>
    <a href="{{ route('user.asset-data.peminjaman.index') }}" class="item @if(\Request::segment(3) == 'peminjaman') active @endif">
        <div class="col">
            <ion-icon name="card-outline" role="img" class="md hydrated" aria-label="card outline"></ion-icon>
            <strong>Peminjaman</strong>
        </div>
    </a>
    <a href="{{ route('user.scan-qr.index') }}" class="item">
        <div class="col">
            <div class="action-button large">
                <ion-icon name="qr-code-outline" class="md hydrated"></ion-icon>
            </div>
        </div>
    </a>
    <a href="{{ route('user.pengaduan.index') }}" class="item @if(\Request::segment(2) == 'pengaduan') active @endif">
        <div class="col">
            <ion-icon name="megaphone-outline" class="md hydrated"></ion-icon>
            <strong>Pengaduan</strong>
        </div>
    </a>
    <a href="{{ route('user.dashboard.profile') }}" class="item @if(\Request::segment(2) == 'profile') active @endif">
        <div class="col">
            <ion-icon name="person-outline" role="img" class="md hydrated"></ion-icon>
            <strong>Profile</strong>
        </div>
    </a>
</div>