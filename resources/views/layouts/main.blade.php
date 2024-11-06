<!DOCTYPE html>
<html lang="en">

@include('components.head')

<body>
    @include('components.navbar')

    @yield('content')

    @include('components.footer')
    @stack('scripts')
</body>

</html>
