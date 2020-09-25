@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Membership Codes</span>
                <small>
                    The codes are required when creating slots.
                </small>
            </h1>
            <a href="/member/mlm/code/sell" class="panel-buttons btn btn-primary pull-right">Sell Codes</a>
            <a href="/member/mlm/code/receipt" class="panel-buttons btn btn-default pull-right">View Receipt(s)</a>
        </div>
    </div>
</div>

<!-- NO PRODUCT YET -->
<div class="panel panel-default panel-block panel-title-block panel-gray ">
    <ul class="nav nav-tabs">
        <li class="active cursor-pointer"><a class="cursor-pointer" data-toggle="tab" href="#pending-codes" ><i class="fa fa-star"></i> Pending Codes</a></li>
        <li class="cursor-pointer"><a class="cursor-pointer" data-toggle="tab" href="#used-codes" ><i class="fa fa-heart"></i> Used Codes</a></li>
        <li class="cursor-pointer"><a class="cursor-pointer" data-toggle="tab" href="#blocked-codes" ><i class="fa fa-trash"></i> Blocked Codes</a></li>
    </ul>
    <div class="search-filter-box">
        <div class="col-md-2" style="padding: 10px">
            <select class="form-control membership_type">
                <option value="All">All Membership</option>
                @foreach($_membership_package as $package)
                    <option value="{{$package->membership_package_id}}">{{$package->membership_package_name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2" style="padding: 10px">
            <select class="form-control slot_type">
                <option value="All">All Type</option>
                <option value="PS">Paid Slot</option>
                <option value="FS">Free Slot</option>
                <option value="CD">CD Slot</option>
            </select>
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
            <div class="table-responsive">
                <div class="load-data" target="unused-code">
                    <div id="unused-code">
                    <table class="table table-condensed">
                        <thead style="text-transform: uppercase">
                            <tr>
                                <th>Activation Code</th>
                                <th>Membership</th>
                                <th>Package</th>
                                <th class="text-center">Type</th>
                                <th>Sold To</th>
                                <th class="text-center">Date Issued</th>
                                <th class="text-center">Product Issued</th>
                                <!-- <th class="text-center">Receipt</th> -->
                                <th class="text-center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($_code_unused as $code)
                                <tr>
                                    <td>{{$code->membership_activation_code}}</td>
                                    <td>{{$code->membership_name}}</td>
                                    <td><a href="javascript:">{{$code->membership_package_name}}</a></td>
                                    <td class="text-center">{{$code->membership_type}}</td>
                                    <td class="text-left">{{$code->membership_code_invoice_f_name}} {{$code->membership_code_invoice_m_name}} {{$code->membership_code_invoice_l_name}}</td>
                                    <td class="text-center">{{$code->membership_date_created}}</td>
                                    <td class="text-center"><input type="checkbox" {{$code->membership_code_product_issued == 0 ? "" : "checked"}} disabled="disabled"></td>

                                    <td>
                                        <a link="/member/mlm/code/block/{{$code->membership_code_id}}" href="javascript:" class="popup">BLOCK</a>
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
            <div class="table-responsive">
                <div class="load-data" target="used-code-filter">
                    <div id="used-code-filter">
                    <table class="table table-condensed">
                        <thead style="text-transform: uppercase">
                            <tr>
                                <th>Activation Code</th>
                                <th>Membership</th>
                                <th>Package</th>
                                <th class="text-center">Type</th>
                                <th>Sold To</th>
                                <th class="text-center">Date Issued</th>
                                <th class="text-center">Product Issued</th>
                                <!-- <th class="text-center">Receipt</th> -->
                                <th class="text-center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($_code_used as $code)
                                <tr>
                                    <td>{{$code->membership_activation_code}}</td>
                                    <td>{{$code->membership_name}}</td>
                                    <td><a href="javascript:">{{$code->membership_package_name}}</a></td>
                                    <td class="text-center">{{$code->membership_type}}</td>
                                    <td class="text-left">{{$code->membership_code_invoice_f_name}} {{$code->membership_code_invoice_m_name}} {{$code->membership_code_invoice_l_name}}</td>
                                    <td class="text-center">{{$code->membership_date_created}}</td>
                                    <td class="text-center"><input type="checkbox" {{$code->membership_code_product_issued == 0 ? "" : "checked"}} disabled="disabled"></td>
    <!--                                 <td class="text-center">
                                        <a href="/member/mlm/code/receipt/{{$code->membership_code_id}}">View</a>
                                    </td>   -->                              
                                    <td>
                                        <!--<a link="/member/mlm/code/block/{{$code->membership_code_id}}" href="javascript:" class="popup">BLOCK</a>-->
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
            <div class="table-responsive">
                <div class="load-data" target="blocked-code-filter">
                    <div id="blocked-code-filter">
                <table class="table table-condensed">
                    <thead style="text-transform: uppercase">
                        <tr>
                            <th>Activation Code</th>
                            <th>Membership</th>
                            <th>Package</th>
                            <th class="text-center">Type</th>
                            <th>Sold To</th>
                            <th class="text-center">Date Issued</th>
                            <th class="text-center">Product Issued</th>
                            <th class="text-center"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($_code_blocked as $code)
                            <tr>
                                <td>{{$code->membership_activation_code}}</td>
                                <td>{{$code->membership_name}}</td>
                                <td><a href="javascript:">{{$code->membership_package_name}}</a></td>
                                <td class="text-center">{{$code->membership_type}}</td>
                                <td class="text-left">{{$code->membership_code_invoice_f_name}} {{$code->membership_code_invoice_m_name}} {{$code->membership_code_invoice_l_name}}</td>
                                <td class="text-center">{{$code->membership_date_created}}</td>
                                <td class="text-center"><input type="checkbox" {{$code->membership_code_product_issued == 0 ? "" : "checked"}} disabled="disabled"></td>
                                <td>
                                    <!--<a link="/member/mlm/code/block/{{$code->membership_code_id}}" href="javascript:" class="popup">BLOCK</a>-->
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
@endsection
@section('script')
<script type="text/javascript">
on_change();
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
// <center><div class="loader-16-gray"></div></center>
function show_load_container()
{
    $(".codes_container").html('<center><div class="loader-16-gray"></div></center>');
}
function submit_done(data) 
{
    if(data == "success")
    {
        show_load_container();
        $(".codes_container").load( "/member/mlm/code .codes_container");
        toastr.success('Code Blocked!');
        console.log("success");    
        $('#global_modal').modal('toggle');
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

function on_change()
{
    $('.membership_type').val("All");
    $('.slot_type').val("All");
    $('.search_name').val("");
    $(".membership_type").change(function()
    {
       var request     = $(".membership_type").val();
       var request2    = $(".slot_type").val();
       var search_name = $(".search_name").val();
       var active_tab  = get_active_tab();
       var url         = encodeURI("/member/mlm/code?slot_type="+request2+"&membership_type="+request+"&search_name="+search_name);
       show_load_container();
       $(".codes_container").load(url+" .codes_container",function() 
       { 
           dynamic_tab(active_tab);
       });
    });
    
    $(".slot_type").change(function()
    {
       var request     = $(".membership_type").val();
       var request2    = $(".slot_type").val();
       var search_name = $(".search_name").val();
       var active_tab  = get_active_tab();
       var url         = encodeURI("/member/mlm/code?slot_type="+request2+"&membership_type="+request+"&search_name="+search_name);
       show_load_container();
       $(".codes_container").load(url+" .codes_container",function() 
       { 
           dynamic_tab(active_tab);
       });
    });
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
   var request     = $(".membership_type").val();
   var request2    = $(".slot_type").val();
   var search_name = $(".search_name").val();
   var active_tab  = get_active_tab();
   var url         = encodeURI("/member/mlm/code?slot_type="+request2+"&membership_type="+request+"&search_name="+search_name);
   show_load_container();
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
var url = document.location.toString();
if (url.match('#')) {
    $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
} 

// Change hash for page-reload
// $('.nav-tabs a').on('shown.bs.tab', function (e) {
//     window.location.hash = e.target.hash;
// })


</script>
<script type="text/javascript" src="/assets/member/js/paginate_ajax_multiple.js"></script>
@endsection