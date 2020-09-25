@extends('layout')
@section('content')
<form method="post">
	 {{ csrf_field() }}
	<div class="container content">
		<h2>Choose Payment Method</h2>
		<div class="payment-container">
			<div class="row clearfix">
				<div class="col-md-6">
					<div class="holder-holder">
						@if(count($_payment_method) != 0)
							@foreach($_payment_method as $payment_method)
								<div class="choose-payment-method holder" method_id="{{ $payment_method->method_id }}" description="{{ $payment_method->link_description }}" style="border: 1px solid #dde2e4; width: 114px;">
									<div class="match-height" style="text-transform: uppercase; font-weight: 700; line-height: 15px;">{{ $payment_method->method_name }}</div>
									<div class="image" style="margin-top: 7.5px;">
										<img src="{{ $payment_method->image_path ? $payment_method->image_path : '/assets/front/img/default.jpg' }}">
									</div>
									<div class="radio" style="margin-bottom: 0;">
										<label >
									  		<input class="radio" type="radio" name="payment_method_id" value="{{ $payment_method->method_id }}">
										</label>
									</div>
								</div>
							@endforeach	
						@else
							<div class="text-center"><h3>No Payment Method Available</h3></div>
						@endif
					</div>
					<div class="details clearfix">
						<div class="detail-holder">
							<div class="details-text" style="white-space: pre-wrap; font-weight: 300; text-transform: uppercase;">Kindly choose a payment method which you are most comfortable with paying.</div>
							<div class="details-order">
								<button class="btn btn-primary">PLACE YOUR ORDER</button>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6 order-summary-container">

				</div>
			</div>
		</div>
	</div>
</form>
@endsection

@section('script')
<script type="text/javascript" src="js/match-height.js"></script>
<script type="text/javascript">


var checkout_form_payment = new checkout_form_payment();

function checkout_form_payment()
{
	init();

	function init()
	{
		$(document).ready(function()
		{
			document_ready();
		});
	}
	function document_ready()
	{
		event_choose_payment_method();
		action_load_sidecart();
		action_match_height();
	}
	function event_choose_payment_method()
	{
		$(".radio").prop("checked", false);
		$(".choose-payment-method").unbind("click");
		$(".choose-payment-method").bind("click", function(e)
		{
			$(".checkout-summary .loader-here").removeClass("hidden");
			$(e.currentTarget).find(".radio").prop("checked", true);

			var description = $(e.currentTarget).attr("description");
			$(".details-text").html(description);

			var method_id = $(e.currentTarget).attr("method_id");
			$.ajax(
			{
				url:"/checkout/method",
				datatype:"json",
				type:"get",
				data:{method_id:method_id},
				success: function(data)
				{
					action_load_sidecart();
				}
			});

		});
	}
	function action_match_height()
	{
		$('.match-height').matchHeight();
	}
	function action_load_sidecart()
	{
		$(".order-summary-container").load("/checkout/side");
	}
}


</script>
@endsection

@section('css')
<link rel="stylesheet" href="/themes/{{ $shop_theme }}/css/checkout_payment.css">
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/assets/front/css/checkout.css">
@endsection