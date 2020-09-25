<div class="modal-header">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Create New Customer Slot</span>
                <small>
                    Add new customer slot.
                </small>
            </h1>
            <button type="button" class="close" data-dismiss="modal">×</button>
        </div>
    </div>

</div>

<div class="modal-body add_new_package_modal_body clearfix">
    <div class="table-responsive panel">
        <form class="global-submit" action="/member/mlm/slot/add/submit" method="post" id="add_slot_form">
            {!! csrf_field() !!}
            <table class="table table-condensed">
                <tbody>
                    <tr>
                        <td>
                            <div class="col-md-4">
                               <label for="slot_no">Slot #(Expected)</label>
                               <input type="text" class="form-control" name="slot_no" value="{{$slotno}}" disabled="disabled"> 
                            </div>
                            <div class="col-md-4">
                               <label for="slot_no">Slot Owner(Customer)</label>
                               <select class="form-control chosen-select input-sm pull-left" name="slot_owner" data-placeholder="Select a Customer" style="width: calc(100% - 43px);" onChange="get_membership_code(this)">
                                    <option value=""></option>
        							@if(count($_customer) != 0)
        								@foreach($_customer as $customer)
        									<option value="{{$customer->customer_id}}" e_mail="{{$customer->email}}">{{$customer->first_name}} {{$customer->middle_name}} {{$customer->last_name}}</option>
        								@endforeach
        							@endif
                                </select>
                                <button type="button" style="display: inline-block; margin-top: -3px;" class="btn btn-default popup" size="lg" link="/member/customer/modalcreatecustomer"><i class="fa fa-plus"></i></button>
                            </div>
                            <div class="col-md-4" id="membership_code_div">
                                
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="col-md-4">
                                <label for="">Sponsor</label>
                                <select class="form-control chosen-slot_sponsor input-sm pull-left" name="slot_sponsor" data-placeholder="Select Slot Sponsor" >
                                    <option value=""></option>
                                	@if(count($_slots) != 0)
                                		@foreach($_slots as $slot)
                                			<option value="{{$slot->slot_id}}" @if($sponsor == $slot->slot_id) selected @endif >{{$slot->first_name}} {{$slot->middle_name}} {{$slot->last_name}} ({{$slot->slot_no}})</option>
                                		@endforeach
                                	@endif
                                </select>
                            </div>

                            <!-- Binary Placing Settings -->
                            @if(isset($binary_settings->marketing_plan_enable))
                                @if($binary_settings->marketing_plan_enable == 1)
                                    @if(isset($binary_advance->binary_settings_placement))
                                        @if($binary_advance->binary_settings_placement == 0)
                                            <div class="col-md-4">
                                                <label for="">Slot Placement (Binary)</label>
                                                <select class="form-control chosen-slot_placement input-sm pull-left" name="slot_placement" data-placeholder="Select Slot Sponsor" >
                                                    <option value=""></option>
                                                	@if(count($_slots) != 0)
                                                		@foreach($_slots as $slot)
                                                			<option value="{{$slot->slot_id}}">{{$slot->first_name}} {{$slot->middle_name}} {{$slot->last_name}} ({{$slot->slot_no}})</option>
                                                		@endforeach
                                                	@endif
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="">Slot Position</label>
                                                {!! mlm_slot_postion('left') !!}
                                            </div>
                                        @elseif($binary_advance->binary_settings_placement == 1)

                                            <div class="col-md-4">
                                                <label for="">Slot Placement (Binary) <span style="color: red;">Auto Placement</span></label>
                                                <select class="form-control chosen-slot_placement input-sm pull-left" name="slot_placement" data-placeholder="Select Slot Sponsor" disabled="disabled">
                                                    <option value=""></option>
                                                    @if(count($_slots) != 0)
                                                        @foreach($_slots as $slot)
                                                            <option value="{{$slot->slot_id}}">{{$slot->first_name}} {{$slot->middle_name}} {{$slot->last_name}} ({{$slot->slot_no}})</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>

                                            <div class="col-md-4">
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
                </tbody>
            </table>
        </form>
    </div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-def-white btn-custom-white modal_slot_as" data-dismiss="modal">Close</button>
<a onClick="submit_form()" class="panel-buttons btn btn-default pull-right">Submit</a>
<script type="text/javascript">
$(".chosen-select").chosen({no_results_text: "The customer doesn't exist."});
$(".chosen-slot_sponsor").chosen({no_results_text: "The slot doesn't exist."});
$(".chosen-slot_placement").chosen({no_results_text: "The slot doesn't exist."});
$(".chosen-slot_position").chosen({no_results_text: "Slot position does not exist."});

function verify_slot()
{
    
}
function submit_form()
{
    $('#add_slot_form').submit();
}
function submit_done(data)
{
    $(".modal_slot_as").click();
    // $('#global_modal').modal('hide');
    console.log(data);
    if(data.response_status == "success_add_slot")
	{
	    console.log('success');
	    toastr.success('Successfully added slot');
        if (typeof slot_created == 'function')
        {
            slot_created(data.slot_data);
        }
	}
	else if(data.response_status == "warning")
	{
		var erross = data.warning_validator;
		$.each(erross, function(index, value) 
		{
		    toastr.error(value);
		}); 
	}
	else if(data.response_status == "warning_2")
	{
	    toastr.warning(data.error);
	}
	 
}
function get_membership_code(ito)
{
    // ito == this ayoko lang madoble yung this kay ito. haha
    $('#membership_code_div').html('<center><div class="loader-16-gray"></div></center>');
    console.log(ito);
    var customer_id = ito.value;
    console.log(customer_id);
    $('#membership_code_div').load('/member/mlm/slot/get/code/' + customer_id, function() {
        load_chosen();
    });
    // load_chosen();
    // setTimeout(function() { load_chosen(); }, 1000);
}
function load_chosen()
{
    console.log('Chosen Loaded');
    $(".chosen-membership_code_id").chosen({no_results_text: "The membership code does not exist."});
}
</script>
</div>

