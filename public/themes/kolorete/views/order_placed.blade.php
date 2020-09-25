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
			<div class="inner-content">
				<h1>Order Placed!</h1>
				<img src="/themes/{{ $shop_theme }}/img/order-placed.png">
				<h2>You have sucessfully placed your order. <span style="color: #2161c8;">Please check your email for further instructions.</span> Thank you and happy shopping!</h2>
				<a href="/" style="text-decoration: none;"><div class="button-checkout">BACK TO SHOP</div></a>
			</div>
		</div>
	</div>

	<!-- SCROLL TO TOP -->
	<div class="scroll-up"><img src="/themes/{{ $shop_theme }}/img/scroll-up.png"></div>
</div>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/payment_success.css">
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