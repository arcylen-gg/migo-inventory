<!-- BROWN POPUP KIT -->
<div class="brown-popup-kit">
	<!-- Modal content-->
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">BROWN&PROUD KITS</h4>
		</div>
		<div class="modal-body">
			<div class="row-no-padding clearfix">
				@foreach($item_kit as $kit)
				<div class="col-md-4 col-sm-4 col-xs-4">
					<div class="kit-parent-holder">
						<div class="kit-holder match-height">
							<div class="product-img-container">
								{{-- <div class="product-hover">
									<div class="kit-small">
										<img src="{{ $kit->item_img }}">
									</div>
									<div class="detail-container">
										<p>
											{!! $kit->item_sales_information ? $kit->item_sales_information : $kit->item_purchasing_information !!}
										</p>
									</div>
								</div> --}}
								<img src="{{ $kit->item_img }}">
							</div>
							{{-- <div class="product-title">{{ $kit->item_name }}</div>
							<div class="kit-category">
								Item Kit Category
							</div> --}}
							<div class="product-price">P {{ number_format($kit->item_price, 2) }}</div>
							<button class="btn btn-custom product-add-cart" {{$kit->inventory_count == 0 ? 'disabled' : 'item-id='.$kit->item_id}} quantity="1">ENROLL NOW</button>
						</div>
					</div>
				</div>
				@endforeach
				{{-- <div class="col-md-4">
					<div class="kit-parent-holder">
						<div class="kit-holder match-height">
							<div class="product-title">Kit-A Lorem ipsum dolor sit amet.</div>
							<div class="product-img-container">
								<div class="product-hover">
									<div class="kit-small">
										<img src="/themes/{{ $shop_theme }}/img/kit-sample-a.jpg">
									</div>
									<div class="detail-container">
										<p>
											Lorem ipsum dolor sit amet, consectetur adipisicing elit. Labore tempora dolore incidunt culpa magni distinctio nisi iusto dolorem ea, provident eligendi aspernatur ut numquam minus praesentium recusandae rerum non nulla.
										</p>
									</div>
								</div>
								<img src="/themes/{{ $shop_theme }}/img/kit-sample-a.jpg">
							</div>
							<div class="product-price">P 9,900.00</div>
							<button class="btn btn-custom">ENROLL NOW</button>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="kit-parent-holder">
						<div class="kit-holder match-height">
							<div class="product-title">Kit-B Lorem ipsum dolor sit amet, consectetur adipisicing.</div>
							<div class="product-img-container">
								<div class="product-hover">
									<div class="kit-small">
										<img src="/themes/{{ $shop_theme }}/img/kit-sample-b.jpg">
									</div>
									<div class="detail-container">
										<p>
											Lorem ipsum dolor sit amet, consectetur adipisicing elit. Labore tempora dolore incidunt culpa magni distinctio nisi iusto dolorem ea, provident eligendi aspernatur ut numquam minus praesentium recusandae rerum non nulla.
										</p>
									</div>
								</div>
								<img src="/themes/{{ $shop_theme }}/img/kit-sample-b.jpg">
							</div>
							<div class="product-price">P 5,900.00</div>
							<button class="btn btn-custom">ENROLL NOW</button>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="kit-parent-holder">
						<div class="kit-holder match-height">
							<div class="product-title">Kit-C Lorem ipsum dolor.</div>
							<div class="product-img-container">
								<div class="product-hover">
									<div class="kit-small">
										<img src="/themes/{{ $shop_theme }}/img/kit-sample-c.jpg">
									</div>
									<div class="detail-container">
										<p>
											Lorem ipsum dolor sit amet, consectetur adipisicing elit. Labore tempora dolore incidunt culpa magni distinctio nisi iusto dolorem ea, provident eligendi aspernatur ut numquam minus praesentium recusandae rerum non nulla.
										</p>
									</div>
								</div>
								<img src="/themes/{{ $shop_theme }}/img/kit-sample-c.jpg">
							</div>
							<div class="product-price">P 2,900.00</div>
							<button class="btn btn-custom">ENROLL NOW</button>
						</div>
					</div>
				</div> --}}
			</div>
		</div>
		<div class="modal-footer">
			<!-- <button type="button" class="btn btn-success pull-left">ADD TO CART</button> -->
		</div>
	</div>
</div>