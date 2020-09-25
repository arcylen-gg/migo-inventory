<div class="col-md-12" style="margin-top: 10px; outline: 1px solid #3c8dbc; background-color: #3c8dbc;">
	<div class="col-md-12" style="height: 100px; background-color: #3c8dbc;">
		<center>
		<?php $customer_data->profile != null ? $profile = $customer_data->profile :  $profile = '/assets/mlm/default-pic.png' ?>
		<img style="border-radius: 50%; border: 1px solid white; "class="card-img-top" src="{{ Request::segment(5) == 'pdf' || Request::input('pdf')=='true' || Request::segment(5) == 'process' ? (public_path().$profile) : $profile }}" width="100" height="100" alt="Card image cap"></center>
		<!--  -->
	</div>
	<div class="col-md-12" style="background-color: white;">
		@if(isset($slot->membership_activation_code)) 
		<span class="pull-right"> <svg id="barcode" ></svg> </span> 
		<input type="hidden" class="chosen-slot_id slot_id" name="slot_id" value="{{$slot->slot_id}}">
		@else
			@if(isset($slot_info))
				@if(isset($slot_info->slot_id))
				<input type="hidden" class="chosen-slot_id slot_id" name="slot_id" value="{{$slot_info->slot_id}}">
				@else
				<input type="hidden" class="chosen-slot_id slot_id" name="slot_id" value="">
				@endif
			@else
				@if(isset($discount_card))
				<input type="hidden" class="chosen-slot_id slot_id" name="slot_id" value="">
				@endif
			@endif	
		@endif
		@if(isset($discount_card->discount_card_log_code)) 
		<input type="hidden" class="discount_card_log_id" name="discount_card_log_id" value="{{$discount_card->discount_card_log_id}}">
		<span class="pull-right" @if($discount_card->discount_card_log_is_expired == 1) style="color:red;" @endif> <svg id="barcode" ></svg> <br>Expiry: {{$discount_card->discount_card_log_date_expired == null ? 'Expiry Date will Generate After Use' : $discount_card->discount_card_log_date_expired }}</span> 
		@else
		<input type="hidden" class="discount_card_log_id" name="discount_card_log_id">
		@endif
		<h3>
			{{$customer_data->mlm_username}}
			<input type="hidden" name="customer_id" value="{{$customer_data->customer_id}}">
		</h3>
			
			<hr />
		
		<div class="col-md-12">
			<small style="color:gray;">
				Name
				</small>
				<br />
			{{$customer_data->first_name}} {{$customer_data->middle_name}} {{$customer_data->last_name}} {{$customer_data->suffix_name}}
			
			
		</div>
		<div class="col-md-12">

			<small style="color:gray;">
			Email
			</small>
			<br />
			{{$customer_data->email}}
			<input type="hidden" name="item_code_customer_email" value="{{$customer_data->email}}">
			
		</div>
		<div class="col-md-12">

			<small style="color:gray;">
			Birthday
			</small>
			<br />
			{{$customer_data->b_day == null ? '0000-00-00': $customer_data->b_day}}
			
		</div>
	</div>
</div>
@if(Request::segment(5) == 'pdf' || Request::segment(5) == 'process' || Request::input('pdf')=='true')
<script src="{{public_path()}}/assets/mlm/barcode/JsBarcode.all.min.js"></script>
@else
<script src="/assets/mlm/barcode/JsBarcode.all.min.js"></script>
@endif
<script>
@if(isset($slot->membership_activation_code))
	@if(isset($slot)) 
	$("#barcode").JsBarcode('{{$slot->membership_activation_code}}', {
		width:1,
		height:30,
		displayValue: false,
	}); 
	@endif
@endif
@if(isset($discount_card->discount_card_log_code))
$("#barcode").JsBarcode('{{$discount_card->discount_card_log_code}}', {
		width:1,
		height:30,
		displayValue: true,
	}); 
@endif
</script>
