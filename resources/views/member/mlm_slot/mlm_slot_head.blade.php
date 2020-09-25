@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Create New Customer Slot</span>
                <small>
                    Setup slot head for your MLM Plan.
                </small>
            </h1>
            <a onClick="submit_form()" class="panel-buttons btn btn-default pull-right">Submit</a>
            <a href="/member/mlm/slot" class="panel-buttons btn btn-default pull-right">< Back</a>
        </div>
    </div>
</div>
<div class="table-responsive">
    <form class="global-submit" action="/member/mlm/slot/get/code/form/submit" method="post" id="company_head_form">
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
                        <div class="col-md-4 hide">
                            <label for="">Sponsor <span style="color: red;">*No Sponsor(Company Head)*</span></label>
                            <input type="number" class="form-control" name="slot_sponsor" value="" disabled="disabled">
                        </div>
                        @if($binary_settings)
                        <div class="col-md-4 hide">
                            <label for="">Slot Position (Binary) <span style="color: red;">*No Slot Position(Company Head)*</span></label>
                            <input type="number" class="form-control" name="slot_position" value="" disabled="disabled">
                        </div>
                        <div class="col-md-4 hide">
                            <label for="">Placement <span style="color: red;">*No Placement(Company Head)*</span></label>
                            {!! mlm_slot_postion_disabled('left') !!}
                        </div>
                        @else
                        <div class="col-md-4 hide">
                            <label for="">Slot Position (Binary) <span style="color: red;">*No Slot Position(Company Head)*</span></label>
                            <input type="number" class="form-control" name="slot_position" value="" disabled="disabled">
                        </div>
                        <div class="col-md-4 hide">
                            <label for="">Placement <span style="color: red;">*No Placement(Company Head)*</span></label>
                            {!! mlm_slot_postion_disabled('left') !!}
                        </div>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</div>
@endsection
@section('script')
<script type="text/javascript">
function submit_form()
{
    $('#company_head_form').submit();
}
function submit_done(data)
    {
    	if(data.response_status == "warning")
    	{
    		var erross = data.warning_validator;
    		$.each(erross, function(index, value) 
    		{
    		    toastr.error(value);
    		}); 
    	}
    	else if(data.response_status == "success")
    	{
    	    console.log('success');
    	    toastr.success('Successfully added company head slot');
    	    location.reload();
    	}
    }
    $(".chosen-select").chosen({no_results_text: "The customer doesn't exist."});
    function get_membership_code(ito)
    {
        // ito == this. ayoko lang madoble yung this kay ito. haha
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
@endsection
