@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Product Codes Receipt</span>
                <small>
                    The product code receipt are shown here.
                </small>
            </h1>
            <!-- <a href="/member/mlm/code/sell" class="panel-buttons btn btn-primary pull-right">Sell Codes</a> -->
            <a href="/member/mlm/product_code" class="panel-buttons btn btn-default pull-right">Back</a>
        </div>
    </div>
</div>
<!-- NO PRODUCT YET -->
<div class="panel panel-default panel-block panel-title-block panel-gray col-md-6 ">
    <ul class="nav nav-tabs">
        <li class="active cursor-pointer"><a class="cursor-pointer" data-toggle="tab" href="#all"><i class="fa fa-star"></i> All Invoice</a></li>
        <!-- <li class="cursor-pointer"><a class="cursor-pointer" data-toggle="tab" href="#archived"><i class="fa fa-trash"></i> Archived Items</a></li> -->
    </ul>
    <div class="search-filter-box">
        <div class="col-md-4" style="padding: 10px">
            <div class="input-group" style="padding-top:22px;">
                <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control search_email" placeholder="Search by email" aria-describedby="basic-addon1" onchange="search_email(this)">
            </div>
        </div>      
        <div class="col-md-3" style="padding: 10px">
            <label for="name" class="control-label">Filter to:</label>
            <div class="input-group">
                <input type="text" class="datepicker form-control input-sm filter_to" value=""/>
            </div>
        </div> 
        <div class="col-md-3" style="padding: 10px">
            <label for="name" class="control-label">Filter by:</label>
            <div class="input-group">
                <input type="text" class="datepicker form-control input-sm filter_by" value=""/>
            </div>
        </div>  
        <div class="col-md-1" style="padding: 10px">
            <div class="input-group" style="padding-top:21px;">
               <button class="btn btn-primary confirm_date_filter" type="button">Filter</button>
            </div>
        </div>  
<!--         <div class="col-md-1" style="padding: 10px">
            <div class="input-group" style="padding-top:21px;">
               <button class="btn btn-primary reset_date_filter" type="button">X</button>
            </div>
        </div>  --> 
    </div>
    <div class="tab-content codes_container">
        <div id="all" class="tab-pane fade in active">
            <div class="form-group order-tags"></div>
            <div class="load-data" target="invoice_append">
                <div id="invoice_append">
            <div class="table-responsive">
                <table class="table table-condensed">
                    <thead style="text-transform: uppercase">
                        <tr>
                            <th>Invoice ID 
                                <a class="filter_default" href="javascript:" @if($date_filter == "default") style="font-size: 25px;" @else style="font-size: 20px;" @endif onclick="sort_by_default()">↓</a> 
                            </th>

                            <th>Customer Name</th>

                            <th>Invoice Date Created
                                <a class="filter_asc"  href="javascript:" @if($date_filter == "asc") style="font-size: 25px;" @else style="font-size: 20px;" @endif onclick="sort_by_asc()">↑</a>   
                                <a class="filter_desc" href="javascript:" @if($date_filter == "desc") style="font-size: 25px;" @else style="font-size: 20px;" @endif onclick="sort_by_desc()">↓</a>
                            </th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    
                        @foreach($_invoice as $invoice)
                        <tr>
                            <td>{{$invoice->item_code_invoice_id}}</td>
                            <td>{{$invoice->first_name}} {{$invoice->middle_name}} {{$invoice->last_name}}</td>
                            <td>{{$invoice->item_code_date_created}}</td>
                            <td>
                                <a href="javascript:" onClick="append_to('/member/mlm/product_code/receipt/view/{{$invoice->item_code_invoice_id}}')" >
                                    View
                                </a>
                                ||
                                <a target="_blank" href="/member/mlm/product_code/receipt/view/{{$invoice->item_code_invoice_id}}?pdf=true" >
                                    PDF
                                </a>
                            </td>
                        </tr> 
                        @endforeach
                     
                    </tbody>

                </table>
                @if(count($_invoice) == 0)
                <center>No Invoice Available</center>
                @endif   
                <center>{!! $_invoice->render() !!}</center>
            </div>
                </div> 
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block panel-gray col-md-6 ">
    <div class="tab-content codes_container">
        <div id="all" class="tab-pane fade in active">
            <div class="form-group order-tags"></div>
            <div class="table-responsive">
                <center>Receipt</center>
                <div class="append_receipt"></div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script type="text/javascript">
    <?php $invoice = Request::input('invoice_id'); ?>
    @if($invoice != null)
        append_to('/member/mlm/product_code/receipt/view/{{$invoice}}')
    @endif
    var filter_date = "default";
    var filter_to   = "";
    var filter_by   = "";

    initialize();

    function initialize()
    {
        $(".filter_to").val("");
        $(".filter_by").val("");
        filter_function();
        filter_reset();
    }
    function append_to(link)
    {
        // $('.append_receipt')..
        $('.append_receipt').html('<center><div class="loader-16-gray"></div></center>');
        $('.append_receipt').load(link);
    }
    function search_email(ito)
    {
        var search = $(ito).val();
        var link ='/member/mlm/product_code/receipt?filter_to='+filter_to+'&filter_by='+filter_by+'&date_filter='+filter_date+'&search_name=' + search + ' #invoice_append';
        $('#invoice_append').load(link);
    }

    function filter_function()
    {
        $(".confirm_date_filter").click(function()
        {
            var search = $(".search_email").val();
            filter_to   = $(".filter_to").val();
            filter_by   = $(".filter_by").val();
            $(".modal-loader").removeClass("hidden");
            var link ='/member/mlm/product_code/receipt?filter_to='+filter_to+'&filter_by='+filter_by+'&date_filter='+filter_date+'&search_name=' + search + ' #invoice_append';
            $('#invoice_append').load(link,function()
            {
                $(".modal-loader").addClass("hidden");
            });
        });
    }

    function filter_reset()
    {
        $(".reset_date_filter").click(function()
        {
            $(".filter_to").val("");
            $(".filter_by").val("");
            var search = $(".search_email").val();
            filter_to   = "";
            filter_by   = "";

            var link ='/member/mlm/product_code/receipt?filter_to='+filter_to+'&filter_by='+filter_by+'&date_filter='+filter_date+'&search_name=' + search + ' #invoice_append';
            $('#invoice_append').load(link);  
        });
    }

    function sort_by_asc()
    {
        // alert(123);
        // $(".filter_asc").unbind("click");
        // $(".filter_asc").bind( "click", function() 
        // {
            var search_value = $(".search_email").val();
            filter_date = "asc";

            $(".modal-loader").removeClass("hidden");
            $('.filter_asc').css('font-size','25px');
            $('.filter_desc').css('font-size','20px');
            $('.filter_default').css('font-size','20px');

            var link ='/member/mlm/product_code/receipt?filter_to='+filter_to+'&filter_by='+filter_by+'&date_filter='+filter_date+'&search_name=' + search_value + ' #invoice_append';
            $('#invoice_append').load(link,function()
            {
                $(".modal-loader").addClass("hidden");
            });

        // });
    }

    function sort_by_desc()
    {
        // $(".filter_desc").unbind("click");
        // $(".filter_desc").bind( "click", function() 
        // {
            var search_value = $(".search_email").val();

            filter_date = "desc";
            $(".modal-loader").removeClass("hidden");
            $('.filter_asc').css('font-size','20px');
            $('.filter_desc').css('font-size','25px');
            $('.filter_default').css('font-size','20px');

            var link ='/member/mlm/product_code/receipt?filter_to='+filter_to+'&filter_by='+filter_by+'&date_filter='+filter_date+'&search_name=' + search_value + ' #invoice_append';
            
            $('#invoice_append').load(link,function()
            {
                $(".modal-loader").addClass("hidden");
            });
        // });
    }

    function sort_by_default()
    {
        // $(".filter_desc").unbind("click");
        // $(".filter_desc").bind( "click", function() 
        // {
            var search_value = $(".search_email").val();

            filter_date = "default";
            $(".modal-loader").removeClass("hidden");
            $('.filter_asc').css('font-size','20px');
            $('.filter_desc').css('font-size','20px');
            $('.filter_default').css('font-size','25px');

            var link ='/member/mlm/product_code/receipt?date_filter='+filter_date+'&search_name=' + search_value + ' #invoice_append';
           
            $('#invoice_append').load(link,function()
            {
                $(".modal-loader").addClass("hidden");
            });
        // });
    }
</script>
<script type="text/javascript" src="/assets/member/js/paginate_ajax_multiple.js"></script>
<!--     <script type="text/javascript">
        $(".popup").click(function()
        {
            $(".modal-content").css("min-height","700px");
        });
    </script> -->
@endsection