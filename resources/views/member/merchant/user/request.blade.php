<h3>{{$header}}</h3>
@if($merchant_commission)
	@if(count($merchant_commission) >= 1)
	<table class="table table-bordered">
		<tr>
			<th>Request #</th>
			<th>Amount</th>
			<th>Date Requested</th>
			@if($commission != 'requested')
			<th>Admin Remarks</th>
			<th>Merchant Remarks</th>
			@endif
			<th></th>
		</tr>

		<tbody>
			@foreach($merchant_commission as $key => $value)
				<tr>
					<td>{{$value->merchant_commission_id}}</td>
					<td>{{currency('PHP', $value->merchant_commission_amount)}}</td>
					<td>{{$value->merchant_commission_request_date}}</td>
					@if($commission != 'requested')
						<td>{{$value->merchant_commission_remarks}}</td>
						<td>{{$value->merchant_commission_request_remarks}}</td>
					@endif
					<td>
						<a onClick="view_link('/member/merchant/commission/user/{{$value->merchant_commission_user_send_request }}?commission={{$commission}}&merchant_commission_id={{$value->merchant_commission_id}}')">Breakdown</a>
						@if($value->merchant_commission_status == 'Requested')
							@if($current_user == $value->merchant_commission_user_send_request)
								<hr>
								<a onClick="view_link('/member/merchant/commission/user/request_update/{{$value->merchant_commission_user_send_request}}?commission=requested&merchant_commission_id={{$value->merchant_commission_id}}')">Mark as Paid</a>
							@endif
						@elseif($value->merchant_commission_status == 'Paid')
							@if($current_user != $value->merchant_commission_user_send_request)
								<hr>
								<a onClick="view_link('/member/merchant/commission/user/request_update/{{$value->merchant_commission_user_send_request}}?commission=paid&merchant_commission_id={{$value->merchant_commission_id}}')">Mark as Collected/Denied</a>
							@endif
						@endif
						@if($commission != 'requested')
							@if($value->merchant_commission_request_proof)
								<hr><a href="{{$value->merchant_commission_request_proof}}" target="_blank">View Proof</a>
							@else
								-No Proof of payment uploaded
							@endif
						@endif
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
	@else
	<center>-No Data Found-</center>
	@endif
@else


@endif