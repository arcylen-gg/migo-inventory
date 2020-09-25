<form class="global-submit form-horizontal" id="form_submit" role="form" action="{{$link_submit_here}}" method="post"> 
    <div class="modal-header">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="_shop_id" class="shop-id" value="{{ $shop_id or '' }}">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title"><i class="fa fa-cart-plus"></i> {{$page_title or ''}}</h4>
        <div>Enter the information of your items below</div>
    </div>
    
    <!-- ITEM TYPE PICKER -->
    <div class="item-type-picker {{ $item_picker }}">
        <div class="item-type-picker-container-clearfix">
            <div class="tp-picker {{isset($_settings['inventory']) ? ($_settings['inventory'] == 0 ? 'hidden' : '') : ''}}" type_id="1">
                <div class="row unselectable">
                    <div class="col-md-5 text-right">
                        <div class="tp-icon text-center"><i class="fa fa-cube"></i></div>
                    </div>
                    <div class="col-md-7">
                        <div class="tp-detail">
                            <div class="tp-title">Inventory</div>
                            <div class="tp-description">Products you buy and/or sell that you track quantities of.</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tp-picker {{isset($_settings['non-inventory']) ? ($_settings['non-inventory'] == 0 ? 'hidden' : '') : ''}}" type_id="2">
                <div class="row unselectable">
                    <div class="col-md-5 text-right">
                        <div class="tp-icon text-center"><i class="fa fa-dropbox"></i></div>
                    </div>
                    <div class="col-md-7">
                        <div class="tp-detail">
                            <div class="tp-title">Non-inventory</div>
                            <div class="tp-description">Products you buy and/or sell but don’t need to (or can’t) track quantities of, for example, nuts and bolts used in an installation.</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tp-picker {{isset($_settings['service']) ? ($_settings['service'] == 0 ? 'hidden' : '') : ''}}" type_id="3">
                <div class="row unselectable">
                    <div class="col-md-5 text-right">
                        <div class="tp-icon text-center"><i class="fa fa-train"></i></div>
                    </div>
                    <div class="col-md-7">
                        <div class="tp-detail">
                            <div class="tp-title">Service</div>
                            <div class="tp-description">Services that you provide to customers, for example, landscaping or tax preparation services.</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tp-picker {{isset($_settings['bundle']) ? ($_settings['bundle'] == 0 ? 'hidden' : '') : ''}}" type_id="4">
                <div class="row unselectable">
                    <div class="col-md-5 text-right">
                        <div class="tp-icon text-center"><i class="fa fa-cubes"></i></div>
                    </div>
                    <div class="col-md-7">
                        <div class="tp-detail">
                            @if($check_terms_to_be_used == 1)
                            <div class="tp-title">Set</div>
                            @else
                            <div class="tp-title">Bundle</div>
                            @endif
                            <div class="tp-description">A collection of products and/or services that you sell together, for example, a gift basket of fruit, cheese, and wine.</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tp-picker {{isset($_settings['item_kit']) ? ($_settings['item_kit'] == 0 ? 'hidden' : '') : ''}}" type_id="5">
                <div class="row unselectable">
                    <div class="col-md-5 text-right">
                        <div class="tp-icon text-center"><i class="fa fa-user-circle-o"></i></div>
                    </div>
                    <div class="col-md-7">
                        <div class="tp-detail">
                            <div class="tp-title">Membership Kit</div>
                            <div class="tp-description">Membership are items that are sometimes sold as Bundle or GC with reward features once sold to customers.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tp-picker {{isset($_settings['other_charge']) ? ($_settings['other_charge'] == 0 ? 'hidden' : '') : ''}}" type_id="6">
                <div class="row unselectable">
                    <div class="col-md-5 text-right">
                        <div class="tp-icon text-center"><i class="fa fa-dollar"></i></div>
                    </div>
                    <div class="col-md-7">
                        <div class="tp-detail">
                            <div class="tp-title">Other Charge</div>
                            <div class="tp-description">Eg. Delivery charges, Interest and etc.</div>
                        </div>
                    </div>
                </div>
            </div>
           
        </div>
    </div>

    <!-- ITEM ADD MAIN -->
    <div class="item-add-main {{$item_type['type_remove_main']}}" style="{{ $item_type['type_main'] }}">
        <div class="clearfix modal-body modallarge-body-layout"> 
            <div class="form-horizontal">
                <!-- BASIC INFORMATION -->
                <h4 class="section-title first">Basic Information</h4>
                <div class="form-group">
                    <div class="col-md-8">
                        <div class="form-group">
                            <div class="col-md-12">
                                <label for="basic-input">Item Name</label>
                                <input id="basic-input" value="{{ get_request_old($item_info, 'item_description', 'item_name') }}" class="form-control item-description" name="item_description" placeholder="">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <label for="basic-input">Item Code / SKU</label>
                                    <input id="basic-input" value="{{ get_request_old($item_info, 'item_sku') }}" class="form-control auto-generate-code" name="item_sku" placeholder="">
                                </div>
                                <div class="col-md-6">
                                    <label for="basic-input">Barcode</label>
                                    <input id="basic-input" value="{{ get_request_old($item_info, 'item_barcode') }}" class="form-control auto-generate-code" name="item_barcode" placeholder="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="form-group">
                            <input type="hidden" name="item_img" class="image-value" key="1">
                            <img src="{{ get_request_old($item_info, 'item_img') != '' ? get_request_old($item_info, 'item_img') : '/assets/front/img/default.jpg' }}" class="image-gallery image-put image-gallery-single" key="1" style="height: 150px;width: 100%;object-fit: contain">
                        </div>
                    </div>                    
                </div>
                <div class="form-group">
                    <div class="col-md-3">
                        <label for="basic-input">Category</label>
                        <select class="form-control select-category inventory" name="item_category">
                            @include("member.load_ajax_data.load_category", ['add_search' => "",'_category' => $_inventory,'type_id' => get_request_old($item_info, 'item_category', 'item_category_id')])
                        </select>
                    </div>
                    <div class="col-md-3 item-i-n-s-b">
                        <label for="basic-input">U/M</label>
                        <select class="form-control select-um inventory" name="item_measurement_id">
                            @include("member.load_ajax_data.load_unit_measurement", ['um_id' => get_request_old($item_info, 'item_measurement_id', 'item_measurement_id')])
                        </select>
                    </div>
                    <div class="col-md-3 item-type">
                        <input type="hidden" class="type-id orig-item-type-id" value="{{ get_request_old($item_info, 'item_type_id') }}" name="orig_item_type_id">
                        <label for="basic-input">Item Type</label>
                        <div class="input-group change-type">
                          <input  type="text" class="form-control input-sm" name="item_type_id" readonly value="{{ get_request_old($item_info, 'item_type_name') }}" aria-describedby="basic-addon1">
                          <span style="background-color: #eee; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-edit"></i></span>
                        </div>
                    </div>
                    <div class="col-md-3 item-i-n-s-b">
                        <label for="basic-input">Manufacturer</label>
                        <select class="form-control select-manufacturer" name="item_manufacturer_id">
                            @include("member.load_ajax_data.load_manufacturer", ['_manufacturer' => $_manufacturer,'manufacturer_id' => get_request_old($item_info, 'item_manufacturer_id')])
                        </select>
                    </div>
                </div>
                <div class="row row-accounts">
                    <div class="col-md-6 sales-module-account">
                        <div class="sale-module">
                            <h4 class="section-title" >Sale</h4>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label for="basic-input">Sale Price / Rate *</label>
                                    <input type="text" class="form-control text-right input-sm item-price-txt" placeholder="0.00" value="{{ currency('', get_request_old($item_info, 'item_price') != '' ? get_request_old($item_info, 'item_price') : 0 ) }}" name="item_price">
                                </div>
                                <div class="col-md-6">
                                    <label for="basic-input">Income Account</label>
                                    <select class="form-control select-income-account" name="item_income_account_id">
                                        @include("member.load_ajax_data.load_chart_account", ['add_search' => "", '_account' => $_income, 'account_id' => get_request_old($item_info, 'item_income_account_id') != '' ? get_request_old($item_info, 'item_income_account_id') : $default_income])
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label for="basic-input">Sales Information</label>
                                    <textarea class="form-control auto-generate" placeholder="Description on sales forms" name="item_sales_information">{{ get_request_old($item_info, 'item_sales_information') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 expense-module-account">
                        <!-- PURCHASE -->
                        <div class="purchase-module"> 
                            <h4 class="section-title item-i-n-s-b">Purchase</h4>
                            <div class="form-group">
                                <div class="col-md-6 for-non-service">
                                    <label for="basic-input"> Cost *</label>
                                    <div class="input-group">
                                      <input  type="text" class="form-control text-right item-cost item-cost-txt" placeholder="0.00" value="{{ currency('', get_request_old($item_info, 'item_cost') != '' ? get_request_old($item_info, 'item_cost') : 0) }}" aria-describedby="basic-addon1" name="item_cost">
                                      <span size="lg" link="/member/item/v2/cost?d={{get_request_old($item_info, 'item_id')}}" style="background-color: #eee; cursor: pointer;" class="input-group-addon popup" id="basic-addon1"><i class="fa fa-calculator"></i></span>
                                    </div>
                                </div>
                                <div class="col-md-6 expense-account">
                                    <label for="basic-input">Expense Account</label>
                                    <select class="form-control select-expense-account" name="item_expense_account_id">
                                        @include("member.load_ajax_data.load_chart_account", ['add_search' => "", '_account' => $_expense, 'account_id' => get_request_old($item_info, 'item_expense_account_id') != '' ? get_request_old($item_info, 'item_expense_account_id') : $default_expense])
                                    </select>
                                </div>
                            </div>
                            <div class="form-group purchase-info">
                                <div class="col-md-12">
                                    <label for="basic-input">Purchasing Information</label>
                                    <textarea class="form-control auto-generate" placeholder="Description on purchase forms" name="item_purchasing_information">{{ get_request_old($item_info, 'item_purchasing_information') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
   
                <!-- INVENTORY -->
                <div class="for-inventory" style="{{$item_type['inventory_type']}}">
                    <h4 class="section-title {{ $item_picker }}">Inventory</h4>
                    <div class="form-group {{ $item_picker }}">
                        <div class="col-md-4">
                            <label for="basic-input">Initial Quantity On Hand *</label>
                            <div class="input-group">
                              <input type="text" class="form-control" placeholder="0" name="item_initial_qty" aria-describedby="basic-addon1" value="0">
                              <span style="background-color: #eee; cursor: pointer;" class="input-group-addon" id="basic-addon1">Unit Conversion</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="basic-input">As Of Date</label>
                           <input id="basic-input" class="form-control" name="item_date_track" value="{{ date('m/d/Y') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="basic-input">Reorder Point</label>
                           <input id="basic-input" class="form-control" value="0" name="item_reorder_point">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-8">
                            <label for="basic-input">Asset Account</label>
                            <select class="form-control select-asset-account" name="item_asset_account_id">
                                @include("member.load_ajax_data.load_chart_account", ['add_search' => "", '_account' => $_asset, 'account_id' => $default_asset])
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="basic-input">Has Serial</label>
                            <select class="form-control select-has-serial" name="item_has_serial">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>
                    {{-- patrick --}}
                    @if($shop_id == 87)
                    <div class="form-group">
                        <div class="col-md-6">
                            <div class="tokens-holder">
                                <select class="form-control token-type" name="token_type">
                                    <option class="hidden">Select Token</option>
                                    @if(isset($token_name))
                                    @foreach($tokens as $token)
                                        @if($token->token_name == $token_name)
                                        <option value="{{ $token->token_id }}" selected="">{{ $token->token_name }}</option>
                                        @else
                                        <option value="{{ $token->token_id }}">{{ $token->token_name }}</option>
                                        @endif
                                    @endforeach
                                    @else
                                    @foreach($tokens as $token)
                                        <option value="{{ $token->token_id }}">{{ $token->token_name }}</option>
                                    @endforeach
                                    @endif
                                    
                                    {{-- <option value='add_new'>Add New Token</option> --}}
                                </select>
                            </div>
                            @if(isset($amount))
                            <input id="basic-input" type="text" value="{{ $amount }}" class="form-control" name="token_amount" placeholder="Amount">
                            @else
                            <input id="basic-input" type="text" value="0" class="form-control" name="token_amount" placeholder="Amount">
                            @endif
                        </div> 
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ITEM ADD BUNDLE -->
    <div class="item-bundle {{$item_type['type_remove_bundle']}}"  style="{{ $item_type['type_bundle_main'] }}">
        <div class="clearfix modal-body modallarge-body-layout"> 
            <div class="form-horizontal">
                <div class="form-group">
                    <div class="col-md-8">
                        <div class="form-group">
                            <div class="col-md-12">
                               <label for="basic-input">Item Name</label>
                                <input id="basic-input" value="{{ get_request_old($item_info, 'item_description', 'item_name') }}" class="form-control item-description" name="item_description" placeholder="">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <label for="basic-input">Item Code / SKU</label>
                                    <input id="basic-input" value="{{ get_request_old($item_info, 'item_sku') }}" class="form-control auto-generate-code" name="item_sku" placeholder="">
                                </div>
                                <div class="col-md-6">
                                    <label for="basic-input">Barcode</label>
                                    <input id="basic-input" value="{{ get_request_old($item_info, 'item_barcode') }}" class="form-control auto-generate-code" name="item_barcode" placeholder="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="form-group">
                            <input type="hidden" name="item_img" class="image-value" key="1">
                            <img src="{{ get_request_old($item_info, 'item_img') != '' ? get_request_old($item_info, 'item_img') : '/assets/front/img/default.jpg' }}" class="image-gallery image-put image-gallery-single" key="1" style="height: 150px;width: 100%;object-fit: contain">
                        </div>
                    </div>                     
                </div>
                <div class="form-group">
                    <div class="col-md-3">
                        <label for="basic-input">Sale Price / Rate *</label>
                        <input type="text" class="form-control text-right item-price-txt" placeholder="0.00" value="{{ currency('', get_request_old($item_info, 'item_price') != '' ? get_request_old($item_info, 'item_price') : 0 ) }}" name="item_price">
                    </div>
                    <div class="col-md-3">
                        <label for="basic-input">Income Account</label>
                        <select class="form-control select-income-account" name="item_income_account_id">
                            @include("member.load_ajax_data.load_chart_account", ['add_search' => "", '_account' => $_income, 'account_id' => get_request_old($item_info, 'item_income_account_id') != '' ? get_request_old($item_info, 'item_income_account_id') : $default_income])
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="basic-input">Category</label>
                        <select class="form-control select-category inventory" name="item_category">
                            @include("member.load_ajax_data.load_category", ['add_search' => "",'_category' => $_inventory,'type_id' => get_request_old($item_info, 'item_category', 'item_category_id')])
                        </select>
                    </div>
                    <div class="col-md-3 item-type">
                        <input type="hidden" class="type-id orig-item-type-id" value="{{ get_request_old($item_info, 'item_type_id') }}" name="orig_item_type_id">
                        <label for="basic-input">Item Type</label>
                        <div class="input-group change-type">
                          <input  type="text" class="form-control input-sm" name="item_type_name" readonly value="{{ get_request_old($item_info, 'item_type_name') }}" aria-describedby="basic-addon1">
                          <span style="background-color: #eee; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-edit"></i></span>
                        </div>
                    </div>
                </div>

                <div class="form-group for-membership-kit" style="{{$item_type['membership_kit_type']}}">
                    <div class="col-md-3">
                        <label for="basic-input">Reorder Point</label>
                        <input type="text" class="form-control " placeholder="0" value="{{ get_request_old($item_info, 'item_reorder_point') }}" name="item_reorder_point">
                    </div>
                    <div class="col-md-3">
                        <label for="basic-input">Asset Account</label>
                        <select class="form-control select-asset-account" name="item_asset_account_id">
                            @include("member.load_ajax_data.load_chart_account", ['add_search' => "", '_account' => $_asset, 'account_id' => get_request_old($item_info, 'item_asset_account_id') != '' ? get_request_old($item_info, 'item_asset_account_id') : $default_asset])
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="basic-input">Membership</label>
                        <select class="form-control" name="membership_id">
                            @include("member.load_ajax_data.load_membership", ['membership_id' => get_request_old($item_info, 'membership_id')])
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="basic-input">GC Earnings</label>
                        <input type="text" class="form-control" name="gc_earning" value="{{ get_request_old($item_info, 'gc_earning') }}">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <label for="basic-input">Item Name</label>
                        <textarea class="form-control auto-generate" placeholder="Description on item" name="item_sales_information">{{ get_request_old($item_info, 'item_sales_information') }}</textarea>
                    </div>
                </div>

                <hr>

                <div class="form-group">
                    <div class="col-md-12">
                        <div class="clearfix" style="margin-bottom: 10px;">
                            <div class="pull-left text-bold" style="font-size: 16px; padding-top: 10px;"><i class="fa fa-shopping-cart"></i> INCLUSIVE ITEMS</div>
                            <button type="button" onclick="action_load_link_to_modal('/member/item/choose')" class="pull-right btn btn-custom-white"><i class="fa fa-plus"></i> Add Item</button>
                        </div>
                        <table class="table table-bordered table-striped table-condensed">
                            <thead style="text-transform: uppercase">
                                <tr>
                                    <th class="text-center">Item SKU</th>
                                    <th class="text-center">Item Cost</th>
                                    <th class="text-center">Item Price</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-center" width="50px"></th>
                                </tr>
                            </thead>
                            <tbody class="choose-item-list">
                                @include('member.load_ajax_data.load_choose_item')
                                <!--
                                <tr>
                                    <td colspan="5" class="text-center">NO ITEM YET</td>
                                </tr>
                                <tr>
                                    <td class="text-center">IPHONE6PLUSGOLD</td>
                                    <td class="text-center">PHP 1,500.00</td>
                                    <td class="text-center">PHP 1,000.00</td>
                                    <td class="text-center">5</td>
                                </tr>
                                -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
        <button {{ $item_button }} class="btn btn-primary btn-custom-primary add-submit-button" type="button"><i class="fa fa-save"></i> Save Item</button>
    </div>
</form>
<script type="text/javascript" src="/assets/member/js/item/item_add.js"></script>
{{-- patrick --}}
<script type="text/javascript" src="/assets/member/js/item/token.js"></script>