@extends('member.layout')

@section('css')
<link rel="stylesheet" type="text/css" href="/assets/member/css/create_order.css">
@endsection

@section('content')
<div class="form-horizontal">
<input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
    <div class="panel panel-default panel-block panel-title-block" id="top">
        <div class="panel-heading">
            <div>
                <i class="fa fa-shopping-cart"></i>
                <h1>
                    <span class="page-title">Order &raquo; Create order</span>
                    <small>
                    Add a product on your website
                    </small>
                </h1>
               	<!--<button class="btn btn-primary pull-right btn-save-draft">Save draft</button>-->
            </div>
        </div>
    </div>
    <div class="form-group">
      <div class="col-md-12">
        <div class="notice notice-danger display-none notice-order">
          <a href="#" class="close-notice">&times;</a>
          <div class="notice-container"></div>
        </div>
      </div>
    </div>
    <div class="form-group">
        <div class="col-md-8">
            <div class="well well-white border-none">
                <div class="form-group">
                    <div class="col-md-8">
                        <label>Order details</label>
                    </div>
                    <div class="col-md-2" > 
                        <!--<span><i class="fa fa-clock-o" aria-hidden="true"></i>&nbsp;<a href="#" id="popover-reserve" data-placement="bottom">Reserve items</a></span>-->
                        <div class="hide" id="reserve-popover">
                          <label class="f18"><b>Reserver items</b></label>
                          <div class="form-group">
                            <div class="col-md-6">
                              <span>Until</span>
                              <input type="text" class="form-control" name="">
                            </div>
                            <div class="col-md-6">
                              <span>at</span>
                              <input type="text" class="form-control" name="">
                            </div>
                          </div>
                          <span class="light-gray">Your inventory will be automatically restocked at this time</span>
                          <hr>
                          <div class="form-group">
                            <div class="col-md-12">
                              <button class="btn btn-primary pull-right">Save</button>
                              <button class="btn btn-def-white pull-right margin-right-20">Cancel</button>
                            </div>
                          </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <!--<a href="#" data-toggle="modal" data-target="#CutomItemModal">Add custom item</a>-->
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="input-group">
                            <i class="fa fa-search fa-search-custom left-0" aria-hidden="true"></i>
                            <input type="search" class="form-control text-search search-product" placeholder="Start typing to search for orders...">
                            <div class="input-group-addon custom-addon padding0">
                                <button class="btn btn-default btn-custom" data-toggle="modal" data-target="#ProductModal">Browse products</button>
                            </div>
                        </div>
                        <div class="custom-drop-down drop-down-search search-tray z-index-1">
                          
                            <!-- <ul class="list-group-item product-list c-pointer">
                              <li class="list-flex">
                                <img src="" class="img-35-35">
                                  <ul class="margin-nl-20">
                                    <li>
                                      <span>Test</span>
                                    </li>
                                    <li>
                                      <span class="color-gray">blue/red/green</span>
                                    </li>
                                  </ul>
                              </li>
                            </ul> -->
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="table-responsive">
                      
                      <table class="table table-condensed order-item">
                        @if($_item['total'] != 0)
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
                              <p class="light-brown pull-right padding0 f10" id="discount_amount_{{$item['tbl_order_item_id']}}">{{$item['amount_to_show']}}</p>
                              <a href="#" class="discountpopover" id="a_new_amount_{{$item['tbl_order_item_id']}}" rel="popover" data-popover-content="#popdiscount{{$item['tbl_order_item_id']}}">{{$item['less_discount']}}</a>
                              
                              <div class="hide" id="popdiscount{{$item['tbl_order_item_id']}}">
                                <label class="pull-left">Discount this item by</label><Br>
                                <div class="form-horizontal">
                                  <div class="form-group">
                                    <div class="col-md-10">
                                      <div class="input-group">
                                        <span class="input-group-btn">
                                          <button class="btn btn-def-white btn-cat-toggle" data-content="₱" data-id="{{$item['tbl_order_item_id']}}" data-trigger="amount">₱</button>
                                        </span>
                                        <span class="input-group-btn">
                                          <button class="btn btn-def-white btn-cat-toggle" data-content="%" data-id="{{$item['tbl_order_item_id']}}" data-trigger="percent">%</button>
                                        </span>
                                        <span class="peso-back left-76 span-discount-cat" id="discount-cat-{{$item['tbl_order_item_id']}}">₱</span>
                                        <input type="number" step="any" name="" class="form-control text-right" id="number-discount-{{$item['tbl_order_item_id']}}" >
                                      </div>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <div class="col-md-12">
                                      <label class="pull-left">Reason</label>
                                    </div>
                                    <div class="col-md-12">
                                      <input type="text" name="" class="form-control" id="indi_discount_reason_{{$item['tbl_order_item_id']}}" placeholder="Damage item, loyalty discount">
                                    </div>
                                  </div>
                                  <hr>
                                  <div class="form-group">
                                    <div class="col-md-12">
                                      <button class="btn btn-def-white pull-left btn-close-popover">Close</button>
                                      <button class="btn btn-primary pull-right indi-discount" id="indi-discount-{{$item['tbl_order_item_id']}}" data-content="{{$item['tbl_order_item_id']}}" data-trigger="{{$item['discount_var']}}">Apply</button>
                                    </div>
                                  </div>
                                </div>
                                
                              </div>
                            </td>
                            <td width="5%" class="valign-center">
                              <p class="light-brown">x</p>
                            </td>
                            <td width="15%" class="valign-center">
                              <div class="input-group">
                              <span class="input-group-btn">
                                <button class="btn btn-def-white btn-plus-inventory" data-content="{{$item['tbl_order_item_id']}}"><i class="fa fa-plus" aria-hidden="true"></i></button>
                              </span>
                              <input type="text" name="" readonly class="form-control text-center" id="oder_quantity_{{$item['tbl_order_item_id']}}" value="{{$item['quantity']}}">
                              <span class="input-group-btn"><button class="btn btn-def-white btn-minus-inventory" data-content="{{$item['tbl_order_item_id']}}"><i class="fa fa-minus" aria-hidden="true"></i></button></span>
                              </div>
                            </td>
                            <td width="10%" class="valign-center text-right">
                              <p class="light-brown item-amount-{{$item['tbl_order_item_id']}}">{{$item['total_amount']}}</p>

                            </td>
                            <td width="5%" class="valign-center"r>
                              <a href="#" class="remove-order-item" data-content="{{$item['tbl_order_item_id']}}" title="Remove item"><i class="fa fa-times" aria-hidden="true"></i></a>
                              <input type="hidden" name="" class="float-amount float-amount-{{$item['tbl_order_item_id']}}" value="{{$item['total_amount_def']}}">
                              <input type="hidden" name="" id="def_amount_{{$item['tbl_order_item_id']}}" value="{{$item['less_discount_def']}}">
                              <input type="hidden" name="" id="orig_amount_{{$item['tbl_order_item_id']}}" value="{{$item['item_amount_def']}}">
                            </td>
                          </tr>
                          
                        @endforeach
                        @endif
                      </table>
                      
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6">
                        <label>Notes</label>
                        <textarea class="textarea-notes form-control note-text" rows="1" placeholder="Add a note..."></textarea>
                    </div>
                    <div class="col-md-6">
                       <table class="table-custom">
                           <tr>
                               <td class="text-right">
                                   <a href="#" id="discount-popover">Discount</a>
                                   <p class="light-brown padding0 p-discount-reason">{{$discount_reason}}</p>
                                   <div class="hide padding40" id="discount-content">
                                    <div class="form-horizontal">
                                       <div class="form-group">
                                           <div class="col-md-12 text-left">
                                                <label>Discount this order by</label>
                                               <div class="input-group">
                                                   <span class="input-group-btn">
                                                       <button class="toggle_discount_main btn btn-def-white radius-left" data-content="₱" data-trigger="amount">₱</button>
                                                    </span>
                                                    <span class="input-group-btn">
                                                      <button class="toggle_discount_main btn btn-def-white radius-none" data-content="%" data-trigger="percent">%</button>
                                                   </span>
                                                   <span class="peso-back left-76 span-discount-main">{{$discount_currency}}</span>
                                                   <input type="number" class="form-control text-right main-discount-amount" name="" value="{{$discounttext}}">
                                               </div>
                                           </div>
                                       </div>
                                       <div class="form-group">
                                           <div class="col-md-12 text-left">
                                               <label>Reason</label>
                                               <input type="text" class="form-control main-discount-reason" name="" value="{{$discount_reason}}" placeholder="Damage item, loyalty discount">
                                           </div>
                                       </div>
                                       <hr>
                                       <div class="form-group">
                                           <div class="col-md-6 text-center">
                                               <button class="btn btn-default btn-def-white btn_close_dis_pop">Close</button>
                                           </div>
                                           <div class="col-md-6 text-center">
                                               <button class="btn btn-primary approve-main-discount" data-trigger="{{$discount_var}}" data-content="{{$tbl_order_id}}">Approve</button>
                                           </div>
                                       </div>
                                    </div>
                                   </div>
                               </td>
                               <td class="text-right">
                                    <span class="main-discount">{{$discountamount}}</span>

                                    <input type="hidden" id="defdiscount" name="" value="{{$defdiscount}}">
                                    <input type="hidden" id="discount_num" data-trigger="{{$discount_var}}" value="{{$defnumdiscount}}" name="">
                               </td>
                           </tr>
                           <tr>
                               <td class="text-right">
                                   <span>Subtotal</span>
                               </td>
                               <td class="text-right">
                                   <span class="subtotal">{{$total_order}}</span>
                                   <input type="text" style="display: none" value="{{$deftotal}}" class="hidden_subtotal" name="">
                               </td>
                           </tr>
                           <tr>
                               <td class="text-right">
                                   <a href="#" id="shipping-popover" rel="popover"  data-placement="top" data-popover-content="#popover-shipping">Shipping</a>
                                   <p class="light-brown padding0 p-shipping">{{$shipping_name}}</p>
                                   <div class="hide" id="popover-shipping" >
                                      <div class="form-horizontal">
                                       <!--<div class="notice notice-attention">-->
                                       <!--    <p>NOT SEEING ALL YOUR RATES?</p>-->
                                       <!--    <Br>-->
                                       <!--     <p>Add a customer with a complete shipping address to select from calculated shipping rates</p>-->
                                       <!--</div>-->
                                       <div class="radio">
                                          <label class=""><input type="radio" name="shippingrad" class="shipping-radio" id="shipping-free" checked value="free">&nbsp;Free shipping</label>
                                       </div>
                                       <div class="radio">
                                         <label class=""><input type="radio" name="shippingrad" class="shipping-radio" id="shipping-custom" value="custom">&nbsp;Custom</label>
                                       </div>
                                        
                                        <div class="form-group">
                                            <div class="col-md-8">
                                                <select class="form-control shipping-name">
                                                  <option value="0">Select shipping</option>
                                                  @foreach($_shipping as $ship)
                                                  <option value="{{$ship->shipping_id}}" data-amount="{{$ship->shipping_fee}}">{{$ship->shipping_name}}</option>
                                                  @endforeach
                                                </select>
                                                <!--<input type="text" class="form-control shipping-name" name="" placeholder="Custom rate name">-->
                                            </div>
                                            <div class="col-md-4">
                                                <span class="peso-back">₱</span>
                                                <input type="number" step="any" class="form-control indent-10 text-right shipping-price" name="" placeholder="">
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group">
                                            <div class="col-md-6 text-center">
                                                <button class="btn btm-default btn-def-white">Cancel</button>
                                            </div>
                                            <div class="col-md-6 text-center">
                                                <button class="btn btn-primary btn-shipping" data-trigger="free">Apply</button>
                                            </div>
                                        </div>
                                      </div>
                                   </div>
                               </td>
                               <td class="text-right">
                                   <span class="shipping_amount">{{$shipping}}</span>
                                   <input type="hidden" class="hidden_shipping_amount" name="" value="{{$defshipping}}">

                               </td>
                           </tr>
                           <tr>
                               <td class="text-right">
                                @if($IsTaxExemp == 0)

                                  <span class="light-brown span-tax-exempt" style="display: none">Customer is tax exempt</span>
                                   <a href="#" id="tax-popover" data-placement="top" rel="popover" data-popover-content="#popover-tax">Taxes</a>
                                   <p class="light-brown p-tax-percent padding0">{{$strtaxpercent}}</p>
                                @else
                                   <span class="light-brown span-tax-exempt">Customer is tax exempt</span>
                                   <a href="#" id="tax-popover" data-placement="top" rel="popover" data-popover-content="#popover-tax" style="display: none">Taxes</a>
                                   <p class="light-brown p-tax-percent padding0"></p>
                                @endif
                                  
                                   <div class="hide" id="popover-tax">
                                     Taxes are automatically calculated.
                                     <div class="checkbox">
                                       <label><input type="checkbox" name="" id="check-charge">Charge taxes</label>
                                     </div>
                                     <hr>
                                     <div class="form-horizontal">
                                       <div class="form-group">
                                         <div class="col-md-12">
                                           <button class="btn btn-def-white pull-left btn-tax-canel">Cancel</button>
                                           <button class="btn btn-primary pull-right btn-charge-tax" data-content="{{$tbl_order_id}}">Apply</button>
                                         </div>
                                       </div>
                                     </div>
                                   </div>
                               
                               </td>
                               <td class="text-right">
                                   <span class="tax-content">{{$tax}}</span>
                                   <input type="hidden" name="" id="deftax" value="{{$deftax}}">
                                   <input type="hidden" name="" id="taxpercent" value="{{$taxpercent}}" data-trigger="{{$hasTax}}">
                               </td>
                           </tr>
                           <tr>
                               <td class="text-right">
                                   <b>Total</b>
                               </td>
                               <td class="text-right">
                                   <span id="total_order_amount">{{$total_ordering}}</span>
                                   <input type="hidden" name="" id="def_total_ordering" value="{{$def_total_ordering}}">
                               </td>
                           </tr>
                       </table>
                    </div>
                </div>

                <hr>
                <div class="form-group">
                    <div class="col-md-10">
                        <h4><i class="fa fa-file-text" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;EMAIL INVOICE</h4>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-default btn-def-white">Email Invoice</button>
                    </div>
                </div>
                <hr>
                <div class="form-group">
                    <div class="col-md-4">
                        <h4><i class="fa fa-credit-card-alt" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;ACCEPT PAYMENT</h4>
                    </div>
                    <div class="col-md-8">
                        <div class="col-md-4 padding0">
                            <button class="btn btn-default btn-def-white pull-right btn-payment" data-trigger="Paid">Mark as paid</button>
                        </div>
                        <div class="col-md-4 padding0">
                            <button class="btn btn-default btn-def-white pull-right btn-payment" data-trigger="Pending">Mark as pending</button>
                        </div>
                        <div class="col-md-4 padding0">
                            <button class="btn btn-default btn-def-white pull-right">Pay with credit card</button>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>

        <div class="col-md-4">
            <div class="well well-white border-none">
                
                <div class="search-customer-form" style="display: {{$customer_form}}">
                  <div class="form-group">
                      <div class="col-md-12"><label>Find or create a customer</label></div>
                  </div>
                  <div class="form-group">
                      <div class="col-md-12">
                          <i class="fa fa-search fa-search-custom" aria-hidden="true"></i>
                          <input type="text" class="form-control search-customer" id="search-customer" placeholder="Find customers..." name="" data-placement="bottom">
                          <div id="popover-customer" class="hide padding0">
                              <div class="customer-list">
                                
                              </div>
                          </div>
                      </div>
                  </div>
                </div>

                <div class="customer-info-div" style="display: {{$customer_info}}">
                  <label class="f18">Customer</label>
                  <a href="#" class="a-close pull-right " data-order="{{$tbl_order_id}}" data-customer="{{$customer_id}}"><i class="fa fa-times"></i></a>
                  <div class="cutomer-result">
                    {!!$customer!!}
                  </div>
                </div>

            </div>
            <!--<div class="well well-white border-none">-->
            <!--    <div class="form-group">-->
            <!--        <div class="col-md-8">-->
            <!--            <label>Tags</label>-->
            <!--        </div>-->
            <!--        <div class="col-md-4">-->
            <!--            <a href="#">View all tags</a>-->
            <!--        </div>-->
            <!--    </div>-->
            <!--    <div class="form-group">-->
            <!--        <div class="col-md-12">-->
            <!--            <input type="text" class="form-control" placeholder="Urgent, reviewed, wholesale" name="">-->
            <!--        </div>-->
            <!--    </div>-->
            <!--    <div class="form-group tag-container">-->
                    
            <!--    </div>-->
            <!--</div>-->
        </div>
    </div>
</div>
<div id="CutomItemModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add custom item</h4>
      </div>
      <div class="modal-body">
        <div class="form-horizontal">
          <div class="form-group">
            <div class="col-md-6">
              <span>Line item name</span>
              <input type="text" class="form-control" name="">
            </div>
            <div class="col-md-3">
              <span>Price per item</span>
              <span class="peso-back">₱</span>
              <input type="number" name="" class="form-control price-item text-right" step="any">
            </div>
            <div class="col-md-3">
              <span>Quantity</span>
              <input type="number" class="form-control text-right" name="">
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="checkbox">
            <label><input type="checkbox" name="">Item is taxable</label>
          </div>
          <div class="checkbox">
            <label><input type="checkbox" name="">Item requires shipping</label>
          </div>
        </div>
        <div class="notice notice-attention">
          <a href="#">Create a product</a> with weight specified to calculate shipping rates accurately
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-def-white " data-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" type="button">Save line item</button>
      </div>
    </div>
  </div>
</div>
<div id="CreateCustomerModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Create a new customer</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" id="new-customer-form">
          <input type="hidden" name="_token" value="{{csrf_token()}}">
          <div class="form-group">
              <div class="col-md-6">
                <span>First name</span>
                <input type="text" class="form-control new_first_name" name="first_name">
              </div>
              <div class="col-md-6">
                <span>Last name</span>
                <input type="text" class="form-control new_last_name" name="last_name">
              </div>
          </div>
          <div class="form-group">
            <div class="col-md-12">
              <span>Email</span>
              <input type="text" class="form-control new_email" name="email">
            </div>
          </div>
          <div class="checkbox">
            <label><input type="checkbox" class="new_accepts_marketing" name="accepts_marketing" value="1">Customer accepts marketing</label>
          </div>
          <div class="checkbox">
            <label><input type="checkbox" class="new_tax_exempt" name="tax_exempt" value="1">Customer is tax exempt</label>
          </div>
          <hr>
          <h4>SHIPPING ADDRESS</h4>
          <div class="form-group">
            <div class="col-md-6">
              <span>Company</span>
              <input type="text" class="form-control new_company" name="company">
            </div>
            <div class="col-md-6">
              <span>Phone</span>
              <input type="text" class="form-control new_phone" name="phone">
            </div>
          </div>
          <div class="form-group">
            <div class="col-md-6">
              <span>Address</span>
              <input type="text" class="form-control new_address" name="address">
            </div>
            <div class="col-md-6">
              <span>Address con't</span>
              <input type="text" class="form-control new_address_cont" name="address_cont">
            </div>
          </div>
          <div class="form-group">
            <div class="col-md-6">
              <span>City</span>
              <input type="text" class="form-control new_city" name="city">
            </div>
            <div class="col-md-6">
              <span>Country</span>
              <select class="form-control new_country" name="country">
                @foreach($_country as $country)
                  <option value="{{$country->country_id}}">{{$country->country_name}}</option>
                @endforeach
                
              </select>
              
            </div>
          </div>
          <div class="form-group">
            <div class="col-md-6">
              <span>Province</span>
              <input type="text" class="form-control new_province" name="province">
            </div>
            <div class="col-md-6">
              <span>Postal/zip code</span>
              <input type="text" class="form-control new_zip_code" name="zip_code">
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-def-white " data-dismiss="modal">Cancel</button>
        <button class="btn btn-primary btn-new-customer" type="button">Save customer</button>
      </div>
    </div>
  </div>
</div>

<div id="ProductModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Select products</h4>
      </div>
      <div class="modal-body">
        <div class="form-horizontal">
          <div class="form-group">
            <div class="col-md-12">
              <span class="peso-back"><i class="fa fa-search"></i></span>
              <input type="text" class="form-control indent-14" name="" placeholder="Find products...">
            </div>
          </div>
          <div class="form-group search-list-option">
            <div class="col-md-12">
              <div class="list-group">
                <a href="#" class="list-group-item search-custom-list" data-content="All products"><span class="badge"><i class="fa fa-chevron-right" aria-hidden="true"></i></span>All products</a>
                <a href="#" class="list-group-item search-custom-list" data-content="Popular products"><span class="badge"><i class="fa fa-chevron-right" aria-hidden="true"></i></span>Popular products</a>
                <!-- <a href="#" class="list-group-item search-custom-list" data-content="Collections"><span class="badge"><i class="fa fa-chevron-right" aria-hidden="true"></i></span>Collections</a> -->
                <a href="#" class="list-group-item search-custom-list" data-content="Product types"><span class="badge"><i class="fa fa-chevron-right" aria-hidden="true"></i></span>Product types</a>
                <!-- <a href="#" class="list-group-item search-custom-list" data-content="Tags"><span class="badge"><i class="fa fa-chevron-right" aria-hidden="true"></i></span>Tags</a> -->
                <a href="#" class="list-group-item search-custom-list" data-content="Vendors"><span class="badge"><i class="fa fa-chevron-right" aria-hidden="true"></i></span>Vendors</a>
              </div>
              
            </div>
          </div>
          <div class="search-item-result" style="display: none">
            <div class="form-group">
              <div class="col-md-12">
                <label class="f18 back-list c-pointer"></label>
              </div>
            </div>
            <div class="form-group">
              <form class="item-result" id="item-result">
                
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-def-white" data-dismiss="modal">Close</button>
        <button class="btn btn-primary btn-add-order" type="button">Add to order</button>
      </div>
    </div>

  </div>
</div>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Tags</h4>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-def-white" data-dismiss="modal">Close</button>
        <button class="btn btn-primary" type="button">Apply changes</button>
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
<div class="modal fade" role="dialog" id="PaymentModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title payment-title">Mark as paid</h4>
      </div>
      <div class="modal-body payment-body">
      </div>
       <div class="modal-footer">
        <button type="button" class="btn btn-def-white" data-dismiss="modal">Cancel</button>
        <button class="btn btn-primary create-order-modal" type="button">Create order</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script type="text/javascript" src="/assets/member/js/customer.js"></script>
<script type="text/javascript" src="/assets/member/js/create_order.js"></script>

@endsection