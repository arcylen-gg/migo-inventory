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
            <a href="/member/ecommerce/product/add" class="panel-buttons btn btn-custom-primary pull-right">Add Product</a>
            <a class="panel-buttons btn btn-custom-white pull-right">Import</a>
        </div>
    </div>
</div>

<!-- NO PRODUCT YET -->
@if(count($_product) == 0 && count($_product_archived) == 0)
<div class="row">
    <div class="col-md-12 text-center">
        <div class="trial-warning clearfix">
            <div class="no-product-title">Add your first product</div>
            <div class="no-product-subtitle">You’re just a few steps away from receiving your first order.</div>
        </div>
    </div>
</div>
@else

<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-body">
        <ul class="nav nav-tabs">
            <li class="{{$active_tab}} cursor-pointer"><a class="cursor-pointer" data-toggle="tab" href="#actives"><i class="fa fa-star"></i> Active Product</a></li>
            <li class="{{$inactive_tab}} cursor-pointer"><a class="cursor-pointer" data-toggle="tab" href="#inactives"><i class="fa fa-trash"></i> Archived</a></li>
        </ul>
        
        <div class="search-filter-box">
            <div class="col-md-4"  style="padding: 10px; padding-left: 16px;">
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Bulk Action <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-custom">
                        <li><a size="lg" href="/member/ecommerce/product/bulk-edit-price" class="">Edit Product Price</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4 col-md-offset-4" style="padding: 10px">
                <div class="input-group">
                    <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                    <input type="text" class="form-control global-search" url="/member/ecommerce/product/list" data-value="1" placeholder="Search" aria-describedby="basic-addon1">
                </div>
            </div>
        </div>
        <div class="tab-content">
            <div id="actives" class="tab-pane fade in {{$active_tab}}">
                <div class="load-data" target="active_product" filter="active" filteru="anime" column_name="{{Request::input('column_name')}}" in_order="{{Request::input('in_order')}}">
                    <div id="active_product">
                        @include('member.ecommerce_product.ecom_load_product_tbl', ['filter' => 'active'])
                    </div>
                </div>
            </div>
            <div id="inactives" class="tab-pane fade in {{$inactive_tab}} ">
                <div class="load-data" filter="inactive" target="inactive_product" column_name="{{Request::input('column_name')}}" in_order="{{Request::input('in_order')}}">
                    <div id="inactive_product">
                        @include('member.ecommerce_product.ecom_load_product_tbl',['_product' => $_product_archived, 'filter' => 'inactive'])
                    </div>
                </div>
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
<script type="text/javascript" src="/assets/member/js/paginate_ajax_multiple.js"></script>
<script type="text/javascript" src="/assets/member/js/table_sort.js"></script>
<script type="text/javascript" src="/assets/member/js/global_view.js"></script>
<script type="text/javascript">
    @if(Session::has('success'))
        toastr.success('{{Session::get('success')}}');
    @endif
    function submit_done(data)
    {
        if(data.status == "success")
        {
            toastr.success(data.message);
            refresh_data()
            data.element.modal("toggle");
        }
        else
        {
            toastr.error(data.message);
            data.element.modal("toggle");
        }
    }

    function refresh_data()
    {
        $(".load-data[target='active_product']").load("/member/ecommerce/product/list #active_product");
        $(".load-data[target='inactive_product']").load("/member/ecommerce/product/list #inactive_product");
    }
</script>
@endsection