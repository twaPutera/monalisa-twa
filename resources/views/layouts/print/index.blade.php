<!DOCTYPE html>
<html>
@include('layouts.print.head')
<body>
    @yield('header')
    @yield('footer')
    <main>
        @yield('body')
    </main>
    @include('layouts.print.js')
</body>
</html>