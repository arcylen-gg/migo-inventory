<form class="global-submit form-to-submit-transfer" role="form" action="{{$action}}" method="POST" >
    <input type="hidden" name="_token" value="{{csrf_token()}}" >
    <input type="hidden" name="collection_id" value="{{Request::input('id')}}" >
    <div class="panel panel-default panel-block panel-title-block" id="top">
        <div class="panel-heading">
            <div class="col-md-6">
                <i class="fa fa-tags"></i>
                <h1>
                    <span class="page-title">Collection</span>
                    <small>
                    
                    </small>
                </h1>
            </div>
            <div class="col-md-6 text-right">                
                <a class="panel-buttons btn btn-custom-white" href="/member/ecommerce/product/collection/list" >Cancel</a>
                <button type="submit" class="panel-buttons btn btn-custom-primary">Save</button>
            </div>
        </div>
    </div>
    <div class="panel panel-default panel-block panel-title-block panel-gray">
       <!--  <ul class="nav nav-tabs">
            <li class="active cursor-pointer"><a class="cursor-pointer" data-toggle="tab" href="#pending-codes"><i class="fa fa-star"></i> Invoice Information</a></li>
            <li class="cursor-pointer"><a class="cursor-pointer" data-toggle="tab" href="#used-codes"><i class="fa fa-list"></i> Activities</a></li>
        </ul> -->
        <div class="tab-content">
            <div class="row">
                <div class="col-md-12" style="padding: 30px;">
                    <!-- START CONTENT -->
                    <div style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 10px;">
                        <div class="row clearfix">
                            <div class="col-md-4">
                               <label>Collection Name</label>
                               <input type="text" class="form-control input-sm" name="collection_name" value="{{$collection->collection_name or ''}}">                                
                            </div>
                        </div>
                    </div>
                    
                    <div class="row clearfix">
                        <div class="col-md-4">
                            <label>Collection Description</label>
                            <textarea class="form-control input-sm textarea-expand" name="collection_description" placeholder="">{{$collection->collection_description or ''}}</textarea>
                        </div>
                    </div>
                    
                    <div class="row clearfix draggable-container">
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
<script type="text/javascript" src="/assets/member/js/textExpand.js"></script>
<script type="text/javascript" src="/assets/member/js/draggable_row.js"></script>
<script type="text/javascript" src="/assets/member/js/e_commerce/collection.js"></script>