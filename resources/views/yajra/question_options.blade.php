<div class="">

    @if(isset($options) && !empty($options))
    @foreach($options as $option)
    <div class="status status_options">{{$option}}</div>
    @endforeach
    @endif

</div>