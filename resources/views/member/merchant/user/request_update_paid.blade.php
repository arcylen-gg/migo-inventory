
<h3>Verify Payment and mark as collected</h3>
<h4>Request #{{$merchant_commission_id}}</h4>
<h5 style="color:green">{{currency('PHP', $sum)}}</h5>
@if($back != null)
<a class="view_link" onClick="view_link('/member/merchant/commission/user/{{$user_id}}?commission=paid')">[BACK]</a>
@endif
<table class="table table-bordered">
	<tr>
		<td colspan="2">
			<label><small style="color: gray">Merchant Remark/s</small></label><br>
			{{$commission_data->merchant_commission_request_remarks}}
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<label><small style="color: gray">Proof of payment</small></label><br>
			@if($commission_data->merchant_commission_request_proof)
				<a href="{{$commission_data->merchant_commission_request_proof}}" target="_blank">View Proof</a>
			@else
				-No Proof of payment uploaded
			@endif
		</td>
	</tr>
	<tr>
		<td>
			<form method="post" action="/member/merchant/commission/user/request_submit/submit" enctype="multipart/form-data">
				{!! csrf_field() !!}
				<input type="hidden" name="commission" value="{{$commission}}">
				<input type="hidden" name="merchant_commission_id" value="{{$merchant_commission_id}}">
				<input type="hidden" name="merchant_commission_status" value="Approved">

				<label><small style="color: gray">Remark/s</small></label><br>
				<textarea name="merchant_commission_remarks" class="form-control"></textarea><br>
				<button class="btn btn-primary pull-right">Mark as Collected</button>
			</form>
		</td>
		<td>
			<form method="post" action="/member/merchant/commission/user/request_submit/submit" enctype="multipart/form-data">
				{!! csrf_field() !!}
				<input type="hidden" name="commission" value="{{$commission}}">
				<input type="hidden" name="merchant_commission_id" value="{{$merchant_commission_id}}">
				<input type="hidden" name="merchant_commission_status" value="Denied">

				<label><small style="color: gray">Remark/s</small></label><br>
				<textarea name="merchant_commission_remarks" class="form-control"></textarea><br>
				<button class="btn btn-danger pull-right">Deny</button>
			</form>
		</td>
	</tr>
</table>