
@extends('member.layout')
@section('content')
    <div class="panel panel-default panel-block panel-title-block" id="top">
	    <div class="panel-heading">
	        <div>
	            <i class="fa fa-tags"></i>
	            <h1>
	                <span class="page-title">Sell Product/s</span>
	                <small>
	                    
	                </small>
	            </h1>
	            <button type="submit" class="panel-buttons btn btn-primary pull-right save_item">Process Purchase</button>
	            <a href="/member/mlm/product_code" class="panel-buttons btn btn-default pull-right">&laquo; Back</a>
	        </div>
	    </div>
	</div>
<form method="post" action="/member/mlm/product_code/sell/process" class="global-submit">
	{!! csrf_field() !!}
    <div class="panel panel-default panel-block panel-title-block panel-gray">
       <!--  <ul class="nav nav-tabs">
            <li class="active cursor-pointer"><a class="cursor-pointer" data-toggle="tab" href="#pending-codes"><i class="fa fa-star"></i> Invoice Information</a></li>
            <li class="cursor-pointer"><a class="cursor-pointer" data-toggle="tab" href="#used-codes"><i class="fa fa-list"></i> Activities</a></li>
        </ul> -->
        <div class="tab-content">
            <div class="row">
                <div class="col-md-12" style="padding: 30px;">
                    <!-- START CONTENT -->
                    <div style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 10px;">
                        <div class="row clearfix">
                        	<div class="col-md-12 customer_data">
							</div>
                            <div class="col-sm-4">
                                <label>Slot</label>
								<input type="text" class="form-control membership_code" name="membership_code" onChange="bar_code_membership_code(this)">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row clearfix draggable-container">
                        <div class="table-responsive">
                            <div class="col-sm-12">
                                <table class="digima-table">
                                    <thead >
                                        <tr>
                                            <th style="width: 10px;" ></th>
                                            <th style="width: 15px;" class="text-right">#</th>
                                            <th style="width: 180px;">Product</th>
                                            <th style="width: 120px;">Price</th>
                                            <th style="width: 100px;">Membership Discount/Piece</th>
                                            <th style="width: 70px;">Quanity</th>
                                            <th style="width: 100px;">Discount Total</th>
                                            <th style="width: 100px;">Subtotal</th>
                                            <th width="10"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="draggable tbody-item">                                    
                                        <tr class="tr-draggable">
                                            <td class="text-center cursor-move move"><i class="fa fa-th-large colo-mid-dark-gray"></i></td>

                                            <td class="invoice-number-td text-right">1</td>
                                            <td>
                                                <select class="drop-down-item">
												    @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
												</select>
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                        </tr>
                                        <tr class="tr-draggable">
                                            <td class="text-center cursor-move move"><i class="fa fa-th-large colo-mid-dark-gray"></i></td>

                                            <td class="invoice-number-td text-right">1</td>
                                            <td>
                                                <select class="drop-down-item">
												    @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
												</select>
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row clearfix">
                        <div class="form-group col-md-3">
								<label for="basic-input">Message displayed on invoice</label>
								<textarea class="form-control" name="item_code_message_on_invoice"></textarea>
							</div>
							<div class="form-group col-md-3">
								<label for="basic-input">Statement memo</label>
								<textarea class="form-control" name="item_code_statement_memo"></textarea>
							</div>
							<div class="form-group col-md-6">
								<label for="basic-input">Customer Paid</label>
								<select class="form-control" name="item_code_paid">
									<option value="1">YES</option>
									<option value="0">NO</option>
								</select>
								<label for="basic-input">Product Issued</label>
								<select class="form-control" name="item_code_product_issued">
									<option value="1">YES</option>
									<option value="0">NO</option>
								</select>
								<label for="basic-input">Use Item Code</label>
								<select class="form-control" name="use_item_code_auto">	
									<option value="1">YES</option>
									<option value="0">NO</option>
								</select>
								<label for="basic-input" class="hide">Warehouse (Inventory)</label>
								<select class="form-control hide" name="warehouse_id">
									@foreach($warehouse as $key => $value)
									<option value="{{$value->warehouse_id}}">{{$value->warehouse_name}}</option>
									@endforeach
								</select>

								<div class="row">
									<br>
								  <div class="col-lg-12">
								    <div class="input-group">
								      <span class="input-group-addon">
								        <input type="checkbox" name="use_gc" aria-label="Checkbox for following text input">
								      </span>
								      <span class="input-group-addon" >USE GC</span>
								      <input type="text" class="form-control" aria-label="Text input with checkbox" name="gc_code">
								    </div>
								  </div>
								</div>
								<div class="row">
									<br>
								  <div class="col-lg-12">
								  <label>USE WALLET</label>
								   <input type="checkbox"  name="use_wallet">
								  </div>
								</div>
							</div>
                    </div>
                    
                    <!-- END CONTENT -->
                </div>
            </div>
        </div>
    </div>
</form>
<div class="div-script">
    <table class="div-item-row-script hide">
        <tr class="tr-draggable">
            <td class="text-center cursor-move move"><i class="fa fa-th-large colo-mid-dark-gray"></i></td>

            <td class="invoice-number-td text-right">1</td>
            <td>
                <select class="drop-down-item">
				    @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
				</select>
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
        </tr>
    </table>
</div>
@endsection

@section('script')
<script type="text/javascript">
	$(".drop-down-item").globalDropList(
	{
	    link: '/member/item/add',
	    link_size: 'lg',
	    width: '100%',
	    maxHeight: "309px",
	    placeholder: 'Item'
	});
	$(".membership_code").focus();
	function bar_code_membership_code(ito)
	{
		var membership_code = ito.value;
		console.log(membership_code);
		$('.customer_data').html('<center><div class="loader-16-gray"></div></center>');
		$('.customer_data').load('/member/customer/product_repurchase/get_slot_v_membership_code/' + membership_code, function(){
			change_slot_class();
		});
		$(ito).val('');
	}
	function change_slot_class()
	{
		var slot_id = $('.slot_id').val();
		var discount_card_log_id = $('.discount_card_log_id').val();
		if(slot_id == undefined || slot_id == null)
		{
			slot_id = 0;
		}
		if(discount_card_log_id == undefined || discount_card_log_id == null)
		{
			discount_card_log_id = 0;
		}
		$('.load_fix_session').load('/member/mlm/product/discount/fix/session/' + slot_id, function(data){
			// load_session();
		});
	}
	$(document).on("keydown", ".membership_code", function(e)
	{
		
		if(e.which == 13)
		{
			e.preventDefault();
			bar_code_membership_code(this);
		}
	});
</script>
<script type="text/javascript" src="/assets/member/js/textExpand.js"></script>
<script type="text/javascript" src="/assets/member/js/draggable_row.js"></script>
<script type="text/javascript" src="/assets/member/mlm/product_code.js"></script>
@endsection