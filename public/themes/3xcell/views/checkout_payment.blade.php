@extends("layout")
@section("content")
<div class="content">
	<div class="top-1-container">
		<div class="container">
			<div class="title-container">Payment</div>
		</div>
	</div>
	<div class="top-2-container">
		<div class="container">
			<div class="content-container row clearfix">
				<div class="col-md-8">
					<div class="title-container">
						<span>Login</span><span><i class="fa fa-angle-right" aria-hidden="true"></i>
						</span><span>Shipping</span><span><i class="fa fa-angle-right" aria-hidden="true"></i>
						</span><span style="color: #2161c8;">Payment</span></div>
					<!-- SHIPPING DETAILS -->
					<div class="mid-title-container">
						Payment
					</div>
					<!-- BILLING ADDRESS -->
					<div class="address-container">
						<div class="title-container">Billing Address:</div>
						<div class="detail-container">{{ $get_cart["tbl_customer"]["first_name"] . " " . $get_cart["tbl_customer"]["middle_name"] . " " .  $get_cart["tbl_customer"]["last_name"] }}</div>
						<div class="detail-container">{{ $get_cart["tbl_customer_address"]["shipping"]["customer_street"] }}</div>
						<div class="detail-container">{{ $get_cart["tbl_customer_address"]["shipping"]["customer_state"] }}, {{ $get_cart["tbl_customer_address"]["shipping"]["customer_city"] }}</div>
						<div class="detail-container">{{ $get_cart["tbl_customer_address"]["shipping"]["customer_zip_code"] }}</div>
						<div class="detail-container">Philippines</div>
					</div>
					<!-- SHIPPING ADDRESS -->
					<div class="address-container">
						<div class="title-container">Shipping Address:</div>
						<div class="detail-container">{{ $get_cart["tbl_customer"]["first_name"] . " " . $get_cart["tbl_customer"]["middle_name"] . " " .  $get_cart["tbl_customer"]["last_name"] }}</div>
						<div class="detail-container">{{ $get_cart["tbl_customer_address"]["shipping"]["customer_street"] }}</div>
						<div class="detail-container">{{ $get_cart["tbl_customer_address"]["shipping"]["customer_state"] }}, {{ $get_cart["tbl_customer_address"]["shipping"]["customer_city"] }}</div>
						<div class="detail-container">{{ $get_cart["tbl_customer_address"]["shipping"]["customer_zip_code"] }}</div>
						<div class="detail-container">Philippines</div>
					</div>
					<form method="post">
					{{ csrf_field() }}
						<div class="select-payment-container">
							<div class="title-container">Select A Payment Method</div>
							<select name="payment_method_id" class="select">
								{{-- <option value="">Paypal</option>
								<option value="">Bank Deposit</option>
								<option value="">FastPay</option>
								<option value="">Palawan Express</option> --}}
								@foreach($_payment_method as $payment_method)
								<option value="{{ $payment_method->method_id }}">{{ $payment_method->method_name }}</option>
								@endforeach
							</select>
							<div>
								<button style="border: 0;" type="submit" class="button-checkout">SUBMIT ORDER</button>
							</div>
						</div>
					</form>
				</div>
				<!-- CART SUMMARY -->
				<div class="col-md-4">
					<div class="order-summary-container">
						<div class="title-container">
							Order Summary
							<a href="/MyCart"><div class="edit-cart">Edit Cart</div></a>
						</div>
						<div class="per-order-container">
							@foreach($get_cart['cart'] as $key => $value)
							<!-- PER ITEM -->
							<div class="per-item-container row-no-padding clearfix">
								<div class="col-md-3">
									<!-- ITEM IMAGE -->
									<div class="image-container">
										<img src="{{ $value['cart_product_information']['image_path'] ? $value['cart_product_information']['image_path'] : '/assets/mlm/img/placeholder.jpg' }}">
									</div>
								</div>
								<div class="col-md-9">
									<!-- ITEM DETAILS -->
									<div class="item-detail-container">
										<div class="item-name text-overflow">{{ $value['cart_product_information']['product_name'] }}</div>
										<div class="bottom-detail row clearfix">
											<div class="col-md-4">
												<div class="price-container">
													<div class="title-price">Price:</div>
													<div class="price">PHP {{ number_format($value['cart_product_information']['product_current_price'], 2) }}</div>
												</div>
											</div>
											<div class="col-md-4">
												<div class="quantity-container">
													<input class="input-quantity" type="number" name="quantity" min="1" step="1" value="{{ $value['quantity'] }}" disabled="">
												</div>
											</div>
											<div class="col-md-4">
												<div class="total-container">
													<div class="title-price">Total:</div>
													<div class="price">PHP {{ number_format($value['cart_product_information']['product_current_price'] * $value["quantity"], 2) }}</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							@endforeach
						</div>
					</div>
					<div class="title-container">Cart Summary</div>
					<div class="cart-summary-container">
						<div class="subtotal-container">
							<div class="title-container">Subtotal:</div>
							<div class="subtotal">PHP {{ number_format($get_cart['sale_information']['total_product_price'], 2) }}</div>
						</div>
						<div class="total-container">
							<div class="title-container">Total:</div>
							<div class="subtotal">PHP {{ number_format($get_cart['sale_information']['total_overall_price'], 2) }}</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- SCROLL TO TOP -->
	<div class="scroll-up"><img src="/themes/{{ $shop_theme }}/img/scroll-up.png"></div>
</div>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/item_payment.css">
@endsection

@section("js")
<script type="text/javascript">
$(document).ready(function()
{
	/*scroll up*/
	$(window).scroll(function () {
        if ($(this).scrollTop() > 700) {
            $('.scroll-up').fadeIn();
        } else {
            $('.scroll-up').fadeOut();
        }
    });

    $('.scroll-up').click(function () {
        $("html, body").animate({
            scrollTop: 0
        }, 700);
        return false;
    });
});
</script>
@endsection