<html>

<head>
    @include('partials.meta')
    <title>@yield('title')</title>
    <link href="{{ asset('css/main.css') }}?v={{env('ASSET_VERSION')}}" rel="stylesheet">
    <link href="{{ asset('dist/vendors.css') }}" rel="stylesheet">

</head>

<body>
  
    <div class="loader loader_bg">
        <div class="lds-ripple">
            <div></div>
            <div></div>
        </div>
    </div>
    <div class="page {{ 'page_'  }}@yield('page_name')">
        <div class='page__block-ui'>

        </div>
        <header class="header ">
            @include('partials.header')
        </header>
        <div class="page__wrapper  g-bg_palegrey ">
            <div class="container-fluid g-cleared">

               
                @include('partials.sidenav')    
                <div class="content default">
                    @yield('content')
                </div>
            </div>
        </div>
        {{--
        <footer class="footer-black-gradient">
        </footer> --}}
    </div>

    @include('partials.global.bottom-scripts')
    @include('partials.flash')

</body>

</html>