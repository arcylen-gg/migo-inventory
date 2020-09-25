
<form class="global-submit form-to-submit-add" action="/member/item/warehouse/adjust_submit" method="post">
<style type="text/css">
    .chosen-container
    {
        width: 100% !important;
    }
</style>
<input type="hidden" name="_token" value="{{csrf_token()}}">
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title layout-modallarge-title item_title">Warehouse Adjust Inventory</h4>
</div>
<div class="modal-body modallarge-body-layout background-white form-horizontal menu_container">
    <input type="hidden" name="warehouse_id" value="{{$warehouse->warehouse_id}}">
    <div class="panel-body form-horizontal">
        <div class="form-group">
            <div class="col-md-6">            
                <h3 style="margin-top: 10px">{{$warehouse->warehouse_name}}</h3>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-6">
                <label>Remarks *</label>
                <textarea required class="form-control input-sm" name="remarks"></textarea>
            </div>
        </div>
        <div class="form-group"> 
        <div class="col-md-1">
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
        </div>
        </div>
        <div class="row clearfix draggable-container warehouse-adjust-container">
            <div class="table-responsive">
                <div class="col-sm-12">
                    <table class="digima-table">
                        <thead >
                            <tr>
                                <th style="width: 10px">#</th>
                                <th style="width: 200px;">Product Name</th>
                                <th style="width: 100px;">Product SKU</th>
                                <th style="width: 100px;">Current Stocks</th>
                                <th style="width: 100px;">QTY</th>
                                <th style="width: 10px"></th>
                            </tr>
                        </thead>
                        <tbody class="draggable tbody-item">
                            <tr class="tr-draggable">
                                <td class="invoice-number-td text-right">1</td>
                                <td class="">
                                    <select class="form-control count-select select-item input-sm droplist-item" id="select_item" name="item_id[]" select_id="1" data-placeholder="Select a Item">
                                        @include('member.load_ajax_data.load_warehouse_item',['warehouse_list' => $warehouse_item]);
                                    </select>
                                </td>
                                <td><span class="product_sku"></td>
                                <td>
                                    <span class="product_current_qty"></span>
                                </td>
                                <td><input type="number" max="-1" class="form-control qty-txt qty-txt1" name="quantity[]"></td>
                                <td class="text-center cursor-pointer remove-tr"><i class="fa fa-trash-o " aria-hidden="true"></i></td>
                            </tr> 
                            <tr class="tr-draggable">
                                <td class="invoice-number-td text-right">1</td>
                                <td class="">
                                    <select class="form-control count-select select-item input-sm droplist-item" id="select_item" name="item_id[]" select_id="1" data-placeholder="Select a Item">
                                        @include('member.load_ajax_data.load_warehouse_item',['warehouse_list' => $warehouse_item]);
                                    </select>
                                </td>
                                <td><span class="product_sku"></td>
                                <td>
                                    <span class="product_current_qty"></span>
                                </td>
                                <td><input type="number" max="-1" class="form-control qty-txt qty-txt1" name="quantity[]"></td>
                                <td class="text-center cursor-pointer remove-tr"><i class="fa fa-trash-o " aria-hidden="true"></i></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>    
    </div>
</div>

<div class="modal-footer" >
    <button type="button" class="btn btn-custom-white" data-dismiss="modal">Cancel</button>
    <button class="btn btn-custom-primary btn-save-modallarge" type="submit" data-url="">Adjust Inventory</button>
</div>
</form>
<div class="div-script">
    <table class="div-item-row-script hide">
        <tr class="tr-draggable">
            <td class="invoice-number-td text-right">1</td>
            <td class="">
                <select class="form-control count-select input-sm select-item" id="select_item" name="item_id[]" select_id="1" data-placeholder="Select a Item">
                    @include('member.load_ajax_data.load_warehouse_item',['warehouse_list' => $warehouse_item]);
                </select>
            </td>
            <td><span class="product_sku"></td>
            <td>
                <span class="product_current_qty"></span>
            </td>
            <td><input type="number" max="-1" class="form-control qty-txt qty-txt1" name="quantity[]"></td>
            <td class="text-center cursor-pointer remove-tr"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
        </tr>
    </table>
</div>
<style type="text/css">
    .red-color
    {
        color: #ff1a1a;
    }
</style>
<script type="text/javascript" src="/assets/member/js/textExpand.js"></script>
 <script type="text/javascript" src="/assets/member/js/draggable_row.js"></script>

<script type="text/javascript" src="/assets/member/js/transfer_warehouse.js"></script>
