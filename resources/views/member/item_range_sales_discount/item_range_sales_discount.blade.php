@extends('member.layout')
@section('content')

<form class="global-submit" role="form" action="{{ $action or ''}}" method="POST" >
    <input type="hidden" class="token" name="_token" value="{{csrf_token()}}" >
    <input type="hidden" class="button-action" name="button_action" value="">
    <input type="hidden" class="transaction-status" disabled="false" value="">
<div class="drawer-overlay">
    <div class="panel panel-default panel-block panel-title-block" id="top">
        <div class="panel-heading">
            <div>
                <i class="{{$icon}}"></i>
                <h1>
                    <span class="page-title">{{ $page or ''}}</span>
                    <small>
                    <!--Add a product on your website-->
                    </small>
                </h1> 
                <div class="dropdown pull-right">
                    <div class="hidden" style="width: 200px;padding: 10px;background-color: #DFF2BF;color:  #4F8A10; font-size: 20px; font-weight: bold; text-align: center;">POSTED</div>
                    <div>
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default panel-block panel-title-block panel-gray"> 
        <div class="search-filter-box">
            <div class="col-md-4" style="padding: 10px">
                <select class="1111 form-control search-select-item input-sm pull-left" name="selected_item">
                    @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                </select>  
            </div>
        </div>
        <div class="tab-content">
            <div class="row">
                 <div class="form-group">
                     <div class="col-md-12">
                        <div class="table-responsive load-item-table">
                          <div class="text-center">LOADING ITEM RANGE SALES DISCOUNT...</div>
                        </div>
                     </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default panel-block panel-title-block" id="top">
        <div class="panel-heading">
            <div class="dropdown pull-right">
                <div class="hidden" style="width: 200px;padding: 10px;background-color: #DFF2BF;color:  #4F8A10; font-size: 20px; font-weight: bold; text-align: center;">POSTED</div>
                <div>
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
<div class="div-script">
    <table class="div-item-row-script hide">
       <tr class="tr-draggable">
            <input type="text" class="hidden poline_id" name="poline_id[]">
            <input type="text" class="hidden itemline_po_id" name="itemline_po_id[]">
            <td class="invoice-number-td text-right">1</td>
            <td>
                <select class="1111 form-control select-item input-sm pull-left " name="item_id[]" >
                    @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                    <option class="hidden" value="" />
                </select>
            </td>
            <td>
                <input class="text-right txt-rate compute" readonly="true" type="text" name="item_rate[]"/>
            </td>
            <td><input class="text-center number-input txt-qty compute" type="text" name="item_qty[]"/></td>
            
            <td><input class="text-center number-input txt-new-price compute" type="text" name="item_new_price[]" value="" /></td>
            <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
        </tr>
    </table>
</div>
@endsection
@section('script')
<script type="text/javascript" src="/assets/member/js/accounting_transaction/item_range/item_range.js"></script>
@endsection