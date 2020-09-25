@extends("layout")
@section("content")
<div class="product-container">
	<div class="container">
		<div class="row clearfix">
			<div class="col-md-3">
				<div class="category-container">
					<div class="animated fadeInLeft text-header">Browse By Category</div>
					<div class="animated fadeInRight links-category">
						@foreach($_categories as $category)
						<a href="/product?type={{ $category["type_id"] }}" class="{{ Request::input("type") == $category["type_id"] ? "active" : "" }}">{{ $category["type_name"] }}</a>
						@endforeach
					</div>
				</div>
				<div class="filter-by-price-container">
					<div class="animated fadeInLeft text-header">Filter By Price</div>
					<div class="animated fadeInDown filter-control">
						<div id="slider-range"></div>
					</div>
					<div class="animated fadeInDown txt-price" id="amount">0 - 0</div>
					<form method="get">
						<input type="hidden" name="min" id="min-holder">
						<input type="hidden" name="max" id="max-holder">
						<button type="submit" class="animated fadeInUp btn-go">Go</button>
					</form>
				</div>
			</div>
			<div class="col-md-9" style="overflow: hidden;">
				<div class="top-item-container">
					<div class="row clearfix">
						@foreach($_product as $product)
							<div class="animated zoomIn col-md-3 col-sm-6">
								<div class="img-and-lbl-container" style="cursor: pointer;" onClick="location.href='/product/view2/{{ $product["eprod_id"] }}'">
									<a href="javascript:"><img style="object-fit: contain;" width="206" height="206" src="{{ get_product_first_image($product) }}"></a>
									<div class="lbl-item-name match-height">{{ get_product_first_name($product) }}</div>
									<div class="lbl-description">&nbsp;</div>
									<div class="lbl-price">{{ get_product_first_price($product) }}</div>
								</div>
							</div>
						@endforeach
					</div>
				</div>
				{{-- @if(Request::input("type"))
					<div class="bottom-item-container" style="overflow: hidden;">
						<div class="row clearfix">
							<div class="wow fadeInLeft text-header">You May Also Like This</div>
							<div class="wow zoomIn col-md-3 col-sm-6">
								<div class="img-and-lbl-container">
									<a href="/product/view/test"><img width="206" height="206" src="/themes/{{ $shop_theme }}/img/brown-accs-img-1.png"></a>
									<div class="lbl-item-name">XB-400</div>
									<div class="lbl-description">Super Bass</div>
									<div class="lbl-price">P 9,500.00</div>
								</div>
							</div>
							<div class="wow zoomIn col-md-3 col-sm-6">
								<div class="img-and-lbl-container">
									<a href="/product/view/test"><img width="206" height="206" src="/themes/{{ $shop_theme }}/img/brown-accs-img-2.png"></a>
									<div class="lbl-item-name">Sense 4</div>
									<div class="lbl-description">10400 mAh</div>
									<div class="lbl-price">P 9,500.00</div>
								</div>
							</div>
							<div class="wow zoomIn col-md-3 col-sm-6">
								<div class="img-and-lbl-container">
									<a href="/product/view/test"><img width="206" height="206" src="/themes/{{ $shop_theme }}/img/brown-accs-img-3.png"></a>
									<div class="lbl-item-name">T'nalak Brown</div>
									<div class="lbl-description">Phone Case</div>
									<div class="lbl-price">P 9,500.00</div>
								</div>
							</div>
							<div class="wow zoomIn col-md-3 col-sm-6">
								<div class="img-and-lbl-container">
									<a href="/product/view/test"><img width="206" height="206" src="/themes/{{ $shop_theme }}/img/brown-accs-img-4.png"></a>
									<div class="lbl-item-name">T'nalak Brown<br>Power Cable</div>
									<div class="lbl-description">Cable</div>
									<div class="lbl-price">P 9,500.00</div>
								</div>
							</div>
						</div>
					</div>
				@endif --}}
			</div>
		</div>
	</div>
</div>
@endsection
@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/product.css">
@endsection
@section("script")
<script type="text/javascript">
var current_min = {{ Request::input("min") ? Request::input("min") : $min_price }};
var current_max = {{ Request::input("max") ? Request::input("max") : $max_price }};
var min_price   = {{ $min_price }};
var max_price 	= {{ $max_price }};
</script>
<script type="text/javascript" src="/themes/{{ $shop_theme }}/js/product.js"></script>
@endsection