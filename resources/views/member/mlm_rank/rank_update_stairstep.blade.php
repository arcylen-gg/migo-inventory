@extends('member.layout')
@section('content')

<!-- <form method="post"> -->
  <div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
      <div>
        <i class="fa fa-angle-double-up" aria-hidden="true"></i>
        <h1>
          <span class="page-title">Rank Update <i class="fa fa-angle-double-right"></i> Update</span>
          <small>
            Update Ranks of SLots of Customers
          </small>
        </h1>
        <button type="button" id="button1" class="btn btn-sm update_ranking"><span><i class="fa fa-refresh" aria-hidden="true"></i></span>  Update Ranking</button>
        <button type="button" id="button2" class="btn btn-sm" data-toggle="modal" data-target="#classModal">Rank Update History</button>
        <button type="button" id="button3" class="btn btn-sm" data-toggle="modal" data-target="#classModal2">View rank logs</button>
      </div>
    </div>
  </div>
<!-- </form> -->

<div class="panel panel-default update_rank_panel" style="display:none;">
    <div class="panel-body">
        <div class="row">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
<!--             <div class="col-md-6">
                    <label>Start Date</label>
                    <input type="text" class="datepicker form-control input-sm start_date" name="start_date" value=""/>
            </div>            
            <div class="col-md-6">
                    <label>End Date</label>
                    <input type="text" class="datepicker form-control input-sm end_date" name="end_date" value=""/>
            </div> -->
            <div class="col-md-6">
                <label for="rematrix_status">Stairstep Status</label>
                <input class="form-control" id="stairstep_status" value="----" disabled>
            </div>            
            <div class="col-md-6">
                <label for="rematrix_status">Progress Bar</label>
                <div id="progressbar"></div>
            </div>
            <div class="col-md-12" style="min-height: 10px;"></div>
            <div class="col-md-2 pull-right">
                <button class="form-control btn-custom-primary distribute_stairstep">Start Update</button>
            </div>
        </div>
    </div>
</div>


<div class="panel panel-default panel-block panel-title-block" id="top">
  <div class="panel-heading">
  <br>
  <br>         
  <table class="table table-bordered table-condensed">
    <thead>
      <tr>
        <th><center>Customer Name</center></th>
        <th><center>Slot No.</center></th>
        <th><center>Current Rank</center></th>
        <th><center>Personal RANK-PV</center></th>
        <th><center>Group RANK-PV</center></th>
        <th><center>Next Rank Qualified</center></th>
      </tr>
    </thead>
    <tbody>
      @foreach($_slot as $slot)
        <tr>
          <td><center>{{$slot->first_name}} {{$slot->middle_name}} {{$slot->last_name}}</center></td>
          <td><center><a href="" class='underline'><span>{{$slot->slot_no}}</span></a></center></td>
          <td><center>{{$slot->stairstep_name ? $slot->stairstep_name : '---'}}</center></td>
          <td style="{{$slot->rank_personal_points >= App\Globals\Mlm_member::get_next_rank($shop_id,$slot->slot_id,'stairstep_required_pv') ? 'color:green' : 'color:red' }}"><center>{{$slot->rank_personal_points}} of {{App\Globals\Mlm_member::get_next_rank($shop_id,$slot->slot_id,'stairstep_required_pv')}}</center></td>
          @if($include_rpv_on_rgpv == 1)
            <td style="{{($slot->rank_group_points + $slot->rank_personal_points) >= App\Globals\Mlm_member::get_next_rank($shop_id,$slot->slot_id,'stairstep_required_gv') ? 'color:green' : 'color:red' }}"><center>{{$slot->rank_group_points + $slot->rank_personal_points}} of {{App\Globals\Mlm_member::get_next_rank($shop_id,$slot->slot_id,'stairstep_required_gv')}}</center></td>
            <td><center><input type="checkbox" name="name1" disabled  {{$slot->rank_personal_points >= App\Globals\Mlm_member::get_next_rank($shop_id,$slot->slot_id,'stairstep_required_pv') && ($slot->rank_group_points + $slot->rank_personal_points) >= App\Globals\Mlm_member::get_next_rank($shop_id,$slot->slot_id,'stairstep_required_gv') && App\Globals\Mlm_member::rank_count_leg($shop_id,$slot->slot_id) >= $slot->stairstep_leg_id ? 'checked' : ''}} />&nbsp;</center></td>
          @else
            <td style="{{$slot->rank_group_points >= App\Globals\Mlm_member::get_next_rank($shop_id,$slot->slot_id,'stairstep_required_gv') ? 'color:green' : 'color:red' }}"><center>{{$slot->rank_group_points}} of {{App\Globals\Mlm_member::get_next_rank($shop_id,$slot->slot_id,'stairstep_required_gv')}}</center></td>
            <td><center><input type="checkbox" name="name1" disabled  {{$slot->rank_personal_points >= App\Globals\Mlm_member::get_next_rank($shop_id,$slot->slot_id,'stairstep_required_pv') && $slot->rank_group_points >= App\Globals\Mlm_member::get_next_rank($shop_id,$slot->slot_id,'stairstep_required_gv') && App\Globals\Mlm_member::rank_count_leg($shop_id,$slot->slot_id) >= $slot->stairstep_leg_id ? 'checked' : ''}} />&nbsp;</center></td>
          @endif
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
</div>
</div>

<div id="classModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="classInfo" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> 
        </button>
      
      </div>
      <div class="modal-body">
        <table class="table table-bordered table-condensed">
          <thead>
            <tr>
              <th><center>Date Updated</center></th>
              <th><center>No of Slots Updated</center></th>
            </tr>
          </thead>
          <tbody>
            @foreach($_history as $history)
              <tr>
                <td><center>{{Carbon\Carbon::parse($history->date_created)->format('F d, Y')}}</center></td>
                <td><center><a data-toggle="modal" href="#modal1" rank_update_date="Rank Update for {{Carbon\Carbon::parse($history->date_created)->format('F d, Y')}}" rank_update_id="{{$history->rank_update_id}}" class='underline view_rank_update'><span>{{$history->total_slots}}</span></a></center></td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">
          Close
        </button>
      </div>
    </div>
  </div>
</div>

<div id="modal1" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="classInfo" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
          
        </button>
        <h4 class="modal-title view_rank_title" id="classModalLabel">
         Rank Update for October 15,2016
       </h4>
     </div>
     <div class="modal-body view_rank_update_modal">

    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-primary" data-dismiss="modal">
        Close
      </button>
    </div>
  </div>
</div>
</div>

<div id="classModal2" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="classInfo" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">

        </button>
        <h4 class="modal-title" id="classModalLabel">
          Points log
        </h4>
      </div>
      <div class="modal-body">
        <table class="table table-bordered table-condensed">
          <thead>
            <tr>
              <th><center>Slot No.</center></th>
              <th><center>Points</center></th>
              <th><center>Type</center></th>
              <th><center>Date received</center></th>
              <th><center>Edit date</center></th>
            </tr>
          </thead>
          <tbody>
            <form method="POST" class="date_edit_form">
              <input type="hidden" name="_token" value="{{csrf_token()}}">
              @foreach($points_log as $log)
                <tr>
                  <td><center>{{$log->slot_no}}</center></td>
                  <td><center>{{$log->points_log_points}}</center></td>
                  <td><center>{{$log->points_log_type}}</center></td>
                  <td><center>{{Carbon\Carbon::parse($log->points_log_date_claimed)->format("M d, Y g:i:s A")}}</center></td>
                  <td><center><input type="text" name="edit_date[{{$log->points_log_id}}]" class="datepicker form-control input-sm start_date" name="start_date" value=""/></center></td>
                </tr>
              @endforeach
            </form>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">
          Close
        </button>
        <button type="button" class="btn btn-primary submit_edit_date_btn">
          Submit
        </button>
      </div>
    </div>
  </div>
</div>

@endsection

<style>
  div button {
    float: right;
    margin-right:10px;

  }

  #classModal {}

  .modal-body {
    overflow-x: auto;
  }


  a.underline {
    text-decoration: none;
  }
  a.underline span {
    display: inline-block;
    border-bottom: 1px solid blue;
    font-size: 15px;
    line-height: 12px;
  }

  th {
    white-space: nowrap;
  }

  td{
    white-space: nowrap;

  }
  
  .page-title{
    color: skyblue !important;   
  }
  #button1{
    border-color:#36a6fd;
    background: white !important; 
    color: skyblue !important; 
  }
  #button2{
    border-color:#36a6fd;
    background: white !important; 
    color: skyblue !important; 
  }

  #button3{
    border-color:#36a6fd;
    background: white !important; 
    color: skyblue !important; 
  }

</style>
<style> 

  .stylish-input-group .input-group-addon{
    background: white !important; 

  }
  .stylish-input-group .form-control{
    border-color:#ccc;
  }
  .stylish-input-group button{
    border:0;
    background:transparent;
  }

  .datepicker{
    border-color:#ccc;
  }

</style>

@section('script')
<script type="text/javascript">
// alert(123);
$("#progressbar > div").css({ 'background': 'LightYellow' });

var total_slots = 0;
$(".view_rank_update").click(function()
{
  $(".modal-loader").removeClass("hidden");
  $(".view_rank_title").text($(this).attr("rank_update_date"));
  var rank_update_id = $(this).attr("rank_update_id");
  var link                    = "/member/mlm/rank/update/view_rank_update?id="+rank_update_id;
  $(".view_rank_update_modal").load(link,function()
  {
    $(".modal-loader").addClass("hidden");
  });
});

$(".submit_edit_date_btn").click(function(){
  $(".date_edit_form").submit();
});



$(".update_ranking").click(function()
{
  $(".update_rank_panel").css("display","block");
});


$("#stairstep_status").val("----");

$(".distribute_stairstep").click(function()
{
    initial();
    $(".distribute_stairstep").remove();
});

function initial()
{
    var link             = "/member/mlm/rank/update/start";
    $("#stairstep_status").val("Initializing...");

    $.ajax(
    {
        url:link,
        dataType:"json",
        data: {_token: $(".token").val()},
        type:"post",
        success: function(data)
        {
            if(data.status == "Success")
            {
                total_slots        = data.total_slots;
                var slot_id        = data.slot_id;
                var rank_update_id = data.rank_update_id;
                compute(slot_id,rank_update_id,data.slot_no);
            }
            else
            {
                alert(data.status);
            }
        }
    });
}

function compute($slot_id,$rank_update_id,$slot_no,$current_count = 1)
{
    var link             = "/member/mlm/rank/update/start/compute";
    $("#stairstep_status").val("Distributing points on slot #"+$slot_no);

    $.ajax(
    {
        url:link,
        dataType:"json",
        data: {_token: $(".token").val(),slot_id:$slot_id, rank_update_id:$rank_update_id, current_count:$current_count},
        type:"post",
        success: function(data)
        {
            $( "#progressbar" ).progressbar(
            {
              value: (data.current_count/total_slots) * 100
            });
            if(data.status == "Success")
            {
                $slot_id       = data.slot_id;
                // alert($slot_id);
                compute($slot_id,$rank_update_id,data.slot_no,data.current_count);
            }
            else if(data.status == "Complete")
            {
                alert("Rank Update completed (Please refresh to see the updates).");
                $("#stairstep_status").val("Rank Update completed");
            }
        },
        error: function()
        {
            setTimeout(function()
            {
                // alert(123);
               compute($slot_id,$rank_update_id);
            }, 2000);
        }
    });  
}
</script>
@endsection

