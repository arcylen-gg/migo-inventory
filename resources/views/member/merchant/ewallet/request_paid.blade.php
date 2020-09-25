@if($mode == 2)
	<h3>Paid</h3>
	<h4>Request #{{$merchant_ewallet->merchant_ewallet_id}}</h4>
	<h5 style="color:green">{{currency('PHP', $merchant_ewallet->merchant_ewallet_amount)}}</h5>
	<a class="view_link" onClick="view_link('/member/merchant/ewallet/list?user_id={{$user_a->user_id}}&find=Paid&list=2')">[BACK]</a>
	<table class="table table-bordered">
		<tr>
			<td colspan="2">
				<label><small style="color: gray">Remarks</small></label><hr>
				{{$merchant_ewallet->merchant_ewallet_request_remarks}}
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<label><small style="color: gray">Proof of payment (Image)</small></label><hr>
				@if($merchant_ewallet->merchant_ewallet_request_proof != null)
				<a href="{{$merchant_ewallet->merchant_ewallet_request_proof}}" target="_blank">View Proof</a>
				@endif
			</td>
		</tr>
		@if($mode_a == 2)
		<tr>
			<td>
				<form method="post" action="/member/merchant/ewallet/request/update/submit" enctype="multipart/form-data">
				{!! csrf_field() !!}
					<input type="hidden" name="merchant_ewallet_id" value="{{$merchant_ewallet->merchant_ewallet_id}}">
					<input type="hidden" name="merchant_ewallet_status" value="Completed">
					<textarea class="form-control" name="merchant_ewallet_remarks"></textarea>
					<hr>
					<button class="btn btn-primary pull-right">Complete</button>
				</form>
			</td>
			<td>
				<form method="post" action="/member/merchant/ewallet/request/update/submit" enctype="multipart/form-data">
					{!! csrf_field() !!}
					<input type="hidden" name="merchant_ewallet_id" value="{{$merchant_ewallet->merchant_ewallet_id}}">
					<input type="hidden" name="merchant_ewallet_status" value="Denied">
					<textarea class="form-control" name="merchant_ewallet_remarks"></textarea>
					<hr>
					<button class="btn btn-danger pull-right">Deny</button>
				</form>	
			</td>
		</tr>
		@endif
	</table>
@elseif($mode == 3)
<h3>Completed</h3>
<h4>Request #{{$merchant_ewallet->merchant_ewallet_id}}</h4>
<h5 style="color:green">{{currency('PHP', $merchant_ewallet->merchant_ewallet_amount)}}</h5>
<a class="view_link" onClick="view_link('/member/merchant/ewallet/list?user_id={{$user_a->user_id}}&find=Completed&list=2')">[BACK]</a>
<table class="table table-bordered">
	<tr>
		<td colspan="2">
			<label><small style="color: gray">Remarks</small></label><hr>
			{{$merchant_ewallet->merchant_ewallet_request_remarks}}
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<label><small style="color: gray">Proof of payment (Image)</small></label><hr>
			@if($merchant_ewallet->merchant_ewallet_request_proof != null)
			<a href="{{$merchant_ewallet->merchant_ewallet_request_proof}}" target="_blank">View Proof</a>
			@endif
		</td>
	</tr>
</table>
@elseif($mode ==4)
<h3>Denied</h3>
<h4>Request #{{$merchant_ewallet->merchant_ewallet_id}}</h4>
<h5 style="color:green">{{currency('PHP', $merchant_ewallet->merchant_ewallet_amount)}}</h5>
<a class="view_link" onClick="view_link('/member/merchant/ewallet/list?user_id={{$user_a->user_id}}&find=Denied&list=2')">[BACK]</a>
<table class="table table-bordered">
	<tr>
		<td colspan="2">
			<label><small style="color: gray">Remarks</small></label><hr>
			{{$merchant_ewallet->merchant_ewallet_request_remarks}}
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<label><small style="color: gray">Proof of payment (Image)</small></label><hr>
			@if($merchant_ewallet->merchant_ewallet_request_proof != null)
			<a href="{{$merchant_ewallet->merchant_ewallet_request_proof}}" target="_blank">View Proof</a>
			@endif
		</td>
	</tr>
</table>

@else
	@if($user->user_is_merchant == 0)
	<h3>Update Request</h3>
	<h4>Request #{{$merchant_ewallet->merchant_ewallet_id}}</h4>
	<h5 style="color:green">{{currency('PHP', $merchant_ewallet->merchant_ewallet_amount)}}</h5>

	<a class="view_link" onClick="view_link('/member/merchant/ewallet/list?user_id={{$user_a->user_id}}&find=Requested&list=2')">[BACK]</a>
	<form method="post" action="/member/merchant/ewallet/request/update/submit" enctype="multipart/form-data">
	{!! csrf_field() !!}
	<input type="hidden" name="merchant_ewallet_id" value="{{$merchant_ewallet->merchant_ewallet_id}}">
	<input type="hidden" name="merchant_ewallet_status" value="Paid">
	<table class="table table-bordered">
		<tr>
			<td>
				<label><small style="color: gray">Remarks</small></label>
				<textarea class="form-control" name="merchant_ewallet_request_remarks" required="required"></textarea>
			</td>
		</tr>
		<tr>
			<td>
				<label><small style="color: gray">Proof of payment (Image)</small></label>
				<input type="file" name="merchant_ewallet_request_proof" accept="image/*">
			</td>
		</tr>
		<tr>
			<td>
				<button class="btn btn-primary pull-right">Submit</button>
			</td>
		</tr>
	</table>
	</form>
	@endif
@endif