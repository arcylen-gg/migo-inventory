@extends("layout")
@section("content")
<div class="content">
	<div class="top-1-container">
		<div class="container">
			<div class="title-container">Checkout</div>
		</div>
	</div>
	<div class="top-2-container">
		<div class="container">
			<div class="content-container row clearfix">
				<div class="col-md-8">
					<div class="title-container">
						<span>Login</span><span><i class="fa fa-angle-right" aria-hidden="true"></i>
						</span><span style="color: #2161c8;">Shipping</span><span><i class="fa fa-angle-right" aria-hidden="true"></i>
						</span><span>Payment</span></div>
					<!-- SHIPPING DETAILS -->
					<div class="mid-title-container">
						Shipping Details
					</div>
					<div class="fillup-container">
						<form method="post">
						<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />

							@if(session("error"))
							    <div class="alert alert-danger" style="margin-top: 25px;">
							        <ul>
							        	<li>{{ session("error") }}</li>
							        </ul>
							    </div>
							@endif

							@if (count($errors) > 0)
							    <div class="alert alert-danger" style="margin-top: 25px;">
							        <ul>
							            @foreach ($errors->all() as $error)
							            	@if($error == "Fail(Bank Declined Transaction)")
							                	<li>Your card was declined. In order to resolve the issue, you will need to contact your back</li>
							                @else
							                	<li>{{ $error }}</li>
							                @endif
							            @endforeach
							        </ul>
							    </div>
							@endif
						
							<div class="title-2">Full Name*</div>
							<div class="textbox">{{ $customer->first_name . ' ' . $customer->middle_name . ' ' . $customer->last_name }}</div>
							<div class="title-2">Contact Number*</div>
							<input maxlength="11" class="textbox" type="text" name="contact_number" value="{{ $customer->customer_mobile }}">
							<div class="title-2">Province*</div>
							<select firstload="true" default="{{ isset($shipping_address->state_id) ? $shipping_address->state_id : null }}" class="textbox load-location" name="customer_state" level="1"></select>
							<div class="title-2">City / Municipality*</div>
							<select firstload="true" default="{{ isset($shipping_address->city_id) ? $shipping_address->city_id : null }}" class="textbox load-location" name="customer_city" level="2">
								<option></option>
							</select>
							<div class="title-2">Barangay*</div>
							<select firstload="true" default="{{ isset($shipping_address->zipcode_id) ? $shipping_address->zipcode_id : null }}" class="textbox load-location" name="customer_zip" level="3">
								<option></option>
							</select>
							<!-- <div class="title-2">Email Address*</div>
							<input class="textbox" type="text" name="{{ $customer->email }}"> -->
							<div class="title-2">Complete Address*</div>
							<textarea spellcheck="false" class="textbox" name="customer_street">{{ isset($shipping_address->customer_street) ? $shipping_address->customer_street : null }}</textarea>
							<!-- <div class="title-container">Province</div>
							<div class="option-container"><span><input type="radio" id="r1" name="option">&nbsp;NCR</span><span><input type="radio" id="r2" name="option">&nbsp;Luzon</span><span><input type="radio" id="r3" name="option">&nbsp;Visayas</span><span><input type="radio" id="r4" name="option">&nbsp;Mindanao</span></div> -->
							<div class="shipping-fee"><span>Note:</span><br>Shipping fee is not included in the checkout price.</div>
							<button disabled type="submit" style="border: 0;" class="button-checkout checkout-button-submit">LOADING ...</button>
						</form>
					</div>
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
					<div class="shipping-fee"><span>Note:</span><br>PayPal will automatically return you to this website once the payment was finished. If not, kindly click the link provided by PayPal to return to this website to finish your transaction. Thank you. </div>
				</div>
			</div>
		</div>
	</div>

	<!-- SCROLL TO TOP -->
	<div class="scroll-up"><img src="/themes/{{ $shop_theme }}/img/scroll-up.png"></div>
</div>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/item_checkout.css">
<style type="text/css">
.textbox
{
	margin-top: 10px;
	padding: 8px;
	border: 1px solid #cbcbcb;
	color: #1c1c1c;
}
</style>
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
<script type="text/javascript">
var	input_quantity = ".input-quantity";
var rawprice = ".raw-price";
var subtotal = ".sub-total";
var maincontainer = ".cart-item-container";
var total = '.subtotal';
</script>
<script type="text/javascript" src="/assets/front/js/global_checkout.js"></script>
<script type="text/javascript" src="/themes/{{ $shop_theme }}/js/checkout.js"></script>
@endsection