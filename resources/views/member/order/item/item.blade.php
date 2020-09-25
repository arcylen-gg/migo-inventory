@extends('member.layout')

@section('css')
<link rel="stylesheet" type="text/css" href="/assets/member/css/create_order.css">
@endsection

@section('content')
<input type="hidden" name="" id="_token" value="{{csrf_token()}}">
<div class="form-horizontal">
	<div class="panel panel-default panel-block panel-title-block" id="top">
        <div class="panel-heading">
            <div>
                <i class="fa fa-shopping-cart lighter-gray"></i>
                <h1 class="f30">
                    <a class="lighter-gray" href="/member/order">Order</a><span >/#{{$item_id}}</span>
                </h1>
            </div>

        </div>
    </div>
    <div class="form-group">
        <div class="col-md-8">
            <div class="well well-white border-none">
            	<div class="form-group">
            	 @if($_item['total'] != 0)
                      <table class="table table-condensed">
                        
                        @foreach($_item['item'] as $key =>$item)
                          <tr id="trorder_{{$item['tbl_order_item_id']}}">
                            <td width="10%" class="valign-center">
                              <img  class="img50x50" src="{{$item['image_path']}}">
                            </td>
                            <td width="25%" class="valign-center">
                              <a href="/member/product/edit/variant/{{$item['variant_product_id']}}?variant_id={{$item['variant_id']}}">{{$item['product_name']}}</a>
                              <p class="light-brown">{{$item['variant_name']}}</p>
                              <p class="light-brown">SKU&nbsp;:&nbsp;{{$item['variant_sku']}}</p>
                            </td>
                            <td width="10%" class="text-right">
                             <p>{{$item['less_discount']}}</p>
                            </td>
                            <td width="5%" class="valign-center">
                              <p class="light-brown">x</p>
                            </td>
                            <td width="15%" class="valign-center">
                              <p>{{$item['quantity']}}</p>
                            </td>
                            <td width="10%" class="valign-center text-right">
                              <p class="light-brown item-amount-{{$item['tbl_order_item_id']}}">{{$item['total_amount']}}</p>

                            </td>
                          
                          </tr>
                          
                        @endforeach
                      </table>
                 @endif
                 </div>
                 <div class="form-group">
                    <div class="col-md-6">
                        <label>Notes</label>
                        <textarea class="textarea-notes form-control" rows="auto" placeholder="Add a note...">{!!$notes!!}</textarea><br>
                        <button class="btn btn-primary btn-save-note" disabled type="button" data-content="{{$item_id}}">Save</button>
                    </div>
                    <div class="col-md-6">
                       <table class="table-custom">
                           <tr>
                               <td class="text-right">
                                   <span class="">Discount{!!$discount_reason!!}</span>
                               </td>
                               <td class="text-right">
                                    <span class="main-discount">{{$discountamount}}</span>
                               </td>
                           </tr>
                           <tr>
                               <td class="text-right">
                                   <span>Subtotal</span>
                               </td>
                               <td class="text-right">
                                   <span class="subtotal">{{$total_order}}</span>
                               </td>
                           </tr>
                           <tr>
                               <td class="text-right">
                                   <span>Shipping{!!$shipping_name!!}</span>
                               </td>
                               <td class="text-right">
                                   <span class="shipping_amount">{{$shippings}}</span>
                               </td>
                           </tr>
                           <tr>
                               <td class="text-right">
                               	<span class="light-brown">{{$IsTaxExempStr}}</span>
                               </td>
                               <td class="text-right">
                                   <span class="tax-content">{{$tax}}</span>
                               </td>
                           </tr>
                           <tr>
                               <td class="text-right">
                                   <b>Total</b>
                               </td>
                               <td class="text-right">
                                   <span id="total_order_amount">{{$total_ordering}}</span>
                               </td>
                           </tr>
                           @if($payment_stat == "Partially refunded")
                          <tr>
                             <td class="text-right">
                               Refunded Amount
                             </td>
                              <td class="text-right">
                                <span id="total_refund">{{$refunded_ammount}}</span>
                              </td>
                            </tr>
                            @elseif($payment_stat == "Refunded")
                            <tr>
                               <td class="text-right">
                                 Refunded Amount
                               </td>
                                <td class="text-right">
                                  <span id="total_refund">{{$refunded_ammount}}</span>
                                </td>
                            </tr>
                            @endif
                       </table>
                    </div>
                </div>
                <hr>
                <div class="form-group">
                	<div class="col-md-12">
                	  @if($payment_stat == "Pending")
                	  <div class="col-md-6">
                	    <span><i class="fa fa-credit-card-alt" aria-hidden="true"></i> </span><span>PAYMENT PENDING</span>
                	  </div>
                		<div class="col-md-6" >
                		  <button class="btn btn-primary pull-right " data-content="{{$item_id}}" data-toggle="modal" data-target="#ModalMarkAsPaid">Marks as paid</button>
                		  <button class="btn btn-def-white pull-right" disabled>Restock</button>
                		</div>
                	  @else
                	  <span class=""><span class="color-success f18"><i class="fa fa-check" aria-hidden="true"></i></span>&nbsp;<span class="f16"><b>Payment of {{$total_ordering}} was accepted.</b></span></span>
                		
                		<button class="btn btn-def-white margin-left-10 pull-right btn-refund-modal" data-content="{{$item_id}}" data-toggle="modal" data-target="#ModalRefund">Refund</button>
                	  
                	  @endif
                	  <button class="btn btn-custom-primary margin-left-10 pull-right">Delivered</button>
                	  <button class="btn btn-custom-primary margin-left-10 pull-right">Shipped</button>
                	  <button class="btn btn-custom-primary margin-left-10 pull-right" disabled><i class="fa fa-check" aria-hidden="true"></i>Processed</button>
                	</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="well well-white border-none cutomer-result">
            	{!!$customer!!}
            </div>
        </div>
    </div>
</div>
<div id="ShippingAddModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit shipping address</h4>
      </div>
      <div class="modal-body">
        <div class="form-horizontal">
          <div class="form-group">
            <div class="col-md-6">
              <span>First name</span>
              <input type="text" class="form-control edit-fname" name="">
            </div>
            <div class="col-md-6">
              <span>Last name</span>
              <input type="text" class="form-control edit-lname" name="">
            </div>
          </div>

          <div class="form-group">
            <div class="col-md-6">
              <span>Company name</span>
              <input type="text" class="form-control edit-company" name="">
            </div>
            <div class="col-md-6">
              <span>Phone number</span>
              <input type="text" class="form-control edit-phone" name="">
            </div>
          </div>

          <div class="form-group">
            <div class="col-md-6">
              <span>Address</span>
              <input type="text" class="form-control edit-address" name="">
            </div>
            <div class="col-md-6">
              <span>Address con't</span>
              <input type="text" class="form-control edit-addresscont" name="">
            </div>
          </div>

          <div class="form-group">
            <div class="col-md-6">
              <span>City</span>
              <input type="text" class="form-control edit-city" name="">
            </div>
            <div class="col-md-6">
              <span>Country</span>
              <select class="form-control" id="edit_country" name="edit_country">
                @foreach($_country as $country)
                  <option value="{{$country->country_id}}">{{$country->country_name}}</option>
                @endforeach
                
              </select>
            </div>
          </div>

          <div class="form-group">
            <div class="col-md-6">
              <span>Province</span>
              <input type="text" class="form-control edit-province" name="">
            </div>
            <div class="col-md-6">
              <span>Postal/zip code</span>
              <input type="text" class="form-control edit-zip" name="">
            </div>
          </div>

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-def-white" data-dismiss="modal">Close</button>
        <button class="btn btn-primary update-shipping" type="button">Apply changes</button>
      </div>
    </div>

  </div>
</div>
<div id="ModalEmailUpdate" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit email</h4>
      </div>
      <div class="modal-body">
        <div class="form-horizontal">
          <div class="form-group">
            <div class="col-md-12">
              <span>Notification emails will be sent to this address.</span>
            </div>
          </div>
          <div class="form-group">
            <div class="col-md-12">
              <span>Email</span>
              <input type="text" class="form-control edit-email" name="">
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-def-white" data-dismiss="modal">Cancel</button>
        <button class="btn btn-primary btn-update-email" type="button">Apply</button>
      </div>
    </div>

  </div>
</div>
<div id="ModalRefund" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Refund Payments</h4>
      </div>
      <div class="modal-body modal-refund">
	      <div class="form-horizontal">
	      	<div class="refund-body"></div>
		      <hr>
		      <div class="form-group">
		      	<table class="pull-right tbl-custom">
              <tbody>
  		      		<tr>
  		      			<td class="text-right light-brown">Subtotal</td>
  		      			<td class="text-right bold">₱&nbsp;<span id="subtotal_span">0.00</span></td>
  		      		</tr>
              </tbody>
              <tbody class="discount-table">
                <tr>
                  <td class="text-right light-brown">Discount</td>
                  <td class="text-right bold">-&nbsp;₱&nbsp;<span id="discount_span">0.00</span></td>
                </tr>
              </tbody>
              <tbody class="tax-table">
                <tr>
                  <td class="text-right light-brown">Tax</td>
                  <td class="text-right bold">₱&nbsp;<span id="tax_span">0.00</span></td>
                </tr>
              </tbody>
              <tbody>
  		      		<tr>
  		      			<td class="text-right light-brown">Total available to refund</td>
  		      			<td class="text-right"><span>₱&nbsp;<span class="available-refund"></span></span></td>
  		      		</tr>
              </tbody>
		      	</table>
		      </div>
		      <hr>
		      <div class="form-group">
		      	<div class="col-md-8">
		      		<span class="pull-left"><i class="fa fa-credit-card"></i>&nbsp;<span class="light-brownn">Refund with: Manual </span></span>
		      	</div>
		      	<div class="col-md-4">
					<span class="peso-back">₱</span>
					<input type="number" step="any" class="form-control indent-10 text-right" id="manual_refund">	
		      	</div>
		      </div>
		      <div class="form-group">
		      	<div class="col-md-12">
		      		<div class="checkbox pull-right">
		      			<label><input type="checkbox" id="restockChck" name="restockChck" checked value="1">&nbsp;<span id="restock">Restock 0 item</span></label>
		      		</div>
		      	</div>
		      </div>
		      <hr>
		      <div class="form-group">
		      	<div class="col-md-12">
		      		<span>Reason for refund (optional) </span>
		      		<input type="text" class="form-control reason-refund" name="">
		      	</div>
		      </div>
	      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-def-white" data-dismiss="modal">Cancel</button>
        <button class="btn btn-primary btn-refund" type="button" disabled data-content="{{$item_id}}">Refund ₱&nbsp;<span class="span-refund">0.00</span></button>
      </div>
    </div>

  </div>
</div>
<div id="ModalMarkAsPaid" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Mark as paid</h4>
      </div>
      <div class="modal-body modal-markpaid">
        <p>Processed by <b>Manual</b>
        <br />
        <br />
        If you received payment manually, mark this order as paid.
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-def-white" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary btn-mark-paid" value="{{$tbl_order_id}}">Mark as paid</button>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
<script type="text/javascript" src="/assets/member/js/item.js"></script>
@endsection