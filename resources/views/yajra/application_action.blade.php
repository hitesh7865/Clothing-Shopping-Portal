<div class="hover-relative">
	@if($status == App\Enums\ApplicationStatus::QUESTIONED)
	<label class="status status_options status_hover jsRemind" data-app-id="{{$id}}">
	  <i class="glyphicon glyphicon-floppy-disk"></i>
	  Remind
	</label>
	@endif
	@if($status == App\Enums\ApplicationStatus::HIRED && $carbonate_synced == 0)
	<label class="status status_options status_hover jsCarbonateSync" data-app-id="{{$id}}">
	  <i class="glyphicon glyphicon-link"></i>
	  Add to Carbonate
	</label>
	@endif
	<label class="status status_options status_hover jsEditApplication" data-app-id="{{$id}}" data-name="{{$name}}"
	  data-rating="{{$rating}}">
	  <i class="glyphicon glyphicon-pencil"></i>
	  Edit
	</label>
</div>