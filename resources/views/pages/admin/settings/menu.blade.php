<ul class="kt-nav kt-nav--bold my-kt-nav-blue3 pt-0 shadow-custom">
    <li class="kt-nav__item">
        <a style="border-top-right-radius: 6px; border-top-left-radius: 6px;"
            @if (\Request::segment(3) == 'sistem-config') class="kt-nav__link px-3 active" href="javascript:;"
        @else
            class="kt-nav__link px-3" href="{{ route('admin.sistem-config.index') }}" @endif>
            <span class="kt-nav__link-icon">
                <i class="fas fa-cog"></i>
            </span>
            <span class="kt-nav__link-text">Sistem Config</span> </a>
    </li>
    <li class="kt-nav__item">
        <a
            @if (\Request::segment(3) == 'lokasi') class="kt-nav__link px-3 active" href="javascript:;"
        @else
            class="kt-nav__link px-3" href="{{ route('admin.setting.lokasi.index') }}" @endif>
            <span class="kt-nav__link-icon">
                <i class="fas fa-map"></i>
            </span>
            <span class="kt-nav__link-text">Master Lokasi</span> </a>
    </li>
    <li class="kt-nav__item">
        <a
            @if (\Request::segment(3) == 'kelas-asset') class="kt-nav__link px-3 active" href="javascript:;"
        @else
            class="kt-nav__link px-3" href="{{ route('admin.setting.kelas-asset.index') }}" @endif>
            <span class="kt-nav__link-icon">
                <i class="fas fa-layer-group"></i>
            </span>
            <span class="kt-nav__link-text">Kelas Asset</span> </a>
    </li>
    <li class="kt-nav__item">
        <a
            @if (\Request::segment(3) == 'group-kategori-asset') class="kt-nav__link px-3 active" href="javascript:;"
        @else
            class="kt-nav__link px-3" href="{{ route('admin.setting.group-kategori-asset.index') }}" @endif>
            <span class="kt-nav__link-icon">
                <i class="fas fa-object-group"></i>
            </span>
            <span class="kt-nav__link-text">Kelompok Asset</span> </a>
    </li>
    <li class="kt-nav__item">
        <a
            @if (\Request::segment(3) == 'kategori-asset') class="kt-nav__link px-3 active" href="javascript:;"
        @else
            class="kt-nav__link px-3" href="{{ route('admin.setting.kategori-asset.index') }}" @endif>
            <span class="kt-nav__link-icon">
                <i class="fas fa-list"></i>
            </span>
            <span class="kt-nav__link-text">Jenis Asset</span> </a>
    </li>
    <li class="kt-nav__item">
        <a
            @if (\Request::segment(3) == 'satuan-asset') class="kt-nav__link px-3 active" href="javascript:;"
        @else
            class="kt-nav__link px-3" href="{{ route('admin.setting.satuan-asset.index') }}" @endif>
            <span class="kt-nav__link-icon">
                <i class="fas fa-box"></i>
            </span>
            <span class="kt-nav__link-text">Satuan Asset</span> </a>
    </li>
    <li class="kt-nav__item">
        <a
            @if (\Request::segment(3) == 'vendor') class="kt-nav__link px-3 active" href="javascript:;"
        @else
            class="kt-nav__link px-3" href="{{ route('admin.setting.vendor.index') }}" @endif>
            <span class="kt-nav__link-icon">
                <i class="fas fa-building"></i>
            </span>
            <span class="kt-nav__link-text">Vendor</span> </a>
    </li>
    <li class="kt-nav__item">
        <a
            @if (\Request::segment(3) == 'kategori-inventori') class="kt-nav__link px-3 active" href="javascript:;"
        @else
            class="kt-nav__link px-3" href="{{ route('admin.setting.kategori-inventori.index') }}" @endif>
            <span class="kt-nav__link-icon">
                <i class="fas fa-list"></i>
            </span>
            <span class="kt-nav__link-text">Kategori Bahan Habis Pakai</span> </a>
    </li>
    <li class="kt-nav__item">
        <a
            @if (\Request::segment(3) == 'satuan-inventori') class="kt-nav__link px-3 active" href="javascript:;"
        @else
            class="kt-nav__link px-3" href="{{ route('admin.setting.satuan-inventori.index') }}" @endif>
            <span class="kt-nav__link-icon">
                <i class="fas fa-box"></i>
            </span>
            <span class="kt-nav__link-text">Satuan Bahan Habis Pakai</span> </a>
    </li>
    <li class="kt-nav__item">
        <a
            @if (\Request::segment(3) == 'kategori-service') class="kt-nav__link px-3 active" href="javascript:;"
        @else
            class="kt-nav__link px-3" href="{{ route('admin.setting.kategori-service.index') }}" @endif>
            <span class="kt-nav__link-icon">
                <i class="fas fa-list"></i>
            </span>
            <span class="kt-nav__link-text">Kategori Servis</span> </a>
    </li>
</ul>
