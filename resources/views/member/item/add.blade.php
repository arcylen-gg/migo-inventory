
  <!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title layout-modallarge-title item_title">Item Type</h4> 
    <input type="hidden" id="item_type_container" value="{{isset($data['type_of_item']) ? $data['type_of_item'] : ''}}">
</div>

<div class="clearfix modal-body max-450 modallarge-body-layout background-white form-horizontal menu_container">
    <div class="col-md-3">
        <buton type="button" class="form-control btn btn-custom-primary" id="inventory_type">Inventory</buton>
    </div>
    <div class="col-md-3">
        <buton type="button" class="form-control btn btn-custom-primary" id="noninventory_type">Non-Inventory</buton>
    </div>
    <div class="col-md-3">
        <buton type="button" class="form-control btn btn-custom-primary" id="service_type">Service</buton>
    </div>
    <div class="col-md-3">
        <buton type="button" class="form-control btn btn-custom-primary" id="bundle_type">Bundle</buton>
    </div>
</div>

<form  action="/member/item/add_submit?item_type=inventory" class="global-submit form_two" id="modal_form_large"  enctype="multipart/form-data" method="post" type_of_item="inventory_type">
  <div class="clearfix modal-body modallarge-body-layout background-white inventory_type" style="display:none;"> 
     <!-- INVENTORY -->
    <div class="form-horizontal">
            <div class="col-md-12">
            <!-- class="global-submit form_one" -->
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <div class="form-group">
                        <div class="col-md-8">
                            <label>Name *</label>
                            <!-- <input type="text" class="form-control" id="item_name" value="{{isset($data['item_name']) ? $data['item_name'] : ''}}" name="item_name" required> -->
                            <textarea required class="form-control input-sm item-name" name="item_name" id="item_name">{{isset($data['item_name']) ? $data['item_name'] : ''}}</textarea><br>
                            <label>Sales information</label>
                            <textarea class="form-control input-sm" id="item_sales_information" name="item_sales_information" placeholder="Description on sales forms" >{{isset($data['item_sales_information']) ? $data['item_sales_information'] : ''}}</textarea>
                        </div>
                        <div class="col-md-4 text-center">
                            <input type="hidden" name="item_img" class="image-value" key="1" required>
                            <img class="img-responsive img-src" key="1" style="width: 100%; object-fit: contain;" src="/assets/front/img/default.jpg">
                            <button type="button" class="btn btn-primary image-gallery image-gallery-single" key="1" style="margin-top: 15px;">Upload Image</button>
                        </div>
                    </div>
                    <div class="form-group">       
                        <div class="col-md-4">
                            <label>SKU</label>
                            <input type="text" class="form-control input-sm item-sku" id="item_sku" value="{{isset($data['item_sku']) ? $data['item_sku'] : ''}}" name="item_sku" >
                        </div>
                        <div class="col-md-4">
                            <label>Barcode</label>
                            <input type="text" class="form-control input-sm" id="item_barcode" value="{{isset($data['item_barcode']) ? $data['item_barcode'] : ''}}" name="item_barcode">
                        </div>
                        <div class="col-md-4">
                            <label>Category *</label>
                            <select name="item_category_id" cat_type="inventory" class="form-control drop-down-category inventory" id="item_category_id" required>
                             @include("member.load_ajax_data.load_category", ['add_search' => "",'_category' => $_inventory])
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <label>Unit of Measure</label>
                            <select class="form-control input-sm measure_container drop-down-um" add="add" name="item_measurement_id">
                                @include("member.load_ajax_data.load_unit_measurement")
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Packing Size</label>
                            <input type="text" name="packing_size" class="form-control input-sm">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-4">    
                            <label>Initial quantity on hand *</label>                    
                            <div class="col-md-6">
                                <input type="number" class="form-control input-sm" id="item_quantity" value="{{isset($data['item_quantity']) ? $data['item_quantity'] : ''}}" name="item_quantity" required>
                            </div>              
                            <div class="col-md-6">
                                <input type="hidden" name="initial_qty" value="1" class="unit-qty">
                                <select class="droplist-um form-control">
                                     @include("member.load_ajax_data.load_um_multi")
                                </select>
                            </div>   
                        </div>                  
                        <div class="col-md-4">
                            <label>Reorder Point </label>
                            <input type="text" class="form-control input-sm" id="item_reorder_point" value="{{isset($data['item_reorder_point']) ? $data['item_reorder_point'] : ''}}" name="item_reorder_point" >
                        </div> 
                        <div class="col-md-4">
                            <label>As of date</label>
                            <input type="text" class="form-control input-sm datepicker" id="item_date_tracked" name="item_date_tracked" value="{{date('m/d/y')}}" >
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <label>Manufacturer</label>
                            <select class="form-control input-sm drop-down-manufacturer" name="item_manufacturer_id">
                                @include("member.load_ajax_data.load_manufacturer")
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Inventory Asset Account *</label>
                            <select name="item_asset_account_id" class="drop-down-coa form-control" id="item_asset_account_id" required>
                               @include("member.load_ajax_data.load_chart_account", ['add_search' => "", '_account' => $_asset, 'account_id' => $default_asset])
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <label>Sales price/rate *</label>
                            <div class="row">
                                <div class="col-md-8">    
                                    <input type="text" class="form-control number-input input-sm" id="item_price" value="{{isset($data['item_price']) ? $data['item_price'] : ''}}" name="item_price" required>
                                </div>
                                <div class="col-md-1">
                                    per <span class="abbreviation"></span>
                                </div>
                            </div>
                        </div>                 
                        <div class="col-md-6">
                            <label>Income Account *</label>
                            <select name="item_income_account_id" class="drop-down-coaster form-control" id="item_income_account_id" required>   
                               @include("member.load_ajax_data.load_chart_account", ['add_search' => "", '_account' => $_income, 'account_id' => $default_income])
                            </select>
                        </div>
                    </div>
                    <div class="form-group">                   
                        <div class="col-md-4">
                            <label>Promo Price</label>
                            <input type="text" class="form-control input-sm" name="promo_price"  value="{{isset($data['item_discount_value']) ? $data['item_discount_value'] : ''}}">                            
                        </div>
                        <div class="col-md-4">
                            <label>Promo Start Date</label>
                            <input type="text" class="form-control datepicker input-sm" name="start_promo_date" value="{{isset($data['item_discount_date_start']) ? $data['item_discount_date_start'] : ''}}" >
                        </div>
                        <div class="col-md-4">
                            <label>Promo End Date</label>
                            <input type="text" class="form-control datepicker input-sm" name="end_promo_date" value="{{isset($data['item_discount_date_end']) ? $data['item_discount_date_end'] : ''}}" >
                        </div>
                    </div>
                    <!-- <div class="col-md-12" style="border-bottom: solid 1px #A CACAC; padding-top:20px;margin-bottom: 5px"></div> -->
                    <div class="form-group">
                        <div class="col-md-12">
                            <label>Purchasing information</label>
                            <textarea class="form-control input-sm" id="item_purchasing_information" name="item_purchasing_information" placeholder="Description on purchase forms" >{{isset($data['item_purchasing_information']) ? $data['item_purchasing_information'] : ''}}</textarea>
                        </div>                     
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">  
                            <label>Cost *</label>
                            <div class="row">
                                <div>
                                    <div class="col-md-8">    
                                       <input type="text" class="form-control number-input input-sm" id="item_cost" value="{{isset($data['item_cost']) ? $data['item_cost'] : ''}}" name="item_cost" required>
                                    </div>
                                    <div class="col-md-4">
                                        per <span class="abbreviation"></span>
                                    </div>
                                </div>
                            </div>
                        </div>               
                        <div class="col-md-6">
                            <label>Expense Account *</label>
                            <select name="item_expense_account_id" class="drop-down-coa form-control" id="item_expense_account_id" required>                                                           
                               @include("member.load_ajax_data.load_chart_account", ['add_search' => "", '_account' => $_expense, 'account_id' => $default_expense])
                            </select>
                        </div>
                    </div>
                    <!-- <div class="col-md-12 text-right" style="padding-top:50px">
                        <button type="button" class="btn btn-custom-white back_to_menu">Back</button>
                        <button class="btn btn-custom-primary pull-right">Submit</button>
                    </div> -->
            </div>
    </div> 

  </div> 
  <div class="modal-footer inventory_type" style="display:none;">
    <button type="button" class="btn btn-custom-white back_to_menu">Back</button>
    <!--<button type="button" class="btn btn-custom-red-white btn-del-modallarge" data-url="" data-value="">Delete</button>-->
    <button class="btn btn-custom-primary btn-save-modallarge" type="submit" data-url="">Submit</button>
  </div>
</form>

<form action="/member/item/add_submit?item_type=noninventory" class="global-submit form_two"  enctype="multipart/form-data"  method="post" type_of_item="noninventory_type">
   <div class="clearfix modal-body modallarge-body-layout background-white noninventory_type" style="display:none;">  
    <!-- NON INVENTORY -->
    <div class="form-horizontal">
            <div class="col-md-12">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                 <div class="form-group">
                    <div class="col-md-8">
                        <label>Name *</label>
                        <!-- <input type="text" class="form-control" id="item_name" value="{{isset($data['item_name']) ? $data['item_name'] : ''}}" name="item_name" required> -->
                        <textarea required class="form-control item-name" name="item_name" id="item_name">{{isset($data['item_name']) ? $data['item_name'] : ''}}</textarea><br>
                    </div>
                    <div class="col-md-4 text-center">
                        <input type="hidden" name="item_img" class="image-value" key="2" required>
                        <img class="img-responsive img-src" key="2" style="width: 100%; object-fit: contain;" src="/assets/front/img/default.jpg">
                        <button type="button" class="btn btn-primary image-gallery image-gallery-single" key="2" style="margin-top: 15px;">Upload Image</button>
                    </div>
                 </div>
                <div class="form-group">                        
                    <div class="col-md-4">
                        <label>SKU</label>
                        <input type="text" class="form-control item-sku" id="item_sku" value="{{isset($data['item_sku']) ? $data['item_sku'] : ''}}" name="item_sku" >
                    </div>
                    <div class="col-md-4">
                        <label>Unit of Measure</label>
                            <select class="form-control input-sm measure_container2 drop-down-um" name="item_measurement_id">
                                @include("member.load_ajax_data.load_unit_measurement")
                            </select>
                    </div>
                    <div class="col-md-4">
                        <label>Category *</label>
                        <select name="item_category_id" cat_type="noninventory" class="form-control drop-down-category non-inventory" id="item_category_id" required>
                            @include("member.load_ajax_data.load_category", ['add_search' => "",'_category' => $_noninventory])
                        </select>
                    </div>
                </div>
                <!-- <div class="col-md-12" style="border-bottom: solid 1px #ACACAC; padding-top:20px;margin-bottom: 5px"></div> -->
                <div class="form-group">               
                    <div class="col-md-12">
                        <label>Sales information</label>
                        <textarea class="form-control" id="item_sales_information" name="item_sales_information" placeholder="Description on sales forms">{{isset($data['item_sales_information']) ? $data['item_sales_information'] : ''}}</textarea>
                        <!-- <input type="text" class="form-control" id="item_sales_information" value="{{isset($data['item_sales_information']) ? $data['item_sales_information'] : ''}}" name="item_sales_information" placeholder="Description on sales forms" required> -->
                    </div>
                    <div class="col-md-12">
                        <input type="checkbox" id="item_sale_to_customer" value="1" name="item_sale_to_customer"> I sell this product/service to my customers.
                    </div>   
                </div>
                <div class="form-group">
                    <div class="col-md-6">
                        <label class="col-md-12">Sales price/rate *</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control number-input" id="item_price" value="{{isset($data['item_price']) ? $data['item_price'] : ''}}" name="item_price" required>
                        </div>
                        <div class="col-md-4">
                            per <span class="abbreviation"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Income Account *</label>
                            <select name="item_income_account_id" class="drop-down-coa form-control" id="item_income_account_id" required>                                
                               @include("member.load_ajax_data.load_chart_account", ['add_search' => "", '_account' => $_income, 'account_id' => $default_income])
                            </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-4">
                        <label>Promo Price</label>
                        <input type="text" class="form-control input-sm" name="promo_price"  value="{{isset($data['item_discount_value']) ? $data['item_discount_value'] : ''}}">                            
                    </div>
                    <div class="col-md-4">
                        <label>Promo Start Date</label>
                        <input type="text" class="form-control datepicker" name="start_promo_date" value="{{isset($data['item_discount_date_start']) ? $data['item_discount_date_start'] : ''}}" >
                    </div>
                    <div class="col-md-4">
                        <label>Promo End Date</label>
                        <input type="text" class="form-control datepicker" name="end_promo_date" value="{{isset($data['item_discount_date_end']) ? $data['item_discount_date_end'] : ''}}" >
                    </div>
                </div>
                <!--<div class="col-md-12" style="border-bottom: solid 1px #ACACAC; padding-top:20px;margin-bottom: 5px"></div>-->
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="checkbox" onclick="toggle_po('.noninventory-po', this)" {{isset($data['item_purchase_from_supplier']) ? 'checked' : ''}} id="item_purchase_from_supplier" name="item_purchase_from_supplier"> I purchase this product/service from a supplier.
                    </div>                
                </div>

                <div class="form-group noninventory-po" style="display: none">
                    <div class="col-md-12">
                        <label>Purchasing information</label>
                        <textarea class="form-control input-sm" id="item_purchasing_information" name="item_purchasing_information" placeholder="Description on purchase forms" >{{isset($data['item_purchasing_information']) ? $data['item_purchasing_information'] : ''}}</textarea>
                    </div>                     
                    <div class="col-md-6">  
                        <label>Cost *</label>
                        <div class="row">
                            <div>
                                <div class="col-md-8">    
                                   <input type="text" class="form-control number-input input-sm" id="item_cost" value="{{isset($data['item_cost']) ? $data['item_cost'] : ''}}" name="item_cost">
                                </div>
                                <div class="col-md-4">
                                    per <span class="abbreviation"></span>
                                </div>
                            </div>
                        </div>
                    </div>               
                    <div class="col-md-6">
                        <label>Expense Account *</label>
                        <select name="item_expense_account_id" class="drop-down-coa form-control" id="item_expense_account_id">                                                           
                           @include("member.load_ajax_data.load_chart_account", ['add_search' => "", '_account' => $_expense, 'account_id' => $default_expense])
                        </select>
                    </div>
                </div>
               <!--  <div class="col-md-12 text-right" style="padding-top:50px">
                    <button type="button" class="btn btn-custom-white back_to_menu">Back</button>
                    <button class="btn btn-custom-primary pull-right">Submit</button>
                </div>
 -->            </div>
    </div>
   </div>
     <div class="modal-footer noninventory_type" style="display:none;">
    <button type="button" class="btn btn-custom-white back_to_menu" >Back</button>
    <!--<button type="button" class="btn btn-custom-red-white btn-del-modallarge" data-url="" data-value="">Delete</button>-->
    <button class="btn btn-custom-primary btn-save-modallarge" type="submit" data-url="">Submit</button>
  </div>
</form>

<form action="/member/item/add_submit?item_type=service" class="global-submit form_three" method="post" type_of_item="service_type">
   <div class="clearfix modal-body modallarge-body-layout background-white service_type" style="display:none;"> 
   
    <!-- SERVICE -->
    <div class="form-horizontal">
            <div class="col-md-12">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                 <div class="form-group">
                        <div class="col-md-8">
                            <label>Name *</label>
                            <!-- <input type="text" class="form-control" id="item_name" value="{{isset($data['item_name']) ? $data['item_name'] : ''}}" name="item_name" required> -->
                            <textarea required class="form-control item-name" name="item_name" id="item_name">{{isset($data['item_name']) ? $data['item_name'] : ''}}</textarea>
                        </div>
                        <div class="col-md-4 text-center">
                            <input type="hidden" name="item_img" class="image-value" key="3" required>
                            <img class="img-responsive img-src" key="3" style="width: 100%; object-fit: contain;" src="/assets/front/img/default.jpg">
                            <button type="button" class="btn btn-primary image-gallery image-gallery-single" key="3" style="margin-top: 15px;">Upload Image</button>
                        </div>
                 </div>
                <div class="form-group">                        
                    <div class="col-md-4">
                        <label>SKU</label>
                        <input type="text" class="form-control item-sku" id="item_sku" value="{{isset($data['item_sku']) ? $data['item_sku'] : ''}}" name="item_sku">
                    </div>
                    <div class="col-md-4">
                        <label>Unit of Measure</label>
                            <select class="form-control input-sm measure_container3 drop-down-um"name="item_measurement_id">
                                @include("member.load_ajax_data.load_unit_measurement")
                            </select>
                    </div>
                    <div class="col-md-4">
                        <label>Category *</label>
                        <select name="item_category_id" cat_type="service" class="form-control drop-down-category services" id="item_category_id" required>
                         @include("member.load_ajax_data.load_category", ['add_search' => "",'_category' => $_service])
                        </select>
                    </div>
                </div>
                <!-- <div class="col-md-12" style="border-bottom: solid 1px #ACACAC; padding-top:20px;margin-bottom: 5px"></div> -->
                <div class="form-group">               
                    <div class="col-md-12">
                        <label>Sales information</label>
                        <!-- <input type="text" class="form-control" id="item_sales_information" value="{{isset($data['item_sales_information']) ? $data['item_sales_information'] : ''}}" name="item_sales_information" placeholder="Description on sales forms" required> -->
                        <textarea class="form-control" id="item_sales_information" name="item_sales_information" placeholder="Description on sales forms" >{{isset($data['item_sales_information']) ? $data['item_sales_information'] : ''}}</textarea>
                    </div>
                    <div class="col-md-12">
                        <input type="checkbox" id="item_sale_to_customer " name="item_sale_to_customer"> I sell this product/service to my customers.
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6">
                        <label class="col-md-12">Sales price/rate *</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control number-input" id="item_price" value="{{isset($data['item_price']) ? $data['item_price'] : ''}}" name="item_price" required>
                        </div>
                        <div class="col-md-4">
                            per <span class="abbreviation"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Income Account *</label>
                        <select name="item_income_account_id" class="drop-down-coa form-control" id="item_income_account_id" required>                                
                           @include("member.load_ajax_data.load_chart_account", ['add_search' => "", '_account' => $_income, 'account_id' => $default_income])
                        </select>
                    </div>
                </div>
                 
                    <div class="form-group">                   
                        <div class="col-md-4">
                            <label>Promo Price</label>
                            <input type="text" class="form-control input-sm" name="promo_price"  value="{{isset($data['item_discount_value']) ? $data['item_discount_value'] : ''}}">                            
                        </div>
                        <div class="col-md-4">
                            <label>Promo Start Date</label>
                            <input type="text" class="form-control datepicker" name="start_promo_date" value="{{isset($data['item_discount_date_start']) ? $data['item_discount_date_start'] : ''}}" >
                        </div>
                        <div class="col-md-4">
                            <label>Promo End Date</label>
                            <input type="text" class="form-control datepicker" name="end_promo_date" value="{{isset($data['item_discount_date_end']) ? $data['item_discount_date_end'] : ''}}" >
                        </div>
                    </div>
                <!-- <div class="col-md-12" style="border-bottom: solid 1px #ACACAC; padding-top:20px;margin-bottom: 5px"></div> -->
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="checkbox" onclick="toggle_po('.service-po', this)" id="item_purchase_from_supplier" name="item_purchase_from_supplier"> I purchase this product/service from a supplier.
                    </div>
                </div>
                 <div class="form-group service-po" style="display: none">
                    <div class="col-md-12">
                        <label>Purchasing information</label>
                        <textarea class="form-control input-sm" id="item_purchasing_information" name="item_purchasing_information" placeholder="Description on purchase forms" >{{isset($data['item_purchasing_information']) ? $data['item_purchasing_information'] : ''}}</textarea>
                    </div>                     
                    <div class="col-md-6">  
                        <label>Cost *</label>
                        <div class="row">
                            <div>
                                <div class="col-md-8">    
                                   <input type="text" class="form-control number-input input-sm" id="item_cost" value="{{isset($data['item_cost']) ? $data['item_cost'] : ''}}" name="item_cost">
                                </div>
                                <div class="col-md-4">
                                    per <span class="abbreviation"></span>
                                </div>
                            </div>
                        </div>
                    </div>               
                    <div class="col-md-6">
                        <label>Expense Account *</label>
                        <select name="item_expense_account_id" class="drop-down-coa form-control" id="item_expense_account_id">                                                           
                           @include("member.load_ajax_data.load_chart_account", ['add_search' => "", '_account' => $_expense, 'account_id' => $default_expense] )
                        </select>
                    </div>
                </div>
                <!-- <div class="col-md-12 text-right" style="padding-top:50px">
                    <button type="button" class="btn btn-custom-white back_to_menu">Back</button>
                    <button class="btn btn-custom-primary pull-right">Submit</button>
                </div> -->
            </div>
    </div> 
   </div>  <div class="modal-footer service_type" style="display:none;">
    <button type="button" class="btn btn-custom-white back_to_menu" >Back</button>
    <!--<button type="button" class="btn btn-custom-red-white btn-del-modallarge" data-url="" data-value="">Delete</button>-->
    <button class="btn btn-custom-primary btn-save-modallarge" >Submit</button>
  </div>
</form>

<form action="/member/item/add_submit?item_type=bundle" class="global-submit form_four" method="post" type_of_item="bundle_type">
    <div class="clearfix modal-body modallarge-body-layout background-white bundle_type" style="display:none;">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <!-- BUNDLE -->
        <div class="form-horizontal">
            <div class="clearfix col-md-12">
                <div class="col-md-8">
                    <div class="form-group">
                        <div class="row col-md-12">
                            <label>Name *</label>
                            <!-- <input type="text" class="form-control" id="item_name" value="{{isset($data['item_name']) ? $data['item_name'] : ''}}" name="item_name" required> -->
                            <textarea required class="form-control item-name" name="item_name" id="item_name">{{isset($data['item_name']) ? $data['item_name'] : ''}}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row col-md-6">
                            <label>SKU</label>
                            <input type="text" class="form-control item-sku" id="item_sku" value="{{isset($data['item_sku']) ? $data['item_sku'] : ''}}" name="item_sku">
                        </div>
                        <div class="col-md-6">
                            <label>Category *</label>
                            <select name="item_category_id" cat_type="bundle" class="form-control drop-down-category bundles" id="item_category_id" required>
                             @include("member.load_ajax_data.load_category", ['add_search' => "",'_category' => $_bundle])
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row col-md-12">
                            <label>Sales Information</label>
                            <textarea class="form-control" id="item_sales_information" name="item_sales_information" placeholder="Description on sales forms" >{{isset($data['item_sales_information']) ? $data['item_sales_information'] : ''}}</textarea>
                        </div>
                     </div>
                    <div class="form-group">                   
                        <div class="col-md-4">
                            <label>Promo Price</label>
                            <input type="text" class="form-control input-sm" name="promo_price"  value="{{isset($data['item_discount_value']) ? $data['item_discount_value'] : ''}}">                            
                        </div>
                        <div class="col-md-4">
                            <label>Promo Start Date</label>
                            <input type="text" class="form-control datepicker" name="start_promo_date" value="{{isset($data['item_discount_date_start']) ? $data['item_discount_date_start'] : ''}}" >
                        </div>
                        <div class="col-md-4">
                            <label>Promo End Date</label>
                            <input type="text" class="form-control datepicker" name="end_promo_date" value="{{isset($data['item_discount_date_end']) ? $data['item_discount_date_end'] : ''}}" >
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="col-md-12 text-center">
                        <div class="form-group">
                            <input type="hidden" name="item_img" class="image-value" key="4" required>
                            <img class="img-responsive img-src" key="4" style="width: 100%; object-fit: contain;" src="/assets/front/img/default.jpg">
                            <button type="button" class="btn btn-primary image-gallery image-gallery-single" key="4" style="margin-top: 15px;">Upload Image</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix col-md-8">
                <div class="table-responsive">
                    <table class="digima-table">
                        <thead >
                            <tr>
                                <th width="5%"></th>
                                <th width="50%">Product / Service</th>
                                <th width="25%">Unit of Measuerment</th>
                                <th width="15%">Qty</th>
                                <th width="5%"></th>
                            </tr>
                        </thead>
                        <tbody class="tbody-item">
                            <tr>
                                <td class="text-center add-tr cursor-pointer"><i class="fa fa-plus" aria-hidden="true"></i></td>
                                <td>
                                    <select class="form-control drop-down-item select-item input-sm pull-left" name="bundle_item_id[]" required>
                                        @include("member.load_ajax_data.load_item_category", ['add_search' => "", '_item' => $_item_to_bundle])
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control drop-down-um-one select-um-one input-sm pull-left" name="bundle_um_id[]">
                                        <option value=""></option> 
                                    </select>
                                </td>   
                                <td><input class="text-center form-control input-sm" type="text" name="bundle_qty[]"/></td>
                                <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer bundle_type" style="display:none;">
        <button type="button" class="btn btn-custom-white back_to_menu">Back</button>
        <button class="btn btn-custom-primary btn-save-modallarge">Submit</button>
    </div>
</form>

<div class="div-script">
    <table class="div-item-row-script hide">
        <tr>
            <td class="text-center add-tr cursor-pointer"><i class="fa fa-plus" aria-hidden="true"></i></td>
            <td>
                <select class="form-control select-item input-sm pull-left" name="bundle_item_id[]" required>
                   @include("member.load_ajax_data.load_item_category", ['add_search' => "", '_item' => $_item_to_bundle])
                </select>
            </td>
            <td>
                <select class="form-control select-um-one input-sm pull-left" name="bundle_um_id[]">
                    <option value=""></option> 
                </select>
            </td>   
            <td><input class="text-center form-control input-sm" type="text" name="bundle_qty[]"/></td>
            <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
        </tr>
    </table>
</div>

<script type="text/javascript" src="/assets/member/js/prompt_serial_number.js"></script>
<script type="text/javascript" src="/assets/member/js/item.js"></script>
<style type="text/css">
    .modal-body
    {
        overflow: visible;
    }
</style>