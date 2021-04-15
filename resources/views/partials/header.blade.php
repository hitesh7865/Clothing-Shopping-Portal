<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">
                {{-- <img src="{{ asset('/images/acloudery-white.png') }}"/> --}}
            </a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">

            <ul class="nav navbar-nav">
                {{--
                <li class=""><a href="/dashboard">Dashboard</a></li> --}}
                <li class="btn-group dropdown dropdown-invers custom_dropdown">
                    {{-- <a class="dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        Hi, {{auth()->user()->fullname}}
                        <span class="caret"></span>
                    </a> --}}

                    <h3 class="mb-0 mt-2" style="margin-top: 9px">YouRHired</h3>

                </li>

                {{--
                <li><a href="mailto:inquiry@acloudery.com"  class="btn btn_blue">Contact us</a> --}}
                </li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                {{--
                <li class=""><a href="/dashboard">Dashboard</a></li> --}}
                <li class="btn-group dropdown dropdown-invers custom_dropdown">
                    <a class="dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        Hi, {{auth()->user()->fullname}}
                        <span class="caret"></span>
                    </a>



                    <ul class="dropdown-menu">

                        <li>
                            <a href="/settings/profile" class="dropmenu-item">
                                <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                                <span class="dropmenu-item-label">Profile</span>
                            </a>
                        </li>
                        <div class="dropdown-divider divider"></div>
                        <li><a href="/logout" class="dropmenu-item">
                                <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>
                                <span class="dropmenu-item-label">Logout</span>
                                {{-- <span class="dropmenu-item-content">Ctrl+E</span> --}}
                            </a> {{-- <i class="glyphicon glyphicon-cog"></i> --}}
                        </li>
                    </ul>
                </li>

                {{--
                <li><a href="mailto:inquiry@acloudery.com"  class="btn btn_blue">Contact us</a> --}}
                </li>
            </ul>
        </div>
    </div>
</nav>