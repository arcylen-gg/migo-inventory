

  <!-- Modal content-->
    @if($ids != null)
    <div class="modal-content">
        <div class="modal-header ">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h4 class="modal-title">Edit Bulk Items</h4>
        </div>
        <form class="global-submit form-horizontal" role="form" action="{{$action or ''}}" method="post">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <div class="modal-body">
                <div class="row">
                    <div class="clearfix modal-body"> 
                        <div class="form-group so-div">
                            <div class="col-md-12">
                                <table class="table table-bordered table-striped table-condensed">
                                    <thead style="text-transform: uppercase">
                                        <tr>
                                            <th class="text-center" width="200px">sku</th>
                                            <th class="text-center" width="200px">barcode</th>
                                            <th class="text-center" width="200px">item name</th>
                                            <th class="text-center" width="250px">description</th>
                                            <th class="text-center" width="120px">sales price</th>
                                            <th class="text-center" width="120px">cost price</th>
                                            <th class="text-center" width="150px">category</th>
                                            <th class="text-center" width="120px">u/m</th>
                                            <th class="text-center" width="50px">reorder point</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    
                                        @foreach($get_info as $key=> $info)
                                        <tr>            
                                            <td><input type="text" class="form-control"  style="font-size: 14px" name="item_sku[]" value="{{$info['item_sku']}}"></td>
                                            <td><input type="text" class="form-control"  style="font-size: 14px" name="item_barcode[]" value="{{$info['item_barcode']}}"></td>
                                            <input type="hidden" class="form-control" style="font-size: 14px" name="item_type_id[]" value="{{$info['item_type_id']}}">                        
                                            <input type="hidden" class="form-control" style="font-size: 14px" name="item_id[]" value="{{$info['item_id']}}">
                                            <td><input type="text" class="form-control" style="font-size: 14px" name="item_description[]" value="{{$info['item_name']}}"></td>
                                            <td><input type="text" class="form-control"  style="font-size: 14px" name="item_sales_information[]" value="{{$info['item_sales_information']}}"></td>
                                            <td><input type="text" class="form-control text-center item-price-txt"  style="font-size: 14px" name="item_price[]" value="{{number_format($info['item_price'], 2)}}"></td>
                                            <td><input type="text" class="form-control text-center item-cost-txt"  style="font-size: 14px" name="item_cost[]" value="{{number_format($info['item_cost'], 2)}}"></td>
                                            <td class="text-center">
                                                <select class="form-control select-category inventory"  style="font-size: 14px" name="item_category[]">
                                                    @include("member.load_ajax_data.load_category", ['add_search' => "",'_category' => $_item_category,'type_id' => $info['item_category_id']])
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <select class="form-control select-um inventory"  style="font-size: 14px" name="item_measurement_id[]">
                                                   @include("member.load_ajax_data.load_unit_measurement", ['um_id'=> $info['item_measurement_id']])
                                                </select>
                                            </td>
                                            <td><input type="text" class="form-control"  style="font-size: 14px" name="item_reorder_point[]" value="{{$info['item_reorder_point']}}"></td>
                                        </tr>
                                        @endforeach
                                    </tbody>                        
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
                <button class="btn btn-primary btn-custom-primary" type="submit">Update Item</button>
            </div>
        </form>

    </div>
    @else
    <div class="text-center">
    <span style="font-size: 20px"><b><i class="fa fa-warning" style="font-size:20px;color:red"></i> Please check items to edit </b></span>
    </div>
    @endif
<script type="text/javascript" src="/assets/member/js/item/item_add.js"></script>