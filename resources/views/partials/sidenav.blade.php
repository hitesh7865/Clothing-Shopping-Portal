<nav class="sidenav">


    <div class="container-fluid">
        <div class='sidenav__menu'>
            <ul url="{{Route::current()->getName()}}">
                @foreach($sidenav_items as $item)
                <li path="{{Request::url()}}" test="{{strpos(Request::path(),$item[ 'link'])}}" link="{{$item['link']}}"
                  class="{{ isset($item['child']) ? 'has-sub' : '' }} {{ strpos(Request::url(),$item['link']) ? 'active' : '' }}"><a href="{{$item['link']}}"><span>{{$item['text']}}</span></a>
                    @if(isset($item[ 'child']))
                    <ul>
                        @foreach($item[ 'child'] as $subitem)
                        <li class="{{ ($loop->last) ? 'last' : '' }} {{ strpos(Request::url(),$subitem['link']) ? 'active' : '' }}"><a href="{{$subitem['link']}}"><span>{{ $subitem['text'] }}</span></a></li>
                        @endforeach
                    </ul>
                    @endif
                </li>

                @endforeach
            </ul>
        </div>
    </div>
</nav>