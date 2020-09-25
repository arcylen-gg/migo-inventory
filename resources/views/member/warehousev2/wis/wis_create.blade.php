@extends('member.layout')
@section('content')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-archive"></i>
            <h1>
                <span class="page-title">CREATE - Warehouse Transfer</span>
            </h1>
            <div class="dropdown pull-right">
                <div>
                    <a class="btn btn-custom-white" href="/member/item/warehouse/wis">Cancel</a>
                    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Select Action
                    <span class="caret"></span></button>
                    <ul class="dropdown-menu  dropdown-menu-custom">
                      <li><a class="select-action" code="sclose">Save & Close</a></li>
                      <li><a class="select-action" code="sedit">Save & Edit</a></li>
                      <li><a class="select-action" code="sprint">Save & Print</a></li>
                      <li><a class="select-action" code="snew">Save & New</a></li>
                      <li><a class="select-action" code="sconfirm">Save & Confirm WT</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- <div class="panel panel-default panel-block panel-title-block">
    <div class="panel-body form-horizontal">
        <div class="form-group">
            <div class="col-md-12">
                <div class="input-group pos-search">
                  <span style="background-color: #eee" class="input-group-addon button-scan" id="basic-addon1">
                    <i class="fa fa-shopping-cart scan-icon"></i>
                    <i style="display: none;" class="fa fa-spinner fa-pulse fa-fw scan-load"></i>
                  </span>
                  <input type="text" class="form-control event_search_item" placeholder="Enter item name or scan barcode" aria-describedby="basic-addon1">
                  <div class="pos-search-container"></div>
                </div>
            </div>
        </div>
       
    </div>
</div> -->

<form class="global-submit form-to-submit-add" action="{{$action}}" method="post">
<input type="hidden" name="_token" value="{{csrf_token()}}">
<input type="hidden" class="button-action" name="button_action" value="">
<input type="hidden" name="wis_id" value="{{$wis->wis_id or ''}}">
<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-body form-horizontal">
        <div class="form-group">
            <div class="col-md-4">
                <label>Transaction Number</label>
                <input type="text" name="wis_number" value='{{isset($wis) ? $wis->wis_number :$transaction_ref_number}}' class="form-control">
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-4">
                <label>Warehouse Destination</label>
                <select required class="form-control select-warehouse droplist-warehouse" name="destination_warehouse_id">
                    @foreach($_warehouse as $warehouse)
                        <option {{isset($wis) ? ($wis->destination_warehouse_id == $warehouse->warehouse_id ? 'selected' : ''): ''}} warehouse-address="{{$warehouse->warehouse_address}}" value="{{$warehouse->warehouse_id}}">{{$warehouse->warehouse_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label>Truck</label>
                <div> 
                    <select class="form-control select-truck" name="truck_id">
                        @include("member.load_ajax_data.load_truck", ['truck_id' => isset($wis) ? $wis->wis_truck_id : ''])
                    </select>                    
                </div>
            </div>
            <div class="col-md-4"></div>
        </div>

        <div class="form-group">
            <div class="col-md-4">
                <label>Ship to</label>
                <div>
                    <textarea class="form-control txt-warehouse-address" name="destination_warehouse_address">{{isset($wis) ? $wis->destination_warehouse_address :''}}</textarea>
                </div>
            </div>
            <div class="col-md-4">
                <label>Delivery Date</label>
                <input type="text" name="delivery_date" class="datepicker form-control input-sm" value="{{ isset($wis->wis_delivery_date) == ''? date('m/d/Y') : date('m/d/Y', strtotime($wis->wis_delivery_date)) }}">
            </div>
        </div>
        <div class="form-group hide">
            <div class="col-md-12">
                <div class="load-item-table-pos-s"></div>
            </div>
        </div>
        <div class="form-group draggable-container">
            <div class="col-md-12">
                <div class="table">
                    <table class="digima-table">
                        <thead>
                            <tr>
                                <th style="" class="text-right">#</th>
                                <th style="width: 300px">Product/Service</th>
                                <th style="">Description</th>
                                @if($check_settings == 1)
                                    <th style="">Bin</th>
                                @endif
                                <th style="">U/M</th>
                                <th style="">Qty</th>
                                <th style="">SRP</th>
                                <th style="">Amount</th>
                                <th width="10"></th>
                            </tr>
                        </thead>
                        <tbody class="draggable tbody-item">
                            @if(count($wis_item) > 0)
                            @foreach($wis_item as $key =>$item) 
                             <tr class="tr-draggable">
                                <td class="invoice-number-td text-center">1</td>
                                @if($check_barcode == '1')
                                <td class="item-select-td">
                                    <input class="form-control input-sm pull-left item-textbox hidden" value="{{$item->item_barcode}}" onkeypress="event_search($(this), event)" type="text"/>
                                    <select class="1111 form-control select-item droplist-item input-sm pull-left item-select {{$item->wt_item_id}}" name="item_id[]" required >
                                        @include("member.load_ajax_data.load_item_category", ['add_search' => "", 'item_id' => $item->wt_item_id])
                                        <option class="hidden" value="" />
                                    </select>
                                </td>
                                @else
                                <td>
                                    <select class="1111 form-control select-item droplist-item input-sm pull-left " name="item_id[]" >
                                        @include("member.load_ajax_data.load_item_category", ['add_search' => "", 'item_id' => $item->wt_item_id])
                                        <option class="hidden" value="" />
                                    </select>
                                    
                                </td>
                                @endif
                                <td><textarea class="form-control txt-desc" name="item_description[]">{{$item->wt_description}}</textarea></td>
                                @if($check_settings == 1)
                                <td>
                                    <select class="form-control droplist-sub-warehouse select-sub-warehouse input-sm" name="item_sub_warehouse[]" >
                                        @include('member.warehousev2.load_sub_warehouse_v2_select', ['_bin_warehouse' => $_bin_item_warehouse[$key]])
                                        <option class="hidden" value="" />
                                    </select>
                                </td>
                                @endif
                                <td>
                                    <select class="2222 droplist-um select-um" name="item_um[]"><option class="hidden" value="" />
                                    @if($item->wt_um)
                                        @include("member.load_ajax_data.load_one_unit_measure", ['item_um_id' => $item->multi_um_id, 'selected_um_id' => $item->wt_um])
                                    @else
                                        <option class="hidden" value="" />
                                    @endif
                                    </select>
                                </td>
                                <td><input class="form-control number-input txt-qty text-center compute" type="text" name="item_qty[]"/ value="{{$item->wt_orig_qty}}"></td>
                                <td><input class="text-right number-input txt-rate" type="text" name="item_rate[]" value="{{currency('',$item->wt_rate)}}"/></td>
                                <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]" value="{{currency('',$item->wt_amount)}}"/></td>
                                <td class="text-center remove-tr cursor-pointer">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                    <input type="hidden" name="item_refname[]">
                                    <input type="hidden" name="item_refid[]">
                                </td>
                            </tr>
                            @endforeach
                            @endif
                            <tr class="tr-draggable">
                                <td class="invoice-number-td text-center">1</td>
                                @if($check_barcode == '1')
                                <td class="item-select-td">
                                    <input class="form-control input-sm pull-left item-textbox hidden" onkeypress="event_search($(this), event)" type="text"/>
                                    <select class="1111 form-control select-item droplist-item input-sm pull-left item-select" name="item_id[]" >
                                        @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                                        <option class="hidden" value="" />
                                    </select>
                                </td>
                                @else
                                <td>
                                    <select class="1111 form-control select-item droplist-item input-sm pull-left " name="item_id[]" >
                                        @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                                        <option class="hidden" value="" />
                                    </select>
                                    
                                </td>
                                @endif
                                <td><textarea class="form-control txt-desc" name="item_description[]"></textarea></td>
                                @if($check_settings == 1)
                                <td>
                                    <select class="form-control droplist-sub-warehouse select-sub-warehouse input-sm" name="item_sub_warehouse[]" >
                                        @include('member.warehousev2.load_sub_warehouse_v2_select')
                                        <option class="hidden" value="" />
                                    </select>
                                </td>
                                @endif
                                <td><select class="2222 droplist-um select-um" name="item_um[]"><option class="hidden" value="" /></select></td>
                                <td><input class="form-control number-input txt-qty text-center compute" type="text" name="item_qty[]"/></td>
                                <td><input class="text-right number-input txt-rate" type="text" name="item_rate[]"/></td>
                                <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]"/></td>
                                <td class="text-center remove-tr cursor-pointer">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                    <input type="hidden" name="item_refname[]">
                                    <input type="hidden" name="item_refid[]">
                                </td>
                            </tr>
                            <tr class="tr-draggable">
                                <td class="invoice-number-td text-center">2</td>
                                @if($check_barcode == '1')
                                <td class="item-select-td">
                                    <input class="form-control input-sm pull-left item-textbox hidden" onkeypress="event_search($(this), event)" type="text"/>
                                    <select class="1111 form-control select-item droplist-item input-sm pull-left item-select" name="item_id[]" >
                                        @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                                        <option class="hidden" value="" />
                                    </select>
                                </td>
                                @else
                                <td>
                                    <select class="1111 form-control select-item droplist-item input-sm pull-left " name="item_id[]" >
                                        @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                                        <option class="hidden" value="" />
                                    </select>
                                    
                                </td>
                                @endif
                                <td><textarea class="form-control txt-desc" name="item_description[]"></textarea></td>
                                @if($check_settings == 1)
                                <td>
                                    <select class="form-control droplist-sub-warehouse select-sub-warehouse input-sm" name="item_sub_warehouse[]" >
                                        @include('member.warehousev2.load_sub_warehouse_v2_select')
                                        <option class="hidden" value="" />
                                    </select>
                                </td>
                                @endif
                                <td><select class="2222 droplist-um select-um" name="item_um[]"><option class="hidden" value="" /></select></td>
                                <td><input class="form-control number-input txt-qty text-center compute" type="text" name="item_qty[]"/></td>
                                <td><input class="text-right number-input txt-rate" type="text" name="item_rate[]"/></td>
                                <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]"/></td>
                                <td class="text-center remove-tr cursor-pointer">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                    <input type="hidden" name="item_refname[]">
                                    <input type="hidden" name="item_refid[]">
                                </td>
                            </tr>
                          {{---  <tr class="tr-draggable">
                                <td>
                                    <select class="form-control droplist-item input-sm" name="item_id[]" >
                                        @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                                        <option class="hidden" value="" />
                                    </select>
                                </td>
                                <td><textarea class="form-control txt-desc" name="item_remarks[]"></textarea></td>
                                <td class="text-center"><label class="txt-remaining-qty"></label> </td>
                                <td><input class="form-control number-input text-center" type="text" name="item_quantity[]"/></td>
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
                                <td><input class="form-control number-input text-center" type="text" name="item_quantity[]"/></td>
                                <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                            </tr> ---}}
                        </tbody>
                    </table>                    
                </div>                
            </div>            
        </div>

        <div class="row clearfix">
            <div class="col-md-6">
                <label>Remarks</label>
                <div>
                    <textarea class="form-control" name="wis_remarks">{{$wis->wis_remarks or ''}}</textarea>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-7 text-right digima-table-label">
                      Total
                    </div>
                    <div class="col-md-5 text-right digima-table-value total">
                        <input type="hidden" name="overall_price" class="total-amount-input" />
                        PHP&nbsp;<span class="total-amount">0.00</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>



<div class="div-script">
    <table class="div-item-row-script-item hide">
        <tr class="tr-draggable">
            <td class="invoice-number-td text-center">2</td>
            @if($check_barcode == '1')
            <td class="item-select-td">
                <input class="form-control input-sm pull-left item-textbox hidden" onkeypress="event_search($(this), event)" type="text"/>
                <select class="1111 form-control select-item droplist-item input-sm pull-left item-select" name="item_id[]" >
                    @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                    <option class="hidden" value="" />
                </select>
            </td>
            @else
            <td>
                <select class="1111 form-control select-item droplist-item input-sm pull-left " name="item_id[]" >
                    @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                    <option class="hidden" value="" />
                </select>
                
            </td>
            @endif
            <td><textarea class="form-control txt-desc" name="item_description[]"></textarea></td>
            @if($check_settings == 1)
            <td>
                <select class="form-control select-sub-warehouse input-sm" name="item_sub_warehouse[]" >
                    @include('member.warehousev2.load_sub_warehouse_v2_select')
                    <option class="hidden" value="" />
                </select>
            </td>
            @endif
            <td><select class="2222 select-um select-um" name="item_um[]"><option class="hidden" value="" /></select></td>
            <td><input class="form-control number-input txt-qty text-center compute" type="text" name="item_qty[]"/></td>
            <td><input class="text-right number-input txt-rate" type="text" name="item_rate[]"/></td>
            <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]"/></td>
            <td class="text-center remove-tr cursor-pointer">
                <i class="fa fa-trash-o" aria-hidden="true"></i>
                <input type="hidden" name="item_refname[]">
                <input type="hidden" name="item_refid[]">
            </td>
        </tr>
    </table>
</div>
@endsection

@section('script')
<script type="text/javascript" src="/assets/member/js/warehouse/wis_create.js"></script>
@endsection

@section('css')
<link rel="stylesheet" type="text/css" href="/assets/member/css/pos.css">
@endsection