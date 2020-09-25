<form class="z-index-1000 global-submit" method="POST" action="/member/customer/updatecustomermain">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title layout-modallarge-title">Update customer info</h4>
  </div>
  <div class="modal-body modallarge-body-layout background-white">
    
    <input type="hidden" name="client_id" value="{{$customer_info->customer_id}}">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <div class="form-horizontal">
    <div class="form-group">
      <div class="col-md-6">
        <div class="col-md-2 padding-lr-1">
          <label>Title</label>
          <input type="text" value="{{$customer_info->title_name}}" name="title" class="form-control auto-name title margin-l-0"/>
        </div>
        <div class="col-md-3 padding-lr-1">
          <label>First name</label>
          <input type="text" value="{{$customer_info->first_name}}" name="first_name" class="form-control auto-name first_name"/>
        </div>
        <div class="col-md-3 padding-lr-1">
          <label>Middle name</label>
          <input type="text" value="{{$customer_info->middle_name}}" name="middle_name" class="form-control auto-name middle_name"/>
        </div>
        <div class="col-md-3 padding-lr-1">
          <label>Last name</label>
          <input type="text" value="{{$customer_info->last_name}}" name="last_name" class="form-control auto-name last_name"/>
        </div>
        <div class="col-md-1 padding-lr-1">
          <label>Suffix</label>
          <input type="text" value="{{$customer_info->suffix_name}}" name="suffix" class="form-control auto-name suffix"/>
        </div>
      </div>
      <div class="col-md-6">
        <label for="">Email</label>
        <input type="email" value="{{$customer_info->email}}" name="email" class="form-control"/>
      </div>
    </div>
    <div class="form-group">
      <div class="col-md-6">
        <label for="">Company</label>
        <input type="text" name="company" value="{{$customer_info->company}}" class="form-control">
      </div>
      <div class="col-md-6">
        <div class="col-md-4 padding-lr-1">
          <label for="">Phone</label>
          <input type="text" value="@if(isset($other->customer_phone)){{$other->customer_phone}}@endif" name="phone" class="form-control" required/>
        </div>
        <div class="col-md-4 padding-lr-1">
          <label for="">Mobile</label>
          <input type="text" value="@if(isset($other->customer_mobile)){{$other->customer_mobile}}@endif" name="mobile" class="form-control"/>
        </div>
        <div class="col-md-4 padding-lr-1">
          <label for="">Fax</label>
          <input type="text" value="@if(isset($other->customer_fax)){{$other->customer_fax}}@endif" name="fax" class="form-control"/>
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="col-md-6">
        <label for="">Display name as</label>
        <div class="input-group">
          <input type="text" name="display_name_as" value="@if(isset($other->customer_display_name)){{$other->customer_display_name}}@endif" class="form-control txt-display-name"/>
          <span class="input-group-btn">
            <button class="btn display-name-drop-down btn-toggle-custom btn-custom-white-gray" type="button" data-target=".drop-down-display-name"><i class="fa fa-caret-down" aria-hidden="true"></i></button>
          </span>
        </div>
        <div class="custom-drop-down list-group drop-down-display-name">
         
        </div>
        <div class="custom-drop-down"></div>
      </div>
      <div class="col-md-6">
        <div class="col-md-4 padding-lr-1">
          <label for="">Other</label>
          <input type="text" name="other" value="@if(isset($other->customer_display_name)){{$other->customer_other_contact}}@endif" class="form-control"/>
        </div>
        <div class="col-md-8 padding-lr-1">
          <label for="">Website</label>
          <input type="text" name="website" value="@if(isset($other->customer_display_name)){{$other->customer_website}}@endif" class="form-control"/>
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="col-md-6">
        <label for=""><b>Print on as check as</b></label>&nbsp;
        <div class="checkbox display-inline-block"><label for=""><input type="checkbox" name="chck_print_on_as" class="checkbox-toggle-rev" data-target=".display-name-check" checked="true"/>Use display name</label></div>
        <input type="text" name="print_on_as_check_as" value="@if(isset($other->customer_print_name)){{$other->customer_print_name}}@endif" class="form-control display-name-check"/>
      </div>
      <div class="col-md-6">
        <div class="checkbox"><label for=""><input type="checkbox" name="chck_is_sub_customer" value="1" class="checkbox-toggle" data-target=".sub-customer"/>Is sub-customer</label></div>
        <div class="col-md-7 padding-lr-1">
          <select class="form-control sub-customer" name="hidden_sub_customer_id">
            <option value="0">Select Customer</option>
            @foreach($_customer as $customer)
            <option value="{{$customer->customer_id}}">{{$customer->first_name.' '.$customer->last_name}}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-5 padding-lr-1">
          <select class="form-control sub-customer sub_customer_billing" name="sub_customer_billing">
            <option value="Bill with parent">Bill with parent</option>
            <option value="Bill this customer">Bill this customer</option>
          </select>
        </div>
      </div>
    </div>
    <!-- FOR MONTHLY BUDGET FIELDMEN -->
    @if(isset($monthly_budget))
      @if($monthly_budget)
        <div class="form-group">
          <div class="col-md-6">
            <label>Fix Monthly Budget</label>
            <input type="text" class="form-control text-right txt-budget-price" placeholder="0.00" value="{{currency('',$customer_info->fix_monthly_budget)}}" name="fix_monthly_budget">
          </div>
          <div class="col-md-6 hidden">
            <label>Adjusted Monthly Budget</label>
            <input type="text" class="form-control text-right txt-budget-price" placeholder="0.00" value="{{currency('',$customer_info->adjusted_monthly_budget)}}" name="adjusted_monthly_budget">
          </div>
        </div>
      @endif
    @endif
    @if(isset($migo_customization))
      @if($migo_customization)
        <div class="form-group">
          <div class="col-md-6">
            <label>Customer Category</label>
            <select class="form-control" name="customer_category">
              <option {{$customer_info->customer_category == 'new-client' ? 'selected' : ''}} value="new-client">New Client</option>
              <option {{$customer_info->customer_category == 'regular' ? 'selected' : ''}} value="regular">Regular</option>
              <option {{$customer_info->customer_category == 'former' ? 'selected' : ''}} value="former">Former</option>
              <option {{$customer_info->customer_category == 'employee' ? 'selected' : ''}} value="employee">Employee</option>
              <option {{$customer_info->customer_category == 'do-not-call' ? 'selected' : ''}} value="do-not-call">Do not Call</option>
            </select>
          </div>
          <div class="col-md-6">
            <label>Customer Type</label>
            <select class="form-control" name="customer_category_type">
              <option {{$customer_info->customer_category_type == 'non-vip' ? 'selected' : ''}} value="non-vip">Non VIP</option>
              <option {{$customer_info->customer_category_type == 'vip' ? 'selected' : ''}} value="vip">VIP</option>
            </select>
          </div>
        </div>
      @endif
    @endif
    <div class="form-group">
      <div class="col-md-12">
        <ul class="nav nav-tabs nav-tabs-custom">
          <li class="active"><a data-toggle="tab" href="#address">Address</a></li>
          <li><a data-toggle="tab" href="#notes">Notes</a></li>
          <li><a data-toggle="tab" href="#tax-info">Tax info</a></li>
          <li><a data-toggle="tab" href="#payment-and-billing">Payment and billing</a></li>
          <li><a data-toggle="tab" href="#attachment">Attachment</a></li>
          @if(!$pis)
          <li><a data-toggle="tab" href="#mlm">MLM</a></li>
          <li><a data-toggle="tab" href="#stockist">Stockist</a></li>
          @endif
          @if ($customer->ismlm == 2)
          <li><a data-toggle="tab" href="#ecomm">E-commerce</a></li>
          @endif
        </ul>
        
        <div class="tab-content">
          <div id="address" class="tab-pane fade in active">
            <br>
           <div class="form-horizontal">
              <div class="form-group">
                <div class="col-md-6">
                  <label for=""><b>Billing address</b></label>
                  
                  <div class="form-group">
                    <div class="col-md-12">
                      <textarea name="billing_street" rows="2" class="form-control billing-address" data-target=".shipping_street" placeholder="Street" required>{{isset($billing->customer_street) ? $billing->customer_street :''}}</textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-6">
                      <input type="text" name="billing_city" value="{{isset($billing->customer_city) ? $billing->customer_city :''}}" class="form-control billing-address" data-target=".shipping_city" placeholder="City/Town">
                    </div>
                    <div class="col-md-6">
                      <input type="text" name="billing_state" value="{{isset($billing->customer_state) ? $billing->customer_state :''}}" class="form-control billing-address" data-target=".shipping_state" placeholder="State">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-6">
                      <input type="text" name="billing_zipcode" value="{{isset($billing->customer_zipcode) ? $billing->customer_zipcode :''}}" class="form-control billing-address" data-target=".shipping_zipcode" placeholder="Zip code">
                    </div>
                    <div class="col-md-6">
                      <select class="form-control billing-address" name="billing_country" data-target=".shipping_country">
                        @foreach($_country as $country)
                        <option value="{{$country->country_id}}" {{isset($billing->country_id) ? ($billing->country_id == $country->country_id ? 'selected="selected"':'' ):''}}>{{$country->country_name}}</option>
                        @endforeach
                      </select>
                      <!--<input type="text" name="billing_country" class="form-control billing-address" data-target=".shipping_country" placeholder="Country">-->
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <label for=""><b>Shipping address</b></label>
                  <div class="checkbox display-inline-block"><label for=""><input type="checkbox" name="chk_same_shipping_address" checked="true" class="checkbox-toggle-rev chk_same_shipping_address" data-target=".same-billing"/>Same as billing address</label></div>
                  <div class="form-group">
                    <div class="col-md-12">
                      <textarea name="shipping_street" rows="2" class="form-control shipping_street same-billing" placeholder="Street">{{isset($shipping->customer_street) ? $shipping->customer_street :''}}</textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-6">
                      <input type="text" value="{{isset($shipping->customer_city) ? $shipping->customer_city :''}}" name="shipping_city" class="form-control shipping_city same-billing" placeholder="City/Town">
                    </div>
                    <div class="col-md-6">
                      <input type="text" value="{{isset($shipping->customer_state) ? $shipping->customer_state :''}}" name="shipping_state" class="form-control shipping_state same-billing" placeholder="State">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-6">
                      <input type="text" value="{{isset($shipping->customer_zipcode) ? $shipping->customer_zipcode :''}}" name="shipping_zipcode" class="form-control shipping_zipcode same-billing" placeholder="Zip code">
                    </div>
                    <div class="col-md-6">
                      <select class="form-control shipping_country same-billing" name="shipping_country">
                        @foreach($_country as $country)
                        <option value="{{$country->country_id}}"{{isset($shipping->country_id) ? ($shipping->country_id == $country->country_id ? 'selected="selected"':'' ):''}}>{{$country->country_name}}</option>
                        @endforeach
                      </select>
                      <!--<input type="text" name="shipping_country" class="form-control shipping_country same-billing" placeholder="Country">-->
                    </div>
                  </div>
                </div>
              </div>
           </div>
          </div>
          <div id="notes" class="tab-pane fade">
            <br>
            <div class="form-horizontal">
              <div class="form-group">
                <div class="col-md-12">
                  <label for=""><b>Notes</b></label>
                  <textarea class="form-control" rows="5" name="notes">{{$other->customer_notes}}</textarea>
                </div>
              </div>
            </div>
          </div>
          <div id="tax-info" class="tab-pane fade">
            <br>
            <div class="form-horizontal">
              <div class="form-group">
                <div class="col-md-6">
                  <label for=""><b>Tax Resale No.</b></label>
                  <input type="text" name="" class="form-control" value="{{$customer_info->customer_tax_resale_no}}" name="tax_resale_no"/>
                </div>
                <div class="col-md-6">
                  <label for=""><b>TIN</b></label>
                  <input type="text" name="tin_number" class="form-control" value="{{$customer_info->tin_number}}" name="tin_number"/>
                </div>
              </div>
            </div>
          </div>
          <div id="payment-and-billing" class="tab-pane fade">
            <br>
            <div class="form-horizontal">
              <div class="form-group">
                <div class="col-md-6">
                  <div class="form-group">
                    <div class="col-md-6">
                      <label for=""><b>Preferred payment method</b></label>
                      <div class="input-group">
                        <input type="text" name="payment_method" value="{{isset($payment_name->payment_name) ? $payment_name->payment_name : ''}}" class="form-control payment_method" placeholder="Enter text"/>
                        <input type="hidden" name="hidden_payment_method" value="{{$customer_info->customer_payment_method}}" class="hidden_payment_method"/>
                        <span class="input-group-btn">
                          <button class="btn btn-custom-white-gray btn-toggle-custom" type="button" data-target=".drop-down-payment-method" container=".modallarge-body-layout"><i class="fa fa-caret-down" aria-hidden="true"></i></button>
                        </span>
                      </div>
                      
                      <div  class="custom-drop-down drop-down-payment-method">
                        <a href="#" class="list-group-item list-drop-display-payment-method" data-value="0" data-html=""><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Add new</a>
                        @foreach($_def_payment_method as $defmethod)
                        <a href="#" class="list-group-item list-drop-display-payment-method" data-value="{{$defmethod->payment_method_id}}" data-html="{{$defmethod->payment_name}}">{{$defmethod->payment_name}}</a>
                        @endforeach
                        @foreach($_payment_method as $method)
                        <a href="#" class="list-group-item list-drop-display-payment-method" data-value="{{$method->payment_method_id}}" data-html="{{$method->payment_name}}">{{$method->payment_name}}</a>
                        @endforeach
                        
                      </div>
                      <div class="custom-drop-down padding-7 new-custom-drop-down new-payment-method">
                        <label><b>New payment method</b></label>
                        <a href="javascript:" class="pos-absolute top-left-5 close-custom-drop" data-target=".new-custom-drop-down"><i class="fa fa-times" aria-hidden="true"></i></a>
                        <br>
                        <div class="form-group">
                          <div class="col-md-12">
                            <span>Name</span>
                            <input type="text" class="form-control new-payment-name">
                          </div>
                        </div>
                        
                        <div class="form-group">
                          <div class="col-md-12">
                            <button class="btn btn-custom-primary pull-right btn-save-new-method" type="button">Save</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-6">
                      <label for=""><b>Preferred delivery method</b></label>
                      <select class="form-control" name="delivery_method">
                        @foreach($_delivery_method as $delivery)
                        <option value="{{$delivery->delivery_method_id}}" @if(isset($other->customer_delivery_method)){{$other->customer_delivery_method == $delivery->delivery_method_id ? 'selected="selected"':''}}@endif</option>{{$delivery->delivery_method}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <div class="col-md-6">
                      <label for=""><b>Terms</b></label>
                      <div class="input-group">
                        <input type="text" class="form-control txt_terms" value="{{isset($termname->term_name) ? $termname->term_name : ''}}" name="txt_terms">
                        <input type="hidden" class="hidden_terms" value="@if(isset($other->customer_terms)){{$other->customer_terms}}@endif" name="hidden_terms">
                        <span class="input-group-btn">
                          <button class="btn btn-custom-white-gray btn-toggle-custom" type="button" data-target=".drop-down-terms" container=".modallarge-body-layout"><i class="fa fa-caret-down" aria-hidden="true"></i></button>
                        </span>
                      </div>
                      <div  class="custom-drop-down drop-down-terms">
                        <a href="#" class="list-group-item list-drop-display-terms" data-value="0" data-html="">
                            <i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Add new
                        </a>
                        @foreach($_term as $term)
                        <a href="#" class="list-group-item list-drop-display-terms" data-value="{{$term->term_id}}" data-html="{{$term->term_name}}">{{$term->term_name}}</a>
                        @endforeach
                      </div>
                      <div class="custom-drop-down new-drop-down-term padding-10 lg-120">
                        <label><b>New term</b></label>
                        <a href="javascript:" class="pos-absolute top-left-5 close-custom-drop" data-target=".new-drop-down-term"><i class="fa fa-times" aria-hidden="true"></i></a>
                        <div class="form-group">
                          <div class="col-md-12">
                            <span>Name</span>
                            <input type="text" name="" class="form-control txt-new-term-name"/>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-md-12">
                            <div class="radio">
                              <label><input type="radio" class="radio-new-terms" name="new_terms_radio" value="fixed day" checked="true">Due in fixed number of days</label>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-md-1"></div>
                          <div class="col-md-2 padding-lr-2">
                            <input type="text" class="form-control txt-fixed-day">
                          </div>
                          <label class="col-md-2 padding-lr-2">days</label>
                        </div>
                        <div class="form-group">
                          <div class="col-md-12">
                            <div class="radio">
                              <label><input type="radio" class="radio-new-terms" name="new_terms_radio" value="certain day">Due by certain day of the month</label>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-md-1"></div>
                          <div class="col-md-2 padding-lr-2">
                            <input type="text" class="form-control txt-certain-day-month" disabled>
                          </div>
                          <label class="col-md-7 padding-lr-2">day of month</label>
                        </div>
                        <div class="form-group">
                          <div class="col-md-1"></div>
                          <label class="col-md-11 ">Due the next month if issued within</label>
                        </div>
                        <div class="form-group">
                          <div class="col-md-1"></div>
                          <div class="col-md-2 padding-lr-2">
                            <input type="text" class="form-control txt-certain-days-due" disabled>
                          </div>
                          <label class="col-md-7 padding-lr-2">days of due date</label>
                        </div>
                        <div class="form-group">
                          <div class="col-md-12">
                            <button class="btn btn-custom-primary pull-right btn-save-new-terms" type="button">Save</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-6">
                      
                      <label for=""><b>Opening balance</b></label>
                      <input type="number" class="form-control text-right" value="@if(isset($other->customer_opening_balance)){{$other->customer_opening_balance}}@endif" name="opening_balance" step="any">
                    </div>
                    <div class="col-md-6">
                      <label for=""><b>as of</b></label>
                      
                      <input type="text" class="form-control indent-13 datepicker" value="@if(isset($other->customer_balance_date)){{$other->customer_balance_date != '0000-00-00' ? date('d/m/Y', strtotime($customer_info->customer_balance_date)) : ''}}@endif" name="date_as_of">
                      <i class="fa fa-calendar pos-absolute top-34 margin-left-6 color-dark-gray" aria-hidden="true"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div id="attachment" class="tab-pane fade">
            <br>
            <div class="form-horizontal">
              <div class="form-group">
                <div class="col-md-6">
                  <label for=""><b><i class="fa fa-paperclip" aria-hidden="true"></i>&nbsp;Attachments</b></label><span class="margin-left-30">Maximum size: 25MB</span>
                  <div class="border-gray border-gray-dotted padding-10 div-attachment form-horizontal">
                    
                    <div class="text-center color-gray div-file-input form-group">
                      <span><i>Click here to upload file</i></span>
                      <input type="file" name="" id="attachment_file" class="hidden"/>
                    </div>
                    @foreach($_attachment as $attachment)
                    <div class="form-group file-{{$attachment->customer_attachment_id}}">
                      <div class="col-md-8">
                        <a href="/member/customer/downloadfile/{{Crypt::encrypt($attachment->customer_attachment_id)}}">{{$attachment->customer_attachment_name}}</a>
                      </div>
                      <div class="col-md-4 file-operation-">
                        <a href="javascript:" class="pull-right remove-upload" data-path="{{$attachment->customer_attachment_path}}" data-value="{{$attachment->customer_attachment_id}}" data-target=".file-{{$attachment->customer_attachment_id}}"><i class="fa fa-times" aria-hidden="true"></i></a>
                	    	<input type="hidden" value="{{$attachment->customer_attachment_path}}" name="fileurl[]">
                	    	<input type="hidden" value="{{$attachment->customer_attachment_name}}" name="filename[]">
                	    	<input type="hidden" value="{{$attachment->customer_attachment_name}}" name="mimetype[]">
                      </div>
                    </div>
                    @endforeach
                  </div>
                </div>

                @if($proposal_number)
                <div class="col-md-6">
                  <div class="proposal-div">
                    @if(count($_proposal) > 0)
                      @foreach($_proposal as $proposal)
                      <div class="proposal-list">
                        <div class="col-md-8">
                          <label>&nbsp</label>
                          <input type="text" class="form-control" name="proposal_number[]" value="{{$proposal}}" placeholder="Proposal Number">
                        </div>
                        <div class="col-md-2">
                          <label>&nbsp</label>
                          <button type="button" class="btn btn-primary btn-add-line"><i class="fa fa-plus"></i></button>
                        </div>
                        <div class="col-md-2">
                          <label>&nbsp</label>
                          <button type="button" class="btn btn-danger btn-remove-line"><i class="fa fa-times"></i></button>
                        </div>
                      </div>
                      @endforeach
                    @endif
                    <div class="proposal-list">
                      <div class="col-md-8">
                        <label>&nbsp</label>
                        <input type="text" class="form-control" name="proposal_number[]" value="" placeholder="Proposal Number">
                      </div>
                      <div class="col-md-2">
                        <label>&nbsp</label>
                        <button type="button" class="btn btn-primary btn-add-line"><i class="fa fa-plus"></i></button>
                      </div>
                      <div class="col-md-2">
                        <label>&nbsp</label>
                        <button type="button" class="btn btn-danger btn-remove-line"><i class="fa fa-times"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
                @endif
              </div>
            </div>
          </div>
          <div id="mlm" class="tab-pane fade">
            <br>
            <div class="form-horizontal">
              @if($customer->signup_with == 'member_register')
              <div class="form-group">
                <div class="col-md-12">
                  <div class="col-md-12">
                    <div class="checkbox display-inline-block">
                      <label for="">
                        <input type="checkbox" name="ismlm" class="ismlm" value="1" @if($customer_info->ismlm == 1) checked @endif />Use In MLM
                        
                        </label>
                      </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="col-md-6">
                    <label>Username</label>
                    <input type="text" class="form-control mlm_username" value="{{$customer_info->mlm_username}}" name="mlm_username" @if($customer_info->ismlm == 0) readonly @endif >
                  </div>
                  <div class="col-md-6">
                    <label>Password</label>
                    <input type="password" class="form-control mlm_password" value="@if($customer_info->password != null){{ Crypt::decrypt($customer_info->password)}}@endif" name="mlm_password" @if($customer_info->ismlm == 0) readonly @endif>
                    <div>
                      <input type="checkbox" class="show_password"> Show Password
                    </div>
                  </div>
                </div>
              </div>
              @else
              <div class="form-group text-center">
              <span>You cannot change password of this account. It was singned up with Google or Facebook</span>
              </div>
              @endif
            </div>
          </div>
  
          <div id="ecomm" class="tab-pane fade">
            <br>
            <div class="form-horizontal">
              <div class="form-group">
                {{-- <div class="col-md-12">
                  <div class="col-md-12">
                    <div class="checkbox display-inline-block">
                      <label for="">
                        <input type="checkbox" name="ismlm" class="ismlm" value="1" @if($customer_info->ismlm == 1) checked @endif />Use In MLM
                        
                        </label>
                      </div>
                  </div>
                </div> --}}
                <div class="col-md-12">
                  <div class="col-md-6">
                    <label>Email</label>
                    <input type="text" class="form-control mlm_email_e_commerce" value="{{$customer_info->email}}" name="mlm_email_e_commerce" @if($customer_info->ismlm == 0) readonly @endif >
                  </div>
                  <div class="col-md-6">
                    <label>Password</label>
                    <input type="password" class="form-control mlm_password_e_commerce" value="@if($customer_info->password != null){{ Crypt::decrypt($customer_info->password)}}@endif" name="mlm_password_e_commerce" @if($customer_info->ismlm == 0) readonly @endif>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div id="stockist" class="tab-pane fade">
            <br>
            <div class="form-horizontal">
              <div class="form-group">
                <div class="col-md-12">
                 <!--  <div class="col-md-12">
                    <div class="checkbox display-inline-block">
                      <label for="">
                        <input type="checkbox" name="checkbox" class="is_stockist" value="1" @if($customer_info->stockist_warehouse_id != 0) checked @endif />Enable Stockist
                        
                        </label>
                      </div>
                  </div> -->
                </div>
                <div class="col-md-12">
                  <div class="col-md-6">
                    <label>Select Warehouse</label>
                    <select class="form-control" name="stockist_warehouse_id">
                      @foreach($_warehouse as $warehouse)
                      <option {{$warehouse->warehouse_id == $customer_info->stockist_warehouse_id ? 'selected' : ''}} value="{{$warehouse->warehouse_id}}">{{$warehouse->warehouse_name}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>


        </div>
      </div>
    </div>
  </div>

  </div>
  <div class="modal-footer">
    <div class="error-modal text-center">
        Error
    </div>
    
    <button type="button" class="btn btn-custom-white " data-dismiss="modal">Cancel</button>
    <button class="btn btn-custom-primary" type="submit">Update customer</button>
  </div>
</form>


<div class="div-script-proposal">
  <div class="body-script-proposal hidden">
    <div class="proposal-list">
      <div class="col-md-8">
        <label>&nbsp</label>
        <input type="text" class="form-control" name="proposal_number[]" value="" placeholder="Proposal Number">
      </div>
      <div class="col-md-2">
        <label>&nbsp</label>
        <button type="button" class="btn btn-primary btn-add-line"><i class="fa fa-plus"></i></button>
      </div>
      <div class="col-md-2">
        <label>&nbsp</label>
        <button type="button" class="btn btn-danger btn-remove-line"><i class="fa fa-times"></i></button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript" src="/assets/member/js/customer.js"></script>
<script type="text/javascript">
  $(".show_password").removeAttr("checked");
  $(".show_password").click(function()
  {
    if($(this).is(':checked'))
    {
      $(".mlm_password").attr("type","text");
    }
    else
    {
      $(".mlm_password").attr("type","password");
    }
  });
</script>