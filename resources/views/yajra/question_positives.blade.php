<div class="">

    @if(isset($positives) && !empty($positives))
    @foreach($positives as $positive)
    <div class="status status_options">{{$positive}}</div>
    @endforeach
    @endif


</div>