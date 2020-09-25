<form class="global-submit" action="/member/maintenance/online_payment/save-gateway-other" method="">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<input type="hidden" name="other_id" value="{{$other->other_id or ''}}">
	<label> Other Information </label>
	<div class="col-md-12 form-group">
		<div class="">
		    <label>Name</label>
		    <input type="text" name="other_name" class="form-control input-sm" value="{{$other->other_name or ''}}" required>
		</div>
	</div>
	<div class="col-md-12 form-group">
		<div class="">
		    <label>Landing Page Message</label>
		    <textarea type="text" name="other_description" class="form-control input-sm" required>{{$other->other_description or ''}}</textarea>
		</div>
	</div>
	<div class="col-md-12 form-group">
		<button type="submit" class="panel-buttons btn btn-custom-primary pull-right">save</button>
	</div>
</form>