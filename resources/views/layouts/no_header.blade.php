<html>

<head>
    @include('partials.meta')
    <title>@yield('title')</title>
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/vendors.css') }}" rel="stylesheet">

</head>

<body>
    <div class="page page_noheader {{ 'page_'  }}@yield('page_name')">
        <div class='page__block-ui'>
        </div>
        <div class="page__wrapper g-cleared g-bg_palegrey">
            @yield('content')
        </div>
        <footer class="footer-black-gradient">
        </footer>
    </div>

    @include('partials.global.bottom-scripts')
    @include('partials.flash')

</body>

</html>