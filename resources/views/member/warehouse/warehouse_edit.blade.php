
<form class="global-submit form-to-submit-add" action="/member/item/warehouse/edit_submit" method="post">
<style type="text/css">
    .chosen-container
    {
        width: 100% !important;
    }
</style>
<input type="hidden" name="_token" value="{{csrf_token()}}">
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title layout-modallarge-title item_title">Edit Warehouse</h4>
    <input type="hidden" name="warehouse_id" value="{{$warehouse->warehouse_id}}">
</div>
<div class="modal-body modallarge-body-layout background-white form-horizontal menu_container">
    <div class="panel-body form-horizontal">
        <div class="form-group">
            <div class="col-md-6">            
                <label>Warehouse Name *</label>
                <input type="text" class="form-control" name="warehouse_name" value="{{$warehouse->warehouse_name}}" id="warehouse_name">
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-12">            
                <label>Warehouse Address *</label>
                <textarea required class="form-control input-sm" name="warehouse_address" id="warehouse_address">{{$warehouse->warehouse_address}}</textarea>
            </div>
        </div>
        @if($merchantwarehouse == 1)
            <div class="col-md-12">
                <input type="checkbox" name="merchant_warehouse" id="merchantwarehouse" {{$warehouse->merchant_warehouse == 1 ? 'checked' : ''}} value="1"> 
                <label for="merchantwarehouse">Use warehouse as merchant?</label>
            </div>

            <div class="col-md-6 default_repurchase_class" style="{{$warehouse->merchant_warehouse == 1 ? '' : 'display:none'}}">
                <div class="col-md-12">
                    <label for="default_repurchase">Default Repurchase Points Multiplier*</label>
                    </br>
                    <input type="number" class="form-control" name="default_repurchase_points_mulitplier" id="default_repurchase" value="{{$warehouse->default_repurchase_points_mulitplier}}"> 
                </div>
                <div class="col-md-12">
                    <label for="default_margin">Default Margin per product (%)*</label>
                    </br>
                    <input type="number" class="form-control" name="default_margin_per_product" id="default_margin" value="{{$warehouse->default_margin_per_product}}"> 
                </div>
            </div> 

            <div class="col-md-6 merchant_logo_div" style="{{$warehouse->merchant_warehouse == 1 ? '' : 'display:none'}}">
                <label for="default_margin">Merchant Logo</label>
                <div class="panel panel-default">
                    <div class="panel-body" style="text-align:center;">
                        <img src="{{$warehouse->merchant_logo != '' ? $warehouse->merchant_logo : '/assets/images/noimage.png'}}" style="max-height: 150px; max-width: 150px; min-height: 150px; max-height: 150px;" class="merchant_logo_container image-gallery image-gallery-single" key="merchantlogo"/>
                        <input type="hidden" class="merchant_logo_input" name="merchant_logo_input">
                    </div>
                </div>
                </br>
            </div>            
        @endif  
        <div class="form-group">
        <div class="col-md-12"><h3>Select Item</h3></div>        
            <div class="col-md-3" >
                <select class="form-control">
                    <option>All Items</option>
                </select>
            </div>
            <div class="col-md-9" > 
                <div class="input-group">
                    <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                    <input type="search" name="" class="form-control" placeholder="Start typing item">
                </div>
            </div>
        </div>
        <div class="row clearfix draggable-container">
            <div class="table-responsive">
                <div class="col-sm-12">
                    <table class="digima-table">
                        <thead >
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Item Name</th>
                                <th style="width: 100px;">SKU</th>
                                <!-- <th class="text-center" style="width: 100px;">Main Warehouse QTY</th> -->
                                <th class="text-center" style="width: 150px;">Reorder Point</th>
                                <th class="text-center hide" style="width: 150px;">Product On Hand</th>
                                <th style="width: 20px"></th>
                            </tr>
                        </thead>
                        <tbody class="draggable">
                        
                        @if(count($warehouse_item) > 0)
                            @foreach($warehouse_item as $key => $w_item)
                            <tr class="tr-draggable tr-draggable-html count_row">
                                <td class="invoice-number-td text-right">{{$key+1}}</td>
                                <td class="">
                                  <!--  <select class="form-control chosen-select select-item input-sm" id="select_item" name="invline_item_id[]" select_id="{{$key+1}}" data-placeholder="Select a Item">
                                    </select> -->
                                   <label class="count-select">{{$w_item->item_name}}</label>
                                </td>
                                <td><label class="sku-txt sku-txt{{$key+1}}">{{$w_item->item_sku}}</label></td>
                                <td>
                                    <input type="text" class="number-input render-point-txt render-point-txt{{$key+1}}" name="reoder_point[{{$w_item->item_id}}]" value="{{$w_item->sub_reorder_point}}">
                                </td>
                                <td class="hide"><input type="text" class="qty-txt qty-txt1 number-input" value="{{$w_item->item_qty}}" name="quantity[{{$w_item->item_id}}]"></td>
                               <td></td>
                            </tr>
                            <input type="hidden" value="{{$key++}}">
                            @endforeach
                        @else
                            <?php $key = 0 ?>
                        @endif

                            <tr class="tr-draggable tr-draggable-html count_row">
                                <td class="invoice-number-td text-right">3</td> 
                                <td>
                                    <select class="form-control count-select select-item input-sm" id="select_item" name="invline_item_id[]" select_id="{{$key+1}}" data-placeholder="Select a Item">
                                        @include('member.load_ajax_data.load_item');
                                    </select>
                                </td>
                                <td><label class="sku-txt sku-txt{{$key+1}}" ></label></td>
                                <td>
                                    <input type="text" class="number-input render-point-txt" value="0">
                                </td>
                                <td class="hide"><input type="text" class="number-input qty-txt" value="0"></td>       
                                <td class="text-center cursor-pointer"><i class="fa fa-trash-o remove-tr" aria-hidden="true"></i></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>    
    </div>
</div>
<div class="div-script">
    <table class="div-item-row-script hide">
         <tr class="tr-draggable tr-draggable-html count_row">
            <td class="invoice-number-td text-right">1</td>
            <td>
                <select class="form-control count-select input-sm" id="select_item" name="invline_item_id[]" data-placeholder="Select a Item" select_id="3">
                    <option>Select Item</option>
                   @include('member.load_ajax_data.load_item');
                </select>
            </td>
            <td ><label class="sku-txt"></label></td>
            <td>
                <input type="text" class="number-input render-point-txt" value="0">
            </td>
            <td class="hide"><input type="text" class="number-input qty-txt" value="0"></td>
            <td class="text-center cursor-pointer"><i class="fa fa-trash-o remove-tr" aria-hidden="true"></i></td>
        </tr>
    </div>
</div>
<div class="modal-footer" >
    <button type="button" class="btn btn-custom-white" data-dismiss="modal">Cancel</button>
    <button class="btn btn-custom-primary btn-save-modallarge" type="submit" data-url="">Update Warehouse</button>
</div>
</form>
<script type="text/javascript" src="/assets/member/js/textExpand.js"></script>
<!-- <script type="text/javascript" src="/assets/member/js/draggable_row.js"></script> -->
<!-- <script type="text/javascript" src="/assets/member/js/transfer_warehouse.js"></script> -->
<script type="text/javascript" src="/assets/member/js/warehouse.js"></script>
<script type="text/javascript">

    // check_checkbox();
    checkbox_change();

    function submit_selected_image_done(data) 
    { 
        $(".merchant_logo_container").attr("src",data.image_data[0].image_path);
        $(".merchant_logo_input").val(data.image_data[0].image_path);
    }

    function check_checkbox()
    {
        if($("#merchantwarehouse").prop("checked"))
        {
            $(".merchant_logo_div").show();
            $(".default_repurchase_class").show();
        }
        else
        {
            $(".merchant_logo_div").hide();
            $(".default_repurchase_class").hide();
        }   
    }

    function checkbox_change()
    {
        $("input[type=checkbox]").change(function()
        {
            check_checkbox();
        });
    }
</script>