@extends("layout")
@section("content")
<div class="content">
	<div class="top-1-container">
		<div class="container">
			<div class="prod-content-container row clearfix">
				<div class="col-md-6">
					<!-- PRODUCT IMAGE -->
					<div class="prod-image-container">
						<img class="single-product-img" src="{{ get_product_first_image($product) }}">
					</div>
				</div>
				<div class="col-md-6">
					<div class="purchase-details-container">
						<div class="product-name-container">{{ get_product_first_name($product) }}{{-- <div class="line-bot"></div> --}}</div>
						<!-- PRODUCT DESCRIPTION -->
						<div class="prod-description-container">
							<div class="prod-description-title-container">
								<div class="description-title">
									Description
								</div>
							</div>
							<div class="prod-description">
								<p>
									{!! get_product_first_description($product) !!}
								</p>
							</div>
						</div>
						<div class="product-price">
							@if(isset($product["variant"][0]["price_level"]) && $product["variant"][0]["price_level"])
								₱ {{ number_format($product["variant"][0]["price_level"], 2) }}
							@else
								{{ get_product_first_price($product) }}
							@endif
							<div style="margin-top: 15px; font-size: 18px;">
								@if($mlm_member)
									@foreach($product["variant"][0]["pv"] as $key => $value)
										@if($key == "STAIRSTEP" )
										{{-- || $key == "STAIRSTEP_GROUP" || $key == "RANK" --}}
											<div>
												<span class="pv-label" style="padding-left: 0;">
													<i class="fa fa-star" aria-hidden="true"></i>
												</span>
												{{-- @if($key == "RANK")
													<span class="pv-label">RANK PV:</span>
												@elseif($key == "STAIRSTEP_GROUP")
													<span class="pv-label">GROUP VOLUME:</span> --}}
												@if($key == "STAIRSTEP")
													<span class="pv-label"></span>
												@else
													<span class="pv-label">{{ str_replace("_", " ", $key) }}</span>
												@endif
												<span class="pv-details">{{ number_format($value, 2) }} PV</span>
											</div>
										@endif
									@endforeach
								@endif
							</div>
						</div>
						<div class="product-quantity">
							<div class="info-title">
								Quantity
							</div>
							<input class="input-quantity" onChange="$(this).siblings('.product-add-cart').attr('quantity', $(this).val())" type="number" name="quantity" min="1" step="1" value="1">
							<div class="add-to-cart-button product-add-cart" item-id="{{ $product['variant'][0]['evariant_item_id'] }}" quantity="1"><span><i class="fa fa-cart-plus" aria-hidden="true"></i></span>&nbsp;&nbsp;<span>ADD TO CART</span></div>
						</div>
						
						<!-- <div class="share-product">
							<div class="info-title">Share This Product</div>
							<div class="share-button-container">
								<div class="share-button"><span><i class="fa fa-facebook" aria-hidden="true"></i></span><span>&nbsp;Facebook</span></div>
								<div class="share-button"><span><i class="fa fa-twitter" aria-hidden="true"></i></span><span>&nbsp;Twitter</span></div>
							</div>
						</div> -->
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="top-2-container">
		<div class="container">
			<!-- RELATED PRODUCTS -->
			<div class="related-products-container">
				<div class="title-container">
					<div class="title">Related Products</div>
					<div class="line-bot"></div>
				</div>
				<div class="per-item-container row clearfix">
					<!-- PER ITEM -->
					@foreach(limit_foreach($_related, 4) as $related)
					<div class="col-md-3">
						<div class="per-item" style="cursor: pointer;" onClick="location.href='/product/view2/{{ $related['eprod_id'] }}'">
							<div class="image-container">
								<img src="{{ get_product_first_image($related) }}">
							</div>
							<div class="detail-container">
								<div class="item-name">
									{{ get_product_first_name($related) }}
								</div>
								<div class="price-container">PHP {{ get_product_first_price($related) }}</div>
								<div class="button-container">SHOP NOW</div>
							</div>
						</div>
					</div>
					@endforeach
				</div>
			</div>
			<!-- PACKAGE -->
			@if(isset($_package))
			<div class="related-products-container">
				<div class="title-container">
					<div class="title">You May Also Like This</div>
					<div class="line-bot"></div>
				</div>
				
				<div class="per-item-container row clearfix">
					<!-- PER ITEM -->
					@if(count($_package) > 0)
						@foreach(limit_foreach($_package, 4) as $package)
						<div class="col-md-3">
							<div class="per-item" style="cursor: pointer;" onClick="location.href='/product/view/{{ $related['eprod_id'] }}'">
								<div class="image-container">
									<img src="{{ get_product_first_image($package) }}">
								</div>
								<div class="detail-container">
									<div class="item-name">
										{{ get_product_first_name($package) }}
									</div>
									<div class="price-container">PHP {{ get_product_first_price($package) }}</div>
									<div class="button-container">SHOP NOW</div>
								</div>
							</div>
						</div>
						@endforeach
					@endif
				</div>
			</div>
			@endif
		</div>
	</div>
</div>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/product_content.css">
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
<script type="text/javascript" src="/assets/front/js/zoom.js"></script>
<script type="text/javascript">
var product_image = ".single-product-img";
var button_cart = ".add-to-cart-buttons";
var product_container = ".content";
var product_quantity = ".input-quantity";
var cart_holder = '.cart-dropdown';
</script>
<script type="text/javascript" src="/assets/front/js/global_addcart.js"></script>
@endsection