@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Products</span>
                <small>
                Manage list of products on your website
                </small>
            </h1>
            <a href="/member/product/add" class="panel-buttons btn btn-primary pull-right">Add Product</a>
            <a class="panel-buttons btn btn-custom-white pull-right">Import</a>
        </div>
    </div>
</div>

<!-- NO PRODUCT YET -->
@if(count($_product) == 0)
<div class="row">
    <div class="col-md-12 text-center">
        <div class="trial-warning clearfix">
            <div class="no-product-title">Add your first product</div>
            <div class="no-product-subtitle">You’re just a few steps away from receiving your first order.</div>
        </div>
    </div>
</div>
@else
<div class="panel panel-default panel-block panel-title-block panel-gray ">
    <ul class="nav nav-tabs">
        <li class="active cursor-pointer"><a class="cursor-pointer" data-toggle="tab">All Products</a></li>
    </ul>
    <div class="tab-content">
        <div id="all-orders" class="tab-pane fade in active">
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon custom-addon">
                        <a href="#" class="btn btn-default btn-custom" id="popover" data-placement="bottom">Filter Orders&nbsp;<span class="caret"></span></a>
                        <div id="popover-content" class="hide padding10">
                            Show all orders where:
                            <div class="form-horizontal padding10">
                                <div class="form-group">
                                    <select class="form-control" id="select-filter">
                                        <option value="reset">Select a filter...</option>
                                        <option value="visibility">Visibility</option>
                                        <option value="product_type">Product Type</option>
                                        <option value="product_vendor">Product Vendor</option>
                                        <option value="tagged_with">Tagged with</option>
                                    </select>
                                </div>
                                <div class="filter-tag-div form-group">
                                </div>
                                <div class="form-group filter-btn-div">
                                    <button class="btn btn-default btn-def-white">Add filter</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <i class="fa fa-search fa-search-custom" aria-hidden="true"></i>
                    <input type="search" class="form-control text-search" placeholder="Start typing to search for orders..." name="">
                </div>
            </div>
            <div class="form-group order-tags"></div>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <td class="col-md-1"><input type="checkbox" name="" ></td>
                            <td class="col-md-2"></td>
                            <td class="col-md-3">Product</td>
                            <td class="col-md-3">Inventory</td>
                            <td class="col-md-2">Type</td>
                            <td class="col-md-2">Vendor</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($_product as $product)
                        <tr>
                            <td><input type="checkbox" name="" ></td>
                            <td><img src="{{ $product->image_path }}" style="width:50px"></td>
                            <td>
                                <a href="/member/product/edit/{{ $product->product_id }}">
                                    {{ $product->item_name }}
                                </a>
                            </td>
                            <td>{{ $product->item_quantity }}</td>
                            <td>{{ $product->type_name }}</td>
                            <td>{{ $product->vendor_name }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

@endsection
@section('css')
<link rel="stylesheet" type="text/css" href="/assets/member/css/product.css">
<link rel="stylesheet" type="text/css" href="/assets/member/css/order.css">
@endsection
@section('script')
<script type="text/javascript" src="/assets/member/js/global_view.js"></script>
@endsection