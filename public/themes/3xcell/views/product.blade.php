@extends("layout")
@section("content")
<div class="content">
	<div class="top-1-container">
		<div class="container">
			<div class="prod-container">
				<div class="row clearfix">
					<div class="cat-promo-holder col-md-3 col-sm-3 col-xs-4">
						<!-- PRODUCT CATEGORIES -->
						<div class="cat-container">
							<div class="cat-title-container">
								<span><i class="fa fa-bars" aria-hidden="true"></i></span>
								<span>Categories</span>
							</div>
							<div class="cat-list-container">
								@if(count($_category) > 0)
									@foreach($_category as $category)
										<div class="cat-list {{ $category['type_id'] == Request::input('type') ? 'active' : '' }}" onClick="location.href='/product?type={{ $category['type_id'] }}'">{{ $category['type_name'] }}</div>
									@endforeach
								@else
									<div class="cat-list">Beauty Skin Care</div>
									<div class="cat-list">Food Supplement</div>
									<div class="cat-list">Healthy Drinks</div>
									<div class="cat-list">Business Packages</div>
									<div class="cat-list">Retail Packages</div>
								@endif
							</div>
						</div>
						<!-- PROMO CONTAINER -->
						{{-- <div class="promo-container">
							<div class="title-container">Promo</div>
							<div class="promo-content">
								<img src="/themes/{{ $shop_theme }}/img/promo-img.png">
								<div class="learn-more-button">LEARN MORE</div>
							</div>
						</div> --}}
					</div>
					<div class="product-list-holder col-md-9 col-sm-12 col-xs-12">
						<div class="prod-list-container">
							<div class="title-container">{{ $category_name }}<div class="line-bot"></div></div>
							<div class="prod-list row clearfix">
								<!-- PER ITEM -->
								@if(count($_product) > 0)
									@foreach($_product as $product)
									<a href="/product/view2/{{ $product['eprod_id'] }}">
										<div class="col-md-4 col-sm-4 col-xs-6">
											<div class="per-item">
												<div class="image-container">
													<img class="1-1-ratio" src="{{ get_product_first_image($product) }}">
												</div>
												<div class="detail-container">
													<div class="item-name">
														{{ get_product_first_name($product) }}
													</div>
													<div class="price-container">{{ get_product_first_price($product) }}</div>
													<div class="button-container">SHOP NOW</div>
												</div>
											</div>
										</div>
									</a>
									@endforeach
								@else
									<a href="/product/view/test">
										<div class="col-md-4 col-sm-4 col-xs-6">
											<div class="per-item">
												<div class="image-container">
													<img src="/themes/{{ $shop_theme }}/img/item-sample.png">
												</div>
												<div class="detail-container">
													<div class="item-name">
														3XCELL Neuro Proprietary Herbal Bend Food Supplement Capsules
													</div>
													<div class="price-container">PHP 250.00</div>
													<div class="button-container">SHOP NOW</div>
												</div>
											</div>
										</div>
									</a>
									<a href="/product/view/test">
										<div class="col-md-4 col-sm-4 col-xs-6>
											<div class="per-item">
												<div class="image-container">
													<img src="/themes/{{ $shop_theme }}/img/item-sample.png">
												</div>
												<div class="detail-container">
													<div class="item-name">
														3XCELL Neuro Proprietary Herbal Bend Food Supplement Capsules
													</div>
													<div class="price-container">PHP 250.00</div>
													<div class="button-container">SHOP NOW</div>
												</div>
											</div>
										</div>
									</a>
									<a href="/product/view/test">
										<div class="col-md-4 col-sm-4 col-xs-6">
											<div class="per-item">
												<div class="image-container">
													<img src="/themes/{{ $shop_theme }}/img/item-sample.png">
												</div>
												<div class="detail-container">
													<div class="item-name">
														3XCELL Neuro Proprietary Herbal Bend Food Supplement Capsules
													</div>
													<div class="price-container">PHP 250.00</div>
													<div class="button-container">SHOP NOW</div>
												</div>
											</div>
										</div>
									</a>
									<a href="/product/view/test">
										<div class="col-md-4 col-sm-4 col-xs-6">
											<div class="per-item">
												<div class="image-container">
													<img src="/themes/{{ $shop_theme }}/img/item-sample.png">
												</div>
												<div class="detail-container">
													<div class="item-name">
														3XCELL Neuro Proprietary Herbal Bend Food Supplement Capsules
													</div>
													<div class="price-container">PHP 250.00</div>
													<div class="button-container">SHOP NOW</div>
												</div>
											</div>
										</div>
									</a>
									<a href="/product/view/test">
										<div class="col-md-4 col-sm-4 col-xs-6">
											<div class="per-item">
												<div class="image-container">
													<img src="/themes/{{ $shop_theme }}/img/item-sample.png">
												</div>
												<div class="detail-container">
													<div class="item-name">
														3XCELL Neuro Proprietary Herbal Bend Food Supplement Capsules
													</div>
													<div class="price-container">PHP 250.00</div>
													<div class="button-container">SHOP NOW</div>
												</div>
											</div>
										</div>
									</a>
									<a href="/product/view/test">
										<div class="col-md-4 col-sm-4 col-xs-6">
											<div class="per-item">
												<div class="image-container">
													<img src="/themes/{{ $shop_theme }}/img/item-sample.png">
												</div>
												<div class="detail-container">
													<div class="item-name">
														3XCELL Neuro Proprietary Herbal Bend Food Supplement Capsules
													</div>
													<div class="price-container">PHP 250.00</div>
													<div class="button-container">SHOP NOW</div>
												</div>
											</div>
										</div>
									</a>
								@endif
							</div>
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
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/product.css">
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