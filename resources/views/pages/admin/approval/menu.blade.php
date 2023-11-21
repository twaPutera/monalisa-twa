<ul class="kt-nav kt-nav--bold my-kt-nav-blue3 pt-0 shadow-custom">
    <li class="kt-nav__item">
        <a style="border-top-right-radius: 6px; border-top-left-radius: 6px;"
            @if (\Request::segment(3) == 'daftar') class="kt-nav__link px-3 active" href="javascript:;"
        @else
            class="kt-nav__link px-3" href="{{ route('admin.approval.daftar.index') }}" @endif>
            <span class="kt-nav__link-icon">
                <i class="fas fa-thumbs-up"></i>
            </span>
            <span class="kt-nav__link-text">Daftar Approval</span> </a>
    </li>
    <li class="kt-nav__item">
        <a
            @if (\Request::segment(3) == 'history') class="kt-nav__link px-3 active" href="javascript:;"
        @else
            class="kt-nav__link px-3" href="{{ route('admin.approval.history.index') }}" @endif>
            <span class="kt-nav__link-icon">
                <i class="fas fa-history"></i>
            </span>
            <span class="kt-nav__link-text">History Approval</span> </a>
    </li>
</ul>
