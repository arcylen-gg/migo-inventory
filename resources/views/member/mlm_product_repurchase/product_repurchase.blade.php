@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Product Repurchase</span>
                <small>
                    Use product code.
                </small>
            </h1>
            <a onClick="submit_form()" class="panel-buttons btn btn-default pull-right">Submit</a>
            <a href="/member/mlm/slot" class="panel-buttons btn btn-default pull-right">< Back</a>
        </div>
    </div>
</div>
<div class="table-responsive panel">
    <form class="global-submit" action="/member/customer/product_repurchase/submit" method="post" id="product_repurchase_form">
        {!! csrf_field() !!}
        <table class="table table-condensed">
            <tbody>
                <tr>
                    <td>
                        <div class="col-md-4">
                           <label for="slot_no">Slot Owner(Customer)</label>
                           <select class="form-control chosen-select input-sm pull-left" name="slot_owner" data-placeholder="Select a Customer" style="width: calc(100% - 43px);" onChange="get_product_code(this); get_slot(this);">
                                <option value=""></option>
    							@if(count($_customer) != 0)
    								@foreach($_customer as $customer)
    									<option value="{{$customer->customer_id}}" e_mail="{{$customer->email}}">{{$customer->first_name}} {{$customer->middle_name}} {{$customer->last_name}}</option>
    								@endforeach
    							@endif
                            </select>
                            <button type="button" style="display: inline-block; margin-top: -3px;" class="btn btn-default popup" size="lg" link="/member/customer/modalcreatecustomer"><i class="fa fa-plus"></i></button>
                        </div>
                        <div class="col-md-4" id="slot_div">
                            
                        </div>
                        <div class="col-md-4" id="product_code_div">
                            
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</div>
<div class="table-responsive panel">
    <div class="code_info">
        
    </div>
</div>
@endsection
@section('script')
<script type="text/javascript">
$(".chosen-select").chosen({no_results_text: "The customer doesn't exist."});

function verify_slot()
{
    
}
function submit_form()
{
    $('#product_repurchase_form').submit();
}
function submit_done(data)
{
    console.log(data);
    if(data.response_status == "success")
	{
	    console.log('success');
	    toastr.success('Successfully used the product code');
	    
	    window.location.href = "/member/customer/product_repurchase/";
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
function get_product_code(ito)
{
    // ito == this ayoko lang madoble yung this kay ito. haha
    $('#product_code_div').html('<center><div class="loader-16-gray"></div></center>');
    console.log(ito);
    var customer_id = ito.value;
    console.log(customer_id);
    $('#product_code_div').load('/member/customer/product_repurchase/get_product_code/' + customer_id, function() {
        load_chosen();
    });
    // load_chosen();
    // setTimeout(function() { load_chosen(); }, 1000);
}
function get_slot(ito)
{
    $('#slot_div').html('<center><div class="loader-16-gray"></div></center>');
    console.log(ito);
    var customer_id = ito.value;
    console.log(customer_id);
    $('#slot_div').load('/member/customer/product_repurchase/get_slot/' + customer_id, function() {
        load_chosen_slot();
    });
}
function load_chosen()
{
    console.log('Chosen Loaded');
    $(".chosen-product_code_id").chosen({no_results_text: "The product code does not exist."});
}
function load_chosen_slot()
{
    console.log('Chosen Loaded');
    $(".chosen-slot_id").chosen({no_results_text: "The slot does not exist."});
}
function change_product_code_get_info(ito)
{
    console.log(ito);
    console.log(ito.value);
    if(ito.value != null)
    {
        $('.code_info').html('<center><div class="loader-16-gray"></div></center>');
        $('.code_info').load('/member/customer/product_repurchase/get_product_code/info/' +ito.value );
    }
    
}
</script>
@endsection
