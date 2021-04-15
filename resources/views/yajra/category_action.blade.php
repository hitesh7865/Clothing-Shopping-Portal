<div class="g-relative text-center {{!empty($carbonate_synced) && $carbonate_synced == 1 ? 'carbonate-synced' : ''}}">


    <span class="dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
        <div class="status status_{{ strtolower(str_replace(' ','-',\App\Enums\ApplicationStatus::getKeyById($status)))}}">
          <span>
             {{ \App\Enums\ApplicationStatus::getKeyById($status)}}
           </span>
    <span class="caret"></span>
</div>
{{-- <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>--}}
</span>



<ul class="dropdown-menu  dropdown-container dropdown-position-topright">
    @foreach(\App\Enums\ApplicationStatus::LIST_ALL as $enumStatus)
    <li class="{{ ($status == $enumStatus['id']) ? 'disabled' : ''}}">
        <a href="#" class="dropmenu-item jsUpdateCandidateStatus" data-app-id={{$id}} data-title="{{$title}}"
          data-status-id={{$enumStatus[ 'id']}}>
            @if($status == $enumStatus['id'])
            <span class="glyphicon glyphicon-check" aria-hidden="true"></span>
            @else
            <span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>
            @endif
            <span class="dropmenu-item-label">{{$enumStatus['value']}}</span> {{-- <span class="dropmenu-item-content">Ctrl+N</span>            --}}
        </a>

    </li>
    @if ($loop->last) {{--
    <div class="dropdown-divider divider"></div> --}}
    @endif
    @endforeach

</ul>
</div>