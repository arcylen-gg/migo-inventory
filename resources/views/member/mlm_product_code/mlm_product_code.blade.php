@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Sell Product/s</span>
                <small>
                    Repurchasing of products.
                </small>
            </h1>
            <div class="col-md-12">
                <form method="post" action="/member/mlm/report/get" target="_blank">
                {!! csrf_field() !!}
                <input type="hidden" name="skip" value="0">
                <input type="hidden" name="take" value="9999">
                <input type="hidden" name="user_id" value="{{$user_a}}">
                <input type="hidden" name="warehouse_id" value="{{$warehouse_a}}">

                <input type="hidden" name="report_choose" value="product_sales_report">
                <input type="hidden" name="pdf" value="excel" class="pdf_input">
                <div class="col-md-3">
                    <span><small style="color:gray">From</small></span>
                    <input type="date" class="form-control" name="from" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
                </div>
                <div class="col-md-3">
                    <span><small style="color:gray">To</small></span>
                    <input type="date" class="form-control" name="to" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
                </div>
                <div class="col-md-3">
                    <span><small style="color:gray">CASHIER'S END SESSION REPORT</small></span><br>
                    <button class="btn btn-custom-green-white btn-pdf" onclick="$('.pdf_input').val('excel');"><i class="fa fa-file-excel-o"></i> Export to Excel</button>
                    <button class="btn btn-custom-red-white btn-pdf" onclick="$('.pdf_input').val('true');"><i class="fa fa-file-pdf-o"></i> Export to PDF</button>
                </div>
                </form>
                <div class="col-md-3">
                    <a href="/member/mlm/product_code/sell" class="panel-buttons btn btn-primary pull-right">Sell Product</a>
                    <a href="/member/mlm/product_code/receipt" class="panel-buttons btn btn-default pull-right">View Receipt(s)</a>
                </div>
            </div>
            
        </div>
    </div>
</div>
@if($ismerchant != 1)
<!-- NO PRODUCT YET -->
<div class="panel panel-default panel-block panel-title-block panel-gray ">
    <ul class="nav nav-tabs">
        <li class="active cursor-pointer"><a class="cursor-pointer" data-toggle="tab" href="#pending-codes"><i class="fa fa-star"></i> Unused Codes</a></li>
        <li class="cursor-pointer"><a class="cursor-pointer" data-toggle="tab" href="#used-codes"><i class="fa fa-heart"></i> Used Codes</a></li>
        <li class="cursor-pointer"><a class="cursor-pointer" data-toggle="tab" href="#blocked-codes"><i class="fa fa-trash"></i> Blocked Codes</a></li>
    </ul>
    <div class="search-filter-box">
        <div class="col-md-4" style="padding: 10px">

        </div>  
        <div class="col-md-4 col-md-offset-4" style="padding: 10px">
            <div class="input-group">
                <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control search_name" placeholder="Search by Activation Code or Sold To" aria-describedby="basic-addon1">
            </div>
        </div>  
    </div>
    <div class="tab-content codes_container">
        <div id="pending-codes" class="tab-pane fade in active">
            <div class="form-group order-tags"></div>
            <div class="load-data" target="code-unused-a">
                <div id="code-unused-a">
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <thead style="text-transform: uppercase">
                                <tr>
                                    <th>Activation Code</th>
                                    <th>Item</th>
                                    <th>Sold To</th>
                                    <th class="text-center">Date Issued</th>
                                    <th class="text-center">Product Issued</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($_code_unused as $code)
                                    <tr>
                                        <td>{{$code->item_activation_code}}</td>
                                        <td>{{$code->item_name}}</td>
                                        <td class="text-left"><a href="javascript:">{{$code->first_name}}</a></td>
                                        <td class="text-center">{{$code->item_date_created}}</td>
                                        <td class="text-center"><input type="checkbox" {{$code->item_code_product_issued == 0 ? "" : "checked"}} disabled="disabled"></td>
                                        <td>
                                            <a link="/member/mlm/product_code/block/{{$code->item_code_id}}" href="javascript:" class="popup">BLOCK</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <center>{!! $_code_unused->render() !!}</center>
                    </div>
                </div>
            </div>    
        </div>
        <div id="used-codes" class="tab-pane fade in">
            <div class="form-group order-tags"></div>
            <div class="load-data" target="code-unused-b">
                <div id="code-unused-b">
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <thead style="text-transform: uppercase">
                                <tr>
                                    <th>Activation Code</th>
                                    <th>Item</th>
                                    <th>Sold To</th>
                                    <th class="text-center">Date Issued</th>
                                    <th class="text-center">Product Issued</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($_code_used as $code)
                                    <tr>
                                        <td>{{$code->item_activation_code}}</td>
                                        <td>{{$code->item_name}}</td>
                                        <td class="text-left"><a href="javascript:">{{$code->first_name}}</a></td>
                                        <td class="text-center">{{$code->item_date_created}}</td>
                                        <td class="text-center"><input type="checkbox" {{$code->item_code_product_issued == 0 ? "" : "checked"}} disabled="disabled"></td>
                                        <td>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <center>{!! $_code_used->render() !!}</center>
                    </div>
                </div>
            </div>        
        </div>
        <div id="blocked-codes" class="tab-pane fade in">
            <div class="form-group order-tags"></div>
            <div class="load-data" target="code-unused-c">
                <div id="code-unused-c">
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <thead style="text-transform: uppercase">
                                <tr>
                                    <th>Activation Code</th>
                                    <th>Item</th>
                                    <th>Sold To</th>
                                    <th class="text-center">Date Issued</th>
                                    <th class="text-center">Product Issued</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($_code_blocked as $code)
                                    <tr>
                                        <td>{{$code->item_activation_code}}</td>
                                        <td>{{$code->item_name}}</td>
                                        <td class="text-left"><a href="javascript:">{{$code->first_name}}</a></td>
                                        <td class="text-center">{{$code->item_date_created}}</td>
                                        <td class="text-center"><input type="checkbox" {{$code->item_code_product_issued == 0 ? "" : "checked"}} disabled="disabled"></td>
                                        <td>
                                            
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <center>{!! $_code_blocked->render() !!}</center>
                    </div>
                </div>
            </div>        
        </div>
    </div> 
</div>
@endif
@endsection
@section('script')
<script type="text/javascript">
on_search();
@if(count($errors) > 0)
   @foreach ($errors->all() as $error)
      toastr.error("{{ $error }}");
  @endforeach
@endif

@if (Session::has('success'))
   toastr.success("{{ Session::get('success') }}");
@endif  
@if (Session::has('warning'))
   toastr.warning("{{ Session::get('warning') }}");
@endif

function submit_done(data) 
{
    if(data == "success")
    {
        $(".codes_container").load( "/member/mlm/product_code .codes_container");
        toastr.success('Line Successfully Added!');
        console.log("success");    
        $('#global_modal').modal('toggle');
    }
    else if(data == "success-block")
    {
        $(".codes_container").load( "/member/mlm/product_code .codes_container");
        toastr.success('Successfully Blocked!');
        console.log("success");    
        $('#global_modal').modal('toggle');
    }
    else
    {
    }
}

function get_active_tab()
{
       var active_tab = null;
       $(".tab-pane").each(function() 
       {
           if($(this).hasClass("active"))
           {
               active_tab = this.id;
           }
       });
       
       return active_tab;
}

function on_search()
{
    var typingTimer;                //timer identifier
    var doneTypingInterval = 350;  //time in ms
    
    $('.search_name').on('input', function() 
    {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(doneSearch, doneTypingInterval);
    });
}

function doneSearch() 
{
   var search_name = $(".search_name").val();
   var active_tab  = get_active_tab();
   var url         = encodeURI("/member/mlm/product_code?search_name="+search_name);
   $(".codes_container").load(url+" .codes_container",function() 
   { 
       dynamic_tab(active_tab);
   });
}

function dynamic_tab($tab_name)
{
        var tab_name = $tab_name;
        if(tab_name == "pending-codes")
        {
            $("#pending-codes").addClass("active");
            $("#used-codes").removeClass("active");
            $("#blocked-codes").removeClass("active");
        }
        else if(tab_name == "used-codes")
        {
            $("#pending-codes").removeClass("active");
            $("#used-codes").addClass("active");
            $("#blocked-codes").removeClass("active");           
        }
        else if(tab_name == "blocked-codes")
        {
            $("#pending-codes").removeClass("active");
            $("#used-codes").removeClass("active");
            $("#blocked-codes").addClass("active");           
        }
}
</script>
<script type="text/javascript" src="/assets/member/js/paginate_ajax_multiple.js"></script>
@endsection