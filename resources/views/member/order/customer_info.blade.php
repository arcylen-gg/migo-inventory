
<input type="hidden" name="" id="hidden_customer" value="{{$customer->customer_id}}">
<input type="hidden" name="" id="hidden_order" value="{{isset($order_id)?$order_id:'0'}}">
<input type="hidden" name="" id="customer_tax" value="0">
<div class="form-horizontal">
	<div class="form-group">
		<div class="col-md-12">
			<img src="{{$customer->profile}}" class="img60x60">
			<span class="pull-right"><i class="fa fa-check-square-o" aria-hidden="true"></i>&nbsp;0&nbsp;orders</span>
		</div>
	</div>
	<div class="form-group">
		<div class="col-md-12">
			<a href="#" class="f18">{{$customer->first_name.' '.$customer->last_name}}</a>
		</div>
	</div>
	<div class="form-group">
		<div class="col-md-12">
			<a href="mailto:{{$customer->email}}" class="a-email">{{$customer->email}}</a>
			<a href="#" class="pull-right a-edit-email" data-content="{{$customer->email}}" data-id="{{$customer->customer_id}}" data-toggle="modal" data-target="#ModalEmailUpdate">Edit</a>
		</div>
	</div>

	<hr>
	<div class="form-group">
		<div class="col-md-12">
			<span>SHIPPING ADDRESS</span>
			<a href="#" class="pull-right a-edit-shipping" data-toggle="modal" data-target="#ShippingAddModal" data-first="{{$customer->first_name}}" data-last="{{$customer->last_name}}" data-company="{{$customer->company}}" data-address="{{$customer->_address}}" data-city="{{$customer->city}}" data-zip="{{$customer->zip_code}}" data-province="{{$customer->province}}" data-phone="{{$customer->phone}}" data-country="{{$customer->country_id}}" data-cont="{{$customer->_address_cont}}" data-cname="{{$customer->country_name}}" data-id="{{$customer->customer_id}}">Edit</a>
		</div>
	</div>
	<div class="form-group">
		<div class="col-md-12">
		
			<span class="light-gray">{{$customer->first_name.' '.$customer->last_name}}</span><br>
			<span class="light-gray">{{$customer->company}}</span><Br>
			<span class="light-gray">{{isset($shipping->customer_street) ? $shipping->customer_street : ''}}</span><Br>
			<span class="light-gray">{{isset($shipping->customer_city)? $shipping->customer_city : '' }}</span><Br>
			<span class="light-gray">{{isset($shipping->customer_zipcode)? $shipping->customer_zipco : ''}}&nbsp;{{isset($shipping->customer_state) ? $shipping->customer_state : ''}}</span><Br>
			<span class="light-gray">{{isset($shipping->country_name)? $shipping->country_name : '' }}</span><Br>
			<span class="light-gray">{{isset($other->customer_phone)? $other->customer_phone : '' }}</span><Br>
		</div>
	</div>
</div>
