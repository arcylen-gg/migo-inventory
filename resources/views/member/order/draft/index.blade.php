@extends('member.layout')

@section('css')
<link rel="stylesheet" type="text/css" href="/assets/member/css/order.css">
@endsection
@section('content')

<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-shopping-cart"></i>
            <h1>
                <span class="page-title">Order</span>
                <small>
                Add order to your website
                </small>
            </h1>
            <a href="/member/order/new_order" class="panel-buttons btn btn-primary pull-right">Create Order</a>
            <a href="#" class="panel-buttons btn btn-default pull-right btn-export btn-custom">Export</a>
        </div>
    </div>
</div>
<div class="alert alert-info">
	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
	<h5><i class="fa fa-flag" aria-hidden="true"></i>&nbsp;test</h5>
	<span><a href="">Receive an alert</a> on your desktop as soon as a new order comes in.</span>
</div>
<div class="panel panel-default panel-block panel-title-block panel-gray ">

	<ul class="nav nav-tabs">
	  <li class="active cursor-pointer"><a class="cursor-pointer" data-toggle="tab" onClick="filter_function('draft', '0','0')">All Draft</a></li>
	  <li ><a class="cursor-pointer" data-toggle="tab"  onClick="filter_function('draft', '0','Unfulfilled')">Unfulfilled</a></li>
	  <li ><a class="cursor-pointer" data-toggle="tab"  onClick="filter_function('draft', 'Unpaid','0')">Unpaid</a></li>
	  <li ><a class="cursor-pointer" data-toggle="tab"  onClick="filter_function('draft', 'Refunded','0')">Refunded</a></li>
	  <li ><a class="cursor-pointer" data-toggle="tab"  onClick="filter_function('draft', 'Partially refunded','0')">Partially Refunded</a></li> 
	</ul>
	<div class="tab-content">
	  <div id="all-orders" class="tab-pane fade in active">
	    <div class="form-group">
	    	<div class="input-group">
	    		<div class="input-group-addon custom-addon">

	    			<a href="#" class="btn btn-default btn-custom" id="popover" data-placement="bottom">Filter Orders&nbsp;<span class="caret"></span></a>
					<div id="popover-content" class="hide padding10">
					  Show all orders where:
					  <div class="form-horizontal padding10">
					  	<div class="form-group">
					  		<select class="form-control" id="select-filter">
					  			<option value="reset">Select a filter...</option>
					  			<option value="status_status">Status</option>
								<option value="payment_status">Payment status</option>
								<option value="fulfillment_status">Fulfillment status</option>
								<option value="tagged_with">Tagged with</option>
								<option value="chargeback_status">Chargeback status</option>
								<option value="risk_level">Risk level</option>
								<option value="date">Date</option>
								<option value="customer">Customer</option>
								<option value="creadit_card">Credit card</option>
					  		</select>
					  	</div>
					  	<div class="filter-tag-div form-group">
					  		
					  	</div>
					  	<div class="form-group filter-btn-div">
					  		<button class="btn btn-default btn-def-white">Add filter</button>
					  	</div>
					  </div>
					</div>
	    		</div>
	    		<div class="hidden" id="filter-order">
	    			<div class="title">Show all orders where:</div>
	    			<div class="form-group">
	    				<select class="form-control">
	    					<option>Select Filter...</option>
	    					<option>Status</option>
	    					<option>Payment Status</option>
	    					<option>Fulfillment Status</option>
	    					<option>Tagged with</option>
	    					<option>Chargeback Status</option>
	    					<option>Risk Level</option>
	    					<option>Date</option>
	    					<option>Customer</option>
	    					<option>Credit Card</option>
	    				</select>
	    			</div>
	    		</div>
	    		<i class="fa fa-search fa-search-custom" aria-hidden="true"></i>
	    		<input type="search" class="form-control text-search" placeholder="Start typing to search for orders..." name="">
	    	</div>
	    </div>
	    <div class="form-group order-tags"></div>
	    <div class="table-responsive" id="orders-data-get">{!! $orders !!}</div>
	    
	  </div>
	 
	</div>
</div>
@endsection
@section('script')
<script type="text/javascript">
function filter_function(statuss, payment_stat, fulfillment_status) 
{
	$('#orders-data-get').html('<div class="col-md-12"><center><img src="/assets/member/images/spinners/22.gif"/></center></div><br>');
	
	$.ajax({
		url 	: 	'/member/ecommerce/order/filter/'+ statuss + '/' + payment_stat + '/' + fulfillment_status,
		type 	: 	'GET',
		success : 	function(result){
			$('#orders-data-get').html(result);
		},
		error 	: 	function(err){
			toastr.error("Error, something went wrong.");
		}
	});
}
</script>
@endsection