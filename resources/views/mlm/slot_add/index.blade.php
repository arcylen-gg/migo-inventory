<link rel="stylesheet" href="/assets/external/chosen/chosen/chosen.css" media="screen"/>
<style type="text/css">
	.input-v2
	{
		border-radius: 7px; width: 100%;
		height: 30px;
	}
	.form_container
	{
		max-height: 500px;
		overflow: scroll;
	}
</style>
<center>
<div class="form_container">
	<form class="global-submit" method="post" action="/mlm/claim/slot">
	{!! csrf_field() !!}
	<div>
		<table class="table">
			@if(!$codes)
			<input type="hidden" name="disabled_validation_code" value="0">
			<tr>
				<td>
					<div class="col-md-12">
						<label>PIN</label>
						<input type="number" class="form-control input-v2" name="membership_code_id" >
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="col-md-12">
						<label>MEMBERSHIP CODE</label>
						<input type="text" class="form-control input-v2" name="membership_activation_code">
					</div>
				</td>
			</tr>
			@else
			<tr>
				<td>
					<input type="hidden" name="disabled_validation_code" value="1">
					{!! $codes !!}
				</td>
			</tr>
			
				
			@endif
			<tr>
				<td>
					<!-- Binary Placing Settings -->
					<div class="col-md-12">
						@if($lead == null)
						<label for="">Sponsor</label>
							{{-- @if($sponsor_a == null) --}}
	                            <select class="form-control chosen-slot_sponsor input-sm pull-left" name="slot_sponsor" data-placeholder="Select Slot Sponsor" >
	                            	@if(count($_slots_sponse) != 0)
	                            		@foreach($_slots_sponse as $slot)
	                            			<option value="{{$slot->slot_id}}" @if($sponsor_a == $slot->slot_id) selected @endif >
	                            			{{$slot->first_name}} {{$slot->middle_name}} {{$slot->last_name}} 
	                            			({{$slot->slot_no}})
	                            			</option>
	                            		@endforeach
	                            	@endif
	                            </select>
	                       {{--  @else  --}}

	                        <input type="hidden" name="slot_sponsor" class="new_slot_id" value="{{$sponsor_a}}">
		                        @if(count($_slots_sponse) != 0) 
		                        	@foreach($_slots_sponse as $slot) 
		                        		@if($sponsor_a == $slot->slot_id)
		                        			<?php $name = $slot->first_name . ' ' . $slot->middle_name . ' ' . $slot->last_name . ' ' . $slot->slot_no; ?>
		                        			<input type="hidden" class="form-control input-v2" disabled="disabled"
	                       					value="{{$name}}">  
		                        		@endif 
		                        	@endforeach 
		                        @endif 

	                        {{-- @endif --}}
	                        
						@else
						<input type="hidden" name="lead_id" value="{{$lead->lead_id}}">
						<input type="hidden" name="slot_sponsor" class="new_slot_id" value="{{$lead->lead_slot_id_sponsor}}">
						<input type="text" class="form-control" name="sponsor" value="{{$lead->mlm_username}} (Slot - {{$lead->slot_no}}) {{$lead->membership_activation_code}}" readonly>
						@endif
					</div>
	                @if(isset($binary_settings->marketing_plan_enable))
	                    @if($binary_settings->marketing_plan_enable == 1)
	                        @if(isset($binary_advance->binary_settings_placement))
	                            @if($binary_advance->binary_settings_placement == 0)
	                                <div class="col-md-12">
	                                    <label for="">Slot Placement (Binary)</label>
	                                    <!-- <input type="text" class="form-control" name="slot_placement"> -->
	                                    @if($placement == null)
	                                    <select class="form-control chosen-slot_sponsor input-sm pull-left" name="slot_placement" data-placeholder="Select Slot Placement" >
			                            	@if(count($_slots) != 0)
			                            		@foreach($_slots as $slot)
			                            			<option value="{{$slot->slot_id}}" @if($placement == $slot->slot_id) selected @endif >{{$slot->first_name}} {{$slot->middle_name}} {{$slot->last_name}} ({{$slot->slot_no}})</option>
			                            		@endforeach
			                            	@endif
			                            </select>
			                            @else
											<input type="hidden" name="slot_placement" value="{{$placement}}">
											@if(count($_slots) != 0)
			                            		@foreach($_slots as $slot)
			                            			@if($placement == $slot->slot_id)
			                            				<?php $name = $slot->first_name . ' ' . $slot->middle_name . ' ' . $slot->last_name . ' ' . $slot->slot_no; ?>
			                            				<input type="text" class="form-control input-v2"  value="{{$name}}" disabled="disabled" >
			                            			@endif
			                            		@endforeach
			                            	@endif
			                            @endif
	                                </div>
	                                <div class="col-md-12">
	                                <!-- placement -->
	                                    <label for="">Slot Position</label>
	                                    {!! mlm_slot_postion('left', $position) !!}
	                                </div>
	                            @elseif($binary_advance->binary_settings_placement == 1)

	                                <div class="col-md-12">
	                                    <label for="">Slot Placement (Binary) <span style="color: red;">Auto Placement</span></label>
	                                    <input type="text" class="form-control" name="slot_placement" disabled="disabled">
	                                </div>

	                                <div class="col-md-12">
	                                    <label for="">Slot Position <span style="color: red;">Auto Placement</span></label>
	                                    <select class="form-control chosen-slot_position" name="slot_position" disabled="disabled">
	                                        <option value="left">left</option>
	                                        <option value="right">right</option>
	                                    </select>
	                                </div>
	                            @endif
	                        @else
	                            <center></center>
	                        @endif
	                    @else

	                    @endif
	                @else
	                    <center></center>
	                @endif
				</td>
			</tr>
			<tr>
				<td>
					<div class="col-md-12">
						<label>Choose Owner</label>
						<label><input id="your_account" type="radio" class="col-md-6" name="choose_owner" value="your" checked>Your Account</label>
						<label><input id="new_user" type="radio" class="col-md-6" name="choose_owner" value="new">New User</label>
						@if($shop_container->shop_key != "alphaglobal")
							<label><input id="exist" type="radio" class="col-md-6" name="choose_owner" value="exist">Existing User</label>
						@endif
					</div>
					<div class="col-md-12 new_form" style="display:none">
					    <label>First Name</label>
						<input class="form-control new_input" type="text" value="" name="first_name" disabled>
						<label>Last Name</label>
						<input class="form-control new_input" type="text" value="" name="last_name" disabled>
						<label>Email</label>
						<input class="form-control new_input" type="email" value="" name="email" disabled>
						<label>Username</label>
						<input class="form-control new_input" type="text" value="" name="mlm_username" disabled>						
						<label>Password</label>
						<input class="form-control new_input" type="password" value="" name="password" disabled>				
						<label>Confirm Password</label>
						<input class="form-control new_input" type="password" value="" name="c_password" disabled>
						<label>Country</label>
						<select name="country_id" class="form-control new_input" disabled>
							@foreach($country as $ctry)
								<option value="{{$ctry->country_id}}">{{$ctry->country_name}}</option>
							@endforeach
						</select>
					</div>
					<div class="col-md-12 exist_form" style="display:none">
						<label>Choose an owner (Customer with no slot only)</label>
						<select name="customer_id" class="form-control exist_input" disabled>
							@foreach($_no_slot_customer as $no_slot_customer)
								<option value="{{$no_slot_customer->customer_id}}">{{$no_slot_customer->first_name}} {{$no_slot_customer->middle_name}} {{$no_slot_customer->last_name}}</option>
							@endforeach
						</select>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<button class="btn btn-primary pull-right" id="rcorners3">SUBMIT</button>
				</td>
			</tr>
			<tr>
				<td class="append_error"></td>
			</tr>
		</table>
	</div>
	</form>
</div>
</center>
<script type="text/javascript" src="/assets/external/chosen/chosen/chosen.jquery.js"></script>
<script>
	function submit_done(data)
	{
		if(data.response_status == "warning")
    	{
    		var erross = data.warning_validator;
    		$.each(erross, function(index, value) 
    		{
    		    toastr.warning(value);
    		}); 
    	}
    	else if(data.response_status == 'warning_2')
    	{
    		toastr.warning(data.error);
    	}
    	else if(data.response_status == 'success_add_slot')
    	{
    		toastr.success('Congratulations, Your slot is created.');
    		toastr.success('Please, Login again for the changes to take effect');
    		window.location = "/mlm/login";
    	}
	}

	$(document).ready(function() 
	{
	    $('input[type=radio][name=choose_owner]').change(function() 
	    {
	        if (this.value == 'your') 
	        {
	            $(".new_form").hide();
	            $(".new_input").attr('disabled', true);
	            $(".append_error").empty();
	        }
	        else if (this.value == 'new') 
	        {
	         	$(".new_form").show();
	         	$(".new_input").attr('disabled', false);
	         	$(".append_error").empty();
	        }
	        else if (this.value == 'exist') 
	        {
	         	$(".exist_form").show();
	         	$(".exist_input").attr('disabled', false);
	         	$(".append_error").empty();
	        }
	    });
	});

</script>
<script type="text/javascript">

	$(".chosen-slot_sponsor").chosen({no_results_text: "The slot doesn't exist.", width: '100%'});
	$(".chosen-slot_position").chosen({no_results_text: "Invalid Position", width: '100%'});
	
</script>
<script>
$(document).ready(function()
{
	$(".chosen-slot_sponsor").chosen().change(function()
	{
		$(".new_slot_id").val($(this).val());
	});
});
	
</script>