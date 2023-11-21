<!DOCTYPE html>
<html>
    @include('layouts.admin.print.head')
    <body>
        {{-- <div class="container"> --}}
            @yield('body')
        {{-- </div> --}}
        @include('layouts.admin.print.js')
    </body>
</html>