@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Product Order Lists</span>
                <small>
                    List of orders
                </small>
            </h1>
            <!-- <a class="panel-buttons btn btn-custom-primary pull-right" href="product_order/create_order" >Create Invoice</a> -->
<!--             <a class="panel-buttons btn btn-default pull-right" href="product_order/create_order" >Sales by product (Report)</a>
            <a class="panel-buttons btn btn-default pull-right" href="product_order/create_order" >Sales by month (Report)</a>
            <a class="panel-buttons btn btn-default pull-right" href="product_order/create_order" >Sales by variant (Report)</a> -->
        </div>
    </div>
</div>

<!-- NO PRODUCT YET -->


<div class="panel panel-default panel-block panel-title-block panel-gray ">
    <div class="col-md-2 pull-right">
    <!-- <br/> -->
        Filtered By:
        <select class="form-control filter_select" name="type_chosen" onchange="on_change_filter($(this).val())">
            <option value="All" {{Request::input("type_chosen") == "All" || Request::input("type_chosen") == "" ? 'selected' : ''}}>All</option> 
            @foreach($_filter as $filter)
                <option value="{{$filter->method_id}}" {{Request::input("type_chosen") == $filter->method_id ? 'selected' : ''}}>{{$filter->method_name}}</option> 
            @endforeach   
        </select>
    </div>    
<!--     <div class="col-md-2 pull-right">
    Filter by:
            <select class="form-control" name="filter_by">
                <option>Payment Type</option>
                <option>Order #</option>
                <option>Customer</option>
                <option>Payment Status</option>
            </select>
    </div> -->
    <ul class="nav nav-tabs">
        <li class="active cursor-pointer"><a class="cursor-pointer" data-toggle="tab"  href="#pending"><i class="fa fa-star"></i> Pending</a></li>
        <li class="cursor-pointer"><a class="cursor-pointer" data-toggle="tab"  href="#failed"><i class="fa fa-star"></i> Failed</a></li>
        <li class="cursor-pointer"><a class="cursor-pointer" data-toggle="tab"  href="#processing"><i class="fa fa-star"></i> Processing</a></li>
        <li class="cursor-pointer"><a class="cursor-pointer" data-toggle="tab"  href="#shipped"><i class="fa fa-star"></i> Shipped</a></li>
        <li class="cursor-pointer"><a class="cursor-pointer" data-toggle="tab"  href="#completed"><i class="fa fa-star"></i> Completed</a></li>
        <li class="cursor-pointer"><a class="cursor-pointer" data-toggle="tab"  href="#onhold"><i class="fa fa-star"></i> On-Hold</a></li>
        <li class="cursor-pointer"><a class="cursor-pointer" data-toggle="tab"  href="#cancelled"><i class="fa fa-trash"></i> Cancelled</a></li>
    </ul>
    <div class="tab-content prod-order-tab">
        <div id="pending" class="tab-pane fade in active">
            <div class="load-data" target="value-pending">
                <div id="value-pending">
                @include("member.product_order.load_product_order",['ec_order' => $ec_order_pending])
                </div>
            </div>
        </div>
        
        <div id="failed" class="tab-pane fade in">
            <div class="load-data" target="value-failed">
                <div id="value-failed">
                    @include("member.product_order.load_product_order",['ec_order' => $ec_order_failed])
                </div>
            </div>
        </div>

        <div id="processing" class="tab-pane fade in ">
            <div class="load-data" target="value-processing">
                <div id="value-processing">
                    @include("member.product_order.load_product_order",['ec_order' => $ec_order_processing])
                </div>
            </div>
        </div>

        <div id="shipped" class="tab-pane fade in ">
            <div class="load-data" target="value-shipped">
                <div id="value-shipped">
                    @include("member.product_order.load_product_order",['ec_order' => $ec_order_shipped])
                </div>
            </div>
        </div> 

        <div id="completed" class="tab-pane fade in ">
            <div class="load-data" target="value-completed">
                <div id="value-completed">
                    @include("member.product_order.load_product_order",['ec_order' => $ec_order_completed])
                </div>
            </div>
        </div> 

        <div id="onhold" class="tab-pane fade in ">
            <div class="load-data" target="value-onhold">
                <div id="value-onhold">
                    @include("member.product_order.load_product_order",['ec_order' => $ec_order_on_hold])
                </div>
            </div>
        </div>

        <div id="cancelled" class="tab-pane fade in ">
            <div class="load-data" target="value-cancelled">
                <div id="value-cancelled">
                    @include("member.product_order.load_product_order",['ec_order' => $ec_order_cancelled])
                </div>
            </div>
        </div>
    </div>
    
</div>

@endsection
@section('script')
<script type="text/javascript" src="/assets/member/js/paginate_ajax_multiple.js"></script>
<script type="text/javascript">
@if (Session::has('success'))
   toastr.success("{{ Session::get('success') }}");
@endif  
@if (Session::has('warning'))
   toastr.warning("{{ Session::get('warning') }}");
@endif  
</script>

<script type="text/javascript">
    $(".filter_select").val("All");
    function on_change_filter(value)
    {
        $(".modal-loader").removeClass("hidden");
        $(".prod-order-tab").load("/member/ecommerce/product_order?type_chosen="+value+" .prod-order-tab", function()
        {
            $(".modal-loader").addClass("hidden");
        });
    }
</script>
@endsection
