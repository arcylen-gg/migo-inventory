
<form class="global-submit form-to-submit-add" action="/member/item/warehouse/refill_submit" method="post">
<style type="text/css">
    .chosen-container
    {
        width: 100% !important;
    }
</style>
<input type="hidden" name="_token" value="{{csrf_token()}}">
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title layout-modallarge-title item_title">Warehouse Inventory Refill</h4>
</div>
<div class="modal-body modallarge-body-layout background-white form-horizontal menu_container">
    <input type="hidden" name="warehouse_id" id="warehouse_id" value="{{$warehouse->warehouse_id}}">
    <div class="panel-body form-horizontal">
        <div class="form-group">
            <div class="col-md-6">            
                <h3 style="margin-top: 10px">{{$warehouse->warehouse_name}}</h3>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-6">
                <label>Vendor *</label>
                <select name="reason_refill" required class="form-control droplist-vendor input-sm">
                    @include('member.load_ajax_data.load_vendor')
                    <option value="other">Others</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-6">
                <label>Remarks *</label>
                <textarea required class="form-control input-sm" name="remarks"></textarea>
            </div>
        </div>
        <div class="form-group"> 
      <!--   <div class="col-md-1">
            <label>Filter</label>
        </div>
        <div class="col-md-3">
            <select class="form-control filter-item">
                <option value="all">All Items</option>
                @if($_cat)
                    @foreach($_cat as $cat)
                        <option value="{{$cat->type_id}}">{{$cat->type_name}}</option>
                    @endforeach
                @endif
            </select>
        </div> -->
        <div class="col-md-6" > 
            <div class="input-group">
                <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                <input type="search" id="search_txt" name="" class="form-control" placeholder="Start typing item">
            </div>
        </div>
        </div>
        <div class="row clearfix draggable-container warehouse-refill-container">
            <div class="table-responsive">
                <div class="col-sm-12">
                    <table class="digima-table" id="item_table">
                        <thead >
                            <tr>
                                <th style="width: 10px">#</th>
                                <th class="text-center" style="width: 40px">Product ID</th>
                                <th style="width: 150px;">Product Name</th>
                                <th style="width: 100px;">Product SKU</th>
                                <th class="text-center" style="width: 50px">Serial Number</th>
                                <th style="width: 100px;">Current Stocks</th>
                                <th style="width: 100px;">Refill</th>
                            </tr>
                        </thead>
                        <tbody class="draggable">
                        @if($warehouse_item != null)
                            @foreach($warehouse_item as $key => $w_item)
                            <tr class="tr-draggable tr-draggable-html count_row">
                                <td class="invoice-number-td text-right">{{$key+1}}</td>
                                <td class="text-center"> {{$w_item->product_id}}</td>
                                <td class="">
                                  <!--  <select class="form-control chosen-select select-item input-sm" id="select_item" name="invline_item_id[]" select_id="{{$key+1}}" data-placeholder="Select a Item">
                                    </select> -->
                                   <label class="count-select">{{$w_item->product_name}}</label>
                                </td>
                                <td><label class="sku-txt sku-txt{{$key+1}}">{{$w_item->product_sku}}</label></td>
                                <td class="text-center"><input type="checkbox" class="serial-chk" disabled {{$w_item->has_serial_number == 1 ? 'checked' : ''}}></td>  
                                <td class="text-center">
                                    @if($w_item->product_warehouse_stocks <= $w_item->product_reorder_point)
                                    <label style="color: red">{{$w_item->product_qty_um}}</label>
                                    @else
                                    <label >{{$w_item->product_qty_um}}</label>
                                    @endif
                                </td>
                                <td> 
                                    <label><input type="text" class="number-input form-control input-sm" value="0" name="quantity[{{$w_item->product_id}}]"></label>
                                </td>
                            </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>    
    </div>
</div>

<div class="modal-footer" >
    <button type="button" class="btn btn-custom-white" data-dismiss="modal">Cancel</button>
    <button class="btn btn-custom-primary btn-save-modallarge" type="submit" data-url="">Refill Warehouse</button>
</div>
</form>

<script type="text/javascript" src="/assets/member/js/textExpand.js"></script>
<!-- <script type="text/javascript" src="/assets/member/js/draggable_row.js"></script> -->
<!-- <script type="text/javascript" src="/assets/member/js/transfer_warehouse.js"></script> -->
<script type="text/javascript">
    
$('.droplist-vendor').globalDropList(
{ 
    width : "100%",
    link : "/member/vendor/add",
    onChangeValue : function ()
    {
        var vendor_id = $(this).val();
        if(vendor_id != "other")
        {
            var warehouse_id = $("#warehouse_id").val();
            $(".warehouse-refill-container").load("/item/warehouse/refill/by_vendor/"+warehouse_id+"/"+vendor_id +" .warehouse-refill-container") 
        }
    }
});
$('#search_txt').keyup(function()
{
    searchTable($(this).val());
});
 function searchTable(inputVal)
    {
        var table = $('#item_table');
        table.find('tr').each(function(index, row)
        {
            var allCells = $(row).find('td');
            if(allCells.length > 0)
            {
                var found = false;
                allCells.each(function(index, td)
                {
                    var regExp = new RegExp(inputVal, 'i');
                    if(regExp.test($(td).text()))
                    {
                        found = true;
                        return false;
                    }
                });
                if(found == true)$(row).show();else $(row).hide();
            }
        });
    }
</script>
<script type="text/javascript" src="/assets/member/js/warehouse.js"></script>
