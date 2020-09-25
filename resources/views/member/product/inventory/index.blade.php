@extends('member.layout')

@section('css')
<link rel="stylesheet" href="/assets/member/css/customBTN.css" type="text/css" />
<link rel="stylesheet" href="/assets/member/css/inventory.css" type="text/css" />
@endsection

@section('content')
<input type="hidden" id="_token" value="{{csrf_token()}}">
<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title"><span class="color-gray">Products/</span>Inventory</span>
                <small>
                Manage your inventory
                </small>
            </h1>
            
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-body">
        <div class="form-horizontal">
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-btn">
                            <button class="btn btn-custom-white-gray btn-filter" rel="popover" popover-title="Show all variants where:"  data-placement="bottom">Filter products&nbsp;<i class="fa fa-caret-down" aria-hidden="true"></i></button>
                            <div class="hide" id="popover-filter">
                                <div class="form-horizontal">
                                    <div class="form-group" style="display:block">
                                        <label for="" class="col-md-12">Show all variants where:</label>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <select class="form-control filter-select">
                                                <option value="">Select filter</option>
                                                <option value="type">Product type</option>
                                                <option value="vendor">Product vendor</option>
                                                <option value="quantity">Product quantity</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group filter-type display-none">
                                        <div class="col-md-12">
                                            <select class="form-control filter-type-sel">
                                                <option value="">Select value...</option> 
                                                @foreach($product_type as $type)
                                                <option value="{{$type->type_id}}">{{$type->type_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group filter-vendor display-none">
                                        <div class="col-md-12">
                                            <select class="form-control filter-vendor-sel">
                                                <option value="">Select value...</option> 
                                                @foreach($product_vendor as $vendor)
                                                <option value="{{$vendor->vendor_id}}">{{$vendor->vendor_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group filter-quantity display-none">
                                        <div class="col-md-12">
                                            <select class="form-control filter-quantity-sel">
                                                <option value="">Select value...</option> 
                                                <option value="equal">equal to</option> 
                                                <option value="not equal">not equal to</option> 
                                                <option value="less than">less than</option> 
                                                <option value="greater than">greater than</option> 
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group filter-quantity-div display-none">
                                        <div class="col-md-12">
                                            <input type="number" class="form-control filter-quantity-number text-right">
                                        </div>
                                    </div>
                                    <div class="form-group filter-button display-none">
                                        <div class="col-md-12">
                                            <button class="btn btn-custom-white pull-right btn-filter-sub" data-trigger="">Filter</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </span>
                        <span class="pos-absolute f-16 color-gray margin-5-5"><i class="fa fa-search" aria-hidden="true"></i></span>
                        <input type="text"  name="" class="form-control indent-13" placeholder="Start typing to search for products...">
                    </div>
                </div>
                
            </div>
            <div class="form-group">
                <div class="col-md-12 table-responsive">
                    <table class="table table-condensed table-hover">
                        <thead>
                            <tr>
                                <th></th>
                                <th class="text-center">
                                    Product variant
                                </th>
                               
                                <th class="text-center">
                                    SKU
                                </th>
                                <th class="text-center">
                                    When sold out
                                </th>
                                <th class="text-center">
                                    Incoming
                                </th>
                                <th class="text-center">
                                    Quantity
                                </th>
                                <th class="text-center">
                                    Update quantity
                                </th>
                            </tr>
                        </thead>
                        <tbody class="tbl-filter">
                        @foreach($item as $variant)
                        <tr>
                            <td class="text-center">
                                <img src="{{$variant['image_path']}}" class="img-50-50"></img>
                            </td>
                            <td>
                                <a href="#">{{$variant['product_name']}}</a><Br>
                                <a href="#">{{$variant['variant_name']}}</a>
                            </td>
                            <td class="text-center">
                                {{$variant['variant_sku']}}
                            </td>
                            <td class="text-center">
                                {{$variant['variant_allow_oos_purchase'] == 1?'Continue selling':'Stop selling'}}
                            </td>
                            <td class="text-center">
                                <a href=#>0</a>
                            </td>
                            <td class="text-center">
                                <span id="quantity-{{$variant['variant_id']}}">{{$variant['variant_inventory_count']}}</span>
                            </td>
                            <td>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <button class="btn btn-custom-white btn-add" id="add-{{$variant['variant_id']}}" data-content="{{$variant['variant_id']}}" disabled>Add</button>
                                    </span>
                                    <span class="input-group-btn">
                                        <button class="btn btn-custom-white border-radius-0 btn-set" id="set-{{$variant['variant_id']}}" data-content="{{$variant['variant_id']}}">Set</button>
                                    </span>
                                    <input type="text" class="form-control text-right" id="txt-quantity-{{$variant['variant_id']}}" value="0">
                                    <span class="input-group-btn">
                                        <button class="btn btn-custom-primary btn-save" id="save-{{$variant['variant_id']}}" data-content="{{$variant['variant_id']}}" data-trigger="add">Save</button>
                                    </span>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12 text-center">
                    {!!$_inventory->render()!!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript" src="/assets/member/js/inventory.js"></script>
@endsection