@extends('member.layout')
@section('content')

<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Items</span>
                <small>
                    List of items
                </small>
            </h1>
            <a class="panel-buttons btn btn-custom-primary pull-right popup" link="/member/item/add" size="lg" data-toggle="modal" data-target="#global_modal">Add Item</a>
            @if($pis)
            <a href="/member/item/print_new_item" target="_blank" class="pull-right btn btn-custom-white"><i class="fa fa-print"></i> Print New Added Item</a>
            @endif
        </div>
    </div>
</div>

<!-- NO PRODUCT YET -->
<div class="panel panel-default panel-block panel-title-block panel-gray ">
    <ul class="nav nav-tabs">
        <li class="active cursor-pointer"><a class="cursor-pointer" data-toggle="tab" href="#all"><i class="fa fa-star"></i> All Items</a></li>
        <li class="cursor-pointer"><a class="cursor-pointer" data-toggle="tab" href="#pending"><i class="fa fa-trash"></i> Pending Items</a></li>
        <li class="cursor-pointer"><a class="cursor-pointer" data-toggle="tab" href="#archived"><i class="fa fa-trash"></i> Archived Items</a></li>
    </ul>
    <div class="search-filter-box">
        <div class="col-md-4" style="padding: 10px">
            <select class="form-control item_type">
                <option>All</option>
                <option value="1">Inventory</option>
                <option value="2">Non-inventory</option>
                <option value="3">Service</option>
            </select>
        </div>
        <div class="col-md-4 col-md-offset-4" style="padding: 10px">
            <div class="input-group">
                <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control search_name" placeholder="Search by item name" aria-describedby="basic-addon1">
            </div>
        </div>  
    </div>
    <div class="tab-content codes_container">
        <div id="all" class="tab-pane fade in active">
            <div class="form-group order-tags"></div>
            <div class="item load-data" target="item-list-data" column_name="{{Request::input('column_name')}}" in_order="{{Request::input('in_order')}}">
               <div id="item-list-data">                  
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-striped table-condensed">
                            <thead style="text-transform: uppercase">
                                <tr>
                                    <th>
                                        @include("member.load_ajax_data.load_th_header_sort",['link' => '/member/item', 'column_name' => 'item_id','in_order' => Request::input('in_order'),'title_column_name' => 'Item ID'])
                                    </th>
                                    <th>
                                        @include("member.load_ajax_data.load_th_header_sort",['link' => '/member/item', 'column_name' => 'item_name','in_order' => Request::input('in_order'),'title_column_name' => 'Item Name'])
                                    </th>
                                    <th>
                                        @include("member.load_ajax_data.load_th_header_sort",['link' => '/member/item', 'column_name' => 'inventory_count','in_order' => Request::input('in_order'),'title_column_name' => 'Inventory'])
                                    </th>
                                    <th class="text-center">
                                        @include("member.load_ajax_data.load_th_header_sort",['link' => '/member/item', 'column_name' => 'item_price','in_order' => Request::input('in_order'),'title_column_name' => 'Sale Price'])
                                    </th>
                                    <th>Item Details</th>
                                    <th>Item Price History</th>
                                    <!-- <th>Receipt</th> -->
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($_item as $key => $item)
                                <tr>
                                    <td>{{$item->item_id}}</td>
                                    <td>{{$item->item_name}}</td>
                                    <!-- <td>{{$item->item_sku}}</td> -->
                                    <td>
                                        {{$item->inventory_count_um}}<br>
                                        {{$item->inventory_count_um_view}}
                                    </td>
                                    <td>
                                        <span>Unit Price : {{currency("PHP", $item->item_price)}}/ {{$item->multi_abbrev or 'pc'}}</span> 
                                        <span>
                                            <br>
                                            @if($item->um_whole != "")
                                            Whole Price : {{currency("PHP", $item->item_whole_price)}} / {{$item->um_whole or 'pc'}}
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <small>
                                        @if($item->conversion)
                                        U/M : {{$item->conversion}}<br>
                                        @endif
                                        Category : {{$item->type_name}}<br>
                                        Item Type :{{$item->item_type_name}}</small>
                                    </td>
                                    <td>
                                        <small>
                                        {!! $item->item_price_history !!}
                                        </small>
                                    </td>
     <!--                                <td class="text-center">
                                        @if(App\Globals\Item::view_item_receipt($item->item_id) != null)
                                            <a href="#" class="popup" link="/member/item/view_item_receipt/{{$item->item_id}}" size="lg" data-toggle="modal" data-target="#global_modal">View</a>
                                        @else
                                            No Record
                                        @endif
                                    </td> -->
                                    <td>
                                    @if($can_edit_other_item == 1)
                                    <div class="btn-group">
                                        <a class="btn btn-primary btn-grp-primary popup" link="/member/item/edit/{{$item->item_id}}" size="lg" href="javascript:">Edit</a>
                                        <a class="btn btn-primary btn-grp-primary popup" link="/member/item/archive/{{$item->item_id}}" size="sm" href="javascript:"> |<span class="fa fa-trash "> </span> </a>
                                    </div>
                                    @else
                                        @if($user_id == $item->user_id)
                                            <div class="btn-group">
                                                <a class="btn btn-primary btn-grp-primary popup" link="/member/item/edit/{{$item->item_id}}" size="lg" href="javascript:">Edit</a>
                                                <!-- <a class="btn btn-primary btn-grp-primary popup" link="/member/item/archive/{{$item->item_id}}" size="sm" href="javascript:"> |<span class="fa fa-trash "> </span> </a> -->
                                            </div>
                                        @else
                                            <center>You have no access editing this item</center>
                                        @endif                               
                                    @endif
                                    </td>
                                </tr>
                                 @endforeach
                            </tbody>
                        </table>
                    </div> 
                    <div class="text-center pull-right">
                        {!!$_item->render()!!}
                    </div>
                </div>
            </div>
        </div><!-- 
        </div>
    </div>     -->
        <div id="pending" class="tab-pane fade in">
            <div class="form-group order-tags"></div>
            <div class="table-responsive">
                <table class="table table-hover table-bordered table-striped table-condensed">
                    <thead style="text-transform: uppercase">
                        <tr>
                            <th>Item ID</th>
                            <th>Item Name</th>
                            <th>Warehouse/Merchant</th>
                            <th>Item Price</th>
                            <th>Added By</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($_item_pending as $item)
                        <tr>
                            <td>{{$item->item_id}}</td>
                            <td>{{$item->item_name}}</td>
                            <td>{{$item->warehouse_name}}</td>
                            <td>{{$item->item_price}}</td>
                            <td>{{$item->user_email }}</td>
                            <td>
                                @if($can_approve_item_request == 1)
                                    <div class="btn-group">
                                        <a link="/member/item/approve_request/{{$item->item_id}}" href="javascript:" class="btn btn-primary btn-grp-primary popup" size="lg">Approve</a>
                                    </div>
                                    <!-- <div class="btn-group">
                                        <a link="/member/item/decline_request/{{$item->item_id}}" href="javascript:" class="btn btn-primary btn-grp-primary popup">Decline</a>
                                    </div> -->
                                @else
                                    <center>You have no access approving items</center>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div id="archived" class="tab-pane fade in">
            <div class="form-group order-tags"></div>
            <div class="table-responsive">
                <table class="table table-hover table-bordered table-striped table-condensed">
                    <thead style="text-transform: uppercase">
                        <tr>
                            <th>Item ID</th>
                            <th>Item Name</th>
                            <th>Item SKU</th>
                            <th>Item Category</th>
                            <th>Item Type</th>
                            <th>Item Price</th>
                            <th>Item Date Created</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($_item_archived as $item)
                        <tr>
                            <td>{{$item->item_id}}</td>
                            <td>{{$item->item_name}}</td>
                            <td>{{$item->item_sku}}</td>
                            <td>{{$item->type_name}}</td>
                            <td>{{$item->item_type_name}}</td>
                            <td>{{currency("PHP", $item->item_price)}}</td>
                            <td>{{date("F d, Y", strtotime($item->item_date_created))}}</td>
                            <td>
                                @if($can_edit_other_item == 1)
                                <div class="btn-group">
                                    <a link="/member/item/restore/{{$item->item_id}}" href="javascript:" class="btn btn-primary btn-grp-primary popup">Restore</a>
                                </div>
                                @else
                                <center>You have no access editing this item</center>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    
</div>
@endsection
<style type="text/css">
    .max-450
    {
        max-height:800px !important;
    }
</style>
@section('script')
<script type="text/javascript" src="/assets/member/js/paginate_ajax_multiple.js"></script>
<script type="text/javascript" src="/assets/custom_plugin/digimaTable/digimaTable.js"></script>
<script type="text/javascript">

$( "div" ).digimaTable({
    url    : "/member/item/data",
    column : ['item_id','item_name']
});
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

function submit_done_item(data)
{
    if(data.message == "Success")
    {
        toastr.success("Success");
        $(".codes_container").load("/member/item .codes_container"); 
        $('#global_modal').modal('toggle');
        $('.multiple_global_modal').modal('hide');
    }   
    else
    {
        $(data.error).each(function( index )
        {
          toastr.warning(data.error[index]);
        });
    }
}
function submit_done(data)
{
    if(data.message == "Success")
    {
        toastr.success("Success");
        $(".codes_container").load("/member/item .codes_container"); 
        $('#global_modal').modal('toggle');
    }
    else if(data.message == "Sucess-archived")
    {
        toastr.success("Successfully archived");
        $(".codes_container #all").load("/member/item .codes_container #all");
        $(".codes_container #archived").load("/member/item .codes_container #archived");
        $('#global_modal').modal('toggle');        
    }    
    else if(data.message == "Success-restored")
    {
        toastr.success("Successfully restored");
        $(".codes_container #all").load("/member/item .codes_container #all"); 
        $(".codes_container #archived").load("/member/item .codes_container #archived"); 
        $('#global_modal').modal('toggle');        
    }
    else
    {
        $(data.error).each(function( index )
        {
          toastr.warning(data.error[index]);
        });
    }
}
function submit_done_for_page(data)
{
    if(data.status == "transfer_success")
    {
        location.href = "/member/item/warehouse"; 
        toastr.success("Successfully transfer");
        $(data.target).html(data.view);    
    }
    else if(data.status == "success")
    {
        toastr.success("Success");
        $(".codes_container").load("/member/item .codes_container"); 
        $(data.target).html(data.view);
        $('#global_modal').modal('toggle');
        $('.multiple_global_modal').modal('hide');
    }
    else if(data.status == "success-serial")
    {
        toastr.success("Success");
        $(data.target).html(data.view);
        
        prompt_confirm();
    }
    else if(data.status == "success-adding-serial")
    {
        toastr.success("Success");
        $(data.target).html(data.view);
        $(".warehouse-container").load("/member/item/warehouse .warehouse-container"); 
        $('#global_modal').modal('toggle');
        $('.multiple_global_modal').modal('hide');
    }
    else if(data.status == "confirmed-serial")
    {
        prompt_serial_number();
    }
    else
    {
        $(data.error).each(function( index )
        {
          toastr.warning(data.error[index]);
        });
    }
}
function get_active_tab()
{
   var active_tab = null;
   $(".tab-pane").each(function() 
   {
       if($(this).hasClass("active"))
       {
            if(this.id != "media-library")
            {
              active_tab = this.id;
            }
       }
   });

   return active_tab;
}

function on_change()
{
    $('.item_type').val("All");
    $('.search_name').val("");
    $(".item_type").change(function()
    {
       var request     = $(".item_type").val();
       var search_name = $(".search_name").val();
       var active_tab  = get_active_tab();

       $(".codes_container").load("/member/item?item_type="+request+"&search_name="+search_name+" .codes_container",function() 
       { 
           dynamic_tab(active_tab);
           $(".item.load-data").attr("item_type",request);
           $(".item.load-data").attr("search_name",search_name);
       });
    });
}

function on_search()
{
    var typingTimer;               //timer identifier
    var doneTypingInterval = 350;  //time in ms (5 seconds)
    
    $('.search_name').on('input', function() {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(doneSearch, doneTypingInterval);
    });
}

function doneSearch() 
{
   var request     = $(".item_type").val();
   var search_name = $(".search_name").val();
   var active_tab  = get_active_tab();
   $(".codes_container").load("/member/item?item_type="+request+"&search_name="+search_name+" .codes_container",function() 
   { 
       dynamic_tab(active_tab);
        $(".item.load-data").attr("item_type",request);
        $(".item.load-data").attr("search_name",search_name);
   });
}

function dynamic_tab($tab_name)
{
        var tab_name = $tab_name;
        if(tab_name == "archived")
        {
            $("#archived").addClass("active");
            $("#all").removeClass("active");
        }
        else if(tab_name == "all")
        {
            $("#all").addClass("active");
            $("#archived").removeClass("active");                
        }
}
</script>
@endsection