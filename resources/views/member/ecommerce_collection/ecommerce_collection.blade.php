<form class="global-submit form-to-submit-transfer" role="form" action="{{$action}}" method="POST" >
    <input type="hidden" name="_token" value="{{csrf_token()}}" >
    <input type="hidden" name="collection_id" value="{{$collection_id or ''}}" >
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title layout-modallarge-title item_title">Add Collection</h4>
</div>

<div class="modal-body modallarge-body-layout background-white form-horizontal menu_container">
    <div class="panel-body form-horizontal">
        <div class="row">
            <div class="col-md-12">
                <!-- START CONTENT -->
                <div class="form-group">
                    <div class="row clearfix">
                        <div class="col-md-6">
                           <label>Collection Name</label>
                           <input type="text" class="form-control input-sm" name="collection_name" value="{{$collection->collection_name or ''}}">                                
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-md-6">
                        <label>Collection Description</label>
                        <textarea class="form-control input-sm textarea-expand" name="collection_description" placeholder="">{{$collection->collection_description or ''}}</textarea>
                    </div>
                </div>
                
                <div class="form-group draggable-container">
                    <div class="table-responsive">
                        <div class="col-sm-12">
                            <table class="digima-table">
                                <thead >
                                    <tr>
                                        <th style="width: 10px;" ></th>
                                        <th style="width: 10px;">#</th>
                                        <th>Product</th>
                                        <th width="10"></th>
                                    </tr>
                                </thead>
                                <tbody class="draggable tbody-item">     
                                    @if(isset($collection_item))
                                        @foreach($collection_item as $item)
                                            <tr class="tr-draggable">
                                                <td class="text-center cursor-move move"><i class="fa fa-th-large colo-mid-dark-gray"></i></td>

                                                <td class="invoice-number-td text-right">1</td>
                                                <td>
                                                    <select class="1111 form-control select-item droplist-item input-sm pull-left" name="collection_item_id[]" >
                                                        @include("member.load_ajax_data.load_ec_product", ['add_search' => "", 'product_id' => $item->ec_product_id ])
                                                        <option class="hidden" value="" />
                                                    </select>
                                                </td>
                                                
                                                <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                            </tr>
                                        @endforeach
                                    
                                    @endif                                
                                        <tr class="tr-draggable">
                                            <td class="text-center cursor-move move"><i class="fa fa-th-large colo-mid-dark-gray"></i></td>

                                            <td class="invoice-number-td text-right">1</td>
                                            <td>
                                                <select class="1111 form-control select-item droplist-item input-sm pull-left" name="collection_item_id[]" >
                                                    @include("member.load_ajax_data.load_ec_product", ['add_search' => ""])
                                                    <option class="hidden" value="" />
                                                </select>
                                            </td>
                                            
                                            <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                        </tr>
                                            
                                        <tr class="tr-draggable">
                                            <td class="text-center cursor-move move" ><i class="fa fa-th-large colo-mid-dark-gray"></i></td>

                                            <td class="invoice-number-td text-right">2</td>
                                            <td>
                                                <select class="22222 form-control select-item droplist-item input-sm pull-left" name="collection_item_id[]" >
                                                    @include("member.load_ajax_data.load_ec_product", ['add_search' => ""])
                                                    <option class="hidden" value="" />
                                                </select>
                                            </td>
                                            
                                            <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                        </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- END CONTENT -->
            </div>
        </div>
    </div>
</div>
<div class="modal-footer" >
    <button type="button" class="btn btn-custom-white" data-dismiss="modal">Cancel</button>
    <button class="btn btn-custom-primary btn-save-modallarge" type="submit" data-url="">Save Collection</button>
</div>
</form>
<div class="div-script">
    <table class="div-item-row-script hide">
        <tr class="tr-draggable">
            <td class="text-center cursor-move move" ><i class="fa fa-th-large colo-mid-dark-gray"></i></td>
            <td class="invoice-number-td text-right">2</td>
            <td>
                <select class="form-control select-item input-sm pull-left" name="collection_item_id[]">
                    @include("member.load_ajax_data.load_ec_product", ['add_search' => ""])
                    <option class="hidden" value="" />
                </select>
            </td>
            
            <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
        </tr>
    </table>
</div>
<script type="text/javascript" src="/assets/member/js/draggable_row.js"></script>
<script type="text/javascript" src="/assets/member/js/e_commerce/collection.js"></script>