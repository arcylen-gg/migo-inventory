@extends("layout")
@section("content")
<div class="content">
	<div class="top-container">
		<div class="container">
			<div class="top-container-content">Payment Summary</div>
		</div>
	</div>
	<div class="bottom-container">
		<div class="container">
			<div class="bottom-container-content">
				<div class="row clearfix">
					<!-- DELIVERY INFORMATION -->
					<div class="col-md-8">
						<div class="delivery-information">
							<div class="title-container">
								Fill Up Delivery Information
							</div>
							<div class="form-container">
								<div class="form-label">Province</div>
								<div class="form-input">
									<select name="customer_state">
										<option value="" hidden>Select Province</option>
										@foreach($_locale as $locale)
										<option value="{{ $locale->locale_name }}">{{ $locale->locale_name }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="form-container">
								<div class="form-label">Complete Shipping Address</div>
								<div class="form-input">
										<textarea placeholder="Type your complete address here"></textarea>
								</div>
							</div>
						</div>
						<!-- FILL UP DONE -->
						<div class="delivery-information" style="display: none;">
							<div class="title-container">
								Delivery Information
							</div>
							<!-- BILLING ADDRESS -->
							<div class="address-container">
								<div class="address-title">Billing Address:</div>
								<ul>
									<li>Mr. Brown</li>
									<li>2285 Chino Roces Ave</li>
									<li>Makati City</li>
									<li>Makati City, 1630</li>
									<li>Philippines</li>
								</ul>
							</div>
							<!-- SHIPPING ADDRESS -->
							<div class="address-container">
								<div class="address-title">Shipping Address:</div>
								<ul>
									<li>Mr. Brown</li>
									<li>2285 Chino Roces Ave</li>
									<li>Makati City</li>
									<li>Makati City, 1630</li>
									<li>Philippines</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<!-- CART SUMMARY -->
						<div class="cart-summary">
							<div class="top-title row-no-padding clearfix">
								<div class="col-md-4">
									<div class="per-title" style="border-bottom: 2px solid #63b944;">Product</div>									
								</div>
								<div class="col-md-4">
									<div class="per-title" style="border-bottom: 2px solid #ef5525;">Quantity</div>
								</div>
								<div class="col-md-4">
									<div class="per-title" style="border-bottom: 2px solid #6075f7;">Price</div>
								</div>
							</div>
							<!-- PER ITEM SUMMARY -->
							<div class="per-summary-content row-no-padding clearfix">
								<div class="col-md-4">
									<div class="per-summary-details">Brown 1</div>
								</div>
								<div class="col-md-4">
									<div class="per-summary-details">1</div>
								</div>
								<div class="col-md-4">
									<div class="per-summary-details">P 9,500.00</div>
								</div>
							</div>
							<!-- PER ITEM SUMMARY -->
							<div class="per-summary-content row-no-padding clearfix">
								<div class="col-md-4">
									<div class="per-summary-details">Eon Card</div>
								</div>
								<div class="col-md-4">
									<div class="per-summary-details">1</div>
								</div>
								<div class="col-md-4">
									<div class="per-summary-details">Free</div>
								</div>
							</div>
							<!-- SUMMARY TOTAL CONTAINER -->
							<div class="total-container row clearfix">
								<!-- SUBTOTAL -->
								<div class="col-md-6">
									<div class="left-detail">Subtotal</div>
								</div>
								<div class="col-md-6">
									<div class="right-detail">P 9,500.00</div>
								</div>
								<!-- TOTAL -->
								<div class="col-md-6">
									<div class="left-detail">Total</div>
								</div>
								<div class="col-md-6">
									<div class="right-detail">P 9,500.00</div>
								</div>
							</div>
							<!-- SHIPPING FEE -->
							<div class="shipping-fee">Shipping Fee is included</div>
						</div>
						<!-- PAYMENT OPTION -->
						<div class="payment-option">
							<div class="top-title">How do you want to pay?</div>
							<div class="option">
								<div class="form-input">
									<select >
										<option value="" hidden>Select Payment Method</option>
										<option value="volvo">Paymaya</option>
										<option value="saab">Bank Deposit</option>
									</select>	
								</div>
							</div>
							<div class="button-container">
								<div class="button-proceed" id="proceed">Proceed</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- POP UP -->
	<div class="popup-enter-a-code">
      <div id="myModal2" class="modal fade">
          <div class="modal-sm modal-dialog">
              <div class="modal-content">
              	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <div class="modal-header">
                      <h4 class="modal-title">Done!</h4>
                  </div>
                  <div class="modal-body">
 					<img src="/themes/{{ $shop_theme }}/img/success-1.png">
 					<p>
						Please check your email for
						further instructions.
 					</p>
 					<div class="button-container">
						<div class="button-proceed" data-dismiss="modal" id="proceed">Okay</div>
					</div>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>
@endsection
@section("script")

<script type="text/javascript">
	$(document).ready(function(){
    $("#proceed").click(function(){
      $("#myModal2").modal('show');
    });
  });
</script>

@endsection
@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/checkout.css">

@endsection