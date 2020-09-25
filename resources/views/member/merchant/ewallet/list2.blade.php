@if($r_list)
	@if(count($r_list) >= 1)
		<h3>{!! $headers !!}</h3>
		<h4>{{$user->user_first_name}} {{$user->user_last_name}}</h4>
		<table class="table table-bordered">
			<tr>
				<th>Id</th>
				<th>Date Requested</th>
				<th>Amount</th>
				<th></th>
			</tr>
			@foreach($r_list as $key => $value)
				<tr>
					<td>{{$value->merchant_ewallet_id}}</td>
					<td>{{$value->merchant_ewallet_request_date}}</td>
					<td>{{currency('PHP', $value->merchant_ewallet_amount)}}</td>
					<td>
						<a onclick="view_link('/member/merchant/ewallet/list?user_id={{$user->user_id}}&find={{$headers}}&merchant_ewallet_id={{$value->merchant_ewallet_id}}')">Breakdown</a>
						@if($value->merchant_ewallet_status == 'Requested')
							@if($user2->user_is_merchant == 0)
							<hr>
							<a onclick="view_link('/member/merchant/ewallet/request/update?user_id={{$user->user_id}}&find={{$headers}}&merchant_ewallet_id={{$value->merchant_ewallet_id}}')">Mark as paid</a>
							@endif
						@endif

						@if($value->merchant_ewallet_status == 'Paid')
							<hr>
							<a onclick="view_link('/member/merchant/ewallet/request/update?user_id={{$user->user_id}}&find={{$headers}}&merchant_ewallet_id={{$value->merchant_ewallet_id}}&mode=2')">View Details</a>
							@if($user2->user_is_merchant == 1)
							<hr>
							<a onclick="view_link('/member/merchant/ewallet/request/update?user_id={{$user->user_id}}&find={{$headers}}&merchant_ewallet_id={{$value->merchant_ewallet_id}}&mode=2&mode_a=2')">Complete/Deny</a>
							@endif
						@endif
						@if($value->merchant_ewallet_status == 'Completed')
							<hr>
							<a onclick="view_link('/member/merchant/ewallet/request/update?user_id={{$user->user_id}}&find={{$headers}}&merchant_ewallet_id={{$value->merchant_ewallet_id}}&mode=3')">View Details</a>
						@endif
						@if($value->merchant_ewallet_status == 'Denied')
							<hr>
							<a onclick="view_link('/member/merchant/ewallet/request/update?user_id={{$user->user_id}}&find={{$headers}}&merchant_ewallet_id={{$value->merchant_ewallet_id}}&mode=4')">View Details</a>
						@endif

					</td>
				</tr>
			@endforeach
		</table>
	@else
	<center>-No Data Found-</center>
	@endif
@else

@endif