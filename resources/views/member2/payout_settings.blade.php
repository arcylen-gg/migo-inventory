@if($_method)
<form method="post" class="form-payout-submit">
	{{ csrf_field() }}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">PAYOUT DETAILS</h4>
	</div>
	<div class="modal-body clearfix" style="position: relative;">
		<div class="loader-payout" style="display: none; top: 50%; z-index: 1; transform: translateY(-50%);position: absolute; left: 0; right: 0; text-align: center; background-color: rgba(255,255,255,0.5);">
    		<img src="/assets/member/img/91.gif">
    	</div>
		<div class="row">
	        <div class="clearfix modal-body"> 
	            <div class="col-md-6">
	                <label for="basic-input">How would you like to receive your payout?</label>
	                <select name="customer_payout_method" class="form-control choose-method">
	                	@foreach($_method as $method)
	                		<option {{ $customer->customer_payout_method == $method ? "selected" : "" }} value="{{ $method }}">{{ $method == "vmoney" ? "E-MONEY" : strtoupper($method) }}</option>
	                	@endforeach
	                </select>
	            </div>

	            <div class="col-md-6">
	                <label for="basic-input">Enter Your Tin Number</label>
	                <input class="form-control" required="required" type="text" name="tin_number" value="{{ trim($tin_number) }}">
	            </div>
	        </div>
	    </div>

	    {{-- AIRLINE --}}
		<div class="row payout-mode-container" content="airline">
	        <div class="clearfix modal-body"> 
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead style="text-transform: uppercase">
                                <tr>
                                    <th class="text-center" style="width: 100px;">SLOT CODE</th>
                                    <th class="text-center" width="120px">AIRLINE TICKETING ACCOUNT ID</th>
                                </tr>
                            </thead>
                            <tbody>
                            	@if(isset($_airline_slot))
	                            	@foreach($_airline_slot as $slot)
	                                <tr>
	                                	<input type="hidden" name="airline[]" value="{{ $slot->slot_no }}">
	                                    <td class="text-center" style="vertical-align: middle;">{{ $slot->slot_no }}</td>
	                                    <td class="text-center">
	                                    	<input type="text" class="form-control text-center" name="tour_wallet_account_id[]" value="{{ $slot->tour_wallet_account_id }}">
	                                    </td>
	                                </tr>
	                                @endforeach
	                            @else
	                            	<tr>
	                            		<td colspan="2" style="text-align: center;">Only VIP Gold Membership</td>
	                            	</tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
	        </div>
		</div>

	    {{-- VMONEY --}}
		<div class="row payout-mode-container" content="vmoney">
	        <div class="clearfix modal-body"> 
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead style="text-transform: uppercase">
                                <tr>
                                    <th class="text-center" style="width: 100px;">SLOT CODE</th>
                                    <th class="text-center" width="120px">E-MONEY EMAIL ADDRESS</th>
                                </tr>
                            </thead>
                            <tbody>
                            	@foreach($_slot as $slot)
                                <tr>
                                	<input type="hidden" name="vmoney[]" value="{{ $slot->slot_no }}">
                                    <td class="text-center" style="vertical-align: middle;">{{ $slot->slot_no }}</td>
                                    <td class="text-center">
                                    	<input type="text" class="form-control text-center" name="vmoney_email[]" value="{{ $slot->vmoney_email }}">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
	        </div>
		</div>

	    {{-- EON CARD --}}
		<div class="row payout-mode-container" content="eon">
	        <div class="clearfix modal-body"> 
	                <div class="col-md-12">
	                    <div class="table-responsive">
	                        <table class="table table-bordered">
	                            <thead style="text-transform: uppercase">
	                                <tr>
	                                    <th class="text-center" style="width: 100px;">SLOT CODE</th>
	                                    <th class="text-center" width="120px">EON ACCOUNT NUMBER</th>
	                                    <th class="text-center" width="120px">EON CARD NUMBER</th>
	                                </tr>
	                            </thead>
	                            <tbody>
	                            	@foreach($_slot as $slot)
	                                <tr>
	                                	<input type="hidden" name="eon_slot_code[]" value="{{ $slot->slot_no }}">
	                                    <td class="text-center" style="vertical-align: middle;">{{ $slot->slot_no }}</td>
	                                    <td class="text-center">
	                                    	<input type="text" class="form-control text-center" name="eon_account_no[]" value="{{ $slot->slot_eon_account_no }}">
	                                    </td>
	                                    <td class="text-center">
	                                    	<input type="text" class="form-control text-center" name="eon_card_no[]" value="{{ $slot->slot_eon_card_no }}">
	                                    </td>
	                                </tr>
	                                @endforeach
	                            </tbody>
	                        </table>
	                    </div>
	                </div>
	        </div>
		</div>

		{{-- BANK DEPOSIT --}}
		<div class="row bank-table payout-mode-container" content="bank">
	        <div class="clearfix modal-body"> 
	            <div class="col-md-12">
	                <div class="table-responsive">
	                    <table class="table table-bordered">
	                        <thead style="text-transform: uppercase">
	                            <tr>
	                                <th class="text-center" style="width: 100px;">SLOT CODE</th>
	                                <th class="text-center" width="120px">BANK</th>
	                                <th class="text-center" width="25px">TYPE</th>
	                                <th class="text-center" width="120px">BANK ACCOUNT NUMBER</th>
	                            </tr>
	                        </thead>
	                        <tbody>
	                        	@foreach($_slot as $slot)
	                            <tr>
	                            	<input type="hidden" name="bank_slot_no[]" value="{{ $slot->slot_no }}">
	                                <td class="text-center" style="vertical-align: middle;">{{ $slot->slot_no }}</td>
	                                <td class="text-center">
	                                	<select name="bank_id[]" class="form-control">
	                                		@foreach($_bank as $bank)
	                                			<option {{ $slot->payout_bank_id == $bank->payout_bank_id ? 'selected' : '' }} value="{{ $bank->payout_bank_id  }}">{{ $bank->payout_bank_name }}</option>
	                                		@endforeach
	                                	</select>
	                                </td>
	                                <td class="text-center">
	                                	<select name="bank_account_type[]" class="form-control text-center">
	                                		<option>SA</option>
	                                		<option>CA</option>
	                                	</select>
	                                </td>
	                              	<td class="text-center">
	                                	<input name="bank_account_no[]" type="text" value="{{ $slot->bank_account_number }}" class="form-control text-center" >
	                                </td>
	                            </tr>
	                            @endforeach
	                        </tbody>
	                    </table>
	                </div>
	            </div>
	        </div>
		</div>

		{{-- PALAWAN EXPRESS --}}
		<div class="row bank-table payout-mode-container" content="palawan_express">
	        <div class="clearfix modal-body">
	            <div class="col-md-12">
	                <div class="table-responsive">
	                    <table class="table table-bordered">
	                        <thead style="text-transform: uppercase">
	                            <tr>
	                                <th class="text-center" style="width: 100px;">SLOT CODE</th>
	                                <th class="text-center" width="120px">NAME</th>
	                                <th class="text-center" width="120px">Contact Number</th>
	                            </tr>
	                        </thead>
	                        <tbody>
	                        	@foreach($_slot as $slot)
	                            <tr>
	                            	<input type="hidden" name="remittance_slot_no[]" value="{{ $slot->slot_no }}">
	                            	<input type="hidden" name="money_remittance_type[]" value="palawan_express">
	                                <td class="text-center" style="vertical-align: middle;">{{ $slot->slot_no }}</td>
	                                <td class="text-center">
	                                	<div class="row clearfix">
	                                		<div class="col-md-4">
	                                			<input type="text" class="form-control" placeholder="First Name" name="first_name[]" value="{{ $slot->first_name }}">
	                                		</div>
	                                		<div class="col-md-4">
	                                			<input type="text" class="form-control" placeholder="Middle Name" name="middle_name[]" value="{{ $slot->middle_name }}">
	                                		</div>
	                                		<div class="col-md-4">
	                                			<input type="text" class="form-control" placeholder="Last Name" name="last_name[]" value="{{ $slot->last_name }}">	                                		
	                                		</div>
	                                	</div>
	                                </td>
	                              	<td class="text-center">
	                                	<input name="contact_number[]" type="text" value="{{ $slot->contact_number }}" class="form-control text-center" >
	                                </td>
	                            </tr>
	                            @endforeach
	                        </tbody>
	                    </table>
	                </div>
	            </div>
	        </div>
		</div>

		{{-- COINS.PH CARD --}}
		<div class="row payout-mode-container" content="coinsph">
	        <div class="clearfix modal-body"> 
	                <div class="col-md-12">
	                    <div class="table-responsive">
	                        <table class="table table-bordered">
	                            <thead style="text-transform: uppercase">
	                                <tr>
	                                    <th class="text-center" style="width: 100px;">SLOT CODE</th>
	                                    <th class="text-center">WALLET ADDRESS</th>
	                                </tr>
	                            </thead>
	                            <tbody>
	                            	@foreach($_slot as $slot)
	                                <tr>
	                                	<input type="hidden" name="coinsph_slot_no[]" value="{{ $slot->slot_no }}">
	                                    <td class="text-center" style="vertical-align: middle;">{{ $slot->slot_no }}</td>
	                                    <td class="text-center">
	                                    	<input type="text" class="form-control text-center" name="wallet_address[]" value="{{ $slot->wallet_address }}">
	                                    </td>
	                                </tr>
	                                @endforeach
	                            </tbody>
	                        </table>
	                    </div>
	                </div>
	        </div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-def-white btn-custom-white btn-custom-close" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
		<button type="submit" class="btn btn-custom-primary update-payout-button" type="button"><i class="fa fa-pencil"></i> Update Payout Details</button>
	</div>
</form>
@else
	<div class="text-center" style="padding: 100px;">PAYOUT HASN'T BEEN SET YET - CONTACT THE ADMINISTRATOR</div>
@endif

<script type="text/javascript">
	
var payout = new payout();

function payout()
{
	init();

	function init()
	{
		action_show_hide_based_on_method();
		event_change_method();
		event_payout_submit();
	}
}
	
function event_change_method()
{
	$(".choose-method").change(function()
	{
		action_show_hide_based_on_method();
	});
}
function action_show_hide_based_on_method()
{
	$method = $(".choose-method").val();
	$(".payout-mode-container").hide();
	$(".payout-mode-container[content=" + $method + "]").fadeIn();
}

function event_payout_submit()
{
	$(".form-payout-submit").submit(function(e)
	{
		$(".loader-payout").show();
		$(".update-payout-button").prop("disabled", true);
		action_submit_payout_details($(e.currentTarget).serialize());
		return false;
	});
}

function action_submit_payout_details(form_data)
{
	$.ajax(
	{
		url:"/members/payout-setting",
		dataType:"json",
		data: form_data,
		type:"post",
		success: function(data)
		{
			$(".update-payout-button").prop("disabled", false);
			$(".loader-payout").hide();

			if (data.status == "success") 
			{
				$("#global_modal").modal("hide");
				setTimeout(function()
				{
					action_load_link_to_modal("/members/payout-setting-success", "sm");
					$(".top-message-warning.for-payout").fadeOut();
				}, 350);
			}
			else
			{
				toastr.error(data.message);
			}
		}
	});
}
</script>