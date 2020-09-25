<form class="z-index-1000 global-submit" action="/member/customer/createcustomer" method="POST">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title layout-modallarge-title">Create new customer</h4>
  </div>
  <div class="modal-body modallarge-body-layout background-white">   
    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
    <input type="hidden" name="customer_status" value="{{$customer_status or 'approved'}}">
    <div class="form-horizontal">

    <div class="form-group">
      <div class="col-md-6">
        <div class="col-md-2 padding-lr-1">
          <label>Title</label>
          <input type="text" name="title" class="form-control auto-name title margin-l-0"/>
        </div>
        <div class="col-md-3 padding-lr-1">
          <label>First name</label>
          <input type="text" name="first_name" class="form-control auto-name first_name" value="{{$value or ''}}" />
        </div>
        <div class="col-md-3 padding-lr-1">
          <label>Middle name</label>
          <input type="text" name="middle_name" class="form-control auto-name middle_name"/>
        </div>
        <div class="col-md-3 padding-lr-1">
          <label>Last name</label>
          <input type="text" name="last_name" class="form-control auto-name last_name"/>
        </div>
        <div class="col-md-1 padding-lr-1">
          <label>Suffix</label>
          <input type="text" name="suffix" class="form-control auto-name suffix"/>
        </div>
      </div>
      <div class="col-md-6">
        <label for="">Email</label>
        <input type="email" name="email" class="form-control"/>
      </div>
    </div>
    <div class="form-group">
      <div class="col-md-6">
        <label for="">Company</label>
        <input type="text" name="company" class="form-control">
      </div>
      <div class="col-md-6">
        <div class="col-md-4 padding-lr-1">
          <label for="">Phone</label>
          <input type="text" name="phone" class="form-control" required/>
        </div>
        <div class="col-md-4 padding-lr-1">
          <label for="">Mobile</label>
          <input type="text" name="mobile" class="form-control"/>
        </div>
        <div class="col-md-4 padding-lr-1">
          <label for="">Fax</label>
          <input type="text" name="fax" class="form-control"/>
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="col-md-6">
        <label for="">Display name as</label>
        <div class="input-group">
          <input type="text" name="display_name_as" class="form-control txt-display-name"/>
          <span class="input-group-btn">
            <button class="btn display-name-drop-down btn-toggle-custom btn-custom-white-gray" type="button" data-target=".drop-down-display-name" container=""><i class="fa fa-caret-down" aria-hidden="true"></i></button>
          </span>
        </div>
        <div class="custom-drop-down list-group drop-down-display-name">
         
        </div>
        <div class="custom-drop-down"></div>
      </div>
      <div class="col-md-6">
        <div class="col-md-4 padding-lr-1">
          <label for="">Other</label>
          <input type="text" name="other" class="form-control"/>
        </div>
        <div class="col-md-8 padding-lr-1">
          <label for="">Website</label>
          <input type="text" name="website" class="form-control"/>
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="col-md-6">
        <label for=""><b>Print on as check as</b></label>&nbsp;
        <div class="checkbox display-inline-block"><label for=""><input type="checkbox" name="chck_print_on_as" class="checkbox-toggle-rev check-print-name-as" data-target=".display-name-check" checked="true"/>Use display name</label></div>
        <input type="text" name="print_on_as_check_as" class="form-control display-name-check"/>
      </div>
      <div class="col-md-6">
        <div class="checkbox"><label for=""><input type="checkbox" name="chck_is_sub_customer" class="checkbox-toggle" data-target=".sub-customer"/>Is sub-customer</label></div>
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
            <input type="text" class="form-control text-right txt-budget-price" placeholder="0.00" name="fix_monthly_budget">
          </div>
          <div class="col-md-6">
            <label>Adjusted Monthly Budget</label>
            <input type="text" class="form-control text-right txt-budget-price" placeholder="0.00" name="adjusted_monthly_budget">
          </div>
        </div>
      @endif
    @endif
    <!-- MIGO --->
    @if(isset($migo_customization))
      @if($migo_customization)
        <div class="form-group">
          <div class="col-md-6">
            <label>Customer Category</label>
            <select class="form-control" name="customer_category">
              <option value="new-client">New Client</option>
              <option value="regular">Regular</option>
              <option value="former">Former</option>
              <option value="employee">Employee</option>
              <option value="do-not-call">Do not Call</option>
            </select>
          </div>
          <div class="col-md-6">
            <label>Customer Type</label>
            <select class="form-control" name="customer_category_type">
              <option value="non-vip">Non VIP</option>
              <option value="vip">VIP</option>
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

        </ul>
        
        <div class="tab-content tab-content-custom">
          <div id="address" class="tab-pane fade in active">
            <br>
           <div class="form-horizontal">
              <div class="form-group">
                <div class="col-md-6">
                  <label for=""><b>Billing address</b></label>
                  <div class="form-group">
                    <div class="col-md-12">
                      <textarea name="billing_street"  rows="2" class="form-control billing-address bill-street" data-target=".shipping_street" placeholder="Street"></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-6">
                      <input type="text" name="billing_city" class="form-control billing-address bill-city" data-target=".shipping_city" placeholder="City/Town" required>
                    </div>
                    <div class="col-md-6">
                      <input type="text" name="billing_state" class="form-control billing-address bill-state" data-target=".shipping_state" placeholder="State">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-6">
                      <input type="text" name="billing_zipcode" class="form-control billing-address bill-zipcode" data-target=".shipping_zipcode" placeholder="Zip code">
                    </div>
                    <div class="col-md-6">
                      <select class="form-control billing-address bill-country" name="billing_country" data-target=".shipping_country">
                        @foreach($_country as $country)
                        <option value="{{$country->country_id}}" {{$country->country_id == '420' ? 'selected' : ''}}>{{$country->country_name}}</option>
                        @endforeach
                      </select>
                      <!--<input type="text" name="billing_country" class="form-control billing-address" data-target=".shipping_country" placeholder="Country">-->
                    </div>
                  </div>
                </div>
                <div class="col-md-6 shipping-container">
                  <label for=""><b>Shipping address</b></label>
                  <div class="checkbox display-inline-block"><label for=""><input type="checkbox" name="chk_same_shipping_address" checked="true" class="checkbox-toggle-rev chk_same_shipping_address same_as_billing" data-target=".same-billing"/>Same as billing address</label></div>
                  <div class="form-group">
                    <div class="col-md-12">
                      <textarea name="shipping_street" rows="2" class="form-control shipping_street same-billing ship-street" placeholder="Street"></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-6">
                      <input type="text" name="shipping_city" class="form-control shipping_city same-billing ship-city" placeholder="City/Town">
                    </div>
                    <div class="col-md-6">
                      <input type="text" name="shipping_state" class="form-control shipping_state same-billing ship-state" placeholder="State">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-6">
                      <input type="text" name="shipping_zipcode" class="form-control shipping_zipcode same-billing ship-zipcode" placeholder="Zip code">
                    </div>
                    <div class="col-md-6">
                      <select class="form-control shipping_country same-billing ship-country" name="shipping_country">
                        @foreach($_country as $country)
                        <option value="{{$country->country_id}}" {{$country->country_id == '420' ? 'selected' : ''}}>{{$country->country_name}}</option>
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
                  <textarea class="form-control" rows="5" name="notes"></textarea>
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
                  <input type="text" name="" class="form-control" name="tax_resale_no"/>
                </div>
                <div class="col-md-6">
                  <label for=""><b>TIN</b></label>
                  <input type="text" name="tin_number" class="form-control" name="tin_number"/>
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
                        <input type="text" name="payment_method" class="form-control payment_method" placeholder="Enter text"/>
                        <input type="hidden" name="hidden_payment_method" class="hidden_payment_method"/>
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
                        <option value="{{$delivery->delivery_method_id}}">{{$delivery->delivery_method}}</option>
                        @endforeach
                       
                      </select>
                      <!--<button class="btn btn-custom-white-gray" type="button"><span>None</span><i class="fa fa-caret-down pull-right-20"></i></button>-->
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <div class="col-md-6">
                      <label for=""><b>Terms</b></label>
                      <div class="input-group">
                        <input type="text" class="form-control txt_terms" name="txt_terms">
                        <input type="hidden" class="hidden_terms" name="hidden_terms">
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
                      <input type="number" class="form-control text-right" name="opening_balance" step="any">
                    </div>
                    <div class="col-md-6">
                      <label for=""><b>as of</b></label>
                      
                      <input type="text" class="form-control indent-13 datepicker" name="date_as_of">
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
                    <!--<div class="form-group file-1">-->
                    <!--  <div class="col-md-8">-->
                    <!--    <span>File name here</span>-->
                    <!--  </div>-->
                    <!--  <div class="col-md-4 margin-top-10px">-->
                    <!--    <div class="custom-progress-container">-->
                    <!--      <div class="custom-progress"></div>-->
                    <!--    </div>-->
                    <!--  </div>-->
                    <!--</div>-->
                  </div>
                </div>
                @if($proposal_number)
                <div class="col-md-6">
                  <div class="proposal-div">
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
          <div id="mlm" class="tab-pane fade hidden">
            <br>
            <div class="form-horizontal">
              <div class="form-group">
                <div class="col-md-12">
                  <div class="col-md-12">
                    <div class="checkbox display-inline-block">
                      <label for="">
                        <input type="checkbox" name="ismlm" class="ismlm" value="1" />Use In MLM
                        
                        </label>
                      </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="col-md-6">
                    <label>Username</label>
                    <input type="text" class="form-control mlm_username" name="mlm_username" readonly>
                  </div>
                  <div class="col-md-6">
                    <label>Password</label>
                    <input type="password" class="form-control mlm_password" name="mlm_password" readonly>
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
                </div>
                <div class="col-md-12">
                  <div class="col-md-6">
                    <label>Select Warehouse</label>
                    <select class="form-control" name="stockist_warehouse_id">
                      @foreach($_warehouse as $warehouse)
                      <option value="{{$warehouse->warehouse_id}}">{{$warehouse->warehouse_name}}</option>
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
    <!--<button type="button" class="btn btn-custom-red-white btn-del-modallarge" data-url="" data-value="">Delete</button>-->
    <button class="btn btn-custom-primary btn-save-modallarge" type="submit" data-url="">Save customer</button>
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