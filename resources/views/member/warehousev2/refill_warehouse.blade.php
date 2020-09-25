@extends('member.layout')
@section('content')
<form class="global-submit form-to-submit-add" action="/member/item/v2/warehouse/refill-submit" method="post">    
<input type="hidden" name="_token" value="{{csrf_token()}}">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Warehouse Refill Inventory</span>
            </h1>
                <a href='/member/item/v2/warehouse' class="panel-buttons btn btn-custom-white pull-right">Cancel</a>
                <button type="submit" class="panel-buttons btn btn-custom-primary pull-right">Refill Warehouse</button>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block">
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
                <select name="reference_name" required class="form-control droplist-vendor input-sm">
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
            <div class="col-md-12">
                <div class="table">
                    <table class="digima-table table table-bordered table-condensed pos-table">
                        <thead>
                            <tr>
                                <th class="text-left" >PRODUCT SKU</th>
                                <th class="text-left" >PRODUCT DESCRIPTION</th>
                                <th class="text-center" width="180px">CURRENT STOCK</th>
                                <th class="text-center" width="180px">QUANTITY</th>
                                <th width="50px"></th>
                            </tr>
                        </thead>
                        <tbody class="draggable tbody-item">
                            <tr class="tr-draggable">
                                <td>
                                    <select class="form-control droplist-item input-sm" name="item_id[]" >
                                        @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                                        <option class="hidden" value="" />
                                    </select>
                                </td>
                                <td><textarea class="form-control txt-desc" name="item_remarks[]"></textarea></td>
                                <td class="text-center"><label class="txt-remaining-qty"></label> </td>
                                <td><input class="form-control number-input" type="text" name="item_quantity[]"/></td>
                                <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                            </tr>
                            <tr class="tr-draggable">
                                <td>
                                    <select class="form-control droplist-item input-sm" name="item_id[]" >
                                        @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                                        <option class="hidden" value="" />
                                    </select>
                                </td>
                                <td><textarea class="form-control txt-desc" name="item_remarks[]"></textarea></td>
                                <td class="text-center"><label class="txt-remaining-qty"></label> </td>
                                <td><input class="form-control number-input" type="text" name="item_quantity[]"/></td>
                                <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                            </tr>
                        </tbody>
                    </table>                    
                </div>                
            </div>            
        </div>
    </div>
</div>
</form>

<div class="div-script">
    <table class="div-item-row-script-item hide">
        <tr class="tr-draggable">
            <td>
                <select class="form-control select-item input-sm pull-left" name="item_id[]" >
                    @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                    <option class="hidden" value="" />
                </select>
            </td>
            <td><textarea class="textarea-expand txt-desc" name="item_remarks[]"></textarea></td>
            <td class="text-center"><label class="txt-remaining-qty"></label> </td>
            <td><input class="text-center number-input txt-qty compute" type="text" name="item_quantity[]"/></td>
            <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
        </tr>
    </table>
</div>
@endsection


@section('script')
<script type="text/javascript" src="/assets/member/js/warehouse/whse_refill.js"></script>
@endsection

@section('css')
<link rel="stylesheet" type="text/css" href="/assets/member/css/pos.css">
@endsection