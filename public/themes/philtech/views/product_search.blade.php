@extends("layout")

@section("content")
<div class="content">
	<div class="container">
	<!-- TOP CONTENT -->
		<div class="row clearfix">
			<div class="col-md-3">
				<div class="left-bar-categories">
					<div class="left-bar-title">
						<img src="/themes/{{ $shop_theme }}/img/menu.png">
						<span>Shop By Category</span>
					</div>
					@if(isset($_categories))
						@foreach($_categories as $category)
						<div class="button-shop">
							<div class="text" style="cursor: pointer;" onClick="location.href='/product?type={{ $category['type_id'] }}'">
								<img src="{{ get_front_sidebar_icon($category['type_name'], $shop_theme) }}">
								<span>{{ $category['type_name'] }}</span>
							</div>
							@if($category['subcategory'])
							<div class="hover">
								<div class="hover-holder">
									<div class="clearfix">
										<ul>
											@foreach($category['subcategory'] as $subcategory)
												<li><a href="/product?type={{ $subcategory['type_id'] }}">{{ $subcategory['type_name'] }}</a></li>
											@endforeach
										</ul>
									</div>
								</div>
							</div>
							@endif
						</div>
						@endforeach
					@else
					<div class="button-shop">
						<div class="text" style="cursor: pointer;" onClick="location.href='/product/test'">
							<img src="/themes/{{ $shop_theme }}/img/sidebar/dth.png">
							<span>DTH PRODUCTS</span>
						</div>
					</div>
					<div class="button-shop">
						<div class="text" style="cursor: pointer;" onClick="location.href='/product/test'">
							<img src="/themes/{{ $shop_theme }}/img/sidebar/card.png">
							<span>PREPAID CARDS</span>
						</div>
					</div>
					<div class="button-shop">
						<div class="text" style="cursor: pointer;" onClick="location.href='/product/test'">
							<img src="/themes/{{ $shop_theme }}/img/sidebar/gadgets.png">
							<span>GADGETS</span>
						</div>
					</div>
					<div class="button-shop">
						<div class="text" style="cursor: pointer;" onClick="location.href='/product/test'">
							<img src="/themes/{{ $shop_theme }}/img/sidebar/electronics.png">
							<span>ELECTRONICS</span>
						</div>
					</div>
					<div class="button-shop">
						<div class="text" style="cursor: pointer;" onClick="location.href='/product/test'">
							<img src="/themes/{{ $shop_theme }}/img/sidebar/services.png">
							<span>SERVICES</span>
						</div>
					</div>
					<div class="button-shop">
						<div class="text" style="cursor: pointer;" onClick="location.href='/product/test'">
							<img src="/themes/{{ $shop_theme }}/img/sidebar/entertainment.png">
							<span>ENTERTAINMENT</span>
						</div>
					</div>
					<div class="button-shop">
						<div class="text" style="cursor: pointer;" onClick="location.href='/product/test'">
							<img src="/themes/{{ $shop_theme }}/img/sidebar/apparel.png">
							<span>APPAREL</span>
						</div>
					</div>
					<div class="button-shop">
						<div class="text" style="cursor: pointer;" onClick="location.href='/product/test'">
							<img src="/themes/{{ $shop_theme }}/img/sidebar/accessories.png">
							<span>ACCESSORIES</span>
						</div>
					</div>
					<div class="button-shop">
						<div class="text" style="cursor: pointer;" onClick="location.href='/product/test'">
							<img src="/themes/{{ $shop_theme }}/img/sidebar/health.png">
							<span>HEALTH & WELLNESS</span>
						</div>
					</div>
					@endif
				</div>
				<div class="hot-deals-container">
					<div class="wow-title">
						<span class="orange">HOT</span><span class="blue">DEALS</span>
						<span class="scroll-button"><a class="left" href="#"><img src="/themes/{{ $shop_theme }}/img/left-button-scroll.png"></a><a class="right" href="#"><img src="/themes/{{ $shop_theme }}/img/right-button-scroll.png"></a></span>
					</div>
					<div class="daily-container">
						@foreach(get_collection(get_content($shop_theme_info, "home", "daily_hot_deals"), $shop_id) as $collection)
							<div class="holder">
								<div class="hot-deals-item-container">
									<img class="4-3-ratio" src="{{ get_collection_first_image($collection) }}">
									<div class="item-details">
										<a href="/product/view2/{{ $collection['product']['eprod_id'] }}"><div class="item-title">{{ get_collection_first_name($collection) }}</div></a>
										<div class="item-price">{{ get_collection_first_price($collection) }}</div>
									</div>
									<button type="button" onClick="location.href='/product/view2/{{ $collection['product']['eprod_id'] }}'" class="new-add-to-cart-button btn" style="margin-top: 25px;">
										<table>
											<tbody>
												<tr>
													<td class="icon"><img src="/themes/{{ $shop_theme }}/img/header/cart-icon.png"></td>
													<td class="text">SHOP NOW</td>
												</tr>
											</tbody>
										</table>
									</button>
								</div>
							</div>
						@endforeach
					</div>
				</div>
				<div class="hot-deals-container">
					<div class="wow-title">
						<span class="orange">SPECIAL</span><span class="blue">OFFERS</span>
					</div>
					<div class="item-container">
						@foreach(get_collection(get_content($shop_theme_info, "home", "special_offers"), $shop_id) as $collection)
							<div class="row-no-padding clearfix per-item">
								<div class="col-xs-4"><img class="item-img 4-3-ratio" src="{{ get_collection_first_image($collection) }}"></div>
								<div class="col-xs-8">
									<div class=" item-details-container">
										<a href="/product/view2/{{ $collection['product']['eprod_id'] }}"><div class="item-title">{{ $collection['product']['eprod_name'] }}</div></a>
										<div class="item-price">{{ get_collection_first_price($collection) }}</div>
									</div>
								</div>
							</div>
						@endforeach
						{{-- <div class="row-no-padding clearfix per-item">
							<div class="col-xs-4"><img class="item-img 4-3-ratio" src="/themes/{{ $shop_theme }}/img/product/2.jpg"></div>
							<div class="col-xs-8">
								<div class=" item-details-container">
									<a href="/product/view2/test"><div class="item-title">Nokia 3310 (20170)</div></a>
									<div class="item-price">P 5,990.00</div>
								</div>
							</div>
						</div>
						<div class="row-no-padding clearfix per-item">
							<div class="col-xs-4"><img class="item-img 4-3-ratio" src="/themes/{{ $shop_theme }}/img/product/3.jpg"></div>
							<div class="col-xs-8">
								<div class=" item-details-container">
									<a href="/product/view2/test"><div class="item-title">GSat HD (Complete Set)</div></a>
									<div class="item-price">P 2,499.00</div>
								</div>
							</div>
						</div>
						<div class="row-no-padding clearfix per-item">
							<div class="col-xs-4"><img class="item-img 4-3-ratio" src="/themes/{{ $shop_theme }}/img/product/4.jpg"></div>
							<div class="col-xs-8">
								<div class=" item-details-container">
									<a href="/product/view2/test"><div class="item-title">GSat Prepaid (Load Card 500)</div></a>
									<div class="item-price">P 500.00</div>
								</div>
							</div>
						</div> --}}
					</div>
				</div>
			</div>

			<div class="col-md-9 prod-content">
				<div class="breadcrumbs">
					<div class="holder"><a href="/">HOME</a></div>
					<div class="holder">•</div>
					@foreach($breadcrumbs as $breadcrumb)
						@if($loop->last)
							<div class="holder active"><a href="/product?type={{ $breadcrumb["type_id"] }}">{{ $breadcrumb["type_name"] }}</a></div>
						@else
							<div class="holder"><a href="/product?type={{ $breadcrumb["type_id"] }}">{{ $breadcrumb["type_name"] }}</a></div>
							<div class="holder">•</div>
						@endif
					@endforeach
				</div>
				<!-- FEATURED TODAY -->
					<div class="featured-container" style="margin-top: 0;">
						<div class="left-container-title">
							<form class="sort-by" method="get">
							<input type="hidden" name="type" value="{{ Request::input('type') }}">
								<div class="sortby">Sort By: 
									<select name="sort" onChange="$('.sort-by').submit();">
										<!-- <option value="" {{ Request::input('sort') == '' ? 'selected' : '' }}>Relevance</option> -->
										<option value="name_asc" {{ Request::input('sort') == 'name_asc' ? 'selected' : '' }}>Brand: A - Z</option>
										<option value="name_desc" {{ Request::input('sort') == 'name_desc' ? 'selected' : '' }}>Brand: Z - A</option>
										<option value="price_desc" {{ Request::input('sort') == 'price_desc' ? 'selected' : '' }}>Price: Low - High</option>
										<option value="price_asc" {{ Request::input('sort') == 'price_asc' ? 'selected' : '' }}>Price: High - Low</option>
										<option value="newest" {{ Request::input('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
									</select>
								</div>
							</form>
							<!-- PAGINATION -->
							<div class="pagination-top">
								{!! $_product->appends(Request::input())->render() !!}
							</div>
						</div>
							<div class="featured-item-container row clearfix">	
								@foreach($_product as $product)
								<div class="col-md-3 match-height">
									<div class="per-item-container">
										<div class="image-content-1">
											{{-- <div class="item-image-large" style="background-image: url({{ get_product_first_image($product) }})"></div> --}}
											<img style="width: 100%;" class="4-3-ratio" src="{{ get_product_first_image($product) }}">
											<button type="button" onClick="location.href='/product/view2/{{ $product['eprod_id'] }}'" class="new-add-to-cart-button btn" >
											<table>
												<tbody>
													<tr>
														<td class="icon"><i class="fa fa-shopping-cart" aria-hidden="true"></i></td>
														<td class="text">View More</td>
													</tr>
												</tbody>
											</table>
										</button>
										</div>
										<div class="item-details">
											<div class="item-title"><a href="/product/view2/{{ $product['eprod_id'] }}">{{ $product['eprod_name'] }}</a></div>
											<!-- <div class="rating">
												<img src="/themes/{{ $shop_theme }}/img/star-active.png">
												<img src="/themes/{{ $shop_theme }}/img/star-active.png">
												<img src="/themes/{{ $shop_theme }}/img/star-active.png">
												<img src="/themes/{{ $shop_theme }}/img/star-active.png">
												<img src="/themes/{{ $shop_theme }}/img/star-disable.png">
											</div> -->
											<div class="item-price">&#8369; {{ $product['min_price'] == $product['max_price'] ? number_format($product['max_price'], 2) : number_format($product['min_price'], 2) . ' - ' . number_format($product['max_price'], 2) }}</div>
										</div>
										{{-- @if(count($product['variant'][0]['mlm_discount']) > 0)
			                            <div style="margin-top: 15px;">
			                                <table class="table table-bordered table-striped table-hover table-condensed" style="font-size: 12px;">
			                                    <thead>
			                                        <tr>
			                                            <th>Membership</th>
			                                            <th>Price</th>
			                                        </tr>
			                                    </thead>
			                                    <tbody>
			                                        @foreach($product['variant'][0]['mlm_discount'] as $key => $mlm_discount)
			                                        <tr>
			                                            <td>{{ $mlm_discount['discount_name'] }}</td>   
			                                            <td>PHP. {{ number_format($mlm_discount['discounted_amount'], 2) }}</td>
			                                        </tr>
			                                        @endforeach
			                                    </tbody>
			                                </table>
			                            </div>
			                            @endif --}}
									</div>
								</div>
								@endforeach
							</div>

							<div class="pagination-bottom text-right">
								{!! $_product->appends(Request::input())->render() !!}
							</div>
					</div>
				</div>
			</div>


		
			
			

		</div>
	</div>	
</div>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/product.css">
@endsection
