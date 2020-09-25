<div class="title">Order Summary</div>
<div class="summary">
	@if (session('fail'))
	    <div class="alert alert-danger">
	    	@if(is_array(session('fail')))
	    		<ul>
		        @foreach(session('fail') as $fail)
	        		<li style="display: block;">{{ $fail }}</li>
		        @endforeach
		        </ul>
		    @else
		    	<ul style="padding: 0; margin: 0;">
		    		<li style="display: block;">{{ session('fail') }}</li>
		    	</ul>
	        @endif
	    </div>
	@endif
	<div class="c-qty">You have {{ count($get_cart["cart"]) }} item in your cart.</div>
	<table class="table">
		<thead>
			<tr>
				<th>PRODUCT</th>
				<th>QTY</th>
				<th>PRICE</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			@foreach($get_cart["cart"] as $cart)
			<tr>
				<td class="name">{{ $cart["cart_product_information"]["product_name"] }}</td>
				<td class="qty">{{ $cart["quantity"] }}</td>
				<td class="price">&#8369; {{ number_format($cart['quantity'] * $cart["cart_product_information"]["product_price"], 2) }}</td>
				<td style="padding-left: 10px;"><a style="color: red;" href="/cart/remove?redirect=1&variation_id={{ $cart["product_id"] }}"><i class="fa fa-close"></i></a></td>
			</tr>
			@endforeach
		</tbody>
	</table>
	<div class="total">
		<table>
			<tbody>
				@if($get_cart["tbl_ec_order"]["shipping_fee"] != 0 || $get_cart["tbl_ec_order"]["service_fee"] != 0)
				<tr>
					<td>Subtotal</td>
					<td class="table-sub-total text-right">&#8369; {{ number_format($get_cart["tbl_ec_order"]["subtotal"], 2) }}</td>
				</tr>
				@endif

				@if($get_cart["tbl_ec_order"]["shipping_fee"] != 0)
				<tr>
					<td>Shipping Fee</td>
					<td class="table-ship-fee text-right">&#8369; {{ number_format($get_cart["tbl_ec_order"]["shipping_fee"], 2) }}</td>
				</tr>
				@endif

				@if($get_cart["tbl_ec_order"]["service_fee"] != 0)
				<tr>
					<td>Transaction Fee</td>
					<td class="table-ship-fee text-right">&#8369; {{ number_format($get_cart["tbl_ec_order"]["service_fee"], 2) }}</td>
				</tr>
				@endif

				<div class="hide {{$disc = 0}}"></div>
				@if($get_cart["tbl_ec_order"]["discount_coupon_amount"] != 0)
				<tr class="{{$disc = $get_cart['tbl_ec_order']['discount_coupon_amount']}}">
					<td>Coupon Discount</td>
					<td class="table-ship-fee text-right">&#8369; {{ number_format($get_cart["tbl_ec_order"]["discount_coupon_amount"], 2) }}</td>
				</tr>
				@endif

				<tr>
					<td>TOTAL</td>
					<td class="table-total text-right">&#8369; {{ number_format($get_cart["tbl_ec_order"]["total"] - $disc, 2) }}</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>