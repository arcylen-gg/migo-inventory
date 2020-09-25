@extends("layout")
@section("content")
<form method="POST" class="form-to-submit">
<input type="hidden" name="_token" value="{{csrf_token()}}">
<div class="container">
	<?php $ctr = 0; ?>
	@foreach($product["variant"] as $product_variant)
	<div class="body-content container {{ $ctr != 0 ? 'hide' : '' }}" variant-id="{{ $product_variant['evariant_id'] }}">
		<div class="intro">
			<div class="row clearfix">
				<div class="col-md-6">
					<div class="image match-height">
						<div class="slider-for">
						{{-- @foreach($product->product_image as $key => $image) --}}
							<div>
								<div class="holder">
									@foreach($product_variant['image'] as $key => $image)
									<img class="single-product-img key-{{$key}} {{ $key == 0 ? '' : 'hide' }} {{$ctr != 0 ? '' : 'first-img'}}" variant-id="{{ $product_variant['evariant_id'] }}" key="{{ $key }}" src="{{ $image['image_path'] }}" data-zoom-image="{{ $image['image_path'] }}">
									@endforeach
								</div>
							</div>
						{{-- @endforeach --}}
						</div>
						<div class="slider-nav">
                        {{-- @foreach($product->product_image as $key => $image) --}}
                            {{-- <div>
                                <div class="holder product-image"><img src="/assets/front/img/placeholder.png"></div>
                            </div> --}}
                        {{-- @endforeach --}}
                    	</div>
					</div>
					<div class="image-gallery">
						
					</div>
				</div>
				<div class="col-md-6">
					<div class="info match-height">
						<div class="name">
							<div class="product-title-price">
								<font>{{ $product["eprod_name"] }}</font>

								@if($product_variant['discounted'] == "true")
								{{-- <font class="pull-right top-price variant-price" style="color:red;font-size:17px;text-decoration: line-through;">PHP. {{ number_format($product_variant['evariant_price'], 2) }}</font> --}}
								<font class="pull-right top-price variant-price">PHP. {{ number_format($product_variant['discounted_price'], 2) }}</font>
								@else
								<font class="pull-right top-price variant-price">PHP. {{ number_format($product_variant['evariant_price'], 2) }}</font>
								@endif
								<img src="/themes/{{ $shop_theme }}/assets/front/img/loader.gif" style="width:30px" class="ajax-loading hide pull-right">
							</div>
						</div>
						<div class="desc-title">Description</div>
						<div class="desc">{!! $product_variant['evariant_description'] !!}</div>
						<div class="item-number-title">Item No.</div>
						<div class="item-number">{{ $product["eprod_id"] }}</div>
						@if($product_variant['item_type_id'] != 2)
						<div class="available-stocks-title">Available Stocks</div>
						<img src="/themes/{{ $shop_theme }}/assets/front/img/loader.gif" style="width:18px" class="ajax-loading hide">
						{{-- <div class="available-stocks variant-inventory-count" value="0">Please select a variant</div> --}}
						<div class="available-stocks variant-inventory-count" value="0">{{ $product_variant['inventory_count'] }}</div>
						@endif
						<div class="row" id="option_container">
						@foreach($_variant as $variant)
	                     <div class="clearfix col-md-4">
	                        <div class="size-title" style="text-transform: capitalize;">{{ $variant['option_name'] }}</div>
	                        <div class="size-desc ">
	                           <select class="attribute-variation size-desc option_value" variant-label="{{ $variant['option_name'] }}" product-id="{{ $product['eprod_id'] }}" variant-id="{{ $product_variant['evariant_id'] }}" name="attr[{{ $variant['option_name_id'] }}]">
	                              <option value="0">Select {{ $variant['option_name'] }}</option>
	                              @foreach(explode(",", $variant['variant_value']) as $option)
	                              <option value="{{ $option }}">{{ $option }}</option>
	                              @endforeach
	                           </select>
	                        </div>
	                     </div>
	                     @endforeach
						</div>

						<div class="row quantity">
							<div class="col-xs-6">
								<button class="btn add-cart add-to-cart-button" type="button" variant-id="{{ $product_variant['evariant_id'] }}">ADD TO CART <img src="/themes/{{ $shop_theme }}/assets/front/img/loader.gif" style="width:18px" class="ajax-loading-cart hide"><img src="/themes/{{ $shop_theme }}/front/add-to-cart-white.png"></button>
								<!-- <span class="note variant-note">*Select a Variant</span> -->
							</div>
							<div class="input-count col-xs-6">
								<table>
									<tbody>
										<tr>
											<td><div class="adjust btn-add">+</div></td>
											<td><input type="text" onkeypress='return event.charCode >= 48 && event.charCode <= 57' class="input-adjust form-control" autocomplete="off" name="quantity" value="1" variant-id="{{ $product_variant['evariant_id'] }}"></td>
											<td><div class="adjust btn-minus">-</div></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		</form>

		<!-- RELATED PRODUCTS -->
		<div class="rel-product">
			<div class="rel-product-title">RELATED <font class="product">PRODUCT</font></div>
			@if($_related)
				@foreach($_related as $related)
				<div class="col-md-3 col-xs-6 col-sm-bottom col-bottom">
					<div class="list-product"  class="prodimg">
						<div class="img">
							<img class="4-3-ratio" src="{{ get_product_first_image($related) }}">
							<div class="show-cart-view cart-hidden hidden">
								<a href="/product/view?id={{ $related["eprod_id"] }}"><img class="magnify-glass" src="/themes/{{ $shop_theme }}/front/magnify-black.png"></a>
								{{-- @if($related->product_has_variations == 0) --}}
									<img src="/themes/{{ $shop_theme }}/front/bag-black.png" class='bag add-to-cart' style='cursor: pointer;'>
								{{-- @endif --}}
							</div>
						</div>
						<div class="prod-name name">{{ get_product_first_name($related) }}</div>
						<div class="prod-price price">{{ get_product_first_price($related) }}</div>
					</div>
				</div>
				@endforeach
			@endif
			<!-- <div class="col-md-3">
				<div class="list">
					<div class="img"><img src="/assets/front/img/test-product.jpg"></div>
					<div class="prod-name">NUTRA-CEUTICALS</div>
					<div class="prod-price">P 200.00</div>
				</div>
			</div> -->
		</div>
	</div>
	<?php $ctr++; ?>
	@endforeach
</div>

@endsection
@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/assets/front/css/view_product.css">
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/assets/front/css/product.css">
@endsection
@section("script")
{{-- <script type="text/javascript" src="/themes/{{ $shop_theme }}/assets/front/js/product.js"></script> --}}
<script type="text/javascript" src="/assets/front/js/zoom.js"></script>
<script type="text/javascript" src="/themes/{{ $shop_theme }}/js/addcart.js"></script>
@endsection