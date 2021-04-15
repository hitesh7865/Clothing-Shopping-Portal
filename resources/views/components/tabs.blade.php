<div class="tabs">
    @foreach($tabs as $tab)
    <a href="{{$tab['link']}}" class="tab {{ strpos(Request::url(),$tab['link']) ? 'active' : '' }}">
      {{$tab['title']}}
    </a>
    @endforeach

    @foreach($tabs as $tab)
    <div class="tab__content">
        @if(strpos(Request::url(),$tab[ 'link']))
        @php ($settings = $tab[ 'data'])
        @include($tab[ 'view'])
        @endif
    </div>
    @endforeach
</div>