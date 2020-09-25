@extends('member.layout')
@section('content')
<form method="post" action="/member/mlm/code/sell/process" class="global-submit">
	<div class="panel panel-default panel-block panel-title-block" id="top">
	    <div class="panel-heading">
	        <div>
	            <i class="fa fa-tags"></i>
	            <h1>
	                <span class="page-title">Sell Codes</span>
	                <small>
	                    Codes are use to create new slot. This is bought by customers in order to become a member.
	                </small>
	            </h1>
	            <button type="submit" class="panel-buttons btn btn-primary pull-right save_membership">Process Purchase</button>
	            <a href="/member/mlm/code" class="panel-buttons btn btn-default pull-right">&laquo; Back</a>
	        </div>
	    </div>
	</div>
	<div class="col-md-12 col-lg-12">
		@if (Session::has('code_error'))
		   <div class='alert alert-danger'>
		   		@foreach(Session::get('code_error') as $code_error)
		   			<b>{{$code_error}}</b>
		   			</br>
		   		@endforeach
		   </div>
		@endif
		<div class="panel panel-default panel-block">
			<div class="list-group">
				<div class="list-group-item clearfix" id="input-fields-horizontal">
					<h4 class="section-title">Customer Information</h4>
					<div class="row">
						<div class="form-group col-md-3">
							<label for="basic-input">Customer</label>
							<div>
								
		                        <select class="form-control chosen-select input-sm pull-left" name="customer_id" data-placeholder="Select a Customer" style="width: calc(100% - 43px);">
		                            <option value=""></option>
									@if(count($_customer) != 0)
										@foreach($_customer as $customer)
											<option value="{{$customer->customer_id}}" e_mail="{{$customer->email}}" first_name="{{$customer->first_name}}" middle_name="{{$customer->middle_name}}" last_name="{{$customer->last_name}}" >{{$customer->first_name}} {{$customer->middle_name}} {{$customer->last_name}}</option>
										@endforeach
									@endif
		                        </select>

								
								<button type="button" style="display: inline-block; margin-top: -3px;" class="btn btn-default popup" size="lg" link="/member/customer/modalcreatecustomer"><i class="fa fa-plus"></i></button>
							</div>
						</div>
						<div class="form-group col-md-4">
							<label for="basic-input">Customer Email</label>
							<input id="basic-input" class="form-control input-sm email" name="membership_code_customer_email">
						</div>
						
						<div class="form-group col-md-5 text-right">
							<label for="basic-input">Total Amount</label>
							<div style="font-size: 32px; margin-top: -10px; color: green;"><span class="total_top">PHP 00.00</span></div>
						</div>
						<div class="col-md-3">
							<label for="basic-input">First Name</label>
							<input id="basic-input" class="form-control input-sm membership_code_customer_f_name" name="membership_code_customer_f_name">
						</div>
						<div class="col-md-3">
							<label for="basic-input">Middle Name</label>
							<input id="basic-input" class="form-control input-sm membership_code_customer_m_name" name="membership_code_customer_m_name">
						</div>
						<div class="col-md-3">
							<label for="basic-input">Last Name</label>
							<input id="basic-input" class="form-control input-sm membership_code_customer_l_name" name="membership_code_customer_l_name">
						</div>
					</div>
				</div>
				<div class="list-group-item clearfix" id="input-fields-horizontal">
					<div class="row">
			            <div class="table-responsive membership_container">
			                <table class="table table-condensed">
			                    <thead style="text-transform: uppercase">
			                        <tr>
			                            <th>Membership & Package</th>
			                            <th class="text-left">Type</th>
			                            <th>Quantity</th>
			                            <th class="text-right">Price</th>
			                            <th class="text-right">Total</th>
			                            <th style="width: 40px;"></th>
			                        </tr>
			                    </thead>
			                    <tbody class="membership_package_body">
			                    	{!! $talbe_body !!}
			                        
			                    </tbody>
			                    <tbody>
			                    	<tr class="hide">
								        <td>
								        	<select class="form-control">
								        		<option>Platinum Set 001</option>
								        		<option>Platinum Set 002</option>
								        		<option>Gold Set 001</option>
								        		<option>Gold Set 002</option>
								        		<option>Gold Set 003</option>
								        	</select>
								        </td>
								        <td class="text-left">
								        	<select style="width: 100px" class="form-control">
								        		<option>PS</option>
								        		<option>FS</option>
								        		<option>CD</option>
								        	</select>
								        </td>
								        <td class="text-left">
								        	<input style="width: 100px" type="text" class="form-control" value="1">
								        </td>
								        <td class="text-right">
								        	PHP 0.00
								        </td>
								        <td class="text-right" style="font-weight: bold;">
								            PHP 0.00
								        </td>
								        <td style="font-size: 20px;">
								        	<a href="">
								        		<i class="fa fa-trash"></i>
								        	</a>
								        </td>
								    </tr>
								    <tr>
								    	<td colspan="3">
								    		<a href="javascript:" class="btn btn-default popup" link="/member/mlm/code/sell/add_line">Add Lines</a>
								    		<a href="javascript:" class="btn btn-default clear_all_lines" onClick="clear_all_lines()">Clear All Lines</a>
								    	</td>
								    	<td class="text-right" style="font-weight: bold;">Total</td>
								    	<td class="text-right" style="font-weight: bold; font-size: 20px; color: green;"><span class="total_bot">PHP 00.00</span></td>
								    </tr>
			                    </tbody>
			                </table>
			            </div>
					</div>
				</div>
				<!-- OTHER SETTINGS -->
				<div class="list-group-item clearfix" id="input-fields-horizontal">
					<div class="row">
						<form role="form" action="/member/mlm/membership/add/save" id="save_membership_form" method="post">
							{!! csrf_field() !!}
							<div class="form-group col-md-3">
								<label for="basic-input">Message displayed on invoice</label>
								<textarea class="form-control" name="membership_code_message_on_invoice"></textarea>
							</div>
							<div class="form-group col-md-3">
								<label for="basic-input">Statement memo</label>
								<textarea class="form-control" name="membership_code_statement_memo"></textarea>
							</div>
							<div class="form-group col-md-3">
								<label for="basic-input">Customer Paid</label>
								<select class="form-control" name="membership_code_paid">
									<option value="1">YES</option>
									<option value="0">NO</option>
								</select>
							</div>
							<div class="form-group col-md-3">
								<label for="basic-input">Product Issued</label>
								<select class="form-control" name="membership_code_product_issued">
									<option value="1">YES</option>
									<option value="0">NO</option>
								</select>
							</div>
							<div class="form-group col-md-3 hide">
								<label for="basic-input">Warehouse (Inventory)</label>
								<select class="form-control hide" name="warehouse_id">
									@foreach($warehouse as $key => $value)
									<option value="{{$value->warehouse_id}}">{{$value->warehouse_name}}</option>
									@endforeach
								</select>
							</div>
						</form>
					</div>
				</div>
				
			</div>
		</div>
	</div>
</form>
<div class="clear"></div>
@endsection

@section('script')
<script type="text/javascript">
compute();
chosen_select_on_change();
chosen_select_on_change_event();
on_change_quantity();
membership_package_options();
membership_package_onchange();
membership_type_onchange();
var past_value = null;

function compute()
{
	// /member/mlm/code/sell/compute
	$('.total_top').load('/member/mlm/code/sell/compute');
	$( ".total_bot" ).load( "/member/mlm/code/sell/compute", function() 
	{
	  check_membership_container();
	});
}
function load_session() 
{
    $('.membership_package_body').html('<tr><td colspan="6"><center><div style="margin: 100px auto;" class="loader-16-gray"></div></center></td></tr>');
    $('.membership_package_body').load('/member/mlm/code/sell/add_line/view', function() 
	{
		compute();
		membership_package_options();
	});
}
function clear_all_lines()
{
	$('.clear').load('/member/mlm/code/sell/clear_line_all', function() 
	{
		load_session();
		toastr.success('All lines cleared.');
	});
}
function remove_line(id) {
	// body...
	$('.clear').load('/member/mlm/code/sell/clear_line/' + id, function() 
	{
		load_session();
		toastr.success('Line Removed');
	});
}
function submit_done(data) 
{
    if(data.response_status == "warning")
    {
        var warning_vali = data.warning_validator;
        warning_vali.forEach(function(entry) 
        {
		    toastr.warning(entry);
		});
    }
    else if(data.response_status == "success")
    {
    	if (data.invoice_id  == null) 
    	{
		  // your code here
		  	
	        // $('#global_modal').modal('toggle');
	        $('#add-new-line-warning').html(" ");
	        toastr.success('Line Successfully Added!');
	        load_session();
	        $('.close').click();

		  
		}
		else
		{
			toastr.success('Purchase complete');
			window.location = "/member/mlm/code/receipt?invoice=" + data.invoice_id;
		}
    }
}

/* ERWIN */
function on_change_quantity()
{
	$(".membership_container").on("input",".quantity_container",function()
	{
		
		var quantity           = $(this);
		var membership_package = quantity.parent().parent().find(".membership_package_id");
		var membership_type    = quantity.parent().parent().find(".membership_type");
		var price              = quantity.parent().parent().find(".price_container");
		var subtotal           = quantity.parent().parent().find(".subtotal_container");
		var total_top          = $(".total_top");
		var total_bot          = $(".total_bot");
		
		var membership_package_value = membership_package.val();
		var quantiy_value			 = quantity.val();
		var membership_type_value    = membership_type.val();
		var price_value	             = $(':selected', membership_package).attr("mem_price");
		var total_value            	 = price_value * quantiy_value;
		
		if(membership_type.val() == "CD")
		{
			price_value = 0;
			total_value = 0;
		}
		
		if(quantity.val() > 0)
		{
	       $.ajax({
           type: "POST",
           url: "/member/mlm/code/sell/add_line/submit",
           data: {_token:$("input[name='_token']").val(),membership_edit:"1",membership_package:membership_package_value,quantiy:quantiy_value,membership_type:membership_type_value}, // serializes the form's elements.
           success: function(returned_data)
           {
           		returned_data = jQuery.parseJSON(returned_data);
        		subtotal.text(returned_data["subtotal"]);
        		total_bot.text(returned_data["total"]);
        		total_top.text(returned_data["total"]);
           }});
		}
	});
}
function membership_package_onchange()
{
	$(".membership_container").on("focus",".membership_package_id",function()
	{
		past_value = $(this).val();
		console.log("value = ",past_value);
    });
    
	$(".membership_container").on("change",".membership_package_id",function()
	{
		var membership_package = $(this);
		var quantity           = membership_package.parent().parent().find(".quantity_container");
		var membership_type    = membership_package.parent().parent().find(".membership_type");
		var price              = membership_package.parent().parent().find(".price_container");
		var subtotal           = membership_package.parent().parent().find(".subtotal_container");
		var total_top          = $(".total_top");
		var total_bot          = $(".total_bot");
		var membership_package_value = membership_package.val();
		var quantiy_value			 = quantity.val();
		var membership_type_value    = membership_type.val();
		var price_value	             = $(':selected', membership_package).attr("mem_price");
		var total_value            	 = price_value * quantiy_value;
		if(membership_type.val() == "CD")
		{
			price_value = 0;
			total_value = 0;
		}
		membership_package_options();
		if(quantity.val() > 0)
		{
	       $.ajax({
           type: "POST",
           url: "/member/mlm/code/sell/add_line/submit",
           data: {_token:$("input[name='_token']").val(),removed:past_value,membership_edit:"1",membership_package:membership_package_value,quantiy:quantiy_value,membership_type:membership_type_value}, // serializes the form's elements.
           success: function(returned_data)
           {
   				past_value		   = membership_package.val();
           		returned_data = jQuery.parseJSON(returned_data);
        		subtotal.text(returned_data["subtotal"]);
        		total_bot.text(returned_data["total"]);
        		total_top.text(returned_data["total"]);
        		price.text(returned_data["price"]);
				load_session();
           }});
		}
		
	});
}
function membership_type_onchange()
{
	$(".membership_container").on("change",".membership_type",function()
	{
		var membership_type    = $(this);
		var membership_package = membership_type.parent().parent().find(".membership_package_id");
		var quantity           = membership_type.parent().parent().find(".quantity_container");
		var price              = membership_type.parent().parent().find(".price_container");
		var subtotal           = membership_type.parent().parent().find(".subtotal_container");
		var total_top          = $(".total_top");
		var total_bot          = $(".total_bot");
		var membership_package_value = membership_package.val();
		var quantiy_value			 = quantity.val();
		var membership_type_value    = membership_type.val();
		var price_value	             = $(':selected', membership_package).attr("mem_price");
		var total_value            	 = price_value * quantiy_value;
		
		console.log(membership_type.val());
		if(quantity.val() > 0)
		{
	       $.ajax({
           type: "POST",
           url: "/member/mlm/code/sell/add_line/submit",
           data: {_token:$("input[name='_token']").val(),membership_edit:"1",membership_package:membership_package_value,quantiy:quantiy_value,membership_type:membership_type_value}, // serializes the form's elements.
           success: function(returned_data)
           {
           		returned_data = jQuery.parseJSON(returned_data);
        		subtotal.text(returned_data["subtotal"]);
        		total_bot.text(returned_data["total"]);
        		total_top.text(returned_data["total"]);
        		price.text(returned_data["price"]);
        		console.log(returned_data);
           }});
		}
	});
	
}
function membership_package_options()
{
	$('select[name=membership_package\\[\\]]').each(function(i,obj) 
	{
		var select_package = $(this).val();
		$(".membership_package_id option[value='"+select_package+"']").not(":eq("+i+")").wrap( "<span>" );	
	});
}
function chosen_select_on_change()
{
	$(".chosen-select").chosen().change(function()
	{
		chosen_select_on_change_event();
	});	
}
function chosen_select_on_change_event()
{
	$(".chosen-select").chosen().val();
	var e_mail = get_chosen_attr("e_mail");
	var first_name = get_chosen_attr("first_name");
	var middle_name = get_chosen_attr("middle_name");
	var last_name = get_chosen_attr("last_name");
	$(".email").val(e_mail);
	$(".membership_code_customer_f_name").val(first_name);
	$(".membership_code_customer_m_name").val(middle_name);
	$(".membership_code_customer_l_name").val(last_name);
}
function get_chosen_attr($attribute)
{
	var options = $( ".chosen-select option:selected" );
	
	for (var i = 0; i < options.length; i++)
	{
	    var property = $(options[i]).attr($attribute);
	    return property;
	}
}
function check_membership_container()
{
	if ($(".membership_package_id")[0])
	{
    	$(".save_membership").prop("disabled",false);
	} 
	else 
	{
	    $(".save_membership").prop("disabled",true);
	}
}

$(".chosen-select").chosen({no_results_text: "The customer doesn't exist."});
</script>
@endsection