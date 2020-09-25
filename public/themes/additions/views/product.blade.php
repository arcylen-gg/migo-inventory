@extends("layout")
@section("content")
<div class="container">
	<table class="product-container">
		<tr>
			<td class="product-filter" style="vertical-align: top;">
				<div class="filter-title">SHOP</div>
				<div class="filter-category">
					<div class="filter-main-category">
						<a href="/product" class="category {{Request::input('type') == null ? 'active' : ''}}" style="text-transform: uppercase;">All</a>
					</div>

					@if(count($_category) > 0)
						@foreach($_category as $category)
							<div class="filter-main-category">
								<a href="/product?type={{ $category['type_id'] }}" class="category {{Request::input('type') == $category['type_id'] ? 'active' : ''}}" style="text-transform: uppercase;">{{ $category['type_name'] }}</a>
								@if(count($category['subcategory']) > 0)
									<div style="padding: 10px 0; padding-bottom: 7.5px;">
										@foreach($category['subcategory'] as $subcategories)
										<div style="color: #000000; font-size: 12.5px; font-weight: 300; padding-left: 7.5px; text-transform: uppercase;" onClick="location.href='/product?type={{ $subcategories['type_id'] }}'" class="menu">{{ $subcategories['type_name'] }}</div>
										@endforeach
									</div>
								@endif
							</div>
						@endforeach
					@endif
				</div>
			</td>

			<td class="body-content" style="vertical-align: top;">
				<div class="head-data">
					<div class="">
						<div class="product-header">
							<img src="/themes/{{ $shop_theme }}/front/dress-header.jpg">
							<div class="header-container">
								<p class="header-title category_name" style="text-transform: uppercase;">{{ $category_name }}</p>
								<p class="header-link">CLOTHES > <font class="category_name" style="text-transform: uppercase;"> {{ $category_name }} </font></p>
							</div>
						</div>
					</div>
				</div>

				<div class="filter-search clearfix">
					<ul class="nav pull-right">
					    <li>
					      <div class="search-combine clearfix">
					        <h5>SORTED BY : &nbsp;</h5>
					      </div>
					    </li>
					    <li class="cart-contain">
					    	<form class="sort-by" method="get">
							<input type="hidden" name="type" value="{{ Request::input('type') }}">
								<div class="sortby">
									<select class="sorted_by form-control" name="sort" onChange="$('.sort-by').submit();">
										<option value="" {{ Request::input('sort') == '' ? 'selected' : '' }}>Relevance</option>
										<option value="name_asc" {{ Request::input('sort') == 'name_asc' ? 'selected' : '' }}>Brand: A - Z</option>
										<option value="name_desc" {{ Request::input('sort') == 'name_desc' ? 'selected' : '' }}>Brand: Z - A</option>
										<option value="price_desc" {{ Request::input('sort') == 'price_desc' ? 'selected' : '' }}>Price: Low - High</option>
										<option value="price_asc" {{ Request::input('sort') == 'price_asc' ? 'selected' : '' }}>Price: High - Low</option>
										<option value="newest" {{ Request::input('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
									</select>
								</div>
							</form>
					    </li>
					</ul>
				</div>

				<div class="content">
					<div class="single-product">
						<div class="title"></div>
						<div class="row row-height ">
							<div class="load-table">
								@if($_product)
									@foreach($_product as $product)
									<div class="col-md-4 col-sm-6 col-xs-6 col-sm-bottom col-bottom match-height">
										<div class="list-product" id="{{$product["eprod_id"]}}" class="prodimg">
											<div class="img">
												<img class="4-3-ratio" onClick="location.href='/product/view/{{ $product["eprod_id"] }}'" src="{{get_product_first_image($product)}}">
												<div class="show-cart-view cart-hidden{{$product["eprod_id"]}} hidden">
													<a href="/product/view/{{ $product["eprod_id"] }}"><img class="magnify-glass" src="/themes/{{ $shop_theme }}/front/magnify-black.png"></a>
													<img src="/themes/{{ $shop_theme }}/front/bag-black.png" class='bag add-to-cart account-modal-button' style='cursor: pointer;'>
												</div>
											</div>
											<div class="prod-name name">{{ get_product_first_name($product) }}</div>
											<div class="prod-price price">{{ get_product_first_price($product) }}</div>
										</div>
									</div>
									@endforeach
									<div class="col-md-12 col-sm-12">
										<div class="text-right">{!! $_product->appends(Request::input())->render() !!}</div>
									</div>
								@else
									<div class="no-product-container">
										<span>-- No Product Available --</span>
									</div>
								@endif
							</div>
						</div>
					</div>
				</div>
			</td>
		</tr>
	</table>
</div>
@endsection
@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/assets/front/css/product.css">
@endsection
@section("script")
{{-- <script type="text/javascript" src="/themes/{{ $shop_theme }}/assets/front/js/product.js"></script>
<script type="text/javascript" src="/themes/{{ $shop_theme }}/assets/front/js/product_filter.js"></script> --}}
@endsection