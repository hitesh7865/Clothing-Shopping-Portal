<div class="">

    @if(isset($negatives) && !empty($negatives))
    @foreach($negatives as $negative)
    <div class="status status_options">{{$negative}}</div>
    @endforeach
    @endif
</div>