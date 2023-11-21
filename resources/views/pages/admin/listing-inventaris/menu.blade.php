<ul class="kt-nav kt-nav--bold my-kt-nav-blue3 pt-0 shadow-custom">
    <li class="kt-nav__item">
        <a style="border-top-right-radius: 6px; border-top-left-radius: 6px;"
            @if (\Request::segment(3) == 'listing-data') class="kt-nav__link px-3 active" href="javascript:;"
        @else
            class="kt-nav__link px-3" href="{{ route('admin.listing-inventaris.index') }}" @endif>
            <span class="kt-nav__link-icon">
                <i class="fas fa-box"></i>
            </span>
            <span class="kt-nav__link-text">Listing Bahan Habis Pakai</span> </a>
    </li>
    <li class="kt-nav__item">
        <a
            @if (\Request::segment(3) == 'permintaan') class="kt-nav__link px-3 active" href="javascript:;"
        @else
            class="kt-nav__link px-3" href="{{ route('admin.permintaan-inventaris.index') }}" @endif>
            <span class="kt-nav__link-icon">
                <i class="fas fa-file"></i>
            </span>
            <span class="kt-nav__link-text">Permintaan Bahan Habis Pakai</span> </a>
    </li>
</ul>
