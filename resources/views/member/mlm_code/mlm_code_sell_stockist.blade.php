@extends('member.layout')
@section('content')
<form method="post" action="/member/mlm/code/sell/process/stockist" class="global-submit">
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
								
		                        <select class="form-control chosen-select customer_select input-sm pull-left" name="customer_id" onChange="change_input(this)" data-placeholder="Select a Customer" style="width: calc(100% - 43px);">
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
			                            <th>Quantity</th>
			                            <th class="text-right">Price</th>
			                        </tr>
			                    </thead>
			                    <tbody class="membership_package_body">
			                    <tr>
			                    	<td>
			                    		<select class="form-control membership_package" name="membership_package[]" onChange="change_membership(this)">
			                    			@foreach($membership_package as $key => $value)
												<option value="{{$value->membership_package_id}}" price="{{currency('PHP', $value->membership_price)}}">{{$value->membership_package_name}}</option>
			                    			@endforeach
			                    		</select>
			                    	</td>
			                    	<td>
			                    		<input type="number" class="form-control" value="1" name="quantity[]" readonly>
			                    		<input type="hidden" value="PS" name="membership_type[]">
			                    	</td>
			                    	<td class="text-right"><span class="price_b"></span></td>
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
change_input(1);
function change_input(ito)
{
  var e_mail = $('.customer_select').find('option:selected').attr('e_mail');
  var first_name = $('.customer_select').find('option:selected').attr('first_name');
  var middle_name = $('.customer_select').find('option:selected').attr('middle_name');
  var last_name = $('.customer_select').find('option:selected').attr('last_name');


  $('.email').val(e_mail);
  $('.membership_code_customer_f_name').val(first_name);
  $('.membership_code_customer_m_name').val(middle_name);
  $('.membership_code_customer_l_name').val(last_name);
}
change_membership(1);
function change_membership(ito)
{
	var price = $('.membership_package').find('option:selected').attr('price');
	$('.price_b').html(price);
	$('.total_top').html(price);
}
</script>
@endsection