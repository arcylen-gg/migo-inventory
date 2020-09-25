@extends("layout")
@section("content")
<div class="product-bg">
	<div class="container">
		@foreach($product['variant'] as $key => $product_variant)
			<div class="product-content {{ $loop->first ? '' : 'hide' }}" variant-id="{{ $product_variant['evariant_id'] }}">
				<table>
					<tr>
						<td class="left">
							<div class="product-holder">
								<div class="product-title">{{ $product["eprod_name"] }}</div>
								<div class="product-main">
									<div class="row clearfix">
										<div class="col-md-7">
											<div class="product-img">
												<table>
													<tr>
														<td class="thumb-holder">
															<div class="thumb">
																@foreach($product_variant['image'] as $key => $image)
																	<div class="holder" key="{{ $key }}" variant-id="{{ $product_variant['evariant_id'] }}">
																		<img src="{{ $image['image_path'] }}">
																	</div>
																@endforeach
															</div>
														</td>
														<td class="img-holder">
															@foreach($product_variant['image'] as $key => $image)
																<img key="{{ $key }}" variant-id="{{ $product_variant['evariant_id'] }}" class="img {{ $loop->first ? '' : 'hide' }}" src="{{ $image['image_path'] }}">
															@endforeach
														</td>
													</tr>
												</table>
											</div>
										</div>
										<div class="col-md-5">
											<div class="product-info">
												<div class="desc">{!! $product_variant["evariant_description"] !!}</div>
												<div class="spec">
													{{-- <div class="spec-title">Specification</div>
													<div class="spec-list">
														<ul>
															<li><i class="fa fa-circle" aria-hidden="true"></i> 4.7 HD IPS Display</li>
															<li><i class="fa fa-circle" aria-hidden="true"></i> LTE Connection</li>
															<li><i class="fa fa-circle" aria-hidden="true"></i> Android Nougat</li>
															<li><i class="fa fa-circle" aria-hidden="true"></i> 13 MP Back Camera</li>
															<li><i class="fa fa-circle" aria-hidden="true"></i> 8 MP Front Camera</li>
															<li><i class="fa fa-circle" aria-hidden="true"></i> OTG Support</li>
															<li><i class="fa fa-circle" aria-hidden="true"></i> Finger print sensor</li>
															<li><i class="fa fa-circle" aria-hidden="true"></i> Infared Sensor</li>
														</ul>
													</div> --}}
												</div>
												<div class="stock">In Stock</div>

												@if($product_variant['discounted'] == "true")
		                                            <div class="price">&#8369; {{ number_format($product_variant['discounted_price'], 2) }}</div>
		                                            <div class="price" style="text-decoration: line-through; font-size: 14px;">&#8369; {{ number_format($product_variant['evariant_price'], 2) }}</div>
	                                            @else
	                                            	<div class="price">&#8369; {{ number_format($product_variant['evariant_price'], 2) }}</div>
	                                            @endif

	                                            <div class="variant-holder" style="margin-top: 15px;">
				                                    @foreach($_variant as $variant)
				                                    <div class="holder">
				                                        <div class="s-label" style="color: #585858; font-size: 13px;font-weight: 500; margin-bottom: 5px;">Select {{ $variant['option_name'] }}</div>
				                                        <div class="select">
				                                            <select name="attr[{{ $variant['option_name_id'] }}]" style="text-transform: capitalize;" class="form-control select-variation" variant-label="{{ $variant['option_name'] }}" product-id="{{ $product['eprod_id'] }}" variant-id="{{ $product_variant['evariant_id'] }}">
				                                                <option value="0" style="text-transform: capitalize;">{{ $variant['option_name'] }}</option>
				                                                @foreach(explode(",", $variant['variant_value']) as $option)
				                                                <option value="{{ $option }}" style="text-transform: capitalize;">{{ $option }}</option>
				                                                @endforeach
				                                            </select>
				                                        </div>
				                                    </div>
				                                    @endforeach
				                                </div>
												
												<div class="qty-add-cart clearfix">
													<div class="qty">
														<div class="qty-title">Quantity</div>
														<div class="qty-input">
															<input onChange="$('.product-add-cart').attr('quantity', $(this).val())" class="form-control input-lg" type="number" min="1" value="1">
														</div>
													</div>
													<div class="add-cart">
														<button item-id="{{ $product_variant['evariant_item_id'] }}" quantity="1" class="btn btn-orange product-add-cart">Add to Cart</button>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="product-feature">
									<div class="feature-title">General Features</div>
									<div class="feature-content">{!! $product["eprod_details"] !!}</div>
								</div>
							</div>
						</td>
						<td class="right">
							<div class="side-holder">
								<div class="side-title">You May Also Like This</div>
								<div class="side-content">
									@foreach($_related as $related)
										<div class="holder">
											<div class="img">
												<img src="{{ get_product_first_image($related) }}">
											</div>
											<div class="name">{{ get_product_first_name($related) }}</div>
											<div class="sub"></div>
											<div class="price">{{ get_product_first_price($related) }}</div>
										</div>
									@endforeach
								</div>
							</div>
						</td>
					</tr>
				</table>
			</div>
		@endforeach
	</div>
</div>
@endsection
@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/product_content.css">
@endsection
@section("script")
<script type="text/javascript" src="/assets/front/js/zoom.js"></script>
<script type="text/javascript" src="/themes/{{ $shop_theme }}/js/product_content.js"></script>
@endsection