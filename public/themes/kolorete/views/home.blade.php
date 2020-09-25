@extends("layout")
@section("content")
<div class="content">

	<!-- Home Slider -->
	<div class="home-banner-container">
		<div class="container">
			<div class="row-no-padding clearfix">
				<div class="col-md-8">
					<div class="slider-wrapper single-item">
						<img src="/themes/{{ $shop_theme }}/img/home-slider-1.jpg">
						<img src="/themes/{{ $shop_theme }}/img/home-slider-1.jpg">
					</div>
				</div>
				<div class="col-md-4">
					<div class="ads-container">
						<img src="/themes/{{ $shop_theme }}/img/home-ads-1.png">
					</div>
					<div class="ads-container" style="margin-top: 8px;">
						<img src="/themes/{{ $shop_theme }}/img/home-ads-2.png">
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Featured Products -->
	<div class="featured-products-container">
		<div class="container">
			<div class="title-container">
				<div class="row clearfix">
					<div class="col-md-9">
						<div class="title">Featured Products</div>
					</div>
					<div class="col-md-3">
						<div class="button-container">
							<a href="/product" role="button">VIEW ALL PRODUCTS</a>
						</div>
					</div>
				</div>
			</div>
			<div class="product-container">
				<div class="row no-gutters clearfix gutters">

					@if(count($_product) > 0)

					    @foreach($_product as $product)

					    <div class="col-md-2">
					    	<div class="product-holder">
					    		<a class="item-hover" href="/product/view2/{{ $product['eprod_id'] }}" style="text-decoration: none;">
					    			<div class="product-image">
					    				<img src="{{ get_product_first_image($product) }}">
					    			</div>
					    			<div class="details-container">
					    				<div class="product-name match-height">{{ get_product_first_name($product) }}</div>
					    				<div class="product-price">{{ get_product_first_price($product) }}</div>
					    			</div>
					    		</a>
					    		<div class="bottom-container">
					    			<div class="row-no-padding clearfix">
					    				<div class="col-md-6">
					    					<a href="/product/view2/{{ $product['eprod_id'] }}">
					    						<div class="image-holder" style="border-right: 1px solid #9e9e9e;">
					    							<img src="/themes/{{ $shop_theme }}/img/view-product-image.png">
					    						</div>
					    					</a>
					    				</div>
					    				<div class="col-md-6">
					    					<a href="javascript:" class="product-add-cart" item-id="{{ $product['variant'][0]['evariant_item_id'] }}" quantity="1">
					    						<div class="image-holder">
					    							<img src="/themes/{{ $shop_theme }}/img/add-to-cart-image.png">
					    						</div>
					    					</a>
					    				</div>
					    			</div>
					    		</div>
					    	</div>
					    </div>

						@endforeach

						@else

							<div class="col-md-2">
								<div class="product-holder">
									<a href="javasctipt:" style="text-decoration: none">
										<div class="product-image">
											<img src="/themes/{{ $shop_theme }}/img/product-1.jpg">
										</div>
										<div class="details-container">
											<div class="product-name  match-height">Elite Contact Lens Spartax Gray</div>
											<div class="product-price">P 360.00</div>
										</div>
									</a>
									<div class="bottom-container">
										<div class="row-no-padding clearfix">
											<div class="col-md-6">
												<a href="#">
													<div class="image-holder" style="border-right: 1px solid #9e9e9e;">
														<img src="/themes/{{ $shop_theme }}/img/view-product-image.png">
													</div>
												</a>
											</div>
											<div class="col-md-6">
												<a href="#">
													<div class="image-holder">
														<img src="/themes/{{ $shop_theme }}/img/add-to-cart-image.png">
													</div>
												</a>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<div class="product-holder">
									<a href="javasctipt:" style="text-decoration: none">
										<div class="product-image">
											<img src="/themes/{{ $shop_theme }}/img/product-2.jpg">
										</div>
										<div class="details-container">
											<div class="product-name  match-height">Elite Contact Lens Mangge kyo Sharinggan</div>
											<div class="product-price">P 360.00</div>
										</div>
									</a>
									<div class="bottom-container">
										<div class="row-no-padding clearfix">
											<div class="col-md-6">
												<a href="#">
													<div class="image-holder" style="border-right: 1px solid #9e9e9e;">
														<img src="/themes/{{ $shop_theme }}/img/view-product-image.png">
													</div>
												</a>
											</div>
											<div class="col-md-6">
												<a href="#">
													<div class="image-holder">
														<img src="/themes/{{ $shop_theme }}/img/add-to-cart-image.png">
													</div>
												</a>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<div class="product-holder">
									<a href="javasctipt:" style="text-decoration: none">
										<div class="product-image">
											<img src="/themes/{{ $shop_theme }}/img/product-3.jpg">
										</div>
										<div class="details-container">
											<div class="product-name  match-height">Elite Contact Lens Hydrocor Gray</div>
											<div class="product-price">P 240.00</div>
										</div>
									</a>
									<div class="bottom-container">
										<div class="row-no-padding clearfix">
											<div class="col-md-6">
												<a href="#">
													<div class="image-holder" style="border-right: 1px solid #9e9e9e;">
														<img src="/themes/{{ $shop_theme }}/img/view-product-image.png">
													</div>
												</a>
											</div>
											<div class="col-md-6">
												<a href="#">
													<div class="image-holder">
														<img src="/themes/{{ $shop_theme }}/img/add-to-cart-image.png">
													</div>
												</a>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<div class="product-holder">
									<a href="javasctipt:" style="text-decoration: none">
										<div class="product-image">
											<img src="/themes/{{ $shop_theme }}/img/product-4.jpg">
										</div>
										<div class="details-container">
											<div class="product-name  match-height">Verdon Ne Silky Keratin Hair Spa</div>
											<div class="product-price">P 120.00</div>
										</div>
									</a>
									<div class="bottom-container">
										<div class="row-no-padding clearfix">
											<div class="col-md-6">
												<a href="#">
													<div class="image-holder" style="border-right: 1px solid #9e9e9e;">
														<img src="/themes/{{ $shop_theme }}/img/view-product-image.png">
													</div>
												</a>
											</div>
											<div class="col-md-6">
												<a href="#">
													<div class="image-holder">
														<img src="/themes/{{ $shop_theme }}/img/add-to-cart-image.png">
													</div>
												</a>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<div class="product-holder">
									<a href="javasctipt:" style="text-decoration: none">
										<div class="product-image">
											<img src="/themes/{{ $shop_theme }}/img/product-5.jpg">
										</div>
										<div class="details-container">
											<div class="product-name  match-height">Ashley Hair Serum</div>
											<div class="product-price">P 146.00</div>
										</div>
									</a>
									<div class="bottom-container">
										<div class="row-no-padding clearfix">
											<div class="col-md-6">
												<a href="#">
													<div class="image-holder" style="border-right: 1px solid #9e9e9e;">
														<img src="/themes/{{ $shop_theme }}/img/view-product-image.png">
													</div>
												</a>
											</div>
											<div class="col-md-6">
												<a href="#">
													<div class="image-holder">
														<img src="/themes/{{ $shop_theme }}/img/add-to-cart-image.png">
													</div>
												</a>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<div class="product-holder">
									<a href="javasctipt:" style="text-decoration: none">
										<div class="product-image">
											<img src="/themes/{{ $shop_theme }}/img/product-6.jpg">
										</div>
										<div class="details-container">
											<div class="product-name  match-height">EpSA Organic Shampoo</div>
											<div class="product-price">P 128.00</div>
										</div>
									</a>
									<div class="bottom-container">
										<div class="row-no-padding clearfix">
											<div class="col-md-6">
												<a href="#">
													<div class="image-holder" style="border-right: 1px solid #9e9e9e;">
														<img src="/themes/{{ $shop_theme }}/img/view-product-image.png">
													</div>
												</a>
											</div>
											<div class="col-md-6">
												<a href="#">
													<div class="image-holder">
														<img src="/themes/{{ $shop_theme }}/img/add-to-cart-image.png">
													</div>
												</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						@endif

				</div>
			</div>
			{{-- <div class="product-container">
				<div class="row clearfix">
					<div class="col-md-2">
						<div class="product-holder">
							<a href="javascript:" style="text-decoration: none;">
								<div class="product-image">
									<img src="/themes/{{ $shop_theme }}/img/product-1.jpg">
								</div>
								<div class="details-container">
									<div class="product-name  match-height">Elite Contact Lens Spartax Gray</div>
									<div class="product-price">P 360.00</div>
								</div>
							</a>
							<div class="bottom-container">
								<div class="row-no-padding clearfix">
									<div class="col-md-6">
										<a href="#">
											<div class="image-holder" style="border-right: 1px solid #9e9e9e;">
												<img src="/themes/{{ $shop_theme }}/img/view-product-image.png">
											</div>
										</a>
									</div>
									<div class="col-md-6">
										<a href="#">
											<div class="image-holder">
												<img src="/themes/{{ $shop_theme }}/img/add-to-cart-image.png">
											</div>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-2">
						<div class="product-holder">
							<a href="javascript:" style="text-decoration: none;">
								<div class="product-image">
									<img src="/themes/{{ $shop_theme }}/img/product-2.jpg">
								</div>
								<div class="details-container">
									<div class="product-name  match-height">Elite Contact Lens Mangge kyo Sharinggan</div>
									<div class="product-price">P 360.00</div>
								</div>
							</a>
							<div class="bottom-container">
								<div class="row-no-padding clearfix">
									<div class="col-md-6">
										<a href="#">
											<div class="image-holder" style="border-right: 1px solid #9e9e9e;">
												<img src="/themes/{{ $shop_theme }}/img/view-product-image.png">
											</div>
										</a>
									</div>
									<div class="col-md-6">
										<a href="#">
											<div class="image-holder">
												<img src="/themes/{{ $shop_theme }}/img/add-to-cart-image.png">
											</div>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-2">
						<div class="product-holder">
							<a href="javascript:" style="text-decoration: none;">
								<div class="product-image">
									<img src="/themes/{{ $shop_theme }}/img/product-3.jpg">
								</div>
								<div class="details-container">
									<div class="product-name  match-height">Elite Contact Lens Hydrocor Gray</div>
									<div class="product-price">P 240.00</div>
								</div>
							</a>
							<div class="bottom-container">
								<div class="row-no-padding clearfix">
									<div class="col-md-6">
										<a href="#">
											<div class="image-holder" style="border-right: 1px solid #9e9e9e;">
												<img src="/themes/{{ $shop_theme }}/img/view-product-image.png">
											</div>
										</a>
									</div>
									<div class="col-md-6">
										<a href="#">
											<div class="image-holder">
												<img src="/themes/{{ $shop_theme }}/img/add-to-cart-image.png">
											</div>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-2">
						<div class="product-holder">
							<a href="javascript:" style="text-decoration: none;">
								<div class="product-image">
									<img src="/themes/{{ $shop_theme }}/img/product-4.jpg">
								</div>
								<div class="details-container">
									<div class="product-name  match-height">Verdon Ne Silky Keratin Hair Spa</div>
									<div class="product-price">P 120.00</div>
								</div>
							</a>
							<div class="bottom-container">
								<div class="row-no-padding clearfix">
									<div class="col-md-6">
										<a href="#">
											<div class="image-holder" style="border-right: 1px solid #9e9e9e;">
												<img src="/themes/{{ $shop_theme }}/img/view-product-image.png">
											</div>
										</a>
									</div>
									<div class="col-md-6">
										<a href="#">
											<div class="image-holder">
												<img src="/themes/{{ $shop_theme }}/img/add-to-cart-image.png">
											</div>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-2">
						<div class="product-holder">
							<a href="javascript:" style="text-decoration: none;">
								<div class="product-image">
									<img src="/themes/{{ $shop_theme }}/img/product-5.jpg">
								</div>
								<div class="details-container">
									<div class="product-name  match-height">Ashley Hair Serum</div>
									<div class="product-price">P 146.00</div>
								</div>
							</a>
							<div class="bottom-container">
								<div class="row-no-padding clearfix">
									<div class="col-md-6">
										<a href="#">
											<div class="image-holder" style="border-right: 1px solid #9e9e9e;">
												<img src="/themes/{{ $shop_theme }}/img/view-product-image.png">
											</div>
										</a>
									</div>
									<div class="col-md-6">
										<a href="#">
											<div class="image-holder">
												<img src="/themes/{{ $shop_theme }}/img/add-to-cart-image.png">
											</div>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-2">
						<div class="product-holder">
							<a href="javascript:" style="text-decoration: none;">
								<div class="product-image">
									<img src="/themes/{{ $shop_theme }}/img/product-6.jpg">
								</div>
								<div class="details-container">
									<div class="product-name  match-height">EpSA Organic Shampoo</div>
									<div class="product-price">P 128.00</div>
								</div>
							</a>
							<div class="bottom-container">
								<div class="row-no-padding clearfix">
									<div class="col-md-6">
										<a href="#">
											<div class="image-holder" style="border-right: 1px solid #9e9e9e;">
												<img src="/themes/{{ $shop_theme }}/img/view-product-image.png">
											</div>
										</a>
									</div>
									<div class="col-md-6">
										<a href="#">
											<div class="image-holder">
												<img src="/themes/{{ $shop_theme }}/img/add-to-cart-image.png">
											</div>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div> --}}
		</div>
	</div>
	
	<!-- News Arrivals -->
	<div class="new-arrivals-container">
		<div class="container">
			<div class="title-container">
				<div class="title">New Arrivals</div>
			</div>
			<div class="product-carousel">

				@if(count($_product) > 0)

				    @foreach($_product as $product)
				    	<div class="holder">
				    		<div class="product-holder">
				    			<a class="item-hover" href="/product/view2/{{ $product['eprod_id'] }}" style="text-decoration: none;">
				    				<div class="product-image">
				    					<img src="{{ get_product_first_image($product) }}">
				    				</div>
				    				<div class="details-container">
				    					<div class="product-name match-height">{{ get_product_first_name($product) }}</div>
				    					<div class="product-price">{{ get_product_first_price($product) }}</div>
				    				</div>
				    			</a>
				    			<div class="bottom-container">
				    				<div class="row-no-padding clearfix">
				    					<div class="col-md-6">
				    						<a href="/product/view2/{{ $product['eprod_id'] }}">
				    							<div class="image-holder" style="border-right: 1px solid #9e9e9e;">
				    								<img src="/themes/{{ $shop_theme }}/img/view-product-image.png">
				    							</div>
				    						</a>
				    					</div>
				    					<div class="col-md-6">
				    						<a href="javascript:" class="product-add-cart" item-id="{{ $product['variant'][0]['evariant_item_id'] }}" quantity="1">
				    							<div class="image-holder">
				    								<img src="/themes/{{ $shop_theme }}/img/add-to-cart-image.png">
				    							</div>
				    						</a>
				    					</div>
				    				</div>
				    			</div>
				    		</div>
				    	</div>
				    @endforeach

				    @else
					<div class="holder">
						<div class="product-holder">
							<div class="product-image">
								<img src="/themes/{{ $shop_theme }}/img/product-1.jpg">
							</div>
							<div class="details-container">
								<div class="product-name  match-height">Elite Contact Lens Spartax Gray</div>
								<div class="product-price">P 360.00</div>
							</div>
							<div class="bottom-container">
								<div class="row-no-padding clearfix">
									<div class="col-md-6">
										<a href="#">
											<div class="image-holder" style="border-right: 1px solid #9e9e9e;">
												<img src="/themes/{{ $shop_theme }}/img/view-product-image.png">
											</div>
										</a>
									</div>
									<div class="col-md-6">
										<a href="#">
											<div class="image-holder">
												<img src="/themes/{{ $shop_theme }}/img/add-to-cart-image.png">
											</div>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="holder">
						<div class="product-holder">
							<div class="product-image">
								<img src="/themes/{{ $shop_theme }}/img/product-2.jpg">
							</div>
							<div class="details-container">
								<div class="product-name  match-height">Elite Contact Lens Mangge kyo Sharinggan</div>
								<div class="product-price">P 360.00</div>
							</div>
							<div class="bottom-container">
								<div class="row-no-padding clearfix">
									<div class="col-md-6">
										<a href="#">
											<div class="image-holder" style="border-right: 1px solid #9e9e9e;">
												<img src="/themes/{{ $shop_theme }}/img/view-product-image.png">
											</div>
										</a>
									</div>
									<div class="col-md-6">
										<a href="#">
											<div class="image-holder">
												<img src="/themes/{{ $shop_theme }}/img/add-to-cart-image.png">
											</div>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="holder">
						<div class="product-holder">
							<div class="product-image">
								<img src="/themes/{{ $shop_theme }}/img/product-3.jpg">
							</div>
							<div class="details-container">
								<div class="product-name  match-height">Elite Contact Lens Hydrocor Gray</div>
								<div class="product-price">P 240.00</div>
							</div>
							<div class="bottom-container">
								<div class="row-no-padding clearfix">
									<div class="col-md-6">
										<a href="#">
											<div class="image-holder" style="border-right: 1px solid #9e9e9e;">
												<img src="/themes/{{ $shop_theme }}/img/view-product-image.png">
											</div>
										</a>
									</div>
									<div class="col-md-6">
										<a href="#">
											<div class="image-holder">
												<img src="/themes/{{ $shop_theme }}/img/add-to-cart-image.png">
											</div>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="holder">
						<div class="product-holder">
							<div class="product-image">
								<img src="/themes/{{ $shop_theme }}/img/product-4.jpg">
							</div>
							<div class="details-container">
								<div class="product-name  match-height">Verdon Ne Silky Keratin Hair Spa</div>
								<div class="product-price">P 120.00</div>
							</div>
							<div class="bottom-container">
								<div class="row-no-padding clearfix">
									<div class="col-md-6">
										<a href="#">
											<div class="image-holder" style="border-right: 1px solid #9e9e9e;">
												<img src="/themes/{{ $shop_theme }}/img/view-product-image.png">
											</div>
										</a>
									</div>
									<div class="col-md-6">
										<a href="#">
											<div class="image-holder">
												<img src="/themes/{{ $shop_theme }}/img/add-to-cart-image.png">
											</div>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="holder">
						<div class="product-holder">
							<div class="product-image">
								<img src="/themes/{{ $shop_theme }}/img/product-5.jpg">
							</div>
							<div class="details-container">
								<div class="product-name  match-height">Ashley Hair Serum</div>
								<div class="product-price">P 146.00</div>
							</div>
							<div class="bottom-container">
								<div class="row-no-padding clearfix">
									<div class="col-md-6">
										<a href="#">
											<div class="image-holder" style="border-right: 1px solid #9e9e9e;">
												<img src="/themes/{{ $shop_theme }}/img/view-product-image.png">
											</div>
										</a>
									</div>
									<div class="col-md-6">
										<a href="#">
											<div class="image-holder">
												<img src="/themes/{{ $shop_theme }}/img/add-to-cart-image.png">
											</div>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="holder">
						<div class="product-holder">
							<div class="product-image">
								<img src="/themes/{{ $shop_theme }}/img/product-6.jpg">
							</div>
							<div class="details-container">
								<div class="product-name  match-height">EpSA Organic Shampoo</div>
								<div class="product-price">P 128.00</div>
							</div>
							<div class="bottom-container">
								<div class="row-no-padding clearfix">
									<div class="col-md-6">
										<a href="#">
											<div class="image-holder" style="border-right: 1px solid #9e9e9e;">
												<img src="/themes/{{ $shop_theme }}/img/view-product-image.png">
											</div>
										</a>
									</div>
									<div class="col-md-6">
										<a href="#">
											<div class="image-holder">
												<img src="/themes/{{ $shop_theme }}/img/add-to-cart-image.png">
											</div>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="holder">
						<div class="product-holder">
							<div class="product-image">
								<img src="/themes/{{ $shop_theme }}/img/product-6.jpg">
							</div>
							<div class="details-container">
								<div class="product-name  match-height">EpSA Organic Shampoo</div>
								<div class="product-price">P 128.00</div>
							</div>
							<div class="bottom-container">
								<div class="row-no-padding clearfix">
									<div class="col-md-6">
										<a href="#">
											<div class="image-holder" style="border-right: 1px solid #9e9e9e;">
												<img src="/themes/{{ $shop_theme }}/img/view-product-image.png">
											</div>
										</a>
									</div>
									<div class="col-md-6">
										<a href="#">
											<div class="image-holder">
												<img src="/themes/{{ $shop_theme }}/img/add-to-cart-image.png">
											</div>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				@endif
			</div>
		</div>
	</div>

	<!-- Our Trusted Brands -->
	<div class="trusted-brand-container">
		<div class="container">
			<div class="title-container">
				<div class="title">Our Trusted Brands</div>
			</div>
			{{-- div class="image-holder">
				<img src="/themes/{{ $shop_theme }}/img/trusted-brand-image-1.jpg" style="width: 85%;">
			</div> --}}
			<div class="brand-container">
				<span class="image-holder"><img src="/themes/{{ $shop_theme }}/img/trusted-brand-image-1.jpg"></span><span class="image-holder"><img src="/themes/{{ $shop_theme }}/img/trusted-brand-image-2.jpg"></span><span class="image-holder"><img src="/themes/{{ $shop_theme }}/img/trusted-brand-image-3.jpg"></span><span class="image-holder"><img src="/themes/{{ $shop_theme }}/img/trusted-brand-image-4.jpg"></span><span class="image-holder"><img src="/themes/{{ $shop_theme }}/img/trusted-brand-image-5.jpg"></span><span class="image-holder"><img src="/themes/{{ $shop_theme }}/img/trusted-brand-image-6.jpg"></span><span class="image-holder"><img src="/themes/{{ $shop_theme }}/img/trusted-brand-image-7.jpg"></span><span class="image-holder"><img src="/themes/{{ $shop_theme }}/img/trusted-brand-image-8.jpg"></span>
			</div>
		</div>
	</div>

	<!-- About Kolorete Marketing -->
	<div class="about-container">
		<div class="container">
			<div class="upper-container">
			    <div class="row clearfix">
			        <div class="col-md-4">
			            <div class="holder">
			                <div class="row-no-padding clearfix">
			                    <div class="col-md-4">
			                        <img src="/themes/{{ $shop_theme }}/img/trusted-image.png">
			                    </div>
			                    <div class="col-md-8">
			                        <div class="title">WE ARE TRUSTED</div>
			                        <div class="description">Secured transactions, Satisfaction, Guaranteed.</div>
			                    </div>
			                </div>
			            </div>
			        </div>
			        <div class="col-md-4">
			            <div class="holder">
			                <div class="row-no-padding clearfix">
			                   <div class="col-md-4">
			                        <img src="/themes/{{ $shop_theme }}/img/quality-image-1.png">
			                    </div>
			                    <div class="col-md-8">
			                        <div class="title">QUALITY PRODUCTS</div>
			                        <div class="description">We assure that we provide the best protection and quality at amazing prices.</div>
			                    </div>
			                </div>
			            </div>
			        </div>
			        <div class="col-md-4">
			            <div class="holder">
			                <div class="row-no-padding clearfix">
			                    <div class="col-md-4">
			                        <img src="/themes/{{ $shop_theme }}/img/quality-image-2.png">
			                    </div>
			                    <div class="col-md-8">
			                        <div class="title">QUALITY PRODUCTS</div>
			                        <div class="description">Fast Delivery on all places locally.</div>
			                    </div>
			                </div>
			            </div>
			        </div>
			    </div>
			</div>
			<div class="mid-container">
			    <div class="row clearfix">
			        <div class="col-md-8">
			            <div class="left-container">
			                <div class="title">KOLORETE MARKETING</div>
			                <div class="information">Kolorete Marketing started on June 16, 2016 as a simple online shop with physical store at Santa Rosa Laguna. Kolorete Marketing is an online shopping center for fashion, trends, styles and other general merchandise with a marketing system that helps every individual to earn while shopping or reselling products.</div>
			                <div class="title">SHIPPING OPTIONS</div>
			                <div class="subtitle">PICK UP</div>
			                <div class="information">1109 Espiritu Compound, Pooc Sta. Rosa City, Laguna</div>
			                <div class="subtitle">MEET UP</div>
			                <div class="information">Within the vicinity of Binan to Cabuyao Laguna</div>
			                <div class="subtitle">SHIPPING</div>
			                <div class="information">Metro Manila starts at P90</div>
			                <div class="information">Provincial starts at P120</div>
			                <div class="title">CONTACT US</div>
			                <div class="subtitle">Kolorete Marketing</div>
			                <div class="information">1109 Espiritu Compound Pooc Sta. Rosa City Laguna</div>
			                <div class="information">VIBER: 09467273576</div>
			                <div class="information">koloretemarketing@gmail.com</div>
			                <div class="information">facebook.com/koloretemarketing</div>
			            </div>
			        </div>
			        <div class="col-md-4">
			            <div class="right-container">
			                <img src="/themes/{{ $shop_theme }}/img/logo-footer.png">
			                <div class="title">FIND US ON MAP</div>
			                <div class="map-container">
			                    <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15464.659178244572!2d121.1135937!3d14.3018559!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x86f66d98403088c7!2sKolorete+Marketing!5e0!3m2!1sen!2sph!4v1516420495967" width="360" height="380" frameborder="0" style="border:0" allowfullscreen></iframe>

			                </div>
			            </div>
			        </div>
			    </div>
			</div>
		</div>
	</div>

<div class="scroll-up"><img src="/themes/{{ $shop_theme }}/img/scroll-up.png"></div>
</div>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/home.css?version=2">

@endsection

@section("js")
<script type="text/javascript" src="/themes/{{ $shop_theme }}/js/match-height.js"></script>
<script type="text/javascript" src="/themes/{{ $shop_theme }}/js/home.js"></script>
<script type="text/javascript">

	$(document).ready(function()
	{
		$('.single-item').slick({
			prevArrow:"<img class='a-left control-c prev slick-prev' src='/themes/{{ $shop_theme }}/img/arrow-left.png'>",
	      	nextArrow:"<img class='a-right control-c next slick-next' src='/themes/{{ $shop_theme }}/img/arrow-right.png'>",
	      	dots: false,
	      	autoplay: true,
	  		autoplaySpeed: 3000,
		});

	    lightbox.option({
	      'disableScrolling': true,
	      'wrapAround': true
	    });

	});
</script>

@endsection


