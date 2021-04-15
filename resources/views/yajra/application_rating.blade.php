<div class="applications__rating">
    @for($i = 1; $i
    <=$rating; $i++) <i class="glyphicon glyphicon-star"></i>
        @endfor

        <div class="applications__unrated">
            @if(empty($rating)) --Unrated--
            @endif
        </div>
</div>