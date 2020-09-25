@extends("member.member_layout")
@section("member_content")
<div class="member-order">
	<div class="main-member-header clearfix">
		<div class="animated fadeInLeft left">
			<div class="icon">
				<div class="brown-icon-orders"></div>
			</div>
			<div class="text">
				<div class="name">My Orders</div>
			</div>
		</div>
		<div class="animated fadeInRight right">
		</div>
	</div>
	<div class="order-content">
		@if(count($_order) > 0)
			@foreach($_order as $key => $order)
			<div class="animated fadeInUp holder">
				<table>
					<tr>
						<td class="img">
							<img src="/themes/{{ $shop_theme }}/img/product-placeholder.png">
						</td>
						<td class="info">
							<div class="first-row clearfix">
								<div class="name">{{ $order->transaction_number }}</div>
								<div class="button">
									<button class="btn btn-orange popup" type='button' link='/members/order-details/{{ $order->transaction_list_id }}' size='lg'>View Order Details</button>
								</div>
							</div>
							<table>
								<tr>
									<td class="detail-label">Payment Status:</td>
									<td class="detail-value"><b>{{ strtoupper($order->payment_status) }}</b></td>
								</tr>
								<tr>
									<td class="detail-label">Delivery Status:</td>
									<td class="detail-value"><b>{{ strtoupper($order->order_status) }}</b></td>
								</tr>
								<tr>
									<td class="detail-label">Customer Email:</td>
									<td class="detail-value">{{ $order->email }}</td>
								</tr>
								<tr>
									<td class="detail-label">Tracking Number:</td>
									<td class="detail-value">NO TRACKING NUMBER YET</td>
								</tr>
								
								<tr>
									<td class="detail-label">Payment Method:</td>
									<td class="detail-value"><b>{{ strtoupper($order->payment_method) }}</b></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
			@endforeach
		@else
			<div class="text-center" style="padding: 100px;">You don't have any order yet.</div>
		@endif
	</div>
</div>
@endsection
@section("member_script")
@endsection
@section("member_css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/member_order.css">
@endsection