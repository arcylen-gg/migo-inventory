
<h3>Update Request</h3>
<h4>Request #{{$merchant_commission_id}}</h4>
<h5 style="color:green">{{currency('PHP', $sum)}}</h5>
@if($back != null)
<a class="view_link" onClick="view_link('/member/merchant/commission/user/{{$user_id}}?commission=paid')">[BACK]</a>
@endif
<form method="post" action="/member/merchant/commission/user/request_submit/submit" enctype="multipart/form-data">
{!! csrf_field() !!}
<input type="hidden" name="commission" value="{{$commission}}">
<input type="hidden" name="merchant_commission_id" value="{{$merchant_commission_id}}">
<input type="hidden" name="merchant_commission_status" value="Paid">
<table class="table table-bordered">
	<tr>
		<td>
			<label><small style="color: gray">Remarks</small></label>
			<textarea class="form-control" name="merchant_commission_request_remarks" required="required"></textarea>
		</td>
	</tr>
	<tr>
		<td>
			<label><small style="color: gray">Proof of payment (Image)</small></label>
			<input type="file" name="merchant_commission_request_proof" accept="image/*">
		</td>
	</tr>
	<tr>
		<td>
			<button class="btn btn-primary pull-right">Submit</button>
		</td>
	</tr>
</table>
</form>