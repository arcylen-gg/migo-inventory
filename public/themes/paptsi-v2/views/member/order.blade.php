@extends("member.member_layout")
@section("member_content")
<div class="member-order">
	<div class="main-member-header clearfix">
		<div class="left">
			<div class="icon">
				<div class="brown-icon-orders"></div>
			</div>
			<div class="text">
				<div class="name">My Orders</div>
			</div>
		</div>
		<div class="right">
			
		</div>
	</div>
	<div class="order-content">
		<div class="holder">
			<table>
				<tr>
					<td class="img">
						<img src="/themes/{{ $shop_theme }}/img/product-placeholder.png">
					</td>
					<td class="info">
						<div class="first-row clearfix">
							<div class="name">Order #1</div>
							<div class="button">
								<button class="btn btn-orange">View Invoice</button>
							</div>
						</div>
						<table>
							<tr>
								<td class="detail-label">Order Status:</td>
								<td class="detail-value">Pending</td>
							</tr>
							<tr>
								<td class="detail-label">Customer Email:</td>
								<td class="detail-value">julia@solidgroup.com.ph</td>
							</tr>
							<tr>
								<td class="detail-label">Payment Status:</td>
								<td class="detail-value">Unpaid</td>
							</tr>
							<tr>
								<td class="detail-label">Billing Address:</td>
								<td class="detail-value">20 Somerset St McKinley Hill Village Taguig City 1634, Pinagsamahan, Taguig City, Metro Manila</td>
							</tr>
							<tr>
								<td class="detail-label">Tracking Number:</td>
								<td class="detail-value">5432154321</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
		<div class="holder">
			<table>
				<tr>
					<td class="img">
						<img src="/themes/{{ $shop_theme }}/img/product-placeholder.png">
					</td>
					<td class="info">
						<div class="first-row clearfix">
							<div class="name">Order #1</div>
							<div class="button">
								<button class="btn btn-orange">View Invoice</button>
							</div>
						</div>
						<table>
							<tr>
								<td class="detail-label">Order Status:</td>
								<td class="detail-value">Pending</td>
							</tr>
							<tr>
								<td class="detail-label">Customer Email:</td>
								<td class="detail-value">julia@solidgroup.com.ph</td>
							</tr>
							<tr>
								<td class="detail-label">Payment Status:</td>
								<td class="detail-value">Unpaid</td>
							</tr>
							<tr>
								<td class="detail-label">Billing Address:</td>
								<td class="detail-value">20 Somerset St McKinley Hill Village Taguig City 1634, Pinagsamahan, Taguig City, Metro Manila</td>
							</tr>
							<tr>
								<td class="detail-label">Tracking Number:</td>
								<td class="detail-value">5432154321</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
		<div class="holder">
			<table>
				<tr>
					<td class="img">
						<img src="/themes/{{ $shop_theme }}/img/product-placeholder.png">
					</td>
					<td class="info">
						<div class="first-row clearfix">
							<div class="name">Order #1</div>
							<div class="button">
								<button class="btn btn-orange">View Invoice</button>
							</div>
						</div>
						<table>
							<tr>
								<td class="detail-label">Order Status:</td>
								<td class="detail-value">Pending</td>
							</tr>
							<tr>
								<td class="detail-label">Customer Email:</td>
								<td class="detail-value">julia@solidgroup.com.ph</td>
							</tr>
							<tr>
								<td class="detail-label">Payment Status:</td>
								<td class="detail-value">Unpaid</td>
							</tr>
							<tr>
								<td class="detail-label">Billing Address:</td>
								<td class="detail-value">20 Somerset St McKinley Hill Village Taguig City 1634, Pinagsamahan, Taguig City, Metro Manila</td>
							</tr>
							<tr>
								<td class="detail-label">Tracking Number:</td>
								<td class="detail-value">5432154321</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>
@endsection
@section("member_script")
@endsection
@section("member_css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/member_order.css">
@endsection