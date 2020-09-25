@extends("member.member_layout")
@section("member_content")
<div class="member-wishlist">
	<div class="left">
		<div class="wishlist-title">My Wishlist</div>
	</div>
	<div class="table-holder table-responsive">
		<table class="table">
			<thead>
				<tr>
					<th>Remove</th>
					<th>Image</th>
					<th>Product Name</th>
					<th>Unit Price</th>
					<th width="150px">Quantity</th>
					<th>Total</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="remove-holder">
						<a href="javascript:"><img src="/themes/{{ $shop_theme }}/img/remove.png"></a>
					</td>
					<td class="img-holder">
						<img src="/themes/{{ $shop_theme }}/img/wishlist-1.jpg">
					</td>
					<td class="name-holder">Brown 1 Membership Kit</td>
					<td class="price-holder">P 9,500.00</td>
					<td class="quantity-holder">
						<div class="another">
							<div class="equal small"><button class="control">-</button></div>
							<div class="equal">
								<input class="form-control" type="text" name="">
							</div>
							<div class="equal small"><button class="control">+</button></div>
						</div>
					</td>
					<td class="total-holder">P 9,500.00</td>
					<td class="add-holder">
						<button class="btn btn-orange">Add to Cart</button>
					</td>
				</tr>
				<tr>
					<td class="remove-holder">
						<a href="javascript:"><img src="/themes/{{ $shop_theme }}/img/remove.png"></a>
					</td>
					<td class="img-holder">
						<img src="/themes/{{ $shop_theme }}/img/wishlist-1.jpg">
					</td>
					<td class="name-holder">Brown 1 Membership Kit</td>
					<td class="price-holder">P 9,500.00</td>
					<td class="quantity-holder">
						<div class="another">
							<div class="equal small"><button class="control">-</button></div>
							<div class="equal">
								<input class="form-control" type="text" name="">
							</div>
							<div class="equal small"><button class="control">+</button></div>
						</div>
					</td>
					<td class="total-holder">P 9,500.00</td>
					<td class="add-holder">
						<button class="btn btn-orange">Add to Cart</button>
					</td>
				</tr>
				<tr>
					<td class="remove-holder">
						<a href="javascript:"><img src="/themes/{{ $shop_theme }}/img/remove.png"></a>
					</td>
					<td class="img-holder">
						<img src="/themes/{{ $shop_theme }}/img/wishlist-1.jpg">
					</td>
					<td class="name-holder">Brown 1 Membership Kit</td>
					<td class="price-holder">P 9,500.00</td>
					<td class="quantity-holder">
						<div class="another">
							<div class="equal small"><button class="control">-</button></div>
							<div class="equal">
								<input class="form-control" type="text" name="">
							</div>
							<div class="equal small"><button class="control">+</button></div>
						</div>
					</td>
					<td class="total-holder">P 9,500.00</td>
					<td class="add-holder">
						<button class="btn btn-orange">Add to Cart</button>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
@endsection
@section("member_script")
@endsection
@section("member_css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/member_wishlist.css">
@endsection