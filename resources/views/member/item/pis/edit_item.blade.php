
  <!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    @if($data['type_of_item'] == "inventory_type")
        <h4 class="modal-title layout-modallarge-title item_title">Inventory</h4>
    @elseif($data['type_of_item'] == "noninventory_type")
        <h4 class="modal-title layout-modallarge-title item_title">Non-Inventory</h4>
    @elseif($data['type_of_item'] == "service_type")
        <h4 class="modal-title layout-modallarge-title item_title">Service</h4>
    @else
        <h4 class="modal-title layout-modallarge-title item_title">Item Type</h4>
    @endif
     <input type="hidden" id="item_type_container" value="{{isset($data['type_of_item']) ? $data['type_of_item'] : ''}}">
</div>

<form  action="{{$action}}?item_type=inventory" class="global-submit form_two" id="modal_form_large"  enctype="multipart/form-data" method="post" type_of_item="inventory_type">
  <div class="clearfix modal-body modallarge-body-layout background-white inventory_type" style="{{isset($data['type_of_item']) ? ($data['type_of_item'] == 'inventory_type' ? '' : 'display:none;') : 'display:none;'}}"> 
     <!-- INVENTORY -->
    <div class="form-horizontal">
            <div class="col-md-12">
            <!-- class="global-submit form_one" -->
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <input type="hidden" class="item_id" name="item_id" value="{{$item_id}}">
                    <div class="form-group">
                        <div class="col-md-8">
                            <label>Name *</label>
                            <!-- <input type="text" class="form-control" id="item_name" value="{{isset($data['item_name']) ? $data['item_name'] : ''}}" name="item_name" required> -->
                            <textarea required class="form-control input-sm item-name" name="item_name" id="item_name">{{isset($data['item_name']) ? $data['item_name'] : ''}}</textarea><br>
                            <label>Sales information</label>
                            <textarea class="form-control input-sm" id="item_sales_information" name="item_sales_information" placeholder="Description on sales forms" >{{isset($data['item_sales_information']) ? $data['item_sales_information'] : ''}}</textarea>
                        </div>
                        <div class="col-md-4 text-center">
                            <input type="hidden" name="item_img" class="image-value" key="1" value="{{$data['item_img'] ? $data['item_img'] : '/assets/front/img/default.jpg' }}">
                            <img class="img-responsive img-src" key="1" style="width: 100%; object-fit: contain;" src="{{$data['item_img'] ? $data['item_img'] : '/assets/front/img/default.jpg' }}">
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
                             @include("member.load_ajax_data.load_category", ['add_search' => "",'_category' => $_inventory,'type_id' => $data["item_category_id"]])
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-4">
                            <label>Unit of Measure</label>
                            <select class="form-control input-sm measure_container drop-down-pis-um notbase-um" name="unit_n_based">
                            @include("member.load_ajax_data.load_pis_um",['_um' => $_um_n, 'id_um' => $um_n_id])
                            </select>
                        </div>
                        <div class="col-md-1 text-center">
                            <strong><h3> = </h3></strong>
                        </div>
                        <div class="col-md-3">
                            <label>.</label>
                            <select class="form-control input-sm measure_container drop-down-pis-um base-um" name="unit_based">
                            @include("member.load_ajax_data.load_pis_um",['_um' => $_um_b, 'id_um' => $um_n_b_id])
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Quantity</label>
                            <input type="text" class="form-control input-sm" name="quantity" value="{{$quantity or 1}}">
                        </div>
                    </div>
               <!--      <div class="form-group">     
                        <div class="col-md-4">    
                            <label>Initial quantity on hand *</label>        
                            <input type="number" class="form-control input-sm" id="item_quantity" value="{{isset($data['item_quantity']) ? $data['item_quantity'] : ''}}" name="item_quantity" required>
                        </div>                  
                        <div class="col-md-4">
                            <label>Reorder Point </label>
                            <input type="text" class="form-control input-sm" id="item_reorder_point" value="{{isset($data['item_reorder_point']) ? $data['item_reorder_point'] : ''}}" name="item_reorder_point" >
                        </div> 
                        <div class="col-md-4">
                            <label>As of date</label>
                            <input type="text" class="form-control input-sm datepicker" id="item_date_tracked" name="item_date_tracked" value="{{date('m/d/y')}}" >
                        </div>
                    </div> -->
                    <div class="form-group">               
                        <div class="col-md-6">
                            <label>Packing Size</label>
                            <input type="text" name="packing_size" class="form-control input-sm" value="{{isset($data['packing_size']) ? $data['packing_size'] : ''}}">
                        </div>
                        <div class="col-md-6">
                            <label>Manufacturer</label>
                            <select class="form-control input-sm drop-down-manufacturer" name="item_manufacturer_id">
                                @include("member.load_ajax_data.load_manufacturer", ['_manufacturer' => $_manufacturer, 'manufacturer_id' => isset($data['item_manufacturer_id']) ? $data['item_manufacturer_id'] : ''])
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <label>Sales price/rate *</label>
                            <input type="text" class="form-control number-input input-sm" id="item_price" value="{{isset($data['item_price']) ? number_format($data['item_price'],4) : ''}}" name="item_price" required>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="sales_price_change" value="sales_price" > Change Sales Price
                                </label>
                            </div>
                        </div> 
                        <div class="col-md-6">  
                            <label>Cost *</label>  
                            <input type="text" class="form-control number-input input-sm" id="item_cost" value="{{isset($data['item_cost']) ? number_format($data['item_cost'],4) : ''}}" name="item_cost" required>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="cost_price_change" value="cost_price" > Change Cost Price
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-md-12" style="border-bottom: solid 1px #A CACAC; padding-top:20px;margin-bottom: 5px"></div> -->
                  <!--   <div class="form-group">
                        <div class="col-md-12">
                            <label>Purchasing information</label>
                            <textarea class="form-control input-sm" id="item_purchasing_information" name="item_purchasing_information" placeholder="Description on purchase forms" >{{isset($data['item_purchasing_information']) ? $data['item_purchasing_information'] : ''}}</textarea>
                        </div>                     
                    </div> -->
                    <div class="form-group">
                    </div>
                    <!-- <div class="col-md-12 text-right" style="padding-top:50px">
                        <button type="button" class="btn btn-custom-white back_to_menu">Back</button>
                        <button class="btn btn-custom-primary pull-right">Submit</button>
                    </div> -->
            </div>
    </div> 

  </div> 
  <div class="modal-footer inventory_type"  style="{{isset($data['type_of_item']) ? ($data['type_of_item'] == 'inventory_type' ? '' : 'display:none;') : 'display:none;'}}">
    <button type="button" class="btn btn-custom-white back_to_menu">Back</button>
    <!--<button type="button" class="btn btn-custom-red-white btn-del-modallarge" data-url="" data-value="">Delete</button>-->
    <button class="btn btn-custom-primary btn-save-modallarge" type="submit" data-url="">Submit</button>
  </div>
</form>

<form action="{{$action}}?item_type=noninventory" class="global-submit form_two"  enctype="multipart/form-data"  method="post" type_of_item="noninventory_type">
   <div class="clearfix modal-body modallarge-body-layout background-white noninventory_type" style="{{isset($data['type_of_item']) ? ($data['type_of_item'] == 'noninventory_type' ? '' : 'display:none;') : 'display:none;'}}">  
    <!-- NON INVENTORY -->
    <div class="form-horizontal">
        <div class="col-md-12">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <input type="hidden" class="item_id" name="item_id" value="{{$item_id}}">
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
                <div class="col-md-6">
                    <label>SKU</label>
                    <input type="text" class="form-control item-sku" id="item_sku" value="{{isset($data['item_sku']) ? $data['item_sku'] : ''}}" name="item_sku" >
                </div>
                <div class="col-md-6">
                    <label>Category *</label>
                    <select name="item_category_id" cat_type="noninventory" class="form-control drop-down-category non-inventory" id="item_category_id" required>
                        @include("member.load_ajax_data.load_category", ['add_search' => "",'_category' => $_noninventory,'type_id' => $data["item_category_id"]])
                    </select>
                </div>
            </div>
            <!-- <div class="col-md-12" style="border-bottom: solid 1px #ACACAC; padding-top:20px;margin-bottom: 5px"></div> -->
         
            <div class="form-group">
                <div class="col-md-4">
                    <label>Unit of Measure</label>
                    <select class="form-control input-sm measure_container drop-down-pis-um notbase-um" name="unit_n_based">
                        @include("member.load_ajax_data.load_pis_um",['_um' => $_um_n, 'id_um' => $um_n_id])
                    </select>
                </div>
                <div class="col-md-1 text-center">
                    <strong><h3> = </h3></strong>
                </div>
                <div class="col-md-3">
                    <label>.</label>
                    <select class="form-control input-sm measure_container drop-down-pis-um base-um" name="unit_based">
                        @include("member.load_ajax_data.load_pis_um",['_um' => $_um_b, 'id_um' => $um_n_b_id])
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Quantity</label>
                    <input type="text" class="form-control input-sm" name="quantity" value="{{$quantity}}">
                </div>
            </div>
            <div class="form-group">   
                <div class="col-md-12">
                    <input type="checkbox" id="item_sale_to_customer" value="1" name="item_sale_to_customer"> I sell this product/service to my customers.
                </div>   
            </div>
            <div class="form-group">  
                <div class="col-md-6">
                    <label> Sales price/rate *</label>
                    <div class="row">
                        <div class="col-md-8">
                            <input type="text" class="form-control number-input" id="item_price" value="{{isset($data['item_price']) ? number_format($data['item_price'],2)  : ''}}" name="item_price" required>
                        </div>                    
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="sales_price_change" value="sales_price" > Change Sales Price
                            </label>
                        </div>
                    </div>
                </div>         
                <div class="col-md-12">
                    <label>Sales information</label>
                    <textarea class="form-control" id="item_sales_information" name="item_sales_information" placeholder="Description on sales forms">{{isset($data['item_sales_information']) ? $data['item_sales_information'] : ''}}</textarea>
                </div> 
            </div>
            <!--<div class="col-md-12" style="border-bottom: solid 1px #ACACAC; padding-top:20px;margin-bottom: 5px"></div>-->
            <div class="form-group">
                <div class="col-md-12">
                    <input type="checkbox" onclick="toggle_po('.noninventory-po', this)" {{isset($data['item_purchase_from_supplier']) ? 'checked' : ''}} id="item_purchase_from_supplier" name="item_purchase_from_supplier"> I purchase this product/service from a supplier.
                </div>                
            </div>

            <div class="form-group noninventory-po" style="display: none">             
                <div class="col-md-6">  
                    <label>Cost *</label>
                    <div class="row">
                        <div class="col-md-8">    
                           <input type="text" class="form-control number-input input-sm" id="item_cost" value="{{isset($data['item_cost']) ? number_format($data['item_cost'],2) : ''}}" name="item_cost">
                        </div>
                    </div>
                </div>   
                <div class="col-md-12">
                    <label>Purchasing information</label>
                    <textarea class="form-control input-sm" id="item_purchasing_information" name="item_purchasing_information" placeholder="Description on purchase forms" >{{isset($data['item_purchasing_information']) ? $data['item_purchasing_information'] : ''}}</textarea>
                </div>        
            </div>
           <!--  <div class="col-md-12 text-right" style="padding-top:50px">
                <button type="button" class="btn btn-custom-white back_to_menu">Back</button>
                <button class="btn btn-custom-primary pull-right">Submit</button>
            </div>
-->     </div>
    </div>
   </div>
     <div class="modal-footer noninventory_type" style="{{isset($data['type_of_item']) ? ($data['type_of_item'] == 'noninventory_type' ? '' : 'display:none;') : 'display:none;'}}">
    <button type="button" class="btn btn-custom-white back_to_menu" >Back</button>
    <!--<button type="button" class="btn btn-custom-red-white btn-del-modallarge" data-url="" data-value="">Delete</button>-->
    <button class="btn btn-custom-primary btn-save-modallarge" type="submit" data-url="">Submit</button>
  </div>
</form>

<form action="{{$action}}?item_type=service" class="global-submit form_three" method="post" type_of_item="service_type">
   <div class="clearfix modal-body modallarge-body-layout background-white service_type" style="{{isset($data['type_of_item']) ? ($data['type_of_item'] == 'service_type' ? '' : 'display:none;') : 'display:none;'}}"> 
   
    <!-- SERVICE -->
    <div class="form-horizontal">
            <div class="col-md-12">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <input type="hidden" class="item_id" name="item_id" value="{{$item_id}}">
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
                    <div class="col-md-6">
                        <label>SKU</label>
                        <input type="text" class="form-control item-sku" id="item_sku" value="{{isset($data['item_sku']) ? $data['item_sku'] : ''}}" name="item_sku">
                    </div>
                    <div class="col-md-6">
                        <label>Category *</label>
                        <select name="item_category_id" cat_type="service" class="form-control drop-down-category services" id="item_category_id" required>
                         @include("member.load_ajax_data.load_category", ['add_search' => "",'_category' => $_service,'type_id' => $data["item_category_id"]])
                        </select>
                    </div>
                </div>
                <!-- <div class="col-md-12" style="border-bottom: solid 1px #ACACAC; padding-top:20px;margin-bottom: 5px"></div> -->
                <div class="form-group">
                    <div class="col-md-4">
                        <label>Unit of Measure</label>
                        <select class="form-control input-sm measure_container drop-down-pis-um notbase-um" name="unit_n_based">
                            @include("member.load_ajax_data.load_pis_um",['_um' => $_um_n, 'id_um' => $um_n_id])
                        </select>
                    </div>
                    <div class="col-md-1 text-center">
                        <strong><h3> = </h3></strong>
                    </div>
                    <div class="col-md-3">
                        <label>.</label>
                        <select class="form-control input-sm measure_container drop-down-pis-um base-um" name="unit_based">
                            @include("member.load_ajax_data.load_pis_um",['_um' => $_um_b, 'id_um' => $um_n_b_id])
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Quantity</label>
                        <input type="text" class="form-control input-sm" name="quantity" value="{{$quantity}}">
                    </div>
                </div>

                <div class="form-group">               
                    <div class="col-md-12">
                        <input type="checkbox" id="item_sale_to_customer" value="1" name="item_sale_to_customer"> I sell this product/service to my customers.               
                    </div>
                </div>
                <div class="form-group">    
                    <div class="col-md-6">
                        <label>Sales price/rate *</label> 
                        <div class="row">
                            <div class="col-md-8">
                                <input type="text" class="form-control number-input" id="item_price" value="{{isset($data['item_price']) ? number_format($data['item_price'],2)  : ''}}" name="item_price" required>
                            </div>                            
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="sales_price_change" value="sales_price" > Change Sales Price
                                </label>
                            </div>
                        </div>
                    </div>           
                    <div class="col-md-12">
                        <label>Sales information</label>
                        <textarea class="form-control" id="item_sales_information" name="item_sales_information" placeholder="Description on sales forms" >{{isset($data['item_sales_information']) ? $data['item_sales_information'] : ''}}</textarea>
                    </div>
                </div>
                <!-- <div class="col-md-12" style="border-bottom: solid 1px #ACACAC; padding-top:20px;margin-bottom: 5px"></div> -->
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="checkbox" onclick="toggle_po('.service-po', this)" id="item_purchase_from_supplier" name="item_purchase_from_supplier"> I purchase this product/service from a supplier.
                    </div>
                </div>
                 <div class="form-group service-po" style="display: none">
                    <div class="col-md-6">  
                        <label>Cost *</label>
                        <div class="row">
                            <div>
                                <div class="col-md-8">    
                                   <input type="text" class="form-control number-input input-sm" id="item_cost" value="{{isset($data['item_cost']) ? number_format($data['item_cost'],2) : ''}}" name="item_cost">
                                </div>
                            </div>
                        </div>
                    </div>            
                    <div class="col-md-12">
                        <label>Purchasing information</label>
                        <textarea class="form-control input-sm" id="item_purchasing_information" name="item_purchasing_information" placeholder="Description on purchase forms" >{{isset($data['item_purchasing_information']) ? $data['item_purchasing_information'] : ''}}</textarea>
                    </div>            
                </div>
                <!-- <div class="col-md-12 text-right" style="padding-top:50px">
                    <button type="button" class="btn btn-custom-white back_to_menu">Back</button>
                    <button class="btn btn-custom-primary pull-right">Submit</button>
                </div> -->
            </div>
    </div> 
   </div>  <div class="modal-footer service_type" style="{{isset($data['type_of_item']) ? ($data['type_of_item'] == 'service_type' ? '' : 'display:none;') : 'display:none;'}}">
    <button type="button" class="btn btn-custom-white back_to_menu" >Back</button>
    <!--<button type="button" class="btn btn-custom-red-white btn-del-modallarge" data-url="" data-value="">Delete</button>-->
    <button class="btn btn-custom-primary btn-save-modallarge" >Submit</button>
  </div>
</form>

<form action="{{$action}}?item_type=bundle" class="global-submit form_four" method="post" type_of_item="bundle_type">
    <div class="clearfix modal-body modallarge-body-layout background-white bundle_type" style="{{isset($data['type_of_item']) ? ($data['type_of_item'] == 'bundle_type' ? '' : 'display:none;') : 'display:none;'}}">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="hidden" class="item_id" name="item_id" value="{{$item_id}}">
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
                             @include("member.load_ajax_data.load_category", ['add_search' => "",'_category' => $_bundle,'type_id' => $data["item_category_id"]])
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row col-md-6">
                            <label>Unit of Measure</label>
                            <select class="form-control input-sm measure_container drop-down-pis-um notbase-um" name="unit_n_based">
                            @include("member.load_ajax_data.load_pis_um",['_um' => $_um_n, 'id_um' => $um_n_id])
                            </select>                            
                        </div>
                        <div class="col-md-6">
                            <label>Barcode </label> <small>(<input type="checkbox" name="auto_generate_code" value="generate"> Auto Generate Code )</small>
                            <input type="text" class="form-control item_barcode" id="item_barcode" value="{{isset($data['item_barcode']) ? $data['item_barcode'] : ''}}" name="item_barcode">
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
                            @if(isset($data["bundle"]))
                            @foreach($data["bundle"] as $bundle)
                                <tr>
                                    <td class="text-center add-tr cursor-pointer"><i class="fa fa-plus" aria-hidden="true"></i></td>
                                    <td>
                                        <select class="form-control drop-down-item select-item input-sm pull-left" name="bundle_item_id[]" required>
                                            @include("member.load_ajax_data.load_item_category", ['add_search' => "", '_item' => $_item_to_bundle, 'item_id' => $bundle["bundle_item_id"]])
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control drop-down-um droplist-um input-sm pull-left" name="bundle_um_id[]">
                                            @if($bundle['bundle_um_id'])
                                                @include("member.load_ajax_data.load_one_unit_measure", ['_um' => $_um_multi, 'item_um_id' => $bundle['multi_um_id'], 'selected_um_id' => $bundle['bundle_um_id']])
                                            @else
                                                <option class="hidden" value="" />
                                            @endif
                                        </select>
                                    </td>   
                                    <td><input class="text-center form-control input-sm" type="text" name="bundle_qty[]" value="{{ $bundle['bundle_qty'] }}" /></td>
                                    <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer bundle_type"  style="{{isset($data['type_of_item']) ? ($data['type_of_item'] == 'bundle_type' ? '' : 'display:none;') : 'display:none;'}}">
        <button type="button" class="btn btn-custom-white back_to_menu">Back</button>
        <button class="btn btn-custom-primary btn-save-modallarge">Submit</button>
    </div>
</form>


<form action="{{$action}}?item_type=group" class="global-submit form_four" method="post" type_of_item="group_type">
    <div class="clearfix modal-body modallarge-body-layout background-white group_type" style="{{isset($data['type_of_item']) ? ($data['type_of_item'] == 'group_type' ? '' : 'display:none;') : 'display:none;'}}">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="hidden" class="item_id" name="item_id" value="{{$item_id}}">
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
                             @include("member.load_ajax_data.load_category", ['add_search' => "",'_category' => $_bundle,'type_id' => $data["item_category_id"]])
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row col-md-6">
                            <label>Unit of Measure</label>
                            <select class="form-control input-sm measure_container drop-down-pis-um notbase-um" name="unit_n_based">
                            @include("member.load_ajax_data.load_pis_um",['_um' => $_um_n, 'id_um' => $um_n_id])
                            </select>                            
                        </div>
                        <div class="col-md-6">
                            <label>Barcode </label> <small>(<input type="checkbox" name="auto_generate_code" value="generate"> Auto Generate Code )</small>
                            <input type="text" class="form-control item_barcode" id="item_barcode" value="{{isset($data['item_barcode']) ? $data['item_barcode'] : ''}}" name="item_barcode">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row col-md-12">
                            <label>Sales Information</label>
                            <textarea class="form-control" id="item_sales_information" name="item_sales_information" placeholder="Description on sales forms" >{{isset($data['item_sales_information']) ? $data['item_sales_information'] : ''}}</textarea>
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
                            @if(isset($data["bundle"]))
                            @foreach($data["bundle"] as $bundle)
                                <tr>
                                    <td class="text-center add-tr cursor-pointer"><i class="fa fa-plus" aria-hidden="true"></i></td>
                                    <td>
                                        <select class="form-control drop-down-item select-item input-sm pull-left" name="bundle_item_id[]" required>
                                            @include("member.load_ajax_data.load_item_category", ['add_search' => "", '_item' => $_item_to_bundle, 'item_id' => $bundle["bundle_item_id"]])
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control drop-down-um droplist-um input-sm pull-left" name="bundle_um_id[]">
                                            @if($bundle['bundle_um_id'])
                                                @include("member.load_ajax_data.load_one_unit_measure", ['_um' => $_um_multi, 'item_um_id' => $bundle['multi_um_id'], 'selected_um_id' => $bundle['bundle_um_id']])
                                            @else
                                                <option class="hidden" value="" />
                                            @endif
                                        </select>
                                    </td>   
                                    <td><input class="text-center form-control input-sm" type="text" name="bundle_qty[]" value="{{ $bundle['bundle_qty'] }}" /></td>
                                    <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer group_type"  style="{{isset($data['type_of_item']) ? ($data['type_of_item'] == 'group_type' ? '' : 'display:none;') : 'display:none;'}}">
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
<script type="text/javascript">
function setTwoNumberDecimal(x) 
{
    var value = parseFloat($(x).val()).toFixed(4);
    $(x).val(value);
}

$('.number-input').change(function(e)
{
    setTwoNumberDecimal(e.currentTarget);
});
</script>
<style type="text/css">
    .modal-body
    {
        overflow: visible;
    }
</style>