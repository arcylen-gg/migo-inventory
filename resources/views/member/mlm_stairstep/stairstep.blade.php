@extends('member.layout')
@section('content')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Stairstep</span>
                <small>
                    Distribute Stairstep
                </small>
            </h1>
        </div>
    </div> 
</div>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="row">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <div class="col-md-6">
                    <label>Start Date</label>
                    <input type="text" class="datepicker form-control input-sm start_date" name="start_date" value=""/>
            </div>            
            <div class="col-md-6">
                    <label>End Date</label>
                    <input type="text" class="datepicker form-control input-sm end_date" name="end_date" value=""/>
            </div>
<!--             <div class="col-md-6">
                <label for="number_of_sponsor">Slot #</label>
                <input class="form-control" id="slot_number" value="----" disabled>
            </div>
            <div class="col-md-6">
                <label for="number_of_sponsor">Number of slots</label>
                <input class="form-control" id="slot_count" value="----" disabled>
            </div> -->
            <div class="col-md-6">
                <label for="rematrix_status">Stairstep Status</label>
                <input class="form-control" id="stairstep_status" value="----" disabled>
            </div>
            <div class="col-md-12" style="min-height: 10px;"></div>
            <div class="col-md-2 pull-right">
                <button class="form-control btn-custom-primary distribute_stairstep">Distribute</button>
            </div>
        </div>
    </div>
</div>
@endsection
<style type="text/css">

</style>
@section('script')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
$("#stairstep_status").val("----");

$(".distribute_stairstep").click(function()
{
    initial();
    $(".distribute_stairstep").remove();
});

function initial()
{
    var link             = "/member/mlm/stairstep_compute/start";
    $("#stairstep_status").val("Initializing...");
    $start_date          = $(".start_date").val();
    $end_date            = $(".end_date").val();

    $.ajax(
    {
        url:link,
        dataType:"json",
        data: {_token: $(".token").val(),start_date:$start_date,end_date:$end_date},
        type:"post",
        success: function(data)
        {
            if(data.status == "Success")
            {
                var slot_id    = data.slot_id;
                var start      = data.start_date;
                var end        = data.end_date;
                var distribute = data.distribute_id;
                compute(slot_id,start,end,distribute);
            }
            else
            {
                alert(data.status);
            }
        }
    });
}

function compute($slot_id,$start,$end,$distribute)
{
    var link             = "/member/mlm/stairstep_compute/start/compute";
    $("#stairstep_status").val("Distributing points on slot #"+$slot_id);
    $start_date          = $(".start_date").val();
    $end_date            = $(".end_date").val();

    $.ajax(
    {
        url:link,
        dataType:"json",
        data: {_token: $(".token").val(),slot_id:$slot_id, start_date:$start, end_date:$end, distribute_id:$distribute},
        type:"post",
        success: function(data)
        {
            if(data.status == "Success")
            {
                $slot_id = data.slot_id;
                // alert($slot_id);
                compute($slot_id,$start,$end,$distribute);
            }
            else if(data.status == "Complete")
            {
                alert("Distributing points completed.");
            }
        },
        error: function()
        {
            setTimeout(function()
            {
                // alert(123);
               compute($slot_id,$start,$end,$distribute);
            }, 2000);
        }
    });  
}

</script>
@endsection