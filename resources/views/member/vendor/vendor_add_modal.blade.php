<form class="global-submit" action="{{ isset($vendor) ? '/member/vendor/edit/'.$vendor->vendor_id : '/member/vendor/add' }}" method="post"> 
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title layout-modallarge-title">Create new vendor</h4>
  </div>
  <div class="modal-body modallarge-body-layout background-white">   
    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
    <div class="form-horizontal">
    <div class="form-group">
      <div class="col-md-6">
        <div class="col-md-2 padding-lr-1">
          <label>Title</label>
          <input type="text" name="vendor_title_name" class="form-control input-sm auto-name title margin-l-0" value="{{$vendor->vendor_title_name or ''}}"/>
        </div>
        <div class="col-md-3 padding-lr-1">
          <label>First name</label>
          <input type="text" name="vendor_first_name" class="form-control input-sm auto-name first_name" value="{{$vendor->vendor_first_name or ''}}" />
        </div>
        <div class="col-md-3 padding-lr-1">
          <label>Middle name</label>
          <input type="text" name="vendor_middle_name" class="form-control input-sm auto-name middle_name" value="{{$vendor->vendor_middle_name or ''}}"/>
        </div>
        <div class="col-md-3 padding-lr-1">
          <label>Last name</label>
          <input type="text" name="vendor_last_name" class="form-control input-sm auto-name last_name" value="{{$vendor->vendor_last_name or ''}}"/>
        </div>
        <div class="col-md-1 padding-lr-1">
          <label>Suffix</label>
          <input type="text" name="vendor_suffix_name" class="form-control input-sm auto-name suffix" value="{{$vendor->vendor_suffix_name or ''}}"/>
        </div>
      </div>
      <div class="col-md-6">
        <label for="">Email</label>
        <input type="email" name="vendor_email" class="form-control input-sm" value="{{$vendor->vendor_email or ''}}"/>
      </div>
    </div>
    <div class="form-group">
      <div class="col-md-6">
        <label for="">Company</label>
        <input type="text" name="vendor_company" class="form-control input-sm" value="{{$vendor->vendor_company or ''}}" >
      </div>
      <div class="col-md-6">
        <div class="col-md-4 padding-lr-1">
          <label for="">Phone</label>
          <input type="text" name="ven_info_phone" class="form-control input-sm" value="{{$vendor->ven_info_phone or ''}}" required/>
        </div>
        <div class="col-md-4 padding-lr-1">
          <label for="">Mobile</label>
          <input type="text" name="ven_info_mobile" class="form-control input-sm" value="{{$vendor->ven_info_mobile or ''}}"/>
        </div>
        <div class="col-md-4 padding-lr-1">
          <label for="">Fax</label>
          <input type="text" name="ven_info_fax" class="form-control input-sm" value="{{$vendor->ven_info_fax or ''}}"/>
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="col-md-6">
        <label for="">Display name as</label>
        <div class="input-group">
          <input type="text" name="ven_info_display_name" class="form-control input-sm txt-display-name" value="{{$vendor->ven_info_display_name or ''}}"/>
          <span class="input-group-btn">
            <button class="btn display-name-drop-down btn-toggle-custom btn-custom-white-gray" type="button" data-target=".drop-down-display-name" container=""><i class="fa fa-caret-down" aria-hidden="true"></i></button>
          </span>
        </div>
        <div class="custom-drop-down list-group drop-down-display-name">
         
        </div>
      </div>
      <div class="col-md-6">
        <div class="col-md-4 padding-lr-1">
          <label for="">Other</label>
          <input type="text" name="ven_info_other_contact" class="form-control input-sm" value="{{$vendor->ven_info_other_contact or ''}}" />
        </div>
        <div class="col-md-8 padding-lr-1">
          <label for="">Website</label>
          <input type="text" name="ven_info_website" class="form-control input-sm" value="{{$vendor->ven_info_website or ''}}"/>
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="col-md-6">
        <label for=""><b>Print on as check as</b></label>&nbsp;
        <div class="checkbox display-inline-block"><label for=""><input type="checkbox" name="chck_print_on_as" class="checkbox-toggle-rev check-print-name-as" data-target=".display-name-check" checked="true"/>Use display name</label></div>
        <input type="text" name="print_on_as_check_as" class="form-control input-sm display-name-check" value="{{$vendor->ven_info_print_name or ''}}"/>
      </div>
    </div>
    <div class="form-group">
      <div class="col-md-12">
        <ul class="nav nav-tabs nav-tabs-custom">
          <li class="active"><a data-toggle="tab" href="#address">Address</a></li>
          <li><a data-toggle="tab" href="#bank-record">Bank Records</a></li>
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
                <div class="col-md-6 billing-container">
                  <label for=""><b>Billing address</b></label>
                  <div class="form-group">
                    <div class="col-md-12">
                      <textarea name="ven_billing_street" rows="2" class="form-control input-sm billing-address bill-street" data-target=".shipping_street" placeholder="Street" >{{$vendor->ven_billing_street or ''}}</textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-6">
                      <input type="text" name="ven_billing_city" class="form-control input-sm billing-address bill-city" data-target=".shipping_city" placeholder="City/Town" value="{{$vendor->ven_billing_city or ''}}" required>
                    </div>
                    <div class="col-md-6">
                      <input type="text" name="ven_billing_state" class="form-control input-sm billing-address bill-state" data-target=".shipping_state" placeholder="State"value="{{$vendor->ven_billing_state or ''}}">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-6">
                      <input type="text" name="ven_billing_zipcode" class="form-control input-sm billing-address bill-zipcode" data-target=".shipping_zipcode" placeholder="Zip code" value="{{$vendor->ven_billing_zipcode or ''}}">
                    </div>
                    <div class="col-md-6">
                      <select class="form-control input-sm billing-country select-country bill-country" name="ven_billing_country_id" data-target=".shipping_country">
                        @foreach($_country as $country)
                        <option value="{{$country->country_id}}" {{isset($vendor) ? $vendor->ven_billing_country_id == $country->country_id ? 'selected' : '' : ''}}>{{$country->country_name}}</option>
                        @endforeach
                      </select>
                      <!--<input type="text" name="billing_country" class="form-control input-sm billing-address" data-target=".shipping_country" placeholder="Country">-->
                    </div>
                  </div>
                </div>
                <div class="col-md-6 shipping-container">
                  <label for=""><b>Shipping address</b></label>
                  <div class="checkbox display-inline-block"><label for=""><input type="checkbox" name="same_as_billing" class="same_as_billing" data-target=".same-billing" checked="true" {{isset($vendor) ? '' : 'selected'}}/>Same as billing address</label></div>
                  <div class="form-group">
                    <div class="col-md-12">
                      <textarea name="ven_shipping_street" rows="2" class="shipping_street form-control input-sm ship ship-street street" placeholder="Street">{{$vendor->ven_shipping_street or ''}}</textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-6">
                      <input type="text" name="ven_shipping_city" class="shipping_city form-control input-sm ship ship-city" placeholder="City/Town" value="{{$vendor->ven_shipping_city or ''}}">
                    </div>
                    <div class="col-md-6">
                      <input type="text" name="ven_shipping_state" class="shipping_state form-control input-sm ship ship-state" placeholder="State" value="{{$vendor->ven_shipping_state or ''}}">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-6">
                      <input type="text" name="ven_shipping_zipcode" class="shipping_zipcode form-control input-sm ship ship-zipcode" placeholder="Zip code" value="{{$vendor->ven_shipping_zipzode or ''}}">
                    </div>
                    <div class="col-md-6">
                      <select class="form-control input-sm shipping_country ship select-country ship-country" name="ven_shipping_country_id">
                        @foreach($_country as $country)
                        <option value="{{$country->country_id}}" {{isset($vendor) ? $vendor->ven_billing_country_id == $country->country_id ? 'selected' : '' : ''}}>{{$country->country_name}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
              </div>
           </div>
          </div>
          <div id="bank-record" class="tab-pane fade">
            <br>
            <div class="form-horizontal">
              <div class="form-group">
                <div class="col-md-12">
                  <label for=""><b>Bank Records</b></label>
                </div>
              </div>
              <div class="form-group">
                <div class="form-group-bank-records">
                  @if(isset($_bank_record))
                  @foreach($_bank_record as $record)
                  <div class="col-md-12">
                    <div class="col-md-5">
                      <label>Bank Name</label>
                      <input type="text" class="form-control" name="vendor_account_name[]" value="{{$record->vendor_account_name}}">
                    </div>
                    <div class="col-md-5">
                      <label>Account Number</label>
                      <input type="text" class="form-control" name="vendor_account_number[]" value="{{$record->vendor_account_number}}">
                    </div>
                    <div class="col-md-1">
                      <label>&nbsp</label>
                      <button type="button" class="btn btn-primary btn-add-line"><i class="fa fa-plus"></i></button>
                    </div>
                    <div class="col-md-1">
                      <label>&nbsp</label>
                      <button type="button" class="btn btn-danger btn-remove-line"><i class="fa fa-times"></i></button>
                    </div>
                  </div>
                  @endforeach
                  @endif
                  <div class="col-md-12">
                    <div class="col-md-5">
                      <label>Bank Name</label>
                      <input type="text" class="form-control" name="vendor_account_name[]" >
                    </div>
                    <div class="col-md-5">
                      <label>Account Number</label>
                      <input type="text" class="form-control" name="vendor_account_number[]" >
                    </div>
                    <div class="col-md-1">
                      <label>&nbsp</label>
                      <button type="button" class="btn btn-primary btn-add-line"><i class="fa fa-plus"></i></button>
                    </div>
                    <div class="col-md-1">
                      <label>&nbsp</label>
                      <button type="button" class="btn btn-danger btn-remove-line"><i class="fa fa-times"></i></button>
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
                  <textarea class="form-control input-sm" rows="5" name="notes">{{$vendor->ven_info_notes or ''}}</textarea>
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
                  <input type="text" class="form-control input-sm" name="tax_resale_no" value="{{$vendor->ven_info_tax_no or ''}}"/>
                </div>
                <div class="col-md-6">
                  <label for=""><b>TIN</b></label>
                  <input type="text" class="form-control input-sm" name="tin_number" value="{{$vendor->ven_info_tin_no or ''}}"/>
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
                    <div class="col-sm-6">  
                      <label>Terms</label>
                      <select class="form-control input-sm new droplist-terms" name="ven_info_terms">
                          @include("member.load_ajax_data.load_terms", ['terms_id' => isset($vendor) ? $vendor->inv_terms_id : ''])
                      </select>
                    </div>
                  </div>
                  <!-- <div class="form-group">
                    <div class="col-md-6">
                      <label for=""><b>Opening balance</b></label>
                      <input type="number" class="form-control input-sm text-right" name="opening_balance" step="any" value="{{$vendor->ven_info_opeining_balance or ''}}">
                    </div>
                    <div class="col-md-6">
                      <label for=""><b>as of</b></label>
                      <input type="text" class="form-control input-sm indent-13 datepicker" name="date_as_of" value="{{$vendor->ven_info_balance_date or ''}}">
                      <i class="fa fa-calendar pos-absolute top-34 margin-left-6 color-dark-gray" aria-hidden="true"></i>
                    </div>
                  </div> -->
                </div>
              </div>
            </div>
          </div>
          <div id="attachment" class="tab-pane fade">
            <br>
            <div class="form-horizontal">
              <div class="form-group">
                <div class="col-md-12">
                  <label for=""><b><i class="fa fa-paperclip" aria-hidden="true"></i>&nbsp;Attachments</b></label><span class="margin-left-30">Maximum size: 25MB</span>
                  <div class="border-gray border-gray-dotted padding-10 div-attachment form-horizontal">
                    
                    <div class="text-center color-gray div-file-input form-group">
                      <span><i>Click here to upload file</i></span>
                      <input type="file" name="" id="attachment_file" class="hidden"/>
                    </div>
                    @if(isset($_attachment))
                    @foreach($_attachment as $attachment)
                    <div class="form-group file-{{$attachment->vendor_attachment_id}}">
                      <div class="col-md-8">
                        <a href="/member/vendor/download-file/{{Crypt::encrypt($attachment->vendor_attachment_id)}}">{{$attachment->vendor_attachment_name}}</a>
                      </div>
                      <div class="col-md-4 file-operation-">
                        <a href="javascript:" class="pull-right remove-upload" data-path="{{$attachment->vendor_attachment_path}}" data-value="{{$attachment->vendor_attachment_id}}" data-target=".file-{{$attachment->vendor_attachment_id}}"><i class="fa fa-times" aria-hidden="true"></i></a>
                        <input type="hidden" value="{{$attachment->vendor_attachment_path}}" name="fileurl[]">
                        <input type="hidden" value="{{$attachment->vendor_attachment_name}}" name="filename[]">
                        <input type="hidden" value="{{$attachment->vendor_attachment_name}}" name="mimetype[]">
                      </div>
                    </div>
                    @endforeach
                    @endif
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
    <button class="btn btn-custom-primary btn-save-modallarge" type="submit" data-url="">Save Vendor</button>
  </div>
</form>
<div class="div-script">
  <div class="body-script hidden">
    <div class="col-md-12">
        <div class="col-md-5">
          <label>Account Name</label>
          <input type="text" class="form-control" name="vendor_account_name[]" value="">
        </div>
        <div class="col-md-5">
          <label>Account Number</label>
          <input type="text" class="form-control" name="vendor_account_number[]" value="">
        </div>
        <div class="col-md-1">
          <label>&nbsp</label>
          <button type="button" class="btn btn-primary btn-add-line"><i class="fa fa-plus"></i></button>
        </div>
        <div class="col-md-1">
          <label>&nbsp</label>
          <button type="button" class="btn btn-danger btn-remove-line"><i class="fa fa-times"></i></button>
        </div>
    </div>
  </div>
</div>
<script type="text/javascript" src="/assets/member/js/vendor.js"></script>