<ul class="kt-nav kt-nav--bold my-kt-nav-blue3 pt-0 shadow-custom">
    <li class="kt-nav__item">
        <a style="border-top-right-radius: 6px; border-top-left-radius: 6px;"
            @if (\Request::segment(3) == 'asset') class="kt-nav__link px-3 active" href="javascript:;"
        @else
            class="kt-nav__link px-3" href="{{ route('admin.pemutihan-asset.asset.index') }}" @endif>
            <span class="kt-nav__link-icon">
                <i class="fas fa-list"></i>
            </span>
            <span class="kt-nav__link-text">Asset Dalam Penghapusan</span> </a>
    </li>
    <li class="kt-nav__item">
        <a
            @if (\Request::segment(3) == 'bast') class="kt-nav__link px-3 active" href="javascript:;"
        @else
            class="kt-nav__link px-3" href="{{ route('admin.pemutihan-asset.index') }}" @endif>
            <span class="kt-nav__link-icon">
                <i class="fas fa-map"></i>
            </span>
            <span class="kt-nav__link-text">BA Penghapusan Asset</span> </a>
    </li>
</ul>
