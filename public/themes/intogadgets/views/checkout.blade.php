@extends('layout')
@section('content')
<div class="container checkout">
	<div class="row clearfix">
		<div class="col-md-12 text-center">
			<img src="/resources/assets/img/checkout.jpg">
		</div>
		<div class="col-md-8">
			<div class="checkout-form">
				<form id="check-out" method="post">
					<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />

					@if(session("error"))
					    <div class="alert alert-danger" style="margin-top: 25px;">
					        <ul>
					        	<li>{{ session("error") }}</li>
					        </ul>
					    </div>
					@endif

					@if (count($errors) > 0)
					    <div class="alert alert-danger" style="margin-top: 25px;">
					        <ul>
					            @foreach ($errors->all() as $error)
					            	@if($error == "Fail(Bank Declined Transaction)")
					                	<li>Your card was declined. In order to resolve the issue, you will need to contact your back</li>
					                @else
					                	<li>{{ $error }}</li>
					                @endif
					            @endforeach
					        </ul>
					    </div>
					@endif



					<div class="fieldset">
						<div class="title col-md-12">Shipping Details</div>
					</div>


					@if($get_cart["new_account"] == false)

							<div class="form-group">
								<div style="border: 1px solid #8ec462;padding:10px">
									<div class="row">
										<div class="col-xs-2" style="padding-top: 40px;text-align: center">
											<i class="fa fa-check-circle-o" style="color:#8ec462;font-size: 25px"></i>
										</div>
										<div class="col-xs-10">
											<label>{{ ucfirst($customer->first_name . ' ' . $customer->middle_name . ' ' . $customer->last_name) }} </label> <br>
											<label>{{ $shipping_address->customer_street }}</label> <br>
											<label>{{ $shipping_address->customer_zipcode}}, {{ $shipping_address->customer_city}}, {{ $shipping_address->customer_state}}</label> <br>
											<label>Mobile Number : {{ $customer->customer_mobile }}</label>
										</div>								
									</div>
								</div>
							</div>
							<div class="fieldset hidden ">
								<label class="col-md-4">First and Last Name</label>
								<div class="field col-md-8">
									<div readonly class="form-control">{{ $customer->first_name . ' ' . $customer->middle_name . ' ' . $customer->last_name }}</div>
								</div>
							</div>
							<div class="fieldset hidden ">
								<label class="col-md-4">Contact Number</label>
								<div class="field col-md-8">
									<input readonly maxlength="11" class="form-control" type="text" name="contact_number" value="{{ $customer->customer_mobile }}">
								</div>
							</div>
							<div class="fieldset hidden ">
								<label class="col-md-4">Province</label>
								<div class="field col-md-8">
									<select readonly firstload="true" default="{{ $shipping_address->state_id }}" class="form-control load-location" name="customer_state" level="1"></select>
								</div>
							</div>
							<div class="fieldset hidden ">
								<label class="col-md-4">City / Municipality</label>
								<div class="field col-md-8">
									<select readonly firstload="true" default="{{ $shipping_address->city_id }}" class="form-control load-location" name="customer_city" level="2">
										<option></option>
									</select>
								</div>
							</div>
							<div class="fieldset hidden ">
								<label class="col-md-4">Barangay</label>
								<div class="field col-md-8">
									<select readonly firstload="true" default="{{ $shipping_address->zipcode_id }}" class="form-control load-location" name="customer_zip" level="3">
										<option></option>
									</select>
								</div>
							</div>
							<div class="fieldset hidden ">
								<label class="col-md-4">Street</label>
								<div class="field col-md-8">
									<textarea readonly spellcheck="false" class="form-control" name="customer_street">{{ $shipping_address->customer_street }}</textarea>
								</div>
							</div>
					
					@else
						<div class="fieldset">
							<label class="col-md-4">First and Last Name</label>
							<div class="field col-md-8">
								<input required class="form-control" type="text" name="full_name" value="{{ old('full_name') }}">
							</div>
						</div>
						<div class="fieldset">
							<label class="col-md-4">Contact Number</label>
							<div class="field col-md-8">
								<input required maxlength="11" class="form-control" type="text" name="contact_number" value="{{ Request::input('customer_mobile') }}">
							</div>
						</div>
						<div class="fieldset">
							<label class="col-md-4">Province</label>
							<div class="field col-md-8">
								<select required firstload="true" default="{{ old('customer_state') }}" class="form-control load-location" name="customer_state" level="1"></select>
							</div>
						</div>
						<div class="fieldset">
							<label class="col-md-4">City / Municipality</label>
							<div class="field col-md-8">
								<select required firstload="true" default="{{ old('customer_city') }}" class="form-control load-location" name="customer_city" level="2">
									<option></option>
								</select>
							</div>
						</div>
						<div class="fieldset">
							<label class="col-md-4">Barangay</label>
							<div class="field col-md-8">
								<select required firstload="true" default="{{ old('customer_zip') }}" class="form-control load-location" name="customer_zip" level="3">
									<option></option>
								</select>
							</div>
						</div>
						<div class="fieldset">
							<label class="col-md-4">Street</label>
							<div class="field col-md-8">
								<textarea required spellcheck="false" class="form-control" name="customer_street">{{ Request::old('customer_street') }}</textarea>
							</div>
						</div>
					@endif

					
					<div class="fieldset">
						<label class="col-md-4">Coupon Code</label>
						<div class="field col-md-8">
							<input class="form-control" type="text" name="coupon_code" value="">
						</div>
					</div>

					@if($get_cart["new_account"] == false)
						<div class="fieldset">
							<div class="field col-md-12">
								<div class="checkbox">
								    <label>
								      <input type="checkbox" class="checkbox-bill"> Bill to different address
								    </label>
								  </div>
							</div>
						</div>

						<div class="different-container disable-bill hide">
							<div class="fieldset">
								<div class="title col-md-12">Billing Details</div>
							</div>
							<div class="fieldset">
								<label class="col-md-4">Province</label>
								<div class="field col-md-8">
									<select disabled firstload="true" default="{{ old('customer_state') }}" class="form-control bill-load-location" name="customer_state" level="1"></select>
								</div>
							</div>
							<div class="fieldset">
								<label class="col-md-4">City / Municipality</label>
								<div class="field col-md-8">
									<select disabled firstload="true" default="{{ old('customer_city') }}" class="form-control bill-load-location" name="customer_city" level="2">
										<option></option>
									</select>
								</div>
							</div>
							<div class="fieldset">
								<label class="col-md-4">Barangay</label>
								<div class="field col-md-8">
									<select disabled firstload="true" default="{{ old('customer_zip') }}" class="form-control bill-load-location" name="customer_zip" level="3">
										<option></option>
									</select>
								</div>
							</div>
							<div class="fieldset">
								<label class="col-md-4">Street</label>
								<div class="field col-md-8">
									<textarea disabled spellcheck="false" class="form-control" name="customer_street">{{ Request::old('customer_street') }}</textarea>
								</div>
							</div>
						</div>
					@endif

					<div class="fieldset text-right btn-container">
						<div class="col-md-12">
							<button class="btn btn-primary checkout-button-submit" disabled>LOADING...</button>
						</div>
					</div>
				</form>
			</div>
		</div>

		<!-- CART HERE -->
		<div class="col-md-4 order-summary-container">
		</div>
	</div>
</div>

@endsection

@section('script')
<script type="text/javascript" src="/assets/front/js/global_checkout.js"></script>
@endsection

@section('css')
<link rel="stylesheet" href="resources/assets/frontend/css/checkout.css">
@endsection