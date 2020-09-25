@extends('member.layout')
@section('content')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Auto Entry</span>
                <small>
                    Auto Entry of slot
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
                <label for="number_of_sponsor">Number of slots</label>
                <input class="form-control" id="slot_count" value="{{$slot_count}}" hidden_count="{{$slot_count}}" disabled>
            </div>
            <div class="col-md-6">
                <label for="rematrix_status">Auto Entry Status</label>
                <input class="form-control" id="auto_entry_status" value="----" disabled>
            </div>
            <div class="col-md-6">
                <label for="rematrix_status">Slot Owner</label>
                <select class="form-control slot_owner" name="slot_owner">
                    @foreach($_customer as $customer)
                        <option value="{{$customer->customer_id}}">{{$customer->first_name}} {{$customer->middle_name}} {{$customer->last_name}} </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label for="rematrix_status">Slot Sponsor</label>
                <select class="form-control slot_sponsor" name="slot_sponsor">
                    @foreach($_slot as $slot)
                        <option value="{{$slot->slot_id}}"> {{$slot->slot_no}} </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label for="rematrix_status">Membership</label>
                <select class="form-control slot_membership" name="slot_membership">
                    @foreach($_membership as $membership)
                        <option value="{{$membership->membership_id}}"> {{$membership->membership_name}} </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label for="rematrix_status">How many slots?</label>
                <input type="text" class="form-control request_amount" value=1>
            </div>
            <div class="col-md-6">
                <label for="rematrix_status">Slot Status</label>
                <select class="form-control slot_status" name="slot_status">
                    <option value="PS">PS</option>
                    <option value="CD">CD</option>
                    <option value="FS">FS</option>
                </select>
            </div>
            <div class="col-md-12" style="min-height: 10px;"></div>
            <div class="col-md-2 pull-right">
                <button class="form-control btn-custom-primary apply_auto_entry">Apply Auto Entry</button>
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
    $("#auto_entry_status").val("----");
    $("#slot_count").val($("#slot_count").attr("hidden_count"));
    var count = 1;

    $(".apply_auto_entry").click(function()
    {
        $("#auto_entry_status").val("Loading....");

        var slot_sponsor     = $(".slot_sponsor").val();     
        var slot_owner       = $(".slot_owner").val();   
        var slot_membership  = $(".slot_membership").val();        
        var slot_status      = $(".slot_status").val();    

        var request_amount    = $(".request_amount").val();

        auto_entry(slot_sponsor,slot_owner,slot_membership,slot_status,request_amount);
    });


    function auto_entry($slot_sponsor,$slot_owner,$slot_membership,$slot_status,$request_amount)
    {
        var link             = "/member/developer/auto_entry/instant_add_slot";
        $("#auto_entry_status").val("Number of slot created: "+count);
        $.ajax(
        {
            url:link,
            dataType:"json",
            data: {_token: $(".token").val(),slot_sponsor: $slot_sponsor, slot_owner: $slot_owner,slot_membership: $slot_membership, slot_status : $slot_status},
            type:"post",
            success: function(data)
            {
                if($request_amount > count)
                {
                    auto_entry($slot_sponsor,$slot_owner,$slot_membership,$slot_status,$request_amount);
                    $("#slot_count").val(parseInt($("#slot_count").val()) + 1);
                }
                else
                {
                    $("#slot_count").val(parseInt($("#slot_count").val()) + 1);
                    $("#auto_entry_status").val("Process complete");
                    $("#auto_entry_status").val("Number of slot created: "+count+" (Process complete)");
                }
                count++;
            },
            error: function()
            {
                setTimeout(function()
                {
                    auto_entry($slot_sponsor,$slot_owner,$slot_membership,$slot_status,$request_amount);
                }, 2000);
            }
        });
    }


</script>
@endsection