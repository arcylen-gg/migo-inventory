@extends("layout")
@section("content")
<div class="container">
	<div class="clearfix demo">
        <div class="item">
            <ul id="image-gallery" class="gallery list-unstyled cS-hidden">
            	@if(loop_content_condition($shop_theme_info, "home", "home_slider"))
            		@foreach(loop_content_get($shop_theme_info, "home", "home_slider") as $slider)
            		<li data-thumb="{{ $slider }}"> 
	                    <img src="{{ $slider }}" />
	                </li>
            		@endforeach
            	@else
                <li data-thumb="/themes/{{ $shop_theme }}/assets/front/global/lightslider/demo/img/thumb/cS-1.jpg"> 
                    <img src="/themes/{{ $shop_theme }}/assets/front/global/lightslider/demo/img/1st.jpg" />
                </li>
                <li data-thumb="/themes/{{ $shop_theme }}/assets/front/global/lightslider/demo/img/thumb/cS-2.jpg"> 
                    <img src="/themes/{{ $shop_theme }}/assets/front/global/lightslider/demo/img/1st.jpg" />
                </li>
                @endif
            </ul>
        </div>
    </div>
</div>
<div class="container">
	<div class="maternity-promo">
		<div class="maternity-line">
		<span class="maternity-title">{{ get_front_divide_string($shop_theme_info, "home", "maternity_title", 2, 0) }}&nbsp;<font class="maternity-clothes">{{ get_front_divide_string($shop_theme_info, "home", "maternity_title", 2, 1) }}</font></span></div>
	</div>
</div>
<div class="container">
	<div class="clearfix maternity-products">
		<div class="left clearfix">
			<div class="product-left1">
			<img src="/themes/{{ $shop_theme }}/front/category2.jpg">
			<div class="title-shop-holder">
				<div class="title">{{ get_content($shop_theme_info, "home", "maternity_bottom_title") }}</div>
				<div class="shop-now">
					<button onClick="location.href='/product?type_id=bottoms'">Shop Now</button>
				</div>
			</div>
			</div>

			<div class="product-left2">
				<img src="/themes/{{ $shop_theme }}/front/category3.jpg">
				<div class="title-shop-holder">
					<div class="title">{{ get_content($shop_theme_info, "home", "maternity_dress_title") }}</div>
					<div class="shop-now">
						<button onClick="location.href='/product?type_id=dress'">Shop Now</button>
					</div>
				</div>
			</div>
		</div>
		<div class="right clearfix">
			<div class="product-right">
				<img src="/themes/{{ $shop_theme }}/front/category4.jpg">
				<div class="title-shop-holder">
					<div class="title">{{ get_content($shop_theme_info, "home", "maternity_bottom_title") }}</div>
					<div class="shop-now">
						<button onClick="location.href='/product?type_id=tops'">Shop Now</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="container">
	<div class="maternity-promo">
		<div class="maternity-line">
		<span class="maternity-title">{{ get_front_divide_string($shop_theme_info, "home", "new_title", 2, 0) }} <font class="maternity-clothes">{{ get_front_divide_string($shop_theme_info, "home", "new_title", 2, 1) }}</font></span></div>
	</div>
</div>
<div class="main">
	<div class="container">
		<div class="list-container">
			<div class="row clearfix">
				@if($_product)
					@foreach(limit_foreach($_product, 6) as $product)
					<div class="col-md-4 col-sm-6 col-xs-6 col-sm-bottom col-bottom">
						<div class="list-product" id="{{$product["eprod_id"]}}" class="prodimg">
							<div class="triangle-topright">
								<div class="label-name">New</div>
								<div class="label-desc">Releases</div>
							</div>
							<div class="img">
								<img onClick="location.href='/product/view/{{ $product["eprod_id"] }}'" class="4-3-ratio" src="{{get_product_first_image($product)}}">
								<div class="show-cart-view cart-hidden{{$product["eprod_id"]}} hidden">
									<a href="/product/view/{{ $product["eprod_id"] }}"><img class="magnify-glass" src="/themes/{{ $shop_theme }}/front/magnify-black.png"></a>
									<img src="/themes/{{ $shop_theme }}/front/bag-black.png" class='bag add-to-cart account-modal-button' style='cursor: pointer;'>
								</div>
							</div>
							<div class="prod-name name">{{get_product_first_name($product)}}</div>
							<div class="prod-price price">{{get_product_first_price($product)}}</div>
						</div>
					</div>
					@endforeach
				@endif
			</div>
		</div>
	</div>	
</div>

@endsection
@section("script")
<script type="text/javascript" src='/themes/{{ $shop_theme }}/assets/front/js/home.js'></script>
<script type="text/javascript" src="/themes/{{ $shop_theme }}/assets/front/global/lightslider/dist/js/lightslider.min.js"></script>
<script>
	$(document).ready(function() {
		$("#content-slider").lightSlider({
	        loop:true,
        keyPress:true
	    });
	    $('#image-gallery').lightSlider({
	        item:1,
	        thumbItem:9,
	        slideMargin: 0,
	        speed:500,
	        auto:true,
	        loop:true,
	        onSliderLoad: function() {
	            $('#image-gallery').removeClass('cS-hidden');
	        }  
	    });
	});
</script>
@endsection
@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/assets/front/css/home.css">
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/assets/front/css/product.css">
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/assets/front/global/lightslider/dist/css/lightslider.css">
@endsection