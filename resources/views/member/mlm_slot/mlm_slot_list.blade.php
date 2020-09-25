@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Customer Slots</span>
                <small>
                    These are given to the customers as a reference that they are already a member.
                </small>
            </h1>
            <!-- <a href="javascript:" class="panel-buttons btn btn-default pull-right">Export Slots</a> -->
            <button  class="panel-buttons btn btn-default pull-right popup" link="/member/mlm/slot/add" size="lg">Add New Slot</button>
            
        </div>
    </div>
</div>
<style type="text/css">

</style>

<!-- NO PRODUCT YET -->
<div class="panel panel-default panel-block panel-title-block panel-gray ">
    <ul class="nav nav-tabs">
        <li class="active cursor-pointer"><a class="cursor-pointer" data-toggle="tab" onclick="active_inactive(0)"><i class="fa fa-star" ></i> Active Slots</a></li>
        <li class="cursor-pointer"><a class="cursor-pointer" data-toggle="tab" onclick="active_inactive(1)"><i class="fa fa-trash"></i> Inactive Slots</a></li>
    </ul>
    
    <div class="search-filter-box">
        <div class="col-md-2" style="padding: 10px">

            <select class="form-control" onchange="change_membership(this)">
                <option value="0">All Membership</option>
                @if(count($membership) != 0)
                    @foreach($membership as $key => $value)
                        <option value="{{$value->membership_id}}">{{$value->membership_name}}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="col-md-2" style="padding: 10px">
            <select class="form-control" onchange="change_membership_type(this)">
                <option value="0">All Type</option>
                <option value="PS">Paid Slot</option>
                <option value="FS">Free Slot</option>
                <option value="CD">CD Slot</option>
            </select>
        </div>
        <div class="col-md-4 col-md-offset-4" style="padding: 10px">
            <div class="input-group">
                <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control search_filter" placeholder="Search by Customer ID or Slot ID" aria-describedby="basic-addon1" onkeyup="search_slot_f(this)">
            </div>
        </div>  
    </div>
    
    <div class="tab-content">
        <div id="all-orders" class="tab-pane fade in active">
            <div class="form-group order-tags"></div>
            <div class="data_filtered_mlm_code">

            </div>
        </div>
    </div>
    
    
</div>

@endsection

@section('script')
<script>

var membership = 0;
var membership_type = 0;
var search_slot = 0;
var slot_active = 0;
var paginate = 1;
ajax_load_membership();
function slot_created(data)
{
    var link="/member/mlm/slot/view/" + data.slot_id;
    action_load_link_to_modal(link, 'lg');
    ajax_load_membership();
}

function submit_done(data)
{
  if(data.status == 'success_transfer')
  {
    $('#global_modal').modal('toggle');
    toastr.success("Transfer done.");
    ajax_load_membership();
  }
}

function ajax_load_membership()
{
    $('.data_filtered_mlm_code').html('<center><div class="loader-16-gray"></div></center>');
    var request = "?";
    if(membership != 0)
    {
        request  = request + "membership=" + membership;
    }
    if(membership_type != 0)
    {
        if(request != "?")
        {
            request  = request + "&";
        }
        request  = request + "membership_type=" + membership_type;
    }
    if(search_slot != 0)
    {
        if(request != "?")
        {
            request  = request + "&";
        }
        search_slot = search_slot.replace(' ', '+');
        request  = request + "search_slot=" + search_slot ;
    }
    if(slot_active != null)
    {
        if(request != "?")
        {
            request  = request + "&";
        }
        request  = request + "slot_active=" + slot_active ;
    }
    if(paginate != null)
    {
        if(request != "?")
        {
            request  = request + "&";
        }
        request  = request + "page=" + paginate ;
    }
    $('.data_filtered_mlm_code').load(request);
}
function change_membership(ito)
{
    membership = ito.value;
    paginate = 1;
    ajax_load_membership();
}
function change_membership_type(ito)
{
    membership_type = ito.value;
    paginate = 1;
    ajax_load_membership();
}
function search_slot_f(ito)
{
    search_slot = ito.value;
    paginate = 1;
    ajax_load_membership();
}
function active_inactive(slot_active_filter)
{
    slot_active = slot_active_filter;
    paginate = 1;
    ajax_load_membership();
}

$(document).ready(function() {
        $(document).on('click', '.pagination a', function (e) {
            paginate = $(this).attr('href').split('page=')[1];
            console.log(paginate);
            ajax_load_membership();
            e.preventDefault();
        });
    });
</script>

@endsection
