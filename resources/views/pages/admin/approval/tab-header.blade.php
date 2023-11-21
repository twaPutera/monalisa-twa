<ul class="nav nav-tabs  nav-tabs-line" role="tablist">
    <li class="nav-item">
        <a class="nav-link @if (\Request::segment(3) == 'peminjaman') active @endif"
            href="{{ route('admin.approval.peminjaman.index') }}">Peminjaman (<span
                id="peminjaman-approval-count">0</span>)</a>
    </li>
    @if (Auth::user()->role == 'manager_it' || Auth::user()->role == 'manager_asset' || Auth::user()->role == 'admin')
        <li class="nav-item dropdown">
            <a class="nav-link @if (\Request::segment(3) == 'pemutihan') active @endif"
                href="{{ route('admin.approval.pemutihan.index') }}">Penghapusan Asset (<span
                    id="pemutihan-approval-count">0</span>)</a>
        </li>
    @endif
    <li class="nav-item">
        <a class="nav-link @if (\Request::segment(3) == 'pemindahan') active @endif"
            href="{{ route('admin.approval.pemindahan.index') }}">Pemindahan (<span
                id="pemindahan-approval-count">0</span>)</a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if (\Request::segment(3) == 'request-inventori') active @endif"
            href="{{ route('admin.approval.request-inventori.index') }}">Request Bahan Habis Pakai (<span
                id="request-inventori-approval-count">0</span>)</a>
    </li>
</ul>
