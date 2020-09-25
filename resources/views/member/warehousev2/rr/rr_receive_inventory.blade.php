@extends('member.layout')
@section('content')

<form class="global-submit" method="post" action="/member/transaction/receiving_report/receive-inventory-submit">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="wis_id" value="{{ $wis_id or '' }}">
<input type="hidden" class="button-action" name="button_action" value="">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-cubes"></i>
            <h1>
                <span class="page-title">Receiving Report</span>
            </h1>
           <div class="dropdown pull-right">
                <div>
                    <a class="btn btn-custom-white" href="/member/transaction/receiving_report">Cancel</a>
                    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Select Action
                    <span class="caret"></span></button>
                    <ul class="dropdown-menu  dropdown-menu-custom">
                      <li><a class="select-action" code="sclose">Save & Close</a></li>
                      <!-- <li><a class="select-action" code="sedit">Save & Edit</a></li> -->
                      <li><a class="select-action" code="sprint">Save & Print</a></li>
                      <!-- <li><a class="select-action" code="snew">Save & New</a></li> -->
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-body form-horizontal">
        <div class="form-group tab-content panel-body">
            <div class="col-md-4">
                <label>RR#</label>
                <input type="text" class="form-control input-sm" name="rr_number" value="{{$transaction_refnum or ''}}">
            </div>
        </div>
        <div class="form-group tab-content panel-body">
            <div class="col-md-4">
                <label>Remarks</label>
                <textarea class="form-control" name="rr_remarks">{{$wis->wis_number or ''}}</textarea>
            </div>
            <div class="col-md-4">
                <label>Date Received</label>
                <input class="form-control datepicker input-sm" type="text" name="rr_date_received" value="{{date('m/d/Y')}}" />
            </div>
        </div>
        <div class="form-group tab-content panel-body warehouse-container">
            <div id="all" class="tab-pane fade in active">
                <div class="form-group order-tags"></div>
                <div class="table-responsive">
                    <table class="digima-table">
                        <thead>
                            <tr>
                                <th style="" class="text-right">#</th>
                                <th style="width: 300px">Product/Service</th>
                                <th style="">Description</th>
                                @if($check_settings == 1)
                                    <th style="">BIN</th>
                                @endif
                                <th style="">U/M</th>
                                <th style="">Qty</th>
                                <th style="">SRP</th>
                                <th style="">Amount</th>
                                <th width="10"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($wis_item) > 0)
                                @foreach($wis_item as $item)
                                <tr class="tr-draggable">
                                    <td class="invoice-number-td text-center">1</td>
                                    <td>
                                        <select readonly="true" class="form-control droplist-item select-item input-sm" name="item_id[]" >
                                            @include("member.load_ajax_data.load_item_category", ['add_search' => "", 'item_id' => $item->wt_item_id])
                                            <option class="hidden" value="" />
                                        </select>
                                    </td>
                                    <td><textarea class="form-control txt-desc" name="item_description[]">{{$item->wt_description}}</textarea></td>
                                    @if($check_settings == 1)
                                    <td>
                                        <select class="form-control droplist-sub-warehouse select-sub-warehouse input-sm" name="item_sub_warehouse[]" >
                                            @include('member.warehousev2.load_sub_warehouse_v2_select')
                                            <option class="hidden" value="" />
                                        </select>
                                    </td>
                                    @endif
                                    <td>
                                        <select class="2222 droplist-um select-um" name="item_um[]">
                                            @if($item->wt_um)
                                                @include("member.load_ajax_data.load_one_unit_measure", ['item_um_id' => $item->multi_um_id, 'selected_um_id' => $item->wt_um])
                                            @else
                                                <option class="hidden" value="" />
                                            @endif
                                        </select>
                                    </td>
                                    <td><input class="form-control number-input txt-qty text-center compute" type="text" name="item_qty[]" value="{{$item->wt_qty}}" /></td>
                                    <td><input class="text-right number-input txt-rate" readonly="true" type="text" value="{{$item->wt_rate}}" name="item_rate[]"/></td>
                                    <td><input class="text-right number-input txt-amount" readonly="true" value="{{$item->wt_amount}}" type="text" name="item_amount[]"/></td>
                                    <td class="text-center remove-tr cursor-pointer">
                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                        <input type="hidden" name="wis_item_quantity[]" value="{{$item->wt_qty}}" >
                                        <input type="hidden" name="wis_item_um[]" value="{{$item->wt_um}}">
                                        <input type="hidden" name="item_refname[]" value="wis">
                                        <input type="hidden" name="item_refid[]" value="{{$wis_id or 0}}">
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-md-6">
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
@endsection
@section('script')
<script type="text/javascript" src="/assets/member/js/warehouse/rr_create.js"></script>
@endsection