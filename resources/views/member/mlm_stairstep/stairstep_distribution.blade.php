@extends('member.layout')
@section('content')

<form method="post">
  <div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
      <div>
        <i class="fa fa-dropbox" aria-hidden="true"></i>
        <h1>
          <span class="page-title">Stair Step Distribution <i class="fa fa-angle-double-right"></i> Distribution</span>
          <small>
            Distribute Stair Step
          </small>
        </h1>
        <button type="button" id="button1" class="submit_btn_distribute btn btn-sm"><span><i class="fa fa-exchange" aria-hidden="true"></i></span>  Distribute Stair Step</button>
        <button type="button" id="button2" class="btn btn-sm" data-toggle="modal" data-target="#classModal"><span><i class="fa fa-history" aria-hidden="true"></i></span>  Distribute History</button>
      </div>
    </div>
  </div>
</form>
@if (Session::has('Error'))
   <div class="alert alert-info">{{ Session::get('Error') }}</div>
@endif
@if (Session::has('Success'))
   <div class="alert success-info">{{ Session::get('Success') }}</div>
@endif
<div class="panel panel-default panel-block panel-title-block" id="top">
  <div class="panel-heading">
  <div class="row">
    <form method="POST" id="distribute_form" action="/member/mlm/stairstep/distribution/submit">   
      <div class="col-md-3">
        <div class="input-group stylish-input-group pull-right">
          <input type="hidden" name="_token" value="{{csrf_token()}}">
          <input type="text" name="start_date" value="{{$start}}" class="form-control pull-right datepicker start_picker">
          <span class="input-group-addon">
            <button type="submit">
              <i class="fa fa-calendar fa-lg datepicker" aria-hidden="true"></i>
            </button>  
          </span>
        </div>
      </div>
      <div class="col-md-3">
        <div class="input-group stylish-input-group pull-right">
          <input type="text" class="form-control pull-right datepicker end_picker" value="{{$end}}" name="end_date">
          <span class="input-group-addon">
            <button type="submit">
              <i class="fa fa-calendar fa-lg datepicker" aria-hidden="true"></i>
            </button>  
          </span>
        </div>
      </div>
    </form>
      <div class="col-md-3 pull-right">
        <div class="input-group stylish-input-group pull-right">
          <input type="text" class="form-control pull-right search" value="{{ Request::input('search') }}" name="search" placeholder="Search" >
          <span class="input-group-addon">
            <button type="button">
              <i class="fa fa-search" aria-hidden="true"></i>
            </button>  
          </span>
        </div>
      </div>
  </div>
  <br>        
  <table class="table table-bordered table-condensed body_distribute">
    <thead>
      <tr>
        <th><center>Customer Name</center></th>
        <th><center>Slot No.</center></th>
        <th><center>Current Rank</center></th>
        <th><center>Current Personal-PV</center></th>
        <th><center>Required Personal-PV</center></th>
        <th><center>Stair Step Points</center></th>
        <th><center>Stair Rebates Bonus</center></th>
        <th><center>Comission</center></th>
      </tr>
    </thead>
    <tbody>
    @foreach($_slot as $slot)
      <tr>
        <td><center>{{$slot->first_name}} {{$slot->middle_name}} {{$slot->last_name}}</center></td>
        <td><center>{{$slot->slot_no}}</center></td>
        <td><center>{{$slot->stairstep_name ? $slot->stairstep_name : "---"}}</center></td>
        <td><center>{{$slot->personal_stairstep}}</center></td>
        <td><center>{{$slot->stairstep_pv_maintenance ? $slot->stairstep_pv_maintenance : "0"}}</center></td>
        <td><center>{{$slot->stairstep_points}}</center></td>
        <td><center>{{$slot->stairstep_rebates_bonus}}</center></td>
        <td><center>{{$slot->personal_stairstep >= $slot->stairstep_pv_maintenance && $slot->commission_multiplier != 0 ? ($slot->stairstep_points * $slot->commission_multiplier) + $slot->stairstep_rebates_bonus: 0}}</center></td>
      </tr>
    @endforeach
    </tbody>
  </table>
  <div class="clearfix">
    <div class="pull-right">
    {!! $_slot->render() !!}
    </div>
  </div>
</div>
</div>
</div>
<div id="classModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="classInfo" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">

        </button>
        <h4 class="modal-title" id="classModalLabel">
          Distribute History
        </h4>
      </div>
      <div class="modal-body">
        <table class="table table-bordered table-condensed">
          <thead>
            <tr>
              <th><center>Date Start</center></th>
              <th><center>Date End</center></th>
              <th><center>Date Processed</center></th>
              <th><center>Slots Processed</center></th>
              <th><center>Status</center></th>
              <th><center></center></th>
            </tr>
          </thead>
          <tbody>
            @foreach($_history as $history)
              <tr>
                <td><center>{{Carbon\Carbon::parse($history->stairstep_distribute_start_date)->format("M d, Y")}}</center></td>
                <td><center>{{Carbon\Carbon::parse($history->stairstep_distribute_end_date)->format("M d, Y")}}</center></td>
                <td><center>{{Carbon\Carbon::parse($history->date_created)->format("M d, Y g:i:s A")}}</center></td>
                <td style="{{$history->total_processed_slot >= $history->total_slot ? 'color:green' : 'color:red'}}"><center>{{$history->total_processed_slot}} out of {{$history->total_slot}}</center></td>
                <td><center>{{$history->complete == 1 ? "Complete":"Incomplete"}}</center></td>
                <td><center><a data-toggle="modal" summary_date_title="{{Carbon\Carbon::parse($history->stairstep_distribute_start_date)->format("M d, Y")}} to {{Carbon\Carbon::parse($history->stairstep_distribute_end_date)->format("M d, Y")}}" href="#modal1" class='underline view_summary' stairstep_id="{{$history->stairstep_distribute_id}}"><span>View Summary</span></a></center></td>
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
          <h4 class="modal-title view_summary_date" id="classModalLabel">
           <!-- October 29,2016 to November 15,2017 -->
         </h4>
       </div>
        <div class="modal-body view_summary_modal">

        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">
          Close
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

  $('.start_picker').keypress(function(e)
  {
      if ( e.which == 13 ) return false;
      //or...
      if ( e.which == 13 ) e.preventDefault();
  });
  
  $('.end_picker').keypress(function(e)
  {
      if ( e.which == 13 ) return false;
      //or...
      if ( e.which == 13 ) e.preventDefault();
  });
  $(".submit_btn_distribute").click(function() 
  {
    $("#distribute_form").submit();
  });

  $(".view_summary").click(function()
  {
    $(".modal-loader").removeClass("hidden");
    $(".view_summary_date").text($(this).attr("summary_date_title"));
    var stairstep_distribute_id = $(this).attr("stairstep_id");
    var link                    = "/member/mlm/stairstep/view_summary?id="+stairstep_distribute_id;
    $(".view_summary_modal").load(link,function()
    {
      $(".modal-loader").addClass("hidden");
    });
  });

  $(".start_picker").change(function()
  {
    $(".modal-loader").removeClass("hidden");
    $(".submit_btn_distribute").attr("disabled", "disabled");
    var start_date = $(".start_picker").val();
    var end_date   = $(".end_picker").val();
    
    var link       = "/member/mlm/stairstep/distribution?start="+start_date+"&end="+end_date+" .body_distribute";

    $(".body_distribute").load(link,function()
    {
      $(".modal-loader").addClass("hidden");
      $(".submit_btn_distribute").removeAttr("disabled");
    });
  }); 

  $(".end_picker").change(function()
  {
    $(".modal-loader").removeClass("hidden");
    $(".submit_btn_distribute").attr("disabled", "disabled");
    var start_date = $(".start_picker").val();
    var end_date   = $(".end_picker").val();

    var link       = "/member/mlm/stairstep/distribution?start="+start_date+"&end="+end_date+" .body_distribute";
    $(".body_distribute").load(link,function()
    {
      $(".modal-loader").addClass("hidden");
      $(".submit_btn_distribute").removeAttr("disabled");
    });
  });
  $('.search').on('keydown',function(e)
  {
    var keyCode = e.keyCode || e.which;
    if (keyCode === 13) 
    { 

      // e.preventDefault();
      // return false;

      var link = '/member/mlm/stairstep/distribution?search='+$('.search').val()+"&start="+$(".start_picker").val()+"&end="+$(".end_picker").val();
      window.location.href= link;
      console.log(link);
      // $('.body_distribute').load(link,function()
      // {
      //   console.log("search = "+$('.search').val());
      // });
    }
    else
    {
      console.log(keyCode);
    }
  });
</script>
@endsection

