<div class="col-md-12">
	@if(isset($customer_info->membership_activation_code))
		@if(isset($customer_view))
			{!! $customer_view !!}
		@else
		<center>Invalid Membership Code.</center>
		@endif
	@else
	<center>Invalid Membership Code.</center>
	@endif
</div>