<h3>Request to Collect E-wallet Reciebables</h3>
@if($user)
	<form method="post" action="/member/merchant/ewallet/request/verfiy/submit">
	{!! csrf_field() !!}	
	<input type="hidden" name="user_id" value="{{$user->user_id}}">
		<div class="col-md-12" style="text-transform: uppercase;">
			<h4>{{$user->user_first_name}} {{$user->user_last_name}}</h4>
		</div>
		<div class="col-md-4">
			<label style="color:gray"><small>From</small></label>
			<input type="date" class="form-control request_from" name="request_from">
		</div>
		<div class="col-md-4">
		<label style="color:gray"><small>To</small></label>
			<input type="date" class="form-control request_to" name="request_to">
		</div>
		<div class="col-md-4">
			<label style="color:gray"><small>Checking</small></label><br>
			<a id="check_income" onClick="check_commission_range({{$user->user_id}})" class="btn btn-primary col-md-12">Verify</a>
		</div>
		<div class="col-md-12 verify_commision_div"></div>
	</form>

@else
	<center>-Invalid User-</center>
@endif	